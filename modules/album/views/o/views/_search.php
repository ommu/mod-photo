<?php
/**
 * Album Views (album-views)
 * @var $this ViewsController
 * @var $model AlbumViews
 * @var $form CActiveForm
 * version: 0.1.4
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co) 
 * @created date 4 May 2017, 12:55 WIB
 * @link https://github.com/ommu/Photo-Albums
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
			<?php echo $model->getAttributeLabel('view_id'); ?><br/>
			<?php echo $form->textField($model,'view_id',array('size'=>11,'maxlength'=>11)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('publish'); ?><br/>
			<?php echo $form->textField($model,'publish'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('album_id'); ?><br/>
			<?php echo $form->textField($model,'album_id',array('size'=>11,'maxlength'=>11)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('user_id'); ?><br/>
			<?php echo $form->textField($model,'user_id',array('size'=>11,'maxlength'=>11)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('view_date'); ?><br/>
			<?php echo $form->textField($model,'view_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('view_ip'); ?><br/>
			<?php echo $form->textField($model,'view_ip',array('size'=>20,'maxlength'=>20)); ?>
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
