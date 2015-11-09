<?php

class FrontAlbumRecents extends CWidget
{

	public function init() {
	}

	public function run() {
		$this->renderContent();
	}

	protected function renderContent() {
		$controller = strtolower(Yii::app()->controller->id);
		
		//import model
		Yii::import('application.modules.album.models.AlbumPhoto');
		Yii::import('application.modules.album.models.Albums');
		
		$criteria=new CDbCriteria;
		$criteria->condition = 'publish = :publish';
		$criteria->params = array(
			':publish'=>1,
		);
		$criteria->order = 'creation_date DESC';
		//$criteria->addInCondition('cat_id',array(2,3,5,6,7));
		//$criteria->compare('cat_id',18);
		$criteria->limit = 3;
			
		$model = Albums::model()->findAll($criteria);

		$this->render('front_album_recents',array(
			'model' => $model,
		));	
	}
}
