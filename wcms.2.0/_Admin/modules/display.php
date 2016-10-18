<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_Admin/include/commonHeader.php";
?>
<h2><span class="arrow">▶</span>디자인 관리 - 디스플레이 설정</h2>
<?php
//디스플레이 삭제
if($_GET['type'] == "displayDel" && $_GET['sort'] && $_GET['position'])
{
	$Rows = $db->queryFetch(" SELECT * FROM `display__".$_GET['skin']."` WHERE sort='".$_GET['sort']."' AND position='".$_GET['position']."' ORDER BY sort ASC ");
	$config = unserialize($Rows['config']);
	@unlink(__HOME__.$cfg['site']['lang']."/image"."/".strtolower($Rows['position'])."_skin".$Rows['sort'].".".$config['listing']); //스킨삭제
	$db->query(" DELETE FROM `display__".$_GET['skin']."` WHERE sort='".$Rows['sort']."' AND position='".$Rows['position']."' ");
	$db->query(" OPTIMIZE TABLES `display__".$_GET['skin']."` ");
	if($db->getAffectedRows() > 0) 
	{ 
		if(!preg_match('/\_Admin\//', $_SERVER['HTTP_REFERER']))
		{
			$func->errCfm("Display 유닛이 정상적으로 삭제되었습니다.", "$.dialogRemove();".PHP_EOL."$.insert('#skinSelector', '".$cfg['droot']."_Admin/modules/displayList.php?type=displayList&mode=design&skin=".$_GET['skin']."&position=".$_GET['position']."',null,300);");
		} 
		else 
		{
			$func->ajaxMsg("Display 유닛이 정상적으로 삭제되었습니다.", "parent.$.dialogRemove();".PHP_EOL."$.insert('#tabBody".$Rows['position']."', './modules/displayList.php?type=displayList&skin=".$_GET['skin']."&position=".$Rows['position']."',null,300)", 20); 
		}
	}
}

