<?php
require_once "../../../_config.php";
require_once __PATH__."/_Admin/include/commonHeader.php";

/* --------------------------------------------------------------------------------------
| SMS(Socket) 발송수량 체크
*/
$socket = $sock->smsCheck();
$smsCount = ($socket['code'] == '90') ? '<span class="colorRed">인증키 확인요망</span>' : '<span class="colorRed">'.number_format($socket['msg'])."</span>&nbsp;건";
?>
<ul>
	<li class="menu">ㆍ<strong><a href="javascript:;" onclick="$.dialog('../modules/mdSms/manage/input.php','&amp;type=<?php echo($sess->encode('smsInput'));?>',520,360)">문자발송 하기</a></strong></li>
	<li class="sect"></li>
	<li class="menu">ㆍ남은수량 :<span class="info small_gray colorRed"><?php echo($smsCount);?></span></li>
	<li class="sect"></li>
	<li class="menu">ㆍ<a href="javascript:;" onclick="$.insert('#module', '../modules/mdSms/manage/history.php','','300')">발송내역 조회</a></li>
	<li class="menu">ㆍ<a href="javascript:;" onclick="$.insert('#module', '../modules/mdSms/manage/info.php','','300')">메세지 관리</a></li>
  <?php
  if(is_file('./manual.pdf')) { print('<li class="sect"></li><li class="menu"><a href="'.$cfg['droot'].'modules/mdSms/manage/manual.pdf"><img src="'.$cfg['droot'].'image/button/btn_manual_module.jpg" width="162" height="21" title="매뉴얼 다운받기" /></a></li>'); }
  ?>
</ul>
<?php
require_once __PATH__."/_Admin/include/commonScript.php";
?>