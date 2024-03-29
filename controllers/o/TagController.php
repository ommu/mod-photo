<?php
/**
 * TagController
 * @var $this TagController
 * @var $model AlbumTag
 * @var $form CActiveForm
 *
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Add
 *	Delete
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2015 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-photo
 *
 *----------------------------------------------------------------------------------------------------------
 */

class TagController extends Controller
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
				$arrThemes = $this->currentTemplate('admin');
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
				'actions'=>array('index','view'),
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
	public function actionManage($album=null) 
	{
		$pageTitle = Yii::t('phrase', 'Album Tags');
		if($album != null) {
			$data = Albums::model()->findByPk($album);
			$pageTitle = Yii::t('phrase', 'Album Tags: {album_title} from category {category_name}', array ('{album_title}'=>$data->title, '{category_name}'=>Phrase::trans($data->category->name)));
		}
		
		$model=new AlbumTag('search');
		$model->unsetAttributes();	// clear any default values
		if(isset($_GET['AlbumTag'])) {
			$model->attributes=$_GET['AlbumTag'];
		}

		$columns = $model->getGridColumn($this->gridColumnTemp());

		$this->pageTitle = $pageTitle;
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_manage', array(
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
		$model=new AlbumTag;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['album_id'], $_POST['tag_id'], $_POST['tag'])) {
			$model->album_id = $_POST['album_id'];
			$model->tag_id = $_POST['tag_id'];
			$model->tag_i = $_POST['tag'];

			if($model->save()) {
				if(Yii::app()->getRequest()->getParam('type') == 'album')
					$url = Yii::app()->controller->createUrl('delete', array('id'=>$model->id,'type'=>'album'));
				else 
					$url = Yii::app()->controller->createUrl('delete', array('id'=>$model->id));
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
				if(Yii::app()->getRequest()->getParam('type') == 'album') {
					echo CJSON::encode(array(
						'type' => 4,
					));
				} else {
					echo CJSON::encode(array(
						'type' => 5,
						'get' => Yii::app()->controller->createUrl('manage'),
						'id' => 'partial-album-tag',
						'msg' => '<div class="errorSummary success"><strong>Album Tag success deleted.</strong></div>',
					));					
				}
			}

		} else {
			$this->dialogDetail = true;
			if(Yii::app()->getRequest()->getParam('type') == 'album')
				$url = Yii::app()->controller->createUrl('o/admin/edit', array('id'=>$model->album_id));
			else
				$url = Yii::app()->controller->createUrl('manage');
			$this->dialogGroundUrl = $url;
			$this->dialogWidth = 350;

			$this->pageTitle = Yii::t('phrase', 'Delete Tag: {tag_body} from album {album_title}', array('{tag_body}'=>$model->tag->body, '{album_title}'=>$model->album->title));
			$this->pageDescription = '';
			$this->pageMeta = '';
			$this->render('admin_delete');
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = AlbumTag::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='album-tag-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
