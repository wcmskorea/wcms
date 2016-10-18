<?php
/* ---------------------------
| 휴대전화 사용여부 검색
|-----------------------------
| Lastest : 2012년 4월 17일 화요일
*/
require_once "../../../_config.php";

if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER'])) { echo("[경고!] 정상적인 접근이 아닙니다."); }
usleep($cfg[sleep]);

if($sess->decode($_GET['type']) != "checkUserMobile")
{
		echo("잘못된 접근입니다.");
		echo('<script type="text/javascript">');
		echo('	$(document).ready(function(){');
		echo('		$(document).reload();');
		echo('	});');
		echo('</script>');
		die();
}

$_GET['idx'] = ($cfg['charset'] == 'euc-kr') ? iconv("UTF-8", "CP949", $_GET['idx']) : $_GET['idx'];
if(!$_GET['idx'] || !preg_match("/^(01[01346-9])-?([1-9]{1}[0-9]{2,3})-?([0-9]{4})$/", $_GET['idx']))
{

		echo("휴대전화 형식이 잘못되었습니다.");
		echo('<script type="text/javascript">');
		echo('	$(document).ready(function(){');
		echo('		$("#mobile").val("");');
		echo('		$("#checkMobile").removeClass("small_blue").addClass("small_orange");');
		echo('		$("#checkusermobile").val("");');
		echo('	});');
		echo('</script>');
}
else
{
	//$sock = new Syssock("wcms", "LQc.ATNlSxK3A");
	//$sock->type	= "memberCheck";
	//$socket = $sock->memCheck($_GET['idx'], null, "Email");
	//if($socket[code] == "00")
	$count = $db->queryFetchOne("SELECT COUNT(*) FROM `mdMember__info` WHERE id<>'".$_SESSION['uid']."' AND REPLACE(mobile,'-','')='".str_replace("-", null, $_GET['idx'])."'");
	if($count < 1)
	{
		echo('<script type="text/javascript">');
		echo('	$(document).ready(function(){');
		echo('		$("#checkMobile").addClass("small_blue").removeClass("small_orange");');
		echo('		$("#checkusermobile").val("Y");');
		echo('	});');
		echo('</script>');
		echo "등록 가능한 휴대폰 번호입니다.";
  }
  else
  {
  		echo('이미 <strong>등록된</strong> 휴대폰 번호입니다');
		echo('<script type="text/javascript">');
		echo('	$(document).ready(function(){');
		echo('		$("#mobile").val("");');
		echo('		$("#checkMobile").removeClass("small_blue").addClass("small_orange");');
		echo('		$("#checkusermobile").val("");');
		echo('		$("#mobile").select();');
		echo('	});');
		echo('</script>');
  }
}
die();
?>
