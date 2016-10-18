<?php
require_once "../../_config.php";
require_once "../include/commonHeader.php";
?>
<dl>
<dd style="float:right; padding:3px;"><span class="btnPack medium icon strong"><span class="add"></span><a href="javascript:;" onclick="$.dialog('./modules/index.php?skin=<?php echo($_GET['skin']); ?>&type=cateReg',null,800,600)"><?php echo($cfg['skinName'][$_GET['skin']]); ?> 메뉴생성</a></span></dd>
<dd style="float:left; padding-top:5px;"><a href="javascript:;" onclick="$.dialog('./modules/index.php?skin=<?php echo($_GET['skin']); ?>&type=cateSort',null,500,300)" class="btnSmall"><span>1차 카테고리 순서변경</span></a></dd>
<dd style="float:left; padding-top:5px;"><a href="javascript:;" onclick="$.dialog('./modules/index.php?skin=<?php echo($_GET['skin']); ?>&type=cateClear',null,300,150)" class="btnSmall"><span class="colorRed">카테고리 삭제</span></a></dd>
</dl>
<div class="clear"></div>

<form name="listForm" id="listForm" enctype="multipart/form-data">
<input type="hidden" id="formType" name="type" value="" />
<input type="hidden" id="skin" name="skin" value="<?php echo($_GET['skin']);?>" />
<input type="hidden" id="cateSelect" name="select" value="" />

<table class="table_basic" style="width:100%;">
<col width="30">
<col width="60">
<col width="120">
<col width="310">
<col width="60">
<col>
<thead>
	<tr>
		<th class="first"><p class="center"><input type="checkbox" checked="checked" id="allarticle" name="allarticle" style="vertical-align:top;cursor:pointer;" title="전체선택" /></p></th>
		<th class="first"><p class="center"><span>단계</span></p></th>
		<th><p class="center"><span>카테고리 코드</span></p></th>
		<th><p class="center"><span>설정관리</span></p></th>
		<th><p class="center"><span>인기도</span></p></th>
		<th><p class="left"><span class="normal">(순서)</span>&nbsp;<span>카테고리명</span></p></th>
	</tr>
</thead>
</table>

