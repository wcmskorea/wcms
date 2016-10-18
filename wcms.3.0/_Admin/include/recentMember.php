<?php
require_once "../../_config.php";
require_once "./commonHeader.php";

/* 최근 접속회원 */
echo('<div class="recent"><ul>');
if($func->checkModule('mdMember'))
{
	$n = 0;
	$db->query(" SELECT * FROM `mdMember__account` WHERE 1 ORDER BY info DESC Limit 15 ");
	while($sRows = $db->fetch())
	{
		$addr01			= ($sRows['address01']) ? explode(" ", $sRows['address01']) : "-";
		$sRows['mobile']= ($sRows['mobile01']) ? $sRows['mobile01'] : "-";
		$sRows['sex']	= ($sRows['sex']) ? ($sRows['sex']=='1' || $sRows['sex']=='3') ? "남" : "여" : "-";
		$info			= ($sRows['info']) ? str_replace("|"," IP: ",$sRows['info']) : "0000.00.00 00:00 IP: 000.000.000.000";
		$icon 			= $func->iconNew($sRows['dateLogin'], (86400*1), '<span class="icon"><img src="/image/icon/new.gif" width="11" height="13" alt="new" /></span>');
		echo('<li class="boardTitle"><span class="date">시간: '.$info.'</span>'.$icon.'<span class="title"><a href="javascript:;" onclick="$.memberInfo(\''.$sRows['id'].'\');" title="'.$sRows['name'].'회원 상세보기"><strong>'.$sRows['name'].'</strong> ('.$sRows['id'].')</a></span></li>');
		$n++;
	}
	while($n < $limit)
	{
		echo('<li class="boardTitle"><span class="date">'.date("m.d").'</span><span class="title" style="color:#999;">아직 데이터가 존재하지 않습니다</span></li>');
		$n++;
	}
}
else
{
	echo('<li class="boardTitle"><span class="title" style="color:#999;">설정된 모듈이 존재하지 않습니다.</span></li>');
}
echo('</ul></div>');
?>
<div class="clear"></div>
