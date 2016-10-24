<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

?>
<!DOCTYPE html>
<html>
<head>

<meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="renderer" content="webkit">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>公告</title>

    <?=Html::cssFile('@web/css/bootstrap.css')?>
    <?=Html::cssFile('@web/js/ligerUI/skins/Aqua/css/ligerui-all.css')?>
    <?=Html::cssFile('@web/css/announce.css')?>
    <?=Html::jsFile('@web/js/jquery.js')?>
    <?=Html::jsFile('@web/js/bootstrap.js')?>
    <?=Html::jsFile('@web/js/ligerUI/js/core/base.js')?>
    <?=Html::jsFile('@web/js/ligerUI/js/plugins/ligerTree.js')?>
    <?=Html::jsFile('@web/js/iscroll.js')?>
    <?=Html::jsFile('@web/js/htmlset.js')?>

</head>
<style>
.l-tree .l-tree-icon-none img {
    margin-bottom: 4px;
    border: 0;
    height: 16px;
    width: 16px;
    top: 2px;
    margin-left: 2px;
}
</style>
<script>
var isiOS = false;
var isAndroid = false;
$(function(){
	$("#announceform-receiver").click(function(){
		var group = "<?=$model->group?>";
		if(group != "0"){
			return;
		}
		document.getElementById("modalTree").click();
		$.get('index.php?r=admin/announce/contacts',{},function(data){
			if(data){
           		tree = $("#tree1").ligerTree(data.tree);
           		manager = $("#tree1").ligerGetTreeManager();
			}else{
				alert('error');
			}
	     },'json');
	});
    if(<?=$sendSucceed?>){
        document.getElementById("sendSucceed").click();
    }
    var u = navigator.userAgent, app = navigator.appVersion;
    isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
    isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
    //    alert('是否是Android：'+isAndroid);
    //    alert('是否是iOS：'+isiOS);
    if(isAndroid == true){
        var clientType = 1; 
    }else if(isiOS == true){
        var clientType = 2; 
    }else{
        var clientType = 3; 
    }
    $("#clientType").attr("value",clientType);
    if(clientType == 2 || clientType == 1){
//        $("#uploadImg").css("display","none");
 //       $("#upload_attach").css("display","none");
    }
});
API.init();
function closeWebview(){
    var op = {
        "name":"CloseWebView"
    };
    API.send_tonative(op);
}
var fileId = 0;
var taskId = 0;
var fileInfo = [];
function uploadFileStart(){
    taskId++;
    var op = {
        "name": "Upload",
        "callback": "OnUploadCb",
        "params": {
            "uploadUrl": "",
            "webAppTransferID": fileId++,
            "taskID": taskId,
        }
    };
    fileInfo[taskId] = { 
        'name':'',
        'size':'',
        'path':'',
        'transferStatus':'start',
    }
    alert(JSON.stringify(op));
    API.send_tonative(op);
}
function closeModal(){
    play = 0;
}
var play = 0;
function OnUploadCb(param){
    params = param.result.params; 
    alert(JSON.stringify(params));
//    var tmp = fileInfo[params.taskId];
//    alert(tmp.transferStatus);
//    if(tmp.transferStatus == 'Success'){
        //return;
//    }
    fileInfo[params.taskId] = { 
        'name':params.fileName,
        'size':params.size,
        'path':params.uploadPath,
        'transferStatus':params.transferStatus,
    }
    if(play == 0){
        if (params.size > 1024 * 1024){ 
            fileSize = (Math.round(params.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
        }else{ 
            fileSize = (Math.round(params.size * 100 / 1024) / 100).toString() + 'KB';
        }
        $("#up_file_name").text(params.fileName);
        $("#up_file_size").text(fileSize);
        document.getElementById("uploadModalBtn").click();
        play = 1;
    }
    if(params.transferStatus == "Failure"){
        var state = "上传失败";
        upload_file_end(0,state,null,fileInfo[params.taskId]);
        return;
    }else if(params.transferStatus == "Transmission"){
        var state ="正在上传";    
    }else if(params.transferStatus == "Cancel"){
        var state = "上传已取消";
        upload_file_end(0,state,null,fileInfo[params.taskId]);
        return;
    }else if(params.transferStatus == "Success"){
        var state ="上传成功";    
        upload_file_end(1,state,null,fileInfo[params.taskId]);
    }
}
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
    if(text.length == 0){
        text = "公司全体员工";
        id = 0;
    }
    $("#announceform-receiver").text(text);
    $("#announceform-receiverid").attr("value",id);
    $("#announceform-receivername").attr("value",text);
    $("#announceform-photo").attr("value",photo);
    document.getElementById("closeContacts").click();
    return true;
}
function upload_btn_click(filetype){
	$("#announceform-filetype").attr("value",filetype);
  //  $("#upload_button").click();
   // uploadFileStart();
   // TransferStatus('teset','0','Cancel','100');
    //var params={'status':'Transmission','fileName':'tets.tst','size':'1000','fileId':0,'uploadPath':'123'};
    //OnUploadCb(params);
   // var params={'status':'Success','fileName':'tets.tst','size':'1000','fileId':0,'uploadPath':'123'};
   // OnUploadCb(params);
   // return;

    if(isiOS || isAndroid){
        uploadFileStart();
        return;
    }
    document.getElementById("upload_button").click();
}

function upload_fun(x){
    var file = $('#upload_button').get(0).files[0];
  	var filetype = $("#announceform-filetype").attr('value');
    if (file) {
        var fileSize = 0;
        var errorSize = 0;
        var str = "";
        if (file.size > 1024 * 1024){ 
            fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
            if(Math.round(file.size * 100 / (1024 * 1024)) / 100 > 200){
                str = "错误！文件不可大于200MB。";
                errorSize =1;   
            }
        }else{ 
            fileSize = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';
        }
        if(filetype == 1){
            if (!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG)$/.test(file.name)) {  
                alert("图片类型必须是.gif,jpeg,jpg,png中的一种");  
                return false;  
            }  
        }
        console.log(file.name, fileSize, file.type);
        upload_file_start(file.name,fileSize);
        if(filetype != 1){
            document.getElementById("uploadModalBtn").click();
        }
        if(errorSize == 1){
            upload_file_end(0,'上传失败',str);
            return 0;
        }
    }
    document.upload_form.submit();
    var file = $('#upload_button');
    file.after(file.clone().val("")); 
    file.remove();
}
function upload_file_start(name,size){
    $("#up_file_name").text(name);
    $("#up_file_size").text(size);

}
function upload_file_end(result,state,str,fileinfo){
  	var filetype = $("#announceform-filetype").attr('value');
  //	alert(filetype);
    if(result){
        $("#up_file_state").css("color","#00FF00");
    	if(filetype == 1){
            alert(fileinfo.path);
	    	$("#contentImg").attr("src",fileinfo.path);
	    	$("#contentBigImg").attr("src",fileinfo.path);
	    	$("#contentShowimg").attr("imgsrc",fileinfo.path);
	    	$("#announceform-contentImg").attr("value",JSON.stringify(fileinfo));
	    	$("#J-showimg").css("display","block");
    	}else{
			var count = parseInt($("#announceform-attachCount").attr("value"))+1;
			if(count > 2){
				$("#upload_attach").css("display","none");
			}
    		for(var i=1;i<4;i++){
    			var display = $("#attach"+i).css("display");
    			if(display == "none"){
    				break;
    			}
    		}
			$("#attachName"+i).text(fileinfo.name);
			$("#attach"+i).css("display","block");
    		$("#announceform-attach"+i).attr("value",JSON.stringify(fileinfo));
    		$("#announceform-attachCount").attr("value",count);
    	}
    	
    }else{
        if(str != null){
            $("#errorSize").text(str);
            $("#errorSize").css("display","block");
        }
    }
    $("#up_file_state").text(state);
    
}
function file_del(id){
	$("#attach"+id).css("display","none");
	$("#announceform-attach"+id).val("");
	var count = $("#announceform-attachCount").attr("value")-1;
	$("#announceform-attachCount").attr("value",count);
	if(count < 3){
		$("#upload_attach").css("display","block");
	}
}
var isSend = false;
function send_submit(){
    if(isSend){
        return false;
    }
	if( $.trim($("#announceform-title").val()) == ""){
		$("#announceform-contBox").css("display","none");
		$("#announceform-tipBox").css("display","block");
		return false;
	}else{
		$("#announceform-tipBox").css("display","none");
    }
	if( $.trim($("#announceform-content").val()) == ""){
		$("#announceform-contBox").css("display","block");
		return false;
	}else{
		$("#announceform-contBox").css("display","none");
    }
    isSend = true;
	$("#sendBtn").css("background-color","#e6e6e6");
    document.send_form.submit();
    return false;
}


