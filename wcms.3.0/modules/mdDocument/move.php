<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
?>
<div id="modal">
<div class="menu_violet">
	<p title="드래그하여 좌우이동이 가능합니다">선택 게시물 카테고리 이동</p>
</div>
<div class="pd15 center">총[<strong id="articleCount" style="color:red;">0</strong>]건의 게시물을 선택하셨습니다.</div>
<div class="center"><select name="move" style="width:250px;">
<?php
$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__` WHERE skin='".$cfg['skin']."' AND listUnion='N' ORDER BY cate ASC ");
while($sRows = $db->fetch())
{
	$open = ($sRows[hidden] == 'Y') ? "비공개" : "공　개";
	//$name = $func->getCateName($sRows[cate]);
	$cate = $category->getCategoryInfo($sRows[cate],$sRows[skin]);
	$name = $cate['name'];
	if($name)
	{
		echo('<option value="'.$sRows[cate].'"');
		if($cfg['module']['cate'] == $sRows[cate]) { echo(' selected="selected" class="red"'); }
		echo('>['.$open.']&nbsp;&nbsp;'.$name.'&nbsp;('.$sRows[cate].')</option>');
	}
}
?>
</select></div>
<div class="center pd10"><span class="btnPack black medium strong"><button type="submit" id="targetSubmit">이동하기</button></span>&nbsp;&nbsp;<span class="btnPack gray medium"><button type="button" onclick="$.dialogRemove()"><?php echo($lang['doc']['cancel']);?></button></span></div>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	var len = $("input[class=articleCheck]:checked").length;
	if(len < 1)
	{
		alert('이동할 게시물을 선택하여 주십시오!');
		$.dialogRemove();
	}
	else
	{
		$("#articleCount").html(len);
		$("input[type=text],input[type=password],textarea").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active");});
		$("#targetSubmit").click(function(){
			var move = $("select[name=move]").val();
			$("#formType").val("<?php echo($sess->encode('articleMove'));?>");
			$("#moveCate").val(move);
			$("#listForm").submit();
		});
	}
	$("#ajax_display").css('background-color','#d2d2d2');
});
//]]>
</script>
