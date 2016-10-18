<?php
require_once "../_config.php";
if(!isset($_SESSION['uid'])) { $func->err("관리자만 이용할 수 있습니다.","window.self.close()"); }
if($_SESSION['ulevel'] > $cfg['operator']) { $func->err("관리자만 이용할 수 있습니다.", "window.self.close()"); }

$dir		= __DB__."/".$_POST[dir];
$fullSize	= 0;

for($i=0;$i<$_POST['rtotal'];$i++) {
	# 용량 표기
	$SIZE	= exec('du '.$dir."/".$_POST[choice][$i].' --max-depth=0') ;
	$SIZE	= explode("/",$SIZE);
	$fullSize += $SIZE[0];
	if($fullSize > 102400) $mod->Err("용량이 100MB를 초과하여 백업되지 않습니다.","history.back()");
}

header("Content-type: application/zip");
header("Content-disposition: attachment; filename=backup_".date("ymd").".zip");

require_once    __PATH__."_Lib/classZip.php";
$zipfile = new zipFile();

for($i=0;$i<$_POST[rtotal];$i++) {
	//echo $dir."/".$_POST[choice][$i]."<br />";
	if(is_file($dir."/".$_POST[choice][$i])) {
		$zipfile -> add_file($dir."/".$_POST[choice][$i], $dir."/".$_POST[choice][$i]); //add_file(원본,압축대상)
	}
	else if(is_dir($dir."/".$_POST[choice][$i])) {
		$zipfile -> add_dirs($dir."/".$_POST[choice][$i]);
	}
}
echo $zipfile -> file();

/*
if(is_file($DIR."/".$_POST[choice][$i])) {
		$zipfile -> add_file($DIR."/".$_POST[choice][$i], $DIR."/".$_POST[choice][$i]); //add_file(원본,압축대상)
	} else

require $_SERVER[DOCUMENT_ROOT]."/_Lib/zipClass.asp";
header("Content-type: application/x-gzip");
header("Content-disposition: attachment; filename=backup_".date("ymd").".tar.gz");
$zipfile = new zipFile();
//$zipfile -> add_dir("_Site/".$CFG['HOME']['Domain']."/".$_GET[dir]);  //디렉토리 생성 (압축파일)
//$filedata ="test/a.jpg";  //원본데이타 파일 (압축대상)
//$zipfile -> add_file("../../_Site/skin/icon01.gif", "../../_Site/zip/icon01.gif");  //add_file(원본,압축대상)
$zipfile -> add_dirs("_Site/".$CFG['HOME']['Domain']."/".$_POST[dir]);
echo $zipfile -> file();

OR

//$zipfile = new zipFile();
//$zipfile -> add_file("font/ariali.ttf","font/ariali.ttf");
//$zipfile -> add_dirs("../../_Site/aceoa.co.kr/skin/");
//$fh = fopen("../../_Site/test.zip", 'w');
//fwrite($fh, $zipfile -> file());
//fclose($fh);
*/

?>