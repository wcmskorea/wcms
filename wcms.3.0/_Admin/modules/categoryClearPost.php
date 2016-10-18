<?php
require_once "../include/commonHeader.php";
if($_POST['type'] == "cateClearPost")
{
	$func->checkRefer("POST");

	$_POST['select'] = substr($_POST['select'], 0, -1);
	$_POST['select'] = explode(',',$_POST['select']);
	//파라메터 검사
	if(count($_POST['select']) < 1) { $func->err($lang['doc']['notmust'], "window.history.back()"); }

	//해당정보 삭제
	$DeleteData = 0; //삭제 건수

	foreach($_POST['select'] AS $key=>$value)
	{
		$db->query(" SELECT * FROM `site__` WHERE skin='".$_POST['skin']."' AND cate like '".$value."%' ");
		while($Rows = $db->fetch())
		{
			//정렬순서 재정리
			if(strlen($Rows['cate']) == 3) 
			{
				$db->query(" UPDATE `site__` SET sort=sort-'1' WHERE skin='".$Rows['skin']."' AND LENGTH(cate)='3' AND sort>'".$Rows['sort']."' ",2);
			}
			else 
			{
				$db->query(" UPDATE `site__` SET sort=sort-'1' WHERE skin='".$Rows['skin']."' AND LENGTH(cate)='".strlen($Rows['cate'])."' AND SUBSTRING(cate,1,".intval(strlen($Rows['cate'])-3).")='".substr($Rows['cate'],0,strlen($Rows['cate'])-3)."' AND sort>'".$Rows['sort']."' ",2);

				//상위 카테고리 갯수 업데이트
				$db->query(" UPDATE `site__` SET child=child-'1' WHERE skin='".$Rows['skin']."' AND cate='".substr($Rows['cate'],0,strlen($Rows['cate'])-3)."' ",2);
			}
			
			// 해당 카테고리 연결 모듈에 대한 데이터 삭제.
			if(substr($Rows['mode'], 0, 2) == 'md') 
			{
				$db->queryForce(" DELETE FROM `".$Rows['mode']."__` WHERE skin='".$Rows['skin']."' AND cate = '".$Rows['cate']."' ",2);
				$db->queryForce(" OPTIMIZE TABLES `".$Rows['mode']."__` ",2);
			}
			
			$db->query(" DELETE FROM `site__` WHERE skin='".$Rows['skin']."' AND cate = '".$Rows['cate']."' ",2);
			$func->setLog(__FILE__, "카테고리 (".$Rows['cate'].")삭제");

			$DeleteData++;
		}
	}

	// 테이블 최적화
	if($DeleteData > 0)
	{
		$db->query(" OPTIMIZE TABLE `site__`");
		
		//XML 업데이트
		$display->cacheXml($_POST['skin']);

		$func->err("카테고리 정보가 정상적으로 삭제되었습니다.", "parent.$.insert('#tabBody".$_POST['skin']."', './modules/categoryList.php?skin=".$_POST['skin']."',null,300); parent.$.dialogRemove();");
	} else {

		//알림창
		$func->err("카테고리 삭제 실패하였습니다.", "parent.$.insert('#tabBody".$_POST['skin']."', './modules/categoryList.php?skin=".$_POST['skin']."',null,300); parent.$.dialogRemove();");
	}
}
?>