<?php
/**
 * Albums (albums)
 * @var $this AdminController
 * @var $model Albums
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/mod-photo-album
 * @contact (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	<ul>
		<li>
			<?php echo $model->getAttributeLabel('album_id'); ?><br/>
			<?php echo $form->textField($model,'album_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('publish'); ?><br/>
			<?php echo $form->textField($model,'publish'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('cat_id'); ?><br/>
			<?php echo $form->textField($model,'cat_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('title'); ?><br/>
			<?php echo $form->textField($model,'title'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('body'); ?><br/>
			<?php echo $form->textArea($model,'body'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('quote'); ?><br/>
			<?php echo $form->textArea($model,'quote'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('headline'); ?><br/>
			<?php echo $form->textField($model,'headline'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('comment_code'); ?><br/>
			<?php echo $form->textField($model,'comment_code'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('creation_date'); ?><br/>
			<?php echo $form->textField($model,'creation_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('creation_id'); ?><br/>
			<?php echo $form->textField($model,'creation_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('modified_date'); ?><br/>
			<?php echo $form->textField($model,'modified_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('modified_id'); ?><br/>
			<?php echo $form->textField($model,'modified_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('headline_date'); ?><br/>
			<?php echo $form->textField($model,'headline_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('slug'); ?><br/>
			<?php echo $form->textField($model,'slug'); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
