<?php
/**
 * AlbumSetting
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/mod-photo
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
 * This is the model class for table "ommu_album_setting".
 *
 * The followings are the available columns in table 'ommu_album_setting':
 * @property integer $id
 * @property string $license
 * @property integer $permission
 * @property string $meta_keyword
 * @property string $meta_description
 * @property string $gridview_column
 * @property integer $headline
 * @property integer $headline_limit
 * @property string $headline_category
 * @property integer $photo_limit 
 * @property integer $photo_resize 
 * @property string $photo_resize_size
 * @property string $photo_view_size
 * @property string $photo_file_type
 * @property string $modified_date
 * @property string $modified_id
 */
class AlbumSetting extends CActiveRecord
{
	public $defaultColumns = array();
	
	// Variable Search
	public $modified_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AlbumSetting the static model class
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
		return 'ommu_album_setting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('license, permission, meta_keyword, meta_description, gridview_column, headline, headline_limit, photo_limit, photo_resize, photo_file_type', 'required'),
			array('permission, headline, headline_limit, photo_limit, photo_resize, modified_id', 'numerical', 'integerOnly'=>true),
			array('license', 'length', 'max'=>32),
			array('headline_limit', 'length', 'max'=>3),
			array('headline_category, photo_resize_size, photo_view_size, photo_file_type', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, license, permission, meta_keyword, meta_description, gridview_column, headline, headline_limit, headline_category, photo_limit, photo_resize, photo_resize_size, photo_view_size, photo_file_type, modified_date, modified_id,
				modified_search', 'safe', 'on'=>'search'),
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
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('attribute', 'ID'),
			'license' => Yii::t('attribute', 'License Key'),
			'permission' => Yii::t('attribute', 'Public Permission Defaults'),
			'meta_keyword' => Yii::t('attribute', 'Meta Keyword'),
			'meta_description' => Yii::t('attribute', 'Meta Description'),
			'gridview_column' => Yii::t('attribute', 'Gridview Column'),
			'headline' => Yii::t('attribute', 'Headline'),
			'headline_limit' => Yii::t('attribute', 'Headline Limit'),
			'headline_category' => Yii::t('attribute', 'Headline Category'),
			'photo_limit' => Yii::t('attribute', 'Photo Limit'),
			'photo_resize' => Yii::t('attribute', 'Photo Resize'),
			'photo_resize_size' => Yii::t('attribute', 'Photo Resize Size'),
			'photo_view_size' => Yii::t('attribute', 'Photo View Size'),
			'photo_file_type' => Yii::t('attribute', 'Photo File Type'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
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
			'modified' => array(
				'alias'=>'modified',
				'select'=>'displayname',
			),
		);

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.license',$this->license,true);
		$criteria->compare('t.permission',$this->permission);
		$criteria->compare('t.meta_keyword',$this->meta_keyword,true);
		$criteria->compare('t.meta_description',$this->meta_description,true);
		$criteria->compare('t.gridview_column',$this->gridview_column,true);
		$criteria->compare('t.headline',$this->headline);
		$criteria->compare('t.headline_limit',$this->headline_limit);
		$criteria->compare('t.headline_category',$this->headline_category,true);
		$criteria->compare('t.photo_limit',$this->photo_limit);
		$criteria->compare('t.photo_resize',$this->photo_resize);
		$criteria->compare('t.photo_resize_size',$this->photo_resize_size,true);
		$criteria->compare('t.photo_view_size',$this->photo_view_size,true);
		$criteria->compare('t.photo_file_type',$this->photo_file_type,true);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.modified_date)',date('Y-m-d', strtotime($this->modified_date)));
		if(Yii::app()->getRequest()->getParam('modified'))
			$criteria->compare('t.modified_id',Yii::app()->getRequest()->getParam('modified'));
		else
			$criteria->compare('t.modified_id',$this->modified_id);
		
		$criteria->compare('modified.displayname',strtolower($this->modified_search),true);

