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
//$db->query(" UPDATE `".$cfg['cate']['mode']."__content".$prefix."` SET readCount=readCount+1 WHERE seq='".$Rows['seq']."' AND ip<>'".$_SERVER['REMOTE_ADDR']."' ");
$db->query(" UPDATE `".$cfg['cate']['mode']."__content".$prefix."` SET readCount=readCount+1 WHERE seq='".$Rows['seq']."' ");

//데이터 정리
$display->title .= " > ".stripslashes($Rows['subject']); //<title>변경

$Rows['id']	= ($Rows['id']) ? $Rows['id'] : "비회원";
$Rows['id']	= (!$member->checkPerm('0') && $Rows['id'] != "비회원") ? "(".str_replace(substr($Rows['id'], 3, strlen($Rows['id'])), "...", $Rows['id']).")" : "(".$Rows['id'].")";
$Rows['id']	= ($cfg['module']['writerInfo'] != 'M') ? "(".$Rows['id'].")" : null;

$ip					= explode(".", $Rows['ip']);
$ip					= (!$member->checkPerm('0')) ? $ip['0'].".".$ip['1'].".XXX.XXX" : $Rows['ip'];
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

//썸네일 이미지
$thumb = $db->queryFetch("SELECT * FROM `".$cfg['cate']['mode']."__file".$prefix."` WHERE parent='".$Rows['seq']."' AND LOWER(extName) IN ('jpg','gif','png','bmp') ORDER BY seq ASC Limit 1", 2);
$dir = str_replace($cfg['site']['lang']."/",null,__SKIN__)."data/".date("Y",$thumb['regDate'])."/".date("m",$thumb['regDate'])."/l-".$thumb['fileName'];