<?php
$countSum = $db->queryFetchOne(" SELECT SUM(hit) FROM `site__` WHERE skin='".$_GET['skin']."' ");
$db->query(" SELECT * FROM `site__` WHERE LENGTH(cate)='3' AND skin='".$_GET['skin']."' ORDER BY sort ASC ");
if($db->getNumRows() < 1)
{
	echo('<table class="table_list" style="width:100%;"><tr><td class="blank">등록된 하위 카테고리가 없습니다.</td></tr></table><div class="clear"></div>');
}
else
{
	while($Rows = $db->fetch())
	{
		$count = $db->queryFetchOne(" SELECT SUM(hit) FROM `site__` WHERE cate like '".$Rows['cate']."%' AND skin='".$Rows['skin']."' ", 2);
		$countPer = ($countSum) ? round(($count * 100) / $countSum, 1)	: 0;

		$droot	= ($Rows['skin'] == 'default') ? $cfg['droot'] : $cfg['droot'].$Rows['skin']."/";
		$sort 	= ($Rows['sort']) ? '<span class="small_gray">('.$Rows['sort'].')</span>' : null;
		$type	= ($Rows['status'] == 'dep') ? '<span class="small_red pd3">(독)</span>' : null;
		$clone 	= ($Rows['status'] == 'normal') ? '<span class="btnPack black small"><a href="javascript:;" onclick="$.dialog(\'./modules/index.php?type=cateClone&skin='.$Rows['skin'].'&cloneCate='.$Rows['cate'].'\',null,800,490)" >C</a></span>' : null;
		if($Rows['mode'] == 'mdDocument')
		{
			$funcules = '<span class="btnPack small"><a href="javascript:;" onclick="$.dialog(\'./modules/index.php?type=cateMod&skin='.$_GET['skin'].'&cate='.$Rows['cate'].'&name='.urlencode($Rows['name']).'\',null,800,450)" style="width:100px;">문서·게시물</a></span>';
		}
		else if($Rows['mode'] == 'mdMember')
		{
			$funcules = '<span class="btnPack blue small"><a href="javascript:alert(\'모듈별 관리 > 회원·고객의 환경설정을 확인하세요\');" style="width:100px;">'.$cfg['solution'][$Rows['mode']].'</a></span>';
		}
		else if(substr($Rows['mode'],0,2) == "md")
		{
			$funcules = '<span class="btnPack blue small"><a href="javascript:;" onclick="$.dialog(\'./modules/index.php?type=cateMod&skin='.$_GET['skin'].'&cate='.$Rows['cate'].'&name='.urlencode($Rows['name']).'\',null,800,450)" style="width:100px;">'.$cfg['solution'][$Rows['mode']].'</a></span>';
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
		$depth = ($Rows['status'] == 'hide') ? '<span class="colorRed">비공개</span>' : '<strong>1차</strong>';
		$check = ($Rows['cate'] == '000') ? '' : '<strong><span class="keeping"><input type="checkbox" name="choice[]" class="articleCheck" value="'.$Rows['cate'].'" title="선택" /></span></strong>';
	?>
	<table class="table_list" style="width:100%;">
	<col width="30">
	<col width="60">
	<col width="120">
	<col width="310">
	<col width="60">
	<col>
	<tbody>
	<tr class="active" onmouseover="this.style.backgroundColor='#ffffcc';" onmouseout="this.style.backgroundColor='#fff';">
		<th scope="col"><p class="center"><?php echo($check);?></p></th>
		<th scope="col"><p class="center"><?php echo($depth);?></p></th>
		<td scope="col"><p><a href="<?php echo($droot);?>?cate=<?php echo($Rows['cate']);?>&menu=<?php echo($Rows['sort']);?>" target="_blank"><?php echo($Rows['cate']);?></a></p></td>
		<td scope="col">
			<?php if(substr($Rows['cate'],0,3) != '000' && $Rows['name'] != '기본설정') { ?><p><?php echo($funcules);?>
			<span class="btnPack white small"><a href="javascript:;" onclick="$.dialog('./modules/index.php?type=cateSort&skin=<?php echo($Rows['skin']); ?>&cate=<?php echo($Rows['cate']);?>',null,500,280)">순서</a></span>
			<span class="btnPack white small"><a href="javascript:;" onclick="$.dialog('./modules/index.php?type=catePerm&skin=<?php echo($Rows['skin']); ?>&cate=<?php echo($Rows['cate']);?>',null,500,240)">권한</a></span>
			<span class="btnPack gray small"><a href="javascript:;" onclick="if(delThis()){$.message('./modules/index.php?type=cateDel&skin=<?php echo($Rows['skin']); ?>&cate=<?php echo($Rows['cate']);?>')}">삭제</a></span>
			<span class="btnPack gray small"><a href="javascript:;" onclick="$.dialog('./modules/index.php?type=cateReg&skin=<?php echo($Rows['skin']); ?>&cate=<?php echo($Rows['cate']);?>',null,800,490)">수정</a></span>
			<?php echo($clone);?></p>
			<?php
			}
			else
			{
				echo('<p class="bg_gray">&nbsp;인트로, 추가Header, 추가Footer, 회원모듈, 기타</p>');
			}
			?></td>
		<td scope="col" class="bg_gray"><p class="right"><?php echo($countPer);?> %</p></td>
		<td scope="col" class="accent" id="td<?php echo($Rows['cate']);?>" onclick="$.cell('#<?php echo($Rows['skin']);?>_<?php echo($Rows['cate']);?>', './modules/index.php?type=cateSub&skin=<?php echo($Rows['skin']); ?>&cate=<?php echo($Rows['cate']);?>'); $(this).toggleClass('open');"><p><?php echo($sort);?>&nbsp;<?php echo($type);?><?php echo($Rows['name']);?></p></td>
	</tr>
	</tbody>
	</table>
	<div id="<?php echo($Rows['skin']);?>_<?php echo($Rows['cate']);?>" style="display:none;" class="blank"><p class="center pd3"><img src="<?php echo($cfg['droot']);?>common/image/ajax_small.gif" width="16" height="16" vspace="5" /></p></div>
	<div class="clear"></div>
	</form>
	<?php
	unset($depth, $funcules, $clone, $count, $countPer);
	}
}

include "../include/commonScript.php";
?>
