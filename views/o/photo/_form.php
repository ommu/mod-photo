<?php
/**
 * Album Photos (album-photo)
 * @var $this PhotoController
 * @var $model AlbumPhoto
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/mod-photo
 *
 */
?>

<?php $form=$this->beginWidget('application.libraries.core.components.system.OActiveForm', array(
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
	
	<?php if(!$model->isNewRecord) {?>
	<div class="form-group row">
		<?php echo $form->labelEx($model,'old_media_i', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
		<div class="col-lg-8 col-md-9 col-sm-12">
			<?php 
			if(!$model->getErrors())
				$model->old_media_i = $model->media;
			echo $form->hiddenField($model,'old_media_i');
			$media = Yii::app()->request->baseUrl.'/public/album/'.$model->album_id.'/'.$model->old_media_i;?>
			<img src="<?php echo Utility::getTimThumb($media, 300, 500, 3);?>" alt="">
		</div>
	</div>
	<?php }?>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'media', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
		<div class="col-lg-8 col-md-9 col-sm-12">
			<?php echo $form->fileField($model,'media'); ?>
			<?php echo $form->error($model,'media'); ?>
			<div class="small-px slient">extensions are allowed: <?php echo Utility::formatFileType($photo_file_type, false);?></div>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'caption', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
		<div class="col-lg-8 col-md-9 col-sm-12">
			<?php echo $form->textArea($model,'caption',array('rows'=>6, 'cols'=>50, 'class'=>'span-7 smaller')); ?>
			<?php echo $form->error($model,'caption'); ?>
		</div>
	</div>
	
	<?php if(!$model->isNewRecord) {?>
	<div class="form-group row">
		<?php echo $form->labelEx($model,'keyword_i', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
		<div class="col-lg-8 col-md-9 col-sm-12">
			<?php 
			if(!$model->isNewRecord) {
				//echo $form->textField($model,'keyword_i',array('maxlength'=>32,'class'=>'span-6'));
				$url = Yii::app()->controller->createUrl('o/phototag/add', array('type'=>'photo'));
				$photo = $model->media_id;
				$tagId = 'AlbumPhoto_keyword_i';
				$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
					'model' => $model,
					'attribute' => 'keyword_i',
					'source' => Yii::app()->createUrl('globaltag/suggest'),
					'options' => array(
						//'delay '=> 50,
						'minLength' => 1,
						'showAnim' => 'fold',
						'select' => "js:function(event, ui) {
							$.ajax({
								type: 'post',
								url: '$url',
								data: { media_id: '$photo', tag_id: ui.item.id, tag: ui.item.value },
								dataType: 'json',
								success: function(response) {
									$('form #$tagId').val('');
									$('form #tag-suggest').append(response.data);
								}
							});

						}"
					),
					'htmlOptions' => array(
						'class'	=> 'span-4',
					),
				));
				echo $form->error($model,'keyword_i');
			}?>
			<div id="tag-suggest" class="suggest clearfix">
				<?php if(!$model->isNewRecord) {
					$tags = $model->tags;
					if(!empty($tags)) {
						foreach($tags as $key => $val) {?>
						<div><?php echo $val->tag->body;?><a href="<?php echo Yii::app()->controller->createUrl('o/phototag/delete',array('id'=>$val->id,'type'=>'photo'));?>" title="<?php echo Yii::t('phrase', 'Delete');?>"><?php echo Yii::t('phrase', 'Delete');?></a></div>
					<?php }
					}
				}?>
			</div>
		</div>
	</div>
	<?php }?>
	
	<div class="form-group row publish">
		<?php echo $form->labelEx($model,'cover', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
		<div class="col-lg-8 col-md-9 col-sm-12">
			<?php echo $form->checkBox($model,'cover'); ?>
			<?php echo $form->labelEx($model, 'cover'); ?>
			<?php echo $form->error($model,'cover'); ?>
		</div>
	</div>

	<div class="form-group row publish">
		<?php echo $form->labelEx($model,'publish', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
		<div class="col-lg-8 col-md-9 col-sm-12">
			<?php echo $form->checkBox($model,'publish'); ?>
			<?php echo $form->labelEx($model, 'publish'); ?>
			<?php echo $form->error($model,'publish'); ?>
		</div>
	</div>

	<div class="submit clearfix">
		<label class="col-form-label col-lg-4 col-md-3 col-sm-12">&nbsp;</label>
		<div class="col-lg-8 col-md-9 col-sm-12">
			<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save'), array('onclick' => 'setEnableSave()')); ?>
		</div>
	</div>

</fieldset>
<?php $this->endWidget(); ?>


