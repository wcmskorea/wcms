<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
unset($_SESSION['docSecret']);

if($member->checkPerm(3) === true) 
{
	include __PATH__."modules/mdDocument/docMemoForm.php";
}

if($member->checkPerm(2) === true) 
{
	if($cfg['module']['listUnion'] != 'Y')
	{
		if($member->checkPerm('0'))
		{
			$articleCount = $cfg['module']['articled'];
			$sql = "cate='".$cfg['module']['cate']."'";
		}
		else
		{
			$articleCount = $cfg['module']['articled'] - $cfg['module']['trashed'];
			$sql = "cate='".$cfg['module']['cate']."' AND idxTrash='0'";
		}
	}
	else
	{
		$getCount = $func->getTotalCount($cfg['cate']['mode']."__", "cate like '".$cfg['module']['cate']."%'");
		$articleCount = $getCount['articled'];
		$cfg['module']['trashed'] = $getCount['trashed'];
		if($member->checkPerm('0'))
		{
			$sql = "cate like '".$cfg['module']['cate']."%'";
		}
		else
		{
			$sql = "cate like '".$cfg['module']['cate']."%' AND idxTrash='0'";
		}
	}

	//검색조건에 따른 sub-query 설정
	$order = (!$_GET['shc'] || $_GET['shc'] == "ASC") ? "DESC" : "ASC"; //검색쿼리

	//검색타입별 서브쿼리 작성
	switch ($_GET['sh'])
	{
		case "division":
			$sql .= ($_GET['shc']) ? " AND subject like '[".$_GET['shc']."]%' ORDER BY idx DESC" : " ORDER BY idx DESC";
			break;
		case  "all":
			$sql .= " AND (subject like '%".$_GET['shc']."%' OR content like '%".$_GET['shc']."%') ORDER BY idx DESC";
			break;
		case "subject":
			$sql .= " AND subject like '%".$_GET['shc']."%' ORDER BY idx DESC";
			break;
		case "writer":
			$sql .= " AND writer like '%".$_GET['shc']."%' ORDER BY idx DESC";
			break;
		case "trash":
			$sql .= " AND idxTrash>'0' ORDER BY idx DESC";
			break;
		case "cnt":
			$sql .= " ORDER BY readCount ".$_GET['shc'];
			break;
		case "date":
			$sql .= " ORDER BY regDate ".$_GET['shc'];
			break;
		default :
			$sql .= " ORDER BY idx DESC";
			break;
	}

	//게시물 리스트 및 페이징 설정
	$row			= $cfg['module']['listHcount'] * $cfg['module']['listVcount'];
	$pagingInstance = new Paging($articleCount, $currentPage, $row, 5);
	$pagingInstance->addQueryString("&amp;".__PARM__."&amp;type=list&amp;sh=".$_GET['sh']."&amp;shc=".$_GET['shc']);
	$pagingResult	= $pagingInstance->result();
	$articleNum		= $articleCount - $pagingResult['LimitIndex'];

	//리스트 상단 : Start
	echo('<form id="listForm" name="listForm" method="post" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data" onsubmit="return false;">
	<input type="hidden" name="cate" value="'.$cfg['module']['cate'].'" />
	<input type="hidden" name="mode" value="'.$_GET['mode'].'" />
	<input type="hidden" name="currentPage" value="'.$currentPage.'" />
	<input type="hidden" id="formType" name="type" value="" />
	<input type="hidden" id="moveCate" name="moveCate" value="" />
	<input type="hidden" id="sh" name="sh" value="" />
	<input type="hidden" id="shc" name="shc" value="" />
	');
	/**
	 * 게시물 출력
	 */
	$n = 0;
	$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE ".$sql." ".$pagingResult['LimitQuery']);
	while($Rows = $db->fetch())
	{
		if(date("Y.m.d", time()) == date("Y.m.d", $Rows['regDate']))
			$date = date("H:i:s", $Rows['regDate']);
		else
			$date = date("Y.m.d H:i:s", $Rows['regDate']);
		$Rows['writer'] = ($cfg['site']['id'] == $Rows['id'] && $Rows['idxDepth']) ? '<strong>'.$Rows['writer'].'<strong>' : $Rows['writer'];
		$writer_id = ($Rows['id']!='' ? '('.$Rows['id'].')' : '(비회원)' );
		$commentCount 	= ($Rows['commentCount']) ? '&nbsp;<span class="small_red strong">('.$Rows['commentCount'].')</span>' : null;
		$icon 			.= $func->iconNew($Rows['regDate'], (86400*3), '<span><img src="'.$cfg['droot'].'user/default/image/icon/new.gif" alt="최근 게시물" /></span>');
		$check 			= (!$Rows['idxTrash'] && $member->checkPerm('0')) ? '<span class="keeping"><input type="checkbox" name="select[]" class="articleCheck" value="'.$Rows['seq'].'" title="선택" /></span>' : null;
		$url 			= '<span><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'">';
		if($Rows['useSecret'] == "Y")
		{
			$icon .= '<span><img src="'.$cfg['droot'].'user/default/image/icon/secret.gif" alt="비밀글" /></span>';
			$url = '<span><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&type='.$sess->encode('secret').'&num='.$Rows['seq'].'">';
		}
		$url 			= str_replace("cate=".$cfg['module']['cate']."&", "cate=".$Rows['cate']."&", $url);

	echo('<div class="clear"></div>			
	<div id="commentBox" class="commentBox">
		<div id="commentList'.$Rows['seq'].'" class="commentList no_line">
			<div class="author"><strong>'.$Rows['writer'].'</strong>&nbsp;&nbsp;<span></span></div>
			<div class="control">
			<span><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode('articleDel').'&amp;num='.$Rows['seq'].'&amp;idx='.$Rows['idx'].'#procForm"><img src="/user/default/image/button/btn_comment_s_del.gif" width="15" height="14" alt="삭제" /></a></span>
			<span><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=='.$sess->encode('memoModify').'&amp;num='.$Rows['seq'].'#procForm"><img src="/user/default/image/button/btn_comment_s_modify.gif" width="15" height="14" alt="수정" /></a></span>
			</div>
			<div class="date"><span><strong>'.$date.'</strong></span></div>
			<div class="clear"></div>
			<div class="content textContent">'.nl2br($Rows['content']).'</div>
		</div>
	</div>
	<div class="clear"></div>
	');
		$n++;
		$articleNum--;
		unset($sort,$depth,$commentCount,$icon,$check,$url,$iconFile,$date);
	}
	echo('</form>');

	//리스트 하단(버튼, 페이징) : Start
	echo('<div class="docBottom">
		<div class="pageNavigation">'.$pagingResult['PageLink'].'</div>
		<div class="searchBox">
			<fieldset>
			<legend>Search document</legend>
			<form name="frmBoard" method="get" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data" onsubmit="return validCheck(this);">
				<input type="hidden" name="cate" value="'.__CATE__.'" />
				<input type="hidden" name="mode" value="'.$_GET['mode'].'" />
				<input type="hidden" name="sh" value="all" />
			<span><input type="text" id="shc" name="shc" title="'.$lang['doc']['keyword'].'" class="input_gray center" style="width:90px;" req="required" trim="trim" value="'.$_GET['shc'].'" /></span>&nbsp;<span class="btnPack small"><button type="submit">'.$lang['doc']['search'].'</button></span>
			</form>
			</fieldset>
		</div>
		<div class="clear"></div>
	</div>');
}
?>