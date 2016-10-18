<?php
header("Content-Type: text/html; charset=UTF-8");
header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

?>

<?

	include("gpin_func.php");
	$_SESSION["gpinAuthRetPage"] = "AuthResponse.php";


	//사용자정보 동기화메시지를 복호화
	$v = decryptGpinSync($_REQUEST["gPinSyncData"]);

	//v[0](개인식별번호)
 	//v[1](개명['name'] | 상태['status'])
 	//v[2]('변경된 이름' | 탈퇴 코드: 5)

	//---------------------------------------------------------
	//이용기관 DB에 변경사항을 적용하십시오.
	//---------------------------------------------------------
	//
	// <pseudo code>
	//
	// if (v[1] == 'name')
	// 	회원 개명처리
	//	[이용기관 DB username] = v[2]
	// else (v[1] == 'status')
	//	if (v[2] == '5')
	//		회원 탈퇴처리
	//
	//---------------------------------------------------------

	//사용자정보 동기화 결과 생성
	//data를 받았을 경우 succ, 못 받았을 경우 fail 값을 전달합니다.
	$ret = makeResponseSyncResponse($v[0], "succ");

	// 에러인 경우 메세지표시 후 종료함.
	GPIN_ERROR_CHECK(TRUE);

	//사용자정보 동기화 결과를 G-PIN 센터에 보냄(반드시 처리해야 합니다.)
	echo $ret;

?>
