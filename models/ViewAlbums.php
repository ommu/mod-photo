<?php
/**
 * ViewAlbums
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-photo
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
 * This is the model class for table "_albums".
 *
 * The followings are the available columns in table '_albums':
 * @property string $album_id
 * @property string $album_cover
 * @property string $media_id
 * @property string $photos
 * @property string $photo_all
 * @property string $tags
 * @property string $photo_tags
 * @property string $views
 * @property string $view_all
 * @property string $likes
 * @property string $like_all
 */
class ViewAlbums extends CActiveRecord
{
	public $defaultColumns = array();

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewAlbums the static model class
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
		return '_albums';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'album_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('album_id, media_id', 'length', 'max'=>11),
			array('album_cover, photos, photo_all, tags, photo_tags, views, view_all, likes, like_all', 'length', 'max'=>21),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('album_id, album_cover, media_id, photos, photo_all, tags, photo_tags, views, view_all, likes, like_all', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'album_id' => Yii::t('attribute', 'Album'),
			'album_cover' => Yii::t('attribute', 'Cover'),
			'media_id' => Yii::t('attribute', 'Media'),
			'photos' => Yii::t('attribute', 'Photos'),
			'photo_all' => Yii::t('attribute', 'Photo All'),
			'tags' => Yii::t('attribute', 'Tags'),
			'photo_tags' => Yii::t('attribute', 'Photo Tags'),
			'views' => Yii::t('attribute', 'Views'),
			'view_all' => Yii::t('attribute', 'View All'),
			'likes' => Yii::t('attribute', 'Likes'),
			'like_all' => Yii::t('attribute', 'Like All'),
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

		$criteria->compare('t.album_id',$this->album_id);
		$criteria->compare('t.album_cover',strtolower($this->album_cover),true);
		$criteria->compare('t.media_id',$this->media_id);
		$criteria->compare('t.photos',$this->photos);
		$criteria->compare('t.photo_all',$this->photo_all);
		$criteria->compare('t.tags',$this->tags);
		$criteria->compare('t.photo_tags',$this->photo_tags);
		$criteria->compare('t.views',$this->views);
		$criteria->compare('t.view_all',$this->view_all);
		$criteria->compare('t.likes',$this->likes);
		$criteria->compare('t.like_all',$this->like_all);

		if(!isset($_GET['ViewAlbums_sort']))
			$criteria->order = 't.album_id DESC';

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
			$this->defaultColumns[] = 'album_id';
			$this->defaultColumns[] = 'album_cover';
			$this->defaultColumns[] = 'media_id';
			$this->defaultColumns[] = 'photos';
			$this->defaultColumns[] = 'photo_all';
			$this->defaultColumns[] = 'tags';
			$this->defaultColumns[] = 'photo_tags';
			$this->defaultColumns[] = 'views';
			$this->defaultColumns[] = 'view_all';
			$this->defaultColumns[] = 'likes';
			$this->defaultColumns[] = 'like_all';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			//$this->defaultColumns[] = 'album_id';
			$this->defaultColumns[] = 'album_cover';
			$this->defaultColumns[] = 'media_id';
			$this->defaultColumns[] = 'photos';
			$this->defaultColumns[] = 'photo_all';
			$this->defaultColumns[] = 'tags';
			$this->defaultColumns[] = 'photo_tags';
			$this->defaultColumns[] = 'views';
			$this->defaultColumns[] = 'view_all';
			$this->defaultColumns[] = 'likes';
			$this->defaultColumns[] = 'like_all';
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

}