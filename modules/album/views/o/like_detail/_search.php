<?php
/**
 * Album Like Details (album-like-detail)
 * @var $this LikedetailController
 * @var $model AlbumLikeDetail
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 4 May 2017, 16:57 WIB
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
			<?php echo $model->getAttributeLabel('id'); ?><br/>
			<?php echo $form->textField($model,'id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('publish'); ?><br/>
			<?php echo $form->textField($model,'publish'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('like_id'); ?><br/>
			<?php echo $form->textField($model,'like_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('likes_date'); ?><br/>
			<?php echo $form->textField($model,'likes_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('likes_ip'); ?><br/>
			<?php echo $form->textField($model,'likes_ip'); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
