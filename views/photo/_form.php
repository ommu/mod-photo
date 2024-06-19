<?php
/**
 * Photos (photos)
 * @var $this app\components\View
 * @var $this ommu\album\controllers\PhotoController
 * @var $model ommu\album\models\Photos
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2020 OMMU (www.ommu.id)
 * @created date 6 January 2020, 02:23 WIB
 * @link https://github.com/ommu/mod-photo
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\ActiveForm;
?>

<div class="photos-form">

<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal form-label-left',
		'enctype' => 'multipart/form-data',
	],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'album_id')
	->textInput(['type' => 'number', 'min' => '1'])
	->label($model->getAttributeLabel('album_id')); ?>

<?php echo $form->field($model, 'member_id')
	->textInput(['type' => 'number', 'min' => '1'])
	->label($model->getAttributeLabel('member_id')); ?>

<?php echo $form->field($model, 'user_id')
	->textInput(['type' => 'number', 'min' => '1'])
	->label($model->getAttributeLabel('user_id')); ?>

<?php $uploadPath = join('/', [$model::getUploadPath(false), $model->member_id]);
$photo = !$model->isNewRecord && $model->old_photo != '' ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->old_photo])), ['alt' => $model->old_photo, 'class' => 'd-block border border-width-3 mb-4']).$model->old_photo.'<hr/>' : '';
echo $form->field($model, 'photo', ['template' => '{label}{beginWrapper}<div>'.$photo.'</div>{input}{error}{hint}{endWrapper}'])
	->fileInput()
	->label($model->getAttributeLabel('photo')); ?>

<?php echo $form->field($model, 'title')
	->textarea(['rows' => 6, 'cols' => 50])
	->label($model->getAttributeLabel('title')); ?>

<?php echo $form->field($model, 'caption')
	->textarea(['rows' => 6, 'cols' => 50])
	->label($model->getAttributeLabel('caption')); ?>

<?php 
if ($model->isNewRecord && !$model->getErrors()) {
    $model->publish = 1;
}
echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>