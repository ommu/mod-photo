<?php
/**
* SyncController
* Handle SyncController
* Copyright (c) 2013, Ommu Platform (ommu.co). All rights reserved.
* version: 2.5.0
* Reference start
*
* TOC :
*	Index
*	IndexFile
*	IndexImage
*
* @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
* @copyright Copyright (c) 2012 Ommu Platform (ommu.co)
* @link http://company.ommu.co
* @contact (+62)856-299-4114
*
*----------------------------------------------------------------------------------------------------------
*/

class SyncController extends Controller
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
			if(Yii::app()->user->level == 1) {
				$arrThemes = Utility::getCurrentTemplate('admin');
				Yii::app()->theme = $arrThemes['folder'];
				$this->layout = $arrThemes['layout'];
			} else {
				$this->redirect(Yii::app()->createUrl('site/login'));
			}
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
				'actions'=>array(),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level)',
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('indeximage'),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level) && (Yii::app()->user->level == 1)',
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
		$this->redirect(array('admin/manage'));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndexImage() 
	{
		ini_set('max_execution_time', 0);
		ob_start();
		
		$criteria=new CDbCriteria;
		/* $criteria->condition = 'media_image <> :media_image';
		$criteria->params = array(
			':media_image'=>'',
		); */
		$criteria->order = 'album_id DESC';
		$album = Albums::model()->findAll($criteria);
		
		$path = 'public/album/';
		
		foreach($album as $key => $item) {
			// Add User Folder
			$album_path = $path.$item->album_id;
			if(!file_exists($album_path)) {
				@mkdir($article_path, 0755, true);

				// Add File in Album Folder (index.php)
				$newFile = $album_path.'/index.php';
				$FileHandle = fopen($newFile, 'w');
			} else {
				@chmod($article_path, 0755, true);
			}
			
			$mediaImages = 'public/article/old/'.$item->noted;
			if(file_exists($mediaImages)) {				
				$images = new AlbumPhoto;
				$images->album_id = $item->album_id;
				$images->cover = 1;
				$images->media = $item->noted;
				if($images->save()) {				
					rename($mediaImages, $album_path.'/'.$item->noted);					
				}
			}
		}
		
		echo 'Ommu Done..';
		ob_end_flush();		
	}
}
