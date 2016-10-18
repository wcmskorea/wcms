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

//리스트 상단 : Start
echo('<form id="listForm" name="listForm" method="post" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data" onsubmit="return false;">
<input type="hidden" name="cate" value="'.$cfg['module']['cate'].'" />
<input type="hidden" name="mode" value="'.$_GET['mode'].'" />
<input type="hidden" name="currentPage" value="'.$currentPage.'" />
<input type="hidden" id="formType" name="type" value="" />
<input type="hidden" id="moveCate" name="moveCate" value="" />
<input type="hidden" id="sh" name="sh" value="" />
<input type="hidden" id="shc" name="shc" value="" />
');

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

	//문서(게시물)관리 버튼 출력
	echo('<div class="docBtn"><ul>');
		if($member->checkPerm(3) && $type != 'view')
		{
			if($cfg['site']['id'] == 'leggylook')
			{
				if($_SESSION['ulevel'] < $cfg['operator'] && $_SESSION['ulevel'])
				{
					echo('<li><span class="btnPack medium strong"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode("input").'">'.$lang['doc']['write'].'</a></span></li>');
				}
			} else
			{
				echo('<li><span class="btnPack medium strong"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode("input").'">'.$lang['doc']['write'].'</a></span></li>');
			}
		}
		if($member->checkPerm('0') && $type != 'view' && $cfg['module']['listUnion'] != 'Y')
		{
			echo('<li><span class="btnPack gray medium"><button type="button" onclick="$.dialog(\''.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=move&amp;mode=dialog\',null,300,140)">'.$lang['doc']['move'].'</button></span></li>');
			echo('<li><span class="btnPack gray medium"><button type="button" onclick="$.dialog(\''.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=clear&amp;mode=dialog\',null,300,140)">'.$lang['doc']['clear'].'</button></span></li>');
		}
		if($member->checkPerm('s') && $type != 'view')
		{
			echo('<li><span class="btnPack black medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode('articleOptimize').'#procForm" class="button bblack">'.$lang['doc']['optimize'].'</a></span></li>');
			echo('<li><span class="btnPack black medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode('articleReset').'#procForm" class="button bblack">'.$lang['doc']['reset'].'</a></span></li>');
		}
	echo('</ul></div><div class="clear"></div>').PHP_EOL;
echo('</div>').PHP_EOL;

//리스트 본문 : Start
echo('<table summary="'.$cfg['cate']['name'].'" class="table_basic" style="width:100%;">
<caption>'.$cfg['cate']['name'].'</caption>
<colgroup>
	<col width="50">
	<col>
	<col width="80">');
	if($cfg['module']['uploadCount'] > 0) { echo('<col width="40">'); }
	if($cfg['module']['readCount'] != 'N') { echo('<col width="50">'); }
	echo('<col width="90">
</colgroup>
<thead>
<tr>');

/**
 * 권한에 따른 게시물 선택박스 노출
 */
if($member->checkPerm('0'))
{
	echo('<th scope="col" class="first"><p class="center pd7"><input type="checkbox" checked="checked" id="allarticle" name="allarticle" style="vertical-align:top;cursor:pointer;" title="전체선택" /></p></th>');
}
else
{
	echo('<th scope="col" class="first"><p class="center pd7">'.$lang['doc']['num'].'</p></th>');
}

echo('<th scope="col"><p class="center pd7">'.$lang['doc']['subject'].'</p></th>
	<th scope="col"><p class="center pd7">'.$lang['doc']['writer'].'</p></th>');
	if($cfg['module']['uploadCount'] > 0) { echo('<th scope="col"><p class="center pd7">첨부</p></th>'); }
	if($cfg['module']['readCount'] != 'N') { echo('<th scope="col"><p class="center pd7"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;sh=cnt&amp;shc='.$order.'">'.$lang['doc']['readCount'].'</a></p></th>');}
	echo('<th scope="col"><p class="center pd7"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;sh=date&amp;shc='.$order.'">'.$lang['doc']['regDate'].'</a></p></th>
	</tr>
</thead>
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
	$writer						= ($Rows['useAdmin'] == 'Y' && $cfg['site']['icon']) ? '<img src="/user/default/image/icon/'.$cfg['site']['icon'].'" />' : '<strong>'.$Rows['writer'].'<strong>';

	if($cfg['module']['opt_division'] != 'N')
	{
		$Rows['subject'] = preg_replace("#\[(.*?)\]#si", '<span class="gray">\\0</span>', $Rows['subject']);
	}
	$commentCount		= ($Rows['commentCount']) ? '&nbsp;<span class="small_red strong">('.$Rows['commentCount'].')</span>' : null;
	$icon 					.= $func->iconNew($Rows['regDate'], (86400*3), '<span><img src="'.$cfg['droot'].'user/default/image/icon/new.gif" alt="최근 게시물" /></span>');
	$check 					= (!$Rows['idxTrash'] && $member->checkPerm('0')) ? '<span class="keeping"><input type="checkbox" name="select[]" class="articleCheck" value="'.$Rows['seq'].'" title="선택" /></span>' : '<span>[삭제]</span>';
	$url 						= '<span><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'">';
	$url 						= str_replace("cate=".$cfg['module']['cate']."&amp;", "cate=".$Rows['cate']."&amp;", $url);
	//첨부파일 출력
	if($Rows['fileCount'])
	{
		$files = $db->queryFetch(" SELECT * FROM `".$cfg['cate']['mode']."__file".$prefix."` WHERE parent='".$Rows['seq']."' limit 1 ", 2);
		$dir = $cfg['upload']['dir'].date("Y",$files['date'])."/".date("m",$files['date'])."/".$files['filename'];
		$iconFile = '<span><a href="'.$cfg['droot'].'modules/download.php?file='.$sess->encode($dir)&amp;'&name='.$files['realname'].'"><img src="'.$cfg['droot'].'common/image/files/'.strtolower($files['extname']).'.gif" align="absmiddle" onError="this.src=\''.$cfg['droot'].'common/image/files/unKonwn.gif\'" width="16" height="16" /></a></span>';
	}
	echo('<tr class="bg3">
		<th scop="row"><p class="center strong">공지</p></th>
		<td class="subject">'.$url.$Rows['subject'].$commentCount.'</a><span>'.$icon.'</td>
		<td><p class="wrap80"><strong class="colorBlack">'.$writer.'<strong></p></td>');
		if($cfg['module']['uploadCount'] > 0) { echo('<td><p class="center">'.$iconFile.'</p></td>'); }
		if($cfg['module']['readCount'] != 'N') { echo('<td><p class="center pd4">'.$Rows['readCount'].'</p></td>'); }
		echo('<td><p class="center pd4">'.$date.'</p></td>
	</tr>');

	$i++;
	unset($sort,$commentCount,$icon,$check,$url,$iconFile,$date);
}

/**
 * 일반 게시물 출력
 */
$n = 0;
$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE useNotice='N' AND ".$sql." ".$pagingResult['LimitQuery']);
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
	if($Rows['idxDepth'])
	{
		for($i = 0; $i < $Rows['idxDepth']; $i++)
		{
			$depth .= "<span>　</span>";
		}
		$depth .= '<span>└</<span>';
		//$Rows['subject'] = preg_replace("#\(답변\)#si",'<strong class="red">\\0</strong>', $Rows['subject']);
	}
	
	$writer					= ($cfg['site']['icon']) ? '<img src="/user/default/image/icon/'.$cfg['site']['icon'].'" />' : '<strong>'.$Rows['writer'].'<strong>';
	$Rows['writer'] = ($Rows['useAdmin'] == 'Y') ? $writer : $Rows['writer'];

	$commentCount 	= ($Rows['commentCount']) ? '&nbsp;<span class="small_red strong">('.$Rows['commentCount'].')</span>' : null;
	$icon 			.= $func->iconNew($Rows['regDate'], (86400*3), '<span><img src="'.$cfg['droot'].'user/default/image/icon/new.gif" alt="최근 게시물" /></span>');
	$check 			= (!$Rows['idxTrash'] && $member->checkPerm('0')) ? '<span class="keeping"><input type="checkbox" name="select[]" class="articleCheck" value="'.$Rows['seq'].'" title="선택" /></span>' : null;
	$url 			= '<span><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'&productNum='.$Rows['productSeq'].'">';
	if($Rows['useSecret'] == "Y")
	{
		$icon .= '<span><img src="'.$cfg['droot'].'user/default/image/icon/secret.gif" alt="비밀글" /></span>';
		$url = '<span><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&type='.$sess->encode('secret').'&num='.$Rows['seq'].'&productNum='.$Rows['productSeq'].'">';
	}
	$url 			= str_replace("cate=".$cfg['module']['cate']."&", "cate=".$Rows['cate']."&", $url);

//	첨부파일 출력
	if($Rows['fileCount'])
	{
		$files = $db->queryFetch(" SELECT * FROM `".$cfg['cate']['mode']."__file".$prefix."` WHERE parent='".$Rows['seq']."' limit 1 ", 2);
		$dir = $cfg['upload']['dir'].date("Y",$files['regDate'])."/".date("m",$files['regDate'])."/".$files['fileName'];
		$iconFile = '<span><a href="'.$cfg['droot'].'addon/system/download.php?file='.$sess->encode($dir).'&name='.$files['realName'].'"><img src="'.$cfg['droot'].'common/image/files/'.strtolower($files['extName']).'.gif" align="absmiddle" onError="this.src=\''.$cfg['droot'].'common/image/files/unKonwn.gif\'" width="16" height="16" /></a></span>';
	}

// 상품정보가 있을경우 상품이미지 출력
	if($Rows['productSeq'] > 0 && !$Rows['idxDepth'])
	{

		//기준 이미지가 지정될경우 기준 이미지 노출
		//지정이 되어 있지 않을경우 최초에 업로드한 이미지 노출
		$viewImgCnt = $db->queryFetch("SELECT COUNT(*) AS cmt FROM `mdProduct__file` WHERE parent='".$Rows['productSeq']."' AND LOWER(extName) IN ('jpg','gif','png','bmp') AND viewImg = 'Y' ",2);

		if($viewImgCnt['cmt'] < 1){ $subQuery = "";}
		else                      { $subQuery = " AND viewImg = 'Y'";}

		//이미지 로딩
		$productImgInfo = $db->queryFetch("SELECT * FROM `mdProduct__file` WHERE parent='".$Rows['productSeq']."' AND LOWER(extName) IN ('jpg','gif','png','bmp') ".$subQuery." ORDER BY seq ASC Limit 1", 2);
		$productInfo    = $db->queryFetch("SELECT cateCode,productName FROM `mdProduct__product` WHERE seq='".$Rows['productSeq']."'", 2);

		$imgDir         = str_replace($cfg['site']['lang']."/",null,__SKIN__)."data/".date("Y",$productImgInfo['regDate'])."/".date("m",$productImgInfo['regDate'])."/s-".$productImgInfo['fileName'];
		$productImg     = '<a href="'.$_SERVER['PHP_SELF'].'?cate='.$productInfo['cateCode'].'&type=view&num='.$Rows['productSeq'].'"><img width="30" border="0" src="'.$imgDir.'"  style="margin:0 5px 0 5px; border:1px solid #d2d2d2; clear: both; margin-right: 8px; float: left;" " /></a>';
		$productName    = $productInfo['productName'];
		//$productName    = '';
		$subject        = $Rows['subject'];
	}else{
		$productImg     = '';
		$productName    = '';
		$subject        = $Rows['subject'];
	}
	echo('<tr>
	<td scop="row"><p class="center">'.$articleNum.'</p></td>
		<td><p>'.$productImg.$productName.'</p><p class="pd3">'.$check.$depth.$url.$subject.$commentCount.'</a>'.$icon.'</td>
		<td><p class="wrap80">'.$Rows['writer'].'</p></td>');
		if($cfg['module']['uploadCount'] > 0) { echo('<td><p class="center">'.$iconFile.'</p></td>'); }
		if($cfg['module']['readCount'] != 'N') { echo('<td><p class="center pd4">'.$Rows['readCount'].'</p></td>'); }
		echo('<td><p class="center pd4">'.$date.'</p></td>
	</tr>');

	$n++;
	$articleNum--;
	unset($sort,$depth,$commentCount,$icon,$check,$url,$iconFile,$date);
}

//게시물이 없을때 공백 : Start
while($n < $row)
{
	echo('<tr class="bg1">
	    <td scop="row"><p class="center pd4 silver">-</p></td>
	    <td><p class="silver">-</p></td>
	    <td><p class="wrap80 silver">-</p></td>');
		if($cfg['module']['uploadCount'] > 0) { echo('<td><p class="center pd4 silver">-</p></td>'); }
		if($cfg['module']['readCount'] != 'N') { echo('<td><p class="center pd4 silver">-</p></td>'); }
	    echo('<td><p class="center pd4 silver">-</p></td>
	</tr>');
	$n++;
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
<script type="text/javascript">
//<![CDATA[
$(document).ready(function()
{
	$("#allarticle").click(function()
	{
		if ($("#allarticle:checked").length > 0)
		{
			$("input[class=articleCheck]:checked").attr("checked", false);
		}
		else
		{
			$("input[class=articleCheck]:not(checked)").attr("checked", "checked");
		}
	});
});
//]]>
</script>
<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/selectBox.js"></script>
