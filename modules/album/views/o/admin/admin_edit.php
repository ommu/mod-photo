<?php
/**
 * Albums (albums)
 * @var $this AdminController
 * @var $model Albums
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/Photo-Albums
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Albums'=>array('manage'),
		$model->title=>array('view','id'=>$model->album_id),
		'Update',
	);
	
	//$photo_limit
	$photo_limit = $setting->photo_limit;
	if($model->category->default_setting == 0)
		$photo_limit = $model->category->photo_limit;

	$url = Yii::app()->controller->createUrl('o/photo/ajaxmanage', array('id'=>$model->album_id,'type'=>'admin'));
	$cs = Yii::app()->getClientScript();
$js=<<<EOP
	$.ajax({
		type: 'get',
		url: '$url',
		dataType: 'json',
		//data: { id: '$id' },
		success: function(render) {
			$('.horizontal-data #media-render #upload').before(render.data);
		}
	});
EOP;
	$cs->registerScript('ajaxmanage', $js, CClientScript::POS_END);
?>

<div class="form" name="post-on">
	<?php echo $this->renderPartial('_form', array(
		'model'=>$model,
		'setting'=>$setting,
		'tag'=>$tag,
	)); ?>
</div>

<div class="boxed mt-15">
	<h3><?php echo Yii::t('phrase', 'Album Photo'); ?></h3>
	<div class="clearfix horizontal-data" name="four">
		<ul id="media-render">
			<li id="upload" <?php echo (count(AlbumPhoto::getPhoto($model->album_id)) == $photo_limit) ? 'class="hide"' : '' ?>>
				<a id="upload-gallery" href="<?php echo Yii::app()->controller->createUrl('o/photo/ajaxadd', array('id'=>$model->album_id,'type'=>'admin'));?>" title="<?php echo Yii::t('phrase', 'Upload Photo'); ?>"><?php echo Yii::t('phrase', 'Upload Photo'); ?></a>
				<img src="<?php echo Utility::getTimThumb(Yii::app()->request->baseUrl.'/public/album/album_plus.png', 320, 250, 1);?>" alt="" />
			</li>
		</ul>
	</div>
</div>
