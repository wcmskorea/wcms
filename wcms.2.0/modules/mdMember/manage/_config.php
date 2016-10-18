<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_Admin/include/commonHeader.php";
?>
<div class="menu_violet"><p><strong>(회원관리 모듈) 환경설정</strong></p></div>
<div class="tabMenu3">
	<ul class="tabBox">
		<li class="tab" id="tabMember02"><p><a href="#none" onclick="$.tabMenu('tabMember02','#tabBodyMember02','../modules/mdMember/manage/_configLevel.php?cate=<?php echo(__CATE__);?>',null,200)" class="actgray">회원등급 설정</a></p></li>
		<li class="tab" id="tabMember03"><p><a href="#none" onclick="$.tabMenu('tabMember03','#tabBodyMember03','../modules/mdMember/manage/_configForm.php?cate=<?php echo(__CATE__);?>',null,200)" class="actgray">입력항목 설정</a></p></li>
		<li class="tab on" id="tabMember01"><p><a href="#none" onclick="$.tabMenu('tabMember01','#tabBodyMember01','../modules/mdMember/manage/_configBasic.php?cate=<?php echo(__CATE__);?>',null,200)" class="actgray">환경설정</a></p></li>
	</ul>
	<div class="tabBody show" id="tabBodyMember01"></div>
	<div class="tabBody hide" id="tabBodyMember02"></div>
	<div class="tabBody hide" id="tabBodyMember03"></div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function()
{
	$.insert('#tabBodyMember01','../modules/mdMember/manage/_configBasic.php?cate=<?php echo(__CATE__);?>',null,200);
	$("#ajax_display").css('background','#fff');
});
//]]>
</script>
</div>
