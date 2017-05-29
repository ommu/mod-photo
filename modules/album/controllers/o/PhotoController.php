<?php
/**
 * PhotoController
 * @var $this PhotoController
 * @var $model AlbumPhoto
 * @var $form CActiveForm
 * version: 0.1.4
 * Reference start
 *
 * TOC :
 *	Index
 *	Manage
 *	Edit
 *	View
 *	RunAction
 *	Delete
 *	Publish
 *	Setcover
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/mod-photo-album
 * @contact (+62)856-299-4114
 *
 *----------------------------------------------------------------------------------------------------------
 */

class PhotoController extends Controller
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
				'actions'=>array('ajaxmanage','ajaxadd','delete','cover'),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level)',
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('manage','edit','view','runaction','delete','publish','setcover'),
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
		$pageTitle = Yii::t('phrase', 'Album Photos');
		if($album != null) {
			$data = Albums::model()->findByPk($album);
			$pageTitle = Yii::t('phrase', 'Album Photos: {album_title} from category {category_name}', array ('{album_title}'=>$data->title, '{category_name}'=>Phrase::trans($data->category->name)));
		}
		
		$model=new AlbumPhoto('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['AlbumPhoto'])) {
			$model->attributes=$_GET['AlbumPhoto'];
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
		$this->render('admin_manage',array(
			'model'=>$model,
			'columns' => $columns,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionEdit($id) 
	{
		$setting = AlbumSetting::model()->findByPk(1,array(
			'select' => 'meta_keyword, headline, photo_limit, photo_file_type',
		));
		$photo_file_type = unserialize($setting->photo_file_type);
		if(empty($photo_file_type))
			$photo_file_type = array();
		
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['AlbumPhoto'])) {
			$model->attributes=$_POST['AlbumPhoto'];
			
			if($model->save()) {
				Yii::app()->user->setFlash('success', 'Album Photo success updated.');
				$this->redirect(array('manage'));
			}
		}
		
		$this->pageTitle = Yii::t('phrase', 'Update Photo: {photo_media} from album {album_title}', array('{photo_media}'=>$model->media, '{album_title}'=>$model->album->title));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_edit',array(
			'model'=>$model,
			'photo_file_type'=>$photo_file_type,
		));
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) 
	{
		$model=$this->loadModel($id);

		$this->pageTitle = Yii::t('phrase', 'View Photo: {photo_media} from album {album_title}', array('{photo_media}'=>$model->media, '{album_title}'=>$model->album->title));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_view',array(
			'model'=>$model,
		));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionRunAction() {
		$id       = $_POST['trash_id'];
		$criteria = null;
		$actions  = $_GET['action'];

		if(count($id) > 0) {
			$criteria = new CDbCriteria;
			$criteria->addInCondition('media_id', $id);

			if($actions == 'publish') {
				AlbumPhoto::model()->updateAll(array(
					'publish' => 1,
				),$criteria);
			} elseif($actions == 'unpublish') {
				AlbumPhoto::model()->updateAll(array(
					'publish' => 0,
				),$criteria);
			} elseif($actions == 'trash') {
				AlbumPhoto::model()->updateAll(array(
					'publish' => 2,
				),$criteria);
			} elseif($actions == 'delete') {
				AlbumPhoto::model()->deleteAll($criteria);
			}
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('manage'));
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
				
				if(isset($_GET['hook']) && $_GET['hook'] == 'admin') {
					$url = Yii::app()->controller->createUrl('o/admin/getcover', array('id'=>$model->album_id,'replace'=>'true'));
					echo CJSON::encode(array(
						'type' => 2,
						'id' => 'media-render',
						'get' => $url,
					));						
				} else {
					echo CJSON::encode(array(
						'type' => 5,
						'get' => Yii::app()->controller->createUrl('manage'),
						'id' => 'partial-album-photo',
						'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Album Photo success deleted.').'</strong></div>',
					));
				}
			}

		} else {
			if(isset($_GET['hook']) && $_GET['hook'] == 'admin')
				$dialogGroundUrl = Yii::app()->controller->createUrl('o/admin/edit', array('id'=>$model->album_id));
			else 
				$dialogGroundUrl = Yii::app()->controller->createUrl('manage');
			$this->dialogDetail = true;
			$this->dialogGroundUrl = $dialogGroundUrl;
			$this->dialogWidth = 350;

			$this->pageTitle = Yii::t('phrase', 'Delete Photo: {photo_media} from album {album_title}', array('{photo_media}'=>$model->media, '{album_title}'=>$model->album->title));
			$this->pageDescription = '';
			$this->pageMeta = '';
			$this->render('admin_delete');
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionPublish($id) 
	{
		$model=$this->loadModel($id);
		
		if($model->publish == 1) {
			$title = Yii::t('phrase', 'Unpublish');
			$replace = 0;
		} else {
			$title = Yii::t('phrase', 'Publish');
			$replace = 1;
		}
		$pageTitle = Yii::t('phrase', '{title}: {photo_media} from album {album_title}', array('{title}'=>$title, '{photo_media}'=>$model->media, '{album_title}'=>$model->album->title));

		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			if(isset($id)) {
				//change value active or publish
				$model->publish = $replace;

				if($model->update()) {
					echo CJSON::encode(array(
						'type' => 5,
						'get' => Yii::app()->controller->createUrl('manage'),
						'id' => 'partial-album-photo',
						'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Album Photo success updated.').'</strong></div>',
					));
				}
			}

		} else {
			$this->dialogDetail = true;
			$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
			$this->dialogWidth = 350;

			$this->pageTitle = $pageTitle;
			$this->pageDescription = '';
			$this->pageMeta = '';
			$this->render('admin_publish',array(
				'title'=>$title,
				'model'=>$model,
			));
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionSetcover($id) 
	{
		$model = $this->loadModel($id);
		
		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			if(isset($id)) {				
				$model->cover = 1;
				
				if($model->update()) {
					if(isset($_GET['hook']) && $_GET['hook'] == 'admin') {
						$url = Yii::app()->controller->createUrl('o/admin/getcover', array('id'=>$model->album_id,'replace'=>'true'));
						echo CJSON::encode(array(
							'type' => 2,
							'id' => 'media-render',
							'get' => $url,
						));
					} else {
						echo CJSON::encode(array(
							'type' => 5,
							'get' => Yii::app()->controller->createUrl('manage'),
							'id' => 'partial-album-photo',
							'msg' => '<div class="errorSummary success"><strong>Album Photo success updated.</strong></div>',
						));						
					}
				}
			}

		} else {
			if(isset($_GET['hook']) && $_GET['hook'] == 'admin')
				$dialogGroundUrl = Yii::app()->controller->createUrl('o/admin/edit', array('id'=>$model->album_id));
			else 
				$dialogGroundUrl = Yii::app()->controller->createUrl('manage');		
			$this->dialogDetail = true;
			$this->dialogGroundUrl = $dialogGroundUrl;
			$this->dialogWidth = 350;

			$this->pageTitle = Yii::t('phrase', 'Cover Photo: {photo_media} from album {album_title}', array('{photo_media}'=>$model->media, '{album_title}'=>$model->album->title));
			$this->pageDescription = '';
			$this->pageMeta = '';
			$this->render('admin_cover');
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = AlbumPhoto::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='album-photo-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
