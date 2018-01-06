<?php
/**
 * Albums (albums)
 * @var $this AdminController
 * @var $model Albums
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 20 October 2016, 10:14 WIB
 * @link https://github.com/ommu/ommu-photo
 *
 */
?>

<?php if($data->media != '') {?>
<li>
	<?php if($data->cover == 0) {?>
		<a id="set-cover" href="<?php echo Yii::app()->controller->createUrl('o/photo/setcover', array('id'=>$data->media_id,'hook'=>'admin'));?>" title="<?php echo Yii::t('phrase', 'Set Cover');?>"><?php echo Yii::t('phrase', 'Set Cover');?></a>
	<?php }?>
	<a id="set-delete" href="<?php echo Yii::app()->controller->createUrl('o/photo/delete', array('id'=>$data->media_id,'hook'=>'admin'));?>" title="<?php echo Yii::t('phrase', 'Delete Photo');?>"><?php echo Yii::t('phrase', 'Delete Photo');?></a>
	<?php 
	$media = Yii::app()->request->baseUrl.'/public/album/'.$data->album_id.'/'.$data->media;?>
	<img src="<?php echo Utility::getTimThumb($media, 320, 250, 1);?>" alt="<?php echo $data->album->title;?>" />	
</li>
<?php }?>