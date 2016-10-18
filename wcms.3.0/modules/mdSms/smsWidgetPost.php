<?php
/* ======================================================================================
| SMS(Socket) 문자 발송
*/
require_once "../../_config.php";

# 리퍼러 체크
if(!eregi($_SERVER['HTTP_HOST'],$_SERVER['HTTP_REFERER']))		$func->ajaxMsg("[경고1] 정상적인 접근이 아닙니다.", '', 20);
if($sess->decode($_GET['type']) != "sms^@^Post") 							$func->ajaxMsg("[경고2] 정상적인 접근이 아닙니다.", '', 20);
if($func->checkModule('mdSms') === false)											$func->ajaxMsg("문자 서비스를 이용하고 있지 않습니다.", '', 20);

if($sess->decode($_GET['type']) == "sms^@^Post" && $_GET['mode'] == 'sms')
{
	foreach($_GET AS $key=>$val)
	{
		${$key} = trim(strip_tags($val));
		# GET값 vaild check
		# $func->validCheck(체크할 값, 항목제목, 항목명, 체크타입)
		if($key == "sender")  $func->vaildCheck($val, "연락받을 전화번호", "phone" ,"M", "div"); //$val, $title, $type=null, $must="N", $target=null
		if($key == "mesg")    $func->vaildCheck($val, "상담내용", "trim" ,"M", "div");
	}
	$mesg							= stripslashes($mesg);

	# 상담모듈 연동
	if($func->checkModule("mdApp01"))
	{
		$appCategory = $db->queryFetchOne(" SELECT `cate` FROM `mdApp01__content` ORDER BY cate ASC ");
		$db->Query(" INSERT INTO `mdApp01__content` (cate,division,name,mobile,phone,content,contentAdd,dateReg,info) VALUES ('".$appCategory."','100','".$_GET['mesg']."','".$sender."','".$sender."','".$mesg."','".$contentAdd."','".time()."','".$cfg['timeip']."') ");
	}

	if($_SESSION['ulevel'] != 1)
	{
		# 문자전송
		if($rdoSend == 2) { $sock->date = strtotime($sdate." ".$shour.":".$smin.":00"); }
		$sock->sender			= $sender;
		$socket						= $sock->smsSend($cfg['site']['mobile'], $mesg);
		$socket['msg']		= ($cfg['charset'] == 'euc-kr') ? $socket['msg'] : iconv("CP949", "UTF-8", $socket['msg']);
		$socket['msg']		= ($socket['code'] == '98') ? "상담신청이 정상적으로 접수되었습니다" : $socket['msg'];
	}
	$func->ajaxMsg($socket['msg'], "", 20);

}
else
{
	$func->ajaxMsg("[경고3] 정상적인 접근이 아닙니다.", '', 20);
}
?>
