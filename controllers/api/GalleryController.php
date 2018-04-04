<?php
/**
 * GalleryController
 * @var $this GalleryController
 * @var $model Albums
 * @var $form CActiveForm
 *
 * Reference start
 * TOC :
 *	Index
 *	Main
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 2 September 2016, 14:46 WIB
 * @link https://github.com/ommu/ommu-photo
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
	 * Initialize public template
	 */
	public function init() 
	{
		$arrThemes = Utility::getCurrentTemplate('public');
		Yii::app()->theme = $arrThemes['folder'];
		$this->layout = $arrThemes['layout'];
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
		$action = trim(Yii::app()->getRequest()->getParam('action'));
		
		if(isset(Yii::app()->getRequest()->getParam('action'))) {
			if(Yii::app()->getRequest()->getParam('action') == 'pexeto_get_portfolio_items') {
				if(isset($_GET['cat']) && $_GET['cat'] != '') {
					$album_id = trim($_GET['cat']);
					$pagesize = trim($_GET['number']);
					$offset = trim($_GET['offset']);
					
					if(!isset(Yii::app()->session['exhibition_id']) || (isset(Yii::app()->session['exhibition_id']) && $album_id != Yii::app()->session['exhibition_id']))
						Yii::app()->session['exhibition_id'] = $album_id;
				
					$criteria=new CDbCriteria;
					$criteria->compare('t.album_id', $album_id);
					
					$dataProvider = new CActiveDataProvider('ViewAlbumPhotoTag', array(
						'criteria' => $criteria,
						'pagination' => array(
							'pageSize' => $pagesize != null && $pagesize != '' ? $pagesize : 10,
							'currentPage' => $offset != 0 ? ($offset % $pagesize) + 1 : 0, 
						),
					));
				
					// photo is no tags
					$criteriaNoTag=new CDbCriteria;
					$criteriaNoTag->with = array(
						'view' => array(
							'alias' => 'view',
						),
					);
					$criteriaNoTag->compare('t.publish', 1);
					$criteriaNoTag->compare('t.album_id', $album_id);
					$criteriaNoTag->compare('view.tag', 0);
					
					$photoNoTag = AlbumPhoto::model()->findAll($criteriaNoTag);
					
					//url and directory path
					$album_url = Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->request->baseUrl;
					$album_path = 'public/album/'.$album_id;
					
					if($photoNoTag != null) {
						if($photoNoTag[0]->media != '' && file_exists($album_path.'/'.$photoNoTag[0]->media)) {
							$titleTag = 'Lainnya';
							$album_photo = $album_url.'/'.$album_path.'/'.$photoNoTag[0]->media;
							$dataPhotoNoTag = array(
								'id' => 0,
								'title' => ucwords(strtolower($titleTag)),
								'pr' => $album_photo,
								'col' => 1,
								'row' => 1,
								'image' => Utility::getTimThumb($album_photo, 380, 235, 1),
								'cat' => $photoNoTag[0]->album->title,
								'slug' => Utility::getUrlTitle($titleTag),
								'link' => Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->controller->createUrl('exhibition/view', array('id' => $album_id, 'tag' => 0, 'slug' => Utility::getUrlTitle($titleTag))),
								'fullwidth' => false,
								'slider' => true,
								'imgnum' => count($photoNoTag),
							);
						}
					}
					
					$model = $dataProvider->getData();
					
					// pager
					$pager = OFunction::getDataProviderPager($dataProvider);
					$get = array_merge($_GET, array($pager['pageVar'] => $pager['nextPage']));
					$nextPager = $pager['nextPage'] != 0 ? OFunction::validHostURL(Yii::app()->controller->createUrl('main', $get)) : '-';
					
					if(!empty($model)) {
						foreach($model as $key => $val) {
							if($val->photo->media != '' && file_exists($album_path.'/'.$val->photo->media)) {
								$album_photo = $album_url.'/'.$album_path.'/'.$val->photo->media;
								$data[] = array(
									'id' => $val->tag_id,
									'title' => ucwords(strtolower($val->tag->body)),
									'pr' => $album_photo,
									'col' => 1,
									'row' => 1,
									'image' => Utility::getTimThumb($album_photo, 380, 235, 1),
									'cat' => $val->album->title,
									'slug' => $val->tags,
									'link' => Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->controller->createUrl('exhibition/view', array('id' => $album_id, 'tag' => $val->tag_id, 'slug' => $val->tags)),
									'fullwidth' => false,
									'slider' => true,
									'imgnum' => $val->photos,
								);
							}
						}
						if($nextPager == '-' && $photoNoTag != null)
							$data[] = $dataPhotoNoTag;
						
					} else {
						if($photoNoTag != null)
							$data[] = $dataPhotoNoTag;
						else
							$data = array();
					}
						
					$return = array(
						'items' => $data,
						'more' => $nextPager != '-' ? true : false,
					);
					if(isset($_GET['itemsMap']) && $_GET['itemsMap'] == 'true') {
						$itemsMap = ViewAlbumPhotoTag::model()->findAll($criteria);
						if($itemsMap != null) {
							foreach($itemsMap as $key => $row) {
								$item[] = array(
									'slug' => $row->tags,
								);
							}
							if($photoNoTag != null) {
								$item[] = array(
									'slug' => Utility::getUrlTitle('Lainnya'),
								);								
							}
						}
						$return['itemsMap'] = $item;
					}
					
					$this->_sendResponse(200, CJSON::encode($this->renderJson($return)));	
					
				} else
					$this->redirect(Yii::app()->createUrl('site/index'));
				
			} else if(Yii::app()->getRequest()->getParam('action') == 'pexeto_get_portfolio_content') {
				//if(isset($_GET['cat']) && $_GET['cat'] != '') {
					if(isset($_GET['cat']) && $_GET['cat'] != '')
						$album_id = $_GET['cat'];
					else
						$album_id = Yii::app()->session['exhibition_id'];
					$pagesize = trim($_GET['number']);
					$itemslug = trim($_GET['itemslug']);
					
					//url and directory path
					$album_url = Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->request->baseUrl;
					$album_path = 'public/album/'.$album_id;
					
					if($itemslug != 'lainnya') {
						$slug = ViewAlbumPhotoTag::model()->findByAttributes(array('album_id' => $album_id, 'tags'=>$itemslug), array(
							'select' => 'tag_id, tags',
						));					
					
						$criteria=new CDbCriteria;
						$criteria->with = array(
							'photo' => array(
								'alias'=>'photo',
							),
						);
						$criteria->compare('photo.publish', 1);
						$criteria->compare('photo.album_id', $album_id);
						$criteria->compare('t.tag_id', $slug->tag_id);
						
						$model = AlbumPhotoTag::model()->findAll($criteria);
					
						if($model != null) {
							foreach($model as $key => $item) {
								if($item->photo->media != '' && file_exists($album_path.'/'.$item->photo->media)) {
									$album_photo = $album_url.'/'.$album_path.'/'.$item->photo->media;
									$data[] = array(
										'img' => $album_photo,
										'desc' => $item->photo->caption != '' ? $item->photo->caption : '-',
										'thumb' => Utility::getTimThumb($album_photo, 150, 150, 1),
									);
								}
							}
							
						} else
							$data = array();
						
					} else {
						$criteria=new CDbCriteria;
						$criteria->with = array(
							'view' => array(
								'alias' => 'view',
							),
						);
						$criteria->compare('t.publish', 1);
						$criteria->compare('t.album_id', $album_id);
						$criteria->compare('view.tag', 0);
						
						$model = AlbumPhoto::model()->findAll($criteria);
					
						if($model != null) {
							foreach($model as $key => $item) {
								if($item->media != '' && file_exists($album_path.'/'.$item->media)) {
									$album_photo = $album_url.'/'.$album_path.'/'.$item->media;
									$data[] = array(
										'img' => $album_photo,
										'desc' => $item->caption != '' ? $item->caption : '-',
										'thumb' => Utility::getTimThumb($album_photo, 150, 150, 1),
									);
								}
							}							
						} else
							$data = array();						
					}
					
					$itemslug = $itemslug != 'lainnya' ? $slug->tags : $itemslug;
					$tag_id = $itemslug != 'lainnya' ? $slug->tag_id : 0;
					$return = array(
						'title' => $itemslug != 'lainnya' ? ucwords(strtolower($slug->tag->body)) : '',
						'slug' => $itemslug,
						'link' => Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->controller->createUrl('exhibition/view', array('id' => $album_id, 'tag' => $tag_id, 'slug' => $itemslug)),
						'fullwidth' => false,
						'images' => $data,
					);
					$this->_sendResponse(200, CJSON::encode($this->renderJson($return)));	
					
				//} else
				//	$this->redirect(Yii::app()->createUrl('site/index'));
			}
		} else
			$this->redirect(Yii::app()->createUrl('site/index'));
	}
	
	public function getCountPhotoInTag($album=null, $tag)
	{
		$criteria=new CDbCriteria;
		$criteria->with = array(
			'photo' => array(
				'alias' => 'photo',
			),
			'photo.album' => array(
				'alias' => 'album',
			),
		);
		$criteria->compare('t.tag_id', $tag);
		$criteria->compare('photo.publish', 1);
		if($album != null)
			$criteria->compare('album.album_id', $album);
		
		$model = AlbumPhotoTag::model()->count($criteria);
		
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='albums-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
