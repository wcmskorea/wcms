<?php
/*---------------------------------------------------------------------------------------
| 카테고리 Display
|----------------------------------------------------------------------------------------
| Relationship : mdPopup/manage/_controll.php
| Last (2008.10.04 : 이성준)
*/
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
usleep($cfg[sleep]);

if($_GET[type] == "delete") {
  $Rows = $db->queryFetch(" SELECT * FROM `mdPopup__content` WHERE seq='".$_GET[idx]."' ");
  if($Rows[seq]) {
    $db->query(" DELETE FROM `mdPopup__content` WHERE seq = '".$_GET[idx]."' ");
    $db->query(" OPTIMIZE TABLES `mdPopup__content` ");
    $func->ajaxMsg("팝업정보가 정상적으로 삭제되었습니다.", "$.insert('#module', '../modules/mdPopup/manage/_controll.php?type=list&skin=".$_GET['skin']."&pos=".$_GET['pos']."',null,300);$.dialogRemove();", 20);
  } else {
    $func->ajaxMsg("선택된 데이터가 없습니다.", "$.dialogRemove();", 20);
  }
} else {
	$func->ajaxMsg("잘못된 경로로 접속하셨습니다.", "$.dialogRemove();", 20);
}
?>
