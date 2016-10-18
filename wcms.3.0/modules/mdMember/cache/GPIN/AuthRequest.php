<?php
header("Content-Type: text/html; charset=euc-kr");
header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();
?>
<?
	//요청 메시지를 만들어 G-PIN센터로 전송하는 페이지를 만듭니다.
	include("gpin_func.php");

	$_SESSION["gpinAuthRetPage"] = "AuthResponse.php";		//응답을 처리할 페이지를 지정합니다.
	$_SESSION["gpinUserIP"]		 = $_SERVER['REMOTE_ADDR'];			//'USER IP를 세션으로 생성

	// 인증요청페이지 생성( makeAuthRequest() )
	// Attr : 요청 사용자정보 ( or 연산을 통해 조합 )
	// 0 or null 사용자 기본정보(개인식별번호/사용자이름/중복가입확인정보)
	// 1 주민등록번호
	// 2 성별
	// 4 생년월일

	if ($_REQUEST["Attr"] != null)
	{
		$ret = makeAuthRequest((int)$_REQUEST["Attr"]);
	}
	else
		$ret = makeAuthRequest(0);

	// 에러인 경우 메세지표시 후 종료함.
	GPIN_ERROR_CHECK(TRUE);

	//인증요청페이지 출력
	echo $ret;

?>
