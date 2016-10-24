<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\web\View;

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>公告列表</title>
<?=Html::cssFile('@web/css/announce.css')?>
<?=Html::cssFile('@web/css/bootstrap.css')?>
<?=Html::jsFile('@web/js/iscroll.js')?>
<?=Html::jsFile('@web/js/jquery.js')?>
<?=Html::jsFile('@web/js/bootstrap.js')?>
<script type="text/javascript">

var myScroll,
	pullDownEl, pullDownOffset,
	pullUpEl, pullUpOffset,
	generatedCount = 0;

//下拉加载数据  模拟加载了几个死数据
function pullDownAction () {
	location.reload(); 
/* 	setTimeout(function () {	
		
		myScroll.refresh();		// 当内容完事儿，记得刷新 (ie: on ajax completion)
	}, 1000); */	 
}
function getLocalTime(nS) {   
// 	alert(nS);
	
	var date = new Date(nS*1000);
	Y = date.getFullYear() + '-';
	M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
	D = (date.getDate()+1 < 10 ? '0'+(date.getDate()+1) : date.getDate()+1)  + ' ';
	h =  (date.getHours()+1 < 10 ? '0'+(date.getHours()+1) : date.getHours()+1) + ':';
	m = (date.getMinutes()+1 < 10 ? '0'+(date.getMinutes()+1) : date.getMinutes()+1)  + ':';
	s =  (date.getSeconds()+1 < 10 ? '0'+(date.getSeconds()+1) : date.getSeconds()+1); 
// 	alert(Y+M+D+h+m+s);
	return Y+M+D+h+m+s;  
     
 }     
 function del(nid){
     if (!confirm("确认要删除？")) {
         return;
     }
     //alert(nid);
     $.get("index.php?r=admin/notice/del",{nid:nid},
     function(data){
      //   alert(data);
         if(data){
             $("#contentlist_"+nid).remove(); 
         }else{                           
             alert("操作失败!");
         }
     },'json');	

}

function pullUpAction () {

/* 	var el, iteam, i;
	el = document.getElementById('listBox'); */
	
 	setTimeout(function () {	
		var el, iteam, i,pageSize;
		el = document.getElementById('listBox');

		$.get("index.php?r=admin/notice/getdata",{searchcontent:$("#searchtitle").val()
		},function(data){
// 			alert(data);
// 	 		alert(JSON.stringify(data));	
			var list=eval(data);
			if(list.length){
				
				for (i=0; i<list.length; i++) {
// 					alert(list[i].title); 
					
					iteam = document.createElement('div');
					iteam.className = 'listIteam';
					iteam.id = "contentlist_"+list[i].announce_id;
				
// 					var time=getLocalTime(list[i].time); 
					relation=list[i].relation;	 
				
                    // 					iteam.innerHTML = "<a href='index.php?r=admin/noticecontent/index&f=1&id="+list[i].announce_id+"' class="listInner"><p class="listInfo"><span class="title"><i class="ficon ic_new">NEW</i>'+list[i].title+'</span></p><p class="listInfo"><span class="date">'+time+'</span></p><span class="tip ficon ic_arrow_right"></span></a>";
                    //
                    if(parseInt(list[i].comment_switch, '10')>=1 && (list[i].top_time > <?=$time?> || list[i].top_time == null)){
                        comment = "<span style='color:red'>[置顶]&nbsp;</span>";
                    }else{
                        comment = "";
                    }
                    if(list[i].sender == "<?= Yii::$app->session['user.uid']?>"){
                //        delStr = "<a href='index.php?r=admin/notice/del&nid="+list[i].announce_id+"' class='info_del' onClick=\"return confirm('确定删除?');\">删除</a>";
                        delStr = "<a href='javascript:void(0)' class='info_del' onClick=\"del("+list[i].announce_id+");\">删除</a>";
                    }else{
                        delStr = "";
                    
                    }
                    if(relation=="unread"){
                        iteam.innerHTML = "<a href='index.php?r=admin/noticecontent/index&f=1&id="+list[i].announce_id+"' class='listInner'><p class='listInfo'><span class='title'><i class='ficon ic_new'>NEW</i>"+comment+list[i].title+"</span></p><p class='listInfo'><span class='date'>"+list[i].noticetime+"</span></p><span class='tip ficon ic_arrow_right'></span></a>"+delStr;

	
						}else{
						iteam.innerHTML = "<a href='index.php?r=admin/noticecontent/index&f=1&id="+list[i].announce_id+"'  class='listInner'><p class='listInfo'><span class='title'>"+comment+list[i].title+"</span></p><p class='listInfo'><span class='date'>"+list[i].noticetime+"</span></p><span class='tip ficon ic_arrow_right'></span></a>"+delStr;
						}
					
					el.appendChild(iteam, el.childNodes[0]);
				}
				myScroll.refresh();	 
// 				start=start+Number(3);
// 				alert('success');
			}else{                                 //没有更多数据
				/* pullUpEl = document.getElementById('pullUp');
				pullUpEl.querySelector('.pullUpLabel').innerHTML = '已没有更多数据...'; */
// 				alert("fail");
				document.getElementById("sendSucceed").click();
// 				alert("已经没有更多数据");
			}
		},'json');	
	
	/* for (i=0; i<3; i++) {
			iteam = document.createElement('div');
			iteam.className = 'listIteam';
			
			iteam.innerHTML = '<a href="#" class="listInner"><p class="listInfo"><span class="title"><i class="ficon ic_new">NEW</i>閫氱煡鏍囬鐜鏍囧織</span></p><p class="listInfo"><span class="date">鍓嶅ぉ 07:56</span></p><span class="tip ficon ic_arrow_right"></span></a>';
			
			
			el.appendChild(iteam, el.childNodes[0]);
		}  */
		
		myScroll.refresh();		// 当内容完事儿，记得刷新(ie: on ajax completion)
	}, 1000);	
}

