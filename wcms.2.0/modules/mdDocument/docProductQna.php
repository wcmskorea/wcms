<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
unset($_SESSION['docSecret']);

$cfg['cate']['mode'] = "mdDocument";
$config = $db->queryFetch(" SELECT cate,articled FROM `mdDocument__` WHERE boardType='QNA' limit 1 ");

$sql  = 'productSeq="'.$_GET['num'].'" AND boardType="QNA"';
$sql .= " AND cate='".$config['cate']."' AND idxTrash='0'";

//검색조건에 따른 sub-query 설정
$order = (!$_GET['shc'] || $_GET['shc'] == "ASC") ? "DESC" : "ASC"; //검색쿼리
$sql .= " ORDER BY idx DESC";

//게시물 리스트 및 페이징 설정
$articleCount		= $func->getArticledCount($cfg['cate']['mode']."__content", "cate='".$config['cate']."' AND idxTrash='0' AND productSeq='".$_GET['num']."'");
$row						= 10;
$pagingInstance = new Paging($articleCount, $currentPage, $row, 5);
$pagingInstance->addQueryString("&amp;".__PARM__."&amp;type=view&amp;num=".$_GET['num']."#qnaInfomation");
$pagingResult		= $pagingInstance->result();
$articleNum			= $articleCount - $pagingResult['LimitIndex'];

