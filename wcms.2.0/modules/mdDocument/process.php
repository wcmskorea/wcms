<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
usleep($cfg['sleep']);

echo('<div id="procForm" class="cube" style="margin:10px auto; width:400px;"><div class="line" style="padding:40px;">');

switch($sess->decode($_POST['type']))
{
//	비밀 게시물 열람
	case "secret" :
		if($_SESSION['ulevel'])
		{
			@ob_end_clean();
			Header("Location: ".$_SERVER['PHP_SELF']."?".str_replace('&amp;','&',__PARM__)."&type=view&num=".$_GET['num']);
			die('페이지로 이동 합니다');
		}
		$title = $lang['doc']['msgSecret']." 열람";
		echo('<form name="frmBoard" method="post" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data" onsubmit="return validCheck(this)">');
		echo('<input type="hidden" name="type" value="'.$sess->encode("secretAccess").'" />');
		break;

//	게시물 수정
	case "articleModify" :
		if($_SESSION['ulevel'])
		{
			@ob_end_clean();
			Header("Location: ".$_SERVER['PHP_SELF']."?".str_replace('&amp;','&',__PARM__)."&type=".$sess->encode('modify')."&num=".$_GET['num']);
			die('페이지로 이동 합니다');
		}
		$title = $lang['doc']['msgModify'];
		echo('<form name="frmBoard" method="post" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data" onsubmit="return validCheck(this)">');
		echo('<input type="hidden" name="type" value="'.$sess->encode('articleModifyAccess').'" />');
		break;

//	게시물 수정
	case "memoModify" :
		if($_SESSION['ulevel'])
		{
			@ob_end_clean();
			Header("Location: ".$_SERVER['PHP_SELF']."?".str_replace('&amp;','&',__PARM__)."&type=list&num=".$_GET['num']);
			die('페이지로 이동 합니다');
		}
		$title = $lang['doc']['msgModify'];
		echo('<form name="frmBoard" method="post" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data" onsubmit="return validCheck(this)">');
		echo('<input type="hidden" name="type" value="'.$sess->encode('memoModifyAccess').'" />');
		break;


//	게시물 삭제폼
	case "articleDel" :
		$title = $lang['doc']['msgTrash'];
		echo('<form name="frmBoard" method="post" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data" onsubmit="return validCheck(this)">');
		if($member->checkPerm('0') === false)
		{
			echo('<input type="hidden" name="type" value="'.$sess->encode('articleDelTrash').'" />');
		}
		else
		{
			echo('<input type="hidden" name="trashed" value="N" />');
			echo('<div class="center"><span class="keeping"><input type="radio" name="type" id="type1" value="'.$sess->encode('articleDelTrash').'" checked="checked" /><label for="type1" class="strong">임시삭제</label></span>&nbsp;&nbsp;<span class="keeping"><input type="radio" name="type" id="type2" value="'.$sess->encode('articleDelAccess').'" /><label for="type2" class="strong">영구삭제</label></span></div>');
		}
		break;

//	게시물 정리
	case "articleOptimize" :
		@ob_end_clean();
		if($member->checkPerm('s') === false) { $func->err($lang['doc']['notperm']); }
		
		//게시글,삭제글 정리
		$squery = ($cfg['module']['share']) ? "cate='".$cfg['module']['share']."'" : "cate='".$cfg['module']['cate']."'";
		$query = " SELECT COUNT(*) AS articled, SUM(if(idxTrash>'0','1','0')) AS trashed FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE ".$squery;
		$Rows = $db->queryFetch($query);
		$query = " UPDATE `".$cfg['cate']['mode']."__` SET articled='".$Rows['articled']."',articleTrashed='".$Rows['trashed']."' WHERE '".$cfg['module']['cate']."' IN (cate,share) ";
		$db->query($query);
		if($db->getAffectedRows() > 0)
		{
			$db->query(" UPDATE `".$cfg['cate']['mode']."__content".$prefix."` AS A SET commentCount=(SELECT COUNT(*) FROM `".$cfg['cate']['mode']."__comment".$prefix."` AS B WHERE B.parent=A.seq) ");
			$func->err($lang['doc']['msgOptimize'], $_SERVER['PHP_SELF']."?".__PARM__."&amp;type=list");
		}
		else
		{
			Header("Location: ".$_SERVER['PHP_SELF']."?".str_replace('&amp;','&',__PARM__)."&type=list");
			die("이미 정리가 되어 있는 상태입니다");
		}

//	휴지통 문서(게시물) 복구
	case "repair" :
		@ob_end_clean();
		if($member->checkPerm('0') === false) { $func->err($lang['doc']['notperm']); }
		$db->query(" UPDATE `".$cfg['cate']['mode']."__content".$prefix."` SET idx=idxTrash,idxTrash='0' WHERE seq='".$_GET['num']."' ");
		if($db->getAffectedRows() > 0)
		{
			/**
			 * 네이버 신디케이션 처리
			*/
			if($func->checkModule("mdSyndication") && $cfg['site']['naverSyndiYN'] == 'Y')
			{
				//복구처리
				$result = $syndi->documentRepair($cfg['module']['cate'], $db->data['seq'],'repair');
			}

			// 문서 갯수 업데이트
			$func->checkCount("trashed", -1, $cfg['module']['cate']);
			$func->err($lang['doc']['msgRepair'], $_SERVER['PHP_SELF']."?".__PARM__."&amp;type=list");
		}
		else
		{
			$func->err($lang['doc']['failure'], $_SERVER['PHP_SELF']."?".__PARM__."&amp;type=list");
		}
		break;

//	문서(게시물) 초기화 - 전체삭제
	case "articleReset" :
		@ob_end_clean();
		if($member->checkPerm('s') === false) { $func->err($lang['doc']['notperm']); }
		$db->query(" SELECT seq FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE cate='".$cfg['cate']['cate']."' ");
		//휴지통 문서는 영구삭제 시킨다
		while($Rows = $db->fetch())
		{
			//본문 삭제
			$db->query(" DELETE FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE seq='".$Rows['seq']."' ", 2);
			if($db->getAffectedRows(2) < 1) { $func->err("['".$value."']번 삭제하는데 실패하였습니다."); }

			//연관 댓글 삭제
			$db->query(" DELETE FROM `".$cfg['cate']['mode']."__comment".$prefix."` WHERE parent='".$Rows['seq']."' ", 2);

			//연관 첨부파일 삭제
			$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__file".$prefix."` WHERE parent='".$Rows['seq']."' ORDER BY regDate ASC ", 2);
			while($sRows = $db->fetch(2))
			{
				@unlink($cfg['upload']['dir'].date("Y",$sRows['regDate'])."/".date("m",$sRows['regDate'])."/".$sRows['fileName']);
				@unlink($cfg['upload']['dir'].date("Y",$sRows['regDate'])."/".date("m",$sRows['regDate'])."/"."s-".$sRows['fileName']);
				@unlink($cfg['upload']['dir'].date("Y",$sRows['regDate'])."/".date("m",$sRows['regDate'])."/"."m-".$sRows['fileName']);
				@unlink($cfg['upload']['dir'].date("Y",$sRows['regDate'])."/".date("m",$sRows['regDate'])."/"."l-".$sRows['fileName']);
			}
			$db->query(" DELETE FROM `".$cfg['cate']['mode']."__file".$prefix."` WHERE parent='".$Rows['seq']."' ", 2);
		}

		$db->query(" UPDATE `".$cfg['cate']['mode']."__` SET articled='0',articleTrashed='0' WHERE '".$cfg['cate']['cate']."' IN (cate,share) ");
		$db->query(" OPTIMIZE TABLE `".$cfg['cate']['mode']."__`,`".$cfg['cate']['mode']."__content".$prefix."`,`".$cfg['cate']['mode']."__comment".$prefix."`,`".$cfg['cate']['mode']."__file".$prefix."` ");
		//알림창
		$func->setLog(__FILE__, "문서(게시물) (".$cfg['cate']['cate'].") 초기화 성공");
		$func->err($lang['doc']['process'], $_SERVER['PHP_SELF']."?".__PARM__."&amp;type=list");
		break;

//	댓글 수정폼
	case "cmtModify" :
		if($_SESSION['ulevel'])
		{
			@ob_end_clean();
			Header("Location: ".$_SERVER['PHP_SELF']."?".str_replace('&amp;','&',__PARM__)."&type=view&num=".$_GET['num']."&rnum=".$_GET['rnum']."#commentList".$_GET['rnum']);
		}
		$title = $lang['comment']['msgModify'];
		echo('<form name="frmBoard" method="post" action="'.$_SERVER['PHP_SELF'].'#commentForm" enctype="multipart/form-data" onsubmit="return validCheck(this)">');
		echo('<input type="hidden" name="type" value="'.$sess->encode('cmtModifyAccess').'" />');
		echo('<input type="hidden" name="rnum" value="'.$_GET['rnum'].'" />');
		break;

//	댓글 삭제폼
	case "cmtDel" :
		$title = $lang['comment']['msgTrash'];
		echo('<form name="frmBoard" method="post" action="'.$_SERVER['PHP_SELF'].'#commentForm" enctype="multipart/form-data" onsubmit="return validCheck(this)">');
		echo('<input type="hidden" name="type" value="'.$sess->encode('cmtDelTrash').'" />');
		echo('<input type="hidden" name="rnum" value="'.$_GET['rnum'].'" />');
		break;
}

//['0']관리, ['1']접근, ['2']열람권한, ['3']작성 : 작성권한이 있어야만 비밀번호 없이 삭제
if($_SESSION['uid'] && $member->checkPerm(3))
{
?>
	<div class="center" style="padding:10px;"><strong class="colorOrange">"<?php echo($title);?>"</strong> <?php echo($lang['doc']['msgQuestion']);?></div>
	<div class="center pd10"><span class="btnPack black medium strong"><button type="submit"><?php echo($lang['doc']['access']);?></button></span>&nbsp;&nbsp;<span class="btnPack gray medium strong"><a href="javascript:;" onclick="window.history.back();"><?php echo($lang['doc']['cancel']);?></a></span></div>
<?php
}
else
{
?>
	<div class="pd10 center"><?php echo('<strong class="colorOrange">"'.$title.'"</strong>&nbsp;'.$lang['doc']['passwd']);?>를 입력하세요</div>
	<div class="center pd3"><input type="password" id="docPasswd" name="docPasswd" title="<?php echo($lang['doc']['passwd']);?>" class="input_blue center" style="width:200px;" value="" req="required" trim="trim" /></div>
	<div class="center pd3"><span class="btnPack black medium strong"><button type="submit"><?php echo($lang['doc']['access']);?></button></span>&nbsp;&nbsp;<span class="btnPack gray medium strong"><a href="javascript:;" onclick="window.history.back();" class="button bgray"><?php echo($lang['doc']['cancel']);?></a></span></div>
<?php
}
?>
	<input type="hidden" name="cate" value="<?php echo($cfg['module']['cate']);?>" />
	<input type="hidden" name="mode" value="<?php echo($_GET['mode']);?>" />
	<input type="hidden" name="menu" value="<?php echo($menu);?>" />
	<input type="hidden" name="sub" value="<?php echo($sub);?>" />
	<input type="hidden" name="num" value="<?php echo($_GET['num']);?>" />
	<input type="hidden" name="idx" value="<?php echo($_GET['idx']);?>" />
	<input type="hidden" name="sh" value="<?php echo($_GET['sh']);?>" />
	<input type="hidden" name="shc" value="<?php echo($_GET['shc']);?>" />
	<input type="hidden" name="currentPage" value="<?php echo($currentPage);?>" />
</form>
</div>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function()
{
	setTimeout ("$('#docPasswd').select()", 500);
});
//]]>
</script>