</script>
<body class="bg_white">
	
<div id="wrap" class="wrap">
	
	<!--
    	作者：786161262@qq.com
    	时间：2015-07-07
    	描述：错误提示部分，提示一个错误，保证文字在320下一行显示。控制说明字数
    -->
	<div id="announceform-tipBox" class="tipBox" style="display:none">		
		<div class="tipInner">
			<i class="ficon ic_wanning"></i>标题不能为空
		</div>
	</div>	
	<div id="announceform-contBox" class="tipBox" style="display:none">		
		<div class="tipInner">
			<i class="ficon ic_wanning"></i>正文不能为空
		</div>
	</div>	
	
	<form name="send_form" id="send_form" method="post" enctype="multipart/form-data" action="index.php?r=admin/announce/save">
	
	<div class="moGrid">

		<div class="formBox">
			
			<div class="inpBox">
				<input id="announceform-title" class="inp" type="text" name="AnnounceForm[title]" style="-webkit-tap-highlight-color:rgba(0,0,0,0);" value="" placeholder="标题" />
			</div>
			
			<!--
            	作者：786161262@qq.com
            	时间：2015-07-07
            	描述：显示接收人
            	每一个接收人在 span 中，最后一个没有逗号
            -->
			<div  id="announceform-receiver" class="inpBox nameList">
				<?=$model->receiver?>
			</div>
			
			
			<div class="inpBox">
				
				<textarea id="announceform-content" class="txtare" name="AnnounceForm[content]" style="-webkit-tap-highlight-color:rgba(0,0,0,0);" placeholder="公告正文..." onpropertychange="this.style.height=this.scrollHeight + 'px'" oninput="this.style.height=this.scrollHeight + 'px'"></textarea>
				
			</div>
			
			<div id="uploadImg" class="inpBox">
				
				<!--	描述：上传后显示的缩略图，点击后显示弹出层，在做删除处理                -->
				<div id="J-showimg" class="imgBox" style="display: none;" >
					<div id="contentShowimg" class="addImg" imgsrc = ""><img id="contentImg" src=""></div>						
				</div>
				<!--end-->
				
				<!--  描述：点击上传图片                -->
				<div class="imgBox" style="display: block;" onclick="upload_btn_click(1);" >
					<div class="addImg" >+</div>
					<!--a href="#" class="addImg" >+</a-->
					<!--input type="file" name="" value="" class="btnfile"-->
				</div>
				<!--end-->
				
			</div>

			
			<!--	描述：上传附件的按钮            -->
			
			<div id="upload_attach" class="inpBox" style="#display:none;">
				<div class="btnAtta" onclick="upload_btn_click(2);">
					<i class="ficon ic_atta"></i>
					<span class="ic_text">上传附件</span>
					
					<!--input type="file" name="" value="" class="btnfile"-->
					
				</div>
			</div>
			<!--end 上传附件按钮-->
			
			<!--
			描述：添加完的附件放在这里 class="inpBox inpBox_atta"
            	attaIteam 为每一条上传的附件
            	附件删除按钮 
            	<a href="#" class="ficon ic_x fr"></a>
            -->
			<div class="inpBox inpBox_atta">
				
				<div id="attach1" href="#" class="attaIteam" style="display:none;">
					<a href="#" class="ficon ic_x fr" onclick='file_del(1)'></a>
					<i id="attachName1" class="ficon ic_file"></i>
				</div>
				
				<div id="attach2" href="#" class="attaIteam" style="display:none;">
					<a href="#" class="ficon ic_x fr" onclick='file_del(2)'></a>
					<i id="attachName2" class="ficon ic_file"></i>
				</div>

				<div id="attach3" href="#" class="attaIteam" style="display:none;">
					<a href="#" class="ficon ic_x fr" onclick='file_del(3)'></a>
					<i id="attachName3" class="ficon ic_file"></i>
				</div>
				
			</div>
			<!--end 结束附件框-->

			<div style="#display: none;">
				<input id="announceform-contentImg" type="hidden" name="AnnounceForm[contentImg]" value="">
				<input id="announceform-attach1" type="hidden" name="AnnounceForm[attach1]" value="">
				<input id="announceform-attach2" type="hidden" name="AnnounceForm[attach2]" value="">
				<input id="announceform-attach3" type="hidden" name="AnnounceForm[attach3]" value="">
				<input id="announceform-attachCount" type="hidden" name="AnnounceForm[attachCount]" value=0>
				<input id="announceform-receiverid" type="hidden" name="AnnounceForm[receiverId]" value=<?=$model->receiverId?>>
				<input id="announceform-receivername" type="hidden" name="AnnounceForm[receiverName]" value=<?=$model->receiver?>>
				<input id="announceform-photo" type="hidden" name="AnnounceForm[photo]" value="">
				<input id="announceform-filetype" type="hidden" name="AnnounceForm[filetype]" value="">
                <input id="announceform-group" type="hidden" name="AnnounceForm[group]" value=<?=$model->group?>>
                <input id="clientType" type="hidden" name="clientType" value=0>
			</div>
			
			
		</div>
	</div>
	</form>
	<div class="moGrid">
		<div class="btnBox">
			<a id="sendBtn" href="javascript:void(0);" class="btnIteam btnSubmit" style="text-decoration:none;" onclick='send_submit();'>				
				<span class="ic_text">发&nbsp;&nbsp;布</span>
			</a>			
		</div>
	</div>
	<div id="uploadfile" style="display:none">
		<form name="upload_form" id="upload_form" class="upload_form"  method="post" target='upload_frame' enctype="multipart/form-data" action="">
			<!--input type="file" id="upload_button" name="upload_file" class="upload_button"  onchange="upload_fun(this.value);"-->
			<input type="file" id="upload_button" name="upload_file" class="upload_button"  onchange="upload_fun(this.value);">
			<iframe id="upload_frame" name="upload_frame" style="display:none"></iframe>
		</form> 
	</div>
