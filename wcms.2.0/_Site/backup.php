<?php
require_once "../_config.php";
if(!isset($_SESSION['uid'])) { $func->err("�����ڸ� �̿��� �� �ֽ��ϴ�.","window.self.close()"); }
if($_SESSION['ulevel'] > $cfg['operator']) { $func->err("�����ڸ� �̿��� �� �ֽ��ϴ�.", "window.self.close()"); }

$dir		= __DB__."/".$_POST[dir];
$fullSize	= 0;

for($i=0;$i<$_POST['rtotal'];$i++) {
	# �뷮 ǥ��
	$SIZE	= exec('du '.$dir."/".$_POST[choice][$i].' --max-depth=0') ;
	$SIZE	= explode("/",$SIZE);
	$fullSize += $SIZE[0];
	if($fullSize > 102400) $mod->Err("�뷮�� 100MB�� �ʰ��Ͽ� ������� �ʽ��ϴ�.","history.back()");
}

header("Content-type: application/zip");
header("Content-disposition: attachment; filename=backup_".date("ymd").".zip");

require_once    __PATH__."_Lib/classZip.php";
$zipfile = new zipFile();

for($i=0;$i<$_POST[rtotal];$i++) {
	//echo $dir."/".$_POST[choice][$i]."<br />";
	if(is_file($dir."/".$_POST[choice][$i])) {
		$zipfile -> add_file($dir."/".$_POST[choice][$i], $dir."/".$_POST[choice][$i]); //add_file(����,������)
	}
	else if(is_dir($dir."/".$_POST[choice][$i])) {
		$zipfile -> add_dirs($dir."/".$_POST[choice][$i]);
	}
}
echo $zipfile -> file();

/*
if(is_file($DIR."/".$_POST[choice][$i])) {
		$zipfile -> add_file($DIR."/".$_POST[choice][$i], $DIR."/".$_POST[choice][$i]); //add_file(����,������)
	} else

require $_SERVER[DOCUMENT_ROOT]."/_Lib/zipClass.asp";
header("Content-type: application/x-gzip");
header("Content-disposition: attachment; filename=backup_".date("ymd").".tar.gz");
$zipfile = new zipFile();
//$zipfile -> add_dir("_Site/".$CFG['HOME']['Domain']."/".$_GET[dir]);  //���丮 ���� (��������)
//$filedata ="test/a.jpg";  //��������Ÿ ���� (������)
//$zipfile -> add_file("../../_Site/skin/icon01.gif", "../../_Site/zip/icon01.gif");  //add_file(����,������)
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