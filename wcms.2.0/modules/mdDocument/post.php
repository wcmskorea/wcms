<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
ob_end_clean(); //화면처리 업는 부분은 캐시를 삭제해준다.

/**
 * 리퍼러 체크
 */
$func->checkRefer("POST");

/**
 * 입력 옵션 설정병합
 */
$cfg['module'] = array_merge($cfg['module'], (array)unserialize($cfg['module']['contentAdd']));

//넘어온 값과 변수 동기화 및 validCheck
foreach($_POST AS $key=>$val)
{
	$db->data[$key] = trim($val);
	//$func->validCheck(체크할 값, 항목제목, 항목명, 체크타입)
	if($key == "subject") { $func->vaildCheck($val, $lang['doc']['subject'], "trim", $cfg['module']['opt_subject']); }
	if($key == "writer") { $func->vaildCheck($val, $lang['doc']['writer'], "trim", $cfg['module']['opt_writer']); }
	if($key == "content") { $func->vaildCheck($val, $lang['doc']['content'], "short", $cfg['module']['opt_content']); }
	if($key == "email") { $func->vaildCheck($val, $lang['doc']['email'], "email", $cfg['module']['opt_email']); }
	if($key == "phone") { $func->vaildCheck($val, $lang['doc']['phone'], "phone", $cfg['module']['opt_phone']); }
	if($key == "passwd" && !$_SESSION['uid']) { $func->vaildCheck($val, $lang['doc']['passwd'], "trim", "M"); }
}
$db->data['writer'] 	= ($_SESSION['uname'] && $member->checkPerm('0') === false) ? $_SESSION['uname'] : $db->data['writer'];

$db->data['subject']	= ($db->data['subject']) ? $db->data['subject'] : "[".$cfg['module']['cate']."]".$cfg['cate']['name'];
$db->data['regDate']	= ($db->data['redate'] == 'Y') ? strtotime($db->data['reyear']."-".$db->data['remonth']."-".$db->data['reday']." ".$db->data['rehour'].":".($db->data['remin']? $db->data['remin']:"00" ).":00") : time();
$db->data['regDate']	= ($cfg['module']['list'] == 'Cal' && $db->data['redate'] != 'Y') ? strtotime(date('Y-m-d 00:00:00')) : $db->data['regDate'];
$db->data['endDate']	= ($db->data['endate'] == 'Y') ? strtotime($db->data['enyear']."-".$db->data['enmonth']."-".$db->data['enday']." ".$db->data['enhour'].":".($db->data['enmin']? $db->data['remin']:"00").":59") : $db->data['regDate'];
$db->data['endDate']	= ($cfg['module']['list'] == 'Cal' && $db->data['endate'] != 'Y') ? $db->data['regDate'] + 86399 : $db->data['endDate'];
$db->data['endDate']	= ($db->data['regDate']	> $db->data['endDate']) ? $db->data['regDate'] : $db->data['endDate'];
$db->data['upDate'] 	= time();
$db->data['ip']			= $_SERVER['REMOTE_ADDR'];
$db->data['productSeq']			= ($db->data['productSeq']) ? $db->data['productSeq'] : "0";
$db->data['boardType']			= ($db->data['boardType']) ? $db->data['boardType'] : "BASIC";

//스팸 체크
//if($db->queryFetchOne(" SELECT COUNT(seq) FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE regDate>=".intval($db->data['regDate']-10)." AND ip='".$_SERVER['REMOTE_ADDR']."'") > 0 && $member->checkPerm('0') === false)
//{
//	$func->err($lang['doc']['spam']);
//}

//데이터 정리
$db->data['seq']        = ($db->queryFetchOne("SELECT MAX(seq) FROM `".$cfg['cate']['mode']."__content".$prefix."`")) + 1;
$db->data['cate']       = ($db->data['move']) ? $db->data['move'] : $db->data['cate'];
$class 									= explode(",", $cfg['module']['division']);
$db->data['subject']    = (is_array($class) && !is_null($db->data['division']) && $cfg['module']['opt_division'] != 'N') ? "[".trim($class[$db->data['division']])."] ".strip_tags($db->data['subject']) : $db->data['subject'];
$db->data['subject']    = trim(strip_tags($db->data['subject']));
$db->data['subject']    = addslashes($db->data['subject']);
$db->data['email']      = trim(strip_tags($email));
$db->data['url']				= trim(strip_tags($url));
$db->data['url']				= preg_replace('/http:\/\//i', null, $db->data['url']);
$db->data['url']				= preg_replace('/https:\/\//i', null, $db->data['url']);
$db->data['content']    = $func->deleteTags($db->data['content']);
$db->data['content']    = str_replace('http://'.__HOST__.'/', $cfg['droot'], $db->data['content']);
if(is_array($_POST['addopt'])) { foreach($_POST['addopt'] AS $val) {	$contentAdd	.= trim($val)."|"; } } /* 추가입력 항목 */
$db->data['contentAdd']	= $func->deleteTags($contentAdd);
$db->data['useAdmin']  = ($_SESSION['ulevel'] && $_SESSION['ulevel'] < $cfg['operator']) ? "Y" : "N";
$db->data['useSecret']  = ($db->data['useSecret']) ? "Y" : "N";
$db->data['useNotice']  = ($db->data['useNotice']) ? 'Y' : 'N';

/**
 * 답글처리
 */
