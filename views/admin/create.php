<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PaiUser */

$this->title = 'Create Pai User';
$this->params['breadcrumbs'][] = ['label' => 'Pai Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pai-user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
