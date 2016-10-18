<?php
/*---------------------------------------------------------------------------------------
 | 회원 로그인 처리
 |----------------------------------------------------------------------------------------
 | Lastest (2009년 12월 11일 금요일 : 이성준
 */
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }

if(!$_POST['uid']) { $func->err("(아이디) 항목은 반드시 입력하셔야합니다.", "window.history.back()"); }
if(!$_POST['upw']) { $func->err("(비밀번호) 항목은 반드시 입력하셔야합니다.", "window.history.back()"); }

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
	$func->err("시스템관리자로 로그인되었습니다", "location.replace('".$_POST['uri']."')");
}

if($_POST['loginType'] == 'web')
{
	//암호화 방식에 다른 검색
	if($cfg['site']['encrypt'] == 'crypt')
	{
		$Rows = $db->queryFetch(" SELECT *,A.recom AS recom FROM `mdMember__account` AS A INNER JOIN `mdMember__info` AS B ON A.id=B.id LEFT JOIN `mdMember__level` AS C ON A.level=C.level WHERE A.id='".strtolower(mysql_real_escape_string($_POST['uid']))."' AND A.level>'0' ");
		if(crypt($_POST['upw'], $Rows['passwd']) != $Rows['passwd']) {
			$func->err("비밀번호가 다르거나 일치하는 회원정보가 존재하지 않습니다.", "window.history.back()");
		}
	}
	else
	{
		$Rows = $db->queryFetch(" SELECT *,A.recom AS recom FROM `mdMember__account` AS A INNER JOIN `mdMember__info` AS B ON A.id=B.id LEFT JOIN `mdMember__level` AS C ON A.level=C.level WHERE A.id='".strtolower(mysql_real_escape_string($_POST['uid']))."' AND A.passwd='".$db->passType($cfg['site']['encrypt'], mysql_real_escape_string($_POST['upw']))."' AND A.level>'0' ");
	}

	if($db->getNumRows() < 1)
	{
		$func->err("비밀번호가 다르거나 일치하는 회원정보가 존재하지 않습니다.", "window.history.back()");
		//$func->err("일치하는 회원정보가 존재하지 않습니다.\\n\\n가입을 하셨다면 ['승인대기']상태이니 운영자에게 문의바랍니다.", "window.history.back()");
	}
	else
	{
		#--- 로그인 입력항목 옵션 체크
		$uname			= ($Rows['nick']) ? $Rows['nick'] : $Rows['name'];
		$uid				= $Rows['id'];
		$author			= ($Rows['idcode']) ? 1 : $Rows['id'];
		$ulevel			= $Rows['level'];
		$useq				= $Rows['seq'];
		$urecom			= $Rows['recom'];
		$utype			= $Rows['division'];
		//$uposition = ($Rows['function']) ? $Rows['function'] : $Rows['position'];
		$uposition	= $Rows['position'];
		$passwdModify = $Rows['passwdModify'] > 0 ? $Rows['passwdModify'] : $Rows['dateReg'];
		$last				= explode("|", $db->queryFetchOne(" SELECT info FROM `mdMember__account` WHERE id='".$Rows['id']."' "));

		$passwdExpire = mktime(date("H", $passwdModify),date("i", $passwdModify),date("s", $passwdModify),date("m", $passwdModify) + 6,date("d", $passwdModify),date("Y", $passwdModify));

		#--- 본인실명확인 여부
		if($cfg['module']['cert'] != 'Pass' && $author != 1)
		{
			$_SESSION['author'] 	= $author;
			header("Location: ./?cate=000002002");
			exit(0);
		}
		else
		{
			//중복 로그인 체크
			if(!$sess->sessionLoginCheck($Rows['id']))
			{
				$func->setLog(__FILE__, "중복로그인으로 기존 로그인 세션 삭제 ".$Rows['id']);
			}

			$_SESSION['useq']				= $useq;
			$_SESSION['uid'] 				= $uid;
			$_SESSION['uname'] 			= $uname;
			$_SESSION['ulevel'] 		= $ulevel;
			$_SESSION['uposition'] 	= $uposition;
			$_SESSION['utype'] 			= $utype;
			$_SESSION['urecom']			= $urecom;
		}

		#--- 30일간 아이디 쿠키저장
		if($_POST['autoid'] == 'Y')
		{
			setcookie("USERID", $_POST['uid'], time()+(86400*30));
		}
		else
		{
			setcookie("USERID", "", 0);
		}
		//$db->query(" UPDATE `mdMember__account` SET info='".$cfg['timeip']."' WHERE id='".$Rows['id']."' ");
		$db->query(" INSERT INTO `mdMember__log` (id,ip) VALUES ('".$Rows['id']."','".$_SERVER['REMOTE_ADDR']."') ");
	}
	$func->setLog(__FILE__, $_SESSION['uname']."님 로그인 성공");

	$msg .= $_SESSION['uname']."님 저희 홈페이지를 찾아 주셔서 감사합니다. ";

	if($passwdExpire < time())
	{
		$msg .= "\\n\\n비밀번호 유효기간(6개월)이 만료되었습니다.";
		$func->err($msg."\\n\\n(최근변경일시: ".date("Y-m-d H:i:s",$passwdModify).")", "window.location.replace('/index.php?cate=000002002&type=".$sess->encode('passwdExpire')."');");
	} else {
		$func->err($msg."\\n\\n(최근접속 시간: ".$last['0'].")", "window.location.replace('".$_POST['uri']."');"); //2012-10-12 오혜진 로그인전 페이지로 이동하도록 변경 ( urldecode(__URI__) => $_POST['uri'] )
	}
}
?>
