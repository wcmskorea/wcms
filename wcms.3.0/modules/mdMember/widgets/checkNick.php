<?php
/* ---------------------------
| 아이디 사용여부 검색
|-----------------------------
| Lastest : 2008-12-15
*/
require_once "../../../_config.php";
if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER'])) { print("[경고!] 정상적인 접근이 아닙니다."); }
usleep($cfg['sleep']);

if($sess->decode($_GET[type]) != "checkUserNick") {
		print("잘못된 접근입니다.");
		print('<script type="text/javascript">');
		print('	$(document).ready(function(){');
		print('		$(document).reload();');
		print('	});');
		print('</script>');
		die();
}

$_GET[idx] = ($cfg[charset] == 'euc-kr') ? iconv("UTF-8", "CP949", $_GET[idx]) : $_GET[idx];
if(!$_GET['idx'] || !preg_match("/^([가-힣a-zA-Z0-9]){4,30}$/", $_GET[idx])) {

		print("3~15자의 한글이나 영문으로 작성해주십시오");
		print('<script type="text/javascript">');
		print('	$(document).ready(function(){');
		print('		$("#nick").val("");');
		print('		$("#checkNick").removeClass("small_blue");');
		print('		$("#checkNick").addClass("small_orange");');
		print('		$("input[name=\'nick\']").val("");');
		//print('		$("#nick").select();');
		print('	});');
		print('</script>');
}
else {

	if($db->QueryFetchOne("SELECT COUNT(*) FROM `mdMember__account` WHERE id<>'".$_SESSION[uid]."' AND LOWER(nick)='".$_GET['idx']."'") > 0) {
		print('<strong>['.$_GET['idx'].']</strong>는 이미 <strong>사용중</strong>입니다');
		print('<script type="text/javascript">');
		print('	$(document).ready(function(){');
		print('		$("#nick").val("");');
		print('		$("#checkNick").removeClass("small_blue");');
		print('		$("#checkNick").addClass("small_orange");');
		print('		$("input[name=\'nick\']").val("");');
		print('		$("#nick").select();');
		print('	});');
		print('</script>');
  }
  else {
		print('<script type="text/javascript">');
		print('	$(document).ready(function(){');
		print('		$("#checkNick").addClass("small_blue");');
		print('		$("#checkNick").removeClass("small_orange");');
		print('	});');
		print('</script>');
		echo "<strong>[".$_GET['idx']."]</strong>는 사용 가능합니다";
  }

}
die();
?>
