<?php if($model != null) {?>
<div class="boxed">
	<h3><?php echo Phrase::trans(24027,1);?></h3>
	<ul class="photo clearfix">
	<?php foreach($model as $key => $val) {
		$images = Yii::app()->request->baseUrl.'/public/album/'.$val->album_id.'/'.$val->media;
		echo '<li><a href="'.Yii::app()->createUrl('album/site/view', array('id'=>$val->album_id, 'photo'=>$val->media_id, 't'=>$val->album->title)).'" title=""><img src="'.Utility::getTimThumb($images,74,74,1).'" alt=""></a></li>';
	}?>
	</ul>
</div>
<?php }?>
