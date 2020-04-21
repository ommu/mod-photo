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

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Albums'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="photo-album-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
