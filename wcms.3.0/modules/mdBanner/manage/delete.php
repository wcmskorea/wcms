<?php
/**---------------------------------------------------------------------------------------
 * 배너관리 모듈 삭제처리
 *----------------------------------------------------------------------------------------
 * Relationship : mdBanner/manage/_controll.php
 */
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
usleep($cfg[sleep]);

if($_GET[type] == "delete") {
	$Rows = $db->queryFetch(" SELECT * FROM `mdBanner__content` WHERE seq='".$_GET[idx]."' AND position='".$_GET[position]."' ");
	if($Rows[seq]) {
		@unlink($cfg['upload'][dir].date("Y",$Rows[date])."/".date("m",$Rows[date])."/".$Rows[filename]);
		$db->query(" DELETE FROM `mdBanner__content` WHERE seq='".$Rows[seq]."' AND position='".$Rows[position]."' ");
		$db->query(" UPDATE `mdBanner__content` SET seq=seq-1 WHERE seq>'".$Rows[seq]."' AND position='".$Rows[position]."' ");
		$db->query(" OPTIMIZE TABLES `mdBanner__content` ");
		$func->ajaxMsg("정상적으로 삭제되었습니다.", "$.insert('#left_mdBanner','../modules/mdBanner/manage/_left.php','20'); $.insert('#module', '../modules/mdBanner/manage/_controll.php?type=list&skin=".$_GET['skin']."&position=".$_GET['position']."',null,300);$.dialogRemove();", 20);
	} else {
		$func->ajaxMsg("선택된 데이터가 없습니다.", "$.dialogRemove();", 20);
	}
} else {
	$func->ajaxMsg("잘못된 경로로 접속하셨습니다.", "$.dialogRemove();", 20);
}
?>
