<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Noticeinfo */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Noticeinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="noticeinfo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->announce_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->announce_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'announce_id',
            'type',
            'title',
            'content',
            'attach',
            'sender',
            'comment_switch',
            'enterpris_id',
            'time:datetime',
            'confirmNum',
            'sender_name',
        ],
    ]) ?>

</div>
