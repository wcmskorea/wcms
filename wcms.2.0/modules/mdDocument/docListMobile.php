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

//검색타입별 서브쿼리 작성
switch ($_GET['sh'])
{
	case "division":
		$sql .= ($_GET['shc']) ? " AND subject like '[".$_GET['shc']."]%' ORDER BY idx DESC" : " ORDER BY idx DESC";
		break;
	case  "all":
		$sql .= " AND (subject like '%".$_GET['shc']."%' OR content like '%".$_GET['shc']."%') ORDER BY idx DESC";
		break;
	case "subject":
		$sql .= " AND subject like '%".$_GET['shc']."%' ORDER BY idx DESC";
		break;
	case "writer":
		$sql .= " AND writer like '%".$_GET['shc']."%' ORDER BY idx DESC";
		break;
	case "trash":
		$sql .= " AND idxTrash>'0' ORDER BY idx DESC";
		break;
	case "cnt":
		$sql .= " ORDER BY readCount ".$_GET['shc'];
		break;
	case "date":
		$sql .= " ORDER BY regDate ".$_GET['shc'];
		break;
	default :
		$sql .= " ORDER BY idx DESC";
		break;
}

//게시물 리스트 및 페이징 설정
$row			= $cfg['module']['listHcount'] * $cfg['module']['listVcount'];
$pagingInstance = new Paging($articleCount, $currentPage, $row, 5);
$pagingInstance->addQueryString("&amp;".__PARM__."&amp;type=list&amp;sh=".$_GET['sh']."&amp;shc=".$_GET['shc']);
$pagingResult	= $pagingInstance->result();
$articleNum		= $articleCount - $pagingResult['LimitIndex'];

