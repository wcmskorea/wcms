<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }

if($_POST['type'] == "displayClonePost")
{
	$func->checkRefer("POST");

//	넘어온 값과 변수 동기화 및 validCheck
	foreach($_POST AS $key=>$val)
	{
		${$key} = trim($val);
		#--- $func->validCheck(체크할 값, 항목제목, 항목명, 체크타입)
		if($key == "cloneSort") { $func->vaildCheck($val, "복사할 디스플레이 설정", $key, "num" ,"M"); }
	}


	//디스플레이 설정 복제 시작
	$Row = $db->queryFetch(" SELECT * FROM `display__".$skin."` WHERE position='".$position."' AND sort = '".$cloneSort."' limit 1 ");

	$db->data['sort']      = $sort;
	$db->data['position']  = $position;
	$db->data['cate']      = $Row['cate'];
	$db->data['name']      = $Row['name'].'(복사)';
	$db->data['form']      = $Row['form'];
	$db->data['listing']   = $Row['listing'];
	$db->data['useHidden'] = $Row['useHidden'];
	$db->data['config']    = $Row['config'];

	if($db->sqlInsert("display__".$skin, "REPLACE", 0) > 0)
	{
		if(!preg_match('/\/_Admin\//', $_SERVER['HTTP_REFERER']))
		{
			$func->err("Display 유닛이 정상적으로 복사되었습니다.", "parent.$.dialogRemove();".PHP_EOL."parent.$.insert('#skinSelector', '".$cfg['droot']."_Admin/modules/displayList.php?type=displayList&mode=design&skin=".$_POST['skin']."&position=".$_POST['position']."',null,300)", 20); 
		}
		else
		{
			$func->err("Display 유닛이 정상적으로 복사되었습니다.", "parent.$.dialogRemove();".PHP_EOL."parent.$.insert('#tabBody".$_POST['position']."', './modules/displayList.php?type=displayList&skin=".$_POST['skin']."&position=".$_POST['position']."&mode=".$_POST['mode']."',null,300)", 20); 
		}
	}
	else
	{
		$func->err("[".$Row['name']."]디스플레이 설정 복제 실패입니다.");
	}
}
else
{
	$sort = $db->queryFetchOne(" SELECT MAX(sort) FROM `display__".$_GET['skin']."` WHERE position='".$_GET['position']."' ") + 1;
}
?>
<form name="frmCate" method="post" enctype="multipart/form-data" action="<?php echo($_SERVER['PHP_SELF']);?>" target="hdFrame">
<input type="hidden" name="type" value="displayClonePost" />
<input type="hidden" name="sort" value="<?php echo($sort);?>" />
<input type="hidden" name="cloneSort" value="<?php echo($_GET['cloneSort']);?>" />
<input type="hidden" name="skin" value="<?php echo($_GET['skin']);?>" />
<input type="hidden" name="position" value="<?php echo($_GET['position']);?>" />
<div id="modal">
	<div class="menu_violet">
		<p title="드래그하여 좌우이동이 가능합니다">디스플레이 설정 복사</p>
	</div>
	<div class="pd15 center"><br />해당 디스플레이 설정을 복사하시겠습니까?</div>
	<div class="center"><span class="btnPack black medium strong"><button type="submit">복사하기</button></span>&nbsp;&nbsp;<span class="btnPack gray medium"><button type="button" onclick="$.dialogRemove()"><?php echo($lang['doc']['cancel']);?></button></span></div>
</div>
</form>
<?php
require_once "../../_Admin/include/commonScript.php";
?>
