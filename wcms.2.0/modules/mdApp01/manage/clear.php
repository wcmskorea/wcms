<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
?>
<div id="modal">
<div class="menu_violet">
	<p title="드래그하여 좌우이동이 가능합니다">선택 게시물 일괄 삭제</p>
</div>
<div class="pd15 center"><br />총[<strong id="articleCount" style="color: red;">0</strong>]건의 문서(게시물)을 선택하셨습니다.</div>
<div class="center"><span class="btnPack black medium strong"><button type="submit" id="targetSubmit">삭제하기</button></span>&nbsp;&nbsp;<span class="btnPack gray medium"><button type="button" onclick="$.dialogRemove()"><?php echo($lang['doc']['cancel']);?></button></span></div>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function() 
{
	var len = $("input[className=articleCheck]:checked").length;
	if(len < 1) 
	{
		alert('삭제할 게시물을 선택하여 주십시오!');
		$.dialogRemove();
	}
	else 
	{
		$("#articleCount").html(len);
		$("input[type=text],input[type=password],textarea").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active");});
		$("#targetSubmit").click(function(){
			$("#formType").val("<?php echo($sess->encode('articleClear'));?>");
			$("#listForm").attr('action','<?php echo($_SERVER['PHP_SELF']);?>') 
			$("#listForm").attr('method','post'); 
			$("#listForm").attr('target','hdFrame'); 
			$("#listForm").submit();
		});
	}
	$("#ajax_display").css('background-color','#d2d2d2');
});
//]]>
</script>
