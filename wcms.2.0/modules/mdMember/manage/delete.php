<?php
/*
 | 회원정보 삭제
 | Relationship : ./modules/mdMember/_controll.php
 | Last (2009.2.01 : 이성준)
 */
# 리퍼러 체크
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
usleep($cfg['sleep']);
$idx = ($_POST['idx']) ? $_POST['idx'] : $_GET['idx'];

if(!$func->vaildCheck($idx, "회원코드", "M")) { $func->ajaxMsg("회원코드는 필수 항목입니다.", null, 20); }
$Rows = $db->queryFetch(" SELECT * FROM `mdMember__account` LEFT JOIN `mdMember__info` ON mdMember__account.id=mdMember__info.id WHERE mdMember__account.seq='".$idx."' ");

if($_GET['type'] == "basic") {

	#--- 일반회원 전환
	$db->query(" UPDATE `mdMember__account` SET level='".$member->memberBasic()."' WHERE id='".$Rows['id']."' ");
	$func->setLog(__FILE__, "회원(".$Rows['id'].") 대기상태(일반회원) 전환 성공");
	$func->ajaxMsg("회원(".$Rows['id'].") 대기상태(일반회원) 전환되었습니다.", "$.insert('#left_mdMember','../modules/mdMember/manage/_left.php',null,50); $.insert('#module', '../modules/mdMember/manage/_controll.php?lev=".$member->memberBasic()."&type=list',null,300); $.dialogRemove();", 200);

} else if($_GET['type'] == "delete") {

	#--- 탈퇴처리
	$db->query(" UPDATE `mdMember__account` SET level='0' WHERE id='".$Rows['id']."' ");
	$func->setLog(__FILE__, "회원(".$Rows['id'].") 탈퇴처리 성공");
	$func->ajaxMsg("회원(".$Rows['id'].") 탈퇴처리 전환되었습니다.", "$.insert('#left_mdMember','../modules/mdMember/manage/_left.php',null,50); $.insert('#module', '../modules/mdMember/manage/_controll.php?lev=ex&type=list',null,300); $.dialogRemove();", 200);

} else if($_GET['type'] == "delComplete") {

	$db->query(" DELETE FROM `mdMember__account` WHERE id='".$Rows['id']."' ");
	$db->query(" DELETE FROM `mdMember__info` WHERE id='".$Rows['id']."' ");
	$db->query(" OPTIMIZE TABLE `mdMember__account`,`mdMember__info` ");
	#--- 적립정보 삭제
	if($func->checkModule('mdMileage'))
		$db->query(" DELETE FROM `mdMileage__mileage` WHERE memberSeq='".$Rows['seq']."' ");
	//	$db->query(" DELETE FROM `mdPayment__moneys` WHERE id='".$Rows['id']."' ");
	//	$db->query(" DELETE FROM `mdPayment__billings` WHERE id='".$Rows['id']."' ");
	//	$db->query(" OPTIMIZE TABLE `mdMileage__mileage`,`mdPayment__moneys`,`mdPayment__billings` ");
		$db->query(" OPTIMIZE TABLE `mdMileage__mileage` ");

	$func->setLog(__FILE__, "회원 (".$Rows['id'].")영구삭제 성공");
	$func->ajaxMsg("회원(".$Rows['id'].") 영구삭제 되었습니다.", "$.insert('#left_mdMember','../modules/mdMember/manage/_left.php',null,50); $.insert('#module', '../modules/mdMember/manage/_controll.php?type=list',null,300); $.dialogRemove();", 200);

} else {

	$func->ajaxMsg("잘못된 접속정보 입니다","");

}
?>
