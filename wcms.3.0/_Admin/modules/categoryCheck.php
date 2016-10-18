<?php
/*
* 카테고리 코드 사용여부 검사
* Last (2008.10.08 : 이성준)
*/
require_once "../../_config.php";
require_once "../include/commonHeader.php";

$cate = $_GET['parent'].$_GET['cate'];
if(!$cate || !preg_match("/^([0-9\_]*){3,15}$/", $cate) || $_GET['cate'] == '000')
{
		echo("3~15자 숫자만 입력하세요");
		echo('<script type="text/javascript">');
		echo('	$(document).ready(function(){');
		echo('		$("#cateCode2").val("");');
		echo('		$("#checkCate").removeClass("small_blue");');
		echo('		$("#checkCate").addClass("small_orange");');
		echo('	});');
		echo('</script>');
} 
else
{
	if($db->queryFetchOne(" SELECT COUNT(*) FROM `site__` WHERE skin='".$_GET['skin']."' AND cate<>'".$_GET['cated']."' AND cate='".$cate."' ") > 0)
	{
		echo('<strong>['.$cate.']</strong>는 이미 <strong>사용중</strong>');
		echo('<script type="text/javascript">');
		echo('	$(document).ready(function(){');
		echo('		$("#cateCode2").val("");');
		echo('		$("#checkCate").removeClass("small_blue");');
		echo('		$("#checkCate").addClass("small_orange");');
		echo('	});');
		echo('</script>');
	} 
	else
	{
		echo('<script type="text/javascript">');
		echo('	$(document).ready(function(){');
		echo('		$("#checkCate").addClass("small_blue");');
		echo('		$("#checkCate").removeClass("small_orange");');
		echo('	});');
		echo('</script>');
		echo "<strong>[".$cate."]</strong>는 사용가능";
	}
}
die();
?>
