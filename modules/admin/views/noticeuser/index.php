<?php

use yii\helpers\Html;
use yii\grid\GridView;
//use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\NoticeuserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Noticeusers';
$this->params['breadcrumbs'][] = $this->title;
?>
<head>

<meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="renderer" content="webkit">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>公告</title>
    <?=Html::cssFile('@web/js/ligerUI/skins/Aqua/css/ligerui-all.css')?>
    <?//=Html::cssFile('@web/css/bootstrap-switch.min.css')?>
    <?=Html::jsFile('@web/js/jquery.js')?>
    <?=Html::jsFile('@web/js/ligerUI/js/core/base.js')?>
    <?=Html::jsFile('@web/js/ligerUI/js/plugins/ligerTree.js')?>
    <?//=Html::jsFile('@web/js/bootstrap-switch.min.js')?>
</head>
<style>
.summary{
    display: none;
}
</style>

<div id='user_list' class="noticeuser-index">

    <h1><?//= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p style='padding: 10px 0px;'>
        <?= Html::a('添加用户', '#' , ['id'=>'userTree','class' => 'btn btn-success']) ?>
        <?//= Html::a('删除选中', '#' , ['onclick'=>"getSelectedRows();",'class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
         //   ['class' => 'kartik\grid\CheckboxColumn'],
            ['class' => 'yii\grid\SerialColumn'],

   //         'id',
    //        'eid',
     //       'uid',
            'name',
            'mobile',
            // 'time:datetime',
            // 'level',
            ['class' => 'yii\grid\ActionColumn',
            //'header' => '操作',
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', 
                        "index.php?r=admin/noticeuser/delete&id=$key".Yii::$app->params['urlSubflag'], 
                        ['title' => Yii::t('yii', '删除用户'),
                            'data-pjax' => '0',
                            "data-confirm"=>"确定要删除该用户？", 
                        ]
                    ); 
                },  
            ],  
            'headerOptions' => ['width' => '120'],    
            ],

       //     ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
<button id='modalTree' class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal" data-backdrop="static" style="display: none;">
</button>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style='z-index:1060'>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close"  data-dismiss="modal" aria-hidden="true" onclick="closeContactsModal()">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel">
					选择用户
				</h4>
			</div>
			<div class="modal-body" style='height:350px;'>	
				<div id='tree' style="width:400px; height:320px; margin:auto; #float:left; clear:both; border:1px solid #ccc; overflow:auto;  ">
				    <div id="loading" style="display:#none;padding-top:30%;padding-left:40%">
                        加载中...
				    </div>
					<ul id="tree1"></ul>
				</div> 
				<div id='select' style="display: none; height:320px; margin:auto; #float:left; clear:both; overflow:auto;">
                    <table id='userList' class="table table-striped">
                        <thead>
                        </thead>
                        <tbody>
                        </tbody>
                    </table> 
                </div>
            </div>
            <div class="modal-footer">
                <a id='reselect' href="#" style="display: none; float: left;padding-top: 10px;padding-left: 350px;">重新选择</a>
				<button id="closeContacts" type="button" class="btn btn-default"  data-dismiss="modal" onclick="closeContactsModal()">关闭
				</button>
				<button id="saveContacts" type="button" class="btn btn-primary" onclick="getChecked()">
					选择
				</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->
</div>
<div>
<form name="addUser" id="addUser" method="post" enctype="multipart/form-data" action="index.php?r=admin/noticeuser/add<?=Yii::$app->params['urlSubflag']?>">
        <input id="uid" type="hidden" name="uid" value=0>
        <input id="name" type="hidden" name="name" value=0>
        <input id="mobile" type="hidden" name="mobile" value=0>
    </form>
</div>
<script>
var manager;
var add = false;
var keys ;
var gridData = {};
$(function(){
	$("#userTree").click(function(){
		document.getElementById("modalTree").click();
        if($("#loading").css("display") == "none"){
	        document.getElementById("reselect").click();
            return;
        }
		$.get('index.php?r=admin/announce/contacts',{},function(data){
			if(data){
                $("#loading").css("display","none");
           		tree = $("#tree1").ligerTree(data.tree);
           		manager = $("#tree1").ligerGetTreeManager();
			}else{
				alert('error');
			}
	     },'json');
	});
    $("#reselect").click(function(){
        $("#select").css('display','none');
        $("#reselect").css('display','none');
        $("#tree").css('display','block');
        $("#saveContacts").text("选择");
        $("#userList").empty();
        add = false;
        
    });
});
function getSelectedRows(){
    keys = $('#user_list').yiiGridView('getSelectedRows');
    alert(keys);
}
function getChecked()
{
    var notes = manager.getChecked();
    var text = "";
    var id = "";
    var photo = "";
    var mobile = "";
    addRow("姓名","电话");
    for (var i = 0; i < notes.length; i++)
    {
        if(notes[i].data.id){
            addRow(notes[i].data.text,notes[i].data.mobile);
            text += notes[i].data.text + ",";
            id += notes[i].data.id + ",";
       //     photo += notes[i].data.photo + ",";
            mobile += notes[i].data.mobile + ",";
        }
    }
    $("#tree").css('display','none');
    $("#select").css('display','block');
    $("#reselect").css('display','block');
    $("#saveContacts").text("添加");
    if(add == false){
        add = true;
    }else{
        $("#uid").val(id);
        $("#name").val(text);
        $("#mobile").val(mobile);
        document.getElementById("closeContacts").click();
        document.addUser.submit();
    }

}
function addRow(name,mobile){
    /*
    //添加一行
    var newTr = document.getElementById('userList').insertRow(-1);
    //添加两列
    var newTd0 = newTr.insertCell(-1);
    var newTd1 = newTr.insertCell(-1);
    //设置列内容和属性
    newTd0.innerText = name;
    newTd1.innerText = mobile;
     */


    var otr = userList.insertRow(-1);
    var otd = document.createElement("td");
    otd.innerHTML = name; 
    var otd1 = document.createElement("td");
    otd1.innerHTML = mobile; 
    otr.appendChild(otd);
    otr.appendChild(otd1);
}
function closeContactsModal(){
}
</script>
