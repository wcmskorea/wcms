<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }

//인증 처리
if(!$_SESSION['docSecret'])
{
	$Rows = $db->queryFetch(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE seq='".$_GET['num']."' ");
	if($db->getNumRows() < 1) { $func->err($lang['doc']['notfound']); }
	if($Rows['notice'] == 'N' && $Rows['useSecret'] =='Y' && ($Rows['id'] != $_SESSION['uid'] || !$_SESSION['uid']) && !$member->checkPerm('0')) { $func->err($lang['doc']['notperm']); }
	echo "<!--"." SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE seq='".$_GET['num']."' "."-->";
}
else
{
	$query = " SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE seq='".$_GET['num']."' AND passwd='".$db->passType($cfg['site']['encrypt'], $_SESSION['docSecret'])."' ";
	$Rows = $db->queryFetch($query);
	if($db->getNumRows() < 1) { $func->err($lang['doc']['notmatch']); }
	echo "<!--".$query."-->";
}
//휴지통 문서 열람권한 체크
if($Rows['idxTrash'] > 0 && !$member->checkPerm('0'))
{
	$func->err($lang['doc']['notperm']);
}

//공지문서를 제외한 열람권한 체크
if($Rows['notice'] < 2 && !$member->checkPerm(2))
{
	$func->err($lang['doc']['notperm']);
}

//조회수 적용
$db->query(" UPDATE `".$cfg['cate']['mode']."__content".$prefix."` SET readCount=readCount+1 WHERE seq='".$Rows['seq']."' AND ip<>'".$_SERVER['REMOTE_ADDR']."' ");

//데이터 정리
$display->title .= " > ".stripslashes($Rows['subject']); //<title>변경
$content	= stripslashes($Rows['content']);
$content	= str_replace("face=", "style=font-family:", $content);
$content	= $func->matchCode($content);
$content	= ($Rows['html'] == 'N') ? nl2br($content) : $content;
?>
<div class="document">
	<div class="docRead">
		<div class="readHeader">
			<div class="titleAndUser">
				<div class="title"><h4><?php echo($Rows['subject']);?></h4></div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="dateAndCount">
			<?php if(!$_GET['print']) {	?>
			<?php if($cfg['module']['readCount'] != 'N') { ?>
			<div class="readedCount" title="<?php echo($lang['doc']['readCount']);?>"><?php echo($lang['doc']['readCount']);?> : <span><?php echo(number_format($Rows['hit']));?></span></div><?php } ?>
        	<?php
			}
			?>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<!-- Content : Start --><div class="contentBody textContent"><?php echo($func->matchImage($content));?></div><!-- Content : End -->
		<div class="clear"></div>
		<?php
		//삭제된 게시물 안내문구 출력
		if($Rows['idxTrash'])
		{
			echo('<div class="center" style="border-bottom: #f3c534 1px dashed; border-left: #f3c534 1px dashed; padding-bottom: 10px; background-color: #fefeb8; padding-left: 10px; padding-right: 10px; border-top: #f3c534 1px dashed; border-right: #f3c534 1px dashed; padding-top: 10px" class=txc-textbox><p>'.$lang['doc']['msgTrashed'].'</p></div>');
		}
		?>
	</div>

<?php
//첨부파일 출력
if($cfg['module']['uploadCount'] > 0 && $Rows['fileCount'] > 0)
{
	echo('<div class="fileAttatch"><ul>');
	$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__file".$prefix."` WHERE parent='".$Rows['seq']."' ");
	while($sRows = $db->fetch())
	{
		$dir = $cfg['upload']['dir'].date("Y",$sRows['regDate'])."/".date("m",$sRows['regDate'])."/".$sRows['fileName'];
		echo('<li><span><img src="'.$cfg['droot'].'common/image/files/'.strtolower($sRows['extName']).'.gif" align="absmiddle" onError="this.src=\''.$cfg['droot'].'common/image/files/unKonwn.gif\'" width="16" height="16" /></span>&nbsp;<span><a href="'.$cfg['droot'].'addon/system/download.php?'.__PARM__.'&file='.$sess->encode($dir).'&name='.$sRows['realName'].'">'.$sRows['realName'].'</a></span></li>');
	}
	echo('</ul><div class="clear"></div></div>');
}

//프린트가 아닐경우 출력
if(!$_GET['print'])
{
	echo('<div class="docButton"><ul class="docBtn">');
	echo('<li><span class="btnPack gray small"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&type='.$sess->encode('articleModify').'&num='.$Rows['seq'].'#procForm">'.$lang['doc']['modify'].'</a></span></li>');
	echo('<li><span class="btnPack gray small"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&type='.$sess->encode('articleDel').'&num='.$Rows['seq'].'&idx='.$Rows['idx'].'#procForm">'.$lang['doc']['delete'].'</a></span></li>');
	if($member->checkPerm('0') && $Rows['idxTrash'] > 0)
	{
		echo('<li><span class="btnPack white small"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode("repair").'&num='.$Rows['seq'].'">'.$lang['doc']['repair'].'</a></span></li>');
	}
	echo('<li><span class="btnPack gray small"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&year='.$year.'&month='.$month.'">'.$lang['doc']['list'].'</a></span></li>
	<li><span class="btnPack gray small"><a href="javascript:;" onclick="new_window(\''.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'&amp;print=y\',\'print\',680,480,\'no\',\'yes\');" onkeypress="new_window(\''.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'&amp;print=y\',\'print\',680,480,\'no\',\'yes\');">'.$lang['doc']['print'].'</a></span></li>
	</ul></div><div class="clear"></div>');
	//댓글 리스트
	if($cfg['module']['comment'] == "Y") { include __PATH__."modules/mdComment/comment.php"; }
}
?>
</div>

<?php
	//하단 게시물 목록
	unset($Rows, $commentCount);
	if($cfg['module']['listView'] != 'N' && !$_GET['print']) { include __PATH__."modules/".$cfg['cate']['mode']."/doc".$cfg['module']['listView'].".php"; }
?>
