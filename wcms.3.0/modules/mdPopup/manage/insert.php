<?php
require_once "../../../_config.php";

if($_POST[type] == "popPost")
{
	if($_POST[idx]!="")
	{
		$seq = $_POST[idx];
	}
	else
	{
		$seq = ($db->queryFetchOne(" SELECT MAX(seq) AS seq FROM `mdPopup__content` ") + 1);
	}

	foreach($_POST AS $key => $val)
	{
		${$key} = trim($val);
		# POST값 vaild check
	}

	$subject	= trim(strip_tags(addslashes($_POST[subject])));
	$size	    = $_POST[sizeLeft].",".$_POST[sizeHeight].",".$_POST[locLeft].",".$_POST[locTop];
	$type       = $_POST[mode];
	$content    = mysql_real_escape_string(trim($_POST[content]));
	$content	= str_replace('http://'.__HOST__.'/', '/', $content);
	$content	= str_replace('<P>', '<P CLASS="dan">', $content);
	$content    = str_replace('PADDING-BOTTOM: 3px; PADDING-LEFT: 3px; PADDING-RIGHT: 3px; PADDING-TOP: 3px', null, $content);
	$content    = $func->deleteTags($content);
	$url        = str_replace("http://", null, addslashes($func->deleteTags($_POST[url])));
	$target     = ( $_POST[target] == 'Y') ? "_blank" : "_parent";
	$scroll     = ( $_POST[scroll] == 'Y') ? "Y" : "N";
	$control    = ( $_POST[control] == 'Y') ? "Y" : "N";
	$emod       = ( $_POST[emod] == 'Y') ? "Y" : "N";
	$hidden     = $_POST[hidden];
	$cate       = $_POST[cate];
	$speriod    = strtotime($speriod." ".$speriodhour.":".$speriodmin.":00");
	$eperiod    = ($unlimit == 'Y') ? 0 : strtotime($eperiod." ".$eperiodhour.":".$eperiodmin.":00");
	$position   = ( $_POST[position] == 'Y') ? "Y" : "N";

	/* 쿼리 작성 */
	$query = " REPLACE INTO `mdPopup__content` (seq,cate,type,subject,content,url,speriod,eperiod,target,control,size,position,scroll,hidden,date,info) VALUES ('".$seq."','".$cate."','".$type."','".$subject."','".$content."','".$url."','".$speriod."','".$eperiod."','".$target."','".$control."','".$size."','".$position."','".$scroll."','".$hidden."','".time()."','".$cfg[timeip]."') ";
	$db->query($query);
	if($db->getAffectedRows() < 1) {
		$func->err("입력실패 - 관리자에게 문의 바랍니다.","opener.$.insert('#content', '../modules/mdPopup/manage/_controll.php?type=".$sess->encode('popList')."',null,300);self.close()");
	} else {
		$func->err("정상적으로 처리 되었습니다.","opener.$.insert('#module', '../modules/mdPopup/manage/_controll.php?type=list',null,300); self.close();");
	}
}

