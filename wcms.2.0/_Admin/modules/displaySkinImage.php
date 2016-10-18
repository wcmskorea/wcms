<?php
require_once "../../_config.php";
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

	//첨부파일 처리
	require_once __PATH__."_Lib/classUpLoad.php";
	$up = new upLoad($cfg['upload']['dir'], $_FILES);
	$up->count = 5;
	$up->dir = __PATH__."_Site/".__DB__."/".$skin."/image/background/";

	//기본형 배경
	if(is_uploaded_file($_FILES['upfile']['tmp_name']))
	{
		$up->upFiles("bg_main_layout");
	}
	//기본형 타이틀
	if(is_uploaded_file($_FILES['upfile1']['tmp_name']))
	{
		$up->upFiles("bg_main_container");
	}
	//탭 메뉴 배경
	if(is_uploaded_file($_FILES['upfile2']['tmp_name']))
	{
		$up->upFiles("bg_sub_layout");
	}
	//탭 메뉴
	if(is_uploaded_file($_FILES['upfile3']['tmp_name']))
	{
		$up->upFiles("bg_sub_container");
	}
	//탭 메뉴(over)
	if(is_uploaded_file($_FILES['upfile4']['tmp_name']))
	{
		$up->upFiles("bg_footer");
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
else
{
	$sort = ($sort) ? $sort : $db->queryFetchOne(" SELECT MAX(sort) FROM `display__".$_GET['skin']."` WHERE position='".$_GET['position']."' ") + 1;
}
?>
<p class="pd10"><strong>파일위치 및 이름구성</strong> : <?php echo($cfg['droot']);?>{레이아웃폴더}/image/{타입별 폴더}/{파일명}</p>
<table class="table_list" style="width:100%;">
	<caption></caption>
	<col width="140">
	<col>
	<thead>
	<tr>
		<th class="first"><p class="center">항 목</p></th>
		<th><span class="normal">(예)</span>&nbsp;카테고리 코드가 '001001'이고 파일 확장자가 'jpg'일때...</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<th><ol><li class="opt">스킨(공통노출)</li></ol></th>
		<td><ol><li class="opt"><?php echo($cfg['droot']);?><span class="orange"><?php echo($_GET['skin']);?></span>/image/skin_<?php echo(strtolower($_GET['position']));?><span class="orange">1</span>.jpg</li></ol></td>
	</tr>
	<tr>
		<th><ol><li class="opt">스킨(지역노출)</li></ol></th>
		<td><ol><li class="opt"><?php echo($cfg['droot']);?><span class="orange"><?php echo($_GET['skin']);?></span>/image/skin_<?php echo(strtolower($_GET['position']));?><span class="orange">001</span>.jpg</li></ol></td>
	</tr>
	<tr>
		<th><ol><li class="opt">스킨(개별노출)</li></ol></th>
		<td><ol><li class="opt"><?php echo($cfg['droot']);?><span class="orange"><?php echo($_GET['skin']);?></span>/image/skin_<?php echo(strtolower($_GET['position']));?><span class="orange">001001</span>.jpg</li></ol></td>
	</tr>
	</tbody>
</table>
<br />
<form name="frmSkin1" method="post" enctype="multipart/form-data" action="<?php echo($_SERVER['PHP_SELF']);?>" target="hdFrame">
<input type="hidden" name="type" value="displayPost" />
<input type="hidden" name="skin" value="<?php echo($_GET['skin']);?>" />
<input type="hidden" name="position" value="<?php echo($_GET['position']);?>" />
<input type="hidden" name="sort" value="<?php echo($sort);?>" />
<table class="table_list" style="width:100%;">
	<caption></caption>
	<col width="140">
	<col>
	<col width="220">
	<thead>
	<tr>
		<th class="first"><p class="center">항 목</p></th>
		<th><p class="center">기타 배경 이미지 설정</p></th>
		<th><p class="center">파 일</p></th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<th><ol><li class="opt">메인 : Layout</li></ol></th>
		<td><ol><li class="opt"><span class="colorOrange noblock">background</span>/bg_main_layout.jpg</li></ol></td>
		<td><ol><li class="opt"><input type="file" name="upfile" class="input_gray" style="width:200px; height:20px;" /></li></ol></td>
	</tr>
	<tr>
		<th><ol><li class="opt">메인 : Container</li></ol></th>
		<td><ol><li class="opt"><span class="colorOrange noblock">background</span>/bg_main_container.jpg</li></ol></td>
		<td><ol><li class="opt"><input type="file" name="upfile1" class="input_gray" style="width:200px; height:20px;" /></li></ol></td>
	</tr>
	<tr>
		<th><ol><li class="opt">서브 : Layout</li></ol></th>
		<td><ol><li class="opt"><span class="colorOrange noblock">background</span>/bg_sub_layout.jpg</li></ol></td>
		<td><ol><li class="opt"><input type="file" name="upfile2" class="input_gray" style="width:200px; height:20px;" /></li></ol></td>
	</tr>
	<tr>
		<th><ol><li class="opt">서브 : Container</li></ol></th>
		<td><ol><li class="opt"><span class="colorOrange noblock">background</span>/bg_sub_container.jpg</li></ol></td>
		<td><ol><li class="opt"><input type="file" name="upfile3" class="input_gray" style="width:200px; height:20px;" /></li></ol></td>
	</tr>
	<tr>
		<th><ol><li class="opt">하단 : Footer</li></ol></th>
		<td><ol><li class="opt"><span class="colorOrange noblock">background</span>/bg_footer.jpg</li></ol></td>
		<td><ol><li class="opt"><input type="file" name="upfile4" class="input_gray" style="width:200px; height:20px;" /></li></ol></td>
	</tr>
	</tbody>
</table>
<div class="pd5 right"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit">파일 업로드</button></span></div>
</form>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    $("input[type=file]").change(function(){
			var IMG_FORMAT = "\.(gif|jpg|jpge|swf|png)$";
			if((new RegExp(IMG_FORMAT, "i")).test(this.value)) return true;
			alert("이미지 파일만 첨부하실 수 있습니다.");
            this.select();
            document.selection.clear();
	});
});
//]]>
</script>
