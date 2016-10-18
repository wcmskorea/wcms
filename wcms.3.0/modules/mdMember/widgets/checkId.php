<?php
/* ---------------------------
| 아이디 사용여부 검색
|-----------------------------
| Lastest : 2008-12-15
*/
require_once "../../../_config.php";

if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER'])) { echo("[경고!] 정상적인 접근이 아닙니다."); }
usleep($cfg[sleep]);

if($sess->decode($_GET[type]) != "checkUserId")
{
		echo("잘못된 접근입니다.");
		echo('<script type="text/javascript">');
		echo('	$(document).ready(function(){');
		echo('		$("#userid").val("");');
		echo('		$("#checkId").removeClass("small_blue");');
		echo('		$("#checkId").addClass("small_orange");');
		//echo('		$("#userid").select();');
		echo('	});');
		echo('</script>');
		die();
}

if(!$_GET['idx'] || is_numeric($_GET['idx']) || !preg_match("/^([a-z0-9\_]){5,15}$/", strtolower($_GET['idx'])))
{
		echo("5~15자의 영문(소문자)를 포함한 (숫자),( _ ) 혼용가능");
		echo('<script type="text/javascript">');
		echo('	$(document).ready(function(){');
		echo('		$("#id").val("");');
		echo('		$("#checkId").removeClass("small_blue");');
		echo('		$("#checkId").addClass("small_orange");');
		echo('		$("#checkuserid").val("");');
		//echo('		$("#userid").select();');
		echo('	});');
		echo('</script>');
}
else {
	$count = $db->queryFetchOne("SELECT COUNT(*) FROM `mdMember__account` WHERE id<>'".$_SESSION[uid]."' AND LOWER(id)='".strtolower($_GET['idx'])."'");
	if($count > 0 || strtolower($_GET['idx']) == 'master')	// master 계정 등록 오류 보완
	{
		echo('<strong>['.strtolower($_GET['idx']).']</strong>는 이미 <strong>사용중</strong>입니다');
		echo('<script type="text/javascript">');
		echo('	$(document).ready(function(){');
		echo('		$("#id").val("");');
		echo('		$("#checkId").removeClass("small_blue");');
		echo('		$("#checkId").addClass("small_orange");');
		echo('		$("input[name=\'checkuserid\']").val("");');
		echo('		$("#id").select();');
		echo('	});');
		echo('</script>');
	}
	else {
		echo('<script type="text/javascript">');
		echo('	$(document).ready(function(){');
		echo('		$("#checkId").addClass("small_blue");');
		echo('		$("#checkId").removeClass("small_orange");');
		echo('		$("input[name=\'checkuserid\']").val("Y");');
		echo('	});');
		echo('</script>');
		echo "<strong>[".strtolower($_GET['idx'])."]</strong>는 사용 가능합니다";
	}
}
die();
?>
