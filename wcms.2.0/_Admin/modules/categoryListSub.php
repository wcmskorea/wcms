<?php
require_once "../include/commonHeader.php";

$n = 0;
$len = strlen(__CATE__) + 3;
$bgColor = ($len == 6) ? "#f7f7f7" : "#efefef";
$countSum = $db->queryFetchOne(" SELECT SUM(hit) FROM `site__` WHERE cate like '".__CATE__."%' AND skin='".$_GET['skin']."' ");

$db->query(" SELECT * FROM `site__` WHERE skin='".$_GET['skin']."' AND LENGTH(cate)='".$len."' AND cate like '".__CATE__."%' ORDER BY sort ASC ");
if($db->getNumRows() < 1)
{
	echo('<table class="table_listSub" style="width:100%;"><tr><td class="blank">['.__CATE__.']에 등록된 하위 카테고리가 없습니다.</td></tr></table><div class="clear"></div>');
}
else
{
	while($Rows = $db->fetch())
	{
		$count = $db->queryFetchOne(" SELECT SUM(hit) FROM `site__` WHERE cate like '".$Rows['cate']."%' AND skin='".$Rows['skin']."' ", 2);
		$countPer = ($countSum) ? round(($count * 100) / $countSum, 1)	: 0;

		$droot	= ($Rows['skin'] == 'default') ? $cfg['droot'] : $cfg['droot'].$Rows['skin']."/";
		$sort	= ($Rows['sort']) ? '<span class="small_gray">('.$Rows['sort'].')</span>' : null;
		$type	= ($Rows['status'] == 'dep') ? '<span class="small_red pd3">(독)</span>' : null;
		$clone	= ($Rows['status'] == 'normal') ? '<span class="btnPack black small"><a href="javascript:;" onclick="$.dialog(\'./modules/index.php?type=cateClone&skin='.$Rows['skin'].'&cloneCate='.$Rows['cate'].'\',null,800,490)">C</a></span>' : null;
		if($Rows['mode'] == 'mdDocument')
		{
			if(substr($Rows['cate'],0,3) == '000')
			{
				$funcules = '<span class="btnPack gray small"><a href="javascript:;" onclick="new_window(\'./modules/categoryContent.html?type=cateContent&amp;cate='.$Rows['cate'].'&skin='.$_GET['skin'].'&name='.urlencode($Rows['name']).'\',\'contentWcms\',1024,600,\'no\',\'yes\');" style="width:100px;">콘텐츠 작성</a></span>';
			}
			else
			{
				$funcules = '<span class="btnPack small"><a href="javascript:;" onclick="$.dialog(\'./modules/index.php?type=cateMod&skin='.$_GET['skin'].'&cate='.$Rows['cate'].'&name='.urlencode($Rows['name']).'\',null,800,450)" style="width:100px;">문서·게시물</a></span>';
			}
		}
		else if($Rows['mode'] == 'mdMember')
		{
			$funcules = '<span class="btnPack blue small"><a href="javascript:alert(\'모듈별 관리 > 회원·고객의 환경설정을 확인하세요\');" style="width:100px;">'.$cfg['solution'][$Rows['mode']].'</a></span>';
		}
		else if(substr($Rows['mode'],0,2) == "md")
		{
			$funcules = '<span class="btnPack blue small"><a href="javascript:;" onclick="$.dialog(\'./modules/index.php?type=cateMod&skin='.$_GET['skin'].'&cate='.$Rows['cate'].'\',null,800,450)" style="width:100px;">'.$cfg['solution'][$Rows['mode']].'</a></span>';
		}
		else if($Rows['mode'] == 'url')
		{
			$funcules = '<span class="btnPack white small"><a href="javascript:alert(\''.$Rows['url'].'\');" style="width:100px;">LINK</a></span>';
		}
		else if(is_numeric($Rows['mode']))
		{
			$funcules = '<span class="btnPack white small"><a href="javascript:alert(\''.$Rows['mode'].' 카테고리로 연결중입니다.\');" style="width:100px;">LINK</a></span>';
		}
		else
		{
			$funcules = '<span class="btnPack small"><a href="javascript:;" style="width:90px;">설정안됨</a></span>';
		}
		for($i=2; $i<(strlen($Rows['cate'])/3); $i++) { $blank .= "　"; }
		$blank .= '<span class="smaller_gray">└→</span>&nbsp;';
		$depth = ($Rows['status'] == 'hide') ? '<span class="colorRed">비공개</span>' : intval($len/3)."차";
		$check = (substr($Rows['cate'],0,3) == '000') ? '' : '<strong><span class="keeping"><input type="checkbox" name="select[]" class="articleCheck" value="'.$Rows['cate'].'" title="선택" /></span></strong>';
	?>
	<table class="table_list" style="width:100%; background-color:<?php echo($bgColor); ?>">
	<col width="30">
	<col width="60">
	<col width="120">
	<col width="310">
	<col width="60">
	<col>
	<tbody>
	<tr class="active" onmouseover="this.style.backgroundColor='#ffffcc';" onmouseout="this.style.backgroundColor='<?php echo($bgColor); ?>';">
		<th scope="col"><p class="center"><?php echo($check);?></p></th>
		<th scope="col"><p class="right"><?php echo($depth);?></p></th>
		<td scope="col"><a href="<?php echo($droot);?>?cate=<?php echo($Rows['cate']);?>" target="_blank"><?php echo($Rows['cate']);?></a></td>
		<td scope="col"><p><?php echo($funcules);?>
	    	<span class="btnPack white small"><a href="javascript:;" onclick="$.dialog('./modules/index.php?type=cateSort&skin=<?php echo($Rows['skin']); ?>&cate=<?php echo($Rows['cate']);?>',null,500,280)">순서</a></span>
	    	<?php if($_SESSION['ulevel'] <= $cfg['operator'] || substr($Rows['cate'],0,3) != '000') { ?>
		    <span class="btnPack white small"><a href="javascript:;" onclick="$.dialog('./modules/index.php?type=catePerm&skin=<?php echo($Rows['skin']); ?>&cate=<?php echo($Rows['cate']);?>',null,500,240)">권한</a></span>
		    <?php }
				if(__PARENT__ != '000' || (__PARENT__ == '000' && $_SESSION['ulevel'] <= $cfg['operator'])) { ?>
		    <span class="btnPack gray small"><a href="javascript:;" onclick="if(delThis()){$.message('./modules/index.php?type=cateDel&skin=<?php echo($Rows['skin']); ?>&cate=<?php echo($Rows['cate']);?>')}">삭제</a></span>
		    <span class="btnPack gray small"><a href="javascript:;" onclick="$.dialog('./modules/index.php?type=cateReg&skin=<?php echo($Rows['skin']); ?>&cate=<?php echo($Rows['cate']);?>',null,800,490)">수정</a></span>
		    <?php echo($clone);?>
		    <?php } ?></p>
		</td>
		<td scope="col" class="bg_gray"><p class="right"><?php echo($countPer);?> %</p></td>
		<td scope="col" class="accent" id="td<?php echo($Rows['cate']);?>" onclick="$.cell('#<?php echo($Rows['skin']);?>_<?php echo($Rows['cate']);?>', './modules/index.php?type=cateSub&skin=<?php echo($Rows['skin']); ?>&cate=<?php echo($Rows['cate']);?>'); $(this).toggleClass('open');"><?php echo($blank);?><?php echo($sort);?>&nbsp;<?php echo($type);?><?php echo($Rows['name']);?></td>
	</tr>
	</tbody>
	</table>
	<div id="<?php echo($Rows['skin']);?>_<?php echo($Rows['cate']);?>" style="display:none;" class="blank"><p class="center pd3"><img src="<?php echo($cfg['droot']);?>common/image/ajax_small.gif" width="16" height="16" vspace="5" /></p></div>
	<div class="clear"></div>
	<?php
	$n++;
	$db->query(" UPDATE `site__` SET child='".$n."' WHERE skin='".$_GET['skin']."' AND cate='".__CATE__."' ", 2);
	unset($blank);
	}
}
?>
<?php include "../include/commonScript.php"; ?>
