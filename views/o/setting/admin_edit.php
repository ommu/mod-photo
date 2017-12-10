<?php
/**
 * Album Settings (album-setting)
 * @var $this SettingController
 * @var $model AlbumSetting
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-photo
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Album Settings'=>array('manage'),
		$model->id=>array('view','id'=>$model->id),
		'Update',
	);
?>

<div class="form" name="post-on">
	<?php echo $this->renderPartial('_form', array(
		'model'=>$model,
		'album'=>$album,
	)); ?>
</div>
