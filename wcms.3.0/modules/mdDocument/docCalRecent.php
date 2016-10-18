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
$sql .= " AND '".$cal->Year.$cal->Month."' BETWEEN FROM_UNIXTIME(regDate,'%Y%m') AND FROM_UNIXTIME(endDate,'%Y%m') ";
?>
<table summary="<?php echo($cfg['cate']['name']);?> 최근 게시목록 입니다." class="table_basic" style="width:100%;">
<caption><?php echo($cfg['cate']['name']);?></caption>
<col width="50">
<col width="190">
<col>
<thead>
	<tr>
		<th scope="col" class="first"><p class="center pd7">상 태</p></th>
		<th scope="col"><p class="center pd7">기 간</p></th>
		<th scope="col"><p class="right pd7">현재 선택일 : <span class="violet"><?php echo($cal->Year);?>년 <?php echo($cal->Month);?>월 <?php echo($cal->Today);?>일</span></p></th>
	</tr>
</thead>
<tbody>
<?php
$i = 1;
$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE ".$sql);
while($Rows = $db->fetch())
{
	$term = $Rows['endDate'] - $Rows['regDate'];
	if($term >= 86400)
	{
		$time = ceil($term/86400).'일간';
	}
	else if($term < 86400 && $term >= 3600)
	{
		$time = floor(($term/60)/60).'시간';
	}
	else
	{
		$time = floor($term/60).'분';
	}
	$sdate				= date("m.d", $Rows['regDate']);
	$edate				= date("m.d", $Rows['endDate']);
	$Rows['subject'] 	= ($Rows['subject']) ? stripslashes($Rows['subject']) : "제목이 없습니다.";
	//$Rows['subject'] 	= $func->cutStr($Rows['subject'], $cfg['module']['cutSubject'], "...");
	$Rows['subject'] 	= ($Rows['idxTrash']) ? '<strike>'.$Rows['subject'].'</strike>' : $Rows['subject'];
	if($cfg['module']['opt_division'] != 'N')
	{
		$Rows['subject'] = preg_replace("#\[(.*?)\]#si", '<span class="gray">\\0</span>', $Rows['subject']);
	}
	$commentCount 	= ($Rows['commentCount']) ? '&nbsp;<span class="small_red strong">('.$Rows['commentCount'].')</span>' : null;
	$url 			= '<span><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'">';
	if($Rows['useSecret'] == "Y")
	{
		$icon .= '<span>(비공개)</span>';
		$url = '<span><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode('secret').'&amp;num='.$Rows['seq'].'">';
	}
	$url 			= str_replace("cate=".$cfg['module']['cate']."&amp;", "cate=".$Rows['cate']."&amp;", $url);

	if($Rows['useNotice'] == 'Y')
	{
		echo('<tr><th><p class="center pd7 colorOrange">중요</p></th>');
	}
	else
	{
		if($Rows['endDate'] < time())
		{
			echo('<th><p class="center pd7 colorGray">종료</p></th>');
		}
		else
		{
			echo('<th><p class="center pd7 colorBlue">진행</p></th>');
		}
	}
	echo('<td><p>'.$sdate.'('.$cal->weekTitleKr[date("w",$Rows['regDate'])].') ~ '.$edate.'('.$cal->weekTitleKr[date("w",$Rows['endDate'])].') : <strong>'.$time.'</strong></p></td>
	<td>'.$icon.$url.$Rows['subject'].$commentCount.'</a></td></tr>');
	$i++;
	$n--;
	unset($icon,$url);
}
if($db->getNumRows() < 1)
{
	echo '<tr class="bg1"><td colspan="3" class="blank">등록된 일정이 없습니다</td></tr>';
}
?>
</tbody>
</table>
