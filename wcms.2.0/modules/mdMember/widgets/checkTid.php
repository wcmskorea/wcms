<?php
/* --------------------------------------------------------------------------------------
| 10억홈피 통합 아이디 사용여부 검색
|----------------------------------------------------------------------------------------
| Lastest : 2008-12-15
*/
if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER']))	{ print("[경고!] 정상적인 접근이 아닙니다."); }

/* --------------------------------------------------------------------------------------
| Classes...
*/
require_once $_SERVER['DOCUMENT_ROOT']."/_Lib/classSession.php";	//세션정보
require_once $_SERVER['DOCUMENT_ROOT']."/_Lib/classSock.php";		//소켓통신
$sess = new Sess();
usleep(500000);

if($sess->decode($_GET['type']) != "checkUserId")
{
		echo("잘못된 접근입니다.");
		echo('<script type="text/javascript">');
		echo('	$(document).ready(function(){');
		echo('		$("#userid").val("");');
		echo('		$("#checkId").removeClass("small_blue").addClass("small_orange");');
		echo('	});');
		echo('</script>');
		die();
}

if(!$_GET['idx'] || !preg_match("/^[a-z0-9\_]{4,15}$/", strtolower($_GET['idx'])))
{
		echo("4~15자의 영문으로 시작한 숫자, [ _ ]를 포함할 수 있습니다");
		echo('<script type="text/javascript">');
		echo('	$(document).ready(function(){');
		echo('		$("#id").val("");');
		echo('		$("#checkId").removeClass("small_blue").addClass("small_orange");');
		echo('		$("#checkuserid").val("");');
		echo('	});');
		echo('</script>');
}
else
{
	$sock = new Syssock("wcms", "LQc.ATNlSxK3A");
	$sock->type	= "memberCheck";
	$socket = $sock->memCheck($_GET['idx']);
	if($socket[code] == "00")
	{
		echo('<script type="text/javascript">');
		echo('	$(document).ready(function(){');
		echo('		$("#checkId").addClass("small_blue").removeClass("small_orange");');
		echo('		$("#checkuserid").val("Y");');
		echo('	});');
		echo('</script>');
		echo "<strong>[".$_GET['idx']."]</strong>는 사용 가능합니다";
	}
	else
	{
		echo('<strong>['.$_GET['idx'].']</strong>는 이미 <strong>사용중</strong>입니다');
		echo('<script type="text/javascript">');
		echo('	$(document).ready(function(){');
		echo('		$("#id").val("");');
		echo('		$("#checkId").removeClass("small_blue").addClass("small_orange");');
		echo('		$("#checkuserid").val("");');
		echo('		$("#id").select("");');
		echo('	});');
		echo('</script>');
	}
}
die();
?>
