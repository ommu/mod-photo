<?php
/**
 * Albums (albums)
 * @var $this AdminController
 * @var $model Albums
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-photo
 *
 */

	$this->breadcrumbs=array(
		'Albums'=>array('manage'),
		$model->title=>array('view','id'=>$model->album_id),
		Yii::t('phrase', 'Update'),
	);
	
	$photos = $model->photos;
	
	//$photo_limit
	$photo_limit = $setting->photo_limit;
	if($model->category->default_setting == 0)
		$photo_limit = $model->category->photo_limit;
?>

<div class="form" name="post-on">
	<?php echo $this->renderPartial('_form', array(
		'model'=>$model,
		'setting'=>$setting,
		'photo_file_type'=>$photo_file_type,
	)); ?>
</div>

<?php if($photo_limit != 1) {?>
<div class="boxed mt-15">
	<h3><?php echo Yii::t('phrase', 'Album Photo'); ?></h3>
	<div class="clearfix horizontal-data" name="four">
		<ul id="media-render">
			<?php 
			$this->renderPartial('_form_photo', array('model'=>$model, 'photos'=>$photos, 'photo_limit'=>$photo_limit));
			if(!empty($photos)) {
				foreach($photos as $key => $data)
					$this->renderPartial('_form_view_photos', array('data'=>$data));
			}?>
		</ul>
	</div>
</div>
<?php }?>