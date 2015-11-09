<?php if($model != null) {?>
<div class="box recent-news-album">
	<h3>Photo Album Terbaru</h3>
	<ul>
		<?php 
		$i=0;
		foreach($model as $key => $val) {
		$i++;
			if($i == 1) {
				if($val->media_id != 0)
					$images = Yii::app()->request->baseUrl.'/public/album/'.$val->album_id.'/'.$val->cover->media;?>
				<li <?php echo $val->media_id != 0 ? 'class="solid"' : '';?>>
					<a href="<?php echo Yii::app()->createUrl('album/site/view', array('id'=>$val->album_id, 't'=>Utility::getUrlTitle($val->title)))?>" title="<?php echo $val->title?>">
						<?php if($val->media_id != 0) {?><img src="<?php echo Utility::getTimThumb($images, 230, 100, 1)?>" alt="<?php echo $val->title?>" /><?php }?>
						<?php echo $val->title?>
					</a>
				</li>
			<?php } else {?>
				<li><a href="<?php echo Yii::app()->createUrl('album/site/view', array('id'=>$val->album_id, 't'=>Utility::getUrlTitle($val->title)))?>" title="<?php echo $val->title?>"><?php echo $val->title?></a></li>
			<?php }
		}?>
	</ul>
</div>
<?php }?>