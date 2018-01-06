<?php
/**
 * Album Categories (album-category)
 * @var $this CategoryController
 * @var $model AlbumCategory
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 26 August 2016, 23:10 WIB
 * @link https://github.com/ommu/ommu-photo
 *
 */

	$this->breadcrumbs=array(
		'Album Categories'=>array('manage'),
		'Create',
	);
?>

<div class="form" name="post-on">
	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
