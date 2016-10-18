<?php
require_once "../../../_config.php";

//리퍼러 체크
if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER'])) { $func->err("[경고!] 정상적인 접근이 아닙니다."); }

switch(trim($_GET['type']).trim($_POST['type']))
{
	case "logVisitorDay":
		include "./logVisitor_day.php";
		break;
		
	case "logVisitorIp":
		include "./logVisitor_ip.php";
		break;
		
	case "logVisitorRefer":
		include "./logVisitor_refer.php";
		break;

	case "logRegister":
		include "./logRegister.php";
		break;
	
	case "logKeyword":
		include "./logVisitor_keyword.php";
		break;
		
	default:
		include "./logVisitor.php";
		break;
}
?>
