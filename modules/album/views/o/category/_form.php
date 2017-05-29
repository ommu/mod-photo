<?php
/**
 * Album Categories (album-category)
 * @var $this CategoryController
 * @var $model AlbumCategory
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 26 August 2016, 23:10 WIB
 * @link https://github.com/ommu/mod-photo-album
 * @contact (+62)856-299-4114
 *
 */
 
	$cs = Yii::app()->getClientScript();
$js=<<<EOP
	$('#AlbumCategory_default_setting').on('change', function() {
		var id = $(this).prop('checked');		
		if(id == true) {
			$('div#default-setting').slideUp();
		} else {
			$('div#default-setting').slideDown();
		}
	});
	$('input[name="AlbumCategory[photo_resize]"]').on('change', function() {
		var id = $(this).val();
		if(id == '1') {
			$('div#resize_size').slideDown();
		} else {
			$('div#resize_size').slideUp();
		}
	});
EOP;
	$cs->registerScript('form', $js, CClientScript::POS_END); 
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'id'=>'album-category-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

<?php //begin.Messages ?>
<div id="ajax-message">
	<?php echo $form->errorSummary($model); ?>
</div>
<?php //begin.Messages ?>

<fieldset>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'title_i'); ?>
		<div class="desc">
			<?php 
			if(!$model->getErrors())
				$model->title_i = Phrase::trans($model->name);
			echo $form->textField($model,'title_i',array('maxlength'=>32)); ?>
			<?php echo $form->error($model,'title_i'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'description_i'); ?>
		<div class="desc">
			<?php 
			if(!$model->getErrors())
				$model->description_i = Phrase::trans($model->desc);
			echo $form->textArea($model,'description_i',array('rows'=>6, 'cols'=>50, 'maxlength'=>128, 'class'=>'span-7 smaller')); ?>
			<?php echo $form->error($model,'description_i'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'default_setting'); ?>
		<div class="desc">
			<?php echo $form->checkBox($model,'default_setting'); ?>
			<?php echo $form->error($model,'default_setting'); ?>
			<div class="small-px silent pt-10"><?php echo Yii::t('phrase', 'Check jika ingin menggunakan pengaturan standar');?></div>
		</div>
	</div>

	<div id="default-setting" class="<?php echo $model->default_setting == 1 ? 'hide' : '';?>">
		<div class="clearfix">
			<label><?php echo $model->getAttributeLabel('photo_limit');?> <span class="required">*</span></label>
			<div class="desc">
				<?php echo $form->textField($model,'photo_limit'); ?>
				<?php echo $form->error($model,'photo_limit'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>

		<div class="clearfix">
			<label><?php echo Yii::t('phrase', 'Photo Setting');?> <span class="required">*</span></label>
			<div class="desc">
				<p><?php echo $model->getAttributeLabel('photo_resize');?></p>
				<?php echo $form->radioButtonList($model, 'photo_resize', array(
					0 => Yii::t('phrase', 'No, not resize photo after upload.'),
					1 => Yii::t('phrase', 'Yes, resize photo after upload.'),
				)); ?>
					
				<?php if(!$model->getErrors()) {
					$model->photo_resize_size = unserialize($model->photo_resize_size);
					$model->photo_view_size = unserialize($model->photo_view_size);
				}?>
					
				<div id="resize_size" class="mt-15 <?php echo $model->photo_resize == 0 ? 'hide' : '';?>">
					<?php echo Yii::t('phrase', 'Width').': ';?><?php echo $form->textField($model,'photo_resize_size[width]',array('maxlength'=>4,'class'=>'span-2')); ?>&nbsp;&nbsp;&nbsp;
					<?php echo Yii::t('phrase', 'Height').': ';?><?php echo $form->textField($model,'photo_resize_size[height]',array('maxlength'=>4,'class'=>'span-2')); ?>
					<?php echo $form->error($model,'photo_resize_size'); ?>
				</div>
				
				<p><?php echo Yii::t('phrase', 'Large Size');?></p>				
				<?php echo Yii::t('phrase', 'Width').': ';?><?php echo $form->textField($model,'photo_view_size[large][width]',array('maxlength'=>4,'class'=>'span-2')); ?>&nbsp;&nbsp;&nbsp;
				<?php echo Yii::t('phrase', 'Height').': ';?><?php echo $form->textField($model,'photo_view_size[large][height]',array('maxlength'=>4,'class'=>'span-2')); ?>
				<?php echo $form->error($model,'photo_view_size[large]'); ?>
				
				<p><?php echo Yii::t('phrase', 'Medium Size');?></p>
				<?php echo Yii::t('phrase', 'Width').': ';?><?php echo $form->textField($model,'photo_view_size[medium][width]',array('maxlength'=>3,'class'=>'span-2')); ?>&nbsp;&nbsp;&nbsp;
				<?php echo Yii::t('phrase', 'Height').': ';?><?php echo $form->textField($model,'photo_view_size[medium][height]',array('maxlength'=>3,'class'=>'span-2')); ?>
				<?php echo $form->error($model,'photo_view_size[medium]'); ?>
				
				<p><?php echo Yii::t('phrase', 'Small Size');?></p>
				<?php echo Yii::t('phrase', 'Width').': ';?><?php echo $form->textField($model,'photo_view_size[small][width]',array('maxlength'=>3,'class'=>'span-2')); ?>&nbsp;&nbsp;&nbsp;
				<?php echo Yii::t('phrase', 'Height').': ';?><?php echo $form->textField($model,'photo_view_size[small][height]',array('maxlength'=>3,'class'=>'span-2')); ?>
				<?php echo $form->error($model,'photo_view_size[small]'); ?>	
			</div>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'default'); ?>
		<div class="desc">
			<?php echo $form->checkBox($model,'default'); ?>
			<?php echo $form->error($model,'default'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'publish'); ?>
		<div class="desc">
			<?php echo $form->checkBox($model,'publish'); ?>
			<?php echo $form->error($model,'publish'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="submit clearfix">
		<label>&nbsp;</label>
		<div class="desc">
			<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save'), array('onclick' => 'setEnableSave()')); ?>
		</div>
	</div>

</fieldset>
<?php $this->endWidget(); ?>


