<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); };

//['0']관리, ['1']접근, ['2']열람권한, ['3']작성(등록)
if($member->checkPerm(3) === false) { $func->err("비밀번호 변경 권한이 없습니다"); }

//리퍼러 체크
if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER'])) { $func->err("['경고']정상적인 접근이 아닙니다."); }
if($_SERVER['REQUEST_METHOD'] == 'GET' ) { $func->err("['경고']정상적인 접근이 아닙니다."); }

//넘어온 값과 변수 동기화 및 validCheck
foreach($_POST AS $key=>$val)
{
	$db->data[$key] = trim($val);

	//$func->validCheck(체크할 값, 항목제목, 항목명, 체크타입, 필수항목
	if($key == "oldpasswd") { $func->vaildCheck($val, "현재 비밀번호", "trim", "M"); }
	if($key == "passwd") 		{ $func->vaildCheck($val, "변경할 비밀번호", "trim", "M"); }
	if($key == "repasswd") 	{ $func->vaildCheck($val, "비밀번호 확인", "trim", "M"); }
}

//비밀번호 체크
if($db->data['passwd'] && $_POST['repasswd'] && $passwd != $repasswd) { $func->err("입력하신 비밀번호가 일치하지 않습니다.", "back"); }
//비밀번호 변수할당
if($db->data['repasswd'])
{
	$db->data['passwd']	= $db->data['repasswd'];
	$db->data['passwdModify'] = time();
}
else
{
	$db->data['passwd']	= $db->data['oldpasswd'];
	$memberInfo = $member->memberInfo($_SESSION['uid']);
	$db->data['passwdModify'] = $memberInfo['passwdModify'];
}

//현재 비밀번호 매칭
if($cfg['site']['encrypt'] == 'crypt')
{
	$Rows = $db->queryFetch(" SELECT * FROM `mdMember__account` WHERE id='".$_SESSION['uid']."'");
	if(crypt($db->data['oldpasswd'], $Rows['passwd']) != $Rows['passwd']) {
		$func->err("비밀번호가 다르거나 일치하는 회원정보가 존재하지 않습니다.", "back");
	}
}
else
{
	$Rows = $db->queryFetch(" SELECT * FROM `mdMember__account` WHERE id='".$_SESSION['uid']."' AND passwd='".$db->passType($cfg['site']['encrypt'], $db->data['oldpasswd'])."'");
	if($db->getNumRows() < 1) { $func->err("현재 비밀번호가 일치하지 않습니다.", "back"); }
}	


//비밀번호 변수할당
$db->data['passwd']		= $db->passType($cfg['site']['encrypt'], $db->data['passwd']);

//시간/IP정보 변수할당
$db->data['info']		= $cfg['timeip'];
	
//회원 비밀번호 변경
$db->query(" UPDATE `mdMember__account` SET passwd='".$db->data['passwd']."', passwdModify='".time()."' WHERE id='".$Rows['id']."' ");
if($db->getAffectedRows() > 0) {
	$func->setLog(__FILE__, "비밀번호 변경 성공");
	$func->err("비밀번호가 정상적으로 변경 되었습니다.", "window.location.replace('./index.php');");
} else 
{
	$func->setLog(__FILE__, "비밀번호 변경 실패");
	$func->err("현재 비밀번호가 일치하지 않거나, 변경된 회원정보가 없습니다.", "back");
}
?>