<?php
/* ======================================================================================
| SMS(Socket) 문자 발송
*/
require_once "../../_config.php";

# 리퍼러 체크
if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER']))		$func->err("[경고!] 정상적인 접근이 아닙니다.");
if($_SERVER['REQUEST_METHOD'] == 'GET' )										$func->err("[경고!] 정상적인 접근이 아닙니다.");
if($sess->decode($_POST['type']) != "sms^@^Post") 					$func->err("[경고!] 정상적인 접근이 아닙니다.");
if(!in_array('mdSms',$cfg['modules']))										  $func->err("문자 서비스를 이용하고 있지 않습니다.");

if($sess->decode($_POST['type']) == "sms^@^Post" && $_POST['mode'] == 'sms')
{
  foreach($_POST AS $key=>$val)
  {
    ${$key} = trim(strip_tags($val));
    # POST값 vaild check
    # $func->validCheck(체크할 값, 항목제목, 항목명, 체크타입)
    if($key == "sender")  $func->vaildCheck($val, "연락받을 전화번호", $key, "phone" ,"M");
    if($key == "mesg")    $func->vaildCheck($val, "상담내용", $key, "trim" ,"M");
  }
  $mesg             = stripslashes($mesg);
  if($rdoSend == 2) $sock->date = strtotime($sdate." ".$shour.":".$smin.":00");
  $sock->send       = $sender;
  $socket           = $sock->smsSend($cfg['site']['mobile'], $mesg);
  if($cfg['charset'] == 'euc-kr') {
    $func->err($socket['msg'], "parent.$('textarea[name=mesg]').val('');");
  } else
  {
    $func->err(iconv("CP949", "UTF-8", $socket['msg']), "parent.$('textarea[name=mesg]').val('');");
  }
} else
{
  $func->err("[경고!] 정상적인 접근이 아닙니다.");
}
?>
