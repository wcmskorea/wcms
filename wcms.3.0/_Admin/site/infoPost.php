<?php
require "../include/commonHeader.php";
require	__PATH__."_Lib/classConfig.php";
$config = new Config();

switch(trim($_POST['type']).trim($_GET['type']))
{
	/**
	 * 시스템 정보변경
	 */
	case "infoSystemPost" :

		//넘어온 값과 변수 동기화 및 validCheck
		foreach($_POST AS $key=>$val)
		{
			$cfg['site'][$key] = trim($val);
			#--- $func->validCheck(체크할 값, 항목제목, 항목명(타겟), 체크타입)
			if($key == "admid") $func->vaildCheck($val, "운영자 아이디", "trim", "M");
			if($key == "operator") $func->vaildCheck($val, "운영자 이름", "trim", "M");
			//if($key == "admpasswd" && $val) $func->vaildCheck(array($val, $_POST['admpasswd2']), "운영자 비밀번호", "match");
		}

		/*if($_POST['admpasswd'] && $_POST['admpasswd2'] && $_POST['admpasswd'] != $_POST['admpasswd2'])
		{
			$func->setLog(__FILE__, "운영자 비밀번호 변경 실패", true);
			$func->err("운영자 비밀번호가 일치하지 않습니다. 적용 실패입니다.");
		}*/

		#--- 첨부파일 처리
		if(is_uploaded_file($_FILES['upfile']['tmp_name']))
		{
			$fileName = $_FILES['upfile']['name'];
			$fileExt  = array_reverse(explode(".", $fileName));

			if(strtolower($fileExt[0]) != 'ico') $func->err('확장자가 ico인 파일만 업로드 가능합니다.');

			require	__PATH__."_Lib/classUpLoad.php";
			$up = new upLoad($cfg['upload']['dir'], $_FILES);
			$up->dir = __HOME__."image/icon/";
			$up->upFiles('favicon'.$up->fileExt);
			//$cfg['site']['icon'] = $up->fileRename;
		}

		/**
	 	* 환경설정 정보 갱신
	 	*/
		$cfg['site']['dateReg']		= strtotime($cfg['site']['regyear']."-".$cfg['site']['regmonth']."-".$cfg['site']['regday']);
		$cfg['site']['dateCheck']	= strtotime($cfg['site']['checkyear']."-".$cfg['site']['checkmonth']."-".$cfg['site']['checkday']." ".$cfg['site']['checkhour'].":".$cfg['site']['checkmin'].":00");
		$cfg['site']['info']		= $cfg['timeip'];

		$config->configed = $cfg;
		$config->configMake(array('type','admid','admpasswd','checkyear','checkmonth','checkday','checkhour','checkmin'));

		if($config->configSave(__HOME__."cache/config.ini.php"))
		{

			$db->query(" UPDATE `mdMember__account` SET name='".$cfg['site']['operator']."' WHERE id='".$cfg['site']['admid']."' AND level='2' ");
			if($cfg['site']['admpasswd'])
			{
				$db->query(" UPDATE `mdMember__account` SET passwd='".$db->passType($cfg['site']['encrypt'], $cfg['site']['admpasswd'])."', passwdModify='".time()."' WHERE id='".$cfg['site']['admid']."' AND level='2' ");
				if($db->getAffectedRows() > 0)
				{
					$socket = $sock->memUpdate($cfg['site']['admid'], $cfg['site']['operator'], $cfg['site']['admpasswd']);
				}
			}

			$config->configSave(preg_replace('/\/default\//','/mobile/',__HOME__)."cache/config.ini.php");
			$config->configSave(preg_replace('/\/default\//','/english/',__HOME__)."cache/config.ini.php");

			$func->setLog(__FILE__, "시스템 정보 변경", true);
			$func->err("시스템 정보가 정상적으로 적용되었습니다.");
		}
		else
		{
			$func->err("시스템 정보가 변경된 내용이 없거나, 적용 실패입니다.");
		}

		break;

	/**
	 * 사업자 정보변경
	 */
	case "infoBizPost" :

		//넘어온 값과 변수 동기화 및 validCheck
		foreach($_POST AS $key=>$val)
		{
			$cfg['site'][$key] = trim($val);
			#--- $func->validCheck(체크할 값, 항목제목, 항목명, 체크타입)
			if($key == "siteName") $func->vaildCheck($val, "사이트 이름", "trim", "M");
			if($key == "siteTitle") $func->vaildCheck($val, "사이트제목", "trim");
			if($key == "groupNo" && $val) $func->vaildCheck($val, "사업자 번호", "groupNo");
			if($key == "phone" && $val) $func->vaildCheck($val, "전화번호", "phone");
			if($key == "fax" && $val) $func->vaildCheck($val, "팩스번호", "homephone");
			if($key == "mobile" && $val) $func->vaildCheck($val, "담당자 휴대전화", "mobile");
			if($key == "email" && $val) $func->vaildCheck($val, "담당자 이메일", "email");
		}

		/**
	 	* 환경설정 정보 갱신
	 	*/
		$cfg['site']['dateReg'] = strtotime($cfg['site']['regyear']."-".$cfg['site']['regmonth']."-".$cfg['site']['regday']);
		$cfg['site']['info'] = $cfg['timeip'];

		$config->configed = $cfg;
		$config->configMake(array('type','regyear','regmonth','regday'));

		if($config->configSave(__HOME__."cache/config.ini.php"))
		{
			$func->setLog(__FILE__, "사업자 정보 변경", true);
			$func->err("사이트 기본 정보가 정상적으로 적용되었습니다.");
		}
		else
		{
			$func->err("사이트 기본 정보가 변경된 내용이 없거나, 적용 실패입니다.");
		}

		break;
}
?>