if(substr($_GET['position'], 0, 1) == 'M') 
{
?>
<div class="tabMenu2">
<ul class="tabBox">
	<li class="tab<?php if(!$_GET['position'] || $_GET['position']=='MT'){echo(' on');}?>" id="tabMT" style="margin-left:0;"><p><a href="#none" onclick="$.tabMenu('tabMT','#tabBodyMT','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=MT',null,200)" class="actgray">MT(상)</a></p></li>
	<li class="tab<?php if($_GET['position']=='ML'){echo(' on');}?>" id="tabML"><p><a href="#none" onclick="$.tabMenu('tabML','#tabBodyML','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=ML',null,200)" class="actgray">ML(좌)</a></p></li>
	<li class="tab<?php if($_GET['position']=='MC'){echo(' on');}?>" id="tabMC"><p><a href="#none" onclick="$.tabMenu('tabMC','#tabBodyMC','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=MC',null,200)" class="actgray">MC(중)</a></p></li>
	<li class="tab<?php if($_GET['position']=='MR'){echo(' on');}?>" id="tabMR"><p><a href="#none" onclick="$.tabMenu('tabMR','#tabBodyMR','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=MR',null,200)" class="actgray">MR(우)</a></p></li>
	<li class="tab<?php if($_GET['position']=='MB'){echo(' on');}?>" id="tabMB"><p><a href="#none" onclick="$.tabMenu('tabMB','#tabBodyMB','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=MB',null,200)" class="actgray">MB(하)</a></p></li>
	<li class="tab<?php if($_GET['position']=='MF'){echo(' on');}?>" id="tabMF"><p><a href="#none" onclick="$.tabMenu('tabMF','#tabBodyMF','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=MF',null,200)" class="actgray">MF(풋)</a></p></li>
	<li class="tab<?php if($_GET['position']=='MQ'){echo(' on');}?>" id="tabMQ"><p><a href="#none" onclick="$.tabMenu('tabMQ','#tabBodyMQ','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=MQ',null,200)" class="actgray">MQ(퀵)</a></p></li>
</ul>
<div class="tabBody<?php if(!$_GET['position'] || $_GET['position']=='MT'){echo(' on');}else{echo(' hide');}?>" id="tabBodyMT"></div>
<div class="tabBody<?php if($_GET['position']=='ML'){echo(' on');}else{echo(' hide');}?>" id="tabBodyML"></div>
<div class="tabBody<?php if($_GET['position']=='MC'){echo(' on');}else{echo(' hide');}?>" id="tabBodyMC"></div>
<div class="tabBody<?php if($_GET['position']=='MR'){echo(' on');}else{echo(' hide');}?>" id="tabBodyMR"></div>
<div class="tabBody<?php if($_GET['position']=='MB'){echo(' on');}else{echo(' hide');}?>" id="tabBodyMB"></div>
<div class="tabBody<?php if($_GET['position']=='MF'){echo(' on');}else{echo(' hide');}?>" id="tabBodyMF"></div>
<div class="tabBody<?php if($_GET['position']=='MQ'){echo(' on');}else{echo(' hide');}?>" id="tabBodyMQ"></div>
</div>
<?php 
} else if(substr($_GET['position'], 1, 2) == 'M')
{ 
?>
<div class="tabMenu2">
<ul class="tabBox">
	<li class="tab<?php if(!$_GET['position'] || $_GET['position']=='TM'){echo(' on');}?>" id="tabTM" style="margin-left:0;"><p><a href="#none" onclick="$.tabMenu('tabTM','#tabBodyTM','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=TM',null,200)" class="actgray">TM(상)</a></p></li>
	<li class="tab<?php if($_GET['position']=='LM'){echo(' on');}?>" id="tabLM"><p><a href="#none" onclick="$.tabMenu('tabLM','#tabBodyLM','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=LM',null,200)" class="actgray">LM(좌)</a></p></li>
	<li class="tab<?php if($_GET['position']=='CM'){echo(' on');}?>" id="tabCM"><p><a href="#none" onclick="$.tabMenu('tabCM','#tabBodyCM','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=CM',null,200)" class="actgray">CM(중)</a></p></li>
	<li class="tab<?php if($_GET['position']=='RM'){echo(' on');}?>" id="tabRM"><p><a href="#none" onclick="$.tabMenu('tabRM','#tabBodyRM','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=RM',null,200)" class="actgray">RM(우)</a></p></li>
	<li class="tab<?php if($_GET['position']=='BM'){echo(' on');}?>" id="tabBM"><p><a href="#none" onclick="$.tabMenu('tabBM','#tabBodyBM','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=BM',null,200)" class="actgray">BM(하)</a></p></li>
	<li class="tab<?php if($_GET['position']=='FM'){echo(' on');}?>" id="tabFM"><p><a href="#none" onclick="$.tabMenu('tabFM','#tabBodyFM','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=FM',null,200)" class="actgray">FM(풋)</a></p></li>
	<li class="tab<?php if($_GET['position']=='QM'){echo(' on');}?>" id="tabQM"><p><a href="#none" onclick="$.tabMenu('tabQM','#tabBodyQM','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=QM',null,200)" class="actgray">QM(퀵)</a></p></li>
</ul>
<div class="tabBody<?php if(!$_GET['position'] || $_GET['position']=='TM'){echo(' on');}else{echo(' hide');}?>" id="tabBodyTM"></div>
<div class="tabBody<?php if($_GET['position']=='LM'){echo(' on');}else{echo(' hide');}?>" id="tabBodyLM"></div>
<div class="tabBody<?php if($_GET['position']=='CM'){echo(' on');}else{echo(' hide');}?>" id="tabBodyCM"></div>
<div class="tabBody<?php if($_GET['position']=='RM'){echo(' on');}else{echo(' hide');}?>" id="tabBodyRM"></div>
<div class="tabBody<?php if($_GET['position']=='BM'){echo(' on');}else{echo(' hide');}?>" id="tabBodyBM"></div>
<div class="tabBody<?php if($_GET['position']=='FM'){echo(' on');}else{echo(' hide');}?>" id="tabBodyFM"></div>
<div class="tabBody<?php if($_GET['position']=='QM'){echo(' on');}else{echo(' hide');}?>" id="tabBodyQM"></div>
</div>
<?php 
} else if(substr($_GET['position'], 1, 2) == 'S')
{ 
?>
<div class="tabMenu2">
<ul class="tabBox">
	<li class="tab<?php if(!$_GET['position'] || $_GET['position']=='TS'){echo(' on');}?>" id="tabTS" style="margin-left:0;"><p><a href="#none" onclick="$.tabMenu('tabTS','#tabBodyTS','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=TS',null,200)" class="actgray">TS(상)</a></p></li>
	<li class="tab<?php if($_GET['position']=='LS'){echo(' on');}?>" id="tabLS"><p><a href="#none" onclick="$.tabMenu('tabLS','#tabBodyLS','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=LS',null,200)" class="actgray">LS(좌)</a></p></li>
	<li class="tab<?php if($_GET['position']=='CS'){echo(' on');}?>" id="tabCS"><p><a href="#none" onclick="$.tabMenu('tabCS','#tabBodyCS','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=CS',null,200)" class="actgray">CS(중1)</a></p></li>
	<li class="tab<?php if($_GET['position']=='MS'){echo(' on');}?>" id="tabMS"><p><a href="#none" onclick="$.tabMenu('tabMS','#tabBodyMS','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=MS',null,200)" class="actgray">CS(중2)</a></p></li>
	<li class="tab<?php if($_GET['position']=='RS'){echo(' on');}?>" id="tabRS"><p><a href="#none" onclick="$.tabMenu('tabRS','#tabBodyRS','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=RS',null,200)" class="actgray">RS(우)</a></p></li>
	<li class="tab<?php if($_GET['position']=='BS'){echo(' on');}?>" id="tabBS"><p><a href="#none" onclick="$.tabMenu('tabBS','#tabBodyBS','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=BS',null,200)" class="actgray">BS(하)</a></p></li>
	<li class="tab<?php if($_GET['position']=='FS'){echo(' on');}?>" id="tabFS"><p><a href="#none" onclick="$.tabMenu('tabFS','#tabBodyFS','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=FS',null,200)" class="actgray">FS(풋)</a></p></li>
	<li class="tab<?php if($_GET['position']=='QS'){echo(' on');}?>" id="tabQS"><p><a href="#none" onclick="$.tabMenu('tabQS','#tabBodyQS','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=QS',null,200)" class="actgray">QS(퀵)</a></p></li>
	<li class="tab<?php if($_GET['position']=='CS2'){echo(' on');}?>" id="tabCS2"><p><a href="#none" onclick="$.tabMenu('tabCS2','#tabBodyCS2','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=CS2',null,200)" class="actgray">CS2(콘)</a></p></li>
</ul>
<div class="tabBody<?php if(!$_GET['position'] || $_GET['position']=='TS'){echo(' on');}else{echo(' hide');}?>" id="tabBodyTS"></div>
<div class="tabBody<?php if($_GET['position']=='LS'){echo(' on');}else{echo(' hide');}?>" id="tabBodyLS"></div>
<div class="tabBody<?php if($_GET['position']=='CS'){echo(' on');}else{echo(' hide');}?>" id="tabBodyCS"></div>
<div class="tabBody<?php if($_GET['position']=='MS'){echo(' on');}else{echo(' hide');}?>" id="tabBodyMS"></div>
<div class="tabBody<?php if($_GET['position']=='RS'){echo(' on');}else{echo(' hide');}?>" id="tabBodyRS"></div>
<div class="tabBody<?php if($_GET['position']=='BS'){echo(' on');}else{echo(' hide');}?>" id="tabBodyBS"></div>
<div class="tabBody<?php if($_GET['position']=='FS'){echo(' on');}else{echo(' hide');}?>" id="tabBodyFS"></div>
<div class="tabBody<?php if($_GET['position']=='QS'){echo(' on');}else{echo(' hide');}?>" id="tabBodyQS"></div>
<div class="tabBody<?php if($_GET['position']=='CS2'){echo(' on');}else{echo(' hide');}?>" id="tabBodyCS2"></div>
</div>
<?php 
} 
else 
{ 
?>
<div class="tabMenu2">
<ul class="tabBox">
	<li class="tab<?php if(!$_GET['position'] || $_GET['position']=='ST'){echo(' on');}?>" id="tabST" style="margin-left:0;"><p><a href="#none" onclick="$.tabMenu('tabST','#tabBodyST','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=ST',null,200)" class="actgray">ST(상)</a></p></li>
	<li class="tab<?php if($_GET['position']=='SL'){echo(' on');}?>" id="tabSL"><p><a href="#none" onclick="$.tabMenu('tabSL','#tabBodySL','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=SL',null,200)" class="actgray">SL(좌)</a></p></li>
	<li class="tab<?php if($_GET['position']=='SC'){echo(' on');}?>" id="tabSC"><p><a href="#none" onclick="$.tabMenu('tabSC','#tabBodySC','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=SC',null,200)" class="actgray">SC(중1)</a></p></li>
	<li class="tab<?php if($_GET['position']=='SM'){echo(' on');}?>" id="tabSM"><p><a href="#none" onclick="$.tabMenu('tabSM','#tabBodySM','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=SM',null,200)" class="actgray">SC(중2)</a></p></li>
	<li class="tab<?php if($_GET['position']=='SR'){echo(' on');}?>" id="tabSR"><p><a href="#none" onclick="$.tabMenu('tabSR','#tabBodySR','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=SR',null,200)" class="actgray">SR(우)</a></p></li>
	<li class="tab<?php if($_GET['position']=='SB'){echo(' on');}?>" id="tabSB"><p><a href="#none" onclick="$.tabMenu('tabSB','#tabBodySB','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=SB',null,200)" class="actgray">SB(하)</a></p></li>
	<li class="tab<?php if($_GET['position']=='SF'){echo(' on');}?>" id="tabSF"><p><a href="#none" onclick="$.tabMenu('tabSF','#tabBodySF','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=SF',null,200)" class="actgray">SF(풋)</a></p></li>
	<li class="tab<?php if($_GET['position']=='SQ'){echo(' on');}?>" id="tabSQ"><p><a href="#none" onclick="$.tabMenu('tabSQ','#tabBodySQ','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=SQ',null,200)" class="actgray">SQ(퀵)</a></p></li>
	<li class="tab<?php if($_GET['position']=='CR'){echo(' on');}?>" id="tabCR"><p><a href="#none" onclick="$.tabMenu('tabCR','#tabBodyCR','<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=CR',null,200)" class="actgray">CR(콘)</a></p></li>
</ul>
<div class="tabBody<?php if(!$_GET['position'] || $_GET['position']=='ST'){echo(' on');}else{echo(' hide');}?>" id="tabBodyST"></div>
<div class="tabBody<?php if($_GET['position']=='SL'){echo(' on');}else{echo(' hide');}?>" id="tabBodySL"></div>
<div class="tabBody<?php if($_GET['position']=='SC'){echo(' on');}else{echo(' hide');}?>" id="tabBodySC"></div>
<div class="tabBody<?php if($_GET['position']=='SM'){echo(' on');}else{echo(' hide');}?>" id="tabBodySM"></div>
<div class="tabBody<?php if($_GET['position']=='SR'){echo(' on');}else{echo(' hide');}?>" id="tabBodySR"></div>
<div class="tabBody<?php if($_GET['position']=='SB'){echo(' on');}else{echo(' hide');}?>" id="tabBodySB"></div>
<div class="tabBody<?php if($_GET['position']=='SF'){echo(' on');}else{echo(' hide');}?>" id="tabBodySF"></div>
<div class="tabBody<?php if($_GET['position']=='SQ'){echo(' on');}else{echo(' hide');}?>" id="tabBodySQ"></div>
<div class="tabBody<?php if($_GET['position']=='CR'){echo(' on');}else{echo(' hide');}?>" id="tabBodyCR"></div>
</div>
<?php 
} 
?>
<script type="text/javascript">
//<![CDATA[
	$(document).ready(function(){
		$.insert("#tabBody<?php echo($_GET['position']);?>","<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayList&skin=<?php echo($_GET['skin']);?>&position=<?php echo($_GET['position']);?>",null,200);
	});
//]]>
</script>

