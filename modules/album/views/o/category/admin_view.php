<?php
/**
 * Album Categories (album-category)
 * @var $this CategoryController
 * @var $model AlbumCategory
 * version: 0.1.4
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 26 August 2016, 23:10 WIB
 * @link https://github.com/ommu/mod-photo-album
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Album Categories'=>array('manage'),
		$model->name,
	);
?>

<div class="dialog-content">
	<?php $this->widget('application.components.system.FDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
				'name'=>'cat_id',
				'value'=>$model->cat_id,
				//'value'=>$model->cat_id != '' ? $model->cat_id : '-',
			),
			array(
				'name'=>'publish',
				'value'=>$model->publish == '1' ? Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				//'value'=>$model->publish,
			),
			array(
				'name'=>'name',
				'value'=>$model->name,
				//'value'=>$model->name != '' ? $model->name : '-',
			),
			array(
				'name'=>'desc',
				'value'=>$model->desc,
				//'value'=>$model->desc != '' ? $model->desc : '-',
			),
			array(
				'name'=>'default',
				'value'=>$model->default,
				//'value'=>$model->default != '' ? $model->default : '-',
			),
			array(
				'name'=>'default_setting',
				'value'=>$model->default_setting,
				//'value'=>$model->default_setting != '' ? $model->default_setting : '-',
			),
			array(
				'name'=>'photo_limit',
				'value'=>$model->photo_limit,
				//'value'=>$model->photo_limit != '' ? $model->photo_limit : '-',
			),
			array(
				'name'=>'photo_resize',
				'value'=>$model->photo_resize,
				//'value'=>$model->photo_resize != '' ? $model->photo_resize : '-',
			),
			array(
				'name'=>'photo_resize_size',
				'value'=>$model->photo_resize_size != '' ? $model->photo_resize_size : '-',
				//'value'=>$model->photo_resize_size != '' ? CHtml::link($model->photo_resize_size, Yii::app()->request->baseUrl.'/public/visit/'.$model->photo_resize_size, array('target' => '_blank')) : '-',
				'type'=>'raw',
			),
			array(
				'name'=>'photo_view_size',
				'value'=>$model->photo_view_size != '' ? $model->photo_view_size : '-',
				//'value'=>$model->photo_view_size != '' ? CHtml::link($model->photo_view_size, Yii::app()->request->baseUrl.'/public/visit/'.$model->photo_view_size, array('target' => '_blank')) : '-',
				'type'=>'raw',
			),
			array(
				'name'=>'creation_date',
				'value'=>!in_array($model->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->creation_date, true) : '-',
			),
			array(
				'name'=>'creation_id',
				'value'=>$model->creation_id,
				//'value'=>$model->creation_id != 0 ? $model->creation_id : '-',
			),
			array(
				'name'=>'modified_date',
				'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->modified_date, true) : '-',
			),
			array(
				'name'=>'modified_id',
				'value'=>$model->modified_id,
				//'value'=>$model->modified_id != 0 ? $model->modified_id : '-',
			),
		),
	)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
