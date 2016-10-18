<?php
if(!defined('__CSS__')) header("HTTP/1.0 404 Not found");

/* --------------------------------------------------------------------------------------
| 검색조건에 따른 sub-query 설정
*/
$order = (!$_GET[shc] || $_GET[shc] == "ASC") ? "DESC" : "ASC"; //검색쿼리
$sq = ($cfg[content][data] == "A") ? "cate like '".__CATE__."%' " : "cate='".__CATE__."' "; //하위포함인지
switch ($_GET[sh]) {
	case "title":
		$sq .= "AND subject like '%".$_GET[shc]."%'";
	break;
	case  "all":
		$sq .= "AND (subject like '%".$_GET[shc]."%' OR content like '%".$_GET[shc]."%')";
	break;
	case "writer":
		$sq .= "AND writer like '%".$_GET[shc]."%'";
	break;
	case "cnt":
		$sq .= "ORDER BY hit ".$_GET[shc];
	break;
	case "date":
		$sq .= "ORDER BY date ".$_GET[shc];
	break;
	default : $sq .= "ORDER BY notice*date DESC"; break;
}

//게시물 리스트 및 페이징 설정
$listCount		= explode(",", $cfg['content']['listCount']); // [0]=>가로, [1]=>세로
$row			= $listCount[0] * $listCount[1]; // 한화면에 출력할 레코드 수
$block			= 7; // 한화면에 출력할 페이지링크수
$totalRec		= $func->getTotalCount($cfg['cate']['mode']."__content".$prefix, $sq);
$queryString	= "&amp;".__PARM__."&amp;type=list&amp;sh=".$_GET['sh']."&amp;shc=".$_GET['shc'];
$pagingInstance = new Paging($totalRec, $currentPage, $row, $block);
$pagingInstance->addQueryString($queryString);
$pagingResult	= $pagingInstance->result();
$n				= $totalRec - $pagingResult['LimitIndex'];

