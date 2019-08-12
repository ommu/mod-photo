<?php
/**
 * Album Photos (album-photo)
 * @var $this PhotoController
 * @var $model AlbumPhoto
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-photo
 *
 */

	$this->breadcrumbs=array(
		'Album Photos'=>array('manage'),
		$model->media_id=>array('view','id'=>$model->media_id),
		Yii::t('phrase', 'Update'),
	);
?>

<div class="form">
	<?php echo $this->renderPartial('_form', array(
		'model'=>$model,
		'photo_file_type'=>$photo_file_type,
	)); ?>
</div>
