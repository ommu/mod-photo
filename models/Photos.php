<?php
/**
 * Photos
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2020 OMMU (www.ommu.id)
 * @created date 6 January 2020, 01:24 WIB
 * @link https://github.com/ommu/mod-photo
 *
 * This is the model class for table "ommu_photos".
 *
 * The followings are the available columns in table "ommu_photos":
 * @property integer $id
 * @property integer $publish
 * @property integer $album_id
 * @property integer $member_id
 * @property integer $user_id
 * @property string $photo
 * @property string $title
 * @property string $caption
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property PhotoAlbum $album
 * @property Members $member
 * @property Users $user
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\album\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\UploadedFile;
use thamtech\uuid\helpers\UuidHelper;
use app\models\Users;
use ommu\member\models\Members;

class Photos extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = ['title', 'caption', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date'];

	public $old_photo;
	public $albumTitle;
	public $memberDisplayname;
	public $creationDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_photos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['member_id', 'user_id'], 'required'],
			[['publish', 'album_id', 'member_id', 'user_id', 'creation_id', 'modified_id'], 'integer'],
			[['title', 'caption'], 'string'],
			[['album_id', 'photo', 'title', 'caption'], 'safe'],
			[['album_id'], 'exist', 'skipOnError' => true, 'targetClass' => PhotoAlbum::className(), 'targetAttribute' => ['album_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'publish' => Yii::t('app', 'Publish'),
			'album_id' => Yii::t('app', 'Album'),
			'member_id' => Yii::t('app', 'Member'),
			'user_id' => Yii::t('app', 'User'),
			'photo' => Yii::t('app', 'Photo'),
			'title' => Yii::t('app', 'Title'),
			'caption' => Yii::t('app', 'Caption'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'old_photo' => Yii::t('app', 'Old Photo'),
			'albumTitle' => Yii::t('app', 'Album'),
			'memberDisplayname' => Yii::t('app', 'Member'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAlbum()
	{
		return $this->hasOne(PhotoAlbum::className(), ['id' => 'album_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMember()
	{
		return $this->hasOne(Members::className(), ['member_id' => 'member_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\album\models\query\Photos the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\album\models\query\Photos(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

        if (!(Yii::$app instanceof \app\components\Application)) {
            return;
        }

        if (!$this->hasMethod('search')) {
            return;
        }

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['memberDisplayname'] = [
			'attribute' => 'memberDisplayname',
			'value' => function($model, $key, $index, $column) {
                $memberDisplayname = isset($model->member) ? $model->member->displayname : '-';
                $userDisplayname = isset($model->user) ? $model->user->displayname : '-';
                if ($userDisplayname != '-' && $memberDisplayname != $userDisplayname) {
                    return $memberDisplayname.'<br/>'.$userDisplayname;
                }
                return $memberDisplayname;
				// return $model->memberDisplayname;
			},
            'format' => 'html',
			'visible' => !Yii::$app->request->get('member') ? true : false,
		];
		$this->templateColumns['albumTitle'] = [
			'attribute' => 'albumTitle',
			'value' => function($model, $key, $index, $column) {
				return isset($model->album) ? $model->album->title : '-';
				// return $model->albumTitle;
			},
			'visible' => !Yii::$app->request->get('album') ? true : false,
		];
		$this->templateColumns['photo'] = [
			'attribute' => 'photo',
			'value' => function($model, $key, $index, $column) {
				$uploadPath = join('/', [self::getUploadPath(false), $model->member_id]);
				return $model->photo ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->photo])), ['alt'=>$model->photo]) : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['title'] = [
			'attribute' => 'title',
			'value' => function($model, $key, $index, $column) {
				return $model->title;
			},
		];
		$this->templateColumns['caption'] = [
			'attribute' => 'caption',
			'value' => function($model, $key, $index, $column) {
				return $model->caption;
			},
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		$this->templateColumns['creationDisplayname'] = [
			'attribute' => 'creationDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->creation) ? $model->creation->displayname : '-';
				// return $model->creationDisplayname;
			},
			'visible' => !Yii::$app->request->get('creation') ? true : false,
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		$this->templateColumns['modifiedDisplayname'] = [
			'attribute' => 'modifiedDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->modified) ? $model->modified->displayname : '-';
				// return $model->modifiedDisplayname;
			},
			'visible' => !Yii::$app->request->get('modified') ? true : false,
		];
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['publish', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->publish);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'text-center'],
			'format' => 'raw',
			'visible' => !Yii::$app->request->get('trash') ? true : false,
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
        if ($column != null) {
            $model = self::find();
            if (is_array($column)) {
                $model->select($column);
            } else {
                $model->select([$column]);
            }
            $model = $model->where(['id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}

	/**
	 * @param returnAlias set true jika ingin kembaliannya path alias atau false jika ingin string
	 * relative path. default true.
	 */
	public static function getUploadPath($returnAlias=true)
	{
		return ($returnAlias ? Yii::getAlias('@public/album') : 'album');
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->old_photo = $this->photo;
		// $this->albumTitle = isset($this->album) ? $this->album->title : '-';
		// $this->memberDisplayname = isset($this->member) ? $this->member->displayname : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
			// $this->photo = UploadedFile::getInstance($this, 'photo');
            if ($this->photo instanceof UploadedFile && !$this->photo->getHasError()) {
				$photoFileType = ['jpg', 'jpeg', 'png', 'bmp', 'gif'];
                if (!in_array(strtolower($this->photo->getExtension()), $photoFileType)) {
					$this->addError('photo', Yii::t('app', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}', [
						'name'=>$this->photo->name,
						'extensions'=>$this->formatFileType($photoFileType, false),
					]));
				}
			} /* else {
                if ($this->isNewRecord || (!$this->isNewRecord && $this->old_photo == '')) {
                    $this->addError('photo', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('photo')]));
                }
			} */

            if ($this->isNewRecord) {
                if ($this->user_id == null) {
                    $this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }

                if ($this->creation_id == null) {
                    $this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            } else {
                if ($this->modified_id == null) {
                    $this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }
        }
        return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
        if (parent::beforeSave($insert)) {
            $uploadPath = join('/', [self::getUploadPath(), $this->member_id]);
            $verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);
            $this->createUploadDirectory(self::getUploadPath(), $this->member_id);

            // $this->photo = UploadedFile::getInstance($this, 'photo');
            if ($this->photo instanceof UploadedFile && !$this->photo->getHasError()) {
                $fileName = join('-', [time(), UuidHelper::uuid()]).'.'.strtolower($this->photo->getExtension());
                if ($this->photo->saveAs(join('/', [$uploadPath, $fileName]))) {
                    if (!$insert && $this->old_photo != '' && file_exists(join('/', [$uploadPath, $this->old_photo]))) {
                        rename(join('/', [$uploadPath, $this->old_photo]), join('/', [$verwijderenPath, $this->id.'-'.time().'_change_'.$this->old_photo]));
                    }
                    $this->photo = $fileName;
                }
            } else {
                if (!$insert && $this->photo == '') {
                    $this->photo = $this->old_photo;
                }
            }
        }
        return true;
	}

	/**
	 * After delete attributes
	 */
	public function afterDelete()
	{
        parent::afterDelete();

        $uploadPath = join('/', [self::getUploadPath(), $this->member_id]);
        $verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);

        if ($this->photo != '' && file_exists(join('/', [$uploadPath, $this->photo]))) {
            rename(join('/', [$uploadPath, $this->photo]), join('/', [$verwijderenPath, $this->id.'-'.time().'_deleted_'.$this->photo]));
        }

	}
}
