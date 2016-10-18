<?php
/* --------------------------------------------------------------------------------------
| 게시물 검색 입력창
|----------------------------------------------------------------------------------------
| Lastest : 이성준 ( 2009년 6월 16일 화요일 )
*/
if(!defined('__CSS__')) header("HTTP/1.0 404 Not found");

?>
<div id="modal">
	<form name="frmBoard" method="get" action="<?=$_SERVER[PHP_SELF]?>" enctype="multipart/form-data">
		<input type="hidden" name="cate" value="<?=__CATE__?>" />

		<div class="menu_black"><p title="드래그하여 좌우이동이 가능합니다"><?=$lang[search_title]?></p></div>
		<div class="pd10 center">
			<input type="radio" id="sh1" name="sh" value="title" checked="checked" />&nbsp;<label for="sh1"><?=$lang[search_op_subject]?></label>
			<input type="radio" id="sh2" name="sh" value="all" />&nbsp;<label for="sh2"><?=$lang[search_op_both]?></label>
			<input type="radio" id="sh3" name="sh" value="writer" />&nbsp;<label for="sh3"><?=$lang[search_op_writer]?></label>
		</div>
		<div class="center"><input type="text" id="shc" name="shc" title="검색어" class="input_blue center" style="width:200px;" value="" /></div>
		<div class="center pd10"><span class="btnPack black medium strong"><button type="submit" class="red"><?=$lang['board_access']?></button></span>&nbsp;&nbsp;<span class="btnPack gray medium"><button type="button" onclick="$.dialogRemove()"><?=$lang['board_cancel']?></button></span></div>

	</form>
</div>
<script type="text/javascript">
$(document).ready(function()
{
	$("input[type=text],input[type=password],textarea").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active");});
	$("#ajax_display").css('background','#fff');
  	setTimeout ("$('#shc').select()", 500);
});
</script>
