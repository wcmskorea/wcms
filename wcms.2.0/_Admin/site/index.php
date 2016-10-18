<?php
require_once "../../_config.php";
require_once "../include/commonHeader.php";

switch(trim($_GET['type']).trim($_POST['type']))
{
	case "info":
		include "./info.php";
		break;

	case "infoSystemPost": case "infoBizPost":
		include "./infoPost.php";
		break;

	case "code":
		include "./code.php";
		break;

	case "codeLeft": case "infoCodePost": case "codeRight": case "codeDelete": case "cateDelete":
		include "./infoCodePost.php";
		break;

	case "dialog":
		include "./codeDialog.php";
		break;

	case "site":
		include "./site.php";
		break;

	case "sitePost": case "layoutPost":
		include "./siteConfigPost.php";
		break;

	case "display": case "dspMove":
		include "./display.php";
		break;

	case "displayPost":
		include "./displayPost.php";
		break;

	case "btnPost":
		include "./makeBtn.php";
		break;

	case "displayCache":
		/**
		 * 스킨 캐시저장
		 */
		$display->cfg['skin'] = $_GET['skin'];
		$display->setCache($_GET['position']);
		$func->ajaxMsg("정상적으로 생성·갱신 되었습니다.", "$.setDisplay('".$_GET['skin']."', '".$_GET['position']."', '".__CATE__."')", 20);
		break;

	case "displayCacheDel":
		if(__CATE__)
		{
			$db->query(" SELECT cate FROM `site__` WHERE skin='".$_GET['skin']."' GROUP BY cate ASC ");
			while($Rows = $db->fetch())
			{
				@unlink(__PATH__."_Site/".__DB__."/".$_GET['skin']."/cache/display/".$_GET['position'].".".$Rows['cate'].".html");
			}
		}
		else
		{
			@unlink(__PATH__."_Site/".__DB__."/".$_GET['skin']."/cache/display/".$_GET['position'].".html") or ("실패");
		}
		$func->ajaxMsg("정상적으로 캐시가 삭제 되었습니다.", "$.setDisplay('".$_GET['skin']."', '".$_GET['position']."', '".__CATE__."')", 20);
		break;

	case "xmlPost":
		/**
		 * 네비게이션 XML 생성 모듈
		 */
		$display->cacheXml($_GET['skin']);
		$func->ajaxMsg("정상적으로 업데이트 되었습니다.","", 20);
		break;

	case "allStylePost":
		/**
		 * 모든 계정 스타일시트 업데이트
		 */
		$n = 0;
		foreach ($func->dirOpen(__PATH__."/skin/") as $key => $val)
		{
			$display->cacheCss($val);
			$n++;
		}
		$func->ajaxMsg("[".$n."]개 스킨의 파일이 모두 업데이트 되었습니다.","", 20);
		break;

	case "stylePost":
		/**
		 * 개별 스타일시트 업데이트
		 */
		$display->cacheCss($_GET['skin']);
		if($_GET['mode'] == 'design')
		{
			$func->ajaxMsg("정상적으로 업데이트 되었습니다.","location.reload();", 20);
		} else
		{
			$func->ajaxMsg("정상적으로 업데이트 되었습니다.","", 20);
		}
		break;

	case "updateSql": case "updateSqlPost":
		/**
		 * 데이터베이스 업데이트 모듈
		 */
		include "./updateSql.php";
		break;

	default:
		$func->ajaxMsg("Type 인코딩 에러","",30);
		break;
}
?>
