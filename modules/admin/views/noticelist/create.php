<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Noticeinfo */

$this->title = 'Create Noticeinfo';
$this->params['breadcrumbs'][] = ['label' => 'Noticeinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="noticeinfo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
