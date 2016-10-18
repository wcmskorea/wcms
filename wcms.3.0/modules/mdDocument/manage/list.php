<?php
/**
 * 문서모듈 - 목록
 */
require_once __PATH__."_Admin/include/commonHeader.php";

$cated = trim($_GET['cated']).trim($_POST['cated']);
if($cated) { $sq = " AND cate = '".$cated."'"; }

//조건별 검색
switch($_GET['sh'])
{
	case "trash" :
		$sq .= " AND idxTrash > '0'";
		break;
	case "subject" :
		$sq .= " AND subject like '%".$_GET['shc']."%'";
		break;
	case "content" :
		$sq .= " AND subject like '%".$_GET['shc']."%' OR content like '%".$_GET['shc']."%'";
		break;
	case "userid" :
		$sq .= " AND id like '%".$_GET['shc']."%'";
		break;
	case "writer" :
		$sq .= " AND writer like '%".$_GET['shc']."%'";
		break;
	case "userip" :
		$sq .= " AND ip like '%".$_GET['shc']."%'";
		break;
	case "detail" :
		if($_GET['userip'])		{ $sq .= " AND ip like '%".$_GET['userip']."%'"; }
		if($_GET['subject'])	{ $sq .= " AND subject like '%".$_GET['subject']."%'"; }
		if($_GET['content'])	{ $sq .= " AND subject like '%".$_GET['shc']."%' OR content like '%".$_GET['shc']."%'"; }
		if($_GET['userid'])		{ $sq .= " AND id like '%".$_GET['userid']."%'"; }
		if($_GET['username'])	{ $sq .= " AND name like '%".$_GET['username']."%'"; }
		if($_GET['email'])		{ $sq .= " AND email like '%".$_GET['email']."%'"; }
		if($_GET['syear'])
		{
			$smonth = ($_GET['smonth']) ? $_GET['smonth'] : "01";
			$sday	= ($_GET['sday']) ? $_GET['sday'] : "01";
			$emonth = ($_GET['smonth']) ? $_GET['smonth'] : "12";
			$eday	= ($_GET['sday']) ? $_GET['sday'] : "31";
			$sq .= " AND regDate BETWEEN '".strtotime($_GET['syear'].'-'.$smonth.'-'.$sday.' 00:00:00')."' AND '".strtotime($_GET['syear'].'-'.$emonth.'-'.$eday.' 23:59:59')."'";
		}
		break;
     default :
        $sq .= " AND idxTrash = '0'";
        break;
}
//게시물 리스트 및 페이징 설정
$row			= ($_GET['row']) ? $_GET['row'] : 15;
$articleCount	= $db->queryFetchOne(" SELECT COUNT(*) FROM `mdDocument__content` WHERE 1".$sq." ");
$pagingInstance = new Paging($articleCount, $currentPage, $row, 10);
$pagingInstance->mode = "module";
$pagingInstance->addQueryString("&amp;type=list&amp;rows=".$_GET['rows']."&amp;cated=".$cated."&amp;sh=".$_GET['sh']."&amp;shc=".$_GET['shc']);
$pagingResult	=  $pagingInstance->result("../modules/mdDocument/manage/_controll.php");
?>
<h2><span class="arrow">▶</span>문서·게시물 관리&nbsp;&nbsp;<span class="normal">&gt;&nbsp;등록된 문서</span></h2>
<div class="table">
<div class="line bg_white">

<?php include "./searchDetail.php"; ?>

<form name="listForm" id="listForm" enctype="multipart/form-data" target="hdFrame">
<input type="hidden" id="formType" name="type" value="" />
<input type="hidden" id="moveCate" name="moveCate" value="" />
<input type="hidden" id="rtotal" name="rtotal" value="" />
<input type="hidden" id="parm01" name="parm01" value="" />
<input type="hidden" id="parm02" name="parm02" value="" />
<input type="hidden" id="parm03" name="parm03" value="" />

