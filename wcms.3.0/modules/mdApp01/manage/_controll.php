<?php
require_once "../../../_config.php";
# 리퍼러 체크
if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER'])) { $func->err("[1] 정상적인 접근이 아닙니다."); }

switch(trim($_GET[type]).trim($_POST[type]))
{
	case "list":
		include "./appList.php";
		break;
		
	case "detail":
		include "./appDetail.php";
		break;
		
	case "mpost": case "dpost":
		include "./appDetailPost.php";
		break;

//	선택 삭제
	case "clear" :
		if($member->checkPerm(0) === false) { $func->ajaxMsg($lang['doc']['notperm'],"","30"); }
		include  "./clear.php";
		break;

	default:
		/**
		 * 인코딩 타입 : Start
		 */
		switch($sess->decode($type))
		{
			//전체삭제
			case "articleClear" :
				if($member->checkPerm(0) === false) { $func->err($lang['doc']['notperm']); }
				include "./clearPost.php";
				break;
//			null일때
			default :
				include "./appList.php";
				break;
		}
		break;
}
?>
