<?php
/**
 * Albums (albums)
 * @var $this AdminController
 * @var $model Albums
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 20 October 2016, 10:14 WIB
 * @link https://github.com/ommu/mod-photo-album
 * @contact (+62)856-299-4114
 *
 */
?>

<li id="upload" <?php echo $photo_limit != 0 && count($photos) == $photo_limit ? 'class="hide"' : '' ?>>
	<a id="upload-gallery" href="<?php echo Yii::app()->controller->createUrl('o/admin/insertcover', array('id'=>$model->album_id,'hook'=>'admin'));?>" title="<?php echo Yii::t('phrase', 'Upload Photo'); ?>"><?php echo Yii::t('phrase', 'Upload Photo'); ?></a>
	<img src="<?php echo Utility::getTimThumb(Yii::app()->request->baseUrl.'/public/album/album_plus.png', 320, 250, 1);?>" alt="" />
</li>