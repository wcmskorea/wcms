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
	<div class="articleNum">'.$cal->setNaviDay($days).'</div>
	<div class="articleIcon"><span class="btnPack medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;listType=month">월간</a></span>&nbsp;<span class="btnPack medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;listType=week">주간</a></span>&nbsp;<span class="btnPack medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;listType=day" class="actRed">일간</a></span></div>');
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
	<col width="50">
	<col>
	<thead>
	<tr>
	<th scope="col" class="first"><p class="center pd7">시 간</p></th>
	<th scope="col"><p class="center pd7">일 정 내 용</p></th>
	</tr>
	</thead>
<tbody>');

for($i=0; $i<=23; $i++)
{
	$i = str_pad($i, 2, "0", STR_PAD_LEFT);
	if(date('H', time()) == $i)
	{
		echo('<tr class="bg2"><th><p class="center pd7 strong">'.$i.'시</p></th><td>');
	}
	else
	{
		echo('<tr class="bg1"><th><p class="center pd7">'.$i.'시</p></th><td>');
	}
	$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE ".$sql." AND ".$cal->Year.$cal->Month.$cal->Today.$i." BETWEEN FROM_UNIXTIME(regDate,'%Y%m%d%H') AND FROM_UNIXTIME(endDate,'%Y%m%d%H') ORDER BY idx ASC ");
	$n = 1;
	$count = $db->getNumRows();
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

		echo('<p class="pd3">(<span>'.date('i분', $Rows['regDate']).' ~ '.date('i분', $Rows['endDate']).'</span>'.$icon.')'.$url.$Rows['subject'].$commentCount.'</a></span></p>');
		$n++;
		unset($icon,$url);
	}
	echo('</td></tr>');
	unset($icon,$url,$depth,$n,$contents);
}
echo('</tbody>
</table>
');
?>
