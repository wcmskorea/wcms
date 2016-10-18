<?php
if(!defined('__CSS__')) header("HTTP/1.0 404 Not found");
/* ---------------------------------------
| 언어가 한국어가 아닐경우 email로 승인
*/
//if($cfg['lang'] != 'kr') $func->Err("Sorry!", "history.back()");
if($sess->decode($_POST[type]) == "findUserId")
{
	$user = $db->queryFetchOne(" SELECT id from `mdMember__account` WHERE name='".$_POST[name]."' AND email='".$_POST[idcode]."' ");
	if($db->getNumRows() < 1)
	{
		$func->err("입력하신 정보와 일치하는 회원이 없습니다.", "back");
	}
  else
	{
		$func->err('회원님이 아이디는 ( '.$user.' ) 입니다', "back");
	}
}
else if($sess->decode($_POST[type]) == "findUserPw")
{
	$user = $db->queryFetch("SELECT mdMember__account.id, name, email, mobile FROM mdMember__account INNER JOIN mdMember__info
													ON mdMember__account.id=mdMember__info.id
													AND mdMember__account.id='".$_POST[id]."' AND mdMember__account.email='".$_POST[idcode]."'");
	if($db->getNumRows() < 1)
	{
		$func->err("입력하신 정보와 일치하는 회원이 없습니다.", "back");
	}
  else
	{
		$authcode	= rand(5432, 98765);
		
		/* ------------------------------------------------------------------------------------
		| SMS(Socket) 문자 발송
		*/
		if($func->checkModule('mdSms') && $cfg['module']['find'] == 'sms')
		{
			$sock->tempMode		= "mdMember";
			$sock->tempArray	= array($user['id'], $authcode , $cfg['site']['siteName']);
			$sock->smsSend($user['mobile'], "temp01");
			$sock->varReset();  //데이터 리셋
		}

		$content = '<strong>'.$cfg[site][siteName].'&nbsp;-&nbsp;임시&nbsp;비밀번호&nbsp;요청하셨습니다!</strong><br /><br />';
		$content .= '회원명&nbsp;:&nbsp;<strong>'.$user[name].'</strong><br /><br />';
		$content .= '임시 비밀번호&nbsp;:&nbsp;<span style="color:red">'.$authcode.'</span><br /><br />';
		$content .= '위 비밀번호로 로그인 후 <span style="font-size:11px;color:#666">반드시 새로운 비밀번호로 변경하시기 바랍니다!<br /><br />';
		$content .= 'http://www.'.$cfg[site][domain].'</span>';
		$result = Member::sendMail($user[email], $cfg[site][email], $cfg[site][siteName]." - 임시 비밀번호 요청입니다.",$content, $cfg[site][siteName]);
		if($result)
		{
			$authcode = $db->passType($cfg['site']['encrypt'], $authcode);	//해당 사이트의 암호화방식에 맞도록 비밀번호 변경(2012-12-28)
			$db->query(" UPDATE `mdMember__account` SET passwd='".$authcode."', passwdModify='".time()."' WHERE id='".$_POST[id]."' AND email='".$_POST[idcode]."' ");
			//Member::sendMail("css@aceoa.com", $user[email], $cfg[site][siteName]." - 임시 비밀번호 요청입니다.",$content, $cfg[site][siteName]);
			$func->setLog(__FILE__, "임시 비밀번호로 비밀번호 변경");
			$func->err("회원님의 ( ".$user[email]." )로 임시비밀번호가 전송되었습니다.", "back");
		}
	}
}
?>
<div id="find_wrap">
	<div class="find_container" style="width:680px; height:260px">

		<div class="cell" style="background:URL(<?=__SKIN__?>image/background/member_id.gif) no-repeat; width:310px; height:253px; padding:60px 10px 0 10px; margin-right:10px;">
			<form name="reg" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data" onsubmit="return validCheck(this);">
			<input type="hidden" name="cate" value="<?=__CATE__?>" />
			<input type="hidden" name="type" value="<?=$sess->encode('findUserId')?>" />
				<ul>
					<li class="opt"><p class="opt"><label for="findname1"><?=$lang['member']['name']?></label>&nbsp;<input type="text" id="findname1" name="name" class="input_gray" style="width:190px" title="<?=$lang['member']['name']?>" req="required" opt="korean" value="" /></p></li>
					<li class="opt"><p class="opt"><label for="findcode1"><?=$lang['member']['email']?></label>&nbsp;<input type="text" id="findcode1" name="idcode" class="input_gray" style="width:190px" title="<?=$lang['member']['email']?>" req="required" trim="trim" value="" /></p></li>
					<li class="btn"><span class="btnPack black medium strong"><button type="submit" class="red"><?=$lang['member']['findId']?></button></span></li>
				</ul>
			<div class="clear"></div>
      <br />
			<div class="cube pd5"><div class="small_gray" style="line-height:140%; background:#fff;">
				<img src="/common/image/icon/icon_title02.gif" width="7" height="9" alt="아이콘" /> <span class="bold">아이디를 잊어버리셨나요? </span>
				<br />회원등록시 사용하였던 이름과 이메일을 입력하시어 아이디를 찾아보실 수 있습니다. 그래도 찾지못하신다면 별도문의 바랍니다.
			</div></div>
			<div class="clear"></div>
			</form>
		</div>

		<div class="cell" style="background:URL(<?=__SKIN__?>image/background/member_password.gif) no-repeat; width:310px; height:253px; padding:60px 10px 0 10px;">
			<form name="reg" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data" onsubmit="return validCheck(this);">
			<input type="hidden" name="cate" value="<?=__CATE__?>" />
			<input type="hidden" name="type" value="<?=$sess->encode('findUserPw')?>" />
				<ul>
					<li><p class="opt"><label for="findname2"><?=$lang['member']['id']?></label>&nbsp;<input type="text" id="findname2" name="id" class="input_gray" style="width:190px" title="<?=$lang['member']['id']?>" req="required" opt="userid" value="" /></p></li>
					<li><p class="opt"><label for="findcode2"><?=$lang['member']['email']?></label>&nbsp;<input type="text" id="findcode2" name="idcode" class="input_gray" style="width:190px" title="<?=$lang['member']['email']?>" req="required" opt="email" value="" /></p></li>
					<li class="btn"><span class="btnPack black medium strong"><button type="submit" class="red"><?=$lang['member']['findPwd']?></button></span></li>
				</ul>
			<div class="clear"></div>
      <br />
			<div class="cube pd5"><div class="small_gray" style="line-height:140%; background:#fff;">
				<img src="/common/image/icon/icon_title02.gif" width="7" height="9" alt="아이콘" /> <span class="bold">비밀번호를 잊어버리셨나요? </span>
				<br />회원등록시 사용하였던 아이디와 E-mail을 입력하시어 임시 비밀번호를 E-mail로 받으실 수 있습니다. 등록하셨던 E-mail이 확인 불가할 경우 신규로 회원을 가입하시길 권유드립니다. (주민번호 입력받지 않습니다)
			</div></div>
			</form>
		</div>

	</div>
</div>
