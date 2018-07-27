<?php
/**
 * Albums
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
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
 * This is the model class for table "ommu_albums".
 *
 * The followings are the available columns in table 'ommu_albums':
 * @property string $album_id
 * @property integer $publish
 * @property string $cat_id
 * @property string $title
 * @property string $body
 * @property string $quote
 * @property integer $headline
 * @property integer $comment_code
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 *
 * The followings are the available model relations:
 * @property AlbumLikes[] $AlbumLikes
 * @property AlbumPhoto[] $AlbumPhotos
 */
class Albums extends CActiveRecord
{
	use UtilityTrait;
	use GridViewTrait;

	public $defaultColumns = array();
	public $media_i;
	public $keyword_i;
	
	// Variable Search
	public $creation_search;
	public $modified_search;
	public $photo_search;
	public $view_search;
	public $like_search;
	public $tag_search;

	/**
	 * Behaviors for this model
	 */
	public function behaviors() 
	{
		return array(
			'sluggable' => array(
				'class'=>'ext.yii-sluggable.SluggableBehavior',
				'columns' => array('title'),
				'unique' => true,
				'update' => true,
			),
		);
	}

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
			array('cat_id, title', 'required'),
			array('publish, headline, comment_code, creation_id, modified_id', 'numerical', 'integerOnly'=>true),
			array('cat_id', 'length', 'max'=>5),
			array('creation_id, modified_id', 'length', 'max'=>11),
			array('
				keyword_i', 'length', 'max'=>32),
			array('title', 'length', 'max'=>128),
			//array('media_i', 'file', 'types' => 'jpg, jpeg, png, gif', 'allowEmpty' => true),
			array('body, quote,
				media_i, keyword_i', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('album_id, publish, cat_id, title, body, quote, headline, comment_code, creation_date, creation_id, modified_date, modified_id,
				creation_search, modified_search, photo_search, view_search, like_search, tag_search', 'safe', 'on'=>'search'),
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
			'view' => array(self::BELONGS_TO, 'ViewAlbums', 'album_id'),
			'category' => array(self::BELONGS_TO, 'AlbumCategory', 'cat_id'),
			'creation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
			'likes' => array(self::HAS_MANY, 'AlbumLikes', 'album_id'),
			'photos' => array(self::HAS_MANY, 'AlbumPhoto', 'album_id'),
			'tags' => array(self::HAS_MANY, 'AlbumTag', 'album_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'album_id' => Yii::t('attribute', 'Album'),
			'publish' => Yii::t('attribute', 'Publish'),
			'cat_id' => Yii::t('attribute', 'Category'),
			'title' => Yii::t('attribute', 'Title'),
			'body' => Yii::t('attribute', 'Description'),
			'quote' => Yii::t('attribute', 'Quote'),
			'headline' => Yii::t('attribute', 'Headline'),
			'comment_code' => Yii::t('attribute', 'Comment'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation ID'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified ID'),
			'media_i' => Yii::t('attribute', 'Photo Cover'),
			'keyword_i' => Yii::t('attribute', 'Tags'),
			'creation_search' => Yii::t('attribute', 'Creation'),
			'modified_search' => Yii::t('attribute', 'Modified'),
			'photo_search' => Yii::t('attribute', 'Photos'),
			'view_search' => Yii::t('attribute', 'Views'),
			'like_search' => Yii::t('attribute', 'Likes'),
			'tag_search' => Yii::t('attribute', 'Tags'),
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
				'alias' => 'view',
			),
			'creation' => array(
				'alias' => 'creation',
				'select' => 'displayname'
			),
			'modified' => array(
				'alias' => 'modified',
				'select' => 'displayname'
			),
		);

		$criteria->compare('t.album_id', $this->album_id);
		if(Yii::app()->getRequest()->getParam('type') == 'publish') {
			$criteria->compare('t.publish', 1);
		} elseif(Yii::app()->getRequest()->getParam('type') == 'unpublish') {
			$criteria->compare('t.publish', 0);
		} elseif(Yii::app()->getRequest()->getParam('type') == 'trash') {
			$criteria->compare('t.publish', 2);
		} else {
			$criteria->addInCondition('t.publish', array(0,1));
			$criteria->compare('t.publish', $this->publish);
		}
		if(Yii::app()->getRequest()->getParam('category'))
			$criteria->compare('t.cat_id',Yii::app()->getRequest()->getParam('category'));
		else
			$criteria->compare('t.cat_id', $this->cat_id);
		$criteria->compare('t.title', $this->title,true);
		$criteria->compare('t.body', $this->body,true);
		$criteria->compare('t.quote', $this->quote,true);
		$criteria->compare('t.headline', $this->headline);
		$criteria->compare('t.comment_code', $this->comment_code);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.creation_date)', date('Y-m-d', strtotime($this->creation_date)));
		if(Yii::app()->getRequest()->getParam('creation'))
			$criteria->compare('t.creation_id',Yii::app()->getRequest()->getParam('creation'));
		else
			$criteria->compare('t.creation_id', $this->creation_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.modified_date)', date('Y-m-d', strtotime($this->modified_date)));
		if(Yii::app()->getRequest()->getParam('modified'))
			$criteria->compare('t.modified_id',Yii::app()->getRequest()->getParam('modified'));
		else
			$criteria->compare('t.modified_id', $this->modified_id);
		
