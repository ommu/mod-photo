<?php
/**
 * GalleryController
 * @var $this GalleryController
 * @var $model Albums
 * @var $form CActiveForm
 * version: 0.0.1
 * Reference start
 *
 * TOC :
 *	Index
 *	Main
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 2 September 2016, 14:46 WIB
 * @link https://github.com/oMMu/Ommu-Photo-Albums
 * @contect (+62)856-299-4114
 *
 *----------------------------------------------------------------------------------------------------------
 */

class GalleryController extends ControllerApi
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';
	public $defaultAction = 'index';

	/**
	 * @return array action filters
	 */
	public function filters() 
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() 
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','main'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level)',
				//'expression'=>'isset(Yii::app()->user->level) && (Yii::app()->user->level != 1)',
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array(),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Lists all models.
	 */
	public function actionIndex() 
	{
		$this->redirect(Yii::app()->createUrl('site/index'));
	}
	
	/**
	 * Lists all models.
	 */
	public function actionMain() 
	{
		if(!isset($_GET['action']))
			$this->redirect(Yii::app()->createUrl('site/index'));
			
		else {
			if($_GET['action'] == 'pexeto_get_portfolio_items') {		
				if(isset($_GET['cat']) && $_GET['cat'] != '') {		
					$album_id = trim($_GET['cat']);
					$pagesize = trim($_GET['number']);
				
					$criteria=new CDbCriteria;
					$criteria->with = array(
						'photo' => array(
							'alias'=>'photo',
						),
					);
					$criteria->compare('photo.publish', 1);
					$criteria->compare('photo.album_id', $album_id);
					$criteria->group = 't.tag_id';
					
					$dataProvider = new CActiveDataProvider('AlbumPhotoTag', array(
						'criteria'=>$criteria,
						'pagination'=>array(
							'pageSize'=>$pagesize != null && $pagesize != '' ? $pagesize : 10,
						),
					));
					$model = $dataProvider->getData();
					
					// pager
					$pager = OFunction::getDataProviderPager($dataProvider);
					$get = array_merge($_GET, array($pager['pageVar']=>$pager['nextPage']));
					$nextPager = $pager['nextPage'] != 0 ? OFunction::validHostURL(Yii::app()->controller->createUrl('main', $get)) : '-';
				
					// photo is no tags
					$criteriaNoTag=new CDbCriteria;
					$criteriaNoTag->with = array(
						'view' => array(
							'alias'=>'view',
						),
					);
					$criteriaNoTag->compare('t.publish', 1);
					$criteriaNoTag->compare('t.album_id', $album_id);
					$criteriaNoTag->compare('view.photo_tag', 0);
					
					$photoNoTag = AlbumPhoto::model()->findAll($criteriaNoTag);
					
					//url and directory path
					$album_url = Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->request->baseUrl.'/';
					$album_path = 'public/album/'.$album_id.'/';
					
					$dataPhotoNoTag = array();
					if($photoNoTag != null) {						
						if($photoNoTag[0]->media != '' && file_exists($album_path.$photoNoTag[0]->media)) {
							$album_photo = $album_url.$album_path.$photoNoTag[0]->media;
							$dataPhotoNoTag = array(
								'id'=>0,
								'title'=>ucwords(strtolower('Lainnya')),
								'pr'=>$album_photo,
								'col'=>1,
								'row'=>1,
								'image'=>Utility::getTimThumb($album_photo, 380, 235, 1),
								'cat'=>$photoNoTag[0]->album->title,
								'slug'=>Utility::getUrlTitle('Lainnya'),
								'link'=>'http:\/\/pexetothemes.com\/demos\/expression_wp\/portfolio\/sailing-boat\/',
								'fullwidth'=>false,
								'slider'=>true,
								'imgnum'=>count($photoNoTag),
							);
						}
					}					
					
					if(!empty($model)) {
						foreach($model as $key => $item) {							
							if($item->photo->media != '' && file_exists($album_path.$item->photo->media)) {
								$album_photo = $album_url.$album_path.$item->photo->media;
								$data[] = array(
									'id'=>$item->tag_id,
									'title'=>ucwords(strtolower($item->tag->body)),
									'pr'=>$album_photo,
									'col'=>1,
									'row'=>1,
									'image'=>Utility::getTimThumb($album_photo, 380, 235, 1),
									'cat'=>$item->photo->album->title,
									'slug'=>Utility::getUrlTitle($item->tag->body),
									'link'=>'http:\/\/pexetothemes.com\/demos\/expression_wp\/portfolio\/sailing-boat\/',
									'fullwidth'=>false,
									'slider'=>true,
									'imgnum'=>4,
								);						
							}
						}
						if($nextPager == '-')
							$data[] = $dataPhotoNoTag;
						
					} else {
						$data = array();
						if($nextPager == '-')
							$data[] = $dataPhotoNoTag;						
					}
					
					$return = array(
						'items' => $data,
						'more' => $nextPager != '-' ? true : false,
					);
					$this->_sendResponse(200, CJSON::encode($this->renderJson($return)));	
					
				} else
					$this->redirect(Yii::app()->createUrl('site/index'));
				
			} else if($_GET['action'] == 'pexeto_get_portfolio_content') {
				
			}
		}
	}
	
	public function getCountPhotoInTag($album, ) 
	{
		$model = Albums::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404, Yii::t('phrase', 'The requested page does not exist.'));
		return $model;
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = Albums::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404, Yii::t('phrase', 'The requested page does not exist.'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model) 
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='articles-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
