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
 *	AjaxManage
 *	AjaxAdd
 *	AjaxEdit
 *	AjaxDelete
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/Photo-Albums
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
		if(AlbumSetting::getInfo('permission') == 1) {
			$arrThemes = Utility::getCurrentTemplate('public');
			Yii::app()->theme = $arrThemes['folder'];
			$this->layout = $arrThemes['layout'];
		} else {
			$this->redirect(Yii::app()->createUrl('site/login'));
		}
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
				'actions'=>array('ajaxmanage','ajaxadd','ajaxdelete','ajaxcover'),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level)',
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('manage','edit','delete'),
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAjaxManage($id) 
	{
		$model = AlbumPhoto::getPhoto($id);
		$setting = AlbumSetting::model()->findByPk(1,array(
			'select' => 'photo_limit',
		));

		$data = '';
		if($model != null) {			
			foreach($model as $key => $val) {
				$image = Yii::app()->request->baseUrl.'/public/album/'.$val->album_id.'/'.$val->media;
				$url = Yii::app()->controller->createUrl('ajaxdelete', array('id'=>$val->media_id));
				$urlCover = Yii::app()->controller->createUrl('ajaxcover', array('id'=>$val->media_id));
				$data .= '<li>';
				if($val->cover == 0) {
					$data .= '<a id="set-cover" href="'.$urlCover.'" title="'.Yii::t('phrase', 'Set Cover').'">'.Yii::t('phrase', 'Set Cover').'</a>';
				}
				$data .= '<a id="set-delete" href="'.$url.'" title="'.Yii::t('phrase', 'Delete Photo').'">'.Yii::t('phrase', 'Delete Photo').'</a>';
				$data .= '<img src="'.Utility::getTimThumb($image, 320, 250, 1).'" alt="'.$val->album->title.'" />';
				$data .= '</li>';
			}
		}
		if(isset($_GET['replace'])) {
			// begin.Upload Button
			$class = (count($model) == $setting->photo_limit) ? 'class="hide"' : '';
			$url = Yii::app()->controller->createUrl('ajaxadd', array('id'=>$id));
			$data .= '<li id="upload" '.$class.'>';
			$data .= '<a id="upload-gallery" href="'.$url.'" title="'.Yii::t('phrase', 'Upload Photo').'">'.Yii::t('phrase', 'Upload Photo').'</a>';
			$data .= '<img src="'.Utility::getTimThumb(Yii::app()->request->baseUrl.'/public/album/album_plus.png', 320, 250, 1).'" alt="" />';
			$data .= '</li>';
			// end.Upload Button
		}
		
		$data .= '';
		$result['data'] = $data;
		echo CJSON::encode($result);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAjaxAdd($id) 
	{
		$albumPhoto = CUploadedFile::getInstanceByName('namaFile');
		$album_path = "public/album/".$id;
		// Add directory
		if(!file_exists($album_path)) {
			@mkdir($album_path, 0755, true);

			// Add file in directory (index.php)
			$newFile = $album_path.'/index.php';
			$FileHandle = fopen($newFile, 'w');
		} else
			@chmod($album_path, 0755, true);
		
		$fileName	= time().'_'.$id.'_'.Utility::getUrlTitle(Albums::getInfo($id, 'title')).'.'.strtolower($albumPhoto->extensionName);
		if($albumPhoto->saveAs($album_path.'/'.$fileName)) {
			$model = new AlbumPhoto;
			$model->album_id = $id;
			$model->media = $fileName;
			if($model->save()) {
				$url = Yii::app()->controller->createUrl('ajaxmanage', array('id'=>$model->album_id));
				echo CJSON::encode(array(
					'id' => 'media-render',
					'get' => $url,
				));
			}
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionAjaxCover($id) 
	{
		if(!isset($_GET['type'])) {
			$arrThemes = Utility::getCurrentTemplate('public');
			Yii::app()->theme = $arrThemes['folder'];
			$this->layout = $arrThemes['layout'];
			Utility::applyCurrentTheme($this->module);
		}
		
		$model = $this->loadModel($id);
		
		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			if(isset($id)) {				
				$model->cover = 1;
				
				if($model->update()) {
						$url = Yii::app()->controller->createUrl('ajaxmanage', array('id'=>$model->album_id,'replace'=>'true'));
					echo CJSON::encode(array(
						'type' => 2,
						'id' => 'media-render',
						'get' => $url,
					));
				}
			}

		} else {
			$this->dialogDetail = true;
			$this->dialogGroundUrl = Yii::app()->controller->createUrl('admin/edit', array('id'=>$model->album_id));
			$this->dialogWidth = 350;

			$this->pageTitle = Yii::t('phrase', 'Cover Photo');
			$this->pageDescription = '';
			$this->pageMeta = '';
			$this->render('front_cover');
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
