<?php
/**
 * Albums (albums)
 * @var $this AdminController * @var $model Albums * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
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
			<?php echo $form->textField($model,'album_id',array('size'=>11,'maxlength'=>11)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('publish'); ?><br/>
			<?php echo $form->textField($model,'publish'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('user_id'); ?><br/>
			<?php echo $form->textField($model,'user_id',array('size'=>11,'maxlength'=>11)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('media_id'); ?><br/>
			<?php echo $form->textField($model,'media_id',array('size'=>11,'maxlength'=>11)); ?>
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
			<?php echo $model->getAttributeLabel('title'); ?><br/>
			<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>64)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('body'); ?><br/>
			<?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('quote'); ?><br/>
			<?php echo $form->textArea($model,'quote',array('rows'=>6, 'cols'=>50)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('photos'); ?><br/>
			<?php echo $form->textField($model,'photos'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('comment'); ?><br/>
			<?php echo $form->textField($model,'comment'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('view'); ?><br/>
			<?php echo $form->textField($model,'view'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('likes'); ?><br/>
			<?php echo $form->textField($model,'likes'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('creation_date'); ?><br/>
			<?php echo $form->textField($model,'creation_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('modified_date'); ?><br/>
			<?php echo $form->textField($model,'modified_date'); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Phrase::trans(3,0)); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
