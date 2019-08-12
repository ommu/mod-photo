<?php
/**
 * Album Settings (album-setting)
 * @var $this SettingController
 * @var $model AlbumSetting
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-photo
 *
 */

	$this->breadcrumbs=array(
		'Album Settings'=>array('manage'),
		Yii::t('phrase', 'Manual'),
	);
?>

<div class="dialog-content">
	<ul>
	<?php
		foreach (new DirectoryIterator($manual_path) as $fileInfo) {
			$filePath = '';
			if($fileInfo->isDot())
				continue;
			
			if($fileInfo->isFile()) {
				$extension = pathinfo($fileInfo->getFilename(), PATHINFO_EXTENSION);
				if(!in_array(strtolower($extension), array('php')))
					$filePath = $this->module->assetsUrl.'/manual/'.$fileInfo->getFilename();
			}
			if($filePath)
				echo '<li>'.CHtml::link($fileInfo->getFilename(), $filePath).'</li>';
		}
	?>
	</ul>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>