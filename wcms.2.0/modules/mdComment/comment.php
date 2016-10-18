<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }

if($_GET['rnum']) 
{
	$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__comment".$prefix."` WHERE seq='".$_GET['rnum']."' AND parent='".$Rows['seq']."' AND trashDate='0' ORDER BY regDate ASC ", 3);
} 
else 
{
	$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__comment".$prefix."` WHERE parent='".$Rows['seq']."' AND trashDate='0' ORDER BY regDate ASC ", 3);
}

$rst = $db->getNumRows(3);
if($rst > 0) { echo('<br /><div id="commentBox" class="commentBox">'); }

$n = 1;
while($ssRows=$db->fetch(3)) 
{
	$ssRows['id']	= ($ssRows['id']) ? $ssRows['id'] : "비회원";
	$ssRows['id']	= (!$member->checkPerm(0) && $ssRows['id'] != "비회원") ? "(".str_replace(substr($ssRows['id'], 3, strlen($ssRows['id'])), "...", $ssRows['id']).")" : "(".$ssRows['id'].")";

	$writer					= ($cfg['site']['icon']) ? '<img src="/user/default/image/icon/'.$cfg['site']['icon'].'" />' : '<strong>'.$ssRows['writer'].'</strong>';
	$ssRows['writer'] = ($ssRows['useAdmin'] == 'Y') ? $writer : $ssRows['writer'];

	$comment		= stripslashes($ssRows['comment']);
	$new			= $func->iconNew($ssRows['regDate'], (86400*1), '<img src="'.__SKIN__.'image/icon/new.gif" style="vertical-align:top;" alt="신규 댓글" />');
	$star			= ($cfg['module']['recommand'] > 0) ? ($ssRows['voteCount']/$cfg['module']['recommand']) * 100 : 0;
	?>
	<div id="commentList<?php echo($ssRows['seq']);?>" class="commentList <?php if($n == 1) { echo('no_line'); }?>">
		<div class="author"><?php echo($ssRows['writer']);?>&nbsp;<?php echo($ssRows['id']);?>&nbsp;<span><?php echo($new);?></span></div>
		<?php if($cfg['module']['recommand'] > 0) { echo('<div class="starPoint"><span style="width:'.$star.'%;"></span><span class="textPoint">'.$ssRows['voteCount'].'</span></div>');}?>
		<div class="control"><span><a href="<?php echo($_SERVER['PHP_SELF']);?>?<?php echo(__PARM__);?>&amp;type=<?php echo($sess->encode('cmtDel'));?>&amp;num=<?php echo($Rows['seq']);?>&amp;rnum=<?php echo($ssRows['seq']);?>#procForm"><img src="<?php echo(__SKIN__);?>image/button/btn_comment_s_del.gif" width="15" height="14" alt="댓글 삭제" /></a></span>
		<span><a href="<?php echo($_SERVER['PHP_SELF']);?>?<?php echo(__PARM__);?>&amp;type=<?php echo($sess->encode('cmtModify'));?>&amp;num=<?php echo($Rows['seq']);?>&amp;rnum=<?php echo($ssRows['seq']);?>#procForm"><img src="<?php echo(__SKIN__);?>image/button/btn_comment_s_modify.gif" width="15" height="14" alt="댓글 수정" /></a></span>
		</div>
		<div class="date"><span><strong><?php echo(date("Y.m.d",$ssRows['regDate']));?></strong> <?php echo(date("H:i:s",$ssRows['regDate']));?></span></div>
		<div class="clear"></div>
		<div class="content textContent"><?php echo(nl2br($comment));?></div>
	</div>
	<?php
	$n++;
}
if($rst > 0) 
{ 
	echo('</div>'); 
}
echo('<div class="clear"></div>');
unset($comment);
if($member->checkPerm(3) === true || ($Rows['endDate'] > time() && $cfg['module']['list'] == 'Forum'))
{
	include __PATH__."modules/mdComment/commentForm.php";
}
?>
			
