<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); }
@ob_end_clean();

/**
 * 리퍼러 체크
 */
$func->checkRefer("POST");

/**
 * 입력 옵션 설정병합
 */
$cfg['module'] = @array_merge($cfg['module'], (array)unserialize($cfg['module']['contentAdd']));

//넘어온 값과 변수 동기화 및 validCheck
foreach($_POST AS $key=>$val)
{
	$db->data[$key] = @trim($val);
	//$func->validCheck(체크할 값, 항목제목, 항목명, 체크타입)
	if($key == "subject") { $func->vaildCheck($val, $lang['doc']['subject'], "trim", $cfg['module']['opt_subject']); }
	if($key == "writer") { $func->vaildCheck($val, $lang['doc']['writer'], "trim", $cfg['module']['opt_writer']); }
	if($key == "content") { $func->vaildCheck($val, $lang['doc']['content'], "short", $cfg['module']['opt_content']); }
	if($key == "email") { $func->vaildCheck($val, $lang['doc']['email'], "email", $cfg['module']['opt_email']); }
	if($key == "phone") { $func->vaildCheck($val, $lang['doc']['phone'], "phone", $cfg['module']['opt_phone']); }
	if($key == "passwd" && !$_SESSION['uid']) { $func->vaildCheck($val, $lang['doc']['passwd'], "trim", "M"); }
}

