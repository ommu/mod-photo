<?php

class FrontAlbumPhoto extends CWidget
{

	public function init() {
	}

	public function run() {
		$this->renderContent();
	}

	protected function renderContent() {
		//import model
		Yii::import('application.modules.album.models.AlbumPhoto');
		Yii::import('application.modules.album.models.Albums');
		
		$model = AlbumPhoto::model()->findAll(array(
			'select' => 'media_id, album_id, media',
			'order' => 'creation_date DESC',
			'limit' => 9,
		));

		$this->render('front_album_photo',array(
			'model' => $model,
		));
	}
}
