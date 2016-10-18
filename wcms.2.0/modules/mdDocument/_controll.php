<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
$type = ($_GET['type']) ? $_GET['type']	: $_POST['type'];
//$prefix = substr($cfg['module']['cate'],0,3);
$prefix = null;
$errorScript = ($_GET['mode']) ? "window.self.close();" : "back";

switch($type)
{
//	목록
	case "list" :
		include __PATH__."modules/mdDocument/doc".$cfg['module']['list'].".php";
		break;

//	열람
	case "view" :
		switch($cfg['module']['list'])
		{
			case "Page" :
				include __PATH__."modules/mdDocument/doc".$cfg['module']['list'].".php";
				break;
			case "Faq" :
				include __PATH__."modules/mdDocument/view".$cfg['module']['list'].".php";
				break;
			case "Case" :
				include __PATH__."modules/mdDocument/view".$cfg['module']['list'].".php";
				break;
			case "ProductQH" :
				include __PATH__."modules/mdDocument/view".$cfg['module']['list'].".php";
				break;
			case "Event" :
				include __PATH__."modules/mdDocument/view".$cfg['module']['list'].".php";
				break;
			case "ListMobile" :
				include __PATH__."modules/mdDocument/view".$cfg['module']['list'].".php";
				break;
			case "Forum" :
				include __PATH__."modules/mdDocument/doc".$cfg['module']['list'].".php";
				break;
			case "ReplyStatus" :	//답변 리스트
				include __PATH__."modules/mdDocument/view".$cfg['module']['list'].".php";
				break;
			default :
				include __PATH__."modules/mdDocument/view.php";
				break;
		}
		break;

//			작성하기
	case "input" : case "reply" :
		switch($cfg['module']['list'])
		{
			case "Event" :
				if($member->checkPerm(3) === false) { $func->err($lang['doc']['notperm'], $errorScript); }
				include __PATH__."modules/mdDocument/inputEvent.php";
				break;
			default :
				if($member->checkPerm(3) === false) { $func->err($lang['doc']['notperm'], $errorScript); }
				include __PATH__."modules/mdDocument/input.php";
				break;
		}
		break;

//	검색
	case "search" :
		include  __PATH__."modules/mdDocument/search.php";
		break;

//	검색
	case "searchDate" :
		include  __PATH__."modules/mdDocument/searchDate.php";
		break;

//	선택 이동
	case "move" :
		if($member->checkPerm('0') === false) { $func->ajaxMsg($lang['doc']['notperm'],"","30"); }
		include  __PATH__."modules/mdDocument/move.php";
		break;

//	선택 삭제
	case "clear" :
		if($member->checkPerm('0') === false) { $func->ajaxMsg($lang['doc']['notperm'],"","30"); }
		include  __PATH__."modules/mdDocument/clear.php";
		break;

	default :
		/**
		 * 인코딩 타입 : Start
		 */
		switch($sess->decode($type))
		{
//			작성하기
			case "input" : case "reply" :
				switch($cfg['module']['list'])
				{
					case "Event" :
						if($member->checkPerm(3) === false) { $func->err($lang['doc']['notperm'], $errorScript); }
						include __PATH__."modules/mdDocument/inputEvent.php";
						break;
					default :
						if($member->checkPerm(3) === false) { $func->err($lang['doc']['notperm'], $errorScript); }
						include __PATH__."modules/mdDocument/input.php";
						break;
				}
				break;

//			입력데이터 전송
			case "inputPost" :
				if($member->checkPerm(3) === false) { $func->err($lang['doc']['notperm'], $errorScript); }
				if($_POST['num'] && $member->checkPerm('0') === false) { $func->err($lang['doc']['notperm'], $errorScript); } //답글은 게시판 관리자만
				//Tracking Update
				if($cfg['site']['tracking']) { $sess->tracking(__CATE__.'(post)', 1); }
				include __PATH__."modules/mdDocument/post.php";
				break;

//			Flex 파일업로드
			case "upLoadFile" :

				if($_FILES)
				{
					require_once __PATH__."_Lib/classUpLoad.php";
					$up = new upLoad($cfg['upload']['dir'], $_FILES);
					$up->upFiles($cfg['module']['cate']."_".$_POST['RenameFile']);
				}
				else
				{
					header("HTTP/1.0 500 Not found");
				}
				break;

//			수정입력
			case "modify" :
				switch($cfg['module']['list'])
				{
					case "Event" :
						if($member->checkPerm(3) === false) { $func->err($lang['doc']['notperm'], $errorScript); }
						include __PATH__."modules/mdDocument/modifyEvent.php";
						break;
					default :
						if($member->checkPerm(3) === false) { $func->err($lang['doc']['notperm'], $errorScript); }
						include __PATH__."modules/mdDocument/modify.php";
						break;
				}
				break;
//			수정 데이터 전송
			case "modifyPost" :
				if($member->checkPerm(3) === false) { $func->err($lang['doc']['notperm'], $_SERVER['PHP_SELF']."?".__PARM__."&type=view&num=".$_POST['num']); }
				include __PATH__."modules/mdDocument/modifyPost.php";
				break;

//			댓글 전송
			case "cmtPost" :
				if(!$cfg['module']['comment'] || $cfg['module']['comment'] == "N") { $func->err($lang['doc']['notperm'], $_SERVER['PHP_SELF']."?".__PARM__."&type=view&num=".$_POST['num']); }
				if($member->checkPerm(3) === false) { $func->err($lang['doc']['notperm'], $_SERVER['PHP_SELF']."?".__PARM__."&type=view&num=".$_POST['num']); }
				include __PATH__."modules/mdComment/commentPost.php";
				break;

//			카테고리 이동
			case "articleMove" :
				if($member->checkPerm('0') === false) { $func->err($lang['doc']['notperm']); }
				include __PATH__."modules/mdDocument/movePost.php";
				break;

//			카테고리 이동
			case "articleClear" :
				if($member->checkPerm('0') === false) { $func->err($lang['doc']['notperm']); }
				include __PATH__."modules/mdDocument/clearPost.php";
				break;

//			게시물 수정,삭제,비밀글 열람,게시물 정리
			case "secret" : case "articleModify" : case "articleDel" : case "cmtModify" : case "cmtDel" : case "articleOptimize" : case "repair" : case "articleReset" : case "memoModify" : case "memoDel" :
				include __PATH__."/modules/mdDocument/process.php";
				break;

			//게시물 인증, 게시물&댓글 삭제
			case "secretAccess" : case "articleModifyAccess" : case "articleDelTrash" : case "articleDelAccess" : case "cmtModifyAccess" : case "cmtDelAccess" : case "cmtDelTrash" : case "memoModifyAccess" :
				include __PATH__."modules/mdDocument/processPost.php";
				break;

//			null일때
			default :
				if(!$type)
				{
					include __PATH__."modules/mdDocument/doc".$cfg['module']['list'].".php";
				}
				else
				{
					//$func->err("세션이 비정상적으로 종료되었습니다. 다시 로그인 하시기 바랍니다.", "window.location.replace('./?cate=000002001');");
					$func->err("세션이 비정상적으로 종료되었습니다. 다시 로그인 하시기 바랍니다.", "back");
				}
				break;
		}
		break;
}

?>
