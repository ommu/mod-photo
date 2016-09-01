<?php
/**
 * AlbumPhoto
 * version: 0.1.4
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link https://github.com/oMMu/Ommu-Photo-Albums
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
 * This is the model class for table "ommu_album_photo".
 *
 * The followings are the available columns in table 'ommu_album_photo':
 * @property string $media_id
 * @property integer $publish
 * @property string $album_id
 * @property integer $orders
 * @property integer $cover
 * @property string $media
 * @property string $title
 * @property string $description
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 *
 * The followings are the available model relations:
 * @property OmmuAlbums $album
 */
class AlbumPhoto extends CActiveRecord
{
	public $defaultColumns = array();
	public $tag;
	public $old_media;
	
	// Variable Search
	public $album_search;
	public $photo_info_search;
	public $creation_search;
	public $modified_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AlbumPhoto the static model class
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
		return 'ommu_album_photo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('album_id', 'required'),
			array('title, description', 'required', 'on'=>'photoInfoRequired'),
			array('publish, orders, cover, creation_id, modified_id', 'numerical', 'integerOnly'=>true),
			array('album_id, creation_id, modified_id', 'length', 'max'=>11),
			//array('media', 'file', 'types' => 'jpg, jpeg, png, gif', 'allowEmpty' => true),
			array('cover, media, title, description,
				tag, old_media', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('media_id, publish, album_id, orders, cover, media, title, description, creation_date, creation_id, modified_date, modified_id,
				album_search, photo_info_search, creation_search', 'safe', 'on'=>'search'),
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
			'view' => array(self::BELONGS_TO, 'ViewAlbumPhoto', 'media_id'),
			'album' => array(self::BELONGS_TO, 'Albums', 'album_id'),
			'creation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'media_id' => Yii::t('attribute', 'Media'),
			'publish' => Yii::t('attribute', 'Publish'),
			'album_id' => Yii::t('attribute', 'Album'),
			'orders' => Yii::t('attribute', 'Orders'),
			'cover' => Yii::t('attribute', 'Cover'),
			'media' => Yii::t('attribute', 'Photo'),
			'title' => Yii::t('attribute', 'Title'),
			'description' => Yii::t('attribute', 'Description'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'tag' => Yii::t('attribute', 'Tag'),
			'old_media' => Yii::t('attribute', 'Old Photo'),
			'album_search' => Yii::t('attribute', 'Album'),
			'photo_info_search' => Yii::t('attribute', 'Photo Info'),
			'creation_search' => Yii::t('attribute', 'Creation'),
			'modified_search' => Yii::t('attribute', 'Modified'),
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

		$criteria->compare('t.media_id',$this->media_id);
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
		if(isset($_GET['album']))
			$criteria->compare('t.album_id',$_GET['album']);
		else
			$criteria->compare('t.album_id',$this->album_id);
		$criteria->compare('t.orders',$this->orders);
		$criteria->compare('t.cover',$this->cover);
		$criteria->compare('t.media',$this->media,true);
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.description',$this->description,true);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.creation_date)',date('Y-m-d', strtotime($this->creation_date)));
		if(isset($_GET['creation']))
			$criteria->compare('t.creation_id',$_GET['creation']);
		else
			$criteria->compare('t.creation_id',$this->creation_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.modified_date)',date('Y-m-d', strtotime($this->modified_date)));
		if(isset($_GET['modified']))
			$criteria->compare('t.modified_id',$_GET['modified']);
		else
			$criteria->compare('t.modified_id',$this->modified_id);
		
		// Custom Search
		$criteria->with = array(
			'view' => array(
				'alias'=>'view',
			),
			'album' => array(
				'alias'=>'album',
				'select'=>'title'
			),
			'creation' => array(
				'alias'=>'creation',
				'select'=>'displayname'
			),
			'modified' => array(
				'alias'=>'modified',
				'select'=>'displayname'
			),
		);
		$criteria->compare('album.title',strtolower($this->album_search), true);
		$criteria->compare('view.photo_info',strtolower($this->photo_info_search), true);
		$criteria->compare('creation.displayname',strtolower($this->creation_search), true);
		$criteria->compare('modified.displayname',strtolower($this->modified_search), true);

		if(!isset($_GET['AlbumPhoto_sort']))
			$criteria->order = 't.media_id DESC';

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
			//$this->defaultColumns[] = 'media_id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'album_id';
			$this->defaultColumns[] = 'orders';
			$this->defaultColumns[] = 'cover';
			$this->defaultColumns[] = 'media';
			$this->defaultColumns[] = 'title';
			$this->defaultColumns[] = 'description';
			$this->defaultColumns[] = 'creation_date';
			$this->defaultColumns[] = 'creation_id';
			$this->defaultColumns[] = 'modified_date';
			$this->defaultColumns[] = 'modified_id';
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
			if(!isset($_GET['album'])) {
				$this->defaultColumns[] = array(
					'name' => 'album_search',
					'value' => '$data->album->title."<br/><span>".Utility::shortText(Utility::hardDecode($data->album->body),150)."</span>"',
					'htmlOptions' => array(
						'class' => 'bold',
					),
					'type' => 'raw',
				);
			}
			$this->defaultColumns[] = array(
				'name' => 'media',
				'value' => 'CHtml::link($data->media, Yii::app()->request->baseUrl.\'/public/album/\'.$data->album_id.\'/\'.$data->media, array(\'target\' => \'_blank\'))',
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'creation_search',
				'value' => '$data->creation_id != 0 ? $data->creation->displayname : "-"',
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
			$this->defaultColumns[] = array(
				'name' => 'photo_info_search',
				'value' => '$data->view->photo_info == 1 ? Chtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : Chtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter'=>array(
					1=>Yii::t('phrase', 'Yes'),
					0=>Yii::t('phrase', 'No'),
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'cover',
				'value' => '$data->cover == 1 ? Chtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : Chtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter'=>array(
					1=>Yii::t('phrase', 'Yes'),
					0=>Yii::t('phrase', 'No'),
				),
				'type' => 'raw',
			);
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
	 * get photo product
	 */
	public static function getPhoto($id, $type=null) {
		if($type == null) {
			$model = self::model()->findAll(array(
				'condition' => 'album_id = :id',
				'params' => array(
					':id' => $id,
				),
			));
		} else {
			$model = self::model()->findAll(array(
				'condition' => 'album_id = :id AND cover = :cover',
				'params' => array(
					':id' => $id,
					':cover' => $type,
				),
			));
		}

		return $model;
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() {
		if(parent::beforeValidate()) {
			$media = CUploadedFile::getInstance($this, 'media');
			if(!Yii::app()->request->isAjaxRequest && $media->name != '') {
				$extension = pathinfo($media->name, PATHINFO_EXTENSION);
				if(!in_array(strtolower($extension), array('bmp','gif','jpg','png')))
					$this->addError('media', 'The file "'.$media->name.'" cannot be uploaded. Only files with these extensions are allowed: bmp, gif, jpg, png.');
			}
			
			if($this->isNewRecord)
				$this->creation_id = Yii::app()->user->id;
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() {
		if(parent::beforeSave()) {			
			//Update album photo
			$controller = strtolower(Yii::app()->controller->id);
			if(!Yii::app()->request->isAjaxRequest && !$this->isNewRecord && $controller == 'o/photo') {
				$album_path = "public/album/".$this->album_id;
				
				$this->media = CUploadedFile::getInstance($this, 'media');
				if($this->media instanceOf CUploadedFile) {
					$fileName = time().'_'.$this->album_id.'_'.Utility::getUrlTitle($this->album->title).'.'.strtolower($this->media->extensionName);
					if($this->media->saveAs($album_path.'/'.$fileName)) {
						if($this->old_media != '' && file_exists($album_path.'/'.$this->old_media))
							rename($album_path.'/'.$this->old_media, 'public/album/verwijderen/'.$this->album_id.'_'.$this->old_media);
						$this->media = $fileName;
					}
				}
				
				if($this->media == '') {
					$this->media = $this->old_media;
				}
			}
		}
		return true;	
	}
	
	/**
	 * After save attributes
	 */
	protected function afterSave() {
		parent::afterSave();

		//set flex cover in album
		//if($this->cover == 1 || count(self::getPhoto($this->album_id)) == 1) {
		if($this->cover == 1) {
			$cover = Albums::model()->findByPk($this->album_id);
			$cover->media_id = $this->media_id;
			$cover->update();
		}		
		
		$setting = AlbumSetting::getInfo('photo_limit, photo_resize, photo_resize_size', 'many');
		$photo_limit = $setting->photo_limit;
		$photo_resize = $setting->photo_resize;
		$photo_resize_size = $setting->photo_resize_size;
		
		if($this->album->cat->default_setting == 0) {
			$photo_limit = $this->album->cat->photo_limit;
			$photo_resize = $this->album->cat->photo_resize;
			$photo_resize_size = $this->album->cat->photo_resize_size;			
		}
		
		//create thumb image
		if($photo_resize == 1) {
			Yii::import('ext.phpthumb.PhpThumbFactory');
			$album_path = 'public/album/'.$this->album_id;
			$albumImg = PhpThumbFactory::create($album_path.'/'.$this->media, array('jpegQuality' => 90, 'correctPermissions' => true));
			$resizeSize = unserialize($photo_resize_size);
			if($resizeSize['height'] == 0)
				$albumImg->resize($resizeSize['width']);
			else
				$albumImg->adaptiveResize($resizeSize['width'], $resizeSize['height']);				
			$albumImg->save($album_path.'/'.$this->media);
		}
			
		//delete other photo (if photo_limit = 1)
		if($photo_limit == 1) {
			self::model()->deleteAll(array(
				'condition'=> 'album_id = :id AND cover = :cover',
				'params'=>array(
					':id'=>$this->album_id,
					':cover'=>0,
				),
			));
		}
	}

	/**
	 * After delete attributes
	 */
	protected function afterDelete() {
		parent::afterDelete();
		//delete album image
		$album_path = "public/album/".$this->album_id;
		if($this->media != '' && file_exists($album_path.'/'.$this->media))
			rename($album_path.'/'.$this->media, 'public/album/verwijderen/'.$this->album_id.'_'.$this->media);

		//reset cover in album
		$data = self::getPhoto($this->album_id);
		if($data != null) {
			if($this->cover == 1) {				
				$photo = self::model()->findByPk($data[0]->media_id);
				$photo->cover = 1;
				if($photo->update()) {
					$cover = albums::model()->findByPk($this->album_id);
					$cover->media_id = $photo->media_id;
					$cover->update();
				}
			}
		}
	}

}