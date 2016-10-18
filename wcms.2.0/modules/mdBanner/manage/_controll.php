<?php
require "../../../_config.php";
require $_SERVER['DOCUMENT_ROOT']."/_Admin/include/commonHeader.php";

switch(trim($_GET['type']).trim($_POST['type']))
{
	case "list": case"listMove":      include "./list.php";           break;
	case "insert":                    include "./insert.php";         break;
	case "insertPost":                include "./insertPost.php";     break;
	case "delete":                    include "./delete.php";         break;
	default:                          include "./list.php";           break;
}
?>
