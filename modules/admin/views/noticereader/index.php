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
<title>查看接收人</title>
<?=Html::cssFile('@web/css/announce.css')?>
<?=Html::jsFile('@web/js/iscroll.js')?>
<?=Html::jsFile('@web/js/jquery.js')?>
</head>
<body>

	
<div id="wrap" class="wrap">
	<div class="moGrid">
		<div class="tabNum">
			<span class="fr corFocus"><i class="ficon ic_checked"></i><?=$confirm_count?></span><span class="fr corFocus"><i class="ficon ic_eye"></i><?=$read_count?></span>
			<span class="fl corDate">共<?=$all_count?>人</span>			
		</div>
		
	</div>
	
	<div class="tabIteam">未查看</div>
	
	<div class="moGrid">
		
		<div class="memBox">
			<?php foreach ($unreaders as $key=>$value): ?>
			<div class="memIteam">
				<?php if($value['photo']!=NULL):?> 
                <p class="pic"><img src="<?=$photoip?><?=$value['photo']?>" /></p>
				<?else:?>
				<p class="pic"><img src="images/u1.jpg" /></p>
				<?endif?>
				<p class="picName"><?=$value['name']?></p>
			</div>
		 <? endforeach?>
			
			
		</div>		
		
	</div>
  
	<div class="tabIteam">已查看</div>
		
	<div class="moGrid">
		
		<div class="memBox">
			<?php foreach ($readers as $key=>$value): ?>
			<div class="memIteam">
			<?php if($value['photo']!=NULL):?> 
                <p class="pic"><img src="<?=$photoip?><?=$value['photo']?>" /></p>
			<?else:?>
			<p class="pic"><img src="images/u1.jpg" /></p>
			<?endif?>
				<p class="picName"><?=$value['name']?></p>
			</div>
			<? endforeach?>
		</div>		
		
	</div>
	
	<div class="tabIteam">已确认</div>
	
	<div class="moGrid">
		
		<div class="memBox">
				<?php foreach ($confirmusers as $key=>$value): ?>
			<div class="memIteam">
				<?php if($value['photo']!=NULL):?> 
                <p class="pic"><img src="<?=$photoip?><?=$value['photo']?>" /></p>
				<?else:?>
				<p class="pic"><img src="images/u1.jpg" /></p>
				<?endif?>
				<p class="picName"><?=$value['name']?></p>
			</div>
			
			<? endforeach?>
		</div>		
		
	</div>
	
</div>

<script type="text/javascript">

var win_w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth,
    win_h =  document.documentElement.clientHeight || document.body.clientHeight || window.innerHeight;
var mag;
var num;

function g( selector ){
	var method = selector.substr(0,1) == '.'?'getElementsByClassName':'getElementById';
	return document[method]( selector.substr(1) );
}	

var membox_w = g('.memBox')[0].clientWidth;
    num = Math.floor( membox_w/80 );
    mag = Math.floor( (membox_w - 80*num)/(num*2) );

for( var i=0; i<g('.memIteam').length; i++ ){
	
	g('.memIteam')[i].style.marginLeft = mag + 'px';
	g('.memIteam')[i].style.marginRight = mag + 'px';
}





</script>

</body>
</html>

