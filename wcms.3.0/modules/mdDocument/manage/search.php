<?php
/**--------------------------------------------------------------------------------------
 * 회원검색 입력창
 *---------------------------------------------------------------------------------------
 * Lastest (2008.10.22 : 이성준)
 */
require_once "../../../_config.php";
require_once __PATH__."/_Admin/include/commonHeader.php";
?>
<div id="modal">
<form name="search1" method="post" action="" enctype="multipart/form-data" onsubmit="return $.checkFarm(this, '../modules/mdDocument/manage/_controll.php','insert','#module');">
	<div class="menu_violet"><p>문 서 검 색</p></div>
	<div class="pd10 center">
		<input type="radio" id="sh1" name="sh" value="subject" checked="checked" /><label for="sh1">제목</label>
		<input type="radio" id="sh2" name="sh" value="content" /><label for="sh2">제목+내용</label>
		<input type="radio" id="sh3" name="sh" value="userid" /><label for="sh3">아이디</label>
		<input type="radio" id="sh4" name="sh" value="writer" /><label for="sh4">작성자</label>
		<input type="radio" id="sh5" name="sh" value="userip" /><label for="sh5">아이피</label>
	</div>
	<div class="center"><span><input type="text" id="shc" name="shc" title="검색어" class="input_gray center" style="width:200px;" value="" /></span>&nbsp;<span class="btnPack violet small"><button type="submit">검색</button></span>
	</div>
	<div id="posit"></div>
	</form>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function() {
	$("input[type=text],input[type=password],textarea").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active");});
	$("#ajax_display").css('background','#fff');
	$('#shc').focus().toggleClass("input_active").select();
});
//]]>
</script>
