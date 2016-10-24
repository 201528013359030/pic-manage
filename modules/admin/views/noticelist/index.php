<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\Noticelist */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Noticeinfos';
$this->params['breadcrumbs'][] = $this->title;
?>
<script type="text/javascript">
var notice = null;
function setSort(id,sort,topTime){
    var sort = $("#sort").val(sort);
    $("#topTime").val(topTime);
    notice = id;

}
function modifySort(){
    var sort = $("#sort").val();
    var topTime = $("#topTime").val();
    if(sort == ''){
        sort = 0;
    }
    window.location.href="index.php?r=admin/noticelist/modify&id="+notice+"&sort="+sort+"&subflag=0102&topTime="+topTime;
    
}
</script>
<style>
.summary{
    display: none;
}
</style>
<div class="noticeinfo-index">

    <h1><?//= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?//= Html::a('Create Noticeinfo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'announce_id',
 //           'type',
            'title',
            'sender_name',
            'time:datetime',
  //          'content',
   //         'attach',
            // 'sender',
            'comment_switch',
           // 'top_day',
            ['attribute' => 'top_day',
            'content' => function($dataProvider){
                return isset($dataProvider['top_day'])?$dataProvider['top_day']:"";
            }],
            // 'enterpris_id',
            // 'confirmNum',
            //'receiverType',
            ['attribute' => 'receiverType',
            'content' => function($dataProvider){
                return Yii::$app->params['receiverType'][$dataProvider['receiverType']];
            }],

            ['class' => 'yii\grid\ActionColumn',
            //'header' => '操作',
            'template' => '{sort} {view} {delete}',
            'buttons' => [
                'sort' => function ($url, $model, $key) {
                    return Html::a('<span class="">置顶</span>', 
                        "#", 
                        ['title' => Yii::t('yii', '修改置顶级别'),
                        'data-pjax' => '0',
                        //    'target'=>"_blank",
                        //'class'=>'desc'
                        'data-toggle'=>"modal",
                        'data-target'=>"#renameModal",
                        'onclick'=>"setSort($key,".$model->comment_switch.",'$model->top_day');"
                        ]
                    ); 
                },  
                'view' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 
                        "index.php?r=admin/noticecontent/index&uid=".Yii::$app->session['user.uid']."&eguid=1&auth_token=".Yii::$app->session['user.token']."&id=$key&a=1", 
                        ['title' => Yii::t('yii', '点击查看公告详情'),
                            'data-pjax' => '0',
                             'target'=>"_blank"
                        ]
                    ); 
                },  
                'delete' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', 
                        "index.php?r=admin/noticelist/delete&id=$key".Yii::$app->params['urlSubflag'], 
                        ['title' => Yii::t('yii', '删除公告'),
                            'data-pjax' => '0',
                            "data-confirm"=>"确定要删除该公告？", 
                        ]
                    ); 
                },  
            ],  
            'headerOptions' => ['width' => '120'],    
            ],
          //  ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <input id="reguid" type="hidden" value="">
    <div class="modal fade" id="renameModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style='width: 300px;z-index:1060'>
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" 
                        data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <span class="input-group-addon">置顶级别</span>
                        <input id="sort" type="text" class="form-control" >
                    </div>
                    <div class="text-danger" style="margin-left:15px;margin-top:5px;">置顶级别范围：0~127(0为非置顶)</div>
                    <div class="input-group">
                        <span class="input-group-addon">置顶天数</span>
                        <input id="topTime" type="text" class="form-control" >
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"data-dismiss="modal">
                        关闭
                    </button>
                    <button type="button" class="btn btn-primary"data-dismiss="modal" onclick="modifySort();">
                        提交更改
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>
</div>
