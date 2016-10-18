<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }

//인증 처리
if(!$_SESSION['docSecret'])
{
	$Rows = $db->queryFetch(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE seq='".$_GET['num']."' ");
	if($db->getNumRows() < 1) { $func->err($lang['doc']['notfound']); }
	if($Rows['useNotice'] == 'N' && $Rows['useSecret'] == 'Y' && ($Rows['id'] != $_SESSION['uid'] || !$_SESSION['uid']) && !$member->checkPerm('0')) { $func->err($lang['doc']['notperm'],"back"); }
}
else
{
	$query = " SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE seq='".$_GET['num']."' AND passwd='".$db->passType($cfg['site']['encrypt'], $_SESSION['docSecret'])."' ";
	$Rows = $db->queryFetch($query);
	if($db->getNumRows() < 1) { $func->err($lang['doc']['notmatch'],"back"); }
}

//휴지통 문서 열람권한 체크
if($Rows['idxTrash'] > 0 && !$member->checkPerm('0'))
{
	$func->err($lang['doc']['notperm'],"back");
}

//공지문서를 제외한 열람권한 체크
if($Rows['notice'] < 2 && !$member->checkPerm(2))
{
	$func->err($lang['doc']['notperm'],"back");
}

//조회수 적용
//$db->query(" UPDATE `".$cfg['cate']['mode']."__content".$prefix."` SET readCount=readCount+1 WHERE seq='".$Rows['seq']."' AND ip<>'".$_SERVER['REMOTE_ADDR']."' ");
$db->query(" UPDATE `".$cfg['cate']['mode']."__content".$prefix."` SET readCount=readCount+1 WHERE seq='".$Rows['seq']."' ");

//데이터 정리
$display->title = stripslashes($Rows['subject'])." (".$display->title.")"; //<title>변경

$Rows['id']	= ($Rows['id']) ? $Rows['id'] : "비회원";
$Rows['id']	= (!$member->checkPerm('0') && $Rows['id'] != "비회원") ? "(".str_replace(substr($Rows['id'], 3, strlen($Rows['id'])), "...", $Rows['id']).")" : "(".$Rows['id'].")";
$Rows['id']	= ($cfg['module']['writerInfo'] != 'M' || $member->checkPerm('0')) ? $Rows['id'] : null;

$writer					= ($cfg['site']['icon']) ? '<img src="/user/default/image/icon/'.$cfg['site']['icon'].'" />' : '<strong>'.$Rows['writer'].'</strong>';
$Rows['writer'] = ($Rows['useAdmin'] == 'Y') ? $writer : $Rows['writer'];

$ip					= explode(".", $Rows['ip']);
//$ip					= (!$member->checkPerm('0')) ? $ip['0'].".".$ip['1'].".XXX.XXX" : $Rows['ip'];
$ip					= (!$member->checkPerm('0')) ? '<span class="small_gray">비공개</span>' : $Rows['ip'];
$ip					= ($cfg['module']['writerInfo'] != 'M') ? "IP : ".$ip : null;

$email			= (!$member->checkPerm('0')) ? '<span class="small_gray">비공개</span>' : $Rows['email'];
$email			= (!$email) ? "X" : $email;
$email			= ($cfg['module']['writerInfo'] != 'M') ? "Email : ".$email : null;

$content		= stripslashes($Rows['content']);
$content		=	str_replace("face=", "style=font-family:", $content);
$content		= $func->matchCode($content);
$content		= ($Rows['useHtml'] == 'N') ? nl2br($content) : $content;

$contentAdd	= explode("|", $Rows['contentAdd']);

$url 		= $func->autoLink($Rows['url']);
$year		= date('Y', $Rows['regDate']);
$month		= date('m', $Rows['regDate']);
?>
<div class="document">
	<div class="docRead">
		<div class="readHeader">
			<div class="titleAndUser">
				<div class="title"><h4><?php if($cfg['module']['opt_category'] != 'N' && $cfg['module']['viewCategory'] == 'Y' && $Rows['category']) echo('['.$Rows['category'].']');?><?php echo($Rows['subject']);?></h4></div>
				<?php if($cfg['module']['writerInfo'] != 'N') {	?>
				<div class="author"><span class="nowrap"><?php echo($Rows['writer']);?>&nbsp;<?php echo($Rows['id']);?></span></div>
				<div class="clear"></div>
				<?php } ?>
			</div>
			<?php if($cfg['module']['writerInfo'] != 'N') {	//등록자 정보 노출 여부 ?>
			<div class="dateAndCount">
				<div class="ip"><?php echo($ip);?></div>
				<div class="email"><?php echo($email);?></div>
				<?php if($cfg['module']['list'] == 'Cal') {	//일정모듈의 경우 시작일 종료일 노출 ?>
				<div class="date" title="<?php echo($lang['doc']['endDate']);?>"><?php echo($lang['doc']['endDate']);?> : <?php echo(date("Y.m.d",$Rows['endDate']));?> <?php echo(date("H:i",$Rows['endDate']));?></div>
				<div class="date" title="<?php echo($lang['doc']['stDate']);?>"><?php echo($lang['doc']['stDate']);?> : <?php echo(date("Y.m.d",$Rows['regDate']));?> <?php echo(date("H:i",$Rows['regDate']));?></div>
				<?php } else { ?>
				<div class="date" title="<?php echo($lang['doc']['regDate']);?>"><?php echo($lang['doc']['regDate']);?> : <?php echo(date("Y.m.d",$Rows['regDate']));?> <span><?php echo(date("H:i:s",$Rows['regDate']));?></span></div>
				<?php } ?>
				<?php if(!$_GET['print']) {	?>
				<?php if($cfg['module']['readCount'] != 'N') { ?>
				<div class="readedCount" title="<?php echo($lang['doc']['readCount']);?>">
						<?php echo($lang['doc']['readCount']);?> : <span><?php echo(number_format($Rows['readCount']));?></span></div><?php } ?>
        		<?php
          	if($cfg['module']['comment'] == 'Y') { echo('<div class="commentCount">'.$lang['doc']['comment'].' : <span>'.$Rows['commentCount'].'</span></div>'); }
						if($cfg['module']['recommand'] > 0) { echo('<div class="votedCount">'.$lang['doc']['recom'].' : <span>'.number_format($Rows['voteCount']).'</span></div>'); }
				} ?>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
			<?php } ?>
		</div>
		<?php if($Rows['url']) {?><div class="link"><strong class="linkTitle">링크 :</strong> <a href="http://<?php echo($Rows['url']);?>" target="_blank">http://<?php echo($func->cutStr($Rows['url'], 100, "..."));?></a></div><?php } ?>

		<!-- Content : Start -->
		<div class="contentBody textContent"><?php echo($func->matchImage($content));?></div>
		<div class="clear"></div>
		<!-- Content : End -->

		<!-- Add Content : Start -->
		<?php
		/* 추가입력 사항 노출 */
		if($cfg['module']['addContent'])
		{
			$form = new Form('table');
			$addOpt = explode(",", $cfg['module']['addContent']);
			echo('<table class="table_list" summary="해당 게시물의 추가 입력양식 입니다." style="width:100%;">
			<caption>추가입력 사항</caption>
			<colgroup>
				<col width="17%">
				<col>
			<colgroup>
			<tbody>');
			foreach($addOpt AS $key=>$val)
			{
				$form->addStart($val, 'addopt['.$key.']', 1);
				$form->addHtml('<ol><li><p class="pd5 colorBlack">'.$contentAdd[$key].'&nbsp;</p></li></ol>');
				$form->addEnd(1);
			}
			echo('</tbody>
			</table>
			');
		}
		?>
		<!-- Add Content : End -->
		<?php 
			//페이스북 버튼
			if($GLOBALS['cfg']['site']['facebook']) { ?><div class="snsFacebookButton"><div class="fb-like" data-href="" data-send="false" data-layout="button_count" data-width="100" data-show-faces="true"></div></div>
		<?php } ?>
		<?php 
			//트위터 버튼
			if($GLOBALS['cfg']['site']['twitter']) { ?><div class="snsTwitterButton"><a href="https://twitter.com/share" class="twitter-share-button" data-lang="en">Tweet</a></div>
		<?php } ?>
		<div class="clear"></div>
		<?php if($cfg['module']['replyImage']=="Y" && $Rows['idxDepth'] > 0) { ?><div class="replyContent"></div><?php } ?>
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
	if($Rows['fileCount'] > 0 && $cfg['module']['download'] != 'N')
	{
		echo('<div class="fileAttatch"><ul>');
		$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__file".$prefix."` WHERE parent='".$Rows['seq']."' ");
		while($sRows = $db->fetch())
		{
			$dir = $cfg['upload']['dir'].date("Y",$sRows['regDate'])."/".date("m",$sRows['regDate'])."/".$sRows['fileName'];
			echo('<li><span><img src="'.$cfg['droot'].'common/image/files/'.strtolower($sRows['extName']).'.gif" align="absmiddle" onError="this.src=\''.$cfg['droot'].'common/image/files/unKonwn.gif\'" width="16" height="16" /></span>&nbsp;<span><a href="'.$cfg['droot'].'addon/system/download.php?'.__PARM__.'&file='.$sess->encode($dir).'&name='.urlencode($sRows['realName']).'">'.$sRows['realName'].'</a></span></li>');
		}
		echo('</ul><div class="clear"></div></div>');
	}

	//프린트가 아닐경우 출력
	if(!$_GET['print'])
	{
		echo('<div class="docButton"><ul class="docBtn">');
		if($member->checkPerm(4) && $Rows['useNotice'] == 'N' && $cfg['module']['list'] == 'List')
		{
			echo('<li><span class="btnPack medium strong"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode("reply").'&amp;num='.$Rows['seq'].'">'.$lang['doc']['rewrite'].'</a></span></li>');
		}
		if($member->checkPerm('3'))
		{
			echo('<li><span class="btnPack gray medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode('articleModify').'&amp;num='.$Rows['seq'].'#procForm">'.$lang['doc']['modify'].'</a></span></li>');
			echo('<li><span class="btnPack gray medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode('articleDel').'&amp;num='.$Rows['seq'].'&amp;idx='.$Rows['idx'].'#procForm">'.$lang['doc']['delete'].'</a></span></li>');
		}
		if($member->checkPerm('0') && $Rows['idxTrash'] > 0)
		{
			echo('<li><span class="btnPack white medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode("repair").'&amp;num='.$Rows['seq'].'">'.$lang['doc']['repair'].'</a></span></li>');
		}
		echo('<li><span class="btnPack gray medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;year='.$year.'&amp;month='.$month.'">'.$lang['doc']['list'].'</a></span></li>
		<li><span class="btnPack gray medium"><a href="javascript:;" onclick="new_window(\''.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'&amp;print=y\',\'print\',680,480,\'no\',\'yes\');" onkeypress="new_window(\''.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'&amp;print=y\',\'print\',680,480,\'no\',\'yes\');">'.$lang['doc']['print'].'</a></span></li>
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
