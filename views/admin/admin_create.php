<?php
/**
 * Photo Albums (photo-album)
 * @var $this app\components\View
 * @var $this app\modules\album\controllers\AdminController
 * @var $model app\modules\album\models\PhotoAlbum
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2020 Ommu Platform (www.ommu.co)
 * @created date 6 January 2020, 02:24 WIB
 * @link https://www.ommu.co
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