</div>
<!--
 *  J-showpic 为弹出曾
 	方法：g('#J-showimg').onclick 显示大图
 *  
 -->
<div id="J-showpic" class="showPic" style="display: none;">
	<div id="J-showwarp">
	<div class="bigImg">
		<img id="contentBigImg"src="">
	</div>
	</div>
	<div class="toolCard">
		<a href="javascript:void(0);" id="J-close" class="close">关闭</a>
		<a href="#" id="J-del" class="del">
			<i class="ficon ic_delbox"></i>
			删除
		</a>
	</div>
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
					接收人
				</h4>
			</div>
			<div class="modal-body" style='height:350px;'>	
				<div style="width:400px; height:320px; margin:auto; #float:left; clear:both; border:1px solid #ccc; overflow:auto;  ">
					<ul id="tree1"></ul>
				</div> 
				<div style="display:none">
				</div>
			</div>
			<div class="modal-footer">
				<button id="closeContacts" type="button" class="btn btn-default"  data-dismiss="modal">关闭
				</button>
				<button id="saveContacts" type="button" class="btn btn-primary" onclick="getChecked()">
					提交更改
				</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->
</div>

<a id="uploadModalBtn" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#uploadModal" data-backdrop="static" style="display:none">
</a>
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
	<div class="modal-dialog" style='z-index:1060'>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="uploadModalLabel">
					上传文件
				</h4>
			</div>
            <div id="errorSize" class="alert alert-danger" style="display:none" >错误！文件不可大于200MB。</div>
			<div class="modal-body">
				<table class="table">
					<thead>
						<tr>
							<th>文件名</th>
							<th>大小</th>
							<th>状态</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td id="up_file_name" style=""></td>
							<td id="up_file_size" style="min-width:6em"></td>
							<td id="up_file_state" style="color:#FF0000;min-width:6em">正在上传</td>
						</tr>
					</tbody>
				</table>
				<div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" onclick="closeModal()">关闭
				</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->
