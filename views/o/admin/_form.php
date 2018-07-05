<?php
/**
 * Albums (albums)
 * @var $this AdminController
 * @var $model Albums
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-photo
 *
 */

	if($model->isNewRecord) {
		$validation = false;
	} else {
		$validation = true;
	}
?>

<?php $form=$this->beginWidget('application.libraries.core.components.system.OActiveForm', array(
	'id'=>'albums-form',
	'enableAjaxValidation'=>$validation,
	'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

	<?php //begin.Messages ?>
	<div id="ajax-message">
		<?php 
		echo $form->errorSummary($model);
		if(Yii::app()->user->hasFlash('error'))
			echo $this->flashMessage(Yii::app()->user->getFlash('error'), 'error');
		if(Yii::app()->user->hasFlash('success'))
			echo $this->flashMessage(Yii::app()->user->getFlash('success'), 'success');
		?>
	</div>
	<?php //begin.Messages ?>

	<h3><?php echo Yii::t('phrase', 'Album Information'); ?></h3>
	<fieldset>
		<div class="row">
			<div class="col-lg-8 col-md-12">

				<div class="form-group row">
					<?php echo $form->labelEx($model,'cat_id', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
					<div class="col-lg-8 col-md-9 col-sm-12">
						<?php
						$category = AlbumCategory::getCategory();

						if($category != null)
							echo $form->dropDownList($model,'cat_id', $category, array('prompt'=>Yii::t('phrase', 'Select Category')));
						else
							echo $form->dropDownList($model,'cat_id', array('prompt'=>Yii::t('phrase', 'No Parent')));?>
						<?php echo $form->error($model,'cat_id'); ?>
					</div>
				</div>

				<div class="form-group row">
					<?php echo $form->labelEx($model,'title', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
					<div class="col-lg-8 col-md-9 col-sm-12">
						<?php echo $form->textField($model,'title', array('maxlength'=>128, 'class'=>'span-8')); ?>
						<?php echo $form->error($model,'title'); ?>
					</div>
				</div>

				<?php if($model->isNewRecord) {?>
				<div class="form-group row">
					<label class="col-form-label col-lg-4 col-md-3 col-sm-12"><?php echo $model->getAttributeLabel('media_i');?></label>
					<div class="col-lg-8 col-md-9 col-sm-12">
						<?php echo $form->fileField($model,'media_i', array('maxlength'=>64)); ?>
						<?php echo $form->error($model,'media_i'); ?>
						<div class="small-px slient">extensions are allowed: <?php echo Utility::formatFileType($photo_file_type, false);?></div>
					</div>
				</div>
				<?php }?>
		
				<div class="form-group row">
					<?php echo $form->labelEx($model,'keyword_i', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
					<div class="col-lg-8 col-md-9 col-sm-12">
						<?php 
						if($model->isNewRecord) {
							echo $form->textArea($model,'keyword_i', array('rows'=>6, 'cols'=>50, 'class'=>'span-10 smaller'));
							
						} else {
							//echo $form->textField($model,'keyword_i', array('maxlength'=>32,'class'=>'span-6'));
							$url = Yii::app()->controller->createUrl('o/tag/add', array('type'=>'album'));
							$album = $model->album_id;
							$tagId = 'Albums_keyword_i';
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
											data: { album_id: '$album', tag_id: ui.item.id, tag: ui.item.value },
											dataType: 'json',
											success: function(response) {
												$('form #$tagId').val('');
												$('form #keyword-suggest').append(response.data);
											}
										});

									}"
								),
								'htmlOptions' => array(
									'class'	=> 'span-6',
								),
							));
							echo $form->error($model,'keyword_i');
						}?>
						<?php if($model->isNewRecord) {?><div class="small-px slient">tambahkan tanda koma (,) jika ingin menambahkan keyword lebih dari satu</div><?php }?>
						<div id="keyword-suggest" class="suggest clearfix">
							<?php 
							if($setting->meta_keyword && $setting->meta_keyword != '-') {
								$arrKeyword = explode(',', $setting->meta_keyword);
								foreach($arrKeyword as $row) {?>
									<div class="d"><?php echo trim($row);?></div>
							<?php }
							}
							if(!$model->isNewRecord) {
								$tags = $model->tags;
								if(!empty($tags)) {
									foreach($tags as $key => $val) {?>
									<div><?php echo $val->tag->body;?><a href="<?php echo Yii::app()->controller->createUrl('o/tag/delete', array('id'=>$val->id,'type'=>'album'));?>" title="<?php echo Yii::t('phrase', 'Delete');?>"><?php echo Yii::t('phrase', 'Delete');?></a></div>
								<?php }
								}
							}?>
						</div>
					</div>
				</div>

			</div>
			<div class="col-lg-4 col-md-12">
				
				<?php if(OmmuSettings::getInfo('site_type') == '1') {?>
				<div class="form-group row publish">
					<?php echo $form->labelEx($model,'comment_code', array('class'=>'col-form-label col-lg-12 col-md-3 col-sm-12')); ?>
					<div class="col-lg-12 col-md-9 col-sm-12">
						<?php echo $form->checkBox($model,'comment_code'); ?>
						<?php echo $form->labelEx($model, 'comment_code'); ?>
						<?php echo $form->error($model,'comment_code'); ?>
					</div>
				</div>
				<?php } else {
					$model->comment_code = 0;
					echo $form->hiddenField($model,'comment_code');
				}?>

				<?php if($setting->headline == 1) {?>
				<div class="form-group row publish">
					<?php echo $form->labelEx($model,'headline', array('class'=>'col-form-label col-lg-12 col-md-3 col-sm-12')); ?>
					<div class="col-lg-12 col-md-9 col-sm-12">
						<?php echo $form->checkBox($model,'headline'); ?>
						<?php echo $form->labelEx($model, 'headline'); ?>
						<?php echo $form->error($model,'headline'); ?>
					</div>
				</div>
				<?php } else {
					$model->headline = 0;
					echo $form->hiddenField($model,'headline');
				}?>

				<div class="form-group row publish">
					<?php echo $form->labelEx($model,'publish', array('class'=>'col-form-label col-lg-12 col-md-3 col-sm-12')); ?>
					<div class="col-lg-12 col-md-9 col-sm-12">
						<?php echo $form->checkBox($model,'publish'); ?>
						<?php echo $form->labelEx($model, 'publish'); ?>
						<?php echo $form->error($model,'publish'); ?>
					</div>
				</div>
			</div>
		</div>

	</fieldset>

	<fieldset>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'quote', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php 
				//echo $form->textArea($model,'quote', array('rows'=>6, 'cols'=>50, 'class'=>'span-10 small'));
				$this->widget('yiiext.imperavi-redactor-widget.ImperaviRedactorWidget', array(
					'model'=>$model,
					'attribute'=>quote,
					// Redactor options
					'options'=>array(
						//'lang'=>'fi',
						'buttons'=>array(
							'html', '|', 
							'bold', 'italic', 'deleted', '|',
						),
					),
					'plugins' => array(
						'fontcolor' => array('js' => array('fontcolor.js')),
						'fullscreen' => array('js' => array('fullscreen.js')),
					),
				)); ?>
				<div class="small-px slient">Note : add {$quote} in description albums</div>
				<?php echo $form->error($model,'quote'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'body', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php 
				//echo $form->textArea($model,'body', array('rows'=>6, 'cols'=>50, 'class'=>'span-10 small'));
				$this->widget('yiiext.imperavi-redactor-widget.ImperaviRedactorWidget', array(
					'model'=>$model,
					'attribute'=>body,
					// Redactor options
					'options'=>array(
						//'lang'=>'fi',
						'buttons'=>array(
							'html', 'formatting', '|', 
							'bold', 'italic', 'deleted', '|',
							'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
							'link', '|',
						),
					),
					'plugins' => array(
						'fontcolor' => array('js' => array('fontcolor.js')),
						'table' => array('js' => array('table.js')),
						'fullscreen' => array('js' => array('fullscreen.js')),
					),
				)); ?>
				<?php echo $form->error($model,'body'); ?>
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


