<?php
/*---------------------------------------------------------------------------------------
| 강좌모듈 : 등록 DB처리
|----------------------------------------------------------------------------------------
| Relationship : /modules/mdBanner/manage/_controll.php
| Last (2009-08-29 : 이성준)
*/
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
usleep($cfg['sleep']);

foreach($_POST AS $key => $val)
{
  ${$key} = trim($val);
  # POST값 vaild check
  if($key == "subject")       $func->vaildCheck($val, "광고명", $key, "trim", "M");
  //if($key == "url")           $func->vaildCheck($val, "이동 URL", $key, "trim");
  if($key == "target")        $func->vaildCheck($val, "이동 Target", $key);
  if($key == "banner")        $func->vaildCheck($val, "배너첨부", $key, "trim", "M");
  if($key == "width")         $func->vaildCheck($val, "배너 가로사이즈", $key, "num", "M");
  if($key == "height")        $func->vaildCheck($val, "배너 세로사이즈", $key, "num", "M");
  if($key == "hidden")        $func->vaildCheck($val, "노출여부", $key);
  if($key == "speriod")       $func->vaildCheck($val, "광고 시작일", $key, "date");
  if($key == "eperiod")       $func->vaildCheck($val, "광고 종료일", $key, "date");
}

#--- 첨부파일 처리
$realName = explode('.', $fileName);
require	__PATH__."_Lib/classUpLoad.php";
$up = new upLoad(str_replace("/default/","/".$_POST['skin']."/",$cfg['upload']['dir']), $_FILES);
$up->resizeOriginImage = 102400;
$up->count = 2;

#--- 썸네일 처리
if(is_uploaded_file($_FILES['upfile1']['tmp_name']))
{
	$up->upFiles(str_replace('banner_', 'bannerThumb_', $realName['0']));
}

if(is_uploaded_file($_FILES['upfile']['tmp_name']))
{
	$up->upFiles("banner_".time().$up->fileExt);
	$fileName = $up->fileRename;
} else {
	$fileName = $fileName;
}

$db->Lock(array('`mdBanner__content`'=>'WRITE'));

$seq        = (!$_POST['idx']) ? $db->queryFetchOne("SELECT MAX(seq) FROM `mdBanner__content` WHERE skin='".$skin."' AND position='".$position."'") + 1 : $_POST['idx'];
$bannerType = ($ext == 'swf') ? "flash" : "img";
$speriod    = strtotime($speriod." ".$speriodhour.":".$speriodmin.":00");
$eperiod    = ($unlimit == 'Y') ? 0 : strtotime($eperiod." ".$eperiodhour.":".$eperiodmin.":00");

if(!$up->fileRename)
{
	$Rows     = $db->queryFetch(" SELECT * FROM `mdBanner__content` WHERE seq='".$_POST['idx']."' AND skin='".$skin."' AND position='".$position."' ");
	$date     = $Rows['date'];
	$up->fileRename = $Rows['filename'];
} else
{
	$date   = time();
}

/**
 * 기본정보 등록
 */
$query = " REPLACE INTO `mdBanner__content` (seq,skin,position,type,subject,url,target,speriod,eperiod,fileName,width,height,widthThumb,heightThumb,hidden,date,info) VALUES ('".$seq."','".$skin."','".$position."','".$bannerType."','".$subject."','".$url."','".$target."','".$speriod."','".$eperiod."','".$fileName."','".$width."','".$height."','".$widthThumb."','".$heightThumb."','".$hidden."','".$date."','".$cfg['timeip']."') ";

$db->query($query);

$msg = (!$_POST['idx']) ? "등록" : "변경";
if($db->getAffectedRows() > 0)
{
	$db->UnLock();
	$func->err("정상적으로 ".$msg." 되었습니다.", "parent.$.insert('#left_mdBanner','../modules/mdBanner/manage/_left.php','20'); parent.$.insert('#module', '../modules/mdBanner/manage/_controll.php?type=list&skin=".$skin."&position=".$position."',null,300); parent.$.dialogRemove();");
} else
{
	$func->err($msg."실패! 관리자에게 문의바랍니다.");
}
