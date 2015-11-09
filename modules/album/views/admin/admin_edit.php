<?php
/**
 * Albums (albums)
 * @var $this AdminController * @var $model Albums *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Albums'=>array('manage'),
		$model->title=>array('view','id'=>$model->album_id),
		'Update',
	);

	$url = Yii::app()->controller->createUrl('photo/ajaxmanage', array('id'=>$model->album_id,'type'=>'admin'));
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
	)); ?>
</div>

<div class="boxed mt-15">
	<h3><?php echo Phrase::trans(24013,1); ?></h3>
	<div class="clearfix horizontal-data" name="four">
		<ul id="media-render">
			<li id="upload" <?php echo (count(AlbumPhoto::getPhoto($model->album_id)) == $setting->photo_limit) ? 'class="hide"' : '' ?>>
				<a id="upload-gallery" href="<?php echo Yii::app()->controller->createUrl('photo/ajaxadd', array('id'=>$model->album_id,'type'=>'admin'));?>" title="<?php echo Phrase::trans(26054,1); ?>"><?php echo Phrase::trans(26054,1); ?></a>
				<img src="<?php echo Utility::getTimThumb(Yii::app()->request->baseUrl.'/public/album/album_default.png', 320, 250, 1);?>" alt="" />
			</li>
		</ul>
	</div>
</div>
