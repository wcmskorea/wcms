<?php
/**
 * Configration
 */
require_once "./_config.php";

/**
 * 로그분석 모듈
 */
if($func->checkModule('mdAnalytic'))
{
	$sess->counting('count', __HOST__);				//방문자 카운트
	$sess->countReferer($_SERVER['HTTP_REFERER']);	//방문자 Referer
}

if($_GET['url'])
{
	Header("Location: http://".$_GET['url']);
} else {
	$func->err("URL 정보가 정확하지 않습니다!", "back");
}

$db->FreeResult();
unset($buffer);
unset($cfg);
?>