function loaded() {
	pullDownEl = document.getElementById('pullDown');
	pullDownOffset = pullDownEl.offsetHeight;
	pullUpEl = document.getElementById('pullUp');	
	pullUpOffset = pullUpEl.offsetHeight;
	
	myScroll = new iScroll('wrap', {
			useTransition: true,
		topOffset: pullDownOffset,
		onRefresh: function () {
			if (pullDownEl.className.match('loading')) {
				pullDownEl.className = '';
				pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉刷新页面...';
			} else if (pullUpEl.className.match('loading')) {
				pullUpEl.className = '';
				pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多...';
			}
		},
		onScrollMove: function () {
			if (this.y > 5 && !pullDownEl.className.match('flip')) {
				pullDownEl.className = 'flip';
				pullDownEl.querySelector('.pullDownLabel').innerHTML = '释放即可加载...';
				this.minScrollY = 0;
			} else if (this.y < 5 && pullDownEl.className.match('flip')) {
				pullDownEl.className = '';
				pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉刷新页面...';
				this.minScrollY = -pullDownOffset;
			} else if (this.y < (this.maxScrollY - 5) && !pullUpEl.className.match('flip')) {
				pullUpEl.className = 'flip';
				pullUpEl.querySelector('.pullUpLabel').innerHTML = '释放即可加载...';
				this.maxScrollY = this.maxScrollY;
			} else if (this.y > (this.maxScrollY + 5) && pullUpEl.className.match('flip')) {
				pullUpEl.className = '';
				pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉即可加载...';
				this.maxScrollY = pullUpOffset;
			}
		},
		onScrollEnd: function () {
			if (pullDownEl.className.match('flip')) {
				pullDownEl.className = 'loading';
				pullDownEl.querySelector('.pullDownLabel').innerHTML = '加载中...';				
				pullDownAction();	// 执行自定义函数（Ajax调用等）
			} else if (pullUpEl.className.match('flip')) {
				pullUpEl.className = 'loading';
				pullUpEl.querySelector('.pullUpLabel').innerHTML = '加载中...';				
				pullUpAction();	// 执行自定义函数（Ajax调用等）
			}
		}
	});
	
setTimeout(function () { document.getElementById('wrap').style.left = '0'; }, 800);
}

document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);

function allowFormsInIscroll(){
 [].slice.call(document.querySelectorAll('input, select, button')).forEach(function(el){
 el.addEventListener(('ontouchstart' in window)?'touchstart':'mousedown', function(e){
 e.stopPropagation();
 
 })
 })
 }
 document.addEventListener('DOMContentLoaded', allowFormsInIscroll, false);

 
</script>



</head>
<body>
	
