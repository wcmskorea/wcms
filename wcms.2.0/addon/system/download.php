<?php
/*---------------------------------------------------------------------------------------
 | 파일 다운로더
 |----------------------------------------------------------------------------------------
 | Last (2009-08-29 : 이성준)
 */
require_once "../../_config.php";
if(!preg_match("/".$_SERVER['HTTP_HOST']."/", $_SERVER['HTTP_REFERER'])) { $func->err("정상적인 접속시도 하시기 바랍니다.","window.history.back()"); }

//if(!$member->checkPerm(2)) { $func->err($lang['board_alert_perm']); }

$file = $sess->decode($_GET['file']);
if($_GET['img']) { $file = str_replace("/user/", "../_Site/".__DB__."/", $_GET['img']); }

if(is_file($file))
{
	$fName		= array_reverse(explode("/", $file));
	$_GET['name'] = urldecode($_GET['name']);
	$_GET['name'] = ($_GET['name']) ? iconv('UTF-8//IGNORE', 'CP949', $_GET['name']) : $fName[0];

	$fileSize = filesize($file);
	Header("Content-type: text/ html; charset=".$cfg['charset']);
	Header("Content-Length: ".(string)($fileSize));
	Header("Content-Disposition: attachment; filename=".$_GET['name']);
	Header("Content-Description: PHP5 Generated Data");
	Header("Cache-Control: cache, must-revalidate");
	Header("Pragma: no-cache");
	Header("Expires: 0");
	$fp = fopen($file, "rb");
	if(!fpassthru($fp))
	fclose($fp);
}
else
{
	$func->err("해당파일(".$_GET['name'].") 존재하지 않습니다.", "window.history.back()");
}
?>
