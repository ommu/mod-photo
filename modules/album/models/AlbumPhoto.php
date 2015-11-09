<?php
/**
 * AlbumPhoto * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
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
 * This is the model class for table "ommu_album_photo".
 *
 * The followings are the available columns in table 'ommu_album_photo':
 * @property string $media_id
 * @property integer $publish
 * @property string $album_id
 * @property integer $orders
 * @property integer $cover
 * @property string $media
 * @property string $desc
 * @property string $creation_date
 *
 * The followings are the available model relations:
 * @property OmmuAlbums $album
 */
class AlbumPhoto extends CActiveRecord
{
	public $defaultColumns = array();
	public $old_media;
	
	// Variable Search
	public $album_search;

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
			array('publish, orders, cover', 'numerical', 'integerOnly'=>true),
			array('album_id', 'length', 'max'=>11),
			array('media,
				old_media', 'length', 'max'=>128),
			//array('media', 'file', 'types' => 'jpg, jpeg, png, gif', 'allowEmpty' => true),
			array('cover, media, desc, creation_date,
				old_media', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('media_id, publish, album_id, orders, cover, media, desc, creation_date,
				album_search', 'safe', 'on'=>'search'),
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
			'album' => array(self::BELONGS_TO, 'Albums', 'album_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'media_id' => 'Media',
			'publish' => 'Publish',
			'album_id' => 'Album',
			'orders' => 'Orders',
			'cover' => 'Cover',
			'media' => 'Photo',
			'desc' => 'Desc',
			'creation_date' => 'Creation Date',
			'album_search' => 'Album Search',
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

		$criteria->compare('t.media_id',$this->media_id,true);
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
		if(isset($_GET['album'])) {
			$criteria->compare('t.album_id',$_GET['album']);
		} else {
			$criteria->compare('t.album_id',$this->album_id);
		}
		$criteria->compare('t.orders',$this->orders);
		$criteria->compare('t.cover',$this->cover);
		$criteria->compare('t.media',$this->media,true);
		$criteria->compare('t.desc',$this->desc,true);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.creation_date)',date('Y-m-d', strtotime($this->creation_date)));
		
		// Custom Search
		$criteria->with = array(
			'album' => array(
				'alias'=>'album',
				'select'=>'title'
			),
		);
		$criteria->compare('album.title',strtolower($this->album_search), true);

		if(!isset($_GET['AlbumPhoto_sort']))
			//$criteria->order = 'media_id DESC';

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
			$this->defaultColumns[] = 'desc';
			$this->defaultColumns[] = 'creation_date';
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
				'name' => 'cover',
				'value' => '$data->cover == 1 ? Chtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : Chtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter'=>array(
					1=>Phrase::trans(588,0),
					0=>Phrase::trans(589,0),
				),
				'type' => 'raw',
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
				//'select' => 'album_id, orders, media',
				'condition' => 'album_id = :id',
				'params' => array(
					':id' => $id,
				),
				//'limit' => 20,
			));
		} else {
			$model = self::model()->findAll(array(
				//'select' => 'album_id, orders, media',
				'condition' => 'album_id = :id AND cover = :cover',
				'params' => array(
					':id' => $id,
					':cover' => $type,
				),
				//'limit' => 20,
				//'order'=> 'orders ASC',
			));
		}

		return $model;
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() {
		if(parent::beforeValidate()) {	
			$currentAction = strtolower(Yii::app()->controller->id.'/'.Yii::app()->controller->action->id);
			$media = CUploadedFile::getInstance($this, 'media');
			if($currentAction != 'photo/ajaxadd' && $media->name != '') {
				$extension = pathinfo($media->name, PATHINFO_EXTENSION);
				if(!in_array($extension, array('bmp','gif','jpg','png')))
					$this->addError('media', 'The file "'.$media->name.'" cannot be uploaded. Only files with these extensions are allowed: bmp, gif, jpg, png.');
			}
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
			if(!$this->isNewRecord && $controller == 'photo' && !Yii::app()->request->isAjaxRequest) {
				$album_path = "public/album/".$this->album_id;
				
				$this->media = CUploadedFile::getInstance($this, 'media');
				if($this->media instanceOf CUploadedFile) {
					$fileName = time().'_'.$this->album_id.'.'.strtolower($this->media->extensionName);
					if($this->media->saveAs($album_path.'/'.$fileName)) {
						if($this->old_media != '')
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
		
		$setting = AlbumSetting::getInfo('photo_limit', 'many');
		
		//create thumb image
		/* if($setting->media_resize == 1) {
			Yii::import('ext.phpthumb.PhpThumbFactory');
			$album_path = "public/album/".$this->album_id;
			$albumImg = PhpThumbFactory::create($album_path.'/'.$this->media, array('jpegQuality' => 90, 'correctPermissions' => true));
			$resizeSize = explode(',', $setting->media_resize_size);
			if($resizeSize[1] == 0)
				$albumImg->resize($resizeSize[0]);
			else
				$albumImg->adaptiveResize($resizeSize[0], $resizeSize[1]);
				
			$albumImg->save($album_path.'/'.$this->media);
		} */
		
		//delete other media (if photo_limit = 1)
		if($setting->photo_limit == 1) {
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
		if($this->media != '')
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