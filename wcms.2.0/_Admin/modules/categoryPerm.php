<?php
require_once "../include/commonHeader.php";

if($_POST['type'] == "catePermPost") 
{
	unset($perm, $permLmt);
	//데이터 정리
	foreach($_POST['perm'] AS $key=>$val) 
	{ 
		$perm .= $val.','; 

		/**
		 * 네이버 신디케이션 처리
		*/
		if($func->checkModule("mdSyndication") && $cfg['site']['naverSyndiYN'] == 'Y')
		{
			//접근권한이 변경됐을 경우
			if($key == 1 && $_POST['perm']['1'] != $_POST['oldPerm1'])
			{
				$result = $syndi->boardModify();
			}
			//열람권한이 변경됐을 경우
			if($key == 2 && $_POST['perm']['2'] != $_POST['oldPerm2'])
			{
				$result = $syndi->boardModify();
			}
		}
	}
	foreach($_POST['limit'] AS $key=>$val) 
	{
		if($key == 0 && !is_numeric($_POST['perm']['0'])) 
		{ 
			$permLmt .= 'P,'; 
		} 
		else if($key == 0 && is_numeric($_POST['perm']['0'])) 
		{ 
			$permLmt .= 'U,';
		} 
		else 
		{ 
			$permLmt .= $val.',';
		}
	}
	$perm = preg_replace('/,$/', '', $perm);
	$permLmt = preg_replace('/,$/', '', $permLmt);

	//쿼리작성
	if($_POST['sync'] == 'Y') 
	{
		$query = "UPDATE `site__` SET perm='".$perm."',permLmt='".$permLmt."' WHERE skin='".$_POST['skin']."' AND cate like '".__CATE__."%'";
	} 
	else 
	{
		$query = "UPDATE `site__` SET perm='".$perm."',permLmt='".$permLmt."' WHERE skin='".$_POST['skin']."' AND cate='".__CATE__."'";
	}
	$db->query($query);
	if($db->getAffectedRows() > 0) 
	{
		//$func->ajaxMsg("권한 설정이 정상적으로 적용되었습니다.","", 20);
		$func->err("권한 설정이 정상적으로 적용되었습니다.", "parent.$.dialogRemove();");
	} 
	else 
	{
		//$func->ajaxMsg("권한 설정이 변경된 내용이 없거나, 적용 실패입니다.","", 20)
		$func->err("권한 설정이 변경된 내용이 없거나, 적용 실패입니다.", "parent.$.dialogRemove();");
	}
} 
else if($_GET['type'] == "catePerm") 
{
	if(! __CATE__ ) { $func->err("(".__CATE__.") 카테고리 정보가 존재하지 않습니다.","", 20); }
	//권한 배열정리
	$permision = array();
	$db->query(" SELECT * FROM `mdMember__level` ORDER BY level ASC ");
	while($Row = $db->fetch()) 
	{
		if($Row['level'] >= 2) { $permision[$Row['level']] = $Row['position']; }
	}
	
	$perms = $db->queryFetch(" SELECT perm,permLmt FROM `site__` WHERE skin='".$_GET['skin']."' AND cate='".__CATE__."' ");
	$member = new Member($perms['perm'], $perms['permLmt']);
}
?>

<form name="frmCate" method="post" enctype="multipart/form-data" action="<?php echo($_SERVER['PHP_SELF']);?>" target="hdFrame">
<input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />
<input type="hidden" name="skin" value="<?php echo($_GET['skin']);?>" />
<input type="hidden" name="type" value="catePermPost" />
<input type="hidden" name="oldPerm1" value="<?php echo($member->perm['1']);?>" />
<input type="hidden" name="oldPerm2" value="<?php echo($member->perm['2']);?>" />

<div class="menu_violet">
	<p title="드래그하여 이동하실 수 있습니다"><strong>카테고리 권한설정 (<?php echo($_GET['skin']);?>)</strong></p>
</div>

