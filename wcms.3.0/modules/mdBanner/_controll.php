<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
$type = ($_GET['type']) ? $_GET['type']	: $_POST['type'];

switch($type) {
	//목록
	case "list" :
		include __PATH__."modules/".$cfg['cate']['mode']."/list".$cfg['content']['listing'].".php";
		break;

	//null일때
	default :
		if(!$type) {
			include __PATH__."modules/".$cfg['cate']['mode']."/list".$cfg['content']['listing'].".php";
		} else {
			$func->err("세션이 비정상적으로 종료되었습니다. 다시 로그인 하시기 바랍니다.", "window.location.replace('./?cate=000002001');");
		} 
	break;
}

?>