if($db->data['parent'])
{
	$Rows                 = $db->queryFetch(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE seq='".$db->data['parent']."' ");
	if($db->getNumRows() < 1 || $Rows['notice'] > 1) { $func->Err("해당 게시물은 답변글을 작성할 수 없습니다", "window.history.back()"); }
	$db->data['idx']				= $Rows['idx'];
	$db->data['idxDepth']		= $Rows['idxDepth'] + 1;
	$db->data['productSeq'] = ($Rows['productSeq']) ? $Rows['productSeq'] : $Rows['seq'];        //답글일 경우 부모의 상품코드 가져오기
	$db->data['id']					= ($Rows['id']) ? $Rows['id'] : $_SESSION['uid'];
	$db->data['passwd']			= ($Rows['passwd']) ? $Rows['passwd'] : $db->passType($cfg['site']['encrypt'], $db->data['passwd']);

	$db->query(" UPDATE `".$cfg['cate']['mode']."__content".$prefix."` SET idx=idx+'1',idxTrash=idxTrash+if(idxTrash>'0','1','0') WHERE idx >= '".$db->data['idx']."' OR idxTrash >= '".$db->data['idx']."' ");
}
else
{
	//답글과 원글순서가 섞이는 부분 처리 2012-01-30 강인
//$db->data['idx']    = $db->data['seq'];
	$db->data['idx']      = ($db->queryFetchOne("SELECT MAX(idx) FROM `".$cfg['cate']['mode']."__content".$prefix."`")) + 1;
	$db->data['id']       = $_SESSION['uid'];
	$db->data['passwd']   = $db->passType($cfg['site']['encrypt'], $db->data['passwd']);
}

/**
 * 첨부파일 처리
 */
require_once	__PATH__."_Lib/classUpLoad.php";
$up             = new upLoad($cfg['upload']['dir'], $_FILES, explode(",", $cfg['module']['thumbType'])); //썸네일 생성비율 ['3']['4']=>비율
$up->count      = $cfg['module']['uploadCount'];
$up->table      = $cfg['cate']['mode']."__file".$prefix;
$up->upLoaded   = ($db->data['fileName']) ? array_reverse(explode("|:|", $db->data['fileName'])) : null;
$up->insertFile = $db->data['insertFile'];
$up->insertDb   = 'Y';
$up->seq        = $db->data['seq'];

//썸네일 비율처리 2012-01-17 강인
if($cfg['module']['thumbIsFix']){ $up->thumbIsFix = $cfg['module']['thumbIsFix'];}

$up->small				= $cfg['module']['thumbSsize'];
$up->middle				= $cfg['module']['thumbMsize'];
$up->large				= $cfg['module']['thumbBsize'];
$up->smallHeight	= $cfg['module']['thumbSsizeHeight'];
$up->middleHeight	= $cfg['module']['thumbMsizeHeight'];
$up->largeHeight	= $cfg['module']['thumbBsizeHeight'];

$up->upFiles();

/**
 * 데이터 입력
 */
$db->data['content'] 	= $db->data['content'].$up->insert;
$db->data['fileCount']	= $up->upCount;

if($db->sqlInsert($cfg['cate']['mode']."__content".$prefix, "INSERT", 0) < 1)
{
	$func->err($lang['doc']['failure'], "window.history.back()");
}
else
{

	// 문서 갯수 업데이트
	$func->checkCount("articled", 1, $db->data['cate']);

	// 문서 캐시저장
//	if(!is_dir(__HOME__.'data/html')) { mkdir(__HOME__.'data/html'); }
//	file_put_contents(__HOME__.'data/html/'.$cfg['module']['cate'].'_'.$cfg[site][lang].'.html', stripslashes($content));
//	$fp = fopen(__HOME__.'data/html/'.$cfg['module']['cate'].'_'.$cfg['site']['lang'].'.html', 'w');
//	fwrite($fp, stripslashes($db->data['content']));
//	fclose($fp);

	//SMS(Socket) 문자 발송 : 게시판은 관리자에게만 통보체크
	if(in_array('mdSms', $cfg['modules']) && $cfg['module']['sms'] != 'N')
	{
		$sock->tempMode		= "mdDocument";
		$sock->sender 		= $cfg['site']['phone'];
		$sock->tempArray 	= array($cfg['cate']['name'], $cfg['site']['domain']);
		$sock->smsSend($cfg['site']['mobile'], "temp01");
	}

	//2012-10-08 오혜진 email 전송 : 답변글 등록시 원글자에게 email 전송
	if($_POST['parent'] && $_POST['replyMail'] && $cfg['module']['replyMail'] == 'Y')
	{
		//메일전송
		$member->sendMail($_POST['replyMail'], $cfg['site']['email'], $db->data['subject'], $db->data['content'], $cfg['site']['siteName']);
		//include __PATH__."modules/mdDocument/sendReplyMail.php";
	}

	$msg = "정상적으로 게시물이 등록되었습니다.";

	if($db->data['boardType'] == "BASIC")
	{
		$func->err($msg, $_SERVER['PHP_SELF']."?".str_replace('cate='.$cfg['module']['cate'].'&','cate='.$cfg['cate']['cate'].'&',__PARM__).'&amp;year='.date('Y',$db->data['regDate']).'&amp;month='.date('m',$db->data['regDate']));
	}
	else
	{
		//새창에서 작성일경우
		if($_POST['mode'] == "content")
		{
			echo("<script type='text/javascript'>alert('".$msg."');opener.location.reload();self.close()</script>");
		}
		else {
			$func->err($msg, $_SERVER['PHP_SELF']."?".str_replace('cate='.$cfg['module']['cate'].'&','cate='.$cfg['cate']['cate'].'&',__PARM__).'&amp;year='.date('Y',$db->data['regDate']).'&amp;month='.date('m',$db->data['regDate']));
		}
	}
}
?>