</div>
<a id="sendSucceed" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#sendModal" data-backdrop="static" style="display:none">
</a>
<div class="modal fade" id="sendModal" tabindex="-1" role="dialog" aria-labelledby="sendModalLabel" aria-hidden="true">
	<div class="modal-dialog" style='z-index:1061'>
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="sendModalLabel">
				</h4>
			</div>
			<div class="modal-body">
				<div style="text-align: center;">
                    发送成功！
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" onclick="closeWebview()">确定
				</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->
</div>

<script type="text/javascript">

var win_w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth,
    win_h =  document.documentElement.clientHeight || document.body.clientHeight || window.innerHeight,
    win_scroll_top = document.documentElement.scrollTop || document.body.scrollTop;
    
//定义公共函数
function g( selector ){
	var method = selector.substr(0,1) == '.'?'getElementsByClassName':'getElementById';
	return document[method]( selector.substr(1) );
}
//处理弹出层尺寸
g('#J-showpic').style.width = win_w + 'px';
g('#J-showpic').style.height = win_h + 'px';
g('#J-showpic').style.lineHeight = win_h + 'px';

//点击显示弹出层操作
g('#J-showimg').onclick = function(){
	g('#wrap').style.display = 'none';
	g('#J-showpic').style.display = 'block';
	
	document.addEventListener('DOMContentLoaded', scroller_pic('J-showwarp'), false);
	
}
g('#J-close').onclick = function(){
	g('#wrap').style.display = 'block';
	g('#J-showpic').style.display = 'none';
}

