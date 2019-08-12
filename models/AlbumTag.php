<?php
/**
 * AlbumTag
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
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
 * This is the model class for table "ommu_album_tag".
 *
 * The followings are the available columns in table 'ommu_album_tag':
 * @property string $id
 * @property string $album_id
 * @property string $tag_id
 * @property string $creation_date
 * @property string $creation_id
 *
 * The followings are the available model relations:
 * @property Albums $album
 */
class AlbumTag extends CActiveRecord
{
	use UtilityTrait;
	use GridViewTrait;

	public $defaultColumns = array();
	public $tag_i;
	
	// Variable Search
	public $category_search;
	public $album_search;
	public $tag_search;
	public $creation_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AlbumTag the static model class
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
		return 'ommu_album_tag';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('album_id, tag_id', 'required'),
			array('album_id, tag_id, creation_id', 'length', 'max'=>11),
			array('
				tag_i', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, album_id, tag_id, creation_date, creation_id,
				category_search, album_search, tag_search, creation_search', 'safe', 'on'=>'search'),
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
			'tag' => array(self::BELONGS_TO, 'OmmuTags', 'tag_id'),
			'creation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('attribute', 'ID'),
			'album_id' => Yii::t('attribute', 'Album'),
			'tag_id' => Yii::t('attribute', 'Tag'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'category_search' => Yii::t('attribute', 'Category'),
			'album_search' => Yii::t('attribute', 'Album'),
			'tag_search' => Yii::t('attribute', 'Tag'),
			'creation_search' => Yii::t('attribute', 'Creation'),
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
			'album' => array(
				'alias' => 'album',
				'select' => 'publish, cat_id, title'
			),
			'tag' => array(
				'alias' => 'tag',
				'select' => 'body'
			),
			'creation' => array(
				'alias' => 'creation',
				'select' => 'displayname'
			),
		);

		$criteria->compare('t.id', $this->id);
		if(Yii::app()->getRequest()->getParam('album'))
			$criteria->compare('t.album_id',Yii::app()->getRequest()->getParam('album'));
		else
			$criteria->compare('t.album_id', $this->album_id);
		if(Yii::app()->getRequest()->getParam('tag'))
			$criteria->compare('t.tag_id',Yii::app()->getRequest()->getParam('tag'));
		else
			$criteria->compare('t.tag_id', $this->tag_id);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.creation_date)', date('Y-m-d', strtotime($this->creation_date)));
		if(Yii::app()->getRequest()->getParam('creation'))
			$criteria->compare('t.creation_id',Yii::app()->getRequest()->getParam('creation'));
		else
			$criteria->compare('t.creation_id', $this->creation_id);
		
		$criteria->compare('album.cat_id', $this->category_search);
		$criteria->compare('album.title', strtolower($this->album_search), true);
		if(Yii::app()->getRequest()->getParam('album') && Yii::app()->getRequest()->getParam('publish'))
			$criteria->compare('album.publish',Yii::app()->getRequest()->getParam('publish'));
		$criteria->compare('tag.body', strtolower($this->tag_search), true);
		$criteria->compare('creation.displayname', strtolower($this->creation_search), true);

		if(!Yii::app()->getRequest()->getParam('AlbumTag_sort'))
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
			$this->defaultColumns[] = 'album_id';
			$this->defaultColumns[] = 'tag_id';
			$this->defaultColumns[] = 'creation_date';
			$this->defaultColumns[] = 'creation_id';
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
			if(!Yii::app()->getRequest()->getParam('album')) {
				$this->defaultColumns[] = array(
					'name' => 'category_search',
					'value' => 'Phrase::trans($data->album->category->name)',
					'filter' => AlbumCategory::getCategory(),
					'type' => 'raw',
				);
				$this->defaultColumns[] = array(
					'name' => 'album_search',
					'value' => '$data->album->title',
				);
			}
			$this->defaultColumns[] = array(
				'name' => 'tag_search',
				'value' => '$data->tag->body',
			);
			$this->defaultColumns[] = array(
				'name' => 'creation_search',
				'value' => '$data->creation->displayname',
			);
			$this->defaultColumns[] = array(
				'name' => 'creation_date',
				'value' => 'Yii::app()->dateFormatter->formatDateTime($data->creation_date, \'medium\', false)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'creation_date'),
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
			$model = self::model()->findByPk($id, array(
				'select' => $column,
			));
			if(count(explode(',', $column)) == 1)
				return $model->$column;
			else
				return $model;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;
		}
	}

	/**
	 * get album tag
	 */
	public static function getKeyword($keyword, $tags) 
	{
		if(empty($tags))
			return $keyword;
		
		else {
			$tag = array();
			foreach($tags as $val)
				$tag[] = $val->tag->body;
				
			$implodeTag = Utility::formatFileType($tag, false);
			return $keyword.', '.$implodeTag;
		}
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() {
		if(parent::beforeValidate()) {
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
			if($this->isNewRecord) {
				$tag_i = $this->urlTitle($this->tag_i);
				if($this->tag_id == 0) {
					$tag = OmmuTags::model()->find(array(
						'select' => 'tag_id, body',
						'condition' => 'body = :body',
						'params' => array(
							':body' => $tag_i,
						),
					));
					if($tag != null)
						$this->tag_id = $tag->tag_id;
					else {
						$data = new OmmuTags;
						$data->body = $this->tag_i;
						if($data->save())
							$this->tag_id = $data->tag_id;
					}					
				}
			}
		}
		return true;
	}

}