<?php
require_once $_SERVER[DOCUMENT_ROOT]."/_config.php";
# 리퍼러 체크
if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER[HTTP_REFERER]))		$func->AjaxMsg("[경고!] 정상적인 접근이 아닙니다.","",120);
if($_SERVER[REQUEST_METHOD] == 'GET' )										$func->AjaxMsg("[경고!] 정상적인 접근이 아닙니다.","",120);
if(!in_array('cpSms',$cfg[components]))										$func->AjaxMsg("문자 서비스를 이용하고 있지 않습니다.","",120);
?>

<form name="popSms" method="post" action="" enctype="multipart/form-data" onsubmit="return $.checkFarm(this, './addon/cpSms/sms_post.php' ,'msg');">
<input type="hidden" name="type" value="<?=$sess->encode('smsPost')?>" />
<div id="sms_pop"  title="마우스로 드래그 하여 이동이 가능합니다">
	<div class="sms_top">&nbsp;</div>
	<div class="sms_bg_1"><span style="margin-left:33px;"><img src="/image/sms/sms_icon.gif" /></span></div>
	<div class="sms_bg_2"><div><span id="msgBytes">0</span>/80 Byte</span></div></div>
	<div class="sms_bg_3">
		<textarea name="msg01" onKeyUp="byteCheck(this,'msgBytes',80);" onfocus="byteCheck(this,'msgBytes',80);"  id="smsMgs" style="width:147px;height:150px;padding:3px;margin:auto;font-size:12px;color:black;border:0" class="input_gray" req="required" title="전송 메세지" trim='trim' ></textarea>
	</div>
	<div class="sms_bg_4"><p><img src="/image/sms/sms_send.gif" /></p>
	<p><input type="text" name="sender01" opt="phone" req="required" title="연락처" trim='trim' class="input_gray" style="width:125px;"/></p></div>
	<div class="sms_bg_5" style="cursor:pointer;_cursor:hand;"><input type="image" src="/image/sms/sms_btn.gif" border="0"></a></div>
	<div class="sms_bottom">&nbsp;</div>
</div>
</form>
<script type="text/javascript">
$(document).ready(function()
{
	$("input[type=text],input[type=password],textarea").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active"); });
});
</script>