		if(!isset($_GET['AlbumSetting_sort']))
			$criteria->order = 't.id DESC';

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
			//$this->defaultColumns[] = 'id';
			$this->defaultColumns[] = 'license';
			$this->defaultColumns[] = 'permission';
			$this->defaultColumns[] = 'meta_keyword';
			$this->defaultColumns[] = 'meta_description';
			$this->defaultColumns[] = 'gridview_column';
			$this->defaultColumns[] = 'headline';
			$this->defaultColumns[] = 'headline_limit';
			$this->defaultColumns[] = 'headline_category';
			$this->defaultColumns[] = 'photo_limit';
			$this->defaultColumns[] = 'photo_resize';
			$this->defaultColumns[] = 'photo_resize_size';
			$this->defaultColumns[] = 'photo_view_size';
			$this->defaultColumns[] = 'photo_file_type';
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
			$this->defaultColumns[] = 'license';
			$this->defaultColumns[] = 'permission';
			$this->defaultColumns[] = 'meta_keyword';
			$this->defaultColumns[] = 'meta_description';
			$this->defaultColumns[] = 'gridview_column';
			$this->defaultColumns[] = 'headline';
			$this->defaultColumns[] = 'headline_limit';
			$this->defaultColumns[] = 'headline_category';
			$this->defaultColumns[] = 'photo_limit';
			$this->defaultColumns[] = 'photo_resize';
			$this->defaultColumns[] = 'photo_resize_size';
			$this->defaultColumns[] = 'photo_view_size';
			$this->defaultColumns[] = 'photo_file_type';
			$this->defaultColumns[] = 'modified_date';
			$this->defaultColumns[] = array(
				'name' => 'modified_search',
				'value' => '$data->modified->displayname',
			);
		}
		parent::afterConstruct();
	}

	/**
	 * Albums get information
	 */
	public static function getInfo($column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk(1,array(
				'select' => $column,
			));
			if(count(explode(',', $column)) == 1)
				return $model->$column;
			else
				return $model;
			
		} else {
			$model = self::model()->findByPk(1);
			return $model;
		}
	}

	/**
	 * User get information
	 */
	public static function getHeadlineCategory()
	{
		$setting = self::model()->findByPk(1, array(
			'select' => 'headline_category',
		));
		
		$headline_category = unserialize($setting->headline_category);
		if(empty($headline_category))
			$headline_category = array();
		
		return $headline_category;		
	}

	/**
	 * get Module License
	 */
	public static function getLicense($source='1234567890', $length=16, $char=4)
	{
		$mod = $length%$char;
		if($mod == 0)
			$sep = ($length/$char);
		else
			$sep = (int)($length/$char)+1;
		
		$sourceLength = strlen($source);
		$random = '';
		for ($i = 0; $i < $length; $i++)
			$random .= $source[rand(0, $sourceLength - 1)];
		
		$license = '';
		for ($i = 0; $i < $sep; $i++) {
			if($i != $sep-1)
				$license .= substr($random,($i*$char),$char).'-';
			else
				$license .= substr($random,($i*$char),$char);
		}

		return $license;
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() {
		if(parent::beforeValidate()) {			
			$this->modified_id = Yii::app()->user->id;
			
			if($this->headline == 1) {
				if($this->headline_limit != '' && $this->headline_limit <= 0)
					$this->addError('headline_limit', Yii::t('phrase', 'Headline Limit lebih besar dari 0'));
				if($this->headline_category == '')
					$this->addError('headline_category', Yii::t('phrase', 'Headline Category cannot be blank.'));
			}
			
			if($this->photo_limit != '' && $this->photo_limit <= 1)
				$this->addError('photo_limit', Yii::t('phrase', 'Photo Limit lebih besar dari 1'));
			
			if($this->photo_resize == 1 && ($this->photo_resize_size['width'] == '' || $this->photo_resize_size['height'] == ''))
				$this->addError('photo_resize_size', Yii::t('phrase', 'Photo Resize cannot be blank.'));
			
			if($this->photo_view_size['large']['width'] == '' || $this->photo_view_size['large']['height'] == '')
				$this->addError('photo_view_size[large]', Yii::t('phrase', 'Large Size cannot be blank.'));
			
			if($this->photo_view_size['medium']['width'] == '' || $this->photo_view_size['medium']['height'] == '')
				$this->addError('photo_view_size[medium]', Yii::t('phrase', 'Medium Size cannot be blank.'));
			
			if($this->photo_view_size['small']['width'] == '' || $this->photo_view_size['small']['height'] == '')
				$this->addError('photo_view_size[small]', Yii::t('phrase', 'Small Size cannot be blank.'));
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() {
		if(parent::beforeSave()) {
			$this->gridview_column = serialize($this->gridview_column);
			$this->headline_category = serialize($this->headline_category);
			$this->photo_resize_size = serialize($this->photo_resize_size);
			$this->photo_view_size = serialize($this->photo_view_size);
			$this->photo_file_type = serialize(Utility::formatFileType($this->photo_file_type));
		}
		return true;
	}

}
