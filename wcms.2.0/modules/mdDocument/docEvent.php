<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }

if($cfg['module']['listUnion'] != 'Y')
{
	if($member->checkPerm('0'))
	{
		$articleCount = $cfg['module']['articled'];
		$sql = "cate='".$cfg['module']['cate']."'";
	}
	else
	{
		$articleCount = $cfg['module']['articled'] - $cfg['module']['trashed'];
		$sql = "cate='".$cfg['module']['cate']."' AND idxTrash='0'";
	}
}
else
{
	$getCount = $func->getTotalCount($cfg['cate']['mode']."__", "cate like '".$cfg['module']['cate']."%'");
	$articleCount = $getCount['articled'];
	$cfg['module']['trashed'] = $getCount['trashed'];
	if($member->checkPerm('0'))
	{
		$sql = "cate like '".$cfg['module']['cate']."%'";
	}
	else
	{
		$sql = "cate like '".$cfg['module']['cate']."%' AND idxTrash='0'";
	}
}

//검색조건에 따른 sub-query 설정
$order = (!$_GET['shc'] || $_GET['shc'] == "ASC") ? "DESC" : "ASC"; //검색쿼리

//검색타입별 서브쿼리 작성
switch ($_GET['sh'])
{
	case "division":
		$sql .= ($_GET['shc']) ? "AND subject like '[".$_GET['shc']."]%' ORDER BY idx DESC" : "ORDER BY idx DESC";
		break;
	case  "all":
		$sql .= "AND (subject like '%".$_GET['shc']."%' OR content like '%".$_GET['shc']."%') ORDER BY idx DESC";
		break;
	case "subject":
		$sql .= "AND subject like '%".$_GET['shc']."%' ORDER BY idx DESC";
		break;
	case "trash":
		$sql .= "AND idxTrash>'0' ORDER BY idx DESC";
		break;
	case "cnt":
		$sql .= "ORDER BY readCount ".$_GET['shc'];
		break;
	case "date":
		$sql .= "ORDER BY regDate ".$_GET['shc'];
		break;
	default :
		$sql .= "ORDER BY endDate DESC";
		break;
}

//게시물 리스트 및 페이징 설정
$row			= $cfg['module']['listHcount'] * $cfg['module']['listVcount'];
$pagingInstance = new Paging($articleCount, $currentPage, $row, 5);
$pagingInstance->addQueryString("&amp;".__PARM__."&amp;type=list&amp;sh=".$_GET['sh']."&amp;shc=".$_GET['shc']);
$pagingResult	= $pagingInstance->result();
$articleNum		= $articleCount - $pagingResult['LimitIndex'];

/**
 * 리스트 상단 : Star
 */
