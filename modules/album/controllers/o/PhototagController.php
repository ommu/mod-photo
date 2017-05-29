<?php
/**
 * PhototagController
 * @var $this PhototagController
 * @var $model AlbumPhotoTag
 * @var $form CActiveForm
 * version: 0.1.4
 * Reference start
 *
 * TOC :
 *	Index
 *	Manage
 *	Add
 *	Delete
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 1 September 2016, 11:58 WIB
 * @link https://github.com/ommu/mod-photo-album
 * @contact (+62)856-299-4114
 *
 *----------------------------------------------------------------------------------------------------------
 */

class PhototagController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';
	public $defaultAction = 'index';

	/**
	 * Initialize admin page theme
	 */
	public function init() 
	{
		if(!Yii::app()->user->isGuest) {
			if(in_array(Yii::app()->user->level, array(1,2))) {
				$arrThemes = Utility::getCurrentTemplate('admin');
				Yii::app()->theme = $arrThemes['folder'];
				$this->layout = $arrThemes['layout'];
			} else
				throw new CHttpException(404, Yii::t('phrase', 'The requested page does not exist.'));
		} else
			$this->redirect(Yii::app()->createUrl('site/login'));
	}

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
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level)',
				//'expression'=>'isset(Yii::app()->user->level) && (Yii::app()->user->level != 1)',
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('manage','add','delete'),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level) && in_array(Yii::app()->user->level, array(1,2))',
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
		$this->redirect(array('manage'));
	}

	/**
	 * Manages all models.
	 */
	public function actionManage($media=null) 
	{
		$pageTitle = Yii::t('phrase', 'Album Photos Data');
		if($media != null) {
			$data = AlbumPhoto::model()->findByPk($media);
			$pageTitle = Yii::t('phrase', 'Album Photos Data: {photo_media} from album {album_title} category {category_name}', array ('{photo_media}'=>$data->media,'{album_title}'=>$data->album->title, '{category_name}'=>Phrase::trans($data->album->category->name)));
		}
		
		$model=new AlbumPhotoTag('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['AlbumPhotoTag'])) {
			$model->attributes=$_GET['AlbumPhotoTag'];
		}

		$columnTemp = array();
		if(isset($_GET['GridColumn'])) {
			foreach($_GET['GridColumn'] as $key => $val) {
				if($_GET['GridColumn'][$key] == 1) {
					$columnTemp[] = $key;
				}
			}
		}
		$columns = $model->getGridColumn($columnTemp);

		$this->pageTitle = $pageTitle;
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('/o/photo_tag/admin_manage',array(
			'model'=>$model,
			'columns' => $columns,
		));
	}	
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAdd() 
	{
		$model=new AlbumPhotoTag;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);
		
		if(isset($_POST['media_id'], $_POST['tag_id'], $_POST['tag'])) {
			$model->media_id = $_POST['media_id'];
			$model->tag_id = $_POST['tag_id'];
			$model->tag_i = $_POST['tag'];

			if($model->save()) {
				if(isset($_GET['type']) && $_GET['type'] == 'photo')
					$url = Yii::app()->controller->createUrl('delete',array('id'=>$model->id,'type'=>'photo'));
				else 
					$url = Yii::app()->controller->createUrl('delete',array('id'=>$model->id));
				echo CJSON::encode(array(
					'data' => '<div>'.$model->tag->body.'<a href="'.$url.'" title="'.Yii::t('phrase', 'Delete').'">'.Yii::t('phrase', 'Delete').'</a></div>',
				));
			}
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id) 
	{
		$model=$this->loadModel($id);
		
		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			if(isset($id)) {
				$model->delete();
				if(isset($_GET['type']) && $_GET['type'] == 'photo') {
					echo CJSON::encode(array(
						'type' => 4,
					));					
				} else {
					echo CJSON::encode(array(
						'type' => 5,
						'get' => Yii::app()->controller->createUrl('manage'),
						'id' => 'partial-album-photo-tag',
						'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Album Photo Tag success deleted.').'</strong></div>',
					));					
				}
				if($model->delete()) {
				}
			}

		} else {
			$this->dialogDetail = true;
			if(isset($_GET['type']) && $_GET['type'] == 'photo')
				$url = Yii::app()->controller->createUrl('o/photo/edit', array('id'=>$model->media_id));
			else
				$url = Yii::app()->controller->createUrl('manage');
			$this->dialogGroundUrl = $url;
			$this->dialogWidth = 350;

			$this->pageTitle = Yii::t('phrase', 'Delete Photo Tag: {tag_body} from photo {photo_media} album {album_title}', array('{tag_body}'=>$model->tag->body, '{photo_media}'=>$model->photo->media, '{album_title}'=>$model->photo->album->title));
			$this->pageDescription = '';
			$this->pageMeta = '';
			$this->render('/o/photo_tag/admin_delete');
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = AlbumPhotoTag::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='album-photo-tag-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
