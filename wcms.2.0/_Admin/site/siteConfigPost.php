<?php
require_once "../include/commonHeader.php";
require_once __PATH__."_Lib/classConfig.php";

if($_POST['type'] == 'sitePost')
{
//	넘어온 값과 변수 동기화 및 validCheck
	foreach($_POST AS $key=>$val)
	{
		$cfg['site'][$key] = trim($val);
//		$func->validCheck(체크할 값, 항목제목, 항목명, 체크타입)
		if($key == "lang") { $func->vaildCheck($val, "언어설정", "trim", "M"); }
		if($key == "frame") { $func->vaildCheck($val, "프레임셋", "trim", "M"); }
		if($key == "align") { $func->vaildCheck($val, "사이트 정렬", "trim", "M"); }
		if($key == "navGnb") { $func->vaildCheck($val, "글로벌 메뉴", "trim", "M"); }
		if($key == "navUnb") { $func->vaildCheck($val, "다국어 메뉴", "trim", "M"); }
		if($key == "navQnb") { $func->vaildCheck($val, "퀵 메뉴", "trim", "M"); }
		if($key == "openSkin") { $func->vaildCheck($val, "스킨정보", "trim", "M"); }
		if($key == "size") { $func->vaildCheck($val, "전체폭", "trim", "M"); }
		if($key == "sizeMsnb") { $func->vaildCheck($val, "메인좌폭", "trim", "M"); }
		if($key == "sizeMside") { $func->vaildCheck($val, "메인우폭", "trim", "M"); }
		if($key == "sizeSsnb") { $func->vaildCheck($val, "서브좌폭", "trim", "M"); }
		if($key == "sizeSside") { $func->vaildCheck($val, "서브우폭", "trim", "M"); }
		if($key == "ssl" && $val) $func->vaildCheck($val, "SSL보안포트");
		if($key == "encrypt") { $func->vaildCheck($val, "암호화 설정", null, "M"); }
	}

	$except = array(); //환경설정에서 제외될 값

//	저장되는 모듈 순서 _config.php - $cfg['solution']
	if($cfg['site']['skin'] != 'default')
	{
		$funculeSelect = $cfg['site']['modules'];
	}
	else
	{
		if($_SESSION['ulevel'] <= $cfg['operator'])
		{
			foreach ($cfg['solution'] as $key=>$val)
			{
				if($cfg['site'][$key])
				{
					$funculeSelect .= $cfg['site'][$key].",";
					@array_push($except, $cfg['site'][$key]);
				}
			}
			if(!preg_match('/\,$/', $funculeSelect)) { $funculeSelect .= ","; }

			#--- 모듈 셋팅
			foreach ($cfg['solution'] as $key=>$val)
			{
				if($cfg['site'][$key] && $db->checkTable($key."__") < 1 && $key != 'mdSitemap')
				{
					include __PATH__."modules/".$key."/manage/_sql.php";
					foreach($sql[$key] AS $value)
					{
						$value = str_replace('="/', '="'.$cfg['droot'], $value); //상위폴더 설정시
						$value = str_replace("_prefix_", null, $value);
						$db->query(trim($value));
					}
				}
			}
		}
	}
	@array_push($except, "type");
	@array_push($except, "skin");
	@array_push($except, "bsTimehour");
	@array_push($except, "bsTimemin");

	$cfg['site']['modules'] = "mdDocument,".preg_replace('/,$/', null, $funculeSelect);
	$cfg['site']['bsTime'] = strtotime(date('Y-m-d')." ".$cfg['site']['bsTimehour'].":".$cfg['site']['bsTimemin'].":00");
	$cfg['site']['info'] = $cfg['timeip'];

	$config = new Config($cfg);
	$config->configMake($except);

	if($config->configSave(__PATH__."_Site/".__DB__."/".$_POST['skin']."/cache/config.ini.php"))
	{
		$display->cacheCss($cfg['site']['skin']);
		$func->err("사이트 환경설정이 정상적으로 적용되었습니다.", "");
	}

}
?>
