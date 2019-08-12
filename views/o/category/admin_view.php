<?php
/**
 * Album Categories (album-category)
 * @var $this CategoryController
 * @var $model AlbumCategory
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 26 August 2016, 23:10 WIB
 * @link https://github.com/ommu/mod-photo
 *
 */

	$this->breadcrumbs=array(
		'Album Categories'=>array('manage'),
		$model->name,
	);
?>

<div class="box">
	<?php $this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
				'name'=>'cat_id',
				'value'=>$model->cat_id,
			),
			array(
				'name'=>'publish',
				'value'=>$this->quickAction(Yii::app()->controller->createUrl('publish', array('id'=>$model->cat_id)), $model->publish),
				'type'=>'raw',
			),
			array(
				'name'=>'name',
				'value'=>Phrase::trans($model->name),
			),
			array(
				'name'=>'desc',
				'value'=>Phrase::trans($model->desc),
			),
			array(
				'name'=>'default',
				'value'=>$model->default == 1 ? Yii::t('phrase', 'Yes') : Yii::t('phrase', 'No'),
				'type'=>'raw',
			),
			array(
				'name'=>'default_setting',
				'value'=>$model->default_setting,
			),
			array(
				'name'=>'photo_limit',
				'value'=>$model->photo_limit,
			),
			array(
				'name'=>'photo_resize',
				'value'=>$model->photo_resize == 1 ? Yii::t('phrase', 'Yes') : Yii::t('phrase', 'No'),
			),
			array(
				'name'=>'photo_resize_size',
				'value'=>$model->photo_resize_size ? $model->photo_resize_size : '-',
				'type'=>'raw',
			),
			array(
				'name'=>'photo_view_size',
				'value'=>$model->photo_view_size ? $model->photo_view_size : '-',
				'type'=>'raw',
			),
			array(
				'name'=>'creation_date',
				'value'=>!in_array($model->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->creation_date) : '-',
			),
			array(
				'name'=>'creation_search',
				'value'=>$model->creation->displayname ? $model->creation->displayname : '-',
			),
			array(
				'name'=>'modified_date',
				'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->modified_date) : '-',
			),
			array(
				'name'=>'modified_search',
				'value'=>$model->modified->displayname ? $model->modified->displayname : '-',
			),
		),
	)); ?>
</div>
