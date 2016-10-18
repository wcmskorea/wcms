<?php
/**
 * Author : Sung-Jun, Lee (http://www.aceoa.com)
 * Lastest : 2011. 1. 25.
 */

//단독실행 방지
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }

//리퍼러 체크
if(!preg_match("/".$_SERVER['HTTP_HOST']."/", $_SERVER['HTTP_REFERER'])) { header("HTTP/1.0 404 Not found"); die(); }
usleep($cfg['sleep']);

if((preg_match("/\_Admin/", $_SERVER['REQUEST_URI']) || preg_match("/admin/", $_SERVER['REQUEST_URI'])) && ($_SESSION['ulevel'] > $cfg['operator'] || !$_SESSION['ulevel']))
{
	$func->err("세션이 종료되었습니다. 로그인 페이지로 이동합니다.", "document.location.replace('/_Admin/login.php');");
	die();
}
?>
