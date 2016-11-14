<?php
require_once "../_config.php";

if(!$_POST['uid']) { $func->err("[운영자 아이디] 항목은 반드시 작성하셔야합니다.", "parent.$('input[typename=uid]').select()"); }
if(!$_POST['upw']) { $func->err("[운영자 비밀번호] 항목은 반드시 작성하셔야합니다.", "parent.$('input[typename=upw]').select()"); }

if($_POST['uid'] == 'master' && md5($_POST['upw']) == $cfg['master'])
{
	//중복 로그인 체크
	if(!$sess->sessionLoginCheck($_POST['uid']))
	{
		$func->setLog(__FILE__, "시스템관리자 중복로그인으로 기존 로그인 세션 삭제");
	}

	$_SESSION['uname']     = "10억홈피";
	$_SESSION['unick']     = "운영자";
	$_SESSION['uid']       = "master";
	$_SESSION['ulevel']    = "1";
	$_SESSION['useq']      = "99999999";
	$_SESSION['uposition'] = "시스템관리자";

	if($_POST['autoid'] == 'Y')
	{
		setcookie("uauto", "", 0);
		setcookie("uauto", $_POST['uid'], time()+(86400*30));
	} else
	{
		setcookie("uauto", "", 0);
	}
	$func->setLog(__FILE__, "시스템관리자 로그인 성공");
	$func->err("[1]정상적으로 로그인되었습니다", "top.location.replace('/_Admin/index.php')");
}
else
{

	if($cfg['site']['encrypt'] == 'crypt')
	{
		$Rows = $db->queryFetch(" SELECT * FROM `mdMember__account` AS A LEFT JOIN `mdMember__info` AS B ON A.id=B.id WHERE A.id='".strtolower(mysql_real_escape_string($_POST['uid']))."' AND A.level<'".$cfg['operator']."' AND A.level>'0' ");
		if(crypt($_POST['upw'], $Rows['passwd']) != $Rows['passwd'])
		{
			$func->err("비밀번호가 다르거나 일치하는 회원정보가 존재하지 않습니다.", "window.history.back()");
		}
	}
	else
	{
		$query = " SELECT * FROM `mdMember__account` AS A LEFT JOIN `mdMember__info` AS B ON A.id=B.id WHERE A.id='".strtolower(mysql_real_escape_string($_POST['uid']))."' AND A.passwd='".$db->passType($cfg['site']['encrypt'], mysql_real_escape_string($_POST['upw']))."' AND A.level<'".$cfg['operator']."' AND A.level>'0' ";
		$Rows = $db->queryFetch($query);
	}

	if($db->getnumRows() > 0)
	{
		//중복 로그인 체크
		//if(!$sess->sessionLoginCheck($Rows['id']))
		//{
		//	$func->setLog(__FILE__, "운영자 중복로그인으로 기존 로그인 세션 삭제 ".$Rows['id']);
		//}

		$_SESSION['uname']				= ($Rows['nick']) ? $Rows['nick'] : $Rows['name'];
		$_SESSION['unick']				= $Rows['nick'];
		$_SESSION['uid']				= $Rows['id'];
		$_SESSION['ulevel']				= $Rows['level'];
		$_SESSION['useq']				= $Rows['seq'];
		$_SESSION['udepartment']	    = $Rows['department'];
		$_SESSION['uposition']          = "시스템관리자";
		$passwdModify = $Rows['passwdModify'] > 0 ? $Rows['passwdModify'] : $Rows['dateReg'];
		$passwdExpire = mktime(date("H", $passwdModify),date("i", $passwdModify),date("s", $passwdModify),date("m", $passwdModify),date("d", $passwdModify)+180,date("Y", $passwdModify));	//비밀번호 유효기간 만료 여부

		if($_POST['autoid'] == 'Y')
		{
			setcookie("uauto", "", 0);
			setcookie("uauto", $_POST['uid'], time()+(86400*30));
		}
		else
		{
			setcookie("uauto", "", 0);
		}


		$db->query(" UPDATE `mdMember__info` SET info='".$cfg['timeip']."' WHERE id='".$Rows['id']."' ");
		$db->query(" INSERT INTO `mdMember__log` (id,ip) VALUES ('".$Rows['id']."','".$_SERVER['REMOTE_ADDR']."') ");
	}
	else
	{
		$_SESSION['uid'] = $_POST['uid'];
	 	$func->setLog(__FILE__, "운영자 로그인 실패");
	 	$func->err("입력된 정보가 다릅거나 접근권한이 없습니다.", "parent.$('input[name=upw]').val('').select();");
	}
}
$msg = "정상적으로 로그인되었습니다.";

if($passwdExpire < time())
{
	$msg .= "\\n\\n비밀번호 유효기간(180일)이 만료되었거나 초기 비밀번호입니다.\\n\\n비밀번호를 변경하시어 이용하시기 바랍니다.";
	$func->setLog(__FILE__, "운영자 로그인 성공 - 비밀번호만료");
	$func->err($msg, "parent.location.replace('".$cfg['droot']."_Admin/passwd.php')");
} else {
	$func->setLog(__FILE__, "운영자 로그인 성공");
	$func->err($msg, "parent.location.replace('".$cfg['droot']."_Admin/')");
}

exit(0);
?>
