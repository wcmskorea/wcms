<?php
/**
 * 게시물 검색 입력창
 */
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
$year	= ($_GET['year']) ? $_GET['year'] : date("Y"); //년도
$month	= ($_GET['month']) ? str_pad($_GET['month'], 2, "0", STR_PAD_LEFT) : date("m"); //월
$today	= ($_GET['day']) ? str_pad($_GET['day'], 2, "0", STR_PAD_LEFT) : date("d"); //일
?>
<div id="modal" class="pd1">
<form name="frmBoard" method="get" action="<?php echo($_SERVER['PHP_SELF']);?>"	enctype="multipart/form-data">
<input type="hidden" name="cate" value="<?php echo($cfg['module']['cate']);?>" />
<input type="hidden" id="mode" name="mode" value="<?php echo($_GET['mode']);?>" />

<div class="menu_violet">
	<p title="드래그하여 좌우이동이 가능합니다">날짜별 검색</p>
</div>
<div class="pd10 center">년도와 월을 선택하여 검색합니다</div>
<div class="center"><select name="year" class="bg_gray">
<?php
for($i=date("Y")-1;$i<=date("Y")+1;$i++) 
{
	echo('<option value="'.$i.'"');
	if($i == $year) { echo(' selected="selected" style="color:red;"'); }
	echo('>'.$i.'</option>');
}
?>
</select>년 <select name="month" class="bg_gray">
<?php
for($i=1;$i<=12;$i++) 
{
	echo('<option value="'.str_pad($i, 2, "0", STR_PAD_LEFT).'"');
	if($i == $month) { echo(' selected="selected" style="color:red;"'); }
	echo('>'.str_pad($i, 2, "0", STR_PAD_LEFT).'</option>');
}
?>
</select>월
<?php 
//if($_GET['listType'] != 'week')
//{
	echo('<select name="day" class="bg_gray">');
	for($i=1;$i<=31;$i++) 
	{
		echo('<option value="'.str_pad($i, 2, "0", STR_PAD_LEFT).'"');
		if($i == date("d")) { echo(' selected="selected" style="color:red;"'); }
		echo('>'.str_pad($i, 2, "0", STR_PAD_LEFT).'</option>');
	}
	echo('</select>일');
//}
?>
</div>
<div class="center pd10"><span class="btnPack black medium strong"><button type="submit" class="red"><?php echo($lang['doc']['access']);?></button></span>&nbsp;&nbsp;<span class="btnPack gray medium"><button type="button" onclick="$.dialogRemove()"><?php echo($lang['doc']['cancel']);?></button></span></div>
</form>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function()
{
	if(!document.getElementById('wrap')) { $('#mode').val('content'); }
	$("input[type=text],input[type=password],textarea").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active");});
	$("#ajax_display").css('background','#efefef');
});
//]]>
</script>
