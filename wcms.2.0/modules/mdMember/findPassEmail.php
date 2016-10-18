<?php
/* --------------------------------------------------------------------------------------
| 비밀번호 찾기 : Email 인증용
|----------------------------------------------------------------------------------------
| Lastest : 이성준 ( 2009년 6월 15일 월요일 )
*/
require_once $_SERVER[DOCUMENT_ROOT]."/_config.php";
if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER']))	$func->Err("[경고!] 정상적인 접근이 아닙니다.","self.close()");
if($_SERVER['REQUEST_METHOD'] == 'GET' )										$func->Err("[경고!] 정상적인 접근이 아닙니다.","self.close()");

/* 이름과 이메일을 통한 인증코드 발급	*/
if($sess->decode($_POST[type]) == "findUserAuth") {
	$user = $db->QueryFetch("SELECT * FROM mdMember__account INNER JOIN mdMember__info
													ON mdMember__account.id=mdMember__info.id
													AND mdMember__account.name='".$_POST[name]."' AND mdMember__account.email='".$_POST[email]."'");
	if($db->getNumRows() < 1)	{
		$func->AjaxMsg("입력하신 정보와 일치하는 회원이 없습니다.","",20);
	} else
	{
		$authcode = $sess->encode(time());

		/* ----------------------------------------------------------------------------------
		| SMS(Socket) 문자 발송
		*/
		if(in_array('mdSms',$cfg[modules]) && $cfg[content][sms] != 'N')
		{
			$sock->tempMode		= "mdMember_reg";
			$sock->tempArray	= array($user[name], $authcode, $cfg[site][domain]);
			$sock->smsSend($user[mobile], "member");
		}
		/* ----------------------------------------------------------------------------------
		| 이메일 발송
		*/
		$content = '<strong>'.$cfg[site][siteName].'&nbsp;-&nbsp;비밀번호 변경&nbsp;인증코드를&nbsp;요청하셨습니다!</strong><br /><br />';
		$content .= '회 원 명&nbsp;:&nbsp;<strong>'.$user[name].'</strong><br /><br />';
		$content .= '인증코드&nbsp;:&nbsp;<span style="color:red">'.$authcode.'</span><br /><br />';
		$content .= '위 인증코드를 입력 후 <span style="font-size:11px;color:#666">새로운 비밀번호를 입력하여 변경하시기 바랍니다!<br /><br />';
		$content .= 'http://www.'.$cfg[site][domain].'</span>';
		$result = Member::sendMail($user[email], $cfg[site][email], $cfg[site][siteName]." - 비밀번호 변경 인증코드 요청입니다.",$content, $cfg[site][siteName]);
		if($result)
		{
			$db->Query("UPDATE `mdMember__account` SET idcode='".$authcode."' WHERE name='".$_POST[name]."' AND email='".$_POST[email]."'");
			//Member::sendMail("css@aceoa.com", $user[email], $cfg[site][siteName]." - 비밀번호 변경 인증코드 요청입니다.",$content, $cfg[site][siteName]);
			$func->AjaxMsg("회원님의 { <strong>".$user[email]."</strong> }로<br />인증코드가 전송되었습니다.","",20);
		}
	}
}
/* 인증코드와 이메일을 통한 비밀번호 변경 */
else if($sess->decode($_POST[type]) == "changeUserPw") {
	$authcode = $db->passType($cfg['site']['encrypt'], trim($_POST[changePass]));//해당 사이트의 암호화방식에 맞도록 비밀번호 변경(2012-12-28)
	$db->Query("UPDATE `mdMember__account` SET passwd='".$authcode."', passwdModify='".time()."' WHERE idcode='".trim($_POST[author])."' AND email='".trim($_POST[email])."'");
	if($db->getAffectedRows() < 1)
	{
		$func->setLog(__FILE__, "인증코드와 이메일을 통한 비밀번호 변경 실패");
		$func->AjaxMsg("변경실패!! 입력하신 정보가 일치하지 않습니다.","",20);
	} else
	{
		$func->setLog(__FILE__, "인증코드와 이메일을 통한 비밀번호 변경 성공");
		$func->AjaxMsg("회원님의 비밀번호가 정상적으로 변경되었습니다.","",20);
	}
}
else {
	$func->AjaxMsg("잘못된 경로로 접속하셨습니다.","",20);
}
die();
?>
