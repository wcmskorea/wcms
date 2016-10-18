<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
if(!$cfg['module']['contentAdd'] && $cfg['module']['list'] != 'Page') { $func->err("입력항목이 설정되지 않았습니다. 환경설정에서 입력항목을 셋팅해주세요"); }

/**
 * 입력 옵션 환경설정 병합
 */
$cfg['module'] = @array_merge($cfg['module'], (array)unserialize($cfg['module']['contentAdd']));


//수정일경우
if($_GET['num'])
{
	//인증 처리
	if(!$_SESSION['commentSecret'])
	{
		$ssRows = $db->queryFetch(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE seq='".$_GET['num']."' ", 3);
		if($db->getNumRows(3) < 1) { $func->err($lang['doc']['notfound']); }
		if(($ssRows['id'] != $_SESSION['uid'] || !$_SESSION['uid']) && !$member->checkPerm(0))
		{
			@ob_end_clean();
			$func->err($lang['doc']['notperm']);
		}
	}
	else
	{
		$query = " SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE seq='".$_GET['num']."' AND passwd='".$db->passType($cfg['site']['encrypt'], $_SESSION['commentSecret'])."'";
		$ssRows = $db->queryFetch($query, 3);
		if($db->getNumRows(3) < 1)
		{
			@ob_end_clean();
			$func->err($lang['doc']['notmatch']);
		}
	}
	$content = stripslashes($ssRows['content']);

	$next_type = "modifyPost";
} else {
	$next_type = "inputPost";
}
$content = ($content) ? $content : $cfg['module']['defaultContent'];
$form = new Form('element');
?>
<form name="commentForm" action="<?php echo($_SERVER['PHP_SELF']);?>" method="post" enctype="multipart/form-data" onsubmit="return validCheck(this)">
<input type="hidden" name="type" value="<?php echo($sess->encode($next_type));?>" />
<input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />
<input type="hidden" name="mode" value="<?php echo($_GET['mode']);?>" />
<input type="hidden" name="parent" value="<?php echo($Rows['seq']);?>" />
<input type="hidden" name="fileName" value="" />
<input type="hidden" name="fileCount" value="<?php echo($Rows['file']);?>" />
<input type="hidden" name="currentPage" value="<?php echo($currentPage);?>" />
<?php
$boardType = $db->queryFetchOne(" SELECT boardType FROM `mdDocument__` WHERE cate='".$cfg['module']['cate']."' limit 1 ");
?>
<input type="hidden" name="boardType" value="<?php echo($boardType);?>" />

<div id="commentForm<?php echo($Rows['seq']);?>" class="commentInput">
<div class="line">

<?php if(!$_GET['num']) { ?>
<div id="commentBtn<?php echo($Rows['seq']);?>" class="head">
	<span><img src="<?php echo(__SKIN__);?>image/icon/icon_comment.gif" width="12" height="14" alt="댓글 아이콘" /></span><strong><?php echo($lang['doc']['write']);?></strong>
</div>
<?php } else { ?>
<div id="commentBtn<?php echo($Rows['seq']);?>" class="head">
	<span><img src="<?php echo(__SKIN__);?>image/icon/icon_comment.gif" width="12" height="14" alt="댓글 아이콘" /></span><strong><?php echo($lang['doc']['modify']);?></strong>
	<span class="colorGray">- 선택하신 글을 수정합니다.</span>
</div>
<?php } ?>
<div class="clear"></div>

<div id="comment<?php echo($Rows['seq']);?>"<?php if(!$_GET['num']) { echo(' class="show"'); }?>>
	<?php
	echo('<div class="body">');

	if(!$_SESSION['uid'])
	{
		echo('
		<div class="commentAuthorName"><label for="writer">'.$lang['doc']['writer'].'</label> :');
		$form->addStart($lang['doc']['writer'], 'writer', 1, 0, 'M');
		$form->add('input', 'writer', $ssRows['writer'], 'width:70px;');
		$form->addEnd();
		echo('</div>
		<div class="commentAuthorPass"><label for="passwd">'.$lang['doc']['passwd'].'</label> :');
		$form->addStart($lang['doc']['passwd'], 'passwd', 1, 0, 'M');
		$form->add('input', 'passwd', null, 'width:70px;');
		$form->addEnd();
		echo('</div>');
	} else {
		echo('<input type="hidden" name="writer" value="'.$_SESSION['uname'].'" />');
	}

	$form->addStart($lang['doc']['content'], 'content', 1, 0, 'M');
	$form->add('textarea', 'content', $content, 'position:relative; width:99%; height:30px; margin:auto;');
	$form->addEnd();
	

	echo('</div><div class="clear"></div>');

	if(!$_GET['num'])
	{
		echo('<div class="commentSubmit"><span class="btnPack medium strong icon"><span class="add"></span><button type="submit">'.$lang['doc']['submit'].'</button></span></div>');
	}
	else
	{
		echo('<input type="hidden" name="num" value="'.$_GET['num'].'" />
		<div class="commentSubmit"><span class="btnPack small icon"><span class="add"></span><button type="submit">'.$lang['doc']['modifySubmit'].'</button></span>&nbsp;<span class="btnPack black small"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&type=list#commentList'.$_GET['num'].'">취소</a></span></div>');
	}

	?>
	<div class="clear"></div>
</div>
</div>
</div>
</form>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function()
{
	$("textarea").resizehandle();
//	$("#commentBtn<?php echo($Rows['seq']);?>").click(function(e){$("#comment<?php echo($Rows['seq']);?>").slideToggle('fast');});
});
//]]>
</script>
