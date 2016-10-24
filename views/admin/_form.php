<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PaiUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pai-user-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'user_name')->textInput(['maxlength' => 200]) ?>


    <?= $form->field($model, 'admin')->textInput(['maxlength' => 10]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