echo('<form id="listForm" name="listForm" method="post" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data" onsubmit="return false;">
<input type="hidden" name="cate" value="'.$cfg['module']['cate'].'" />
<input type="hidden" name="mode" value="'.$_GET['mode'].'" />
<input type="hidden" name="currentPage" value="'.$currentPage.'" />
<input type="hidden" id="formType" name="type" value="" />
<input type="hidden" id="moveCate" name="moveCate" value="" />
<input type="hidden" id="sh" name="sh" value="" />
<input type="hidden" id="shc" name="shc" value="" />').PHP_EOL;

//문서 정보 출력
echo('<div class="clear"></div><div class="docInfo">');
	echo('<div class="articleNum">');
		//말머리 검색 출력
		if($cfg['module']['division'] && $cfg['module']['opt_division'] != 'N')
		{
			echo('<div class="selectBox" style="width:100px;">
			<span class="selectCtrl"><span class="selectArrow"></span></span>
			<button class="selectValue" type="button">말머리 검색</button>
			<ul class="selectList1">
			<li><a href="'.$_SERVER['PHP_SELF'].'?cate='.$cfg['module']['cate'].'&amp;type=list">전체</a></li>');
			$class = explode(",", $cfg['module']['division']);
			foreach($class AS $val) { echo('<li><a href="'.$_SERVER['PHP_SELF'].'?cate='.$cfg['module']['cate'].'&amp;type=list&amp;sh=division&amp;shc='.urlencode($val).'">'.$val.'</a></li>'); }
			echo('</ul>
			</div>');
		}
	echo('</div>');
	//문서(게시물)관리 버튼 출력
	echo('<div class="docBtn"><ul>');
		if($member->checkPerm(3) && $type != 'view')
		{
			echo('<li><span class="btnPack medium strong"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode("input").'">'.$lang['doc']['write'].'</a></span></li>');
		}
		if($member->checkPerm('0') && $type != 'view' && $cfg['module']['listUnion'] != 'Y')
		{
			echo('<li><span class="btnPack gray medium"><button type="button" onclick="$.dialog(\''.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=clear&amp;mode=dialog\',null,300,140)">'.$lang['doc']['clear'].'</button></span></li>');
		}
		if($member->checkPerm('s') && $type != 'view')
		{
			echo('<li><span class="btnPack black medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode('articleOptimize').'#procForm">'.$lang['doc']['optimize'].'</a></span></li>');
			echo('<li><span class="btnPack black medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode('articleReset').'#procForm">'.$lang['doc']['reset'].'</a></span></li>');
		}
	echo('</ul></div><div class="clear"></div>').PHP_EOL;
echo('</div>');

/**
 * 리스트 본문 : Start
 */
echo('<div class="docThumb">').PHP_EOL;
$n = 0;

$cellWidth 	= 100 / $cfg['module']['listHcount'];

if($cfg['module']['thumbIsFix'] == "F"){
	$height 	= $cfg['module']['thumbMsizeHeight'];
}else{
	$thumbType 	= explode(",", $cfg['module']['thumbType']); //['3']['4']=>비율
	$height 	= ($cfg['module']['thumbType']) ? ceil(($cfg['module']['thumbMsize'] * $thumbType['1']) / $thumbType['0']) : ceil(($cfg['module']['thumbMsize'] * 3) / 4);
}

$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE ".$sql." ".$pagingResult['LimitQuery']);
while($Rows = $db->fetch())
{
//	데이터 정리
	$date 				= date("Y.m.d", $Rows['regDate']);
	$Rows['subject'] 	= ($Rows['subject']) ? stripslashes($Rows['subject']) : "제목이 없습니다.";
	$Rows['subject'] 	= $func->cutStr($Rows['subject'], $cfg['module']['cutSubject'], "...");
	$Rows['subject'] 	= ($Rows['idxTrash'] > 0) ? '<strike>'.$Rows['subject'].'</strike>' : $Rows['subject'];

	$commentCount 	= ($Rows['commentCount'] > 0) ? '&nbsp;('.$Rows['commentCount'].')' : null;
	$icon 			.= $func->iconNew($Rows['regDate'], (86400*3), '<span><img src="'.$cfg['droot'].'user/default/image/icon/new.gif" alt="최근 게시물" /></span>');
	$check 			= (!$Rows['idxTrash'] && $member->checkPerm('0')) ? '<span class="keeping"><input type="checkbox" name="select[]" class="articleCheck" value="'.$Rows['seq'].'" title="선택" /></span>' : null;
	$url 			= '<span><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'">';
	if($Rows['useSecret'] == "Y")
	{
		$icon .= '<span><img src="'.$cfg['droot'].'user/default/image/icon/secret.gif" alt="비밀글" /></span>';
		$url = '<span><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode('secret').'&amp;num='.$Rows['seq'].'">';
	}
	$url 			= str_replace("cate=".$cfg['module']['cate']."&amp;", "cate=".$Rows['cate']."&amp;", $url);

	//썸네일 이미지
	//첨부파일이 있을경우 첨부파일 이미지를
	//첨부파일이 없을경우 EDITOR에서 등록한 이미지를 리스트에 노출

	//썸네일 이미지
	$thumb = $db->queryFetch("SELECT * FROM `".$cfg['cate']['mode']."__file".$prefix."` WHERE parent='".$Rows['seq']."' AND LOWER(extName) IN ('jpg','gif','png','bmp') ORDER BY seq ASC Limit 1", 2);
	if($cfg['module']['skin'] != 'default' && $cfg['module']['share'])
	{
		$dir = str_replace($cfg['cate']['skin']."/", "default/",__SKIN__)."data/".date("Y",$thumb['regDate'])."/".date("m",$thumb['regDate'])."/m-".$thumb['fileName'];
	} else {
		$dir = __SKIN__."data/".date("Y",$thumb['regDate'])."/".date("m",$thumb['regDate'])."/m-".$thumb['fileName'];
	}
	
	//진행여부
	$isIng = ($Rows['endDate'] > 0 && date('Y-m-d',$Rows['endDate']) < date('Y-m-d',time())) ? '<span style="line-height: 150%;">[행사종료]</span><br>' : '<span style="color:red;line-height: 150%;">[진행중]</span><br>';

	$contentAdd						= explode("|", $Rows['contentAdd']);
	$Rows['contentAdd'] = stripslashes(nl2br($contentAdd[0]));
	$Rows['contentAdd'] = ($Rows['contentAdd']) ? $Rows['contentAdd'] : '&nbsp;';
	//리스트 출력
	if($n % $cfg['module']['listHcount'] == 0){ echo('<div class="line" style="width:100%;display:table">');}
	echo('<div class="cell" style="width:'.($cfg['module']['thumbMsize']+10).'px;text-align:center;">
					<div class="viewImage" style="width:'.$cfg['module']['thumbMsize'].'px;float: left; padding: 0 10px 0 0;">
						<p>'.$url.'<img src="'.$dir.'" title="'.$Rows['subject'].'" alt="'.$Rows['subject'].'" class="thumbNail" style="width:'.$cfg['module']['thumbMsize'].'px; height:'.$height.'px;" onerror="this.src=\'/user/default/image/icon/noimg_m.gif\'" onmouseover="overClass(this);" onmouseout="overClass(this);" /></a></p>
					</div>
					<div class="left" style="float:left; width:'.$cfg['module']['thumbMsize'].'px">
						<dl>
							<dd class="pd3" style="font-weight:bold;margin-bottom:5px;">'.$icon.$isIng.$url.$Rows['subject'].'</a><span class="commentCount">'.$commentCount.'</dd>
							<dd class="pd3">기간: '.date("Y.m.d",$Rows['regDate']).' ~ '.date("Y.m.d",$Rows['endDate']).'</dd>
							<dd class="pd3">'.$Rows['contentAdd'].'</dd>
						</dl>						
					</div>
				</div>
				');
	if($n % $cfg['module']['listHcount'] == $cfg['module']['listHcount'] - 1){ echo('</div>');}
	$n++;
	unset($icon, $url);
}

/**
 * 게시물이 없을때 공백 : Start
 */
while($n < $row)
{
	if($n % $cfg['module']['listHcount'] == 0){ echo('<div style="width:100%;">');}
	echo('<div class="cell" style="width:'.($cellWidth-1).'%;">
					<div class="viewImage" style="width:'.$cfg['module']['thumbMsize'].'px;float: left;padding: 0 10px 40px 0;">
						<p><img src="'.$cfg['droot'].'common/image/noimg_s.gif" alt="준비중" class="thumbNail" style="width:'.$cfg['module']['thumbMsize'].'px; height:'.$height.'px;" onmouseover="overClass(this);" onmouseout="overClass(this);" /></p>
					</div>
					<div class="center" style="width:170px;float: left;">
						<dl>
							<dd class="title" style="margin-bottom:10px;"></dd>
						</dl>
						<dl>
							<dd class="content" style="margin-bottom:10px;"></dd>
						</dl>
						<dl>
							<dd class="title"></dd>
						</dl>
					</div>
				</div>');
	if($n % $cfg['module']['listHcount'] == $cfg['module']['listHcount'] - 1){ echo('</div>');}
	$n++;
}
echo('<div class="clear"></div>
</div><!-- End : docThumb -->
</form>');

//리스트 하단(버튼, 페이징) : Start
echo('<div class="docBottom">
	<div class="pageNavigation">'.$pagingResult['PageLink'].'</div>
	<div class="searchBox">
		<fieldset>
		<legend>Search document</legend>
		<form name="frmBoard" method="get" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data" onsubmit="return validCheck(this);">
	    <input type="hidden" name="cate" value="'.$cfg['module']['cate'].'" />
	    <input type="hidden" name="mode" value="'.$_GET['mode'].'" />
	    <div class="selectBox" style="width:90px;">
			<span class="selectCtrl"><span class="selectArrow"></span></span>
			<div class="selectValue">제목</div>
			<ul class="selectList2">
			<li><input name="sh" id="sh0" type="radio" value="subject" checked="checked" class="option"><label for="sh0">제목</label></li>
			<li><input name="sh" id="sh1" type="radio" value="all" class="option"><label for="sh1">제목+내용</label></li>
			</ul>
		</div>
		<span><label for="shc" class="hide">검색어</label></span><span><input type="text" id="shc" name="shc" title="검색어" class="input_gray center" style="width:90px;" req="required" trim="trim" value="" /></span>&nbsp;<span class="btnPack small"><button type="submit">'.$lang['doc']['search'].'</button></span>
		</form>
		</fieldset>
	</div>
	<div class="countBox">
		<span><a href="'.$_SERVER['PHP_SELF'].'?cate='.$cfg['cate']['cate'].'&amp;mode='.$_GET['mode'].'">'.$lang['doc']['article'].': <strong>'.number_format($articleCount).'</strong> 건</a></span>');
		if($member->checkPerm('0'))
		{
			echo('<span>&nbsp;/&nbsp;</span><span><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;sh=trash">'.$lang['doc']['trash'].': <strong>'.number_format($cfg['module']['trashed']).'</strong> 건</a></span>');
		}
	echo('</div>
	<div class="clear"></div>
</div>');
?>
<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/selectBox.js"></script>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function()
{
	$("#allarticle").click(function()
	{
		if ($("#allarticle:checked").length > 0)
		{
			$("input[className=articleCheck]:checked").attr("checked", "");
		}
		else
		{
			$("input[className=articleCheck]:not(checked)").attr("checked", "checked");
		}
	});
});
//]]>
</script>