//리스트 본문 : Start
echo('<table summary="'.$cfg['cate']['name'].'" class="table_basic" style="width:100%;">
<caption>'.$cfg['cate']['name'].'</caption>
<colgroup>
	<col>
</colgroup>
<tbody>');

/**
 * 공지 게시물 출력
 */
$i = 0;
$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE idxTrash='0' AND useNotice='Y' AND ".$sql);
while($Rows = $db->fetch())
{
	$date 						= date("Y.m.d", $Rows['regDate']);
	$Rows['subject'] 	= ($Rows['subject']) ? stripslashes($Rows['subject']) : "제목이 없습니다.";
	$Rows['subject'] 	= $func->cutStr($Rows['subject'], $cfg['module']['cutSubject'], "...");
	$Rows['subject'] 	= ($Rows['idxTrash']) ? '<strike>'.$Rows['subject'].'</strike>' : $Rows['subject'];
	if($cfg['module']['opt_division'] != 'N')
	{
		$Rows['subject'] = preg_replace("#\[(.*?)\]#si", '<span class="gray">\\0</span>', $Rows['subject']);
	}
	$Rows['content']	= stripslashes($Rows['content']);
	$Rows['content']	= strip_tags($Rows['content']);
	$Rows['content']	= str_replace("&nbsp;", null, $Rows['content']);
	$Rows['content']	= str_replace("\t", null, $Rows['content']);
	$Rows['content']	= $func->cutStr($Rows['content'], $cfg['module']['cutContent'], "...");
	$commentCount		= ($Rows['commentCount']) ? '&nbsp;<span class="small_red strong">('.$Rows['commentCount'].')</span>' : null;
	$icon 					.= $func->iconNew($Rows['regDate'], (86400*3), '<span><img src="'.$cfg['droot'].'user/default/image/icon/new.gif" alt="최근 게시물" /></span>');
	$check 					= '<span>→</span>';
	$url 						= '<span><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'">';
	$url 						= str_replace("cate=".$cfg['module']['cate']."&amp;", "cate=".$Rows['cate']."&amp;", $url);

	echo('<tr class="bg3">
		<td class="subject"><p class="strong pd5">'.$url.$Rows['subject'].$commentCount.'</a><span>'.$icon.'</p>
		<p class="pd5">'.$Rows['content'].'</p></td>
	</tr>');

	$i++;
	unset($sort,$commentCount,$icon,$check,$url,$iconFile,$date);
}

/**
 * 일반 게시물 출력
 */
$n = 0;
$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE ".$sql." ".$pagingResult['LimitQuery']);
while($Rows = $db->fetch())
{
	$date 				= date("Y.m.d", $Rows['regDate']);
	$Rows['subject'] 	= ($Rows['subject']) ? stripslashes($Rows['subject']) : "제목이 없습니다.";
	$Rows['subject'] 	= $func->cutStr($Rows['subject'], $cfg['module']['cutSubject'], "...");
	$Rows['subject'] 	= ($Rows['idxTrash']) ? '<strike>'.$Rows['subject'].'</strike>' : $Rows['subject'];
	if($cfg['module']['opt_division'] != 'N')
	{
		$Rows['subject'] = preg_replace("#\[(.*?)\]#si", '<span class="gray">\\0</span>', $Rows['subject']);
	}
	$Rows['content']	= stripslashes($Rows['content']);
	$Rows['content']	= strip_tags($Rows['content']);
	$Rows['content']	= str_replace("&nbsp;", null, $Rows['content']);
	$Rows['content']	= str_replace("\t", null, $Rows['content']);
	$Rows['content']	= $func->cutStr($Rows['content'], $cfg['module']['cutContent'], "...");
	$Rows['writer'] = ($cfg['site']['id'] == $Rows['id'] && $Rows['idxDepth']) ? '<strong>'.$Rows['writer'].'<strong>' : $Rows['writer'];
	$commentCount 	= ($Rows['commentCount']) ? '&nbsp;<span class="small_red strong">('.$Rows['commentCount'].')</span>' : null;
	$icon 			.= $func->iconNew($Rows['regDate'], (86400*3), '<span><img src="'.$cfg['droot'].'user/default/image/icon/new.gif" alt="최근 게시물" /></span>');
	$check 					= '<span>→</span>';
	$url 			= '<span><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'">';
	if($Rows['useSecret'] == "Y")
	{
		$icon .= '<span><img src="'.$cfg['droot'].'user/default/image/icon/secret.gif" alt="비밀글" /></span>';
		$url = '<span><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&type='.$sess->encode('secret').'&num='.$Rows['seq'].'">';
	}
	$url 			= str_replace("cate=".$cfg['module']['cate']."&", "cate=".$Rows['cate']."&", $url);

	//if($articleNum % 2 == 0) { echo('<tr class="bg1">'); } else { echo('<tr class="bg2">'); }
	echo('<tr>
		<td><p class="strong pd5">'.$check.$depth.$url.$Rows['subject'].$commentCount.'</a>'.$icon.'</p>
		<p class="pd5">'.$Rows['content'].'</p></td>
	</tr>');

	$n++;
	$articleNum--;
	unset($sort,$depth,$commentCount,$icon,$check,$url,$iconFile,$date);
}
echo('</tbody></table></form>');

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
			<li><input name="sh" id="sh2" type="radio" value="writer" class="option"><label for="sh2">작성자</label></li>
			</ul>
		</div>
		<span><label for="shc" class="hide">검색어</label></span><span><input type="text" id="shc" name="shc" title="검색어" class="input_gray center" style="width:90px;" req="required" trim="trim" value="" /></span>&nbsp;<span class="btnPack small"><button type="submit">'.$lang['doc']['search'].'</button></span>
		</form>
		</fieldset>
	</div>
	<div class="clear"></div>
</div>');
?>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function()
{
	$("#allarticle").click(function()
	{
		if ($("#allarticle:checked").length > 0)
		{
			$("input[className=articleCheck]:checked").attr("checked", false);
		}
		else
		{
			$("input[className=articleCheck]:not(checked)").attr("checked", "checked");
		}
	});
});
//]]>
</script>
<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/selectBox.js"></script>