//리스트 상단 : Start
echo('<form id="listForm" name="listForm" method="post" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data" onsubmit="return false;">
');

//문서 정보 출력
echo('<div class="docInfo">');
	echo('<div class="articleNum">');
	echo('</div>');

	//문서(게시물)관리 버튼 출력
	echo('<div class="docBtn"><ul>');
		//if($_SESSION['uid'])
		//{
			echo('<li><span class="btnPack black medium strong"><a href="javascript:;" onclick="new_window(\''.$cfg['droot'].'?cate='.$config['cate'].'&amp;type='.$sess->encode("input").'&amp;mode=content&amp;productSeq='.$productSeq.'\',\'contentWcms\',800,600,\'no\',\'yes\');">상품관련 문의하기</a></span></li>');
		//}
	echo('</ul></div><div class="clear"></div>').PHP_EOL;
echo('</div>').PHP_EOL;

//리스트 본문 : Start
echo('<table summary="'.$cfg['cate']['name'].'" class="table_list" style="width:100%;">
<caption>'.$cfg['cate']['name'].'</caption>
<colgroup>
	<col width="80">
	<col>
	<col width="80">
	<col width="100">
</colgroup>
<thead>
<tr>');

echo('<th scope="col" class="first"><p class="center pd7">'.$lang['doc']['num'].'</p></th>');
echo('<th scope="col"><p class="center pd7">'.$lang['doc']['subject'].'</p></th>
	<th scope="col"><p class="center pd7">'.$lang['doc']['writer'].'</p></th>
	<th scope="col"><p class="center pd7">'.$lang['doc']['regDate'].'</p></th>
	</tr>
</thead>
<tbody>');

/**
 * 일반 게시물 출력
 */
$n = 0;
$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__content` WHERE ".$sql." ".$pagingResult['LimitQuery']);
while($Rows = $db->fetch())
{
	$date 				= date("Y.m.d", $Rows['regDate']);
	$Rows['subject'] 	= ($Rows['subject']) ? stripslashes($Rows['subject']) : "제목이 없습니다.";
	//$Rows['subject'] 	= $func->cutStr($Rows['subject'], 30, "...");
	$Rows['subject'] 	= ($Rows['idxTrash']) ? '<strike>'.$Rows['subject'].'</strike>' : $Rows['subject'];
	$content	= stripslashes($Rows['content']);
	$content	= str_replace("face=", "style=font-family:", $content);
	$content	= $func->matchCode($content);
	$content	= ($Rows['html'] == 'N') ? nl2br($content) : $content;

	$writer					= ($cfg['site']['icon']) ? '<img src="/user/default/image/icon/'.$cfg['site']['icon'].'" />' : '<strong>'.$Rows['writer'].'<strong>';
	$Rows['writer'] = ($Rows['useAdmin'] == 'Y') ? $writer : $Rows['writer'];

	if($Rows['idxDepth'])
	{
		for($i = 0; $i < $Rows['idxDepth']; $i++)
		{
			$depth .= "<span>　</span>";
		}
		$depth .= '<span>└</<span>';
	}

	$commentCount 	= ($Rows['commentCount']) ? '&nbsp;<span class="small_red strong">('.$Rows['commentCount'].')</span>' : null;
	$icon 			.= $func->iconNew($Rows['regDate'], (86400*3), '<span><img src="'.$cfg['droot'].'user/default/image/icon/new.gif" alt="최근 게시물" /></span>');
	$url 			= '<span><a href="javascript:;" onclick="f_ClickShow(\'divContent_'.$articleNum.'\')">';

	if($Rows['useSecret'] == "Y")
	{
		$icon .= '<span><img src="'.$cfg['droot'].'user/default/image/icon/secret.gif" alt="비밀글" /></span>';
		if($Rows['useSecret'] =='Y' && ($Rows['id'] != $_SESSION['uid'] || !$_SESSION['uid'])){
			$url   = '<span><a href="javascript:;" onclick="alert(\'본인만 확인이 가능합니다.\')">';
		}else{
			$url   = '<span><a href="javascript:;" onclick="f_ClickShow(\'divContent_'.$articleNum.'\')">';
		}
	}
	$qnaImg = '<img width="50" border="0" src="'.str_replace("/l-", "/s-", $imgDir[0]).'" align=absmiddle />';
	echo('<tr>
					<td scop="row"><p class="center">'.$articleNum.'</p></td>
					<td><p>'.$check.$depth.$url.$Rows['subject'].$commentCount.'</a>'.$icon.'</td>
					<td><p class="wrap70">'.$Rows['writer'].'</p></td>
					<td><p class="center pd4">'.$date.'</p></td>
				</tr>
				<tr id="divContent_'.$articleNum.'" style="display:none">
					<td scop="row" colspan="4" bgcolor="f5f5f5">
					<div class="contentBody textContent">'.$func->matchImage($content).'</div>');
					//본인글만 수정하기 버튼 출력
					if($Rows['id'] == $_SESSION['uid'])
					{
						echo('<div style="float:right"><span class="btnPack gray small"><a href="'.$_SERVER['PHP_SELF'].'?cate='.$config['cate'].'&amp;type='.$sess->encode('articleModify').'&amp;num='.$Rows['seq'].'">수정하기</a></span></div>');
					}
					//댓글 리스트(2012-11-19 추가)
					if($Rows['commentCount'] > '0')
					{
						echo('<div class="clear"></div>
						<div id="commentBox" class="commentBox">	');
						$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__comment` WHERE parent='".$Rows['seq']."' AND trashDate='0' ORDER BY regDate ASC ",2);
						$cmt = 1;
						while($ssRows = $db->fetch(2))
						{
							$ssRows['id']	= ($ssRows['id']) ? $ssRows['id'] : "비회원";
							$ssRows['id']	= (!$member->checkPerm(0) && $ssRows['id'] != "비회원") ? "(".str_replace(substr($ssRows['id'], 3, strlen($ssRows['id'])), "...", $ssRows['id']).")" : "(".$ssRows['id'].")";
							$comment		= stripslashes($ssRows['comment']);
							$new			= $func->iconNew($ssRows['regDate'], (86400*1), '<img src="'.__SKIN__.'image/icon/new.gif" style="vertical-align:top;" alt="신규 댓글" />');

							echo('<div id="commentList'.$ssRows['seq'].'" class="commentList'.($cmt == 1 ? ' no_line':'').'">
								<div class="author"><strong>'.$ssRows['writer'].'</strong>&nbsp;'.$ssRows['id'].'&nbsp;<span>'.$new.'</span></div>
								<div class="date"><span><strong>'.date("Y.m.d",$ssRows['regDate']).'</strong> '.date("H:i:s",$ssRows['regDate']).'</span></div>
								<div class="clear"></div>
								<div class="content textContent">'.nl2br($comment).'</div>
							</div>
							');
							$cmt++;
						}
						echo('</div>');
					}

	echo('</td></tr>');

	$n++;
	$articleNum--;
	unset($sort,$depth,$commentCount,$icon,$check,$url,$iconFile,$date);
}
if($n < 1)
{
	echo('<tr><td colspan="4" class="blank">등록된 상담내역이 없습니다</td></tr>');
}
echo('</tbody></table></form>');

//리스트 하단(버튼, 페이징) : Start
echo('<div class="docBottom">
	<div class="pageNavigation">'.$pagingResult['PageLink'].'</div>
	<div class="clear"></div>
</div>');
?>
<script type="text/javascript">
function f_ClickShow(id){
	var divId = "#"+id;
		$(divId).toggle();
	};
</script>