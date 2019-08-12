<?php
/**
 * Album Categories (album-category)
 * @var $this CategoryController
 * @var $model AlbumCategory
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 26 August 2016, 23:10 WIB
 * @link https://github.com/ommu/mod-photo
 *
 */

	$this->breadcrumbs=array(
		'Album Categories'=>array('manage'),
		Yii::t('phrase', 'Create'),
	);
?>

<div class="form" name="post-on">
	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