		$criteria->compare('creation.displayname', strtolower($this->creation_search), true);
		$criteria->compare('modified.displayname', strtolower($this->modified_search), true);
		$criteria->compare('view.photos', $this->photo_search);
		$criteria->compare('view.views', $this->view_search);
		$criteria->compare('view.likes', $this->like_search);
		$criteria->compare('view.tags', $this->tag_search);

		if(!Yii::app()->getRequest()->getParam('Albums_sort'))
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
			//$this->defaultColumns[] = 'album_id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'cat_id';
			$this->defaultColumns[] = 'title';
			$this->defaultColumns[] = 'body';
			$this->defaultColumns[] = 'quote';
			$this->defaultColumns[] = 'headline';
			$this->defaultColumns[] = 'comment_code';
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
	protected function afterConstruct() 
	{
		$setting = AlbumSetting::model()->findByPk(1, array(
			'select' => 'gridview_column, headline',
		));
		$gridview_column = unserialize($setting->gridview_column);		
		if(empty($gridview_column))
			$gridview_column = array();
		
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
				'value' => '$data->title',
			);
			if(!Yii::app()->getRequest()->getParam('category')) {
				$this->defaultColumns[] = array(
					'name' => 'cat_id',
					'value' => 'Phrase::trans($data->category->name)',
					'filter' => AlbumCategory::getCategory(),
					'type' => 'raw',
				);
			}
			if(in_array('creation_search', $gridview_column)) {
				$this->defaultColumns[] = array(
					'name' => 'creation_search',
					'value' => '$data->creation->displayname',
				);
			}
			if(in_array('creation_date', $gridview_column)) {
				$this->defaultColumns[] = array(
					'name' => 'creation_date',
					'value' => 'Yii::app()->dateFormatter->formatDateTime($data->creation_date, \'medium\', false)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter' => $this->filterDatepicker($this, 'creation_date'),
				);
			}
			if(in_array('photo_search', $gridview_column)) {
				$this->defaultColumns[] = array(
					'name' => 'photo_search',
					'value' => 'CHtml::link($data->view->photos ? $data->view->photos : 0, Yii::app()->controller->createUrl("o/photo/manage", array("album"=>$data->album_id)))',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'type' => 'raw',
				);
			}
			if(in_array('view_search', $gridview_column)) {
				$this->defaultColumns[] = array(
					'name' => 'view_search',
					'value' => 'CHtml::link($data->view->views ? $data->view->views : 0, Yii::app()->controller->createUrl("o/view/manage", array("album"=>$data->album_id)))',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'type' => 'raw',
				);	
			}
			if(in_array('like_search', $gridview_column)) {
				$this->defaultColumns[] = array(
					'name' => 'like_search',
					'value' => 'CHtml::link($data->view->likes ? $data->view->likes : 0, Yii::app()->controller->createUrl("o/like/manage", array("album"=>$data->album_id)))',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'type' => 'raw',
				);
			}
			if(in_array('tag_search', $gridview_column)) {
				$this->defaultColumns[] = array(
					'name' => 'tag_search',
					'value' => 'CHtml::link($data->view->tags ? $data->view->tags : 0, Yii::app()->controller->createUrl("o/tag/manage", array("album"=>$data->album_id)))',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'type' => 'raw',
				);
			}
			if($setting->headline == 1) {
				$this->defaultColumns[] = array(
					'name' => 'headline',
					'value' => 'in_array($data->cat_id, AlbumSetting::getHeadlineCategory()) ? ($data->headline == 1 ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : Utility::getPublish(Yii::app()->controller->createUrl("headline", array("id"=>$data->album_id)), $data->headline, 1)) : \'-\'',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter' => $this->filterYesNo(),
					'type' => 'raw',
				);
			}
			if(!Yii::app()->getRequest()->getParam('type')) {
				$this->defaultColumns[] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("publish", array("id"=>$data->album_id)), $data->publish, 1)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter' => $this->filterYesNo(),
					'type' => 'raw',
				);
			}
		}
		parent::afterConstruct();
	}

	/**
	 * Albums get information
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
	 * Albums get information
	 */
	public static function getHeadline()
	{
		$setting = AlbumSetting::model()->findByPk(1, array(
			'select' => 'headline_limit, headline_category',
		));
		$headline_category = unserialize($setting->headline_category);
		if(empty($headline_category))
			$headline_category = array();
		
		$criteria=new CDbCriteria;
		$criteria->compare('publish', 1);
		$criteria->addInCondition('cat_id', $headline_category);
		$criteria->compare('headline', 1);
		$criteria->order = 'headline_date DESC';
		
		$model = self::model()->findAll($criteria);
		
		$headline = array();
		if(!empty($model)) {
			$i=0;
			foreach($model as $key => $val) {
				$i++;
				if($i <= $setting->headline_limit)
					$headline[] = $val->album_id;
			}
		}
		
		return $headline;
	}

	/**
	 * Albums get information
	 */
	public function searchIndexing($index)
	{
		Yii::import('application.vendor.ommu.album.models.*');
		
		$criteria=new CDbCriteria;
		$criteria->compare('publish', 1);
		$criteria->order = 'album_id DESC';
		//$criteria->limit = 10;
		$model = Albums::model()->findAll($criteria);
		foreach($model as $key => $item) {
			$photos = $item->photos;
			if(!empty($photos)) {
				$media = $item->view->album_cover ? $item->view->album_cover : $photos[0]->media;
				$image = Yii::app()->request->baseUrl.'/public/album/'.$item->album_id.'/'.$media;
			} else 
				$image = '';
			$doc = new Zend_Search_Lucene_Document();
			$doc->addField(Zend_Search_Lucene_Field::UnIndexed('id', CHtml::encode($item->album_id), 'utf-8')); 
			$doc->addField(Zend_Search_Lucene_Field::Text('media', CHtml::encode($image), 'utf-8'));
			$doc->addField(Zend_Search_Lucene_Field::Text('title', CHtml::encode($item->title), 'utf-8'));
			$doc->addField(Zend_Search_Lucene_Field::Text('body', CHtml::encode(Utility::hardDecode(Utility::softDecode($item->body))), 'utf-8'));
			$doc->addField(Zend_Search_Lucene_Field::Text('url', CHtml::encode(Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->createUrl('album/site/view', array('id'=>$item->album_id,'slug'=>$this->urlTitle($item->title)))), 'utf-8'));
			$doc->addField(Zend_Search_Lucene_Field::UnIndexed('date', CHtml::encode($this->dateFormat($item->creation_date, 'long', 'long')), 'utf-8'));
			$doc->addField(Zend_Search_Lucene_Field::UnIndexed('creation', CHtml::encode($item->creation->displayname), 'utf-8'));
			$index->addDocument($doc);		
		}
		
		return true;
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() 
	{
		$setting = AlbumSetting::model()->findByPk(1, array(
			'select' => 'photo_file_type',
		));
		$photo_file_type = unserialize($setting->photo_file_type);
		if(empty($photo_file_type))
			$photo_file_type = array();
		
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->creation_id = Yii::app()->user->id;
			else
				$this->modified_id = Yii::app()->user->id;
			
			if($this->headline == 1 && $this->publish == 0)
				$this->addError('publish', Yii::t('phrase', 'Publish cannot be blank.'));
			
			$media_i = CUploadedFile::getInstance($this, 'media_i');
			if($media_i->name != '') {
				$extension = pathinfo($media_i->name, PATHINFO_EXTENSION);
				if(!in_array(strtolower($extension), $photo_file_type))
					$this->addError('media_i', Yii::t('phrase', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}.', array(
						'{name}'=>$media_i->name,
						'{extensions}'=>Utility::formatFileType($photo_file_type, false),
					)));
			}
		}
		return true;
	}
	
	/**
	 * After save attributes
	 */
	protected function afterSave() 
	{
		parent::afterSave();
		$setting = AlbumSetting::model()->findByPk(1, array(
			'select' => 'headline',
		));
		
		// Add album directory
		$album_path = "public/album/".$this->album_id;
		if(!file_exists($album_path)) {
			@mkdir($album_path, 0755, true);

			// Add file in album directory (index.php)
			$newFile = $album_path.'/index.php';
			$FileHandle = fopen($newFile, 'w');
		} else
			@chmod($album_path, 0755, true);
		
		if($this->isNewRecord) {
			$this->media_i = CUploadedFile::getInstance($this, 'media_i');
			if($this->media_i != null) {
				if($this->media_i instanceOf CUploadedFile) {
					$fileName = time().'_'.$this->urlTitle($this->title).'.'.strtolower($this->media_i->extensionName);
					if($this->media_i->saveAs($album_path.'/'.$fileName)) {
						$images = new AlbumPhoto;
						$images->album_id = $this->album_id;
						$images->cover = '1';
						$images->media = $fileName;
						$images->save();
					}
				}
			}
			
			//input keyword
			if(trim($this->keyword_i) != '') {
				$keyword_i = Utility::formatFileType($this->keyword_i);
				if(!empty($keyword_i)) {
					foreach($keyword_i as $key => $val) {
						$subject = new AlbumTag;
						$subject->album_id = $this->album_id;
						$subject->tag_id = 0;
						$subject->tag_i = $val;
						$subject->save();
					}
				}
			}	
		}
		
		// Reset headline
		if($setting->headline == 1 && $this->headline == 1) {
			$headline = self::getHeadline();
			
			$criteria=new CDbCriteria;
			$criteria->addNotInCondition('album_id', $headline);
			self::model()->updateAll(array('headline'=>0), $criteria);
		}
	}

	/**
	 * Before delete attributes
	 */
	protected function beforeDelete() {
		if(parent::beforeDelete()) {
			$album_path = "public/album/".$this->album_id;
			
			//delete media photos
			$photos = $this->photos;
			if(!empty($photos)) {
				foreach($photos as $val) {
					if($val->media != '' && file_exists($album_path.'/'.$val->media))
						rename($album_path.'/'.$val->media, 'public/album/verwijderen/'.$val->album_id.'_'.$val->media);
				}
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