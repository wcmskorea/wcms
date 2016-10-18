<?php
/**
 * 휴대폰 문자를 이용한 본인인증 서비스
 * 
*/
require_once "../../../_config.php";

if($sess->decode($_GET['type']) == "checkCertPost") 
{
	@session_start();
	if(!$func->checkModule('mdSms')) 
	{
		$func->ajaxMsg("문자 서비스를 이용하고 있지 않습니다.", "", 20);
	} else 
	{
		//valid check
		if(!$_GET['receiver']) { $func->ajaxMsg("휴대전화 번호를 입력하세요", "", 20); }
	    $func->vaildCheck($_GET['receiver'], "휴대전화 번호", "mobile", "M");
	
	    //인증코드 생성 및 세션저장
	    $mobileCode = rand(11432, 99765);
	    $mobile = str_replace("-", "", $_GET['receiver']);
			session_unregister('mobileCode');
	    session_unregister('mobile');
	    $_SESSION['mobileCode'] = $mobileCode;
	    $_SESSION['mobile']		= $mobile;

			if((!$_SESSION['ulevel'] || $_SESSION['ulevel'] > $cfg['operator']) && $sock->smsDelay() >= 3)
			{
				$func->ajaxMsg("당일 인증횟수 3회 초과하셨습니다! 다음날 시도해주세요.", "$.dialogRemove();", 20);
			}
	
	    //휴대폰 전송
	    $mesg				= "본인 확인 인증코드는 [".$mobileCode."]입니다. -".$cfg['site']['siteName']."-";
			$sock->send	= $cfg['site']['phone'];
	    $socket			= $sock->smsSend($mobile, $mesg);
			//var_dump($sock);
			if($socket['code'] == '01')
			{
    		$func->ajaxMsg("['".$_GET['receiver']."']번호로 인증코드를 전송하였습니다.", "$('#mobile').val('".$_GET['receiver']."').attr('disabled','true'); $.dialogRemove();","20");
			} else {
				$func->ajaxMsg("[".$socket['code']."]전송 실패! ".$socket['msg']."", "$.dialogRemove();","20");
			}
  	}
} else if(!$func->checkModule('mdSms')) 
{
  $func->ajaxMsg("문자관리 모듈이 설치되지 않았습니다.", "", 20);
}
?>
<div id="modal">
	<form name="frmMember" method="post" action="<?php echo($cfg['droot']);?>modules/mdMember/addon/checkCertSms.php" enctype="multipart/form-data" onsubmit="return $.checkFarm(this, '<?php echo($_SERVER['PHP_SELF']);?>', 'messege');">
	<input type="hidden" name="type" value="<?php echo($sess->encode("checkCertPost"));?>" />
    <div class="menu_violet"><p>- 휴대폰 인증 서비스 (일일 3회제한) -</p></div>
    <div class="pd10 center"><span>현재 소지한 휴대전화의 번호를 입력 후 전송받기를 클릭하세요!</span></div>
    <div class="input center"><input type="text" id="receiver" name="receiver" title="본인 휴대전화 번호" class="input_gray center" style="width:150px;" value="<?php echo($_GET['mobile']);?>" readonly />&nbsp;<span class="btnPack violet small"><button type="submit">전송받기</button></span>
    </div>
	</form>
</div>
<script type="text/javascript">
//<!CDATA[
$(document).ready(function() {
	$("input, textarea").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active");});
	$("#ajax_display").css('background','#fff');
	$('#receiver').focus().toggleClass("input_active").select();
});
//]]>
</script>
