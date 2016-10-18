<?php
/* ---------------------------
| 아이디 사용여부 검색
|-----------------------------
| Lastest : 2008-12-15
*/
require_once "../../../_config.php";

if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER'])) { echo("[경고!] 정상적인 접근이 아닙니다."); }
usleep($cfg[sleep]);

if($sess->decode($_GET[type]) != "checkUserPwd")
{
		echo("잘못된 접근입니다.");
		echo('<script type="text/javascript">');
		echo('	$(document).ready(function(){');
		echo('		$("#passwd").val("");');
		echo('		$("#checkPwd").removeClass("small_gray");');
		echo('		$("#checkPwd").addClass("small_orange");');
		echo('	});');
		echo('</script>');
		die();
}

$chkCount = 0; // 조합
$chkLength = 0; // 길이

//숫자
$pattern = '/[0-9]/i';
if(preg_match($pattern ,$_GET['idx'])) $chkCount++;

//영문
$pattern = '/[a-zA-Z]/i';
if(preg_match($pattern ,$_GET['idx'])) $chkCount++;

//특수문자
$pattern = '/[^0-9a-zA-Z]/i';
if(preg_match($pattern ,$_GET['idx'])) $chkCount++;

if(!$_GET['idx'] || strlen($_GET['idx']) < 8 || strlen($_GET['idx']) > 15) {
		echo('<script type="text/javascript">').PHP_EOL;
		echo('	$(document).ready(function(){').PHP_EOL;
		echo('		$("#passwd").val("");').PHP_EOL;
		echo('		$("#checkPwd").removeClass("small_gray");').PHP_EOL;
		echo('		$("#checkPwd").addClass("small_orange");').PHP_EOL;
		echo('		$("#checkPwd").val("");').PHP_EOL;
		echo('	});').PHP_EOL;
		echo('</script>').PHP_EOL;
		echo("영문/특수문자/숫자를 2가지 종류로 구성시 10자리 이상, 3가지 종류로 구성시 8자리 이상");
} else if($chkCount < 2) {
		echo('<script type="text/javascript">').PHP_EOL;
		echo('	$(document).ready(function(){').PHP_EOL;
		echo('		$("#passwd").val("");').PHP_EOL;
		echo('		$("#checkPwd").removeClass("small_gray");').PHP_EOL;
		echo('		$("#checkPwd").addClass("small_orange");').PHP_EOL;
		echo('		$("#checkPwd").val("");').PHP_EOL;
		echo('	});').PHP_EOL;
		echo('</script>').PHP_EOL;
		echo("영문자/숫자/특수문자 중 2종류이상 (현재 ".$chkCount."종류)으로 구성되어야 합니다.");
} else if($chkCount == 2) {
	if(strlen($_GET['idx']) < 10) {
		echo('<script type="text/javascript">').PHP_EOL;
		echo('	$(document).ready(function(){').PHP_EOL;
		echo('		$("#passwd").val("");').PHP_EOL;
		echo('		$("#checkPwd").removeClass("small_gray");').PHP_EOL;
		echo('		$("#checkPwd").addClass("small_orange");').PHP_EOL;
		echo('		$("#checkPwd").val("");').PHP_EOL;
		echo('	});').PHP_EOL;
		echo('</script>').PHP_EOL;
		echo("영문자/숫자/특수문자를 2종류로 구성한 경우 최소 10자리 이상 입력");
	} else {
		echo('<script type="text/javascript">').PHP_EOL;
		echo('	$(document).ready(function(){').PHP_EOL;
		echo('		$("#checkPwd").removeClass("small_orange");').PHP_EOL;
		echo('		$("#checkPwd").addClass("small_gray");').PHP_EOL;
		echo('		$("input[name=\'checkuserpwd\']").val("Y");').PHP_EOL;
		echo('	});').PHP_EOL;
		echo('</script>').PHP_EOL;
		echo "사용 가능합니다";
	}
} else if($chkCount > 2) {
	if(strlen($_GET['idx']) < 8) {
		echo('<script type="text/javascript">').PHP_EOL;
		echo('	$(document).ready(function(){').PHP_EOL;
		echo('		$("#passwd").val("");').PHP_EOL;
		echo('		$("#checkPwd").removeClass("small_gray");').PHP_EOL;
		echo('		$("#checkPwd").addClass("small_orange");').PHP_EOL;
		echo('		$("#checkPwd").val("");').PHP_EOL;
		echo('	});').PHP_EOL;
		echo('</script>').PHP_EOL;
		echo("영문자/숫자/특수문자를 3종류로 구성한 경우 최소 8자리 이상 입력");
	} else {
		echo('<script type="text/javascript">').PHP_EOL;
		echo('	$(document).ready(function(){').PHP_EOL;
		echo('		$("#checkPwd").removeClass("small_orange");').PHP_EOL;
		echo('		$("#checkPwd").addClass("small_gray");').PHP_EOL;
		echo('		$("input[name=\'checkuserpwd\']").val("Y");').PHP_EOL;
		echo('	});').PHP_EOL;
		echo('</script>').PHP_EOL;
		echo "사용 가능합니다";
	}
}
/*else {
	echo('<script type="text/javascript">');
	echo('	$(document).ready(function(){');
	echo('		$("#checkPwd").removeClass("small_orange");');
	echo('		$("#checkPwd").addClass("small_gray");');
	echo('		$("input[name=\'checkuserpwd\']").val("Y");');
	echo('	});');
	echo('</script>');
	echo "사용 가능합니다";
}*/
die();
?>
