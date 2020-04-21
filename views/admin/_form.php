<?php
/**
 * Photo Albums (photo-album)
 * @var $this app\components\View
 * @var $this ommu\album\controllers\AdminController
 * @var $model ommu\album\models\PhotoAlbum
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2020 OMMU (www.ommu.id)
 * @created date 6 January 2020, 02:24 WIB
 * @link https://github.com/ommu/mod-photo
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
?>

<div class="photo-album-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'member_id')
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('member_id')); ?>

<?php echo $form->field($model, 'user_id')
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('user_id')); ?>

<?php echo $form->field($model, 'title')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('title')); ?>

<?php echo $form->field($model, 'caption')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('caption')); ?>

<?php if($model->isNewRecord && !$model->getErrors())
	$model->publish = 1;
echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>