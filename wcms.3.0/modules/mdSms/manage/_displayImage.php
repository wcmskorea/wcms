<?php
require_once "../../../_config.php";
$func->checkRefer();

//디스플레이 등록
if($_POST['type'] == "displayPost")
{
	$func->checkRefer("POST");

	foreach($_POST AS $key=>$val)
	{
		${$key} = trim($val);
	}

	$position = strtolower($position);
	$baseDir = __PATH__."_Site/".__DB__."/".$skin."/image";

	//기본형 배경
	if(is_uploaded_file($_FILES['upfile']['tmp_name']))
	{
		$extName = array_reverse(explode(".", strtolower($_FILES['upfile']['name'])));
		move_uploaded_file($_FILES['upfile']['tmp_name'], $baseDir."/background/bg_recent_".$position.$sort.".".$extName[0]);
	}

	//기본형 타이틀
	if(is_uploaded_file($_FILES['upfile2']['tmp_name']))
	{
		$extName = array_reverse(explode(".", strtolower($_FILES['upfile2']['name'])));
		move_uploaded_file($_FILES['upfile2']['tmp_name'], $baseDir."/title/recent_".$position.$sort.".".$extName[0]);
	}

	$sql	= " SELECT * FROM `display__".$_POST['skin']."` WHERE position='".$_POST['position']."' AND form like 'TAB%' ORDER BY sort ASC ";
	$rst 	= @mysql_query($sql);
	while($Rows = @mysql_fetch_array($rst))
	{
		if(is_uploaded_file($_FILES['upfileTab1_'.$Rows['cate']]['tmp_name']))
		{
			$extName = array_reverse(explode(".", strtolower($_FILES['upfileTab1_'.$Rows['cate']]['name'])));
			move_uploaded_file($_FILES['upfileTab1_'.$Rows['cate']]['tmp_name'], $baseDir."/menu/recentTab_".$Rows['cate'].".".$extName[0]);
		}
		if(is_uploaded_file($_FILES['upfileTab2_'.$Rows['cate']]['tmp_name']))
		{
			$extName = array_reverse(explode(".", strtolower($_FILES['upfileTab2_'.$Rows['cate']]['name'])));
			move_uploaded_file($_FILES['upfileTab2_'.$Rows['cate']]['tmp_name'], $baseDir."/menu/recentTab_over_".$Rows['cate'].".".$extName[0]);
		}
	}

	//기본형 타이틀
	if(is_uploaded_file($_FILES['upfileTab3']['tmp_name']))
	{
		$extName = array_reverse(explode(".", strtolower($_FILES['upfileTab3']['name'])));
		move_uploaded_file($_FILES['upfileTab3']['tmp_name'], $baseDir."/button/recentTabMore_".$position.$sort.".".$extName[0]);
	}

	//안내문구 및 페이지 이동
	if(!preg_match('/\/_Admin\//', $_SERVER['HTTP_REFERER']))
	{
		$func->errCfm("Display(Skin) 이미지 정보가 정상적으로 적용되었습니다.", "parent.$.dialogRemove();".PHP_EOL."parent.$.insert('#skinSelector', '".$cfg['droot']."_Admin/modules/displayList.php?type=displayList&mode=design&skin=".$_POST['skin']."&position=".$_POST['position']."');");
	}
	else
	{
		$func->err("Display(Skin) 이미지 정보가 정상적으로 적용되었습니다.", "parent.$.dialogRemove();".PHP_EOL."parent.$.insert('#tabBody".$_POST['position']."', './modules/displayList.php?type=displayList&skin=".$_POST['skin']."&position=".$_POST['position']."',null,300);");
	}
}
?>
<form name="frmCate" method="post" enctype="multipart/form-data" action="<?php echo($_SERVER['PHP_SELF']);?>" target="hdFrame">
<input type="hidden" name="type" value="displayPost" />
<input type="hidden" name="skin" value="<?php echo($_GET['skin']);?>" />
<input type="hidden" name="position" value="<?php echo($_GET['position']);?>" />
<input type="hidden" name="sort" value="<?php echo($_GET['sort']);?>" />

