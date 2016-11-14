<?php
require_once "../../_config.php";
require_once "./commonHeader.php";

$weeks = array('일','월','화','수','목','금','토');

if($func->checkModule('mdApp01'))
{
	$mdApp01 = $db->queryFetch(" SELECT COUNT(*) AS count,cate FROM `mdApp01__content` WHERE state='0' AND DATE_FORMAT(FROM_UNIXTIME(dateReg),'%Y%m%d')='".date("Ymd")."' ");
}
?>
<div class="pd7">
	<ul>
    	<li style="padding-left:3px;" class="colorViolet"><strong><?php echo(date("Y .m .d"));?></strong>&nbsp;<span>(<?php echo($weeks[date('w')]);?>)</span></li>
    	<li class="colorViolet">&nbsp;<strong><?php echo(date("A"));?></strong><span><?php echo(date("h:i"));?></span></li>
		<?php if($func->checkModule('mdApp01')) { ?>
		<li class="colorGray wrap15 center">|</li>
		<li class="small_gray"><a href="javascript:;" onclick="$.insert('#module', '../modules/mdApp01/manage/_controll.php?type=list&amp;cate=<?php echo($mdApp01['cate']);?>&amp;state=0',null,300); $.menus('#left_mdApp01', '../modules/mdApp01/manage/_left.php', '#left_mdApp01_a');"><span>신규상담</span><span>:</span><span class="colorOrange"><?php echo(number_format($mdApp01['count']));?></span>건</a></li>
		<?php } ?>
		<?php if($func->checkModule('mdMessage')) { ?>
		<li class="colorGray wrap15 center">|</li>
		<li class="small_gray"><a href="javascript:;"><span>신규쪽지</span><span>:</span><span class="colorOrange"><?php echo(number_format($sess->counting('today')));?></span>건</a></li>
		<?php } ?>
<!--
		<?php if($func->checkModule('mdProduct')) { ?>
		<li class="colorGray wrap15 center">|</li>
		<li class="small_gray"><a href="javascript:;"><span>오늘재고</span><span>:</span><span class="colorOrange"><?php echo(number_format($sess->counting('today')));?></span>건</a></li>
		<?php } ?>
-->
		<?php
			if($func->checkModule('mdOrder')) {
				$todayOrder = $db->queryFetch("SELECT IFNULL(SUM(if(status='0',1,0)),0) AS s0 FROM `mdOrder__order` WHERE SUBSTR(regdate,1,10) ='".date("Y-m-d")."' ");
		?>
		<li class="colorGray wrap15 center">|</li>
		<li class="small_gray"><a href="javascript:;"><span>오늘 결제</span><span>:</span><span class="colorOrange"><?php echo(number_format($todayOrder['s0']));?></span>건</a></li>
		<?php } ?>
		<?php
			if($func->checkModule('mdMileage')) {
				$todayMileage = $db->queryFetch(" SELECT SUM(mileage) AS mileage FROM `mdMileage__mileage` WHERE mileageType = 'A' AND SUBSTR(regdate,1,10) ='".date("Y-m-d")."' ");
		?>
		<li class="colorGray wrap15 center">|</li>
		<li class="small_gray"><a href="javascript:;"><span>오늘 적립</span><span>:</span><span><strong class="colorOrange"><?php echo(number_format($todayMileage['mileage']));?></strong></span>Point</a></li>
		<?php } ?>
	</ul>
	<div class="clear"></div>
</div>
<script type="text/javascript">
//<[!CDATA[
	$(document).ready(function(){setTimeout("$.insert('#lastest','./include/lastest.php');", 500000);});
//]]>
</script>