/* --------------------------------------------------------------------------------------
| 리스트 상단 : Start
*/
echo('<div class="boardInfo">');
echo('<div class="articleNum">'.$lang['board_article'].' <strong>'.number_format($totalRec).'</strong>');
//말머리 검색 출력
if($cfg['content']['division']) {
	echo('&nbsp;&nbsp;&nbsp;<span><select name="shc" class="bg_gray" onchange="$.searchBoardClass(this.value);">
	<option value="">말머리 검색</option>');
	$class = explode(",", $cfg['content']['division']);
	foreach($class AS $val) { print ('<option value="'.$val.'" >-> '.$val.' </option>'); }
	echo('</select></span>');
}
echo('</div>');
echo('<ul class="boardBtn ">');
if($member->checkPerm(3)) { echo('<li><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&type='.$sess->encode("input").'" class="button bbasic strong"><span>'.$lang['board_write'].'</span></a></li>'); }
if($member->checkPerm('0')) {
	echo('<li><span class="button bgray"><button type="button" class="blue" onclick="$.dialog(\''.$_SERVER['PHP_SELF'].'?'.__PARM__.'&type=move&mode=ajax\',null,300,120)">'.$lang['board_move'].'</button></span></li>');
	echo('<li><span class="button bgray"><button type="button" class="blue" onclick="$.dialog(\''.$_SERVER['PHP_SELF'].'?'.__PARM__.'&type=clear&mode=ajax\',null,300,120)">'.$lang['board_clear'].'</button></span></li>');
}
echo('</ul><div class="clear"></div>');
echo('</div>');

/* --------------------------------------------------------------------------------------
| 리스트 본문 : Start
*/
$i = 0;
$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE ".$sq." ".$pagingResult[LimitQuery]);
while($Rows=$db->fetch()) {
	//보안설정
	if($Rows[notice] < 2) {
		/* [0]관리, [1]접근, [2]열람권한, [3]작성 */
		if($member->checkPerm('2') == false) $func->errMsg("비밀번호가 틀렸거나 열람할 권한이 없습니다","history.back()");
	}
	//조회수 증가
	$db->query(" UPDATE `".$cfg['cate']['mode']."__content".$prefix."` SET hit=hit+1 WHERE seq='".$Rows[seq]."' AND ip<>'".$_SERVER[REMOTE_ADDR]."' ", 2);

	//데이터 정리
	$display->title = stripslashes($Rows['subject']); //<title>변경
	$Rows['id']	= ($Rows['id']) ? $Rows['id'] : "비회원";
	$Rows['id']	= (!$member->checkPerm('0') && $Rows['id'] != "비회원") ? "(".str_replace(substr($Rows['id'], 3, strlen($Rows['id'])), "...", $Rows['id']).")" : "(".$Rows['id'].")";
	$ip			= explode(".", $Rows['ip']);
	$ip			= (!$member->checkPerm('0')) ? $ip['0'].".".$ip['1'].".XXX.XXX" : $Rows['ip'];
	$email		= explode("@", $Rows['email']);
	$email		= (!$member->checkPerm('0')) ? "비공개" : $Rows['email'];
	$content	= stripslashes($Rows['content']);
	$content	= str_replace("face=", "style=font-family:", $content);
	$content	= $func->matchCode($content);
	$content	= ($Rows['html'] == 'N') ? nl2br($content) : $content;
	$url = $func->autoLink($Rows['url']);
	if($i == 0) { $func->title = $Rows[subject]; }
?>
<div class="boardDocument">
	<div class="boardRead">
		<div class="readHeader">
			<div class="titleAndUser">
				<div class="title"><h4><?php echo($Rows['subject']);?></h4></div>
        		<div class="clear"></div>
				<div class="author"><span class="nowrap"><?php echo($Rows['writer']);?>&nbsp;<?php echo($Rows['id']);?></span></div>
				<div class="clear"></div>
			</div>
			<div class="dateAndCount">
				<div class="ip">IP : <?php echo($ip);?></div>
				<div class="email">Email : <?php echo($email);?></div>
				<div class="date" title="<?php echo($lang['board_title_date']);?>"><?php echo($lang['board_title_date']);?> : <?php echo(date("Y.m.d",$Rows['date']));?> <span><?php echo(date("H:i:s",$Rows['date']));?></span></div>
				<?php if(!$_GET['print']) {	?>
				<div class="readedCount" title="<?php echo($lang['board_title_hit']);?>"><?php echo($lang['board_title_hit']);?> : <span><?php echo(number_format($Rows['hit']));?></span></div>
        		<?php
          			if($cfg['content']['comment'] == 'Y') { echo('<div class="commentCount">'.$lang['board_comment'].' : <span>'.$func->getCommentCount($cfg['cate']['mode'], $Rows['seq']).'</span></div>'); }
					if($cfg['content']['recom'] > 0) { echo('<div class="votedCount">'.$lang['board_recom'].' : <span>'.number_format($Rows['recom']).'</span></div>'); }
				} ?>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
		<?php
		//본문
		if($Rows[secret] == 'Y' && !$member->checkPerm('0'))	{
			echo('<!-- Content : Start --><div class="contentBody textContent"><p class="center gray">본 게시물은 비공개로 설정되어 있습니다.</p></div><!-- Content : End -->');
		}
		else {
			echo('<!-- Content : Start --><div class="contentBody textContent">'.$func->matchImage($content).'</div><!-- Content : End -->');
		}
		?>
		<div class="clear"></div>
	</div>

<?php
//첨부파일 출력
if($Rows['file'] > 0 && $cfg['content']['file'] > 0) {
	echo('<div class="fileAttatch"><ul>');
	$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__file".$prefix."` WHERE parent='".$Rows['seq']."' ", 2);
	while($sRows = $db->fetch(2)) {
		$dir = $upload['dir'].date("Y",$sRows['date'])."/".date("m",$sRows['date'])."/".$sRows['filename'];
		echo('<li><span><img src="'.$cfg['droot'].'image/files/'.strtolower($sRows['extname']).'.gif" align="absmiddle" onError="this.src=\'/image/files/unKonwn.gif\'" width="16" height="16" /></span><span><a href="'.$cfg['droot'].'modules/download.php?file='.$sess->encode($dir).'&name='.$sRows['realname'].'">'.$sRows['realname'].'</a></span></li>');
	}
	echo('</ul><div class="clear"></div></div>');
}

//프린트가 아닐경우 출력
if(!$_GET['print'])
{
	echo('<div class="clear"></div>
	<div class="boardButton"><ul class="boardBtn">');
	//if($member->checkPerm('0') === true && ($Rows['seq'] == $Rows['parent'] & $Rows['notice'] == 1)) { echo('<li><!--답변--><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode("rewrite").'&num='.$Rows['seq'].'" class="button bbasic strong"><span>'.$lang['board_rewrite'].'</span></a></li>'); }
	echo('<li><!--수정--><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&type='.$sess->encode('articleModify').'&num='.$Rows['seq'].'#procForm" class="button bgray"><span>'.$lang['board_modify'].'</span></a></li>');
	echo('<li><!--삭제--><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&type='.$sess->encode('articleDel').'&num='.$Rows['seq'].'#procForm" class="button bgray"><span>'.$lang['board_delete'].'</span></a></li>');
	echo('<li><!--인쇄--><a href="#none" onclick="new_window(\''.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'&amp;print=y\',\'print\',680,480,\'no\',\'yes\');" onkeypress="new_window(\''.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'&amp;print=y\',\'print\',680,480,\'no\',\'yes\');" class="button"><span>'.$lang['board_print'].'</span></a></li></ul></div><div class="clear"></div>');
	//댓글 리스트
	if($cfg['content']['comment'] == "Y") { include __PATH__."modules/mdComment/comment.php"; }
}
?>
</div>
<?php
	$i++;
}
/* 게시물이 없을때 */
if($i < 1) echo '<div class="boardDocument"><div class="blank">등록된 게시물이 없습니다.</div></div>';

//리스트 하단(페이징) : Start
echo('<div class="boardBottom"><div class="leftButtonBox"><span class="button"><button type="button" onclick="$.dialog(\''.$_SERVER[PHP_SELF].'\', \'&amp;'.__PARM__.'&mode=ajax&amp;type=search\',300,120)" class="gray">'.$lang[board_search].'</button></span></div><div class="rightButtonBox">');
if($member->checkPerm(3)) { echo('<a href="'.$_SERVER[PHP_SELF].'?'.__PARM__.'&amp;type='.$sess->encode("input").'" class="button bbasic strong"><span>'.$lang[board_write].'</span></a>'); }
echo('</div><div class="pageNavigation">'.$pagingResult[PageLink].'</div></div><div class="clear"></div>');

?>
<div class="clear"></div>
