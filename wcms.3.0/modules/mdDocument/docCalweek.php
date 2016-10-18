<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
unset($_SESSION['docSecret']);

if($cfg['module']['listUnion'] != 'Y')
{
	if($member->checkPerm('0'))
	{
		$sql = "cate='".$cfg['module']['cate']."'";
	}
	else
	{
		$sql = "cate='".$cfg['module']['cate']."' AND idxTrash='0'";
	}
}
else
{
	if($member->checkPerm('0'))
	{
		$sql = "cate like '".$cfg['module']['cate']."%'";
	}
	else
	{
		$sql = "cate like '".$cfg['module']['cate']."%' AND idxTrash='0'";
	}
}

echo('<div class="docInfo">
	<div class="articleNum">'.$cal->setNaviWeek($week).'</div>
	<div class="articleIcon"><span class="btnPack medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;listType=month">월간</a></span>&nbsp;<span class="btnPack medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;listType=week" class="actRed">주간</a></span>&nbsp;<span class="btnPack medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;listType=day" class="btnPack medium">일간</a></span></div>');
	//문서(게시물)관리 버튼 출력
	echo('<div class="docBtn"><ul>');
		if($member->checkPerm(3))
		{
			echo('<li><span class="btnPack medium strong"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode("input").'">'.$lang['doc']['write'].'</a></span></li>');
		}
		if($member->checkPerm('s'))
		{
			echo('<li><span class="btnPack black medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode('articleReset').'#procForm">'.$lang['doc']['reset'].'</a></span></li>');
		}
	echo('</ul></div><div class="clear"></div>').PHP_EOL;
echo('</div>');

//리스트 본문 : Start
echo('
<table summary="'.$cfg['content']['name'].' 게시물 목록입니다." class="table_basic" style="width:100%;">
	<caption>'.$cfg['content']['name'].'</caption>
	<col width="60">
	<col width="50">
	<col>
	<thead>
	<tr>
	<th scope="col" class="first"><p class="center pd7">날 짜</p></th>
	<th scope="col"><p class="center pd7">요 일</p></th>
	<th scope="col"><p class="center pd7">일 정 내 용</p></th>
	</tr>
	</thead>
<tbody>');
$week = ($week) ? $week - date("W", time()) : 0;
for($i=0; $i<7; $i++)
{
	$thisDate = mktime(0, 0, 0, date("m"), date("d") - date("w", time()) + ($week*7) + $i, date("Y"));
	$thisDay = date("d", $thisDate);

	if(date('d') == $thisDay)
	{
		echo('<tr class="bg2"><td><p class="center pd7 strong">'.$thisDay.'일</p></td>');
	}
	else
	{
		echo('<tr class="bg1"><td><p class="center pd7">'.$thisDay.'일</p></td>');
	}
	echo('<th><p class="center"><span style="color:'.$cal->weekColorArray[$i].';"><strong>'.$cal->weekTitleKr[$i].'</strong></span></p></th>
	<td>');
	$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE ".$sql." AND '".date('Y',$thisDate).date('m',$thisDate).$thisDay."' BETWEEN FROM_UNIXTIME(regDate,'%Y%m%d') AND FROM_UNIXTIME(endDate,'%Y%m%d') ORDER BY idx ASC");
	while($Rows = $db->fetch())
	{
		$Rows['subject'] 	= ($Rows['subject']) ? stripslashes($Rows['subject']) : "제목이 없습니다.";
		$Rows['subject'] 	= $func->cutStr($Rows['subject'], $cfg['module']['cutSubject'], "...");
		$Rows['subject'] 	= ($Rows['idxTrash']) ? '<strike>'.$Rows['subject'].'</strike>' : $Rows['subject'];
		if($cfg['module']['opt_division'] != 'N')
		{
			$Rows['subject'] = preg_replace("#\[(.*?)\]#si", '<span class="gray">\\0</span>', $Rows['subject']);
		}
		$commentCount 	= ($Rows['commentCount']) ? '&nbsp;<span class="small_red strong">('.$Rows['commentCount'].')</span>' : null;
		$url 			= '<span><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'">';
		if($Rows['useNotice'] == "Y")
		{
			$icon .= ',<span>중요</span>';
		}
		if($Rows['useSecret'] == "Y")
		{
			$icon .= ',<span>비공개</span>';
			$url = '<span><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode('secret').'&amp;num='.$Rows['seq'].'">';
		}
		$url = str_replace("cate=".$cfg['module']['cate']."&amp;", "cate=".$Rows['cate']."&amp;", $url);
		echo('<p class="pd3">(<span>'.date('H:i', $Rows['regDate']).'~'.date('H:i', $Rows['endDate']).'</span>'.$icon.')&nbsp;'.$url.$Rows['subject'].$commentCount.'</a></span></p>');
	    unset($icon,$url);
	}
	echo('</td></tr>');
}
echo('</tbody>
</table>');
?>
