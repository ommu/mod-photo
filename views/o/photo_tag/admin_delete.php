<?php
/**
 * Album Photo Tags (album-photo-tag)
 * @var $this PhototagController
 * @var $model AlbumPhotoTag
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 1 September 2016, 11:58 WIB
 * @link https://github.com/ommu/mod-photo
 *
 */

	$this->breadcrumbs=array(
		'Album Photo Tags'=>array('manage'),
		Yii::t('phrase', 'Delete'),
	);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'album-photo-tag-form',
	'enableAjaxValidation'=>true,
)); ?>

	<div class="dialog-content">
		<?php echo Yii::t('phrase', 'Are you sure you want to delete this item?');?>
	</div>
	<div class="dialog-submit">
		<?php echo CHtml::submitButton(Yii::t('phrase', 'Delete'), array('onclick' => 'setEnableSave()')); ?>
		<?php echo CHtml::button(Yii::t('phrase', 'Cancel'), array('id'=>'closed')); ?>
	</div>
	
<?php $this->endWidget(); ?>
