<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
#--- [0]관리, [1]접근, [2]열람권한, [3]작성(등록)
if($member->checkPerm(3) === false) { $func->err("상담신청 권한이 없습니다"); }

#--- 리퍼러 체크
$func->checkRefer("POST");

/**
 * 입력 옵션 설정병합
 */
$cfg['module'] = @array_merge($cfg['module'], (array)unserialize($cfg['module']['contentAdd']));

//등록시간 체크
if($cfg['module']['limitTimeStart'] && $cfg['module']['limitTimeEnd'])
{
	$am = ($cfg['module']['limitTimeStart'] < 13) ? "오전" : "오후";
	$pm = ($cfg['module']['limitTimeEnd'] < 13) ? "오전" : "오후";
	if(date('H') < $cfg['module']['limitTimeStart']) { $func->err("죄송합니다! 금일 접수는 [ ".$am." ".$cfg['module']['limitTimeStart']."시 ] 부터 신청하실 수 있습니다 ^^","/"); }
	if(date('H') >= $cfg['module']['limitTimeEnd']) { $func->err("죄송합니다! 금일 접수는 [ ".$pm." ".$cfg['module']['limitTimeEnd']."시 ] 까지 마감 되었습니다. 다음날 참여부탁드립니다 ^^","/"); }
}

#--- 넘어온 값과 변수 동기화 및 validCheck
foreach($_POST AS $key=>$val)
{
	if(is_array($val)) { $val = implode("|",$val);}
	${$key} = trim($val);

	//$func->validCheck(체크할 값, 항목제목, 항목명, 체크타입)
	//입력폼이 변경이 되면 해당 유효성 체크 하는 부분도 수정되어야 한다.
	if($key == "division")  $func->vaildCheck($val, "상담구분", "trim", $cfg['module']['division']);
	if($key == "name")      $func->vaildCheck($val, "신청자명", "trim", $cfg['module']['opt_name']);
	if($key == "email")     $func->vaildCheck($val, "회원 이메일", "email", $cfg['module']['opt_email']);
	if($key == "mobile")    $func->vaildCheck($val, "휴대전화 번호", "mobile", $cfg['module']['opt_mobile']);
	if($key == "phone")     $func->vaildCheck($val, "전화번호", "phone", $cfg['module']['opt_phone']);
	if($key == "tel")       $func->vaildCheck($val, "연락처", "phone", $cfg['module']['opt_tel']);
	if($key == "content")   $func->vaildCheck($val, "상세내용(기타)", "trim", $cfg['module']['opt_content']);

	$db->data[$key] = trim($val);
	$db->data['mobile'] 	= ($_SESSION['mobile']) ? $_SESSION['mobile'] : $db->data['mobile'];
	$db->data['contentAdd'][$key] = trim($val);
}

//본인인증 체크
if($cfg['module']['opt_mobileAuth'] && $cfg['module']['opt_mobileAuth'] != 'N' && $_SESSION['mobileCode'] != $_POST['authcode']) { $func->err("휴대폰 인증코드가 일치하지 않습니다. 다시 확인 후 시도해 주십시오.", "back"); }

$db->data['seq']		= ($_POST['idx']) ? $_POST['idx'] : $db->queryFetchOne("SELECT MAX(seq) FROM `".$cfg['cate']['mode']."__content`") + 1;
$db->data['cate']		= __CATE__;
$db->data['id']			= $_SESSION['uid'];
$db->data['idcode']			= str_replace("-", null, $db->data['idcode']);
if($db->data['birthyear'])
{
	//회원생일 변수할당
	$db->data['birth']	= strtotime($db->data['birthyear']."-".$db->data['birthmonth']."-".$db->data['birthday']);
}
$db->data['division']	= $db->data['contentAdd']['division'];
$db->data['mobile']		= str_replace("-", "", $db->data['mobile']);
$db->data['phone']		= str_replace("-", "", $db->data['phone']);
$db->data['contentAdd']['content'] = mysql_real_escape_string($db->data['contentAdd']['content']);
if($db->data['schedulemonth'] || $db->data['scheduleday'] || $db->data['scheduleyear'])
{
	$db->data['schedule'] = mktime($db->data['schedulehour'], $db->data['schedulemin'], $db->data['schedulesec'], $db->data['schedulemonth'], $db->data['scheduleday'], $db->data['scheduleyear']);
}
$dateOpt = ($_POST['idx']) ? 'dateModify' : 'dateReg';
$db->data[$dateOpt] = time();
$db->data['info'] = $cfg['timeip'];
$db->data['content'] = mysql_real_escape_string($_POST['content']);

//중복신청 체크
if($cfg['module']['limitDuple'] == 'Y' && $_SESSION['ulevel'] != '1')
{
	if($db->queryFetchOne(" SELECT COUNT(*) FROM `".$cfg['cate']['mode']."__content` WHERE id='".$_SESSION['uid']."' OR mobile='".$db->data['mobile']."' OR phone='".$db->data['phone']."' OR info like '%|".$_SERVER['REMOTE_ADDR']."' OR (zipcode='".$db->data['zipcode']."' AND address01='".$db->data['address01']."' AND address02='".$db->data['address02']."') ") > 0) { $func->err("죄송합니다! 입력하신 정보로 이미 신청한 내역이 있습니다.", "back"); }
}

