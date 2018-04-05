<?php 
/**
 * @var $this AlbumRecentsComponent
 * @var $model Albums
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/mod-photo
 *
 */

if($model != null) {?>
<div class="box recent-news-album">
	<h3>Photo Album Terbaru</h3>
	<ul>
		<?php 
		$i=0;
		foreach($model as $key => $val) {
		$i++;
			$image = Yii::app()->request->baseUrl.'/public/album/album_default.png';
			$photos = $val->photos;
			if(!empty($photos)) {
				$media = $val->view->album_cover ? $val->view->album_cover : $photos[0]->media;
				$image = Yii::app()->request->baseUrl.'/public/album/'.$val->album_id.'/'.$media;
			}
			if($i == 1) {?>
				<li <?php echo !empty($photos) ? 'class="solid"' : '';?>>
					<a href="<?php echo Yii::app()->createUrl('album/site/view', array('id'=>$val->album_id, 'slug'=>Utility::getUrlTitle($val->title)))?>" title="<?php echo $val->title?>">
						<?php if(!empty($photos)) {?><img src="<?php echo Utility::getTimThumb($image, 230, 100, 1)?>" alt="<?php echo $val->title?>" /><?php }?>
						<?php echo $val->title?>
					</a>
				</li>
			<?php } else {?>
				<li><a href="<?php echo Yii::app()->createUrl('album/site/view', array('id'=>$val->album_id, 'slug'=>Utility::getUrlTitle($val->title)))?>" title="<?php echo $val->title?>"><?php echo $val->title?></a></li>
			<?php }
		}?>
	</ul>
</div>
<?php }?>