g('#J-del').onclick = function(){
	g('#J-showimg').style.display = 'none';	
	g('#announceform-contentImg').value = '';	
	g('#wrap').style.display = 'block';
	g('#J-showpic').style.display = 'none';
}

var myscroll_pic;
var flag = true;
function scroller_pic(ele){
	
	setTimeout(function(){
	
		myscroll_pic =new iScroll(ele,{
						
			zoom:true,
			zoomMin:1,
			zoomMax:1,
			doubleTapZoom:2,
			wheelAction: 'zoom',
            momentum:false,
			hScrollbar:false, 
			vScrollbar:false,
			
			onZoomEnd:function(){
				if(this.scale>1){
					myscroll.stop();
					myscroll.enabled = false;
				}else{
					myscroll.enabled = true;
				}
				//console.log( this.scale );
			}
			
		});
	
	 },200 );
	              
}
//处理显示的图片在小格里放缩显示
function showpic(){
	var box_w = '',
        img_w = '',
        img_h = '',
        index = 0,
        real_img = { w:'',h:'' };
        
    var box = g('#J-showimg'),
        iteam = g('#J-showimg').getElementsByTagName('img')[0];
     
   	setTimeout(function() {
   		   		
   		var img =  document.createElement('img');
   		img.src = iteam.src;
   		real_img.w = img.width;
   		real_img.h = img.height;
   		//console.log(real_img);
   		var wh = box.clientWidth / box.clientHeight;
		var imgWh = real_img.w / real_img.h;
   		
   		if(wh > imgWh){
		    iteam.style.width = '100%';		        
	    }else{
	    	iteam.style['max-width'] = 'none';
	        iteam.style.height = '100%';
	    }
	    
   		//处理左右的值
	    var left = Math.ceil( (box.clientWidth - box.firstChild.offsetWidth)/2 );
	    var top = Math.ceil( (box.clientHeight - box.firstChild.offsetHeight)/2 );
	    
	    if(wh > imgWh){
	        iteam.style['-webkit-transform'] = 'translateY('+top+'px)';		        
	    }else{
	    	iteam.style['-webkit-transform'] = 'translateX('+left+'px)';	
	    }  
   	
   	},100);
   	
    
}
showpic();
</script>

</body>
</html>
