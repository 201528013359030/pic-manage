<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

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

<body class="">
<ul id="tree1"></ul>
</body>
<script>
var data = <?=$userTree?>;
tree = $("#tree1").ligerTree(data.tree);
manager = $("#tree1").ligerGetTreeManager();
function getChecked()
{
    var notes = manager.getChecked();
    var text = "";
    var id = "";
    var photo = "";
    for (var i = 0; i < notes.length; i++)
    {
        if(notes[i].data.id){
            text += notes[i].data.text + ",";
            id += notes[i].data.id + ",";
            photo += notes[i].data.photo + ",";
        }
    }
    $("#announceform-receiver").attr("value",text);
    $("#announceform-receiverid").attr("value",id);
    $("#announceform-photo").attr("value",photo);
}
</script>
