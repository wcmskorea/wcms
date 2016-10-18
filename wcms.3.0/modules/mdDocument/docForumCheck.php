<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
#하단 리스트 시작
$getCount = $func->getTotalCount($cfg['cate']['mode']."__comment", "cate like '".$cfg['module']['cate']."%'");
$articleCount = $getCount['articled'];

$cfg['module']['trashed'] = $getCount['trashed'];
$sql = "cate like '".$cfg['module']['cate']."%' ORDER BY idx DESC";

//게시물 리스트 및 페이징 설정
$row = $cfg['module']['listHcount'] * $cfg['module']['listVcount'];
$pagingInstance = new Paging($articleCount, $currentPage, $row, 5);
$pagingInstance->addQueryString("&amp;".__PARM__."&amp;type=list&amp;sh=".$_GET['sh']."&amp;shc=".$_GET['shc']);
$pagingResult	= $pagingInstance->result();
$articleNum		= $articleCount - $pagingResult['LimitIndex'];

//문서 정보 출력
echo('<div class="docInfo">');
	echo('<div class="articleNum">');
		//말머리 검색 출력
		if($cfg['module']['division'] && $cfg['module']['opt_division'] != 'N')
		{
			echo('<div class="selectBox" style="width:100px;">
			<span class="selectCtrl"><span class="selectArrow"></span></span>
			<button class="selectValue" type="button">말머리 검색</button>
			<ul class="selectList1">
			<li><a href="'.$_SERVER['PHP_SELF'].'?cate='.$cfg['module']['cate'].'&amp;type=list&amp;mode='.$_GET['mode'].'">전체</a></li>');
			$class = explode(",", $cfg['module']['division']);
			foreach($class AS $val) { echo('<li><a href="'.$_SERVER['PHP_SELF'].'?cate='.$cfg['module']['cate'].'&amp;type=list&amp;mode='.$_GET['mode'].'&amp;sh=division&amp;shc='.urlencode($val).'">'.$val.'</a></li>'); }
			echo('</ul>
			</div>');
		}
	echo('</div>');
echo('</div>').PHP_EOL;

//리스트 본문 : Start
echo('<table summary="'.$cfg['cate']['name'].'" class="table_basic" style="width:100%;">
<caption>'.$cfg['cate']['name'].'</caption>
<colgroup>
	<col width="50">
	<col>
	<col width="60">
	<col width="100">
	<col width="100">
</colgroup>
<thead>
<tr>
	<th scope="col" class="first"><p class="center pd7">연번</p></th>
	<th scope="col"><p class="center pd7">주 제</p></th>
	<th scope="col"><p class="center pd7">참여자</p></th>
	<th scope="col"><p class="center pd7">개설일</p></th>
	<th scope="col"><p class="center pd7">폐설일</p></th>
	</tr>
</thead>
<tbody>');

/**
 * 일반 게시물 출력
 */
$n = 0;
$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE ".$sql." ".$pagingResult['LimitQuery']);
while($Rows = $db->fetch())
{
	$startDate 				= date("y.m.d H시", $Rows['regDate']);
	$endDate					= date("y.m.d H시", $Rows['endDate']-60);
	$Rows['subject'] 	= ($Rows['subject']) ? stripslashes($Rows['subject']) : "제목이 없습니다.";
	$Rows['subject'] 	= $func->cutStr($Rows['subject'], $cfg['module']['cutSubject'], "...");
	$Rows['subject'] 	= ($Rows['idxTrash']) ? '<strike>'.$Rows['subject'].'</strike>' : $Rows['subject'];
	if($cfg['module']['opt_division'] != 'N')
	{
		$Rows['subject'] = preg_replace("#\[(.*?)\]#si", '<span class="gray">\\0</span>', $Rows['subject']);
	}
	if($Rows['idxDepth'])
	{
		for($i = 0; $i < $Rows['idxDepth']; $i++)
		{
			$depth .= "<span>　</span>";
		}
		$depth .= '<span>└</<span>';
	}
	$Rows['writer'] = ($cfg['site']['id'] == $Rows['id'] && $Rows['idxDepth']) ? '<strong>'.$Rows['writer'].'<strong>' : $Rows['writer'];
	$icon 			.= $func->iconNew($Rows['regDate'], (86400*3), '<span><img src="'.$cfg['droot'].'user/default/image/icon/new.gif" alt="최근 게시물" /></span>');
	$check 			= (!$Rows['idxTrash'] && $member->checkPerm('0')) ? '<span class="keeping"><input type="checkbox" name="select[]" class="articleCheck" value="'.$Rows['seq'].'" title="선택" /></span>' : null;
	$url 			= '<span><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'">';
	if($Rows['useSecret'] == "Y")
	{
		$icon .= '<span><img src="'.$cfg['droot'].'user/default/image/icon/secret.gif" alt="비밀글" /></span>';
		$url = '<span><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&type='.$sess->encode('secret').'&num='.$Rows['seq'].'">';
	}
	$url 			= str_replace("cate=".$cfg['module']['cate']."&", "cate=".$Rows['cate']."&", $url);

	//if($articleNum % 2 == 0) { echo('<tr class="bg1">'); } else { echo('<tr class="bg2">'); }
	echo('<tr>
	<td scop="row"><p class="center">'.$articleNum.'</p></td>
		<td><p>'.$check.$depth.$url.$Rows['subject'].$commentCount.'</a>'.$icon.'</td>
		<td><p class="center pd4">'.$Rows['commentCount'].'명</p></td>
		<td><p class="center pd4">'.$startDate.'</p></td>
		<td><p class="center pd4">'.$endDate.'</p></td>
	</tr>');

	$n++;
	$articleNum--;
	unset($sort,$depth,$commentCount,$icon,$check,$url,$iconFile,$date);
}

echo('</tbody></table></form>');