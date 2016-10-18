<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
//수정일경우
if($_GET['rnum'])
{
	//인증 처리
	if(!$_SESSION['commentSecret'])
	{
		$ssRows = $db->queryFetch(" SELECT * FROM `".$cfg['cate']['mode']."__comment".$prefix."` WHERE seq='".$_GET['rnum']."' AND parent='".$Rows['seq']."' ", 3);
		if($db->getNumRows(3) < 1) { $func->err($lang['doc']['notfound']); }
		if(($ssRows['id'] != $_SESSION['uid'] || !$_SESSION['uid']) && !$member->checkPerm(0))
		{
			@ob_end_clean();
			$func->err($lang['doc']['notperm']);
		}
	}
	else
	{
		$query = " SELECT * FROM `".$cfg['cate']['mode']."__comment".$prefix."` WHERE seq='".$_GET['rnum']."' AND parent='".$Rows['seq']."' AND passwd='".$db->passType($cfg['site']['encrypt'], $_SESSION['commentSecret'])."'";
		$ssRows = $db->queryFetch($query, 3);
		if($db->getNumRows(3) < 1)
		{
			@ob_end_clean();
			$func->err($lang['doc']['notmatch']);
		}
	}
	$comment = stripslashes($ssRows['comment']);
	$comment = str_replace("face=", "style=font-family:", $comment);
}
$form = new Form('element');
?>
<form name="commentForm" action="<?php echo($_SERVER['PHP_SELF']);?>" method="post" enctype="multipart/form-data" onsubmit="return validCheck(this)">
<input type="hidden" name="type" value="<?php echo($sess->encode('cmtPost'));?>" />
<input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />
<input type="hidden" name="mode" value="<?php echo($_GET['mode']);?>" />
<input type="hidden" name="menu" value="<?php echo($_GET['menu']);?>" />
<input type="hidden" name="sub" value="<?php echo($_GET['sub']);?>" />
<input type="hidden" name="uri" value="<?php echo(__URI__);?>" />
<input type="hidden" name="parent" value="<?php echo($Rows['seq']);?>" />
<input type="hidden" name="replyMail" value="<?php echo($Rows['email']);?>" />

<div id="commentForm<?php echo($Rows['seq']);?>" class="commentInput">
<div class="line">

<?php if(!$_GET['rnum']) { ?>
	<div id="commentBtn<?php echo($Rows['seq']);?>" class="head">
		<span><img src="<?php echo(__SKIN__);?>image/icon/icon_comment.gif" width="12" height="14" alt="댓글 아이콘" /></span><strong><?php echo($lang['comment']['write']);?></strong>
		<span>클릭하면 입력 창이 열립니다.</span>&nbsp;<span class="colorGray">(<?php echo($lang['comment']['new']);?>)</span>
	</div>
<?php } else { ?>
	<div id="commentBtn<?php echo($Rows['seq']);?>" class="head">
		<span><img src="<?php echo(__SKIN__);?>image/icon/icon_comment.gif" width="12" height="14" alt="댓글 아이콘" /></span><strong><?php echo($lang['comment']['modify']);?></strong>
		<span class="colorGray">(최소 5자이상 작성하셔야 합니다)</span>
	</div>
<?php } ?>
<div class="clear"></div>

<div id="comment<?php echo($Rows['seq']);?>"<?php if(!$_GET['rnum']) { echo(' class="hide"'); }?>>
	<?php
	echo('<div class="body">');
	$form->addStart($lang['comment']['content'], 'comment', 1, 0, 'M');
	$form->add('textarea', 'comment', $comment, 'position:relative; width:99%; height:100px; margin:auto;');
	$form->addEnd();
	echo('</div><div class="clear"></div>');

	if(!$_GET['rnum'])
	{
		$email = $db->queryFetchOne("SELECT email FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE seq='".$_GET['num']."'");
		echo('<div class="commentSubmit"><span class="btnPack medium strong icon"><span class="add"></span><button type="submit">'.$lang['comment']['write'].'</button></span></div>');
	}
	else
	{
		echo('<input type="hidden" name="rnum" value="'.$_GET['rnum'].'" />
		<div class="commentSubmit"><span class="btnPack medium strong icon"><span class="add"></span><button type="submit">'.$lang['doc']['modifySubmit'].'</button></span>&nbsp;<span class="btnPack black medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&type=view&num='.$_GET['num'].'#commentList'.$_GET['rnum'].'">취소</a></span></div>');
	}

	if($cfg['module']['recommand'] > 0)
	{
		for($i=0; $i<=$cfg['module']['recommand']; $i++)
		{
			$recommand[$i] = $i." 점";
		}
		echo('<div class="commentAuthorRecom"><label for="voteCount">'.$lang['doc']['recom'].'</label> :');
		$form->addStart($lang['doc']['writer'], 'voteCount', 1, 0, 'M');
		$form->add('radio', $recommand, $ssRows['voteCount'], 'color:red;');
		$form->addEnd();
		echo('</div>');
	}

	if(!$_SESSION['uid'])
	{
		echo('<div class="commentAuthorPass"><label for="passwd">'.$lang['doc']['passwd'].'</label> :');
		$form->addStart($lang['doc']['passwd'], 'passwd', 1, 0, 'M');
		$form->add('input', 'passwd', null, 'width:70px;');
		$form->addEnd();
		echo('</div>
		<div class="commentAuthorName"><label for="writer">'.$lang['doc']['writer'].'</label> :');
		$form->addStart($lang['doc']['writer'], 'writer', 1, 0, 'M');
		$form->add('input', 'writer', $ssRows['writer'], 'width:70px;');
		$form->addEnd();
		echo('</div>');
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
	$("#commentBtn<?php echo($Rows['seq']);?>").click(function(e){$("#comment<?php echo($Rows['seq']);?>").slideToggle('fast');});
});
//]]>
</script>
