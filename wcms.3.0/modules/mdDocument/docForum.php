<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }

//타이틀설정
$lang['doc']['subject'] = '주 제';
$lang['doc']['writer'] = '개설자';
$lang['doc']['comment'] = '의견';
$lang['comment']['new'] = '최소 5자이상 작성하셔야 합니다';

//최근 게시물 
$_GET['num'] = (!$_GET['num']) ? $db->queryFetchOne(" SELECT seq FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE cate='".__CATE__."' ORDER BY idx DESC Limit 1 ") : $_GET['num'];

//인증 처리
if(!$_SESSION['docSecret'])
{
	$Rows = $db->queryFetch(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE seq='".$_GET['num']."' ");
	if($db->getNumRows() < 1) { $func->err($lang['doc']['notfound']); }
	if($Rows['useNotice'] == 'N' && $Rows['useSecret'] == 'Y' && ($Rows['id'] != $_SESSION['uid'] || !$_SESSION['uid']) && !$member->checkPerm('0')) { $func->err($lang['doc']['notperm'],"back"); }
}
else
{
	$query = " SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE seq='".$_GET['num']."' AND passwd='".$db->passType($cfg['site']['encrypt'], $_SESSION['docSecret'])."' ";
	$Rows = $db->queryFetch($query);
	if($db->getNumRows() < 1) { $func->err($lang['doc']['notmatch'],"back"); }
}

//휴지통 문서 열람권한 체크
if($Rows['idxTrash'] > 0 && !$member->checkPerm('0'))
{
	$func->err($lang['doc']['notperm'],"back");
}

//공지문서를 제외한 열람권한 체크
if($Rows['notice'] < 2 && !$member->checkPerm(2))
{
	$func->err($lang['doc']['notperm'],"back");
}

//조회수 적용
//$db->query(" UPDATE `".$cfg['cate']['mode']."__content".$prefix."` SET readCount=readCount+1 WHERE seq='".$Rows['seq']."' AND ip<>'".$_SERVER['REMOTE_ADDR']."' ");
$db->query(" UPDATE `".$cfg['cate']['mode']."__content".$prefix."` SET readCount=readCount+1 WHERE seq='".$Rows['seq']."' ");

//데이터 정리
$display->title = stripslashes($Rows['subject']); //<title>변경

$Rows['id']	= ($Rows['id']) ? $Rows['id'] : "비회원";
$Rows['id']	= (!$member->checkPerm('0') && $Rows['id'] != "비회원") ? "(".str_replace(substr($Rows['id'], 3, strlen($Rows['id'])), "...", $Rows['id']).")" : "(".$Rows['id'].")";
$Rows['id']	= ($cfg['module']['writerInfo'] != 'M') ? $Rows['id'] : null;

$ip					= explode(".", $Rows['ip']);
//$ip					= (!$member->checkPerm('0')) ? $ip['0'].".".$ip['1'].".XXX.XXX" : $Rows['ip'];
$ip					= (!$member->checkPerm('0')) ? '<span class="small_gray">비공개</span>' : $Rows['ip'];
$ip					= ($cfg['module']['writerInfo'] != 'M') ? "IP : ".$ip : null;

$email			= (!$member->checkPerm('0')) ? '<span class="small_gray">비공개</span>' : $Rows['email'];
$email			= (!$email) ? "X" : $email;
$email			= ($cfg['module']['writerInfo'] != 'M') ? "Email : ".$email : null;

$content		= stripslashes($Rows['content']);
$content		=	str_replace("face=", "style=font-family:", $content);
$content		= $func->matchCode($content);
$content		= ($Rows['html'] == 'N') ? nl2br($content) : $content;

$contentAdd	= explode("|", $Rows['contentAdd']);

$url 		= $func->autoLink($Rows['url']);
$year		= date('Y', $Rows['regDate']);
$month		= date('m', $Rows['regDate']);
?>
<div class="document">
	<div class="docRead">
		<div class="readHeader">
			<div class="titleAndUser">
				<div class="title"><h4><?php echo($Rows['subject']);?></h4></div>
				<?php if($cfg['module']['writerInfo'] != 'N') {	?>
				<div class="author">개설자 : <span class="nowrap"><?php echo($Rows['writer']);?></span></div>
				<div class="clear"></div>
				<?php } ?>
			</div>
			<?php if($cfg['module']['writerInfo'] != 'N') {	//등록자 정보 노출 여부 ?>
			<div class="dateAndCount">
				<div class="ip"><?php echo($ip);?></div>
				<div class="date" title="<?php echo($lang['doc']['endDate']);?>"><?php echo($lang['doc']['endDate']);?> : <span class="colorBlack"><?php echo(date("Y.m.d",$Rows['endDate']));?> <?php echo(date("H:i",$Rows['endDate']));?></span></div>
				<div class="date" title="<?php echo($lang['doc']['stDate']);?>"><?php echo($lang['doc']['stDate']);?> : <span class="colorBlack"><?php echo(date("Y.m.d",$Rows['regDate']));?> <?php echo(date("H:i",$Rows['regDate']));?></span></div>
				<?php 
				 	if($cfg['module']['comment'] == 'Y') { echo('<div class="commentCount">'.$lang['doc']['comment'].' : <span>'.$Rows['commentCount'].'</span></div>'); }
				?>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
			<?php } ?>
		</div>

		<!-- Content : Start -->
		<div class="contentBody textContent"><?php echo($func->matchImage($content));?></div>
		<div class="clear"></div>
		<!-- Content : End -->

		<!-- Add Content : Start -->
		<?php
		/* 추가입력 사항 노출 */
		if($cfg['module']['addContent'])
		{
			$form = new Form('table');
			$addOpt = explode(",", $cfg['module']['addContent']);
			echo('<table class="table_list" summary="해당 게시물의 추가 입력양식 입니다." style="width:100%;">
			<caption>추가입력 사항</caption>
			<colgroup>
				<col width="17%">
				<col>
			<colgroup>
			<tbody>');
			foreach($addOpt AS $key=>$val)
			{
				$form->addStart($val, 'addopt['.$key.']', 1);
				$form->addHtml('<ol><li><p class="pd5 colorBlack">'.$contentAdd[$key].'&nbsp;</p></li></ol>');
				$form->addEnd(1);
			}
			echo('</tbody>
			</table>
			');
		}
		?>
		<!-- Add Content : End -->
		<div class="clear"></div>

		<?php
		//삭제된 게시물 안내문구 출력
		if($Rows['idxTrash'])
		{
			echo('<div class="center" style="border-bottom: #f3c534 1px dashed; border-left: #f3c534 1px dashed; padding-bottom: 10px; background-color: #fefeb8; padding-left: 10px; padding-right: 10px; border-top: #f3c534 1px dashed; border-right: #f3c534 1px dashed; padding-top: 10px" class=txc-textbox><p>'.$lang['doc']['msgTrashed'].'</p></div>');
		}
		?>
	</div>

	<?php
	//첨부파일 출력
	if($Rows['fileCount'] > 0 && $cfg['module']['download'] != 'N')
	{
		echo('<div class="fileAttatch"><ul>');
		$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__file".$prefix."` WHERE parent='".$Rows['seq']."' ");
		while($sRows = $db->fetch())
		{
			$dir = $cfg['upload']['dir'].date("Y",$sRows['regDate'])."/".date("m",$sRows['regDate'])."/".$sRows['fileName'];
			echo('<li><span><img src="'.$cfg['droot'].'common/image/files/'.strtolower($sRows['extName']).'.gif" align="absmiddle" onError="this.src=\''.$cfg['droot'].'common/image/files/unKonwn.gif\'" width="16" height="16" /></span>&nbsp;<span><a href="'.$cfg['droot'].'addon/system/download.php?'.__PARM__.'&file='.$sess->encode($dir).'&name='.urlencode($sRows['realName']).'">'.$sRows['realName'].'</a></span></li>');
		}
		echo('</ul><div class="clear"></div></div>');
	}

	//프린트가 아닐경우 출력
	if(!$_GET['print'])
	{
		echo('<div class="docButton"><ul class="docBtn">');
		if($member->checkPerm('0'))
		{
			echo('<li><span class="btnPack medium strong"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode("input").'">개설하기</a></span></li>');
			//echo('<li><span class="btnPack black medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode('articleOptimize').'#procForm">'.$lang['doc']['optimize'].'</a></span></li>');
			echo('<li><span class="btnPack gray medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode('articleModify').'&amp;num='.$Rows['seq'].'#procForm">'.$lang['doc']['modify'].'</a></span></li>');
			echo('<li><span class="btnPack gray medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode('articleDel').'&amp;num='.$Rows['seq'].'&amp;idx='.$Rows['idx'].'#procForm">'.$lang['doc']['delete'].'</a></span></li>');
		}
		if($member->checkPerm('0') && $Rows['idxTrash'] > 0)
		{
			echo('<li><span class="btnPack white medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode("repair").'&amp;num='.$Rows['seq'].'">'.$lang['doc']['repair'].'</a></span></li>');
		}
		echo('<li><span class="btnPack gray medium"><a href="javascript:;" onclick="new_window(\''.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'&amp;print=y\',\'print\',680,480,\'no\',\'yes\');" onkeypress="new_window(\''.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'&amp;print=y\',\'print\',680,480,\'no\',\'yes\');">'.$lang['doc']['print'].'</a></span></li>
		</ul></div><div class="clear"></div>');
		
		echo('<p class="small_gray colorOrange">※ 종료일 전까지 참여하실 수 있습니다.</p>');
		//댓글 리스트
		$lang['comment']['write'] = '의견달기';
		$lang['comment']['modify'] = '작성하신 의견을 수정합니다';
		include __PATH__."modules/mdComment/comment.php"; 
	}
	?>
</div>

<?php
unset($Rows);

#하단 리스트 시작
$getCount = $func->getTotalCount($cfg['cate']['mode']."__", "cate like '".$cfg['module']['cate']."%'");
$articleCount = $cfg['module']['articled'];
$sql = "cate like '".$cfg['module']['cate']."%' ORDER BY idx DESC";

//게시물 리스트 및 페이징 설정
$row = $cfg['module']['listHcount'] * $cfg['module']['listVcount'];
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
<input type="hidden" id="shc" name="shc" value="" />');

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

//리스트 하단(버튼, 페이징) : Start
echo('<div class="docBottom">
	<div class="pageNavigation">'.$pagingResult['PageLink'].'</div>
</div>');
?>
<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/selectBox.js"></script>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$("#allarticle").click(function(){
		if ($("#allarticle:checked").length > 0){
			$("input[class=articleCheck]:checked").attr("checked", false);
		}else{
			$("input[class=articleCheck]:not(checked)").attr("checked", "checked");
		}
	});
});
//]]>
</script>
