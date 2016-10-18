<?php
/**
 * 비밀번호 입력창
 * 게시물 (비밀글,수정,삭제)
 * 댓글 (수정, 삭제)
 */
@ob_end_clean();
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
$func->checkRefer("POST");

switch($sess->decode($_POST['type']))
{
	//비밀글,수정,삭제 접근제어
	case "secretAccess" :

		if(!$_POST['docPasswd']) { $func->err("작성시 입력하신 비밀번호를 입력해주세요","back"); }
		//입력받은 비밀번호 체크
		$query = " SELECT COUNT(*) FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE seq='".$_POST['num']."' AND passwd='".$db->passType($cfg['site']['encrypt'], $_POST['docPasswd'])."' ";
		if($db->queryFetchOne($query) < 1)
		{
			$func->err($lang['doc']['notmatch'],"back");
		}
		else
		{
			$_SESSION['docSecret'] = $_POST['docPasswd'];
		}
		Header("Location: ".$_SERVER['PHP_SELF']."?".str_replace('cate='.$cfg['module']['cate'].'&','cate='.$cfg['cate']['cate'].'&',__PARM__)."&type=view&num=".$_POST['num']);
		die();

	break;

	//게시물 수정 접근제어
	case "articleModifyAccess" : case "memoModifyAccess" :

		if(!$_POST['docPasswd']) { $func->err("작성시 입력하신 비밀번호를 입력해주세요"); }

		//입력받은 비밀번호 체크
		$query = " SELECT COUNT(*) FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE seq='".$_POST['num']."' AND passwd='".$db->passType($cfg['site']['encrypt'], $_POST['docPasswd'])."' ";
		if($db->queryFetchOne($query) < 1)
		{
			$func->err($lang['doc']['notmatch']);
		}
		else
		{
			$_SESSION['docSecret'] = $_POST['docPasswd'];
		}
		Header("Location: ".$_SERVER['PHP_SELF']."?".str_replace('cate='.$cfg['module']['cate'].'&','cate='.$cfg['cate']['cate'].'&',__PARM__)."&type=".$sess->encode('modify')."&num=".$_POST['num']);
		die();

	break;

	//게시물 삭제(휴지통)
	case "articleDelTrash" :

		//'관리'권한체크
		if($member->checkPerm('0') === true)
		{
			$query = "seq='".$_POST['num']."'";
			$msg = $lang['doc']['notfound'];
		}
		else
		{
			//로그인중인 상태의 '작성'권한 체크
			if($_SESSION['ulevel'] > 0 && $member->checkPerm(3) === true)
			{
				$query = "seq='".$_POST['num']."' AND id='".$_SESSION['uid']."'";
				$msg = $lang['doc']['notown'];
			}
			else
			{
				//비회원의 입력받은 비밀번호로 체크
				if(!$_POST['docPasswd']) { $func->err("작성시 입력했던 비밀번호를 입력해주세요"); }
				$query = "seq='".$_POST['num']."' AND passwd='".$db->passType($cfg['site']['encrypt'], $_POST['docPasswd'])."' ";
				$msg = $lang['doc']['notmatch'];
			}
		}

		// 이전 상태값 조회(2013-08-07)
		$oldContent = $db->queryFetch(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE seq='".$_POST['num']."'");

		//문서 휴지통으로 이동
		$db->query(" UPDATE `".$cfg['cate']['mode']."__content".$prefix."` SET idx='0',idxTrash='".$_POST['idx']."' WHERE ".$query." AND idxTrash='0' ");
		if($db->getAffectedRows() < 1) { 
			$func->err($msg); 
		}
		else
		{
			/**
			 * 네이버 신디케이션 처리
			*/
			if($func->checkModule("mdSyndication") && $cfg['site']['naverSyndiYN'] == 'Y')
			{
				if($oldContent['useSecret'] == 'N')
					$syndi->documentDelete($oldContent['cate'], $_POST['num'], $oldContent['subject']);
			}

		}

		//문서 갯수 업데이트
		$func->checkCount("trashed", 1, $cfg['module']['cate']);

		//알림창
		$func->setLog(__FILE__, "게시글 (".$cfg['module']['cate']."-".$_POST['num'].")삭제(휴지통) 성공");
		$func->err($lang['doc']['process'], $_SERVER['PHP_SELF']."?".str_replace('cate='.$cfg['module']['cate'].'&','cate='.$cfg['cate']['cate'].'&',__PARM__)."&type=list");

	break;

	//게시물 삭제(영구)
	case "articleDelAccess" :

		//'관리'권한 체크
		if($member->checkPerm('0') === true)
		{
			$query = "seq='".$_POST['num']."'";
			$msg = $lang['doc']['notfound'];
		}
		else
		{
			//로그인중인 상태의 '작성'권한 체크
			if($_SESSION['ulevel'] > 0 && $member->checkPerm(3) === true)
			{
				$query = "seq='".$_POST['num']."' AND id='".$_SESSION['uid']."' AND idxTrash>'0' ";
				$msg = $lang['doc']['notown'];
			}
			else
			{
				//비회원의 입력받은 비밀번호 체크
				if(!$_POST['docPasswd']) { $func->err($lang['doc']['notown']); }
				$query = "seq='".$_POST['num']."' AND passwd='".$db->passType($cfg['site']['encrypt'], $_POST['docPasswd'])."' AND idxTrash>'0' ";
				$msg = $lang['doc']['notmatch'];
			}
		}
		//본문 삭제 진행
		$db->query(" DELETE FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE ".$query);
		if($db->getAffectedRows() < 1) { 
			$func->err($msg); 
		}
		else
		{
			/**
			 * 네이버 신디케이션 처리
			*/
			if($func->checkModule("mdSyndication") && $cfg['site']['naverSyndiYN'] == 'Y')
			{
				if($oldContent['useSecret'] == 'N')
					$syndi->documentDelete($oldContent['cate'], $_POST['num'], $oldContent['subject']);
			}
		}

		//연관 댓글 삭제 진행
		$db->query(" DELETE FROM `".$cfg['cate']['mode']."__comment".$prefix."` WHERE parent='".$_POST['num']."' ");

		//댓글갯수 업데이트
		$func->checkCount("deleteComment", "-".$db->getAffectedRows(), $cfg['module']['cate'], $_POST['trashed']);

		//연관 첨부파일 삭제 진행
		$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__file".$prefix."` WHERE parent='".$_POST['num']."' ORDER BY regDate ASC ");
		while($sRows = $db->fetch())
		{
			@unlink($cfg['upload']['dir'].date("Y",$sRows['regDate'])."/".date("m",$sRows['regDate'])."/".$sRows['fileName']);
			@unlink($cfg['upload']['dir'].date("Y",$sRows['regDate'])."/".date("m",$sRows['regDate'])."/"."s-".$sRows['fileName']);
			@unlink($cfg['upload']['dir'].date("Y",$sRows['regDate'])."/".date("m",$sRows['regDate'])."/"."m-".$sRows['fileName']);
			@unlink($cfg['upload']['dir'].date("Y",$sRows['regDate'])."/".date("m",$sRows['regDate'])."/"."l-".$sRows['fileName']);
		}
		$db->query(" DELETE FROM `".$cfg['cate']['mode']."__file".$prefix."` WHERE parent='".$_POST['num']."' ");

		//문서갯수 업데이트
		$func->checkCount("delete", -1, $cfg['module']['cate'], $_POST['trashed']);

		$db->query(" OPTIMIZE TABLE `".$cfg['cate']['mode']."__`,`".$cfg['cate']['mode']."__content".$prefix."`,`".$cfg['cate']['mode']."__comment".$prefix."`,`".$cfg['cate']['mode']."__file".$prefix."` ");
		//알림창
		$func->setLog(__FILE__, "게시글 (".$cfg['module']['cate']."-".$_POST['num'].")삭제(영구) 성공");
		$func->err($lang['doc']['process'], $_SERVER['PHP_SELF']."?".str_replace('cate='.$cfg['module']['cate'].'&','cate='.$cfg['cate']['cate'].'&',__PARM__)."&type=list");

	break;

	//게시물 수정 접근제어
	case "cmtModifyAccess" :

		if(!$_POST['docPasswd']) { $func->err("작성시 입력하신 비밀번호를 입력해주세요"); }
		$query = " SELECT COUNT(*) FROM `".$cfg['cate']['mode']."__comment".$prefix."` WHERE seq='".$_POST['rnum']."' AND parent='".$_POST['num']."' AND passwd='".$db->passType($cfg['site']['encrypt'], $_POST['docPasswd'])."' ";
		if($db->queryFetchOne($query) < 1)
		{
			$func->err($lang['doc']['notmatch']);
		}
		else
		{
			$_SESSION['mdBoardComment'] = $_POST['docPasswd'];
		}
		Header("Location: ".$_SERVER['PHP_SELF']."?".str_replace('cate='.$cfg['module']['cate'].'&','cate='.$cfg['cate']['cate'].'&',__PARM__)."&type=view&rnum=".$_POST['rnum']."&num=".$_POST['num']);
		exit(0);

	break;

	//게시물 삭제(휴지통)
	case "cmtDelTrash" :

		if($member->checkPerm('0') === true)
		{
			$query = "seq='".$_POST['rnum']."' AND parent='".$_POST['num']."'";
		}
		else
		{
			if($_SESSION['ulevel'] > 0 && $member->checkPerm(3) === true)
			{
				$query = "seq='".$_POST['rnum']."' AND parent='".$_POST['num']."' AND id='".$_SESSION['uid']."'";
				$msg = $lang['doc']['notown'];
			}
			else
			{
				if(!$_POST['docPasswd']) { $func->err("댓글 비밀번호를 입력해주세요"); }
				$query = "seq='".$_POST['rnum']."' AND parent='".$_POST['num']."' AND passwd='".$db->passType($cfg['site']['encrypt'], $_POST['docPasswd'])."' ";
				$msg = $lang['doc']['notmatch'];
			}
		}
		$db->query(" UPDATE `".$cfg['cate']['mode']."__comment".$prefix."` SET trashDate='".time()."' WHERE ".$query." AND trashDate='0' ");
		if($db->getAffectedRows() < 1) { $func->err($msg); }

		//문서 갯수 업데이트
		$db->query(" UPDATE `".$cfg['cate']['mode']."__content".$prefix."` SET commentCount=commentCount-'1' WHERE seq='".$_POST['num']."' ");

		//알림창
		$func->setLog(__FILE__, "게시글 댓글 (".$cfg['module']['cate']."-".$_POST['rnum']."-".$_POST['num'].")삭제(휴지통) 성공");
		$func->err($lang['doc']['process'], $_SERVER['PHP_SELF']."?".str_replace('cate='.$cfg['module']['cate'].'&','cate='.$cfg['cate']['cate'].'&',__PARM__)."&type=view&num=".$_POST['num']."#commentForm".$_POST['num']);

	break;

	//댓글 삭제(영구)
	case "cmtDelAccess" :

		if($member->checkPerm('0') === true)
		{
			$query = "seq='".$_POST['rnum']."' AND parent='".$_POST['num']."'";
		}
		else
		{
			if($_SESSION['ulevel'] > 0 && $member->checkPerm(3) === true)
			{
				$query = "seq='".$_POST['rnum']."' AND parent='".$_POST['num']."' AND id='".$_SESSION['uid']."'";
				$msg = $lang['doc']['notown'];
			}
			else
			{
				if(!$_POST['docPasswd']) { $func->err("댓글 비밀번호를 입력해주세요"); }
				$query = "seq='".$_POST['rnum']."' AND parent='".$_POST['num']."' AND passwd='".$db->passType($cfg['site']['encrypt'], $_POST['docPasswd'])."' ";
				$msg = $lang['doc']['notmatch'];
			}
		}
		//댓글삭제
		$db->query(" DELETE FROM `".$cfg['cate']['mode']."__comment".$prefix."` WHERE ".$query );
		if($db->getAffectedRows() < 1) { $func->err($msg); }

		//댓글의 추천점수 본문적용
		if($_POST['recom'] > 0) { $db->query(" UPDATE `".$cfg['cate']['mode']."__content".$prefix."` SET recom=recom-'".$_POST['recom']."' WHERE seq='".$_POST['num']."' "); }

		$db->query(" OPTIMIZE TABLE `".$cfg['cate']['mode']."__comment".$prefix."` ");
		$func->setLog(__FILE__, "게시글 댓글 (".$cfg['module']['cate']."-".$_POST['rnum']."-".$_POST['num'].")삭제(영구) 성공");
		$func->err($lang['doc']['process'], $_SERVER['PHP_SELF']."?".str_replace('cate='.$cfg['module']['cate'].'&','cate='.$cfg['cate']['cate'].'&',__PARM__)."&type=view&num=".$_POST['num']."#commentForm".$_POST['num']);

	break;

	default :
		$func->err("세션이 비정상적으로 종료되었습니다. 다시 시도 하시기 바랍니다.");
	break;
}

?>
