<?php
/**
 * Album Photos (album-photo)
 * @var $this PhotoController * @var $model AlbumPhoto * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'id'=>'album-photo-form',
	'enableAjaxValidation'=>true,
	'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

<?php //begin.Messages ?>
<div id="ajax-message">
	<?php echo $form->errorSummary($model); ?>
</div>
<?php //begin.Messages ?>

<fieldset>

	<?php
		if(!$model->isNewRecord) {
			$model->old_media = $model->media;
			echo $form->hiddenField($model,'old_media');
			if($model->media != '') {
				$file = Yii::app()->request->baseUrl.'/public/album/'.$model->album_id.'/'.$model->media;
				$media = '<img src="'.Utility::getTimThumb($file, 300, 500, 3).'" alt="">';
				echo '<div class="clearfix">';
				echo $form->labelEx($model,'old_media');
				echo '<div class="desc">'.$media.'</div>';
				echo '</div>';
			}
		}	
	?>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'media'); ?>
		<div class="desc">
			<?php echo $form->fileField($model,'media'); ?>
			<?php echo $form->error($model,'media'); ?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'desc'); ?>
		<div class="desc">
			<?php echo $form->textArea($model,'desc',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($model,'desc'); ?>
		</div>
	</div>
	
	<div class="clearfix publish">
		<?php echo $form->labelEx($model,'cover'); ?>
		<div class="desc">
			<?php echo $form->checkBox($model,'cover'); ?>
			<?php echo $form->labelEx($model,'cover'); ?>
			<?php echo $form->error($model,'cover'); ?>
		</div>
	</div>

	<div class="clearfix publish">
		<?php echo $form->labelEx($model,'publish'); ?>
		<div class="desc">
			<?php echo $form->checkBox($model,'publish'); ?>
			<?php echo $form->labelEx($model,'publish'); ?>
			<?php echo $form->error($model,'publish'); ?>
		</div>
	</div>

	<div class="submit clearfix">
		<label>&nbsp;</label>
		<div class="desc">
			<?php echo CHtml::submitButton($model->isNewRecord ? Phrase::trans(1,0) : Phrase::trans(2,0), array('onclick' => 'setEnableSave()')); ?>
		</div>
	</div>

</fieldset>
<?php $this->endWidget(); ?>


