<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
unset($_SESSION['docSecret']);

//일정관리 사용여부에 따른 모듈 설정
require_once __PATH__."/_Lib/classCalendar.php";
$cal		= new Calendar();
$cal->Year	= ($_GET['year']) ? $_GET['year'] : date("Y"); //년도
$cal->Month	= ($_GET['month']) ? str_pad($_GET['month'], 2, "0", STR_PAD_LEFT) : date("m"); //월
$cal->Today	= ($_GET['day']) ? str_pad($_GET['day'], 2, "0", STR_PAD_LEFT) : date("d"); //일
$cal->configSetting();
$days		= ($_GET['days']) ? $_GET['days'] : 0; //일간
$week		= ($_GET['week']) ? $_GET['week'] : 0; //주간

if($_COOKIE["docList"] == 'month' || $_GET['listType'] == 'month'  || !$_GET['listType'] && !$_COOKIE["docList"])
{
	echo('<div class="docInfo">
	<div class="articleNum">'.$cal->setNavi().'</div>
	<div class="articleIcon"><span class="btnPack medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;listType=month" class="actRed">월간</a></span>&nbsp;<span class="btnPack medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;listType=week">주간</a></span>&nbsp;<span class="btnPack medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;listType=day" class="btnPack medium">일간</a></span></div>');
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
	echo('</div><!-- End.docInfo -->').PHP_EOL;
	echo('<table summary="'.$cfg['cate']['name'].'의 일정안내 입니다." class="docCal">
		<caption>'.$cfg['cate']['name'].'</caption>
		<thead>
			<tr>
			'.$cal->setWeek("List").'
			</tr>
		</thead>
		<tbody>
			<tr>
			'.$cal->setList($cfg['module']['listUnion']).'
			</tr>
		</tbody>
	</table>
	<script type="text/javascript">
	//<![CDATA[
		$(document).ready(function() 
		{
			$("tbody td").bind("mouseenter mouseleave", function(e){
				$(this).toggleClass("this");
			});
		});
	//]]>
	</script>');
	include __PATH__."modules/".$cfg['cate']['mode']."/docCalRecent.php";
	setcookie("docList", null);
	unset($_COOKIE["docList"]);
}
else
{
	if($_GET['listType']) { setcookie("docList", $_GET['listType']); }
	$_COOKIE["docList"] = ($_GET['listType']) ? $_GET['listType'] : $_COOKIE["docList"];

	switch($_COOKIE['docList'])
	{
		case "year":
			include __PATH__."modules/".$cfg['cate']['mode']."/docCalyear.php";
			break;
		case "week":
			include __PATH__."modules/".$cfg['cate']['mode']."/docCalweek.php";
			break;
		case "day":
			include __PATH__."modules/".$cfg['cate']['mode']."/docCalday.php";
			break;
	}
}
?>
