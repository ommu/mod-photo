<?php
/**
 * Album Views (album-views)
 * @var $this ViewController
 * @var $model AlbumViews
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co) 
 * @created date 4 May 2017, 12:55 WIB
 * @link https://github.com/ommu/mod-photo
 *
 */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	<ul>
		<li>
			<?php echo $model->getAttributeLabel('view_id'); ?><br/>
			<?php echo $form->textField($model,'view_id'); ?>
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
			<?php echo $model->getAttributeLabel('user_id'); ?><br/>
			<?php echo $form->textField($model,'user_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('view_date'); ?><br/>
			<?php echo $form->textField($model,'view_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('view_ip'); ?><br/>
			<?php echo $form->textField($model,'view_ip'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('deleted_date'); ?><br/>
			<?php echo $form->textField($model,'deleted_date'); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