//caseBox사이즈
$detailSize = $cfg['site']['size'] - $cfg['site']['sizeSsnb'] - $cfg['site']['sizeSside'] - $cfg['site']['padContentLeft'] - $cfg['site']['padContentRight'];
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
				<?php if($cfg['module']['list'] == 'Cal') {	//일정모듈의 경우 시작일 종료일 노출 ?>
				<div class="date" title="<?php echo($lang['doc']['endDate']);?>"><?php echo($lang['doc']['endDate']);?> : <?php echo(date("Y.m.d",$Rows['endDate']));?> <?php echo(date("H:i",$Rows['endDate']));?></div>
				<div class="date" title="<?php echo($lang['doc']['stDate']);?>"><?php echo($lang['doc']['stDate']);?> : <?php echo(date("Y.m.d",$Rows['regDate']));?> <?php echo(date("H:i",$Rows['regDate']));?></div>
				<?php } else { ?>
				<div class="date" title="<?php echo($lang['doc']['regDate']);?>"><?php echo($lang['doc']['regDate']);?> : <?php echo(date("Y.m.d",$Rows['regDate']));?> <span><?php echo(date("H:i:s",$Rows['regDate']));?></span></div>
				<?php } ?>
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

		<!-- Content : Start -->
		<!--<div class="caseBox" style="width:<?php echo($detailSize-32);?>px">
			<div style="padding:10px; float:left; width:<?php echo($cfg['module']['thumbBsize']);?>px">
				<img src="<?php echo($dir);?>" title="<?php echo($Rows['subject']);?>" alt="<?php echo($Rows['subject']);?>" class="thumbNail" style="width:<?php echo($cfg['module']['thumbBsize']);?>px; border:2px solid #d2d2d2" onerror="this.src='/common/image/noimg_m.gif'" onmouseover="overClass(this);" onmouseout="overClass(this);" />
			</div>
			<div style="float:left; width:<?php echo($detailSize-$cfg['module']['thumbBsize']-60);?>px">
				<div class="contentBody textContent"><?php echo($func->matchImage($content));?></div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>-->
		<!-- Content : End -->

		<!-- Relate Content : Start -->
		<?php
		$n = 0;
		if($cfg['module']['thumbIsFix'] == "F")
		{
			$height 	= $cfg['module']['thumbMsizeHeight'];
		} else {
			$thumbType 	= explode(",", $cfg['module']['thumbType']); //['3']['4']=>비율
			$height 	= ($cfg['module']['thumbType']) ? ceil(($cfg['module']['thumbSsize'] * $thumbType['1']) / $thumbType['0']) : ceil(($cfg['module']['thumbSsize'] * 3) / 4);
		}

		$sq = ($Rows['idxDepth'] < 1) ? "'".$Rows['seq']."' IN (seq,productSeq)" : "'".$Rows['productSeq']."' IN (seq,productSeq)";
		$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE ".$sq." ORDER BY seq " );
		
		$imageList = '';
		while($sRows = $db->fetch())
		{
			//데이터 정리
			$date 							= date("Y.m.d", $sRows['regDate']);
			$sRows['subject'] 	= ($sRows['subject']) ? stripslashes($sRows['subject']) : "제목이 없습니다.";
			$sRows['subject'] 	= $func->cutStr($sRows['subject'], $cfg['module']['cutSubject'], "...");
			$sRows['subject'] 	= ($sRows['idxTrash'] > 0) ? '<strike>'.$sRows['subject'].'</strike>' : $sRows['subject'];
			//$url 								= '<a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$sRows['seq'].'#module">';
			//$url 								= str_replace("cate=".$cfg['module']['cate']."&amp;", "cate=".$sRows['cate']."&amp;", $url);
			$url = '<a href="#none">';

			$content		= stripslashes($sRows['content']);
			$content		=	str_replace("face=", "style=font-family:", $content);
			$content		= $func->matchCode($content);
			$content		= ($sRows['html'] == 'N') ? nl2br($content) : $content;

			//썸네일 이미지
			$thumb = $db->queryFetch("SELECT * FROM `".$cfg['cate']['mode']."__file".$prefix."` WHERE parent='".$sRows['seq']."' AND LOWER(extName) IN ('jpg','gif','png','bmp') ORDER BY seq ASC Limit 1", 2);
			$ldir = str_replace($cfg['site']['lang']."/",null,__SKIN__)."data/".date("Y",$thumb['regDate'])."/".date("m",$thumb['regDate'])."/l-".$thumb['fileName'];
			$dir = str_replace($cfg['site']['lang']."/",null,__SKIN__)."data/".date("Y",$thumb['regDate'])."/".date("m",$thumb['regDate'])."/m-".$thumb['fileName'];

			echo('<div class="caseBox" id="imgView'.$n.'" style="width:'.($detailSize-32).'px;'.($n==0 ? 'display:block;':'display:none;').'">
				<div style="padding:10px; float:left; width:'.$cfg['module']['thumbBsize'].'px">
					<img src="'.$ldir.'" title="'.$sRows['subject'].'" alt="'.$sRows['subject'].'" class="thumbNail" style="width:'.$cfg['module']['thumbBsize'].'px; border:2px solid #d2d2d2" onerror="this.src=\'/common/image/noimg_m.gif\'" onmouseover="overClass(this);" onmouseout="overClass(this);" />
				</div>
				<div style="float:left; width:'.($detailSize-$cfg['module']['thumbBsize']-60).'px">
					<div class="contentBody textContent">'.($func->matchImage($content)).'</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>');

			//리스트 출력
			 $imageList .= '<div class="cell">
			<div class="viewImage" style="width:'.$cfg['module']['thumbSsize'].'px">
				<p>'.$url.'<img src="'.$dir.'" title="'.$sRows['subject'].'" alt="'.$sRows['subject'].'" class="thumbNail" style="width:'.$cfg['module']['thumbSsize'].'px; height:'.$height.'px;" onerror="this.src=\'/common/image/noimg_m.gif\'" onclick="$.imgSlide('.$n.');" onmouseover="overClass(this);" onmouseout="overClass(this);" /></a></p>
				<div class="icon">'.$icon.'</div>
			</div>
			<div class="center" style="width:'.$cfg['module']['thumbSsize'].'px;">
				<div class="title" style="font-weight:normal">'.$url.$sRows['subject'].'</a></div>
			</div>
			</div>'.PHP_EOL;
			$n++;
			unset($icon, $url);
		}
		echo('<div class="docThumb">').PHP_EOL;
		echo $imageList;
		echo('<div class="clear"></div>
		</div><!-- End : docThumb -->');
		?>
		<!-- Relate Content : End -->

		<?php
		//삭제된 게시물 안내문구 출력
		if($Rows['idxTrash'])
		{
			echo('<div class="center" style="border-bottom: #f3c534 1px dashed; border-left: #f3c534 1px dashed; padding-bottom: 10px; background-color: #fefeb8; padding-left: 10px; padding-right: 10px; border-top: #f3c534 1px dashed; border-right: #f3c534 1px dashed; padding-top: 10px" class=txc-textbox><p>'.$lang['doc']['msgTrashed'].'</p></div>');
		}
		?>
	</div>

	<?php
	//프린트가 아닐경우 출력
	if(!$_GET['print'])
	{
		echo('<div class="docButton"><ul class="docBtn">');
		if($member->checkPerm(4) && $Rows['useNotice'] == 'N' && $Rows['idxDepth'] < 1)
		{
			echo('<li><span class="btnPack medium strong"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode("reply").'&amp;num='.$Rows['seq'].'">'.$lang['doc']['relate'].'</a></span></li>');
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
<script type="text/javascript">
(function($){
	if (!$) return;
	$.imgSlide = function(num)
	{
		$(".caseBox").css({"display" : "none"});
		$("#imgView"+num).animate({opacity:"show"}, "def");
	}
})(jQuery);
</script>
<?php
	//하단 게시물 목록
	unset($Rows, $commentCount);
	if($cfg['module']['listView'] != 'N' && !$_GET['print']) { include __PATH__."modules/".$cfg['cate']['mode']."/doc".$cfg['module']['listView'].".php"; }
?>
