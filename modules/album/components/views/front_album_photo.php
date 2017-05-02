<?php if($model != null) {?>
<div class="boxed">
	<h3><?php echo Yii::t('phrase', 'Recent Photo');?></h3>
	<ul class="photo clearfix">
	<?php foreach($model as $key => $val) {
		$image = Yii::app()->request->baseUrl.'/public/album/'.$val->album_id.'/'.$val->media;
		echo '<li><a href="'.Yii::app()->createUrl('album/site/view', array('id'=>$val->album_id, 'photo'=>$val->media_id, 't'=>Utility::getUrlTitle($val->album->title))).'" title=""><img src="'.Utility::getTimThumb($image,74,74,1).'" alt=""></a></li>';
	}?>
	</ul>
</div>
<?php }?>
