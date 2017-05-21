<?php
/**
 * Album Photos (album-photo)
 * @var $this PhotoController
 * @var $model AlbumPhoto
 * @var $form CActiveForm
 * version: 0.1.4
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/mod-photo-album
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Album Photos'=>array('manage'),
		$model->media_id=>array('view','id'=>$model->media_id),
		'Update',
	);
?>

<div class="form">
	<?php echo $this->renderPartial('_form', array(
		'model'=>$model,
		'photo_file_type'=>$photo_file_type,
	)); ?>
</div>