<div id="wrap" class="wrap">
<div id="scroller">
	<div id="pullDown">
		<span class="pullDownIcon"></span><span class="pullDownLabel">下拉即可加载...</span>
	</div>
	<div class="moBox"  id="searchBox">
		<div class="searchBox">
			<div class="searchInner">
			<input id="searchtitle" class="inpSearch" name="searchtitle" value="" type="text" placeholder="输入搜索关键字">			
			<a href="javascript:void(0);" class="ficon ic_search" id="search"></a>
			</div>
		</div>		
	</div>
	<div class="moBox">
		<div id="listBox" class="listBox">
			<div class="listIteam" id="empty">
				<div class="empty">暂无公告</div>
			</div>
			<?php foreach ($NoticeList as $key=>$value): ?>
			<div class="listIteam"  id="contentlist_<?=$value['announce_id']?>">
				<a href="index.php?r=admin/noticecontent/index&f=1&id=<?=$value['announce_id']?>" class="listInner">
					<p class="listInfo">						
                    <span class="title"><?php if($value['relation']=="unread"):?><i class="ficon ic_new"  id="icon_new_<?=$value['announce_id']?>">NEW</i><?endif?><?php if($value['comment_switch'] >= 1 && ($value['top_time']>$time || $value['top_time']==null)):?><span style='color:red'>[置顶] </span><?endif?><?=$value['title']?></span>
					</p>
					<p class="listInfo">
						<span class="date"><?=date('Y-m-d H:i:s',$value['time'])?></span>
					</p>
					<span class="tip ficon ic_arrow_right"></span>
				</a>
                <?if($value['sender'] == Yii::$app->session['user.uid']):?>
                    <!--a href="index.php?r=admin/notice/del&nid=<?=$value['announce_id']?>" class="info_del" onClick="return confirm('确定删除?');">删除</a-->
                    <a href="javascript:void(0)" class="info_del" onClick="del('<?=$value['announce_id']?>');">删除</a>
                <?endif?>
			</div>
			<? endforeach?>
		</div>
	</div>
	<div id="pullUp">
		<span class="pullUpIcon"></span><span class="pullUpLabel">上拉加载更多...</span>
	</div>
	<input type="hidden" id="count" value=<?=$count?>/>
</div>
<?if($admin):?>
<div class="nav_btm">
    <a class="navLink" href="index.php?r=admin/announce/index&gid=0&main=1">发布公告</a>
</div>
<?endif?>
</div>
<a id="sendSucceed" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#sendModal" style="display:none">
</a>

<div class="modal fade" id="sendModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
	<div class="modal-dialog" style='z-index:1060'>
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="uploadModalLabel">
				</h4>
			</div>
			<div class="modal-body">
				<div style="text-align: center;">
                   已经没有更多数据！
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" onclick="">确定
				</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->
</div>
</body>
</html>
<script type="text/javascript">
// <!--

//-->
document.getElementById('searchtitle').addEventListener('input', function(e){
//     var value = e.target.value;
	$.get("index.php?r=admin/notice/search",{searchtitle:$("#searchtitle").val()
	},function(data){
//	 		alert(JSON.stringify(data));
		var list=eval(data);
		var relation;
		$("#listBox").empty();	
		if(list.length){           //如果获得了数据			
			for (i=0; i<list.length; i++) {
//					alert(i);
				iteam = document.createElement('div');
				iteam.className = 'listIteam';		
				relation=list[i].relation;	
				var time=getLocalTime(list[i].time); 
//					alert(relation);	
				if(relation=="unread"){
					iteam.innerHTML = "<a href='index.php?r=admin/noticecontent/index&f=1&id="+list[i].announce_id+"' class='listInner'><p class='listInfo'><span class='title'><i class='ficon ic_new'>NEW</i>"+list[i].title+"</span></p><p class='listInfo'><span class='date'>"+time+"</span></p><span class='tip ficon ic_arrow_right'></span></a>";

					}else{
					iteam.innerHTML = "<a href='index.php?r=admin/noticecontent/index&f=1&id="+list[i].announce_id+"'  class='listInner'><p class='listInfo'><span class='title'>"+list[i].title+"</span></p><p class='listInfo'><span class='date'>"+time+"</span></p><span class='tip ficon ic_arrow_right'></span></a>";
					}
			
				$("#listBox").append(iteam);					
				}
//				alert(iteam);
		
//				el.appendChild(iteam, el.childNodes[0]);
			
/* 	 		if(list.length>5){
				$("#pullUp").show();
			}else{
				$("#pullUp").hide();
			}  */
			
// 			alert(iteam);
// 			el。html(iteam);
//				alert('success');
		}else{              //如果没有数据，提示空
// 			alert(123);
			iteam = document.createElement('div');
			iteam.className = 'listIteam';			
			iteam.innerHTML = '<div class="empty">暂无公告</div>';				
//				$("#listBox").empty();
// 			$("#pullUp").hide();
			$("#listBox").append(iteam);
//				alert("fail");
		}
	},'json');
});
/* var bind_name="input";//定义所要绑定的事件名称
if(navigator.userAgent.indexOf("MSIE")!=-1) {
	bind_name="propertychange";//判断是否为IE内核 IE内核的事件名称要改为propertychange
} */
/*输入框键盘离开事件绑定*/
/* $("#searchtitle").bind(bind_name,function(){
    if(this.value!=null&&this.value!=""){
        
  var inputWidth=$(this).outerWidth();
        var inputHeight=$(this).outerHeight();
        var inputOffset =  $(this).offset();
        var inputTop=inputOffset.top;
        var inputLeft=inputOffset.left;
        $("#searchBox").css({top:inputTop+2,left:inputLeft+inputWidth-27}).show();
        inputObj=this; 
    }else{
    	location.reload(); 

    }
}); */
function getLocalTime(nS) {   
// 	alert(nS);
	
	var date = new Date(nS*1000);
	Y = date.getFullYear() + '-';
	M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
	D = (date.getDate()+1 < 10 ? '0'+(date.getDate()+1) : date.getDate()+1)  + ' ';
	h =  (date.getHours()+1 < 10 ? '0'+(date.getHours()+1) : date.getHours()+1) + ':';
	m = (date.getMinutes()+1 < 10 ? '0'+(date.getMinutes()+1) : date.getMinutes()+1)  + ':';
	s =  (date.getSeconds()+1 < 10 ? '0'+(date.getSeconds()+1) : date.getSeconds()+1); 
// 	alert(Y+M+D+h+m+s);
	return Y+M+D+h+m+s;  
     
 }
