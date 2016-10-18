<?php
/* --------------------------------------------------------------------------------------
| SMS빠른상담 (심플형) 위젯
|----------------------------------------------------------------------------------------
*/
$width				= preg_replace('/px|%/',null,$this->config['width']);
$height				= preg_replace('/px|%/',null,$this->config['height']);

$this->result .= '<div id="sms">';
$this->result .= '
<div id="sms_box02" style="height:'.$height.'px;">
  <form name="smsBox02" method="post" action="'.$this->cfg['droot'].'modules/mdSms/smsSimplePost.php" enctype="multipart/form-data" onsubmit="return validCheck(this);">
  <input type="hidden" name="type" value="'.$GLOBALS['sess']->encode('sms^@^Post').'" />
  <input type="hidden" name="mode" value="smsSimple" />
  <ul class="sms_lcd">
    <li><span><input type="text" name="mesg" title="신청자명" class="input_text required" style="width:90px;" req="required" /></span></li>
    <!--<li><textarea name="mesg" rows="5" cols="16" class="sms_content" style="width:82px; height:45px; padding:5px; word-break:break-all; ime-mode:active" title="전송할 상담내용"></textarea></li>-->
		<li><span><input type="text" name="sender" title="연락받을 전화번호" class="input_text required" style="width:90px;" req="required" phone="true" /></span></li>
    <li><input type="image" src="/user/default/image/button/btn_sms_send.gif" width="94" alt="전송하기" /></li>
  </ul>
  </form>
</div>
';
$this->result .= '</div>';
$this->result .= '<script type="text/javascript" src="/common/js/validation.js"></script>';