<?php
/**
 * Albums * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link http://company.ommu.co
 * @contact (+62)856-299-4114
 *
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 *
 * --------------------------------------------------------------------------------------
 *
 * This is the model class for table "ommu_albums".
 *
 * The followings are the available columns in table 'ommu_albums':
 * @property string $album_id
 * @property integer $publish
 * @property string $user_id
 * @property string $media_id
 * @property integer $headline
 * @property integer $comment_code
 * @property string $title
 * @property string $body
 * @property string $quote
 * @property integer $photos
 * @property integer $comment
 * @property integer $view
 * @property integer $likes
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 *
 * The followings are the available model relations:
 * @property OmmuAlbumLikes[] $ommuAlbumLikes
 * @property OmmuAlbumPhoto[] $ommuAlbumPhotos
 */
class Albums extends CActiveRecord
{
	public $defaultColumns = array();
	public $media;
	public $old_media;
	
	// Variable Search
	public $user_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Albums the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ommu_albums';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'required'),
			array('publish, headline, comment_code, photos, comment, view, likes', 'numerical', 'integerOnly'=>true),
			array('user_id, media_id', 'length', 'max'=>11),
			array('title,
				media, old_media', 'length', 'max'=>128),
			//array('media', 'file', 'types' => 'jpg, jpeg, png, gif', 'allowEmpty' => true),
			array('media_id, title, body, quote, creation_date, creation_id, modified_date, modified_id,
				media, old_media', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('album_id, publish, user_id, media_id, headline, comment_code, title, body, quote, photos, comment, view, likes, creation_date, creation_id, modified_date, modified_id,
				user_search', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'likes' => array(self::HAS_MANY, 'AlbumLikes', 'album_id'),
			'photo' => array(self::HAS_MANY, 'AlbumPhoto', 'album_id'),
			'cover' => array(self::BELONGS_TO, 'AlbumPhoto', 'media_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'album_id' => 'Album',
			'publish' => Phrase::trans(24010,1),
			'user_id' => 'User',
			'media_id' => 'Photo',
			'headline' => 'Headline',
			'comment_code' => Phrase::trans(24009,1),
			'title' => 'Title',
			'body' => 'Body',
			'quote' => 'Quote',
			'photos' => 'Photos',
			'comment' => 'Comment',
			'view' => 'View',
			'likes' => 'Likes',
			'creation_date' => 'Creation Date',
			'creation_id' => 'Creation ID',
			'modified_date' => 'Modified Date',
			'modified_id' => 'Modified ID',
			'user_search' => 'User',
			'media' => 'Photo',
			'old_media' => 'Old Photo',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('t.album_id',$this->album_id,true);
		if(isset($_GET['type']) && $_GET['type'] == 'publish') {
			$criteria->compare('t.publish',1);
		} elseif(isset($_GET['type']) && $_GET['type'] == 'unpublish') {
			$criteria->compare('t.publish',0);
		} elseif(isset($_GET['type']) && $_GET['type'] == 'trash') {
			$criteria->compare('t.publish',2);
		} else {
			$criteria->addInCondition('t.publish',array(0,1));
			$criteria->compare('t.publish',$this->publish);
		}
		if(isset($_GET['user'])) {
			$criteria->compare('t.user_id',$_GET['user']);
		} else {
			$criteria->compare('t.user_id',$this->user_id);
		}
		$criteria->compare('t.media_id',$this->media_id);
		$criteria->compare('t.headline',$this->headline);
		$criteria->compare('t.comment_code',$this->comment_code);
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.body',$this->body,true);
		$criteria->compare('t.quote',$this->quote,true);
		$criteria->compare('t.photos',$this->photos);
		$criteria->compare('t.comment',$this->comment);
		$criteria->compare('t.view',$this->view);
		$criteria->compare('t.likes',$this->likes);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.creation_date)',date('Y-m-d', strtotime($this->creation_date)));
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.modified_date)',date('Y-m-d', strtotime($this->modified_date)));
		
		// Custom Search
		$criteria->with = array(
			'user' => array(
				'alias'=>'user',
				'select'=>'displayname'
			),
		);
		$criteria->compare('user.displayname',strtolower($this->user_search), true);

		if(!isset($_GET['Albums_sort']))
			$criteria->order = 'album_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>30,
			),
		));
	}


	/**
	 * Get column for CGrid View
	 */
	public function getGridColumn($columns=null) {
		if($columns !== null) {
			foreach($columns as $val) {
				/*
				if(trim($val) == 'enabled') {
					$this->defaultColumns[] = array(
						'name'  => 'enabled',
						'value' => '$data->enabled == 1? "Ya": "Tidak"',
					);
				}
				*/
				$this->defaultColumns[] = $val;
			}
		} else {
			//$this->defaultColumns[] = 'album_id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'user_id';
			$this->defaultColumns[] = 'media_id';
			$this->defaultColumns[] = 'headline';
			$this->defaultColumns[] = 'comment_code';
			$this->defaultColumns[] = 'title';
			$this->defaultColumns[] = 'body';
			$this->defaultColumns[] = 'quote';
			$this->defaultColumns[] = 'photos';
			$this->defaultColumns[] = 'comment';
			$this->defaultColumns[] = 'view';
			$this->defaultColumns[] = 'likes';
			$this->defaultColumns[] = 'creation_date';
			$this->defaultColumns[] = 'modified_date';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {
			/*
			$this->defaultColumns[] = array(
				'class' => 'CCheckBoxColumn',
				'name' => 'id',
				'selectableRows' => 2,
				'checkBoxHtmlOptions' => array('name' => 'trash_id[]')
			);
			*/
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			$this->defaultColumns[] = array(
				'name' => 'title',
				'value' => '$data->title."<br/><span>".Utility::shortText(Utility::hardDecode($data->body),200)."</span>"',
				'htmlOptions' => array(
					'class' => 'bold',
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'user_search',
				'value' => '$data->user->displayname',
			);
			$this->defaultColumns[] = array(
				'name' => 'creation_date',
				'value' => 'Utility::dateFormat($data->creation_date)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'creation_date',
					'language' => 'ja',
					'i18nScriptFile' => 'jquery.ui.datepicker-en.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'creation_date_filter',
					),
					'options'=>array(
						'showOn' => 'focus',
						'dateFormat' => 'dd-mm-yy',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
			);
			if(!isset($_GET['type'])) {
				$this->defaultColumns[] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("publish",array("id"=>$data->album_id)), $data->publish, 1)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter'=>array(
						1=>Phrase::trans(588,0),
						0=>Phrase::trans(589,0),
					),
					'type' => 'raw',
				);
			}
			if(!isset($_GET['type'])) {
				$this->defaultColumns[] = array(
					'name' => 'headline',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("headline",array("id"=>$data->album_id)), $data->headline, 1)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter'=>array(
						1=>Phrase::trans(588,0),
						0=>Phrase::trans(589,0),
					),
					'type' => 'raw',
				);
			}
		}
		parent::afterConstruct();
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk($id,array(
				'select' => $column
			));
			return $model->$column;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;			
		}
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() {
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				$this->user_id = Yii::app()->user->id;
				$this->creation_id = Yii::app()->user->id;	
			} else {				
				$this->modified_id = Yii::app()->user->id;	
			}
			if($this->headline == 1 && $this->publish == 0) {
				$this->addError('publish', Phrase::trans(340,0));
			}
			
			$media = CUploadedFile::getInstance($this, 'media');
			if($media->name != '') {
				$extension = pathinfo($media->name, PATHINFO_EXTENSION);
				if(!in_array($extension, array('bmp','gif','jpg','png')))
					$this->addError('media', 'The file "'.$media->name.'" cannot be uploaded. Only files with these extensions are allowed: bmp, gif, jpg, png.');
			}
		}
		return true;
	}
	
	/**
	 * After save attributes
	 */
	protected function afterSave() {
		parent::afterSave();
		if($this->isNewRecord) {
			// Add album directory
			$album_path = "public/album/".$this->album_id;
			if(!file_exists($album_path)) {
				@mkdir($album_path, 0777, true);

				// Add file in album directory (index.php)
				$newFile = $album_path.'/index.php';
				$FileHandle = fopen($newFile, 'w');
			}

			$this->media = CUploadedFile::getInstance($this, 'media');
			if($this->media instanceOf CUploadedFile) {
				$fileName = time().'_'.$this->album_id.'.'.strtolower($this->media->extensionName);
				if($this->media->saveAs($album_path.'/'.$fileName)) {
					$images = new AlbumPhoto;
					$images->album_id = $this->album_id;
					$images->cover = '1';
					$images->media = $fileName;
					$images->save();
				}
			}
		}
		
		if(AlbumSetting::getInfo('headline') == 1) {
			if($this->headline == 1) {
				self::model()->updateAll(array(
					'headline' => 0,	
				), array(
					'condition'=> 'album_id != :id',
					'params'=>array(':id'=>$this->album_id),
				));
			}
		}
	}

	/**
	 * Before delete attributes
	 */
	protected function beforeDelete() {
		if(parent::beforeDelete()) {
			$album_path = "public/album/".$this->album_id;
			
			//delete media photos
			$album_photo = AlbumPhoto::getPhoto($this->album_id);
			foreach($album_photo as $val) {
				if($val->media != '')
					rename($album_path.'/'.$val->media, 'public/album/verwijderen/'.$val->album_id.'_'.$val->media);
			}
		}
		return true;			
	}

	/**
	 * After delete attributes
	 */
	protected function afterDelete() {
		parent::afterDelete();
		//delete album image
		$album_path = "public/album/".$this->album_id;
		Utility::deleteFolder($album_path);		
	}

}