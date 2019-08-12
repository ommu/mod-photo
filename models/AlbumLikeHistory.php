<?php
/**
 * AlbumLikeHistory
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 4 May 2017, 16:57 WIB
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
 * This is the model class for table "ommu_album_like_history".
 *
 * The followings are the available columns in table 'ommu_album_like_history':
 * @property string $id
 * @property integer $publish
 * @property string $like_id
 * @property string $likes_date
 * @property string $likes_ip
 *
 * The followings are the available model relations:
 * @property AlbumLikes $like
 */
class AlbumLikeHistory extends CActiveRecord
{
	use GridViewTrait;

	public $defaultColumns = array();
	
	// Variable Search
	public $category_search;
	public $album_search;
	public $user_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AlbumLikeHistory the static model class
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
		return 'ommu_album_like_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('publish, like_id, likes_ip', 'required'),
			array('publish', 'numerical', 'integerOnly'=>true),
			array('like_id', 'length', 'max'=>11),
			array('likes_ip', 'length', 'max'=>20),
			array('likes_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, publish, like_id, likes_date, likes_ip,
				category_search, album_search, user_search', 'safe', 'on'=>'search'),
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
			'like' => array(self::BELONGS_TO, 'AlbumLikes', 'like_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('attribute', 'ID'),
			'publish' => Yii::t('attribute', 'Publish'),
			'like_id' => Yii::t('attribute', 'Like'),
			'likes_date' => Yii::t('attribute', 'Likes Date'),
			'likes_ip' => Yii::t('attribute', 'Likes Ip'),
			'category_search' => Yii::t('attribute', 'Category'),
			'album_search' => Yii::t('attribute', 'Album'),
			'user_search' => Yii::t('attribute', 'User'),
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
			'like' => array(
				'alias' => 'like',
			),
			'like.album' => array(
				'alias' => 'like_album',
				'select' => 'cat_id, title'
			),
			'like.user' => array(
				'alias' => 'like_user',
				'select' => 'displayname'
			),
		);

		$criteria->compare('t.id', $this->id);
		if(Yii::app()->getRequest()->getParam('type') == 'subscribe')
			$criteria->compare('t.publish', 1);
		elseif(Yii::app()->getRequest()->getParam('type') == 'unsubscribe')
			$criteria->compare('t.publish', 0);
		else {
			$criteria->addInCondition('t.publish', array(0,1));
			$criteria->compare('t.publish', $this->publish);
		}
		if(Yii::app()->getRequest()->getParam('like'))
			$criteria->compare('t.like_id', Yii::app()->getRequest()->getParam('like'));
		else
			$criteria->compare('t.like_id', $this->like_id);
		if($this->likes_date != null && !in_array($this->likes_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.likes_date)', date('Y-m-d', strtotime($this->likes_date)));
		$criteria->compare('t.likes_ip', strtolower($this->likes_ip), true);

		$criteria->compare('like_album.cat_id', $this->category_search);
		$criteria->compare('like_album.title', strtolower($this->album_search), true);
		$criteria->compare('like_user.displayname', strtolower($this->user_search), true);

		if(!Yii::app()->getRequest()->getParam('AlbumLikeDetail_sort'))
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
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'like_id';
			$this->defaultColumns[] = 'likes_date';
			$this->defaultColumns[] = 'likes_ip';
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
			if(!Yii::app()->getRequest()->getParam('like')) {
				$this->defaultColumns[] = array(
					'name' => 'category_search',
					'value' => 'Phrase::trans($data->like->album->category->name)',
					'filter' => AlbumCategory::getCategory(),
					'type' => 'raw',
				);
				$this->defaultColumns[] = array(
					'name' => 'album_search',
					'value' => '$data->like->album->title',
				);
				$this->defaultColumns[] = array(
					'name' => 'user_search',
					'value' => '$data->like->user->displayname ? $data->like->user->displayname : \'-\'',
				);
			}
			$this->defaultColumns[] = array(
				'name' => 'likes_date',
				'value' => 'Yii::app()->dateFormatter->formatDateTime($data->likes_date, \'medium\', false)',
				'htmlOptions' => array(
					//'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'likes_date'),
			);
			$this->defaultColumns[] = array(
				'name' => 'likes_ip',
				'value' => '$data->likes_ip',
				'htmlOptions' => array(
					//'class' => 'center',
				),
			);
			$this->defaultColumns[] = array(
				'name' => 'publish',
				'value' => '$data->publish == 1 ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
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

}