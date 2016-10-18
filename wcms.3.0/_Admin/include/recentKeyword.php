<?php
require_once "../../_config.php";
require_once "./commonHeader.php";

/* 전체 인기 키워드 */
echo('<div class="recent"><ul>');
		$n = 1;
    $db->query(" SELECT *,SUM(hit) AS total FROM `mdAnalytic__keyword` GROUP BY `keyword` ORDER BY total DESC LIMIT 10 ");
    while($Rows = $db->fetch())
    {
      $icon = $func->iconNew($Rows['regDate'], (86400*1), '<span class="icon"><img src="'.$cfg['droot'].'common/image/icon/new.gif" width="11" height="13" alt="최근 게시물" /></span>');
      echo('<li class="boardTitle"><span class="date">'.$n.'위 - ('.$Rows['total'].')</span>'.$icon.'<span class="title">'.$Rows['keyword'].'</span></li>');
      $n++;
    }
echo('</ul></div>');
?>
<div class="clear"></div>
