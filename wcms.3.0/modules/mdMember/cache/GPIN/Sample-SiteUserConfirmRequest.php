<?php

header("Content-Type: text/html; charset=UTF-8");
header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();
?>

<?
	// ##############################################################################
	// FILE_NAME: GPinSample-siteUserConfirmSend.php
	// DESC: 가입자 등록 시, 본인확인 후 센터에 이용기관에서의 사용자 id를 포함한 확인요청 메시지를 전송하는 페이지
	// ##############################################################################

	include("gpin_func.php");

	$_SESSION["gpinAuthRetPage"]= "Sample-SiteUserConfirmResponse.php";
	$_SESSION["gpinUserIP"] = $_SERVER["REMOTE_ADDR"];

	$attributes = split(";", $_REQUEST["Attr"]);

	$ret = makeAttributeRequest($_REQUEST["vidn"], $attributes);

	// 에러인 경우 메세지표시 후 종료함.		
	GPIN_ERROR_CHECK(TRUE);


	echo $ret;
?>
