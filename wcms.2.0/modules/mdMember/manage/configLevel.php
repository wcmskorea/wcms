<?php
require_once  "../../../_config.php";

if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER'])) { $func->ajaxMsg("['경고!'] 정상적인 접근이 아닙니다.","", 100); }

if($_POST['type'] == "levelPost")
{
	$func->checkRefer("POST");

	$sql = "REPLACE INTO `mdMember__level` (`level`,`position`,`rate`,`default`,`recom`) VALUES ('1','관리자','1000000000000','N','N')";
	foreach ($_POST['pos'] AS $key=>$value)
	{
		$base = ($key == $_POST['basic']) ? "Y" : "N";
		$recom = ($key == $_POST['recom']) ? "Y" : "N";
		$sql .= ",('".$key."','".trim($value)."','".str_replace(",",null,trim($_POST['point'][$key]))."','".$base."','".$recom."')";
	}
	$sql .= ";";
	$db->query($sql);

	if($_POST['basic'] == '99') { $db->query(" UPDATE `mdMember__level` SET basic='Y' WHERE level='99' "); }

	//환경설정 정보에 추천인 레벨 갱신
	require	__PATH__."_Lib/classConfig.php";
    
    $config = new Config();
    $cfg['site']['merchantLevel']	= $_POST['recom'];
	$config->configed = $cfg;
	$config->configMake(array());
	$config->configSave(__HOME__."cache/config.ini.php");

	$func->err("정상적으로 적용 되었습니다.", "parent.$.menus('#left_mdMember','../modules/mdMember/manage/_left.php','#left_mdMember_a');");
}
else
{
	$func->checkRefer("GET");

	$db->query(" SELECT * FROM `mdMember__level` WHERE level BETWEEN 2 AND 98 ORDER BY level ASC ");
}
?>
<div id="messageBox"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/delete.gif" /></spna>&nbsp;<strong>Message :</strong>&nbsp;&nbsp;&nbsp;</div>

<form id="frmCate" name="frmCate" method="post" target="hdFrame" action="<?php echo($_SERVER['PHP_SELF']);?>" enctype="multipart/form-data">
<input type="hidden" name="type" value="levelPost" />

<fieldset id="help">
<legend> < TIP's > </legend>
<ul>
	<li>본 페이지는 회원의 등급을 관리합니다.</li>
    <li>기본설정 : 설정된 등급코드(등급)중 기본설정을 체크한 등급으로 신규 가입시 기본등급이 됩니다.</li>
	<li>추천인 : 설정된 등급코드(등급)중 추천레벨을 체크한 등급으로 신규 가입시 추천인으로 선택 가능합니다.</li>
    <li>비활성화된 등급은 등급명칭을 입력하고 적용을 해야 활성화 됩니다.</li>
</ul>
</fieldset>

<table class="table_list" style="width:100%;">
	<col width="80">
	<col width="80">
	<col width="80">
	<col>
	<?php if($cfg['mileage']['levelUpUse'] == 'Y') echo('<col width="170">'); ?>
	<thead>
	<tr>
	    <th class="first"><p class="center"><span>등급코드</span></p></th>
	    <th><p class="center"><span>기본설정</span></p></th>
	    <th><p class="center"><span>추천레벨</span></p></th>
	    <th><p class="center"><span>등급명칭</span></p></th>
	    <?php if($cfg['mileage']['levelUpUse'] == 'Y') echo('<th><p class="center"><span>승급포인트</span></p></th>');?>
	</tr>
	</thead>
	<tbody>
	<?php
	if($db->getNumRows() < 1)
	{
		echo('<tr><td class="blank" colspan="5">회원정보가 존재하지 않습니다.</td></tr>');
	}
	else
	{
		$b = 0;
		$n = 2;
		while($Rows = $db->fetch())
		{
			echo('<tr>
			<th><p class="center"><strong>Lv.'.str_pad($Rows['level'], 2, "0", STR_PAD_LEFT).'</strong></p></td>
			<td><p class="center"><input type="radio" id="basic_'.$Rows['level'].'" name="basic" value="'.$Rows['level'].'"');
			if($Rows['default'] == 'Y') { echo(' checked="checked"'); }
			echo('/><label for="basic_'.$Rows['level'].'">');
			if($Rows['default'] == 'Y')
			{
				echo(' <span class="colorRed">기본</span>');
				$b++;
			}
			else
			{
				echo(' 선택</label>');
			}
			echo('</p></td>
			
			<td><p class="center"><input type="radio" id="recom_'.$Rows['level'].'" name="recom" value="'.$Rows['level'].'"');
			if($Rows['recom'] == 'Y') { echo(' checked="checked"'); }
			echo('/><label for="recom_'.$Rows['level'].'">');
			if($Rows['recom'] == 'Y')
			{
				echo(' <span class="red">추천</span>');
				$b++;
			}
			else
			{
				echo(' 선택</label>');
			}
			echo('</p></td>
			<td><input class="input_text" type="text" name="pos['.$n.']" style="width:150px;" title="등급명칭" value="'.$Rows['position'].'" maxlength="6" /></td>'.($cfg['mileage']['levelUpUse'] == 'Y' ? '<td><input class="input_text" type="text" name="point['.$n.']" style="width:100px;" title="승급포인트" value="'.($Rows['rate'] > 0 ? $Rows['rate']:'').'" /></td>':''));
			echo('</tr>');
			$n++;
		}
		//공백
		while($n < 11)
		{
			echo('<tr>
			<th><p class="center">Lv.'.str_pad($n, 2, "0", STR_PAD_LEFT).'</p></td>
			<td><p class="center"><input type="radio" name="basic" disabled="disabled" /></p></td>
			<td><p class="center"><input type="radio" name="recom" disabled="disabled" /></p></td>
			<td><input class="input_text" type="text" name="pos['.$n.']" style="width:150px;" title="등급명칭" value="" maxlength="6" /></td>'.($cfg['mileage']['levelUpUse'] == 'Y' ? '
			<td><input class="input_text" type="text" name="point['.$n.']" style="width:100px;" title="승급포인트" value="" /></td>':''));
			echo('</tr>');
			$n++;
		}
	}
	?>
	<tr>
		<th><p class="center"><strong>Lv.99</strong></p></th>
		<td><p class="center"><input type="radio" id="basic_99" name="basic" value="99"<?php if($b < 1){echo(' checked="checked"');}?> /><label for="basic_99"><?php if($b < 1){echo('<span class="red">기본</span>');}else{echo('선택');}?></label></p></td>
		<td><p class="center"><input type="radio" id="recom_99" name="recom" value="99"<?php if($b < 1){echo(' checked="checked"');}?> /><label for="recom_99"><?php if($b < 1){echo('<span class="red">추천</span>');}else{echo('선택');}?></label></p></td>
		<td>승인대기</td>
		<?php if($cfg['mileage']['levelUpUse'] == 'Y') echo('<td></td>');?>
	</tr>
	</tbody>
</table>
<div class="pd5 center"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit">위 설정으로 적용</button></span></div>
</form>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$('#frmCate').validate({onkeyup:function(element){$(element).valid();}});
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
//]]>
</script>
<?php
/* 공통 스크립트 */
require_once __PATH__."_Admin/include/commonScript.php";
?>
