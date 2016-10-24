<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Attendancer */

$this->title = 'Create Attendancer';
$this->params['breadcrumbs'][] = ['label' => 'Attendancers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attendancer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
