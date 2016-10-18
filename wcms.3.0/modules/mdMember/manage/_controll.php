<?php
/* 환경설정 파일 */
require_once "../../../_config.php";
/* 리퍼러 체크 */
if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER'])) { $func->err("[1] 정상적인 접근이 아닙니다."); }

switch(trim($_POST['type']).trim($_GET['type']))
{
	case "config":
		include "./config.php";
		break;

    /**
	 * 회원등급별 정책
	 */
	case "configLevelPolicy" :

		if($_GET['del'] == "yes")
		{
			$db->query(" DELETE FROM `mdShop__grades` WHERE seq='".$_GET['idx']."' ");
			if($db->getAffectedRows() > 0)
			{
				$db->query(" OPTIMIZE TABLE `mdShop__grades` ");
				$func->setLog(__FILE__, "회원등급 정보 삭제", true);
				$func->ajaxMsg("회원등급 정보가 삭제되었습니다.", "$.insert('#tabBody07','./site/shopGrade.php',null,200)", 20);
			}
			else
			{
				$func->ajaxMsg("회원등급 정보가 변경된 내용이 없거나, 적용 실패입니다.", "", 20);
			}
		}
		else
		{
			//넘어온 값과 변수 동기화 및 validCheck
			foreach($_POST AS $key=>$val)
			{
				$db->data[$key] = trim($val);
				#$func->validCheck(체크할 값, 항목제목, 체크타입, 필수항목, 항목명(타겟))
				if($key == "level")       { $func->vaildCheck($val, "등급순위", "trim");}
				if($key == "addPointRate"){ $func->vaildCheck($val, "추가적립", "trim");}
				if($key == "discountRate"){ $func->vaildCheck($val, "추가할인", "trim");}
				if($key == "summary")     { $func->vaildCheck($val, "관련설명", "trim");}
				if($key == "startPayment"){ $func->vaildCheck($val, "실적범위[최소금액]", "trim");}
				if($key == "endPayment")  { $func->vaildCheck($val, "실적범위[최대금액]", "trim");}
			}

			$db->data['seq']	= ($_POST['idx']) ? $_POST['idx'] : NULL;
			$db->data['modifier'] 	= $_SESSION['uid'];

			if($db->sqlInsert("`mdShop__grades`","REPLACE", false))
			{
				$func->setLog(__FILE__, "회원등급별 정책정보 변경", true);
				$func->err("회원등급 정보가 정상적으로 적용되었습니다.", "parent.$.tabMenu('tab04','#tabBody04','../modules/mdMember/manage/configLevelPolicy.php',null,200)");
			}
			else
			{
				$func->err("회원등급 정보가 변경된 내용이 없거나, 적용 실패입니다.");
			}
		}
		break;

    case "list":
		include "./list.php";
		break;

	case "insert":
		include "./detail.php";
		break;

	case "post":
		include "./detailPost.php";
		break;

	case "detail":
		include "./detail.php";
		break;

	case "message": case "messagePost": case "messageDel":
		include "./detailMessages.php";
		break;

	case "mileage": case "mileagePost": case "mileageDel":
		include "./detailMileages.php";
		break;

	case "delete": case "delComplete":
		include "./delete.php";
		break;

	case "analySex":
		include "./analyticsSex.php";
		break;

	case "analyAge": 
		include "./analyticsAge.php";
		break;

	case "analyRegion":
		include "./analyticsRegion.php";
		break;

	case "order": case "orderPost":
		include "./detailOrder.php";
		break;

	case "log": case "logPost":
		include "./detailLog.php";
		break;

	default:
		include "./list.php";
		break;
}
?>
