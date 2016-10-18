<?php
require_once "../../_config.php";
require_once "../include/commonHeader.php";

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
	$up->count = 2;
	
	//기본형 배경
	if(is_uploaded_file($_FILES['upfile']['tmp_name']))
	{
		$up->dir = __PATH__."_Site/".__DB__."/".$skin."/image/background/";
		$up->thumbIsFix = 'F'; //원본사이즈 그대로
		$up->upFiles("bg_localNavi02");
	}
	//기본형 타이틀
	if(is_uploaded_file($_FILES['upfile1']['tmp_name']))
	{
		$up->dir = __PATH__."_Site/".__DB__."/".$skin."/image/background/";
		$up->thumbIsFix = 'F'; //원본사이즈 그대로
		$up->upFiles("bg_localSubBox");
	}
	
	//안내문구 및 페이지 이동
	if(!preg_match('/\/_Admin\//', $_SERVER['HTTP_REFERER']))
	{
		$func->errCfm("Display(Navigation) 이미지타입 이미지 정보가 정상적으로 적용되었습니다.", "parent.$.dialogRemove();".PHP_EOL."parent.$.insert('#skinSelector', '".$cfg['droot']."_Admin/modules/displayList.php?type=displayList&mode=design&skin=".$_POST['skin']."&position=".$_POST['position']."');");
	} 
	else
	{
		$func->err("Display(Navigation) 이미지타입 이미지 정보가 정상적으로 적용되었습니다.", "parent.$.dialogRemove();".PHP_EOL."parent.$.insert('#tabBody".$_POST['position']."', './modules/displayList.php?type=displayList&skin=".$_POST['skin']."&position=".$_POST['position']."',null,300);");
	}
}
else
{
	$sort = ($sort) ? $sort : $db->queryFetchOne(" SELECT MAX(sort) FROM `display__".$_GET['skin']."` WHERE position='".$_GET['position']."' ") + 1;
}
?>
<form name="frmSkin1" method="post" enctype="multipart/form-data" action="<?php echo($_SERVER['PHP_SELF']);?>" target="hdFrame">
<input type="hidden" name="type" value="displayPost" />
<input type="hidden" name="skin" value="<?php echo($_GET['skin']);?>" />
<input type="hidden" name="position" value="<?php echo($_GET['position']);?>" />
<input type="hidden" name="sort" value="<?php echo($sort);?>" />
<input type="hidden" name="form" value="skin" />

<p class="pd10"><strong>파일위치 및 이름구성</strong> : <?php echo($cfg['droot']);?>{레이아웃폴더}/image/{타입별 폴더}/{파일명}</p>
<table class="table_list" style="width:100%;">
	<caption></caption>
	<col width="80">
	<col width="110">
	<col>
	<col width="220">
	<thead>
	<tr>
		<th class="first"><p class="center">형태</p></th>
		<th><p class="center">항 목</p></th>
		<th><span class="normal">(예)&nbsp;</span>위치('<?php echo($_GET['position']);?>')<span class="normal">&nbsp;>&nbsp;</span>노출순서  '<?php echo($sort);?>'<span class="normal">&nbsp;>&nbsp;</span>확장자 'gif'</th>
		<th><p class="center">파 일</p></th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<th scope="row" rowspan="3"><p>이미지 타입<br />(1차메뉴)</p></th>
		<th><ol><li class="opt">BOX배경</li></ol></th>
		<td><ol><li class="opt"><span class="colorOrange noblock">background</span>/bg_localNavi02.gif</li></ol></td>
		<td><ol><li class="opt"><input type="file" name="upfile" class="input_gray" style="width:200px; height:20px;" /></li></ol></td>
	</tr>
	<tr>
		<th><ol><li class="opt">이미지 메뉴(OFF)</li></ol></th>
		<td><ol><li class="opt"><span class="colorOrange noblock">menu</span>/lnb<span class="colorOrange">001</span>_off.gif</li></ol></td>
		<td><ol><li class="opt">카테고리 코드별 FTP업로드</li></ol></td>
	</tr>
	<tr>
		<th><ol><li class="opt">이미지 메뉴(ON)</li></ol></th>
		<td><ol><li class="opt"><span class="colorOrange noblock">menu</span>/lnb<span class="colorOrange">001</span>_on.gif</li></ol></td>
		<td><ol><li class="opt">카테고리 코드별 FTP업로드</li></ol></td>
	</tr>
	<tr>
		<th scope="row"><p>텍스트 타입<br />(2차메뉴)</p></th>
		<th><ol><li class="opt">BOX배경</li></ol></th>
		<td><ol><li class="opt"><span class="colorOrange noblock">background</span>/bg_localSubBox.gif</li></ol></td>
		<td><ol><li class="opt"><input type="file" name="upfile1" class="input_gray" style="width:200px; height:20px;" /></li></ol></td>
	</tr>
	</tbody>
</table>
<div class="pd5 right"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit">파일 업로드</button></span></div>
</form>
<?php 
require_once __PATH__."/_Admin/include/commonScript.php";
?>
