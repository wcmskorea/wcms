<?php
/* ---------------------------
| 아이디 사용여부 검색
|-----------------------------
| Lastest : 2008-12-15
*/
require_once "../../../_config.php";

if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER'])) { echo("[경고!] 정상적인 접근이 아닙니다."); }
usleep($cfg[sleep]);

if($sess->decode($_GET['type']) != "checkUserEmail")
{
		echo("잘못된 접근입니다.");
		echo('<script type="text/javascript">');
		echo('	$(document).ready(function(){');
		echo('		$(document).reload();');
		echo('	});');
		echo('</script>');
		die();
}

$_GET[idx] = ($cfg[charset] == 'euc-kr') ? iconv("UTF-8", "CP949", $_GET['idx']) : $_GET['idx'];
if(!$_GET['idx'] || !preg_match("/^[^@ ]+@[a-zA-Z0-9\-\.]+\.+[a-zA-Z0-9\-\.]/", $_GET['idx']))
{

		echo("이메일 형식이 잘못되었습니다.");
		echo('<script type="text/javascript">');
		echo('	$(document).ready(function(){');
		echo('		$("#email").val("");');
		echo('		$("#checkEmail").removeClass("small_blue").addClass("small_orange");');
		echo('		$("#checkuseremail").val("");');
		echo('	});');
		echo('</script>');
}
else
{
	//$sock = new Syssock("wcms", "LQc.ATNlSxK3A");
	//$sock->type	= "memberCheck";
	//$socket = $sock->memCheck($_GET['idx'], null, "Email");
	//if($socket[code] == "00")
	$count = $db->queryFetchOne("SELECT COUNT(*) FROM `mdMember__account` WHERE id<>'".$_SESSION['uid']."' AND email='".$_GET['idx']."'");
	if($count < 1)
	{
		echo('<script type="text/javascript">');
		echo('	$(document).ready(function(){');
		echo('		$("#checkEmail").addClass("small_blue").removeClass("small_orange");');
		echo('		$("#checkuseremail").val("Y");');
		echo('	});');
		echo('</script>');
		echo "등록 가능한 메일입니다.";
  }
  else
  {
  		echo('이미 <strong>등록된</strong> 이메일 입니다');
		echo('<script type="text/javascript">');
		echo('	$(document).ready(function(){');
		echo('		$("#email").val("");');
		echo('		$("#checkEmail").removeClass("small_blue").addClass("small_orange");');
		echo('		$("#checkuseremail").val("");');
		echo('		$("#email").select();');
		echo('	});');
		echo('</script>');
  }
}
die();
?>
