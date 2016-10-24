<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AttendancerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>
<head>
    <?=Html::cssFile('@web/js/ligerUI/skins/Aqua/css/ligerui-all.css')?>
    <?//=Html::cssFile('@web/css/bootstrap-switch.min.css')?>
    <?=Html::jsFile('@web/js/jquery.js')?>
    <?=Html::jsFile('@web/js/ligerUI/js/core/base.js')?>
    <?=Html::jsFile('@web/js/ligerUI/js/plugins/ligerTree.js')?>
    <?//=Html::jsFile('@web/js/bootstrap-switch.min.js')?>
<meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="renderer" content="webkit">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>公告</title>

</head>
<div class="pai-user-index">

    <h1><?//= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加审批人', '#' , ['id'=>'userTree','class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'user_id',
            'user_name',
            'user_sex',
            'admin',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>

</div>
<button id='modalTree' class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal" data-backdrop="static" style="display: none;">
</button>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style='z-index:1060'>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close"  data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel">
					选择用户
				</h4>
			</div>
			<div class="modal-body" style='height:350px;'>	
				<div style="width:400px; height:320px; margin:auto; #float:left; clear:both; border:1px solid #ccc; overflow:auto;  ">
				    <div id="loading" style="display:#none;padding-top:30%;padding-left:40%">
                        加载中...
				    </div>
					<ul id="tree1"></ul>
				</div> 
			</div>
			<div class="modal-footer">
				<button id="closeContacts" type="button" class="btn btn-default"  data-dismiss="modal">关闭
				</button>
				<button id="saveContacts" type="button" class="btn btn-primary" onclick="getChecked()">
					提交
				</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->
</div>
<script>
$(function(){
	$("#userTree").click(function(){
        show_book();
    });
});
function getChecked()
{
    var notes = manager.getChecked();
    var text = "";
    var id = "";
    var photo = "";
    for (var i = 0; i < notes.length; i++)
    {
    	if(notes[i].data.id){
	        id += notes[i].data.id + ",";
    	}
    }
    if(id.length == 0){
        return;
    }
    $.post('index.php?r=test/bind',{id:id},function(data){
        if(data == 1){
            document.getElementById("closeContacts").click();
            alert("修改成功");
            location.reload();
        }else{
            alert('修改失败');
        }
    },'json');
    return true;
}
function show_book(){
    document.getElementById("modalTree").click();
    if($("#loading").css("display") == "none"){
        return;
    }
    $.post('index.php?r=test/contacts',{},function(data){
        if(data){
            $("#loading").css("display","none");
            tree = $("#tree1").ligerTree(data.tree);
            manager = $("#tree1").ligerGetTreeManager();
        }else{
            alert('error');
        }
    },'json');
}
</script>