if($_GET[idx])
{
	$Rows		= $db->queryFetch(" SELECT * FROM `mdPopup__content` WHERE seq='".$_GET[idx]."' ");
	$speriod    = ($Rows[speriod]) ? $Rows[speriod] : time();
    $eperiod    = ($Rows[eperiod]) ? $Rows[eperiod] : time();
	$size       = explode(",",$Rows[size]);
	if($db->getNumRows() < 1) { $func->errMsg("해당정보가 없습니다.","self.close()"); }
}
else
{
	$speriod = time();
    $eperiod = time();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
	<title><?php echo($cfg[site][siteName]);?> :: 팝업 관리</title>
	<meta http-equiv="content-type" content="application/xhtml+xml; charset=<?php echo($cfg[charset]);?>" />
	<meta http-equiv="content-style-type" content="text/css" />
	<meta http-equiv="pragma" content="no-cache" />
	<link rel="stylesheet" href="<?php echo($cfg[droot]);?>common/css/admin.css" type="text/css" charset="<?php echo($cfg[charset]);?>" media="all" />
	<script type="text/javascript" src="<?php echo($cfg[droot]);?>common/js/common.js"></script>
	<script type="text/javascript" src="<?php echo($cfg[droot]);?>common/js/editor.js"></script>
	<script type="text/javascript" src="<?php echo($cfg[droot]);?>common/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo($cfg[droot]);?>common/js/jquery.ui.js"></script>
	<script type="text/javascript" src="<?php echo($cfg[droot]);?>common/js/jquery.date.js"></script>
	<script type="text/javascript" src="<?php echo($cfg[droot]);?>common/js/jquery.validate.js"></script>
	<script type="text/javascript" src="<?php echo($cfg[droot]);?>common/js/ajax.js"></script>
</head>

<script type="text/javascript">
//<![CDATA[
//예약 or 즉시
	$.endSet = function() {
	if(toGetElementById('unlimit').checked == true) {
		toGetElementById('eperiod').className = "input_gray";
		toGetElementById('eperiod').disabled = true;
		toGetElementById('eperiodhour').disabled = true;
		toGetElementById('eperiodmin').disabled = true;
	} else {
		toGetElementById('eperiod').className = "input_text";
		toGetElementById('eperiod').disabled = false;
		toGetElementById('eperiodhour').disabled = false;
		toGetElementById('eperiodmin').disabled = false;
	}
};
//]]>
</script>
</head>
<body class="back_gray">
<div id="messageBox"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/delete.gif" /></spna>&nbsp;<strong>Message :</strong>&nbsp;&nbsp;&nbsp;</div>
<div id="calendarLayer" style="display:none;z-index:999;"><iframe name="calendarFrame" src="<?php echo($cfg[droot]);?>common/js/calendar.html" width="172" height="176" border="0" frameborder="0" scrolling="no"></iframe></div>
<form name="bbsform" id="bbsform" method="post" action="<?php echo($_SERVER[PHP_SELF]);?>" enctype="multipart/form-data">
<input type="hidden" name="type" value="popPost" />
<input type="hidden" name="idx" value="<?php echo($Rows[seq]);?>" />
<input type="hidden" name="fileName" value="" />
<div class="menu_violet"><p><strong>팝업등록 및 옵션설정</strong></p></div>

<table class="table_basic" style="width:100%;">
<caption>팝업등록 및 옵션설정</caption>
<col width="110" />
<col />
	<tbody>
	<?php
		$form = new Form('table');

		$form->addStart('노출 여부', 'hidden', 1, 0, 'M');
		$form->add('radio', array('N'=>'일반노출','Z'=>'그룹노출','Y'=>'노출안함'), $Rows['hidden'], 'color:black;');
		$form->addEnd(1);
	?>
	</tbody>
	<tr>
		<th><label for="cate" class="required"><span class="red">*</span><strong>노출 페이지</strong></label></th>
		<td><ol>
		<li class="opt"><select name="cate" class="bg_gray" title="노출위치" style="width:360px;">
		<option value="main" <?php if($Rows[cate]=="main"){echo('selected="selected"');}?> style="color:blue;">메인페이지</option>
		<?php
			$db->query(" SELECT cate,name,mode FROM `site__` WHERE SUBSTRING(cate,1,3)<>'000' AND target<>'_dep' AND status='normal' ORDER BY cate ASC ");
			while($sRows = $db->fetch()) {
			for($i=2; $i<=strlen($sRows[cate])/3; $i++) { $blank .= "　"; }
				echo('<option value="'.$sRows[cate].'"');
				if($Rows[cate] == $sRows[cate]) {
					echo(' selected="selected" style="color:#003399;"');
				} else if(strlen($sRows[cate]) == 3) {
					echo(' style="color:#990000;"');
				}
				echo('>'.$blank.' ('.substr($sRows[cate],-3).')'.$sRows[name].'</option>');
				unset($blank);
			}
		?>
		</select>&nbsp;<span class="small_gray">(해당 페이지에서 팝업을 표시)</span></li>
		</ol>
		</td>
	</tr>
	<tr>
		<th><label for="cate" class="required"><span class="red">*</span><strong>노출 일정</strong></label></th>
		<td><ol>
			<li class="opt"><input type="text" id="speriod" name="speriod" style="text-align:center;width:80px;" class="input_text required" readonly="true" value="<?php echo(date("Y-m-d", $speriod));?>" maxlength="10" title="팝업 노출 시작일을 입력해 주세요" />
			<select id="speriodhour" name="speriodhour" style="width:60px;" title="팝업 노출 시작">
			<?php for($i=0;$i<24;$i++) {
				$i = str_pad($i, 2, "0", STR_PAD_LEFT);
				echo('<option value="'.$i.'"');
				if($i == date("H", $speriod)) { echo(' selected="selected"'); }
				echo('>'.$i.'시</option>');
			} ?></select>
			<select id='speriodmin' name="speriodmin" style="width:60px;" title="팝업 노출 시작">
			<?php for($i=0;$i<60;$i++) {
				$i = str_pad($i, 2, "0", STR_PAD_LEFT);
				echo('<option value="'.$i.'"');
				if($i == date("i", $speriod)) { echo(' selected="selected"'); }
				echo('>'.$i.'분</option>');
			} ?></select>부터</li>
			<li class="opt"><input type="text" id="eperiod" name="eperiod" style="text-align:center;width:80px;" class="input_text" readonly="true" value="<?php echo(date("Y-m-d", $eperiod));?>" title="팝업 노출 마감일을 입력해 주세요" />
			<select id="eperiodhour" name="eperiodhour" style="width:60px;" title="팝업 노출 마감">
			<?php for($i=0;$i<24;$i++) {
				$i = str_pad($i, 2, "0", STR_PAD_LEFT);
				echo('<option value="'.$i.'"');
				if($i == date("H", $eperiod)) { echo(' selected="selected"'); }
				echo('>'.$i.'시</option>');
			} ?></select>
			<select id='eperiodmin' name="eperiodmin" style="width:60px;" title="팝업 노출 마감">
			<?php for($i=0;$i<60;$i++) {
				$i = str_pad($i, 2, "0", STR_PAD_LEFT);
				echo('<option value="'.$i.'"');
				if($i == date("i", $eperiod)) { echo(' selected="selected"'); }
				echo('>'.$i.'분</option>');
			} ?></select>까지 - <input name="unlimit" type="checkbox" id="unlimit" value="Y"<?php if($Rows[eperiod] < 1){echo(' checked="checked"');}?> onclick="$.endSet();" /><label for="unlimit" style="color:red;">무기한</label></li>
			</ol>
		</td>
	</tr>
	<tr>
		<th><label for="cate" class="required"><span class="red">*</span><strong>노출 위치</strong></label></th>
		<td><ol>
			<li class="opt">좌측 : <input class="required input_text" type="text" name="locLeft" style="width:30px;" value="<?php echo($size[2]);?>" digits="true" maxlength="3" />px</li>
			<li class="opt">상단 : <input class="required input_text" type="text" name="locTop" style="width:30px;" value="<?php echo($size[3]);?>" digits="true" maxlength="3" />px</li>
			<li class="opt"><input name="position" type="checkbox" id="" value="Y"<?php if($Rows[position] =='Y'){echo(' checked="checked"');}?> /><label for="position" style="color:red;">가운데</label></li>
		</ol>
		</td>
	</tr>
	<tr>
		<th><label for="cate" class="required"><span class="red">*</span><strong>노출 방식</strong></label></th>
		<td><ol>
			<li class="opt"><input name="mode" type="radio" id="mode1" class="input_radio" value="L" <?php if($Rows[type]!="W") echo 'checked="checked"'; ?> /><label for="mode1">레이어</label></li>
			<li class="opt"><input name="mode" type="radio" id="mode3" class="input_radio" value="T" <?php if($Rows[type]=="T") echo 'checked="checked"'; ?> /><label for="mode3">레이어(투명)</label></li>
			<li class="opt"><input name="mode" type="radio" id="mode2" class="input_radio" value="W" <?php if($Rows[type]=="W") echo 'checked="checked"'; ?> /><label for="mode2">일반창</label></li>
		</ol>
		</td>
	</tr>
	<tr>
		<th><label for="cate" class="required"><span class="red">*</span><strong>노출 제어</strong></label></th>
		<td><ol>
			<li class="opt"><input name="control" type="radio" id="control1" class="input_radio" value="Y" <?php if($Rows[control]!="N") echo 'checked="checked"'; ?> /><label for="control1">사용</label></li>
			<li class="opt"><input name="control" type="radio" id="control2" class="input_radio" value="N" <?php if($Rows[control]=="N") echo 'checked="checked"'; ?> /><label for="control2">사용안함</label>&nbsp;&nbsp;<span class="small_gray">(오늘 하루 더이상 열지않기 사용여부)</span></li>
		</ol>
		</td>
	</tr>
	<tr>
		<th><label for="cate" class="required"><span class="red">*</span><strong>팝업 사이즈</strong></label></th>
		<td><ol>
			<li class="opt">가로 : <input class="input_text required" type="text" name="sizeLeft" id="sizeLeft"  style="width:30px" value="<?php echo($size[0]);?>" req="required" digits="true" maxlength="4" />px</li>
			<li class="opt">세로 : <input class="input_text required" type="text" name="sizeHeight" id="sizeHeight" style="width:30px" value="<?php echo($size[1]);?>" req="required" digits="true" maxlength="4" />px</li>
			<li class="opt"><input name="scroll" type="checkbox" id="" value="Y"<?php if($Rows[scroll] =='Y'){echo(' checked="checked"');}?> /><label for="scroll" style="color:red;">스크롤</label></li>
		</ol>
		</td>
	</tr>
	<tr>
		<th><label for="cate" class="required"><span class="red">*</span><strong>팝업 타이틀</strong></label></th>
		<td><ol>
			<li class="opt"><input class="input_text required" type="text" name="subject" style="width:360px;" value="<?php echo($Rows[subject]);?>" req="required" maxlength="50" /> <span class="small_gray">(브라우저 툴바에 노출되는 타이틀명)</span></li>
		</ol>
		</td>
	</tr>
	<tr>
		<th><label for="cate"><strong>링크 연결</strong></label></th>
		<td><ol>
			<li class="opt"><input class="input_gray" type="text" name="url" style="width:360px;" value="<?php echo($Rows[url]);?>" maxlength="100" />&nbsp;<input name="target" type="checkbox" id="target" value="Y" class="input_check" <?php if($Rows[target]=="_blank"){echo(' checked="checked"');}?>/>&nbsp;<label for="target">새창열기</label></li>
		</ol>
		</td>
	</tr>
	<tr>
		<th><label for="cate"><strong>상세 내용</strong><br /><br /><span><a href="#none" onclick="$.dialog('./skinSet.php','&amp;type=<?php echo($sess->encode('skin'));?>',470,250)" class="btnPack metal small"><span>스킨삽입</span></a></span></label></th>
		<td class="dash_bottom bg_gray">
		<?php
		//상세내용 : 다음 에디터
		include __PATH__."addon/editor/editor.php";
		?>
		</td>
	</tr>
</table>
<div class="center pd5"><span class="btnPack black medium strong"><button type="submit" onclick="return submitForm(this.form);" class="red"><?php echo($lang['doc']['submit']);?></button>
</span>&nbsp;<span class="btnPack gray medium"><a href="javascript:self.close();"><?php echo($lang['doc']['cancel']);?></a></span></div>
</form>
<?php
if($Rows[emod]=="Y") { echo '<script type="text/javascript">$.endSet()</script>'; }
?>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function() {
	<?php
	if($Rows[eperiod] < 1) { echo('$.endSet();'); }
	?>
	$("input[type=text],input[type=password],textarea").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active"); });
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
$(document).keypress(function(e){if(e.which == 27) $.dialogRemove();});
//]]>
</script>
<script type="text/javascript">
//<[!CDATA[
	initCal({id:"speriod",type:"day",today:"y",icon:"n"});
	initCal({id:"eperiod",type:"day",today:"y",icon:"n"});
//]]>
</script>
</body>
</html>
<?php
$db->Disconnect();
?>
