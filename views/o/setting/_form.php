<?php
/**
 * Album Settings (album-setting)
 * @var $this SettingController
 * @var $model AlbumSetting
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-photo
 *
 */
 
	$cs = Yii::app()->getClientScript();
$js=<<<EOP
	$('input[name="AlbumSetting[photo_resize]"]').on('change', function() {
		var id = $(this).val();
		if(id == '1') {
			$('div#resize_size').slideDown();
		} else {
			$('div#resize_size').slideUp();
		}
	});
	
	$('select#AlbumSetting_headline').on('change', function() {
		var id = $(this).val();
		if(id == '1') {
			$('div#headline').slideDown();
		} else {
			$('div#headline').slideUp();
		}
	});
EOP;
	$cs->registerScript('resize', $js, CClientScript::POS_END); 
?>

<?php $form=$this->beginWidget('application.libraries.core.components.system.OActiveForm', array(
	'id'=>'album-setting-form',
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
			<label>
				<?php echo $model->getAttributeLabel('license');?> <span class="required">*</span><br/>
				<span><?php echo Yii::t('phrase', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.');?></span>
			</label>
			<div class="desc">
				<?php 
				if($model->isNewRecord || (!$model->isNewRecord && $model->license == ''))
					$model->license = AlbumSetting::getLicense();
			
				if($model->isNewRecord || (!$model->isNewRecord && $model->license == ''))
					echo $form->textField($model,'license',array('maxlength'=>32,'class'=>'span-4'));
				else
					echo $form->textField($model,'license',array('maxlength'=>32,'class'=>'span-4','disabled'=>'disabled'));?>
				<?php echo $form->error($model,'license'); ?>
				<span class="small-px"><?php echo Yii::t('phrase', 'Format: XXXX-XXXX-XXXX-XXXX');?></span>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'permission'); ?>
			<div class="desc">
				<span class="small-px"><?php echo Yii::t('phrase', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.');?></span>
				<?php 
				if($model->isNewRecord && !$model->getErrors())
					$model->permission = 1;
				echo $form->radioButtonList($model, 'permission', array(
					1 => Yii::t('phrase', 'Yes, the public can view album unless they are made private.'),
					0 => Yii::t('phrase', 'No, the public cannot view video feeder.'),
				)); ?>
				<?php echo $form->error($model,'permission'); ?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'meta_description'); ?>
			<div class="desc">
				<?php echo $form->textArea($model,'meta_description',array('rows'=>6, 'cols'=>50, 'class'=>'span-7 smaller')); ?>
				<?php echo $form->error($model,'meta_description'); ?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'meta_keyword'); ?>
			<div class="desc">
				<?php echo $form->textArea($model,'meta_keyword',array('rows'=>6, 'cols'=>50, 'class'=>'span-7 smaller')); ?>
				<?php echo $form->error($model,'meta_keyword'); ?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'gridview_column'); ?>
			<div class="desc">
				<?php 
				$customField = array(
					'creation_search' => $album->getAttributeLabel('creation_search'),
					'creation_date' => $album->getAttributeLabel('creation_date'),
					'photo_search' => $album->getAttributeLabel('photo_search'),
					'view_search' => $album->getAttributeLabel('view_search'),
					'like_search' => $album->getAttributeLabel('like_search'),
					'tag_search' => $album->getAttributeLabel('tag_search'),
				);
				if(!$model->getErrors())
					$model->gridview_column = unserialize($model->gridview_column);
				echo $form->checkBoxList($model,'gridview_column', $customField); ?>
				<?php echo $form->error($model,'gridview_column'); ?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'headline'); ?>
			<div class="desc">
				<?php 
				if($model->isNewRecord && !$model->getErrors())
					$model->headline = 1;
				echo $form->dropDownLIst($model,'headline', array(
					'1' => Yii::t('phrase', 'Enable'),
					'0' => Yii::t('phrase', 'Disable'),
				)); ?>
				<?php echo $form->error($model,'headline'); ?>
			</div>
		</div>
		
		<div id="headline" class="<?php echo $model->headline == 0 ? 'hide' : '';?>">
			<div class="clearfix">
				<?php echo $form->labelEx($model,'headline_limit'); ?>
				<div class="desc">
					<?php 
					if($model->isNewRecord && !$model->getErrors())
						$model->headline_limit = 0;
					echo $form->textField($model,'headline_limit', array('maxlength'=>3, 'class'=>'span-2')); ?>
					<?php echo $form->error($model,'headline_limit'); ?>
				</div>
			</div>

			<div class="clearfix">
				<?php echo $form->labelEx($model,'headline_category'); ?>
				<div class="desc">
					<?php 
					$parent = null;
					$category = AlbumCategory::getCategory(1);
					if(!$model->getErrors())
						$model->headline_category = unserialize($model->headline_category);
					if($category != null)
						echo $form->checkBoxList($model,'headline_category', $category);
					else
						echo $form->checkBoxList($model,'headline_category', array('prompt'=>Yii::t('phrase', 'No Categories'))); ?>
					<?php echo $form->error($model,'headline_category'); ?>
				</div>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'photo_limit'); ?>
			<div class="desc">
				<?php echo $form->textField($model,'photo_limit', array('class'=>'span-2')); ?>
				<?php echo $form->error($model,'photo_limit'); ?>
			</div>
		</div>

		<div class="clearfix">
			<label><?php echo Yii::t('phrase', 'Photo Setting');?> <span class="required">*</span></label>
			<div class="desc">
				<p><?php echo $model->getAttributeLabel('photo_resize');?></p>
				<?php 
				if($model->isNewRecord && !$model->getErrors())
					$model->photo_resize = 0;
				echo $form->radioButtonList($model, 'photo_resize', array(
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

		<div class="clearfix">
			<?php echo $form->labelEx($model,'photo_file_type'); ?>
			<div class="desc">
				<?php 
				if(!$model->getErrors()) {
					$photo_file_type = unserialize($model->photo_file_type);
					if(!empty($photo_file_type))
						$model->photo_file_type = Utility::formatFileType($photo_file_type, false);
					else
						$model->photo_file_type = 'jpg, png, bmp';
				}
				echo $form->textField($model,'photo_file_type', array('class'=>'span-6')); ?>
				<?php echo $form->error($model,'photo_file_type'); ?>
				<span class="small-px">pisahkan jenis file dengan koma (,). example: "jpg, png, bmp"</span>
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