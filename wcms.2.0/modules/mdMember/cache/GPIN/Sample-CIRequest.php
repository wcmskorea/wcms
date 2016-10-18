<?php
header("Content-Type: text/html; charset=euc-kr");
header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

?>
<?php

	include("gpin_func.php");
	// 공공I-PIN에 사용자의 사이트 가입확인을 알려주고 그에 대한 응답을 받을 페이지 정보를 session에 설정합니다.
    // TODO 이용기관에서 사용할 페이지를 설정합니다.
	$_SESSION["gpinAuthRetPage"] = "Sample-CIResponse.jsp";
	// 인증 수신시 요청처와 동일한 위치인지를 확인할 요청자IP를 session에 저장합니다.
	$_SESSION["gpinUserIP"]	 = $_SERVER['REMOTE_ADDR'];

	$ret="실패";

	if ($_REQUEST["Attr"] != null){
		
		$attributes = split(";", $_REQUEST["Attr"]);	
		$ret = makeAttributeRequest('$_REQUEST["vidn"] | $_REQUEST["regNo"]', $attributes);

	}

	// 에러인 경우 메세지표시 후 종료함.		
	GPIN_ERROR_CHECK(TRUE);

	//인증요청페이지 출력
	echo $ret;


?>