$(function(){
// 	alert("refresh");
/* 	if($("#wrap").scrollTop()){
		$("#pullUp").show();
	}else{
		$("#pullUp").hide();
	} */
	$("#searchtitle").val("");
    var count=$("#count").val();
    if(count){
        $("#empty").hide();
    }else{
    	$("#empty").show();
    }
	var count=$("#count").val();
	if(count*1>10){
		$("#pullUp").show();
	}else{
		$("#pullUp").hide();
	}
	
});
$("#searchtitle").keyup(function(){
// 	alert(123);
// 	var el, iteam, i;
// 	el = document.getElementById('listBox');
// ;
// 	if($("#searchtitle").val()!=""){
// 		$.get("index.php?r=admin/notice/search",{searchtitle:$("#searchtitle").val()
// 		},function(data){

// 			var list=eval(data);
// 			var relation;
// 			$("#listBox").empty();	
// 			if(list.length){           //如果获得了数据			
// 				for (i=0; i<list.length; i++) {
// // 					alert(i);
// 					iteam = document.createElement('div');
// 					iteam.className = 'listIteam';		
// 					relation=list[i].relation;	
// 					var time=getLocalTime(list[i].time); 
// // 					alert(relation);	
// 					if(relation=="unread"){
// 						iteam.innerHTML = "<a href='index.php?r=admin/noticecontent/index&f=1&id="+list[i].announce_id+"' class='listInner'><p class='listInfo'><span class='title'><i class='ficon ic_new'>NEW</i>"+list[i].title+"</span></p><p class='listInfo'><span class='date'>"+time+"</span></p><span class='tip ficon ic_arrow_right'></span></a>";
	
// 						}else{
// 						iteam.innerHTML = "<a href='index.php?r=admin/noticecontent/index&f=1&id="+list[i].announce_id+"'  class='listInner'><p class='listInfo'><span class='title'>"+list[i].title+"</span></p><p class='listInfo'><span class='date'>"+time+"</span></p><span class='tip ficon ic_arrow_right'></span></a>";
// 						}
				
// 					$("#listBox").append(iteam);					
// 					}
// // 				alert(iteam);
			
// // 				el.appendChild(iteam, el.childNodes[0]);
				
// 			/* 	if(list.length>5){
// 					$("#pullUp").show();
// 				}else{
// 					$("#pullUp").hide();
// 				} */
				
// //	 			alert(iteam);
// //	 			el。html(iteam);
// // 				alert('success');
// 			}else{              //如果没有数据，提示空
// //	 			alert(123);
// 				iteam = document.createElement('div');
// 				iteam.className = 'listIteam';			
// 				iteam.innerHTML = '<div class="empty">暂无公告</div>';				
// // 				$("#listBox").empty();
// 				$("#pullUp").hide();
// 				$("#listBox").append(iteam);
// // 				alert("fail");
// 			}
// 		},'json');
// // 		myScroll.refresh();	

// 	}else{
// 	  /*   alert("请输入关键字！"); */
// 	}

});

	$("div[id^='contentlist']").each(function(){
	    $(this).click(function(){    
	      	var imgid = $(this).attr("id");
	        var  imgidlist=imgid.split("_");
// 	        var icon="icon_new_"+imgidlist[1];
// 	        alert("#icon_new_"+imgidlist[1]);
// 	        $("#icon_new_"+imgidlist[1]).attr("display",true);
	        $(this).find("i[id^=icon_new]").hide();
// 	        $("#icon_new_"+imgidlist[1]).hide();
// 	       alert(icon); 
	    })
	 });




</script>
