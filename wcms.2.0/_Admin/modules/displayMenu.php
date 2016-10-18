<?php
require_once "../../_config.php";
require_once "../include/commonHeader.php";

//디스플레이 삭제
if($_GET['type'] == "displayDel" && $_GET['sort'] && $_GET['position'])
{
	$db->query(" DELETE FROM `display__".$_GET['skin']."` WHERE sort='".$_GET['sort']."' AND position='".$_GET['position']."' ");
	$db->query(" OPTIMIZE TABLES `display__".$_GET['skin']."` ");
	if($db->getAffectedRows() > 0) 
	{ 
		if(!preg_match('/\/_Admin\//', $_SERVER['HTTP_REFERER']))
		{
			$func->errCfm("Display(Navigation) 유닛이 정상적으로 삭제되었습니다.", "$.dialogRemove();".PHP_EOL."$.insert('#skinSelector', '".$cfg['droot']."_Admin/modules/displayList.php?type=displayList&mode=design&skin=".$_GET['skin']."&position=".$_GET['position']."',null,300);");
		} else 
		{
			$func->ajaxMsg("Display(Navigation) 유닛이 정상적으로 삭제되었습니다.","$.insert('#tabBody".$_GET['position']."', './modules/displayList.php?type=displayList&skin=".$_GET['skin']."&position=".$_GET['position']."',null,300)", 20); 
		}
	}
}
?>
<div class="menu_violet">
	<p title="드래그하여 이동하실 수 있습니다"><strong>Navigation 유닛설정</strong> ( 레이아웃:<?php echo($_GET['skin']);?> / 위치:<?php echo($_GET['position']);?> )</p>
</div>
<div class="tabMenu3">
	<ul class="tabBox">
		<li class="tab" id="tabMenu05"><p><a href="javascript:new_window('<?php echo($cfg['droot']);?>_Admin/wftp/ftp.php?dir=<?php echo($cfg['skin']);?>/image','wftp',800,600,'no','yes');" class="actgray">WEB·FTP 열기</a></p></li>
		<li class="tab" id="tabMenu03"><p><a href="javascript:$.tabMenu('tabMenu03','#tabBodyMenu03','<?php echo($cfg['droot']);?>_Admin/modules/displayMenuImage2.php?type=<?php echo($_GET['type']);?>&skin=<?php echo($_GET['skin']);?>&position=<?php echo($_GET['position']);?>&sort=<?php echo($_GET['sort']);?>',null,200)" class="actgray">3. 이미지타입 이미지 설정</a></p></li>
		<li class="tab" id="tabMenu02"><p><a href="javascript:$.tabMenu('tabMenu02','#tabBodyMenu02','<?php echo($cfg['droot']);?>_Admin/modules/displayMenuImage.php?type=<?php echo($_GET['type']);?>&skin=<?php echo($_GET['skin']);?>&position=<?php echo($_GET['position']);?>&sort=<?php echo($_GET['sort']);?>',null,200)" class="actgray">2. 텍스트타입 이미지 설정</a></p></li>
		<li class="tab on" id="tabMenu01"><p><a href="javascript:$.tabMenu('tabMenu01','#tabBodyMenu01','<?php echo($cfg['droot']);?>_Admin/modules/displayMenuSet.php?type=<?php echo($_GET['type']);?>&skin=<?php echo($_GET['skin']);?>&position=<?php echo($_GET['position']);?>&sort=<?php echo($_GET['sort']);?>',null,200)" class="actgray">1. 네비게이션 환경설정하기</a></p></li>
	</ul>
	<div class="clear"></div>
	<div class="tabBody show" id="tabBodyMenu01"></div>
	<div class="tabBody hide" id="tabBodyMenu02"></div>
	<div class="tabBody hide" id="tabBodyMenu03"></div>
	<div class="tabBody hide" id="tabBodyMenu04"></div>
	<div class="tabBody hide" id="tabBodyMenu05"></div>
	<script type="text/javascript">
	//<![CDATA[
		$(document).ready(function(){
			$("#ajax_display").css('background','#fff');
			$.insert('#tabBodyMenu01','<?php echo($cfg['droot']);?>_Admin/modules/displayMenuSet.php?type=<?php echo($_GET['type']);?>&skin=<?php echo($_GET['skin']);?>&position=<?php echo($_GET['position']);?>&sort=<?php echo($_GET['sort']);?>',null,200);
		});
	//]]>
	</script>
</div>
