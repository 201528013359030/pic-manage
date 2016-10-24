<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Noticeuser */

$this->title = 'Update Noticeuser: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Noticeusers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="noticeuser-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
