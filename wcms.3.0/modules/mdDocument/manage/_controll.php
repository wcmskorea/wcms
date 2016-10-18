<?php
/**
 * 문서모듈 - Controller
 */
require_once "../../../_config.php";
					
switch(trim($_POST['type']).trim($_GET['type']))
{
	case "list":
		include "./list.php";
		break;
		
	case "comment":
		include "./listComment.php";
		break;
	
	case "file":
		include "./listFile.php";
		break;

	case "move":
		include "./move.php";
		break;

	case "movePost":
		include "./movePost.php";
		break;

	case "clear":
		include "./clear.php";
		break;

	case "clearPost" :
		include "./clearPost.php";
		break;

	default :
		include "./list.php";
		break;
}
?>
