<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }

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
$db->query(" UPDATE `".$cfg['cate']['mode']."__content".$prefix."` SET readCount=readCount+1 WHERE seq='".$Rows['seq']."' ");

//데이터 정리
$display->title .= " > ".stripslashes($Rows['subject']); //<title>변경

$Rows['id']	= ($Rows['id']) ? $Rows['id'] : "비회원";
$Rows['id']	= (!$member->checkPerm('0') && $Rows['id'] != "비회원") ? "(".str_replace(substr($Rows['id'], 3, strlen($Rows['id'])), "...", $Rows['id']).")" : "(".$Rows['id'].")";
$Rows['id']	= ($cfg['module']['writerInfo'] != 'M' || $member->checkPerm('0')) ? $Rows['id'] : null;

$writer					= ($cfg['site']['icon']) ? '<img src="/user/default/image/icon/'.$cfg['site']['icon'].'" />' : '<strong>'.$Rows['writer'].'<strong>';
$Rows['writer'] = ($Rows['useAdmin'] == 'Y') ? $writer : $Rows['writer'];

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
				<div class="author"><span class="nowrap"><?php echo($Rows['writer']);?>&nbsp;<?php echo($Rows['id']);?></span></div>
				<div class="clear"></div>
				<?php } ?>
			</div>
			<?php if($cfg['module']['writerInfo'] != 'N') {	//등록자 정보 노출 여부 ?>
			<div class="dateAndCount">
				<div class="ip"><?php echo($ip);?></div>
				<div class="email"><?php echo($email);?></div>
				<div class="date" title="<?php echo($lang['doc']['regDate']);?>"><?php echo($lang['doc']['regDate']);?> : <?php echo(date("Y.m.d",$Rows['regDate']));?> <span><?php echo(date("H:i:s",$Rows['regDate']));?></span></div>
				<?php if(!$_GET['print']) {	?>
				<?php if($cfg['module']['readCount'] != 'N') { ?>
				<div class="readedCount" title="<?php echo($lang['doc']['readCount']);?>"><?php echo($lang['doc']['readCount']);?> : <span><?php echo(number_format($Rows['readCount']));?></span></div><?php } ?>
        		<?php
          			if($cfg['module']['comment'] == 'Y') { echo('<div class="commentCount">'.$lang['doc']['comment'].' : <span>'.$Rows['commentCount'].'</span></div>'); }
					if($cfg['module']['recommand'] > 0) { echo('<div class="votedCount">'.$lang['doc']['recom'].' : <span>'.number_format($Rows['voteCount']).'</span></div>'); }
				} ?>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
			<?php } ?>
		</div>
		<?php
		// 상품정보가 있을경우 상품이미지 출력
		if($Rows['productSeq'] > 0 && $Rows['idxDepth'] < 1)
		{
			echo('<div style="border-bottom:2px dashed #efefef;">');
			//이미지 로딩
			$productImgInfo = $db->queryFetch("SELECT * FROM `mdProduct__file` WHERE parent='".$Rows['productSeq']."' AND LOWER(extName) IN ('jpg','gif','png','bmp') ORDER BY seq DESC Limit 1", 2);
			$productInfo    = $db->queryFetch("SELECT cateCode,productName FROM `mdProduct__product` WHERE seq='".$Rows['productSeq']."'", 2);

			$imgDir         = str_replace($cfg['site']['lang']."/",null,__SKIN__)."data/".date("Y",$productImgInfo['regDate'])."/".date("m",$productImgInfo['regDate'])."/s-".$productImgInfo['fileName'];
			$productImg     = '<a href="'.$_SERVER['PHP_SELF'].'?cate='.$productInfo['cateCode'].'&type=view&num='.$Rows['productSeq'].'"><img width="64" src="'.$imgDir.'"  style="border:1px solid #d2d2d2" alt="제품 보러가기" /></a>';
			$productName    = $productInfo['productName'];
			$productInfoAll = $shop->productInfoAll($Rows['productSeq']); //상품 정보

			echo('<table summary="상품에 대한 간략정보입니다." style="width:100%; background-color:#f2f2f2">
			<caption>상품정보</caption>
			<colgroup>
				<col width="70">
				<col>
			<colgroup>
			<tbody>
			<tr>
				<td><p class="center pd3">'.$productImg.'</p></td>
				<td><p class="strong pd3">상품명 : '.$productName .'</p>
						<p class="strong pd3">판매가 : \\'.number_format($productInfoAll['salesPrice']) .'</p>
						<p class="small_gray pd3">상품 사진을 클릭하시면 상세 페이지로 이동합니다.</p>
				</td>
			</tr>
			</tbody>
			</table>');
			echo('</div>');
		}
		?>

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

		if($member->checkPerm(4) && $Rows['useNotice'] == 'N' && $cfg['module']['list'] == 'ProductQH')
		{
			echo('<li><span class="btnPack medium strong"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode("reply").'&amp;num='.$Rows['seq'].'">'.$lang['doc']['rewrite'].'</a></span></li>');
		}
		echo('<li><span class="btnPack gray medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode('articleModify').'&amp;num='.$Rows['seq'].'#procForm">'.$lang['doc']['modify'].'</a></span></li>');
		echo('<li><span class="btnPack gray medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode('articleDel').'&amp;num='.$Rows['seq'].'&amp;idx='.$Rows['idx'].'#procForm">'.$lang['doc']['delete'].'</a></span></li>');
		if($member->checkPerm('0') && $Rows['idxTrash'] > 0)
		{
			echo('<li><span class="btnPack white medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode("repair").'&amp;num='.$Rows['seq'].'">'.$lang['doc']['repair'].'</a></span></li>');
		}
		echo('<li><span class="btnPack gray medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;year='.$year.'&amp;month='.$month.'">'.$lang['doc']['list'].'</a></span></li>
		<li><span class="btnPack gray medium"><a href="javascript:;" onclick="new_window(\''.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'&amp;print=y\',\'print\',680,480,\'no\',\'yes\');" onkeypress="new_window(\''.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'&amp;print=y\',\'print\',680,480,\'no\',\'yes\');">'.$lang['doc']['print'].'</a></span></li>
		</ul></div><div class="clear"></div>');
		//댓글 리스트
		if($cfg['module']['comment'] == "Y") { include __PATH__."modules/mdComment/comment.php"; }
	}
	?>
</div>

<?php
	//하단 게시물 목록
	unset($Rows, $commentCount);
	if($cfg['module']['listView'] != 'N' && !$_GET['print']) { include __PATH__."modules/".$cfg['cate']['mode']."/docProductQH.php"; }
?>
