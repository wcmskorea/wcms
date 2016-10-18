<?php
/* ======================================================================================
| 게시판 모듈 : VIEW페이지 하단 리스트
|----------------------------------------------------------------------------------------
| 이성준 (2009년 6월 5일 금요일)
*/
if(!defined('__CSS__')) header("HTTP/1.0 404 Not found");

/* --------------------------------------------------------------------------------------
| 검색조건에 따른 sub-query 설정
*/
$order = (!$_GET['shc'] || $_GET['shc'] == "ASC") ? "DESC" : "ASC"; #검색쿼리
$sq = ($cfg['module']['data'] == "A") ? "cate like '".__CATE__."%' " : "cate='".__CATE__."' "; #하위포함인지
switch ($_GET['sh'])
{
	case "title":
		$sq .= "AND subject like '%".$_GET['shc']."%'";
	break;
	case  "all":
		$sq .= "AND (subject like '%".$_GET['shc']."%' OR content like '%".$_GET['shc']."%')";
	break;
	case "writer":
		$sq .= "AND writer like '%".$_GET['shc']."%'";
	break;
	case "cnt":
		$sq .= "ORDER BY hit ".$_GET['shc'];
	break;
	case "date":
		$sq .= "ORDER BY date ".$_GET['shc'];
	break;
	default : $sq .= "ORDER BY dateReg DESC"; break;
}

/* --------------------------------------------------------------------------------------
| 게시물 리스트 및 페이징 설정
*/

$listCount		= explode(",", $cfg['module']['listCount']); // ['0']=>가로, ['1']=>세로
$row			= $listCount['0'] * $listCount['1']; // 한화면에 출력할 레코드 수
$block			= 10; // 한화면에 출력할 페이지링크수
$totalRec		= $db->getTotalCount($cfg['cate']['mode']."__content", $sq);
$currentPage	= ($_GET['currentPage']) ? $_GET['currentPage'] : 1;
$queryString	= "&amp;".__PARM__."&amp;sh=".$_GET['sh']."&amp;shc=".$_GET['shc']."#appList";
$pagingInstance = new Paging($totalRec, $currentPage, $row, $block);
$pagingInstance->addQueryString($queryString);
$pagingResult	= $pagingInstance->result();
$n				= $totalRec - $pagingResult['LimitIndex'];

/* --------------------------------------------------------------------------------------
| 리스트 본문 : Start
*/
echo('<table id="appList" summary="상담(일반) 신청현황" class="table_list" style="width:100%;">
<caption>상담(일반) 신청현황</caption>
<col width="60">
<col width="100">
<col>
<col width="100">
<col width="120">
<thead>
<tr>
  <th class="first"><p class="center">번호</p></th>
  <th><p class="center">신청자</p></th>
  <th><p class="center">신청정보</p></th>
  <th><p class="center">상태</p></th>
  <th><p class="center">신청일</p></th>
</tr>
</thead>
<tbody>');

$i = 1;
$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__content` WHERE ".$sq." ".$pagingResult['LimitQuery']." ");

while($Rows=$db->Fetch())
{
	$contentAdd		= (array)unserialize($Rows['contentAdd']);
	if($contentAdd['scheduleyear'] || $contentAdd['schedulemonth'] || $contentAdd['scheduleday']) {	//예약시간 입력안받는 경우(2012-05-02)
		$schedule			= mktime($contentAdd['schedulehour'], $contentAdd['schedulemin'], $contentAdd['schedulesec'], $contentAdd['schedulemonth'], $contentAdd['scheduleday'], $contentAdd['scheduleyear']);
	}
	//$name = mb_strcut($Rows['name'],0,6,'utf-8'); //글자 한자 자르기
	$date				= ($schedule) ? date("Y/m/d H:i", $schedule) : date("Y/m/d H:i", $Rows['dateReg']);
	
	//상담유형 설정
	$appDivision = ($division && $Rows['division']) ? $division[$Rows['division']] : $division['0'];
	$Rows['division'] = ($Rows['division'] == '100') ? "문자상담" : $appDivision;
	$Rows['division'] = (!$Rows['division']) ? "상담" : $Rows['division'];

	#--- 아이콘 설정
	$icon .= $func->iconNew($Rows['dateReg'], 86400, '<span><img src="/common/image/icon/new.gif" alt="최근 게시물" /></span>');
	#--- URL 설정
	$url = '<a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'&amp;currentPage='.$currentPage.'&amp;sh='.$_GET['sh'].'&amp;shc='.$_GET['shc'].'">';

	if($n%2 == 0) echo('<tr class="bg2">'); else echo('<tr class="bg1">');
	echo('<th><p class="center pd5">'.$n.'</p></th>
	<td><p class="center wrap100">'.$func->cutStr($Rows['name'],2).'○</p></td>
	<td>[<strong>'.$Rows['division'].'</strong>] 신청 하셨습니다.'.$icon.'</td>
	<td><p class="center">'.$result[$Rows['state']].'</p></td>
	<td><p class="center">'.$date.'</p></td></tr>');

	$i++;
	$n--;
	unset($icon,$url);
}
#--- 게시물이 없을때 공백 : Start
while($i < $row)
{
	if($n%2 == 0) echo('<tr class="bg2">'); else echo('<tr class="bg1">');
		echo('<td><p class="center pd5 gray">-</p></td>
		<td><p class="gray">-</p></td>
		<td><p class="gray">-</p></td>
		<td><p class="gray">-</p></td>
		<td><p class="gray">-</p></td></tr>');
	$i++;
  $n--;
}
echo('</tbody></table>');

/* --------------------------------------------------------------------------------------
| 리스트 하단(페이징) : Start
*/
echo('<div class="pageNavigation pd10">'.$pagingResult['PageLink'].'</div>');
?>
