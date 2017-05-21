<?php 
/**
 * @var $this AlbumPhotoComponent
 * @var $model AlbumPhoto
 * version: 0.1.4
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/mod-photo-album
 * @contact (+62)856-299-4114
 *
 */

if($model != null) {?>
<div class="boxed">
	<h3><?php echo Yii::t('phrase', 'Recent Photo');?></h3>
	<ul class="photo clearfix">
	<?php foreach($model as $key => $val) {
		$image = Yii::app()->request->baseUrl.'/public/album/'.$val->album_id.'/'.$val->media;
		echo '<li><a href="'.Yii::app()->createUrl('album/site/view', array('id'=>$val->album_id, 'photo'=>$val->media_id, 'slug'=>Utility::getUrlTitle($val->album->title))).'" title=""><img src="'.Utility::getTimThumb($image,74,74,1).'" alt=""></a></li>';
	}?>
	</ul>
</div>
<?php }?>
