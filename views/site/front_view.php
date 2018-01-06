<?php
/**
 * Albums (albums)
 * @var $this SiteController
 * @var $model Albums
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-photo
 *
 */

	$this->breadcrumbs=array(
		'Albums'=>array('manage'),
		$model->title,
	);
?>

<?php $this->widget('application.libraries.core.components.system.FDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'album_id',
		'publish',
		'user_id',
		'media_id',
		'headline',
		'comment_code',
		'title',
		'body',
		'quote',
		'photos',
		'comment',
		'view',
		'likes',
		'creation_date',
		'creation_id',
		'modified_date',
		'modified_id',
	),
)); ?>