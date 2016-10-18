<?php
if(!defined('__CSS__')) header("HTTP/1.0 404 Not found");

$listCount 	= explode(",", $cfg['content']['listCount']); //['0']=>가로, ['1']=>세로
//$sizeAll 	= (($listCount['0'] * ($imgSize['1']+16)) + ($listCount['0'] * 20));
//검색조건에 따른 sub-query 설정
$order = (!$_GET['shc'] || $_GET['shc'] == "ASC") ? "DESC" : "ASC"; //검색쿼리

//검색타입별 서브쿼리 작성
$sq .= "1 ORDER BY seq ASC";

//게시물 리스트 및 페이징 설정
$row			= $listCount[0] * $listCount[1]; // 한화면에 출력할 레코드 수
$block			= 10; // 한화면에 출력할 페이지링크수
$totalRec		= $func->getTotalCount($cfg['cate']['mode']."__content".$prefix, $sq);
$queryString	= "&amp;".__PARM__."&amp;type=list&amp;sh=".$_GET['sh']."&amp;shc=".$_GET['shc'];
$pagingInstance = new Paging($totalRec, $currentPage, $row, $block);
$pagingInstance->addQueryString($queryString);
$pagingResult	= $pagingInstance->result();
$n				= $totalRec - $pagingResult['LimitIndex'];

/**
 * 리스트 상단 : Star
 */
echo('<form id="listForm" name="listForm" method="post" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data" onsubmit="return false;">
<input type="hidden" name="cate" value="'.__CATE__.'" />
<input type="hidden" name="currentPage" value="'.$currentPage.'" />
<input type="hidden" id="formType" name="type" value="" />
<input type="hidden" id="moveCate" name="moveCate" value="" />
<input type="hidden" id="sh" name="sh" value="" />
<input type="hidden" id="shc" name="shc" value="" />');

echo('<div class="boardInfo">');
//말머리 검색 출력
if($cfg['content']['division'] && $cfg['content']['opt_division'] != 'N') {
	echo('&nbsp;&nbsp;&nbsp;<span><select name="shc" class="bg_gray" onchange="$.searchBoardClass(this.value);">
	<option value="">분류별 검색</option>');
	$class = explode(",", $cfg['content']['division']);
	foreach($class AS $val) { print ('<option value="'.$val.'" >-> '.$val.' </option>'); }
	echo('</select></span>');
}
echo('<div class="clear"></div></div>');

/**
 * 리스트 본문 : Star
 */
echo('<div class="boardThumb"><div style="width:580px; margin:auto;">');
$i = 0;
$n = 0;
$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE ".$sq." ".$pagingResult['LimitQuery'] );
while($Rows = $db->fetch()) {
	
	//데이터 정리
	$date = date("Y-m-d",$Rows['date']);
	$Rows['subject']= stripslashes($Rows['subject']);
	$Rows['subject']= $func->cutStr($Rows['subject'], $cfg['content']['subjectCut'], "...");
	$url = $func->cutStr($Rows['url'], $cfg['content']['subjectCut'], "...");
	$dir = str_replace("kr/","",__SKIN__)."data/".date("Y",$Rows['date'])."/".date("m",$Rows['date'])."/".$Rows['filename'];
	//리스트 출력
	echo('<div class="cell" style="margin:0 5px;"><div><a href="'.$Rows['url'].'" target="'.$Rows['target'].'"><img src="'.$dir.'" title="'.$Rows['subject'].'" alt="'.$Rows['subject'].'" class="thumbNail" style="width:'.$Rows['width'].'px; height:'.$Rows['height'].'px;" onerror="this.src=\'/image/noimg_m.gif\'" onmouseover="overClass(this);" onmouseout="overClass(this);" /></a></div>
	<div class="center">
		<div class="icon" style="margin-left:2px;">'.$icon.'</div>
		<div style="width:'.$Rows['width'].'px;">'.$Rows['subject'].'</div>
		</div>
	</div>');
	$n++;
	$i++;
	unset($icon, $url);
}
echo('</form>
<div class="clear"></div>
</div></div>');

/**
 * 리스트 하단(페이징) : Start
 */
echo('<div class="boardBottom">');
echo('<div class="pageNavigation">'.$pagingResult['PageLink'].'</div>
</div>
<div class="clear"></div>');
?>
