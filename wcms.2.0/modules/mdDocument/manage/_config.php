<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_Admin/include/commonHeader.php";
?>
<div class="menu_violet"><p><strong><?php echo(__CATE__);?> (문서·게시물 모듈) 환경설정</strong></p></div>
<div class="tabMenu3">
	<ul class="tabBox">
		<li class="tab" id="tabDoc02"><p><a href="javascript:$.tabMenu('tabDoc02','#tabBodyDoc02','../modules/mdDocument/manage/_configForm.php?cate=<?php echo(__CATE__);?>&amp;skin=<?php echo($_GET['skin']);?>',null,200)" class="actgray">2. 입력항목 설정</a></p></li>
		<li class="tab on" id="tabDoc01"><p><a href="javascript:$.tabMenu('tabDoc01','#tabBodyDoc01','../modules/mdDocument/manage/_configBasic.php?cate=<?php echo(__CATE__);?>&amp;skin=<?php echo($_GET['skin']);?>',null,200)" class="actgray">1. 환경설정</a></p></li>
	</ul>
	<div class="tabBody show" id="tabBodyDoc01"></div>
	<div class="tabBody hide" id="tabBodyDoc02"></div>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function()
{
	$.insert('#tabBodyDoc01','../modules/mdDocument/manage/_configBasic.php?cate=<?php echo(__CATE__);?>&skin=<?php echo($_GET['skin']);?>',null,200);
	$("#ajax_display").css('background','#fff');
});
//]]>
</script>
