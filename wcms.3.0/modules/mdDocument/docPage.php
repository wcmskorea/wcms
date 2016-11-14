<?php
if(!defined('__CSS__')) header("HTTP/1.0 404 Not found");

if($member->checkPerm('0'))
{
	$articleCount = $cfg['module']['articled'];
	$sql = "cate='".$cfg['module']['cate']."'";
}
else
{
	$articleCount = $cfg['module']['articled']-$cfg['module']['trashed'];
	$sql = "cate='".$cfg['module']['cate']."' AND idxTrash='0'";
}

//게시물 리스트 및 페이징 설정
$pagingInstance = new Paging($articleCount, $currentPage, $cfg['module']['listHcount'] * $cfg['module']['listVcount'], 10);
$pagingInstance->addQueryString("&amp;".__PARM__."&amp;type=list&amp;sh=".$_GET['sh']."&amp;shc=".$_GET['shc']);
$pagingResult	= $pagingInstance->result();

/* --------------------------------------------------------------------------------------
| 리스트 본문 : Start
*/
/* 게시물이 없을때 */

//운영자만 작성버튼 출력
if($member->checkPerm('0') && !$_GET['print'])
{
	echo('<div class="docInfo">
	<ul class="docBtn">
	<li><span class="btnPack green medium strong"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&type='.$sess->encode("input").'">콘텐츠 '.$lang['doc']['write'].'</a></span></li>
		</ul>
	</div>
	<div class="clear"></div>');
}

if($articleCount > 0)
{
	$query = " SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE ".$sql." ORDER BY idx DESC ".$pagingResult['LimitQuery'];
	$db->query($query);
	while($Rows=$db->fetch())
	{
		//조회수 업데이트
		//$db->query(" UPDATE `".$cfg['cate']['mode']."__content".$prefix."` SET readCount=readCount+1 WHERE seq='".$Rows['seq']."' AND ip<>'".$_SERVER['REMOTE_ADDR']."' ", 2);

		//데이터 정리
		if(is_file(__HOME__."/cache/document/".__CATE__.".html"))
		{
			$content = stripslashes(@file_get_contents(__HOME__."/cache/document/".__CATE__.".html"));
		} else
		{
			$content	= stripslashes($Rows['content']);
			$content	= str_replace("face=", "style=font-family:", $content);
			$content	= $func->matchCode($content);
			$content	= ($Rows['html'] == 'N') ? nl2br($content) : $content;
		}

		//본문 출력
		echo('<div class="document">
		<!-- Content : Start -->
			<div class="contentBody textContent">'.$content.'</div>
			<!-- Content : End -->
		</div>');

		//삭제된 문서 안내문구 노출
		if($Rows['idxTrash'])
		{
			echo('<div class="center" style="border-bottom: #f3c534 1px dashed; border-left: #f3c534 1px dashed; padding-bottom: 10px; background-color: #fefeb8; padding-left: 10px; padding-right: 10px; border-top: #f3c534 1px dashed; border-right: #f3c534 1px dashed; padding-top: 10px" class=txc-textbox><p>'.$lang['doc']['msgTrashed'].'</p></div>');
		}

		//첨부파일 출력
		if($cfg['cate']['uploadCount'] > 0 && $Rows['fileCount'] > 0)
		{
			echo('<div class="fileAttatch"><ul>');
			$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__file".$prefix."` WHERE parent='".$Rows['seq']."' ", 2);
			while($sRows = $db->fetch(2))
			{
				$dir = $cfg['upload']['dir'].date("Y",$sRows['regDate'])."/".date("m",$sRows['regDate'])."/".$sRows['fileName'];
				echo('<li><span><img src="'.$cfg['droot'].'common/image/files/'.strtolower($sRows['extName']).'.gif" align="absmiddle" onError="this.src=\''.$cfg['droot'].'common/image/files/unKonwn.gif\'" width="16" height="16" /></span>&nbsp;<span><a href="'.$cfg['droot'].'addon/system/download.php?'.__PARM__.'&file='.$sess->encode($dir).'&name='.$sRows['realName'].'">'.$sRows['realName'].'</a></span></li>');
			}
			echo('</ul></div>');
		}

		//프린트가 아닐경우 버튼 출력
		if($member->checkPerm('0') && !$_GET['print'])
		{
			echo('<div class="clear"></div><div class="docButton">');
			echo('<ul class="docBtn">');
			echo('<li><span class="btnPack gray medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&type='.$sess->encode('articleModify').'&num='.$Rows['seq'].'#procForm">'.$lang['doc']['modify'].'</a></span></li>');
			echo('<li><span class="btnPack gray medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&type='.$sess->encode('articleDel').'&num='.$Rows['seq'].'&idx='.$Rows['idx'].'#procForm">'.$lang['doc']['delete'].'</a></span></li>');
			if($Rows['idxTrash'])
			{
				echo('<li><span class="btnPack white medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode("repair").'&num='.$Rows['seq'].'">'.$lang['doc']['repair'].'</a></span></li>');
			}
			echo('<li><span class="btnPack gray medium"><a href="javascript:;" onclick="new_window(\''.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'&amp;print=y\',\'print\',680,480,\'no\',\'yes\');" onkeypress="new_window(\''.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'&amp;print=y\',\'print\',680,480,\'no\',\'yes\');">'.$lang['doc']['print'].'</a></span></li>');
			echo('</ul><div class="clear"></div></div>');
		}

		//댓글모듈 출력
		if($cfg['module']['comment'] == "Y") { include __PATH__."modules/mdComment/comment.php"; }
	}

	//프린트가 아닐경우 버튼 출력
	if($member->checkPerm('0') && !$_GET['print'])
	{
		echo('<div class="pageNavigation">'.$pagingResult['PageLink'].'</div>');
	}
}
else
{
	//데이터 정리
	if(is_file(__HOME__."/cache/document/".__CATE__.".html"))
	{
		$content = stripslashes(@file_get_contents(__HOME__."/cache/document/".__CATE__.".html"));
	}
	//else
	//{
		//$content = '<p class="center"><img src="'.$cfg['droot'].'common/image/construction.gif" width="550" title="'.$lang['module']['construction'].'" alt="'.$lang['module']['construction'].'" \></p>';
	//}
	//본문 출력
	echo('<div class="document">
	<!-- Content : Start -->
		<div class="contentBody textContent">'.$func->matchImage($content).'</div>
		<!-- Content : End -->
	</div>');
}
?>
