<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Noticeinfo */

$this->title = 'Update Noticeinfo: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Noticeinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->announce_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="noticeinfo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
