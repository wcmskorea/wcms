<?php
require_once "../../_config.php";
require_once "../include/commonHeader.php";

switch(trim($_GET['type']).trim($_POST['type'])) 
{

	case "cateList":
		include "./category.php";
		break;

	case "cateReg": case "cateRegPost":
		include "./categoryRegist.php";
		break;

	case "cateClone": case "cateClonePost":
		include "./categoryClone.php";
		break;

	case "cateMod": case "cateModPost":
		/* 모듈 설정 로드 */
		$cfg['cate'] = $db->queryFetch(" SELECT mode FROM `site__` WHERE skin='".$_GET['skin']."' AND cate='".__CATE__."' ");
		if(!is_file("../../modules/".$cfg['cate']['mode']."/manage/_config.php")) 
		{
			$func->ajaxMsg("[".$_GET['skin']."]해당 모듈의 설정페이지가 존재하지 않습니다", "", 200);
		} 
		else 
		{
			include "../../modules/".$cfg['cate']['mode']."/manage/_config.php";
		}
		break;

	case "catePerm": case "catePermPost":
		include "./categoryPerm.php";
		break;

	case "cateSub":
		include "./categoryListSub.php";
		break;

	case "cateSort": case "cateSortPost":
		include "./categorySort.php";
		break;

	case "cateDel": case "cateDelPost":
		include "./categoryDelete.php";
		break;

	case "cateClear": 
		include "./categoryClear.php";
		break;

	case "cateClearPost":
		include "./categoryClearPost.php";
		break;

	case "cateCheck":
		include "./categoryCheck.php";
		break;

	case "display" : case "displayMove" :
		include "./display.php";
		break;
		
	case "displayList" :
		include "./displayList.php";
		break;
	
	case "displayClone": case "displayClonePost":
		include "./displayClone.php";
		break;

	case "displayDel" :
		if($_GET['sort'] && $_GET['position'])
		{
			//모드별 테이블 설정
			if($_GET['mode'] == 'test' || $_POST['mode'] == 'test')
			{
				$displayTable	= "display__".$_GET['skin'].$_POST['skin']."Test";
				$mode = "test";
			} else {
				$displayTable	= "display__".$_GET['skin'].$_POST['skin'];
				$mode = "real";
			}

			$Rows = $db->queryFetch(" SELECT * FROM `".$displayTable."` WHERE sort='".$_GET['sort']."' AND position='".$_GET['position']."' ORDER BY sort ASC ");
			$config = unserialize($Rows['config']);
			@unlink(__HOME__.$cfg['site']['lang']."/image"."/".strtolower($Rows['position'])."_skin".$Rows['sort'].".".$config['listing']); //스킨삭제
			$db->query(" DELETE FROM `".$displayTable."` WHERE sort='".$Rows['sort']."' AND position='".$Rows['position']."' ");
			$db->query(" OPTIMIZE TABLES `".$displayTable."` ");
			if($db->getAffectedRows() > 0) 
			{ 
				if(!preg_match('/\_Admin\//', $_SERVER['HTTP_REFERER']))
				{
					$func->ajaxMsg("Display(Clear) 유닛이 정상적으로 삭제되었습니다.", "$.insert('#skinSelector', '".$cfg['droot']."_Admin/modules/displayList.php?type=displayList&mode=design&skin=".$_GET['skin']."&position=".$_GET['position']."',null,300);$.dialogRemove();", 20); 
				} 
				else 
				{
					$func->ajaxMsg("Display 유닛이 정상적으로 삭제되었습니다.", "$.insert('#tabBody".$Rows['position']."', './modules/displayList.php?type=displayList&skin=".$_GET['skin']."&position=".$Rows['position']."&mode=".$_GET['mode']."',null,300);$.dialogRemove();", 20); 
				}
			}
		}
		break;
}
?>
