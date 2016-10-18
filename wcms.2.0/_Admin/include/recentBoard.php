<?php
require_once "../../_config.php";
require_once "./commonHeader.php";

/* 최근 게시물 */
echo('<div class="recent"><ul>');
$db->query(" SELECT * FROM `mdDocument__content` ORDER BY regDate DESC Limit 10 ");
while($Rows = $db->fetch())
{
	$config						= $db->queryFetch(" SELECT skin,cate,share FROM `mdDocument__` WHERE cate='".$Rows['cate']."' ", 2);
	$droot						= ($config['skin'] != 'default') ? $cfg['droot'].$config['skin']."/" : $cfg['droot'];
	$cateName 				= $func->getCateName($Rows['cate']);
	$url 							= $cfg['droot'].'?cate='.$Rows['cate'].'&type=view&num='.$Rows['seq'];
	$Rows['subject']	= stripslashes($Rows['subject']);
	$Rows['subject']	= strip_tags($Rows['subject']);
	$Rows['subject']	= (!$Rows['subject']) ? "제목없음" : $Rows['subject'];
	$icon							= $func->iconNew($Rows['regDate'], (86400*1), '<span class="icon"><img src="/user/default/image/icon/new.gif" width="11" height="13" alt="최근 게시물" /></span>');

	echo('<li class="boardTitle"><span class="date">'.date("Y.m.d H:i",$Rows['regDate']).'</span><span class="ip">'.$Rows['ip'].'</span><span class="colorGray">{'.$cateName.'}·</span><span class="title"><a href="javascript:new_window(\''.$droot.'?cate='.$Rows['cate'].'&amp;type=view&amp;num='.$Rows['seq'].'&amp;mode=content\',\'contentWcms\',1024,600,\'no\',\'yes\');" title="'.$Rows['writer'].'·'.$Rows['id'].' 님이 작성하실 게시글 입니다">'.$Rows['subject'].'</a></span>'.$icon.'</li>');

	$n++;
	unset($droot);
}
echo('</ul></div>');
?>
<div class="clear"></div>
