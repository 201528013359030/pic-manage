<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Stationinfo;
use yii\jui\DatePicker;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Noticelist */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="noticeinfo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index','subflag'=>Yii::$app->request->get('subflag')],
        'method' => 'get',
    ]); ?>

    <?//= $form->field($model, 'announce_id') ?>

    <?//= $form->field($model, 'type') ?>


    <?//= $form->field($model, 'content') ?>

    <?//= $form->field($model, 'attach') ?>

    <?php //  echo $form->field($model, 'sender') ?>

    <?php // echo $form->field($model, 'comment_switch') ?>

    <?php // echo $form->field($model, 'enterpris_id') ?>


    <?php // echo $form->field($model, 'confirmNum') ?>
    <div class="row">
        <div class="col-sm-3"><?= $form->field($model, 'title') ?></div>
        <div class="col-sm-3"><?= $form->field($model, 'sender_name') ?></div>      
        <div class="col-sm-3"><?php  echo $form->field($model, 'time')->widget(DatePicker::className(),['clientOptions' => ['dateFormat' => 'yy-mm-dd']])->textInput(['placeholder' => Yii::t('app', '发布时间'),'style'=>'width:px','readonly'=>true]) ?>
</div>      
        <div class="col-sm-3">
            <div class="form-group">
                <?= Html::submitButton('查询', ['class' => 'btn btn-primary','style'=>'margin-top: 25px;']) ?>
                <?//= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
            </div>
        </div>      
        <!--div class="col-sm-3"><?//= $form->field($model, 'receiverType') ?></div-->      
    </div>




    <?php ActiveForm::end(); ?>

</div>
