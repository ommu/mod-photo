<?php
/**
 * AlbumPhoto
 * version: 0.1.4
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/Photo-Albums
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
 * @property string $caption
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
	public $keyword_i;
	public $old_media_i;
	
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
			array('caption', 'required', 'on'=>'photoInfoRequired'),
			array('publish, orders, cover, creation_id, modified_id', 'numerical', 'integerOnly'=>true),
			array('album_id, creation_id, modified_id', 'length', 'max'=>11),
			//array('media', 'file', 'types' => 'jpg, jpeg, png, gif', 'allowEmpty' => true),
			array('cover, media, caption,
				keyword_i, old_media_i', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('media_id, publish, album_id, orders, cover, media, caption, creation_date, creation_id, modified_date, modified_id,
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
			'tags' => array(self::HAS_MANY, 'AlbumPhotoTag', 'media_id'),
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
			'caption' => Yii::t('attribute', 'Caption'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'keyword_i' => Yii::t('attribute', 'Tags'),
			'old_media_i' => Yii::t('attribute', 'Old Photo'),
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
		$criteria->compare('t.caption',$this->caption,true);
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
			$this->defaultColumns[] = 'caption';
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
				'filter' => Yii::app()->controller->widget('application.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'creation_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
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
			if(!isset($_GET['type'])) {
				$this->defaultColumns[] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("publish",array("id"=>$data->media_id)), $data->publish, 1)',
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
	 * Resize Photo
	 */
	public static function resizePhoto($photo, $size) {
		Yii::import('ext.phpthumb.PhpThumbFactory');
		$resizePhoto = PhpThumbFactory::create($photo, array('jpegQuality' => 90, 'correctPermissions' => true));
		if($size['height'] == 0)
			$resizePhoto->resize($size['width']);
		else			
			$resizePhoto->adaptiveResize($size['width'], $size['height']);
		
		$resizePhoto->save($photo);
		
		return true;
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() 
	{
		$controller = strtolower(Yii::app()->controller->id);
		$currentAction = strtolower(Yii::app()->controller->id.'/'.Yii::app()->controller->action->id);
		
		if(parent::beforeValidate()) 
		{
			if($this->isNewRecord)
				$this->creation_id = Yii::app()->user->id;
			else
				$this->modified_id = Yii::app()->user->id;
			
			if($currentAction != 'o/admin/insertcover') {
				$media = CUploadedFile::getInstance($this, 'media');
				if($media != null) {
					$extension = pathinfo($media->name, PATHINFO_EXTENSION);
					if(!in_array(strtolower($extension), array('bmp','gif','jpg','png')))
						$this->addError('media', Yii::t('phrase', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}.', array(
							'{name}'=>$media->name,
							'{extensions}'=>'bmp, gif, jpg, png.',
						)));
					
				} else {
					if($this->isNewRecord && $controller == 'o/photo')
						$this->addError('media', 'Media (Photo) cannot be blank.');				
				}
			}
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() 
	{
		$controller = strtolower(Yii::app()->controller->id);
		$currentAction = strtolower(Yii::app()->controller->id.'/'.Yii::app()->controller->action->id);
		
		if(parent::beforeSave()) {
			$album_path = "public/album/".$this->album_id;
			
			// Add directory
			if(!file_exists($album_path)) {
				@mkdir($album_path, 0755, true);

				// Add file in directory (index.php)
				$newFile = $album_path.'/index.php';
				$FileHandle = fopen($newFile, 'w');
			} else
				@chmod($album_path, 0755, true);

			//Update album photo
			if(in_array($currentAction, array('o/photo/add','o/photo/edit'))) {
				$this->media = CUploadedFile::getInstance($this, 'media');
				if($this->media != null) {
					if($this->media instanceOf CUploadedFile) {
						$fileName = time().'_'.Utility::getUrlTitle($this->album->title).'.'.strtolower($this->media->extensionName);
						if($this->media->saveAs($album_path.'/'.$fileName)) {
							if(!$this->isNewRecord) {
								if($this->old_media_i != '' && file_exists($album_path.'/'.$this->old_media_i))
									rename($album_path.'/'.$this->old_media_i, 'public/album/verwijderen/'.$this->album_id.'_'.$this->old_media_i);
							}
							$this->media = $fileName;
						}
					}
				} else {
					if(!$this->isNewRecord && $this->media == '')
						$this->media = $this->old_media_i;
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
		
		$setting = AlbumSetting::model()->findByPk(1, array(
			'select' => 'photo_limit, photo_resize, photo_resize_size',
		));		
		$photo_limit = $setting->photo_limit;
		$photo_resize = $setting->photo_resize;
		$photo_resize_size = $setting->photo_resize_size;
		
		if($this->album->category->default_setting == 0) {
			$photo_limit = $this->album->category->photo_limit;
			$photo_resize = $this->album->category->photo_resize;
			$photo_resize_size = $this->album->category->photo_resize_size;			
		}
		$photo_resize_size = unserialize($photo_resize_size);
		
		$album_path = 'public/album/'.$this->album_id;
		
		// Add directory
		if(!file_exists($album_path)) {
			@mkdir($album_path, 0755, true);

			// Add file in directory (index.php)
			$newFile = $album_path.'/index.php';
			$FileHandle = fopen($newFile, 'w');
		} else
			@chmod($album_path, 0755, true);
		
		//resize cover after upload
		if($photo_resize == 1 && $this->media != '')
			self::resizePhoto($album_path.'/'.$this->media, $photo_resize_size);
			
		//delete other media (if media_limit = 1)
		if($photo_limit == 1) {
			$photos = self::model()->findAll(array(
				'condition'=> 'media_id <> :media AND album_id = :album',
				'params'=>array(
					':media'=>$this->media_id,
					':album'=>$this->album_id,
				),
			));
			if($photos != null) {
				foreach($photos as $key => $val)
					self::model()->findByPk($val->media_id)->delete();
			}
		}
		
		//update if new cover (cover = 1)
		if($this->cover == 1)
			self::model()->updateAll(array('cover'=>0), 'media_id <> :media AND album_id = :album', array(':media'=>$this->media_id,':album'=>$this->album_id));
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
		$photos = $this->album->photos;
		if($photos != null && $this->cover == 1)
			self::model()->updateByPk($photos[0]->media_id, array('cover'=>1));
	}

}