<div class="docInfo">
	<div class="selectBox" style="width:100px;">
		<span class="selectCtrl"><span class="selectArrow"></span></span>
		<button class="selectValue selected" type="button"><?php echo($row);?>건씩 보기</button>
		<ul class="selectList1">
			<li><a href="javascript:;" onclick="$.insert('#module', '../modules/mdDocument/manage/_controll.php?type=list&amp;cated=<?php echo($cated)?>&amp;row=15',null,300);">15건씩 보기</a></li>
			<li><a href="javascript:;" onclick="$.insert('#module', '../modules/mdDocument/manage/_controll.php?type=list&amp;cated=<?php echo($cated)?>&amp;row=30',null,300);">30건씩 보기</a></li>
			<li><a href="javascript:;" onclick="$.insert('#module', '../modules/mdDocument/manage/_controll.php?type=list&amp;cated=<?php echo($cated)?>&amp;row=50',null,300);">50건씩 보기</a></li>
			<li><a href="javascript:;" onclick="$.insert('#module', '../modules/mdDocument/manage/_controll.php?type=list&amp;cated=<?php echo($cated)?>&amp;row=100',null,300);">100건씩 보기</a></li>
		</ul>
	</div>
	<div class="selectBox" style="width:300px;">
		<span class="selectCtrl"><span class="selectArrow"></span></span>
		<ul class="selectList1">
			<?php
			$db->query(" SELECT cate,name FROM `site__` WHERE SUBSTRING(cate,1,3)<>'000' AND mode='mdDocument' ORDER BY skin, cate,sort ASC ");
			while($sRows = $db->fetch())
			{
				for($i=3;$i<=strlen($sRows['cate'])/3;$i++) { $blank .= "　"; }
				echo('<li><a href="javascript:;" onclick="$.insert(\'#module\', \'../modules/mdDocument/manage/_controll.php?type=list&amp;row='.$row.'&amp;cated='.$sRows['cate'].'\',null,300);">'.$blank.' ('.substr($sRows['cate'],-3).')'.$sRows['name'].'</a></li>');
				if($sRows['cate'] == $cated)
				{
					$selectedCate = $sRows['name'];
				}
				unset($blank);
			}
			$selectedCate = (!$selectedCate) ? "카테고리별 문서보기" : $selectedCate;
			?>
		</ul>
		<button class="selectValue selected" type="button"><?php echo($selectedCate);?></button>
	</div>
	<?php
	if($cated)
	{
		echo('<div style="float:left; margin-left:5px;"><span class="btnPack violet small"><a href="javascript:;" onclick="new_window(\''.$cfg['droot'].'?cate='.$cated.'&amp;type='.$sess->encode("input").'&amp;mode=content\',\'contentWcms\',1024,600,\'no\',\'yes\');">새문서 등록하기</a></span></div>');
	}
	else
	{
		echo('<div style="float:left; padding:5px;"><span class="colorGray">카테고리를 선택하면 "새문서를 작성할 수 있습니다."</span></div>');
	}
	?>
	<div class="docBtn">
	<ul>
		<li style="padding:4px;"><span class="colorGray"><strong class="colorOrange"><?php echo(number_format($articleCount));?></strong> 건</span></li>
		<!--<li><span class="btnPack black small"><button type="button" onclick="$('#detailSearch').animate({height:'toggle', opacity:'toggle'}, 'fast');">상세검색</button></span></li>-->
		<li><span class="btnPack gray small"><button type="button" onclick="$.dialog('<?php echo($_SERVER['PHP_SELF']);?>?<?php echo(__PARM__);?>&amp;type=move&amp;mode=dialog',null,300,140)">선택이동</button></span></li>
		<li><span class="btnPack gray small"><button type="button" onclick="$.dialog('<?php echo($_SERVER['PHP_SELF']);?>?<?php echo(__PARM__);?>&amp;type=clear&amp;mode=dialog',null,300,140)">선택삭제</button></span></li>
	</ul>
	</div>
	<div class="clear"></div>