/**
 * 이전 상태값 조회(2013-08-07)
*/
$oldContent = $db->queryFetch(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE seq='".$_POST['num']."'");

$db->data['seq']		= $_POST['num'];
$db->data['writer'] 	= ($_SESSION['uname'] && $member->checkPerm('0') === false) ? $_SESSION['uname'] : $db->data['writer'];
$db->data['subject']	= ($db->data['subject']) ? $db->data['subject'] : "[".$cfg['module']['cate']."]".$cfg['cate']['name'];

// 해당 값들이 있을때만 데이터를 등록한다.

$db->data['regDate']	= ($db->data['redate'] == 'Y') ? strtotime($db->data['reyear']."-".$db->data['remonth']."-".$db->data['reday']." ".$db->data['rehour'].":".($db->data['remin']? $db->data['remin']:"00" ).":00") : $db->data['regDate'];
//$db->data['regDate']	= strtotime($db->data['reyear']."-".$db->data['remonth']."-".$db->data['reday']." ".$db->data['rehour'].":".($db->data['remin']? $db->data['remin']:"00" ).":00");
$db->data['endDate']	= strtotime($db->data['enyear']."-".$db->data['enmonth']."-".$db->data['enday']." ".$db->data['enhour'].":".($db->data['enmin']? $db->data['remin']:"00").":59");
$db->data['endDate']	= ($db->data['regDate']	> $db->data['endDate']) ? $db->data['regDate'] : $db->data['endDate'];

if(!$db->data['reyear']) unset($db->data['regDate']);
if(!$db->data['endDate']) unset($db->data['endDate']);

$db->data['upDate']		= time();
$db->data['ip']			= $_SERVER['REMOTE_ADDR'];
$db->data['cate']		= ($db->data['move']) ? $db->data['move'] : $db->data['cate'];
$class 					= explode(",", $cfg['module']['division']);
$db->data['subject']	= (is_array($class) && !is_null($db->data['division']) && $cfg['module']['opt_division'] != 'N') ? "[".trim($class[$db->data['division']])."] ".strip_tags($db->data['subject']) : $db->data['subject'];
$db->data['subject'] 	= trim(strip_tags($db->data['subject']));
$db->data['subject'] 	= addslashes($db->data['subject']);
$db->data['email'] 		= trim(strip_tags($db->data['email']));
$db->data['url']				= trim(strip_tags($url));
$db->data['url']				= preg_replace('/http:\/\//i', null, $db->data['url']);
$db->data['url']				= preg_replace('/https:\/\//i', null, $db->data['url']);
$db->data['passwd'] 	= ($_SESSION['uid']) ? null : $db->passType($cfg['site']['encrypt'], $db->data['passwd']);
$db->data['content'] 	= $func->deleteTags($db->data['content']);
$db->data['content'] 	= str_replace('http://'.__HOST__.'/', $cfg['droot'], $db->data['content']);
//db->data['content'] 	= addslashes($db->data['content']);
if(is_array($_POST['addopt'])) { foreach($_POST['addopt'] AS $val) {	$contentAdd	.= trim($val)."|"; } } /* 추가입력 항목 */
$db->data['contentAdd']	= $func->deleteTags(preg_replace('/|$/',null,$contentAdd));
$db->data['useSecret'] 	= ($db->data['useSecret']) ? "Y" : "N";
$db->data['useNotice']	= ($db->data['useNotice']) ? 'Y' : 'N';
$db->data['productSeq']			= ($db->data['productSeq']) ? $db->data['productSeq'] : "0";
$db->data['boardType']			= ($db->data['boardType']) ? $db->data['boardType'] : "BASIC";

//define(__PARM__, "cate=".$db->data['cate']);

/**
 * 첨부파일 처리
 */
require_once		__PATH__."_Lib/classUpLoad.php";
$up 						= new upLoad($cfg['upload']['dir'], $_FILES, explode(",", $cfg['module']['thumbType'])); //썸네일 생성비율 ['3']['4']=>비율
$up->count			= $cfg['module']['uploadCount'];
$up->table			= $cfg['cate']['mode']."__file".$prefix;
$up->upLoaded		= ($db->data['fileName']) ? array_reverse(explode("|:|", $db->data['fileName'])) : null;
$up->insertFile	= $db->data['insertFile'];
$up->insertDb		= 'Y';
$up->seq				= $db->data['seq'];
$up->upCount		= $up->upCount + $db->data['fileCount'];
$up->upFiles();

/**
 * 첨부파일 삭제
 */
if(count($_POST['oldFile']) > 0) { $up->upDataBase($_POST['oldFile']); }

/**
 * 데이터 정리
 */
$db->data['content'] 	= $db->data['content'].$up->insert;
$db->data['fileCount']	= $up->upCount;

/**
 * 예외처리 데이터
 */
$except = array('seq');

/**
 * 데이터 입력
 */
if($db->sqlUpdate($cfg['cate']['mode']."__content".$prefix, "seq='".$db->data['seq']."' AND cate='".$db->data['cate']."'", $except, 0) < 1)
{
	$func->err($lang['doc']['failure'], "window.history.back()");
}
else
{
	/**
	 * 카테고리 이동시 관련 데이터 변경
	 */
	if(trim($_POST['cate']) != $db->data['cate'])
	{
		$db->query(" UPDATE `".$cfg['cate']['mode']."__file".$prefix."` SET cate='".$db->data['cate']."' WHERE seq='".$db->data['seq']."' ");
		$db->query(" UPDATE `".$cfg['cate']['mode']."__comment".$prefix."` SET cate='".$db->data['cate']."' WHERE seq='".$db->data['seq']."' ");

		/**
		 * 문서갯수 업데이트
		 */
		$func->checkCount("move", 1, trim($_POST['cate']), $db->data['cate'], $db->data['trashed']);
	}

	if($cfg['module']['list']=="Memo") {
		$func->setLog(__FILE__, "게시물 (".$cfg['module']['cate']."-".$db->data['seq'].")수정 성공");
		$func->err("정상적으로 수정되었습니다.", $_SERVER['PHP_SELF']."?".str_replace('cate='.$cfg['module']['cate'].'&','cate='.$cfg['cate']['cate'].'&',__PARM__)."&amp;type=list");
	} else {
		$func->setLog(__FILE__, "게시물 (".$cfg['module']['cate']."-".$db->data['seq'].")수정 성공");
		$func->err("정상적으로 수정되었습니다.", $_SERVER['PHP_SELF']."?".str_replace('cate='.$cfg['module']['cate'].'&','cate='.$cfg['cate']['cate'].'&',__PARM__)."&amp;type=view&amp;num=".$db->data['seq']);
	}
}
?>