<p class="pd10"><strong>파일위치 및 이름구성</strong> : <?php echo($cfg['droot']);?>{레이아웃폴더}/image/{타입별 폴더}/{파일명}</p>
<table class="table_list" style="width:100%;">
	<caption></caption>
	<col width="140">
	<col>
	<col width="220">
	<thead>
	<tr>
		<th class="first"><p class="center">항 목</p></th>
		<th><span class="normal">(예)&nbsp;</span>위치('<?php echo($_GET['position']);?>')<span class="normal">&nbsp;>&nbsp;</span>노출순서  '<?php echo($_GET['sort']);?>'<span class="normal">&nbsp;>&nbsp;</span>확장자 'gif'</th>
		<th><p class="center">파 일</p></th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<th><ol><li class="opt">기본형 배경</li></ol></th>
		<td><ol><li class="opt"><span class="colorOrange noblock">background</span>/bg_recent_<span class="colorOrange noblock"><?php echo(strtolower($_GET['position']));?><?php echo($_GET['sort']);?></span>.gif</li></ol></td>
		<td><ol><li class="opt"><input type="file" name="upfile" class="input_gray" style="width:200px; height:20px;" /></li></ol></td>
	</tr>
	<tr>
		<th><ol><li class="opt">기본형 타이틀</li></ol></th>
		<td><ol><li class="opt"><span class="colorOrange noblock">title</span>/recent_<span class="colorOrange noblock"><?php echo(strtolower($_GET['position']));?><?php echo($_GET['sort']);?></span>.gif (swf,jpg,png,gif)</li></ol></td>
		<td><ol><li class="opt"><input type="file" name="upfile2" class="input_gray" style="width:200px; height:20px;" /></li></ol></td>
	</tr>
	<?php
	$sql	= " SELECT * FROM `display__".$_GET['skin']."` WHERE position='".$_GET['position']."' AND form like 'TAB%' ORDER BY sort ASC ";
	$rst 	= @mysql_query($sql);
	while($Rows = @mysql_fetch_array($rst))
	{
	?>
	<tr>
		<th><ol><li class="opt">TAB 메뉴</li></ol></th>
		<td><ol><li class="opt"><span class="colorOrange noblock">menu</span>/recentTab_<span class="colorOrange noblock"><?php echo($Rows['cate']);?></span>.gif (jpg,png,gif)</li></ol></td>
		<td><ol><li class="opt"><input type="file" name="upfileTab1_<?php echo($Rows['cate']);?>" class="input_gray" style="width:200px; height:20px;" /></li></ol></td>
	</tr>
	<tr>
		<th><ol><li class="opt">TAB 메뉴(over)</li></ol></th>
		<td><ol><li class="opt"><span class="colorOrange noblock">menu</span>/recentTab_over_<span class="colorOrange noblock"><?php echo($Rows['cate']);?></span>.gif (jpg,png,gif)</li></ol></td>
		<td><ol><li class="opt"><input type="file" name="upfileTab2_<?php echo($Rows['cate']);?>" class="input_gray" style="width:200px; height:20px;" /></li></ol></td>
	</tr>
	<?php } ?>
	<tr>
		<th><ol><li class="opt">TAB 메뉴(more)</li></ol></th>
		<td><ol><li class="opt"><span class="colorOrange noblock">button</span>/recentTabMore_<span class="colorOrange noblock"><?php echo(strtolower($_GET['position']));?><?php echo($_GET['sort']);?></span>.gif</li></ol></td>
		<td><ol><li class="opt"><input type="file" name="upfileTab3" class="input_gray" style="width:200px; height:20px;" /></li></ol></td>
	</tr>
	</tbody>
</table>
<div class="pd5 right"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit">파일 업로드</button></span></div>
</form>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$("input[type=text],input[type=password],textarea").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active");});
});
//]]>
</script>
