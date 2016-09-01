<?php
/**
 * ViewAlbumStatistics
 * version: 0.1.4
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 1 September 2016, 09:23 WIB
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
 * This is the model class for table "_view_album_statistics".
 *
 * The followings are the available columns in table '_view_album_statistics':
 * @property string $date_key
 * @property string $album_insert
 * @property string $album_update
 * @property string $album_delete
 * @property string $album_likes
 * @property string $album_unlikes
 * @property string $album_photo_insert
 * @property string $album_photo_delete
 * @property string $album_views
 * @property string $album_setting_update
 * @property string $tag_insert_unique
 * @property string $tag_insert
 * @property string $tag_delete
 * @property string $category_insert
 * @property string $category_update
 * @property string $category_delete
 */
class ViewAlbumStatistics extends CActiveRecord
{
	public $defaultColumns = array();

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewAlbumStatistics the static model class
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
		return '_view_album_statistics';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'date_key';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('album_insert, album_update, album_delete, album_likes, album_unlikes, album_photo_insert, album_photo_delete, album_views, album_setting_update, tag_insert_unique, tag_insert, tag_delete, category_insert, category_update, category_delete', 'length', 'max'=>23),
			array('date_key', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('date_key, album_insert, album_update, album_delete, album_likes, album_unlikes, album_photo_insert, album_photo_delete, album_views, album_setting_update, tag_insert_unique, tag_insert, tag_delete, category_insert, category_update, category_delete', 'safe', 'on'=>'search'),
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
			'date_key' => Yii::t('attribute', 'Date Key'),
			'album_insert' => Yii::t('attribute', 'Album Insert'),
			'album_update' => Yii::t('attribute', 'Album Update'),
			'album_delete' => Yii::t('attribute', 'Album Delete'),
			'album_likes' => Yii::t('attribute', 'Album Likes'),
			'album_unlikes' => Yii::t('attribute', 'Album Unlikes'),
			'album_photo_insert' => Yii::t('attribute', 'Album Photo Insert'),
			'album_photo_delete' => Yii::t('attribute', 'Album Photo Delete'),
			'album_views' => Yii::t('attribute', 'Album Views'),
			'album_setting_update' => Yii::t('attribute', 'Album Setting Update'),
			'tag_insert_unique' => Yii::t('attribute', 'Tag Insert Unique'),
			'tag_insert' => Yii::t('attribute', 'Tag Insert'),
			'tag_delete' => Yii::t('attribute', 'Tag Delete'),
			'category_insert' => Yii::t('attribute', 'Category Insert'),
			'category_update' => Yii::t('attribute', 'Category Update'),
			'category_delete' => Yii::t('attribute', 'Category Delete'),
		);
		/*
			'Date Key' => 'Date Key',
			'Album Insert' => 'Album Insert',
			'Album Update' => 'Album Update',
			'Album Delete' => 'Album Delete',
			'Album Likes' => 'Album Likes',
			'Album Unlikes' => 'Album Unlikes',
			'Album Photo Insert' => 'Album Photo Insert',
			'Album Photo Delete' => 'Album Photo Delete',
			'Album Views' => 'Album Views',
			'Album Setting Update' => 'Album Setting Update',
			'Tag Insert Unique' => 'Tag Insert Unique',
			'Tag Insert' => 'Tag Insert',
			'Tag Delete' => 'Tag Delete',
			'Category Insert' => 'Category Insert',
			'Category Update' => 'Category Update',
			'Category Delete' => 'Category Delete',
		
		*/
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

		if($this->date_key != null && !in_array($this->date_key, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.date_key)',date('Y-m-d', strtotime($this->date_key)));
		$criteria->compare('t.album_insert',strtolower($this->album_insert),true);
		$criteria->compare('t.album_update',strtolower($this->album_update),true);
		$criteria->compare('t.album_delete',strtolower($this->album_delete),true);
		$criteria->compare('t.album_likes',strtolower($this->album_likes),true);
		$criteria->compare('t.album_unlikes',strtolower($this->album_unlikes),true);
		$criteria->compare('t.album_photo_insert',strtolower($this->album_photo_insert),true);
		$criteria->compare('t.album_photo_delete',strtolower($this->album_photo_delete),true);
		$criteria->compare('t.album_views',strtolower($this->album_views),true);
		$criteria->compare('t.album_setting_update',strtolower($this->album_setting_update),true);
		$criteria->compare('t.tag_insert_unique',strtolower($this->tag_insert_unique),true);
		$criteria->compare('t.tag_insert',strtolower($this->tag_insert),true);
		$criteria->compare('t.tag_delete',strtolower($this->tag_delete),true);
		$criteria->compare('t.category_insert',strtolower($this->category_insert),true);
		$criteria->compare('t.category_update',strtolower($this->category_update),true);
		$criteria->compare('t.category_delete',strtolower($this->category_delete),true);

		if(!isset($_GET['ViewAlbumStatistics_sort']))
			$criteria->order = 't.date_key DESC';

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
			$this->defaultColumns[] = 'date_key';
			$this->defaultColumns[] = 'album_insert';
			$this->defaultColumns[] = 'album_update';
			$this->defaultColumns[] = 'album_delete';
			$this->defaultColumns[] = 'album_likes';
			$this->defaultColumns[] = 'album_unlikes';
			$this->defaultColumns[] = 'album_photo_insert';
			$this->defaultColumns[] = 'album_photo_delete';
			$this->defaultColumns[] = 'album_views';
			$this->defaultColumns[] = 'album_setting_update';
			$this->defaultColumns[] = 'tag_insert_unique';
			$this->defaultColumns[] = 'tag_insert';
			$this->defaultColumns[] = 'tag_delete';
			$this->defaultColumns[] = 'category_insert';
			$this->defaultColumns[] = 'category_update';
			$this->defaultColumns[] = 'category_delete';
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
			$this->defaultColumns[] = array(
				'name' => 'date_key',
				'value' => 'Utility::dateFormat($data->date_key)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'date_key',
					'language' => 'ja',
					'i18nScriptFile' => 'jquery.ui.datepicker-en.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'date_key_filter',
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
			$this->defaultColumns[] = 'album_insert';
			$this->defaultColumns[] = 'album_update';
			$this->defaultColumns[] = 'album_delete';
			$this->defaultColumns[] = 'album_likes';
			$this->defaultColumns[] = 'album_unlikes';
			$this->defaultColumns[] = 'album_photo_insert';
			$this->defaultColumns[] = 'album_photo_delete';
			$this->defaultColumns[] = 'album_views';
			$this->defaultColumns[] = 'album_setting_update';
			$this->defaultColumns[] = 'tag_insert_unique';
			$this->defaultColumns[] = 'tag_insert';
			$this->defaultColumns[] = 'tag_delete';
			$this->defaultColumns[] = 'category_insert';
			$this->defaultColumns[] = 'category_update';
			$this->defaultColumns[] = 'category_delete';
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