<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_config.php";
require_once __PATH__."/_Admin/include/commonHeader.php";
?>
<div class="menu_violet"><p title="드래그하여 이동하실 수 있습니다"><strong>문자·SMS 모듈</strong> ( 레이아웃:<?php echo($_GET['skin']);?> / 위치:<?php echo($_GET['position']);?> )</p></div>
<div class="tabMenu3">
	<ul class="tabBox">
		<?php
		if(__CATE__)
		{
			echo('<li class="tab" id="tabDoc04"><p><a href="javascript:$.tabMenu(\'tabDoc04\',\'#tabBodyDoc04\',\'../modules/mdSms/manage/_configBasic.php?cate='.__CATE__.'\',null,200)" class="actgray">게시판 환경설정</a></p></li>');
		}
		?>
		<li class="tab" id="tabDoc03"><p><a href="javascript:new_window('<?php echo($cfg['droot']);?>_Admin/wftp/ftp.php?dir=<?php echo($cfg['skin']);?>/image','wftp',800,600,'no','yes');" class="actgray">WEB·FTP 열기</a></p></li>
		<li class="tab" id="tabDoc02"><p><a href="javascript:$.tabMenu('tabDoc02','#tabBodyDoc02','<?php echo($cfg['droot']);?>modules/mdSms/manage/_displayImage.php?type=<?php echo($_GET['type']);?>&skin=<?php echo($_GET['skin']);?>&position=<?php echo($_GET['position']);?>&sort=<?php echo($_GET['sort']);?>',null,200)" class="actgray">2. 스킨 이미지 정보</a></p></li>
		<li class="tab on" id="tabDoc01"><p><a href="javascript:$.tabMenu('tabDoc01','#tabBodyDoc01','<?php echo($cfg['droot']);?>modules/mdSms/manage/_displaySet.php?type=<?php echo($_GET['type']);?>&skin=<?php echo($_GET['skin']);?>&position=<?php echo($_GET['position']);?>&sort=<?php echo($_GET['sort']);?>',null,200)" class="actgray">1.  환경설정</a></p></li>
	</ul>
	<div class="clear"></div>
	<div class="tabBody show" id="tabBodyDoc01"></div>
	<div class="tabBody hide" id="tabBodyDoc02"></div>
	<div class="tabBody hide" id="tabBodyDoc03"></div>
	<?php
	if(__CATE__)
	{
		echo('<div class="tabBody hide" id="tabBodyDoc04"></div>');
	}
	?>
	<script type="text/javascript">
	//<![CDATA[
		$(document).ready(function()
		{
			$("#ajax_display").css('background','#fff');
			$.insert('#tabBodyDoc01','../modules/mdSms/manage/_displaySet.php?type=<?php echo($_GET['type']);?>&skin=<?php echo($_GET['skin']);?>&position=<?php echo($_GET['position']);?>&sort=<?php echo($_GET['sort']);?>',null,200);
		});
	//]]>
	</script>
</div>
