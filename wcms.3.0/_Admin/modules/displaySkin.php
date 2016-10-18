<?php
require_once "../../_config.php";
require_once "../include/commonHeader.php";
?>
<div class="menu_violet"><p title="드래그하여 이동하실 수 있습니다"><strong>Skin 유닛설정</strong> ( 레이아웃:<?php echo($_GET['skin']);?> / 위치:<?php echo($_GET['position']);?> )</p></div>
<div class="tabMenu3">
	<ul class="tabBox">
		<li class="tab" id="tabSkin03"><p><a href="javascript:new_window('<?php echo($cfg['droot']);?>_Admin/wftp/ftp.php?dir=<?php echo($cfg['skin']);?>/image','wftp',800,600,'no','yes');" class="actgray">WEB·FTP 열기</a></p></li>
		<li class="tab" id="tabSkin02"><p><a href="javascript:$.tabMenu('tabSkin02','#tabBodySkin02','<?php echo($cfg['droot']);?>_Admin/modules/displaySkinImage.php?type=<?php echo($_GET['type']);?>&skin=<?php echo($_GET['skin']);?>&position=<?php echo($_GET['position']);?>&sort=<?php echo($_GET['sort']);?>',null,200)" class="actgray">2. 스킨(기타)정보</a></p></li>
		<li class="tab on" id="tabSkin01"><p><a href="javascript:$.tabMenu('tabSkin01','#tabBodySkin01','<?php echo($cfg['droot']);?>_Admin/modules/displaySkinSet.php?type=<?php echo($_GET['type']);?>&skin=<?php echo($_GET['skin']);?>&position=<?php echo($_GET['position']);?>&sort=<?php echo($_GET['sort']);?>',null,200)" class="actgray">1. 스킨 설정하기</a></p></li>
	</ul>
	<div class="clear"></div>
	<div class="tabBody show" id="tabBodySkin01"></div>
	<div class="tabBody hide" id="tabBodySkin02"></div>
	<div class="tabBody hide" id="tabBodySkin03"></div>
	<script type="text/javascript">
	//<![CDATA[
		$(document).ready(function()
		{
			$("#ajax_display").css('background','#fff');
			$.insert('#tabBodySkin01','<?php echo($cfg['droot']);?>_Admin/modules/displaySkinSet.php?type=<?php echo($_GET['type']);?>&skin=<?php echo($_GET['skin']);?>&position=<?php echo($_GET['position']);?>&sort=<?php echo($_GET['sort']);?>',null,200);
		});
	//]]>
	</script>
</div>