<table class="table_basic" style="width:100%;">
	<caption>관리자 정보 설정</caption>
	<col width="100">
	<col>
	<tr>
		<th>관리권한 설정</th>
		<td><select name="perm[0]" class="bg_gray" style="width:150px" title="관리권한">
			<option value="">선택하세요</option>
			<?php
			foreach ($permision as $key => $val) {
				if($val) {
					echo('<option value="'.$key.'"');
					if($member->perm['0'] == $key) { echo(' selected="selected" style="color:#990000;"'); }
					echo('>('.$key.') '.$val.'</option>');
				}
			}
			echo('<optgroup label="--담당자 지정--">');
			$db->query(" SELECT id,name FROM `mdMember__account` WHERE level BETWEEN '".$cfg['operator']."' AND '".intval($cfg['operator'] + 1)."' ");
			while($sRows = $db->fetch()) {
				echo('<option value="'.$sRows['id'].'"');
				if($member->perm['0'] == $sRows['id']) { echo(' selected="selected" style="color:#990000;"'); }
				echo('>'.$sRows['name'].'('.$sRows['id'].')</option>');
			}
			echo('</optgroup>');
			?>
			</select>&nbsp;
			<input name="limit[0]" type="radio" id="limit1_1" class="input_radio" value="U"<?php if(!$member->permLimit['0'] || $member->permLimit['0']=='U'){echo(' checked="checked"');}?> /><label for="limit1_1">이상레벨</label>
			<input name="limit[0]" type="radio" id="limit1_2" class="input_radio" value="E"<?php if($member->permLimit['0']=='E'){echo(' checked="checked"');}?> /><label for="limit1_2">동일레벨</label>
			<input name="limit[0]" type="radio" id="limit1_3" class="input_radio" value="P"<?php if($member->permLimit['0']=='P'){echo(' checked="checked"');}?> /><label for="limit1_3">담당지정</label>
		</td>
	</tr>
	<tr>
		<th>접근권한 설정</th>
		<td><select name="perm[1]" class="bg_gray" style="width:150px" title="접근권한">
			<option value="">선택하세요</option>
			<?php
			foreach ($permision as $key => $val) {
				if($val) {
					echo('<option value="'.$key.'"');
					if($member->perm['1'] == $key) { echo(' selected="selected" style="color:#990000;"'); }
					echo('>('.$key.') '.$val.'</option>');
				}
			}
			?>
			</select>&nbsp;
			<input name="limit[1]" type="radio" id="limit2_1" class="input_radio" value="U"<?php if(!$member->permLimit['1'] || $member->permLimit['1']=='U'){echo(' checked="checked"');}?> /><label for="limit2_1">이상레벨</label>
			<input name="limit[1]" type="radio" id="limit2_2" class="input_radio" value="E"<?php if($member->permLimit['1']=='E'){echo(' checked="checked"');}?> /><label for="limit2_2">동일레벨</label>
			<input name="limit[1]" type="radio" id="limit2_3" class="input_radio" value="A"<?php if($member->permLimit['1']=='A'){echo(' checked="checked"');}?> /><label for="limit2_3">성인인증</label>
		</td>
	</tr>
	<tr>
		<th>열람권한 설정</th>
		<td><select name="perm[2]" class="bg_gray" style="width:150px"
			title="열람권한">
			<option value=''>선택하세요</option>
			<?php
			foreach ($permision as $key => $val) {
				if($val) {
					echo('<option value="'.$key.'"');
					if($member->perm['2'] == $key) { echo(' selected="selected" style="color:#990000;"'); }
					echo('>('.$key.') '.$val.'</option>');
				}
			}
			?>
			</select>&nbsp;
			<input name="limit[2]" type="radio" id="limit3_1" class="input_radio" value="U"<?php if(!$member->permLimit['2'] || $member->permLimit['2']=='U'){echo(' checked="checked"');}?> /><label for="limit3_1">이상레벨</label>
			<input name="limit[2]" type="radio" id="limit3_2" class="input_radio" value="E"<?php if($member->permLimit['2']=='E'){echo(' checked="checked"');}?> /><label for="limit3_2">동일레벨</label>
		</td>
	</tr>
	<tr>
		<th>작성권한 설정</th>
		<td><select name="perm[3]" class="bg_gray" style="width:150px" title="작성권한">
			<option value="">선택하세요</option>
			<?php
			foreach ($permision as $key => $val) {
				if($val) {
					echo('<option value="'.$key.'"');
					if($member->perm['3'] == $key) { echo(' selected="selected" style="color:#990000;"'); }
					echo('>('.$key.') '.$val.'</option>');
				}
			}
			?>
			</select>&nbsp;
			<input name="limit[3]" type="radio" id="limit4_1" class="input_radio" value="U"<?php if(!$member->permLimit['3'] || $member->permLimit['3']=='U'){echo(' checked="checked"');}?> /><label for="limit4_1">이상레벨</label>
			<input name="limit[3]" type="radio" id="limit4_2" class="input_radio" value="E"<?php if($member->permLimit['3']=='E'){echo(' checked="checked"');}?> /><label for="limit4_2">동일레벨</label>
		</td>
	</tr>
	<tr>
		<th>답변권한 설정</th>
		<td><select name="perm[4]" class="bg_gray" style="width:150px" title="답변권한">
			<option value="">선택하세요</option>
			<?php
			foreach ($permision as $key => $val) {
				if($val) {
					echo('<option value="'.$key.'"');
					if($member->perm['4'] == $key) { echo(' selected="selected" style="color:#990000;"'); }
					echo('>('.$key.') '.$val.'</option>');
				}
			}
			?>
			</select>&nbsp;
			<input name="limit[4]" type="radio" id="limit5_1" class="input_radio" value="U"<?php if(!$member->permLimit['4'] || $member->permLimit['4']=='U'){echo(' checked="checked"');}?> /><label for="limit5_1">이상레벨</label>
			<input name="limit[4]" type="radio" id="limit5_2" class="input_radio" value="E"<?php if($member->permLimit['4']=='E'){echo(' checked="checked"');}?> /><label for="limit5_2">동일레벨</label>
		</td>
	</tr>
	<tr>
		<th>하위 동일적용</th>
		<td class="darkgray"><input name="sync" type="radio" id="sync_1" class="input_radio" value="N" checked="checked" /><label for="sync_1">적용안함</label>
		<input name="sync" type="radio" id="sync_2" class="input_radio" value="Y" /><label for="sync_2">적용함</label>
		</td>
	</tr>
</table>
<div class="pd5 right"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit">위 설정으로 적용</button></span>&nbsp;<span class="btnPack medium black"><a href="javascript:;" onclick="$.dialogRemove();">취소</a></span></div>
</form>
<?php include "../include/commonScript.php"; ?>
