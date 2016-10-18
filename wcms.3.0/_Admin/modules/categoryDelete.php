<?php
/**
 * 카테고리별 삭제
 * Last (2008.10.16 : 이성준)
 */
require_once "../include/commonHeader.php";

if($_GET['type'] == "cateDel")
{
	$Rows = $db->queryFetch(" SELECT * FROM `site__` WHERE skin='".$_GET['skin']."' AND cate='".__CATE__."' ");
	if($Rows) 
	{
//		정렬순서 재정리
		if(strlen($Rows['cate']) == 3) 
		{
			$db->query(" UPDATE `site__` SET sort=sort-'1' WHERE skin='".$Rows['skin']."' AND LENGTH(cate)='3' AND sort>'".$Rows['sort']."' ");
		}
		else 
		{
			$db->query(" UPDATE `site__` SET sort=sort-'1' WHERE skin='".$Rows['skin']."' AND LENGTH(cate)='".strlen($Rows['cate'])."' AND SUBSTRING(cate,1,".intval(strlen($Rows['cate'])-3).")='".substr($Rows['cate'],0,strlen($Rows['cate'])-3)."' AND sort>'".$Rows['sort']."' ");

			//상위 카테고리 갯수 업데이트
			$db->query(" UPDATE `site__` SET child=child-'1' WHERE skin='".$Rows['skin']."' AND cate='".substr($Rows['cate'],0,strlen($Rows['cate'])-3)."' ");
		}
		
		// 해당 카테고리 연결 모듈에 대한 데이터 삭제.
		if(substr($Rows['mode'], 0, 2) == 'md') 
		{
			$db->queryForce(" DELETE FROM `".$Rows['mode']."__` WHERE cate like '".$Rows['cate']."%' ");
			$db->queryForce(" OPTIMIZE TABLES `".$Rows['mode']."__` ");
		}
		
		$db->query(" DELETE FROM `site__` WHERE skin='".$Rows['skin']."' AND cate like '".$Rows['cate']."%' ");
		$db->query(" OPTIMIZE TABLE `site__`");

		//XML 업데이트
		$display->cacheXml($Rows['skin']);
		
		$func->setLog(__FILE__, "카테고리 (".__CATE__.")삭제 성공");
		$func->ajaxMsg("카테고리 정보가 정상적으로 삭제되었습니다.","$.insert('#tabBody".$Rows['skin']."', './modules/categoryList.php?skin=".$Rows['skin']."'); $.dialogRemove();", 20);
	} 
	else 
	{
		$func->ajaxMsg("카테고리 정보가 존재하지 않습니다.","", 20);
	}
} 
else 
{
	$func->ajaxMsg("잘못된 경로로 접속하셨습니다.","", 20);
}
?>
