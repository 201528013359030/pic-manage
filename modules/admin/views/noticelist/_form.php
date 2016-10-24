<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Noticeinfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="noticeinfo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'content')->textInput(['maxlength' => 5000]) ?>

    <?= $form->field($model, 'attach')->textInput(['maxlength' => 1000]) ?>

    <?= $form->field($model, 'sender')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'comment_switch')->textInput() ?>

    <?= $form->field($model, 'enterpris_id')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'time')->textInput() ?>

    <?= $form->field($model, 'confirmNum')->textInput() ?>

    <?= $form->field($model, 'sender_name')->textInput(['maxlength' => 100]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
