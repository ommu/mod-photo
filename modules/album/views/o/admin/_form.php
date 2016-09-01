<?php
/**
 * Albums (albums)
 * @var $this AdminController
 * @var $model Albums
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link https://github.com/oMMu/Ommu-Photo-Albums
 * @contect (+62)856-299-4114
 *
 */

	if($model->isNewRecord) {
		$validation = false;
	} else {
		$validation = true;
	}
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'id'=>'albums-form',
	'enableAjaxValidation'=>$validation,
	'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

	<?php //begin.Messages ?>
	<div id="ajax-message">
		<?php 
		echo $form->errorSummary($model);
		if(Yii::app()->user->hasFlash('error'))
			echo Utility::flashError(Yii::app()->user->getFlash('error'));
		if(Yii::app()->user->hasFlash('success'))
			echo Utility::flashSuccess(Yii::app()->user->getFlash('success'));
		?>
	</div>
	<?php //begin.Messages ?>

	<h3><?php echo Yii::t('phrase', 'Album Information'); ?></h3>
	<fieldset class="clearfix">
		<div class="clear">
			<div class="left">
				<div class="shadow"></div>

				<div class="clearfix">
					<?php echo $form->labelEx($model,'cat_id'); ?>
					<div class="desc">
						<?php
						$category = AlbumCategory::getCategory();

						if($category != null)
							echo $form->dropDownList($model,'cat_id', $category, array('prompt'=>Yii::t('phrase', 'Select Category')));
						else
							echo $form->dropDownList($model,'cat_id', array('prompt'=>Yii::t('phrase', 'No Parent')));?>
						<?php echo $form->error($model,'cat_id'); ?>
					</div>
				</div>

				<div class="clearfix">
					<?php echo $form->labelEx($model,'title'); ?>
					<div class="desc">
						<?php echo $form->textField($model,'title',array('maxlength'=>128, 'class'=>'span-8')); ?>
						<?php echo $form->error($model,'title'); ?>
					</div>
				</div>

				<div class="clearfix">
					<?php echo $form->labelEx($model,'quote'); ?>
					<div class="desc">
						<?php 
						//echo $form->textArea($model,'quote',array('rows'=>6, 'cols'=>50, 'class'=>'span-10 small'));
						$this->widget('application.extensions.imperavi.ImperaviRedactorWidget', array(
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
						<span class="small-px">Note : add {$quote} in description albums</span>
						<?php echo $form->error($model,'quote'); ?>
					</div>
				</div>

				<div class="clearfix">
					<?php echo $form->labelEx($model,'body'); ?>
					<div class="desc">
						<?php 
						//echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50, 'class'=>'span-10 small'));
						$this->widget('application.extensions.imperavi.ImperaviRedactorWidget', array(
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
		
				<?php if(!$model->isNewRecord || ($model->isNewRecord && $setting->meta_keyword != '')) {?>
				<div class="clearfix">
					<?php echo $form->labelEx($model,'keyword'); ?>
					<div class="desc">
						<?php 
						if(!$model->isNewRecord) {
							//echo $form->textField($model,'keyword',array('maxlength'=>32,'class'=>'span-6'));
							$url = Yii::app()->controller->createUrl('o/tag/add', array('type'=>'album'));
							$album = $model->album_id;
							$tagId = 'Albums_keyword';
							$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
								'model' => $model,
								'attribute' => 'keyword',
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
							echo $form->error($model,'keyword');
						}?>
						<div id="keyword-suggest" class="suggest clearfix">
							<?php 
							$arrKeyword = explode(',', $setting->meta_keyword);
							foreach($arrKeyword as $row) {?>
								<div class="d"><?php echo trim($row);?></div>
							<?php }
							if(!$model->isNewRecord) {
								if($tag != null) {
									foreach($tag as $key => $val) {?>
									<div><?php echo $val->tag_TO->body;?><a href="<?php echo Yii::app()->controller->createUrl('o/tag/delete',array('id'=>$val->id,'type'=>'album'));?>" title="<?php echo Yii::t('phrase', 'Delete');?>"><?php echo Yii::t('phrase', 'Delete');?></a></div>
								<?php }
								}
							}?>
						</div>
					</div>
				</div>
				<?php }?>

			</div>

			<div class="right">
				<div class="shadow"></div>

				<?php if($model->isNewRecord) {?>
				<div class="clearfix">
					<label><?php echo $model->getAttributeLabel('media');?></label>
					<div class="desc">
						<?php echo $form->fileField($model,'media',array('maxlength'=>64)); ?>
						<?php echo $form->error($model,'media'); ?>
					</div>
				</div>
				<?php }?>
				
				<?php if(OmmuSettings::getInfo(site_type) == '1') {?>
				<div class="clearfix publish">
					<?php echo $form->labelEx($model,'comment_code'); ?>
					<div class="desc">
						<?php echo $form->checkBox($model,'comment_code'); ?><label><?php echo $model->getAttributeLabel('comment_code');?></label>
						<?php echo $form->error($model,'comment_code'); ?>
					</div>
				</div>
				<?php } else {
					$model->comment_code = 0;
					echo $form->hiddenField($model,'comment_code');
				}?>

				<?php if(OmmuSettings::getInfo(site_headline) == '1') {?>
				<div class="clearfix publish">
					<?php echo $form->labelEx($model,'headline'); ?>
					<div class="desc">
						<?php echo $form->checkBox($model,'headline'); ?><label><?php echo $model->getAttributeLabel('headline');?></label>
						<?php echo $form->error($model,'headline'); ?>
					</div>
				</div>
				<?php } else {
					$model->headline = 0;
					echo $form->hiddenField($model,'headline');
				}?>

				<div class="clearfix publish">
					<?php echo $form->labelEx($model,'publish'); ?>
					<div class="desc">
						<?php echo $form->checkBox($model,'publish'); ?><label><?php echo $model->getAttributeLabel('publish');?></label>
						<?php echo $form->error($model,'publish'); ?>
					</div>
				</div>
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


