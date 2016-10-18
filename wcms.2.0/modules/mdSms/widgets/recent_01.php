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
$this->result .= '<div id="sms" class="cube" style="height:'.$this->height.'px;"><div class="line">';
$this->result .= '
<div id="sms_box" style="height:200px; padding:5px;">
  <form name="smsBox01" method="post" action="'.$this->cfg[droot].'modules/mdSms/smsPost.php" enctype="multipart/form-data" target="hdFrame">
  <input type="hidden" name="type" value="'.$sess->encode('sms^@^Post').'" />
  <input type="hidden" name="mode" value="sms" />

  <h3>'.$this->cateName.'<span class="keeping"><span id="msgBytesPop" class="red">0</span><span>/80byte</span></span></h3>
  <ul class="sms_lcd">
    <li>
    <textarea name="mesg" rows="5" cols="16" id="tbMessage" onKeyUp="byteCheck(this,\'msgBytesPop\',80);" onfocus="byteCheck(this,\'msgBytesPop\',80);" class="sms_content" style="width:120px; height:76px; padding:5px; word-break:break-all; ime-mode:active" title="전송할 상담내용"></textarea>
    </li>
    <li><span><input type="text" name="sender" title="연락받을 전화번호" class="sms_sender" style="width:128px;" /></span></li>
    <li style="padding-top:10px;"><span class="button bblack strong"><button type="submit">문자상담 신청</button></span></li>
  </ul>
  </form>
</div>
';
$this->result .= '</div></div>';
