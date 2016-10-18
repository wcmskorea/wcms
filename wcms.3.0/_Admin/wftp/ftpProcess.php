<?php
require_once "../../_config.php";
require_once "../include/commonHeader.php";

//$droot	= ($cfg[site][setup] == 'Y') ? __HOME__ : __PATH__.'skin/';
$droot	= __PATH__."_Site/".__DB__."/";
$dir 	= ($_GET['dir']) ?  $droot.$_GET['dir'] : $droot;

$oldName= stripslashes(trim($_GET['oldName']));
$oldName= preg_replace("/[[:space:]]/","", $oldName);
$newName= stripslashes(trim($_GET['newName']));
$newName= preg_replace("/[[:space:]]/","", $newName);

switch(trim($_POST[type]).trim($_GET[type]))
{
//	폴더 생성
	case 'folderPost' :
		$pattern = '/[!@#$%^&*()<>|:"?+=\/]/';
		if(preg_match($pattern ,$newName)) $func->ajaxMsg("[$newName] 생성 실패입니다. 특수문자를 제거후 생성해주세요.", "$.insert('#dirSub','./ftpData.php?dir=$_GET[dir]');$.dialogRemove();", 15);

		if(!@mkdir($dir."/".$newName)) $func->ajaxMsg("[$newName] 생성 실패입니다.", "$.insert('#dirSub','./ftpData.php?dir=$_GET[dir]');$.dialogRemove();", 15);
			$func->ajaxMsg("[$newName]폴더가 생성 되었습니다", "$.insert('#dirSub','./ftpData.php?dir=$_GET[dir]');$.dialogRemove();", 15);
		break;
//	폴더 및 파일 이름변경
	case 'renamePost' :
		$pattern = '/[!@#$%^&*()<>|:"?+=\/]/';
		if(preg_match($pattern ,$newName)) $func->ajaxMsg("[$oldName]에서 [$newName]으로 변경 실패입니다. 특수문자를 제거후 변경해주세요.", "$.insert('#dirSub','./ftpData.php?dir=$_GET[dir]');$.dialogRemove();", 15);

		if($oldName && $newName && ($oldName != $newName)) {
		@rename($dir."/".$oldName, $dir."/".$newName);
		$func->ajaxMsg("[$oldName]에서 [$newName]으로 변경되었습니다", "$.insert('#dirSub','./ftpData.php?dir=$_GET[dir]');$.dialogRemove();", 20);
		} else {
			$func->ajaxMsg("[폴더 및 파일 이름변경]실패입니다", "$.insert('#dirSub','./ftpData.php?dir=$_GET[dir]');$.dialogRemove();", 20);
		}
		break;
//	파일 업로드
	case 'upload' :
		$func->ajaxMsg("파일이 업로드되었습니다", "$.insert('#dirSub','./ftpData.php?dir=$_GET[dir]');$.dialogRemove();", 15);
		break;
//	폴더 및 파일 삭제
	case 'delete' :
		foreach($_GET[choice] AS $key=>$val) {
//			echo $val."<br />";
			if(is_file($dir."/".$val)) {
				if($cfg[charset] == 'euc-kr') { $val = iconv("UTF-8//IGNORE","CP949", $val); }
				@unlink($dir."/".$val);
			} else {
				$func->dirDel($dir."/".$val, true);
			}
			$func->setLog(__FILE__, "WEB-FTP (".$val.")삭제 성공");
		}
		$func->ajaxMsg("정상적으로 삭제 되었습니다", "$.insert('#dirSub','./ftpData.php?dir=$_GET[dir]');$.dialogRemove();", 15);
		break;
	default :
		$func->ajaxMsg("데이터 전송 실패", "$.insert('#dirSub','./ftpData.php','&dir=$_GET[dir]');$.dialogRemove();", 15);
		break;
}

include "../include/commonScript.php";
?>
