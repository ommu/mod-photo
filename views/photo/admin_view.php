<?php
/**
 * Photos (photos)
 * @var $this app\components\View
 * @var $this ommu\album\controllers\PhotoController
 * @var $model ommu\album\models\Photos
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
use yii\widgets\DetailView;

if (!$small) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Photos'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $model->title;

    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id' => $model->id]), 'icon' => 'pencil', 'htmlOptions' => ['class' => 'btn btn-primary']],
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="photos-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'publish',
		'value' => $model->quickAction(Url::to(['publish', 'id' => $model->primaryKey]), $model->publish),
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'albumTitle',
		'value' => function ($model) {
			$albumTitle = isset($model->album) ? $model->album->title : '-';
            if ($albumTitle != '-') {
				return Html::a($albumTitle, ['admin/view', 'id' => $model->album_id], ['title' => $albumTitle, 'class' => 'modal-btn']);
            }
			return $albumTitle;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'memberDisplayname',
        'value' => function ($model) {
            $memberDisplayname = isset($model->member) ? $model->member->displayname : '-';
            $userDisplayname = isset($model->user) ? $model->user->displayname : '-';
            if ($userDisplayname != '-' && $memberDisplayname != $userDisplayname) {
                return $memberDisplayname.'<br/>'.$userDisplayname;
            }
            return $memberDisplayname;
        },
        'format' => 'html',
	],
	[
		'attribute' => 'photo',
		'value' => function ($model) {
			$uploadPath = join('/', [$model::getUploadPath(false), $model->member_id]);
			return $model->photo ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->photo])), ['alt' => $model->photo, 'class' => 'd-block border border-width-3 mb-4']).$model->photo : '-';
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'title',
		'value' => $model->title ? $model->title : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'caption',
		'value' => $model->caption ? $model->caption : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'creation_date',
		'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'creationDisplayname',
		'value' => isset($model->creation) ? $model->creation->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'modified_date',
		'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'modifiedDisplayname',
		'value' => isset($model->modified) ? $model->modified->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'updated_date',
		'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Update'), 'class' => 'btn btn-primary btn-sm']),
		'format' => 'html',
		'visible' => !$small && Yii::$app->request->isAjax ? true : false,
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class' => 'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>