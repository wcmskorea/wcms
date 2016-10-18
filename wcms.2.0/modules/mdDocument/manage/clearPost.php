<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
@ob_end_clean();


//리퍼러 체크
$func->checkRefer("POST");

//파라메터 검사
if(count($_POST['choice']) < 1) { $func->err($lang['doc']['notmust'], "window.history.back()"); }

//해당정보 삭제
$DeleteData = 0; //삭제 건수

foreach($_POST['choice'] AS $key=>$value)
{
	$func->setLog(__FILE__, "게시물 (".$value.") 삭제");

	//게시물 삭제 진행
	$db->query(' DELETE FROM `mdDocument__content'.$prefix.'` WHERE seq="'.$value.'" ');

	$DeleteData++;

}

// 테이블 최적화
if($DeleteData > 0)
{
	$db->query(" UPDATE `mdDocument__` SET articled='0',articleTrashed='0' WHERE 1 "); //리셋
	$db->query(" SELECT cate, COUNT(*) AS articled, SUM(if(idxTrash>'0','1','0')) AS trashed FROM `mdDocument__content".$prefix."` GROUP BY cate ");
	while($Rows = $db->fetch())
	{
		$query = " UPDATE `mdDocument__` SET articled='".$Rows['articled']."',articleTrashed='".$Rows['trashed']."' WHERE cate='".$Rows['cate']."' ";//IN (cate,share) ";
		$db->query($query);
	}
}

//알림창
$func->err("정상적으로 삭제 되었습니다.", "
parent.$.insert('#left_mdDocument','../modules/mdDocument/manage/_left.php');
parent.$.insert('#module', '../modules/mdDocument/manage/_controll.php', '&type=list&sh=".$_POST['sh']."&shc=".$_POST['shc']."',300);
parent.$.dialogRemove();
");
?>