<?php
/* ======================================================================================
| SMS(Socket) 문자 발송
*/
require_once "../../_config.php";

# 리퍼러 체크
if(!eregi($_SERVER['HTTP_HOST'],$_SERVER['HTTP_REFERER']))		$func->ajaxMsg("[경고1] 정상적인 접근이 아닙니다.", '', 20);
if($sess->decode($_POST['type']) != "sms^@^Post") 						$func->ajaxMsg("[경고2] 정상적인 접근이 아닙니다.", '', 20);
if($func->checkModule('mdSms') === false)											$func->ajaxMsg("문자 서비스를 이용하고 있지 않습니다.", '', 20);

if($sess->decode($_POST['type']) == "sms^@^Post" && $_POST['mode'] == 'smsSimple')
{
	foreach($_POST AS $key=>$val)
	{
		${$key} = trim(strip_tags($val));
		# GET값 vaild check
		# $func->validCheck(체크할 값, 항목제목, 항목명, 체크타입)
		if($key == "sender")  $func->vaildCheck($val, "신청자 연락처", "phone" ,"M", "div"); //$val, $title, $type=null, $must="N", $target=null
		if($key == "mesg")    $func->vaildCheck($val, "신청자명", "trim" ,"M", "div");
	}

	# 상담모듈 연동
	if($func->checkModule("mdApp01"))
	{
		$contentAdd['name'] = $_POST['mesg'];
		$contentAdd['mobile'] = $sender;
		$contentAdd['phone'] = $sender;
		$contentAdd  = serialize($contentAdd);
		$appCategory = $db->queryFetchOne(" SELECT `cate` FROM `mdApp01__content` ORDER BY cate ASC ");
		$db->Query(" INSERT INTO `mdApp01__content` (cate,division,name,mobile,phone,content,contentAdd,dateReg,info) VALUES ('".$appCategory."','100','".$_POST['mesg']."','".$sender."','".$sender."','".$mesg."','".$contentAdd."','".time()."','".$cfg['timeip']."') ");
	}

	//if($_SESSION['ulevel'] != 1)
	//{
		# 문자전송
		if($rdoSend == 2) { $sock->date = strtotime($sdate." ".$shour.":".$smin.":00"); }
		$sock->tempMode = "mdApp01";
		$sock->sender			= $sender;
		$sock->tempArray	= array($_POST['mesg'], "빠른상담신청");
		$socket           = $sock->smsSend($cfg['site']['mobile'], "temp01");

		$socket['msg']		= ($cfg['charset'] == 'euc-kr') ? $socket['msg'] : iconv("CP949", "UTF-8", $socket['msg']);
		$socket['msg']		= ($socket['code'] == '98') ? "상담신청이 정상적으로 접수되었습니다" : $socket['msg'];
	//}
	$func->err($socket['msg'], "/");
}
else
{
	$func->err("[경고3] 정상적인 접근이 아닙니다.", '/');
}
?>
