<?php
require_once  "../../../_config.php";
#--- 리퍼러 체크
if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER[HTTP_REFERER]))  { $func->err("[경고!] 정상적인 접근이 아닙니다."); }
if($_SERVER[REQUEST_METHOD] == 'GET')                   { $func->err("[경고!] 정상적인 접근이 아닙니다."); }
if(!in_array('mdSms',$cfg[modules]))                    { $func->err("문자 서비스를 이용하고 있지 않습니다."); }

if($sess->decode($_POST[type]) != "smsPost")            { $func->err("[경고!] 정상적인 접근이 아닙니다."); }

#--- 넘어온 값과 변수 동기화 및 validCheck
foreach($_POST AS $key=>$val)
{
  ${$key} = trim($val);
  #-- POST값 vaild check
  #-- $func->validCheck(체크할 값, 항목제목, 항목명, 체크타입)
  if($key == "receiveList")   $func->vaildCheck($val, "수신자 번호", $key);
  if($key == "msg01")         $func->vaildCheck($val, "메세지 내용", $key);
  if($key == "sender")        $func->vaildCheck($val, "발신자 번호", $key, "phone");
}

/* --------------------------------------------------------------------------------------
| SMS(Socket) 문자 발송
*/
$mesg										= stripslashes(trim($_POST[msg01]));
$receiveList						= explode("\n", trim($_POST[receiveList]));
if($_POST[rdoSend] == 2) { $sock->date = strtotime($_POST[sdate]." ".$_POST[shour].":".$_POST[smin].":00"); }
$sock->send							= trim($_POST[sender]);

$count = 0;
foreach($receiveList AS $val)
{
  $val    = trim($val);
	$socket	= $sock->smsSend($val, $mesg);
						$sock->varReset();  //데이터 리셋
	if($socket[code] == '01') { $count++; }
}
if($count < 1)
{
	$func->setLog(__FILE__, "SMS발송실패 (".$socket[msg].")");
}

#--- 메세지 저장처리
if($_POST[savemsg] == 'Y')
{
  $query = " INSERT INTO `mdSms__mesg` (mesg,date) VALUES ('".$mesg."','".time()."') ";
  $db->query($query);
}
$func->err("총 {".count($receiveList)."}건중 {".$count."}건 발송성공 하였습니다", "parent.$.dialogRemove(); parent.$.insert('#module','../modules/mdSms/manage/history.php','','300')");
?>
