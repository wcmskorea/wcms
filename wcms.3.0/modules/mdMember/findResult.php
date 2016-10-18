<?php
/* ---------------------------
| 아이디 찾기 검색
|-----------------------------
| Lastest : 2009-02-10
*/
require_once "../../_config.php";
if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER'])){ $func->err("[경고!] 정상적인 접근이 아닙니다."); }
if($_SERVER['REQUEST_METHOD'] == 'GET' ) { $func->err("[경고!] 정상적인 접근이 아닙니다."); }

if($sess->decode($_POST[type]) == "findUserId")
{
  $_POST[name] = ($cfg[charset] == 'euc-kr') ? iconv("UTF-8", "CP949", $_POST[name]) : $_POST[name];

	$user = $db->queryFetchOne(" SELECT id from `mdMember__account` WHERE name='".$_POST[name]."' AND email='".$_POST[idcode]."' ");
	if($db->getNumRows() < 1)
	{
		$func->ajaxMsg("[".$_POST[idcode]."]입력하신 정보와 일치하는 회원이 없습니다.","",20);
	} else
	{
		$func->ajaxMsg('회원님이 아이디는 { <strong class="red">'.$user.'</strong> } 입니다','return true;',20);
	}
}
else if($sess->decode($_POST[type]) == "findUserPw")
{
	$user = $db->queryFetch("SELECT * FROM mdMember__account INNER JOIN mdMember__info
													ON mdMember__account.id=mdMember__info.id
													AND mdMember__account.id='".$_POST[id]."' AND mdMember__account.email='".$_POST[idcode]."'");
	if($db->getNumRows() < 1)
	{
		$func->ajaxMsg("입력하신 정보와 일치하는 회원이 없습니다.","",20);
	} else
	{
		$authcode	= rand(5432, 98765);

		/* ------------------------------------------------------------------------------------
		| SMS(Socket) 문자 발송
		*/
		if(in_array('mdSms',$cfg[modules]) && $cfg[content][sms] != 'N' && $user[mobile])
		{
			$sock->tempMode		= "mdMember_find";
			$sock->tempArray	= array($user[id], $authcode, $cfg[site][domain]);
			$sock->smsSend($user[mobile], "member");
		}

		$content = '<strong>'.$cfg[site][siteName].'&nbsp;-&nbsp;임시&nbsp;비밀번호&nbsp;요청하셨습니다!</strong><br /><br />';
		$content .= '회원명&nbsp;:&nbsp;<strong>'.$user[name].'</strong><br /><br />';
		$content .= '임시 비밀번호&nbsp;:&nbsp;<span style="color:red">'.$authcode.'</span><br /><br />';
		$content .= '위 비밀번호로 로그인 후 <span style="font-size:11px;color:#666">반드시 새로운 비밀번호로 변경하시기 바랍니다!<br /><br />';
		$content .= 'http://www.'.$cfg[site][domain].'</span>';
		$result = Member::sendMail($user[email], $cfg[site][email], $cfg[site][siteName]." - 임시 비밀번호 요청입니다.",$content, $cfg[site][siteName]);
		if($result)
		{
			$authcode = $db->passType($cfg['site']['encrypt'], $authcode);//해당 사이트의 암호화방식에 맞도록 비밀번호 변경(2012-12-28)
			$db->query("UPDATE `mdMember__account` SET passwd='".$authcode."', passwdModify='".time()."' WHERE id='".$_POST[id]."' AND email='".$_POST[idcode]."'");
			//Member::sendMail("css@aceoa.com", $user[email], $cfg[site][siteName]." - 임시 비밀번호 요청입니다.",$content, $cfg[site][siteName]);
			$func->setLog(__FILE__, "임시 비밀번호로 비밀번호 변경");
			$func->ajaxMsg("회원님의 { <strong>".$user[email]."</strong> }로<br />임시비밀번호가 전송되었습니다.","return true;",20);
		}
	}
} else
{
  $func->AjaxMsg("잘못된 경로로 접속하셨습니다.","",20);
}
die();
?>