</div>

<table class="table_basic" style="width:100%; margin-top:5px;">
	<caption></caption>
	<col width="60">
	<col width="30">
	<col>
	<col width="100">
	<col width="40">
	<col width="40">
	<col width="40">
	<col width="100">
	<thead>
		<tr>
			<th class="first"><p class="center">번호</p></th>
			<th><p class="center"><input type="checkbox" checked="checked" id="allarticle" name="allarticle" style="vertical-align: top; cursor: pointer;" title="전체선택" /></p></th>
			<th><p class="center">{카테고리명}·제목</p></th>
			<th><p class="center">작성자</p></th>
			<th><p class="center">파일</p></th>
			<th><p class="center">댓글</p></th>
			<th><p class="center">조회</p></th>
			<th><p class="center">등록일</p></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$db->query(" SELECT * FROM `mdDocument__content` WHERE 1".$sq." ORDER BY idx DESC ".$pagingResult['LimitQuery'] );

	if($db->getNumRows() < 1)
	{
		echo('<tr><td class="blank" colspan="8">등록된 문서·게시물이 존재하지 않습니다.</td></tr>');
	}
	else
	{
		while($Rows = $db->fetch())
		{
			$cateName 						= $func->getCateName($Rows['cate']);
			//$Rows['subject'] 		= ($Rows['idxTrash']) ? '<strike>'.$Rows['subject'].'</strike>' : $Rows['subject'];
			$Rows['fileCount'] 		= ($Rows['fileCount'] > 0) ? '<span class="colorOrange">'.$Rows['fileCount'].'</span>' : '<span class="colorGray">'.$Rows['fileCount'].'</span>';
			$Rows['commentCount'] = ($Rows['commentCount'] > 0) ? '<span class="colorOrange">'.$Rows['commentCount'].'</span>' : '<span class="colorGray">'.$Rows['commentCount'].'</span>';
			$Rows['readCount'] 		= ($Rows['readCount'] > 0) ? '<span class="colorOrange">'.$Rows['readCount'].'</span>' : '<span class="colorGray">'.$Rows['readCount'].'</span>';
			$seq 									= ($Rows['idxTrash']) ? '<span class="colorOrange">삭제</span>' : $Rows['seq'];

			echo('<tr>
			<th><p class="center">'.$seq.'</p></th>
			<td><p class="center"><input type="checkbox" id="choice_'.$Rows['seq'].'" class="articleCheck" name="choice[]" value="'.$Rows['seq'].'" /></p></td>
			<td title="'.$Rows['writer'].'님께서 작성하신 문서입니다."><a href="javascript:;" onclick="new_window(\''.$cfg['droot'].'?cate='.$Rows['cate'].'&amp;type=view&amp;num='.$Rows['seq'].'&amp;mode=content\',\'contentWcms\',1024,600,\'no\',\'yes\');"><span class="colorGray">{'.$cateName.'}·</span>'.$Rows['subject'].'</a></td>
			<td class="bg_gray wrap100">'.$Rows['writer'].'</td>
			<td><p class="center">'.$Rows['fileCount'].'</p></td>
			<td><p class="center">'.$Rows['commentCount'].'</p></td>
			<td><p class="center">'.$Rows['readCount'].'</p></td>
			<td class="bg_gray"><p class="center">'.date("Y.m.d", $Rows['regDate']).' <span class="small_gray">'.date("H:i", $Rows['regDate']).'</span></p></td>
			</tr>');
		}
	}
	?>
	</tbody>
</table>
<div class="pageNavigation"><?php echo($pagingResult['PageLink']);?></div>
</div>
</div>

<input type="hidden" name="total" value="<?php echo($i);?>" />
</form>
<?php
require_once __PATH__."_Admin/include/commonScript.php";
?>
