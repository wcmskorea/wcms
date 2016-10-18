<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
unset($_SESSION['docSecret']);

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

//게시물 리스트 및 페이징 설정
$row			= $cfg['module']['listHcount'] * $cfg['module']['listVcount'];
$pagingInstance = new Paging($articleCount, $currentPage, $row, 5);
$pagingInstance->addQueryString("&amp;".__PARM__."&amp;type=list&amp;sh=".$_GET['sh']."&amp;shc=".$_GET['shc']);
$pagingResult	= $pagingInstance->result();
$articleNum		= $articleCount - $pagingResult['LimitIndex'];

//문서 정보 출력
echo('<div class="docInfo">');
	echo('<div class="articleNum">');
		//말머리 검색 출력
		if($member->checkPerm('0'))
		{
			echo('<span class="keeping"><input type="checkbox" checked="checked" id="allarticle" name="allarticle" title="전체선택" /><label for="allarticle" class="strong">전체선택</label></span>').PHP_EOL;
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
			echo('<li><span class="btnPack gray medium"><button type="button" onclick="$.dialog(\''.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=move&amp;mode=dialog\',null,300,140)">'.$lang['doc']['move'].'</button></span></li>');
			echo('<li><span class="btnPack gray medium"><button type="button" onclick="$.dialog(\''.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=clear&amp;mode=dialog\',null,300,140)">'.$lang['doc']['clear'].'</button></span></li>');
		}
		if($member->checkPerm('s') && $type != 'view')
		{
			echo('<li><span class="btnPack black medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode('articleOptimize').'#procForm" class="button medium bblack">'.$lang['doc']['optimize'].'</a></span></li>');
			echo('<li><span class="btnPack black medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode('articleReset').'#procForm" class="button medium bblack">'.$lang['doc']['reset'].'</a></span></li>');
		}
	echo('</ul></div><div class="clear"></div>').PHP_EOL;
echo('</div>').PHP_EOL;
?>

<div class="cube"><div class="line center" style="padding:15px;">
	<fieldset>
	<legend>Search document</legend>
	<form name="frmBoard" method="get" action="<?php echo($_SERVER['PHP_SELF']);?>" enctype="multipart/form-data" onsubmit="return validCheck(this);">
    <input type="hidden" name="cate" value="<?php echo($cfg['module']['cate']);?>" />
    <input type="hidden" name="mode" value="<?php echo($_GET['mode']);?>" />
    <input type="hidden" name="sh" value="all" />
	<span class="keeping"><label for="shc" class="strong">자주하는 질문</label></span>&nbsp;<span><input type="text" id="shc" name="shc" title="검색어" class="input_gray center" style="width:300px;" req="required" trim="trim" value="" /></span>&nbsp;<span class="btnPack black medium"><button type="submit">검색하기</button></span>
	</form>
	</fieldset>
</div></div>

<div class="docTab" style="margin-top:15px;">
	<ul class="tabBox">
		<li class="tab<?php if($_GET['sh'] == 'all' || !$_GET['shc']){echo(' on');}?>"><p><a href="<?php echo($_SERVER['PHP_SELF'].'?cate='.$cfg['module']['cate'].'&amp;mode='.$_GET['mode']); ?>#content">전 체</a></p></li>
		<?php
		if($cfg['module']['division'])
		{
			$class = explode(",", $cfg['module']['division']);
			foreach($class AS $val)
			{
				echo('<li class="tab');
				if($_GET['shc'] == $val) { echo(' on'); }
				echo('"><p><a href="'.$_SERVER['PHP_SELF'].'?cate='.$cfg['module']['cate'].'&amp;type=list&amp;sh=division&amp;shc='.urlencode($val).'&amp;mode='.$_GET['mode'].'#content">'.$val.'</a></p></li>').PHP_EOL;
			}
		}
		?>

	</ul>
	<div class="clear"></div>
</div>

<?php
//리스트 상단 : Start
echo('<form id="listForm" name="listForm" method="post" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data" onsubmit="return false;">
<input type="hidden" name="cate" value="'.$cfg['module']['cate'].'" />
<input type="hidden" name="mode" value="'.$_GET['mode'].'" />
<input type="hidden" name="currentPage" value="'.$currentPage.'" />
<input type="hidden" id="formType" name="type" value="" />
<input type="hidden" id="moveCate" name="moveCate" value="" />
');

//리스트 본문 : Start
echo('<table summary="'.$cfg['module']['name'].'" class="table_board" style="width:100%;">
<caption>'.$cfg['module']['name'].'</caption>
<colgroup>
	<col width="50">
	<col>
</colgroup>
<tbody>');

/**
 * 공통 질문 출력
 */
$i = 0;
$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE useNotice='Y' AND ".$sql."");
while($Rows = $db->fetch())
{
	$date 				= date("Y.m.d", $Rows['regDate']);
	$Rows['subject'] 	= ($Rows['subject']) ? stripslashes($Rows['subject']) : "제목이 없습니다.";
	$Rows['subject'] 	= $func->cutStr($Rows['subject'], $cfg['module']['cutSubject'], "...");
	$Rows['subject'] 	= ($Rows['idxTrash']) ? '<strike>'.$Rows['subject'].'</strike>' : $Rows['subject'];
	$Rows['content'] 	= $func->cutStr(stripslashes(strip_tags($Rows['content'])), 200, "...");
	if($cfg['module']['opt_division'] != 'N')
	{
		$Rows['subject'] = preg_replace("#\[(.*?)\]#si", null, $Rows['subject']);
	}
	$commentCount		= ($Rows['commentCount']) ? '&nbsp;<span class="medium_red strong">('.$Rows['commaentCount'].')</span>' : null;
	$url 				= '<span><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'">';
	$url 				= str_replace("cate=".$cfg['module']['cate']."&amp;", "cate=".$Rows['cate']."&amp;", $url);
	echo('<tr>
		<td scop="row"><p class="center pd5 strong violet">공통</p></td>
		<td class="subject"><dl><dt>'.$url.'<strong>'.$Rows['subject'].'</strong>'.$commentCount.'</a></span></dt>
		<dd>'.$Rows['content'].'</dd></dl>
		</td>
	</tr>');

	$i++;
	unset($sort,$commentCount,$check,$url,$iconFile,$date);
}

/**
 * 일반 게시물 출력
 */

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
	case "writer":
		$sql .= "AND writer like '%".$_GET['shc']."%' ORDER BY idx DESC";
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
		$sql .= "ORDER BY idx DESC";
		break;
}

$i = 0;
$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE ".$sql." ".$pagingResult['LimitQuery']);
while($Rows = $db->fetch())
{
	$date 				= date("Y.m.d", $Rows['regDate']);
	$Rows['subject'] 	= ($Rows['subject']) ? stripslashes($Rows['subject']) : "제목이 없습니다.";
	$Rows['subject'] 	= $func->cutStr($Rows['subject'], $cfg['module']['cutSubject'], "...");
	$Rows['subject'] 	= ($Rows['idxTrash']) ? '<strike>'.$Rows['subject'].'</strike>' : $Rows['subject'];
	$Rows['content'] 	= $func->cutStr(stripslashes(strip_tags($Rows['content'])), 200, "...");
	if($cfg['module']['opt_division'] != 'N')
	{
		$Rows['subject'] = preg_replace("#\[(.*?)\]#si", '<strong class="colorActive">\\0</strong>', $Rows['subject']);
	}
	if($Rows['idxDepth'])
	{
		for($i = 0; $i < $Rows['idxDepth']; $i++)
		{
			$depth .= "<span>　</span>";
		}
		$depth .= '<span>└</<span>';
		//$Rows['subject'] = preg_replace("#\(답변\)#si",'<strong class="red">\\0</strong>', $Rows['subject']);
	}
	$commentCount 	= ($Rows['commentCount']) ? '&nbsp;<span class="medium_red strong">('.$Rows['commentCount'].')</span>' : null;
	$check 			= (!$Rows['idxTrash'] && $member->checkPerm('0')) ? '<span class="keeping"><input type="checkbox" name="select[]" class="articleCheck" value="'.$Rows['seq'].'" title="선택" /></span>' : null;
	$url 			= '<span><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'">';
	if($Rows['useSecret'] == "Y")
	{
		$url = '<span><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode('secret').'&amp;num='.$Rows['seq'].'">';
	}
	$url 			= str_replace("cate=".$cfg['module']['cate']."&amp;", "cate=".$Rows['cate']."&amp;", $url);

	echo('<tr>
		<td scop="row"><p class="center pd5 strong">'.$articleNum.'</p></td>
		<td scop="row"><dl><dt>'.$check.$url.$Rows['subject'].$commentCount.'</a></span></dt>
		<dd>'.$Rows['content'].'</dd></dl></td>
	</tr>');
	$i++;
	$articleNum--;
	unset($sort,$depth,$commentCount,$check,$url,$iconFile,$date);
}

//게시물이 없을때 공백 : Start
while($i < $row)
{
	echo('<tr class="bg1">
	    <td scop="row"><p class="center pd4 silver">-</p></td>
	    <td><p class="silver">-</p></td>
	</tr>');
	$i++;
}
echo('</tbody></table></form>');

//리스트 하단(버튼, 페이징) : Start
echo('<div class="docBottom">
	<div class="pageNavigation">'.$pagingResult['PageLink'].'</div>
	<div class="countBox">
		<span><a href="'.$_SERVER['PHP_SELF'].'?cate='.$cfg['module']['cate'].'&amp;mode='.$_GET['mode'].'">'.$lang['doc']['article'].': <strong>'.number_format($articleCount).'</strong> 건</a></span>');
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
		if($("#allarticle:checked").length > 0)
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

