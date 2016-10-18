<?php
/**
 * 댓글 입력.수정 폼
 *
 * Lastest : 이성준 (2009년 6월 5일 금요일)
*/
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
@ob_end_clean();

//넘어온 값과 변수 동기화 및 validCheck
foreach($_POST AS $key=>$val) 
{
	$db->data[$key] = trim($val);
	//$func->validCheck(체크할 값, 항목제목, 항목명, 체크타입)
	if($key == "writer" && !$_SESSION['uid']) { $func->vaildCheck($val, $lang['doc']['writer'], "trim", "M"); }
	if($key == "passwd" && !$_SESSION['uid']) { $func->vaildCheck($val, $lang['doc']['passwd'], "trim", "M"); }
	if($key == "comment") { $func->vaildCheck($val, $lang['doc']['content'], "short", "M"); }
}

$db->data['seq']		= '';
$db->data['id']			= $_SESSION['uid'];
$db->data['writer']		= ($_SESSION['uname']) ? $_SESSION['uname'] : $db->data['writer'];
$db->data['writer']		= ($_SESSION['unick']) ? $_SESSION['unick'] : $db->data['writer'];
$db->data['comment']	= trim($func->deleteTags($db->data['comment']));
$db->data['comment']	= str_replace('http://'.__HOST__.'/', '/', $db->data['comment']);
$db->data['passwd']		= $db->passType($cfg['site']['encrypt'], $db->data['passwd']);
$db->data['useAdmin']	= ($_SESSION['ulevel'] && $_SESSION['ulevel'] < $cfg['operator']) ? "Y" : "N";
$db->data['ip']			= $_SERVER['REMOTE_ADDR'];

if($_POST['rnum']) 
{
	$db->data['upDate'] = time();
	$db->sqlUpdate($cfg['cate']['mode']."__comment".$prefix, "seq='".$db->data['rnum']."'", array('seq','parent'), 0);
	$func->err($lang['doc']['process'], $_SERVER['PHP_SELF']."?".__PARM__."&type=view&num=".$db->data['parent']."#commentList".$db->data['rnum']);
} 
else 
{
	$db->data['regDate'] = time();
	
	//스팸성 방지를 위한 10초간 딜레이설정
	if($db->queryFetchOne(" SELECT COUNT(seq) FROM `".$cfg['cate']['mode']."__comment".$prefix."` WHERE regDate>=".$db->data['regDate']."-10 AND ip='".$_SERVER['REMOTE_ADDR']."'") > 0) { $func->err($lang['doc']['spam'], $return); }
	//$query = "INSERT INTO `".$cfg['cate']['mode']."__comment".$prefix."` (cate,seq,parent,id,writer,passwd,comment,regDate,ip,voteCount) VALUES ('".__CATE__."','".$maxnum."','".$_POST['parent']."','".$_SESSION['uid']."','".$uname."','".$passwd."','".$commentContent."','".$date."','".$_SERVER['REMOTE_ADDR']."','".$recom."')";
	
	//결과처리
	if($db->sqlInsert($cfg['cate']['mode']."__comment".$prefix, "INSERT", 0) < 1) 
	{
		$func->err($lang['doc']['failure'], $_SERVER['PHP_SELF']."?".__PARM__."&type=view&num=".$_POST['parent']."#commentList".$_POST['rnum']."");
	} 
	else 
	{
		//2012-10-08 오혜진 email 전송 : 댓글 등록시 원글자에게 email 전송
		if($_POST['replyMail'] && $cfg['module']['replyMail'] == 'Y')
		{
			//메일전송
			$member->sendMail($_POST['replyMail'], $cfg['site']['email'], $cfg['site']['siteName'].' : 댓글이 등록되었습니다.', $db->data['comment'], $cfg['site']['siteName']);
		}

		$db->query(" UPDATE `".$cfg['cate']['mode']."__content".$prefix."` SET commentCount=commentCount+'1' WHERE seq='".$_POST['parent']."' ");
		if($recom > 0 && !$_POST['rnum']) 
		{ 
			$db->query(" UPDATE `".$cfg['cate']['mode']."__content".$prefix."` SET recom=recom+'".$recom."' WHERE seq='".$_POST['parent']."' "); 
		}
		$func->err($lang['doc']['process'], $_SERVER['PHP_SELF']."?".__PARM__."&type=view&num=".$_POST['parent']."#commentBox");
	}
		
}
?>
