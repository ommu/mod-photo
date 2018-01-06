<?php
/**
 * AdminController
 * @var $this AdminController
 * @var $model Albums
 * @var $form CActiveForm
 *
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Add
 *	Edit
 *	RunAction
 *	Delete
 *	Publish
 *	Headline
 *	Getcover
 *	Insertcover
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-photo
 *
 *----------------------------------------------------------------------------------------------------------
 */

class AdminController extends Controller
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
				'actions'=>array('manage','add','edit','runaction','delete','publish','headline','getcover','insertcover'),
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
	public function actionManage($category=null) 
	{
		$pageTitle = Yii::t('phrase', 'Albums');
		if($category != null) {
			$data = AlbumCategory::model()->findByPk($category);
			$pageTitle = Yii::t('phrase', 'Albums: Category {category_name}', array ('{category_name}'=>Phrase::trans($data->name)));
		}
		
		$model=new Albums('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Albums'])) {
			$model->attributes=$_GET['Albums'];
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAdd() 
	{
		$model=new Albums;
		$setting = AlbumSetting::model()->findByPk(1,array(
			'select' => 'meta_keyword, headline, photo_file_type',
		));
		$photo_file_type = unserialize($setting->photo_file_type);
		if(empty($photo_file_type))
			$photo_file_type = array();

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Albums'])) {
			$model->attributes=$_POST['Albums'];
			
			if($model->save()) {
				Yii::app()->user->setFlash('success', Yii::t('phrase', 'Album success created.'));
				$this->redirect(array('edit','id'=>$model->album_id));
			}
		}

		$this->pageTitle = Yii::t('phrase', 'Create Album');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_add',array(
			'model'=>$model,
			'setting'=>$setting,
			'photo_file_type'=>$photo_file_type,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionEdit($id) 
	{
		$model=$this->loadModel($id);

		$setting = AlbumSetting::model()->findByPk(1,array(
			'select' => 'meta_keyword, headline, photo_limit, photo_file_type',
		));
		$photo_file_type = unserialize($setting->photo_file_type);
		if(empty($photo_file_type))
			$photo_file_type = array();

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Albums'])) {
			$model->attributes=$_POST['Albums'];
			
			$jsonError = CActiveForm::validate($model);
			if(strlen($jsonError) > 2) {
				$errors = $model->getErrors();
				$summary['msg'] = "<div class='errorSummary'><strong>".Yii::t('phrase', 'Please fix the following input errors:')."</strong>";
				$summary['msg'] .= "<ul>";
				foreach($errors as $key => $value) {
					$summary['msg'] .= "<li>{$value[0]}</li>";
				}
				$summary['msg'] .= "</ul></div>";

				$message = json_decode($jsonError, true);
				$merge = array_merge_recursive($summary, $message);
				$encode = json_encode($merge);
				echo $encode;

			} else {
				if(isset($_GET['enablesave']) && $_GET['enablesave'] == 1) {
					if($model->save()) {
						echo CJSON::encode(array(
							'type' => 0,
							'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Album success updated.').'</strong></div>',
						));
					} else {
						print_r($model->getErrors());
					}
				}
			}
			Yii::app()->end();
			
		}
		
		$this->pageTitle = Yii::t('phrase', 'Update Album: {album_title}', array('{album_title}'=>$model->title));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_edit',array(
			'model'=>$model,
			'setting'=>$setting,
			'photo_file_type'=>$photo_file_type,
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
			$criteria->addInCondition('id', $id);

			if($actions == 'publish') {
				Albums::model()->updateAll(array(
					'publish' => 1,
				),$criteria);
			} elseif($actions == 'unpublish') {
				Albums::model()->updateAll(array(
					'publish' => 0,
				),$criteria);
			} elseif($actions == 'trash') {
				Albums::model()->updateAll(array(
					'publish' => 2,
				),$criteria);
			} elseif($actions == 'delete') {
				Albums::model()->deleteAll($criteria);
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
				if($model->delete()) {
					echo CJSON::encode(array(
						'type' => 5,
						'get' => Yii::app()->controller->createUrl('manage'),
						'id' => 'partial-albums',
						'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Album success deleted.').'</strong></div>',
					));
				}
			}

		} else {
			$this->dialogDetail = true;
			$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
			$this->dialogWidth = 350;

			$this->pageTitle = Yii::t('phrase', 'Delete Album: {album_title}', array('{album_title}'=>$model->title));
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
		$pageTitle = Yii::t('phrase', '{title}: {album_title}', array('{title}'=>$title, '{album_title}'=>$model->title));

		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			if(isset($id)) {
				//change value active or publish
				$model->publish = $replace;

				if($model->update()) {
					echo CJSON::encode(array(
						'type' => 5,
						'get' => Yii::app()->controller->createUrl('manage'),
						'id' => 'partial-albums',
						'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Album success updated.').'</strong></div>',
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
	public function actionHeadline($id) 
	{
		$model=$this->loadModel($id);

		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			if(isset($id)) {
				//change value active or publish
				$model->headline = 1;
				$model->publish = 1;

				if($model->update()) {
					echo CJSON::encode(array(
						'type' => 5,
						'get' => Yii::app()->controller->createUrl('manage'),
						'id' => 'partial-albums',
						'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Album success updated.').'</strong></div>',
					));
				}
			}

		} else {
			$this->dialogDetail = true;
			$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
			$this->dialogWidth = 350;

			$this->pageTitle = Yii::t('phrase', 'Headline: {album_title}', array('{album_title}'=>$model->title));
			$this->pageDescription = '';
			$this->pageMeta = '';
			$this->render('admin_headline');
		}
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionGetcover($id) 
	{
		$setting = AlbumSetting::model()->findByPk(1,array(
			'select' => 'photo_limit',
		));
		$photo_limit = $setting->photo_limit;
		
		$model=$this->loadModel($id);
		$photos = $model->photos;

		$data = '';
		if(isset($_GET['replace']))
			$data .= $this->renderPartial('_form_photo', array('model'=>$model, 'photos'=>$photos, 'photo_limit'=>$photo_limit), true, false);
		
		if(!empty($photos)) {
			foreach($photos as $key => $val)
				$data .= $this->renderPartial('_form_view_photos', array('data'=>$val), true, false);
		}
		
		$data .= '';
		$result['data'] = $data;
		echo CJSON::encode($result);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionInsertcover($id) 
	{
		$setting = AlbumSetting::model()->findByPk(1,array(
			'select' => 'photo_limit',
		));
		$photo_limit = $setting->photo_limit;
	
		$album_path = "public/album/".$id;
		// Add directory
		if(!file_exists($album_path)) {
			@mkdir($album_path, 0755, true);

			// Add file in directory (index.php)
			$newFile = $album_path.'/index.php';
			$FileHandle = fopen($newFile, 'w');
		} else
			@chmod($album_path, 0755, true);
			
		//if(Yii::app()->request->isAjaxRequest) {
			$model = $this->loadModel($id);
			if($model->category->default_setting == 0)
				$photo_limit = $model->category->photo_limit;
			
			$uploadPhoto = CUploadedFile::getInstanceByName('namaFile');
			$fileName = time().'_'.Utility::getUrlTitle($model->title).'.'.strtolower($uploadPhoto->extensionName);
			if($uploadPhoto->saveAs($album_path.'/'.$fileName)) {
				$photo = new AlbumPhoto;
				$photo->album_id = $model->album_id;
				$photo->cover = $model->photos == null ? '1' : '0';
				$photo->media = $fileName;
				if($photo->save()) {
					$url = Yii::app()->controller->createUrl('getcover', array('id'=>$model->album_id,'replace'=>'true'));
					echo CJSON::encode(array(
						'id' => 'media-render',
						'get' => $url,
					));
				}
			}
			
		//} else
		//	throw new CHttpException(404, Yii::t('phrase', 'The requested page does not exist.'));
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='albums-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