/**
 * 첨부파일 처리
 */
require			__PATH__."_Lib/classUpLoad.php";
$up 			= new upLoad($cfg['upload']['dir'], $_FILES);
$up->count		= $cfg['module']['uploadCount'];
$up->table		= $cfg['cate']['mode']."__file";
$up->upLoaded	= ($db->data['contentAdd']['fileName']) ? array_reverse(explode("|:|", $db->data['contentAdd']['fileName'])) : null;
$up->insertFile	= $db->data['contentAdd']['insertFile'];
$up->insertDb	= 'Y';
$up->seq		= $db->data['seq'];
$up->upFiles();

$db->data['file'] = $up->upCount;

// 불필요한 데이터 삭제
unset($db->data['contentAdd']['cate'],
$db->data['contentAdd']['type'],
$db->data['contentAdd']['menu'],
$db->data['contentAdd']['sub'],
$db->data['contentAdd']['idx'],
$db->data['contentAdd']['dateReg'],
$db->data['contentAdd']['fileName'],
$db->data['contentAdd']['division'],
$db->data['contentAdd']['content']
);
$db->data['contentAdd']  = serialize($db->data['contentAdd']);

/*-----------------------------------------------------------------------------------
| 정보 등록 및 수정
*/
if($_POST['idx'])
{
	$db->sqlUpdate("mdApp01__content", "seq='".$_POST['idx']."'", array('cate','seq'), 0);
}
else
{
	$db->sqlInsert("mdApp01__content", "REPLACE", 0);
}

/* ----------------------------------------------------------------------------------
| SMS(Socket) 문자 발송
*/
if($func->checkModule('mdSms') && $cfg['module']['sms'] != 'N')
{
	$sock->tempMode = "mdApp01";

	//발신번호 누락 예외(휴대전화 이외 입력의 경우도 처리) 2012-12-14
	if($db->data['mobile'])
		$db->data['mobile'] = $db->data['mobile'];
	else if($db->data['tel'])
		$db->data['mobile'] = $db->data['tel'];
	else if($db->data['phone'])
		$db->data['mobile'] = $db->data['phone'];

	switch($cfg['module']['sms'])
	{
		case "O" : /*운영자만*/
			$sock->sender		= $cfg['site']['phone']; //$db->data['mobile'];
			$sock->tempArray	= array(str_replace('-','',$db->data['mobile']).'/'.$db->data['name'], $cfg['cate']['name']);
			$sock->smsSend($cfg['site']['mobile'], "temp01");
			break;
		case "M" : /*신청자만*/
			$sock->sender		= $cfg['site']['mobile'];
			$sock->tempArray	= array($cfg['cate']['name'], $cfg['site']['domain']);
			$sock->smsSend($db->data['mobile'], "temp02");
			break;
		case "B" : /*둘다전송*/
		//회원
			$sock->sender		= $cfg['site']['mobile'];
			$sock->tempArray	= array($cfg['cate']['name'], $cfg['site']['domain']);
			$sock->smsSend($db->data['mobile'], "temp02");
		//운영자
			$sock->sender		= $cfg['site']['phone']; //$db->data['mobile'];
			$sock->tempArray	= array(str_replace('-','',$db->data['mobile']).'/'.$db->data['name'], $cfg['cate']['name']);
			$sock->smsSend($cfg['site']['mobile'], "temp01");
			break;
		case "X" : /*특정번호*/
			if($cfg['module']['smsMobile']) {
				$sock->sender		= $cfg['site']['phone']; //$db->data['mobile'] ? $db->data['mobile'] :  $cfg['site']['mobile'];
				$sock->tempArray	= array(str_replace('-','',$db->data['mobile']).'/'.$db->data['name'], $cfg['cate']['name']);
				$sock->smsSend($cfg['module']['smsMobile'], "temp01");
			}
			break;
	}
	$sock->varReset();  //데이터 리셋

}

/* ----------------------------------------------------------------------------------
| 메일 발송
*/
if($cfg['module']['mailSend'] == 'O' || $cfg['module']['mailSend'] == 'X') //2014-02-11
{
	switch($cfg['module']['mailSend'])
	{
		case "O" : /*운영자만*/
			$mailTo = $cfg['site']['email'];
			break;

		case "X" : /*특정메일주소*/
			$mailTo = $cfg['module']['email'];
			break;
	}

	$mailTo      = $mailTo ? $mailTo : $cfg['site']['email'];
	$mailFrom    = $db->data['email'] ? $db->data['email'] : $cfg['site']['email'];
	$mailSubject = $category->getCategoryInfo($db->data['cate'], $cfg['skin']).'가 접수되었습니다.';

	//메일전송
	//$member->sendMail($mailTo, $mailFrom, $mailSubject, $db->data['content'], $cfg['site']['siteName']);
	include __PATH__."modules/mdApp01/sendMail.php";
}

if($_POST['idx'])
{
	$func->err("정상적으로 변경 되었습니다." ,"window.location.replace('".$_SERVER['PHP_SELF']."?".__PARM__."')");
} else
{
	$func->err("정상적으로 신청(접수) 되었습니다.", "window.history.back()");
}
?>
