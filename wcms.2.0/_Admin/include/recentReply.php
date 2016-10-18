<?php
require_once "../../_config.php";
require_once "./commonHeader.php";

/* 최근 댓글 */
echo('<div class="recent"><ul>');
    $db->query(" SELECT * FROM `mdDocument__comment` ORDER BY regDate DESC LIMIT 10 ");
    while($Rows = $db->fetch())
    {
      $Rows['comment']	= stripslashes($Rows['comment']);
      $Rows['comment']	= strip_tags($Rows['comment']);
      $Rows['comment']	= $func->cutStr($Rows['comment'], 40)."...";
      $icon				= $func->iconNew($Rows['regDate'], (86400*1), '<span class="icon"><img src="'.$cfg['droot'].'common/image/icon/new.gif" width="11" height="13" alt="최근 게시물" /></span>');
      echo('<li class="boardTitle"><span class="date">'.date("Y.m.d H:i",$Rows['regDate']).'</span>'.$icon.'<span>('.$Rows['writer'].')</span><span class="title"><a href="'.$cfg['droot'].'?cate='.$Rows['cate'].'&type=view&num='.$Rows['parent'].'#reply" title="'.$Rows['comment'].'" target="blank">'.$Rows['comment'].'</a></span></li>');
      $n++;
    }
echo('</ul></div>');
?>
<div class="clear"></div>
