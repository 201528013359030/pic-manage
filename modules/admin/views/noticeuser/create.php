<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Noticeuser */

$this->title = 'Create Noticeuser';
$this->params['breadcrumbs'][] = ['label' => 'Noticeusers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="noticeuser-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
