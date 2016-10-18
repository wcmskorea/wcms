<?php
/* --------------------------------------------------------------------------------------
| 배너모듈 (가로형) : Add-on
|----------------------------------------------------------------------------------------
| Relationship : ./addon/display.php
| Last (2009-11-14 : 이성준)
|----------------------------------------------------------------------------------------
| $this->disp 배열값
| [0-3]box공백, [4-5]노출개수/공백, [6-8]노출타입/배속/지연, [9-11]타이틀/제목/날짜 노출
*/
$this->result .= '<div id="sms" class="banner" style="height:'.$this->height.'px;"><div class="line">';
$this->result .= '
<div id="sms_box02">
  <form name="smsBox02" method="post" action="'.$this->cfg[droot].'modules/mdSms/smsPost.php" enctype="multipart/form-data" target="hdFrame">
  <input type="hidden" name="type" value="'.$GLOBALS['sess']->encode('sms^@^Post').'" />
  <input type="hidden" name="mode" value="sms" />
  <ul class="sms_lcd">
    <li><span><input type="text" name="sender" title="연락받을 전화번호" class="sms_sender" style="width:128px;" /></span></li>
    <li><textarea name="mesg" rows="5" cols="16" class="sms_content" style="width:120px; height:45px; padding:5px; word-break:break-all; ime-mode:active" title="전송할 상담내용"></textarea></li>
    <li><input type="image" src="/user/kr/image/button/btn_sms_send.gif" width="74" height="22" alt="전송하기" onerror="this.src=\'/skin/A/01/kr/image/button/btn_sms_send.gif\'" /></li>
  </ul>
  </form>
</div>
';
$this->result .= '</div></div>';
