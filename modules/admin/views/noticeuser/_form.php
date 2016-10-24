<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Noticeuser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="noticeuser-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'eid')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'uid')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'time')->textInput() ?>

    <?= $form->field($model, 'level')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
