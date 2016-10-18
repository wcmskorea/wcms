<?php
require_once "../../../_config.php";
//리퍼러 체크
if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER'])) { $func->err("[경고!] 정상적인 접근이 아닙니다."); }

switch(trim($_GET['type']).trim($_POST['type'])){
	case "list":
		include "./list.php";
	  break;
	  
	case "skinList":
		include "./listSkin.php";
	  break;
	  
	case "post":
		include "./post.php";
	  break;
	  
	case "delete":
		include "./delete.php";
	  break;
	  
	default:
		include "./list.php";
	  break;
}
?>
