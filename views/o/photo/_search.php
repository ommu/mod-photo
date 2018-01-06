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
 * @link https://github.com/ommu/ommu-photo
 *
 */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	<ul>
		<li>
			<?php echo $model->getAttributeLabel('media_id'); ?><br/>
			<?php echo $form->textField($model,'media_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('publish'); ?><br/>
			<?php echo $form->textField($model,'publish'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('album_id'); ?><br/>
			<?php echo $form->textField($model,'album_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('cover'); ?><br/>
			<?php echo $form->textField($model,'cover'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('media'); ?><br/>
			<?php echo $form->textField($model,'media'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('caption'); ?><br/>
			<?php echo $form->textArea($model,'caption'); ?>
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

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
