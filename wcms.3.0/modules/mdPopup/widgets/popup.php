<?php
if(!(__CATE__)) { $cate = "main"; }

if(!isset($_COOKIE["POPUP_ZONE"]))
{
	$query = " SELECT * FROM `mdPopup__content` WHERE speriod<'".time()."' AND (eperiod='0' OR eperiod>'".time()."') AND hidden='Z' AND cate='".__CATE__."' ORDER BY seq DESC LIMIT 10 ";
	$db->query($query);
	if($db->getNumRows() > 0) {

		echo('<div id="POPUP_ZONE" class="dialog" title="팝업존">
		<div style="width:320px; height:320px; background: url('.$cfg[droot].'modules/mdPopup/skin/skin_zone.jpg) no-repeat; padding:80px 25px 0 25px;">
		<ul>');

		while($Rows = $db->fetch()) {
			$size     = explode(",",$Rows[size]);
			$width    = $size[0];
			$height   = $size[1];
			$Rows[subject]	= $func->cutStr($Rows[subject], 36, "...");
			echo('<li class="pd3">ㆍ<a href="#none" onclick="$.dialog(\'../modules/mdPopup/addon/popupView.php?idx='.$Rows[seq].'\',null,'.$width.','.$height.')" class="actUnder"><strong>'.$Rows[subject].'</strong></a></li>').PHP_EOL;
		}

		echo('</ul></div>
		<div style="padding:5px;_padding:0px; background:#fff;">
		<ol>
			<li style="float:right;margin-right:3px;"><input type="radio" name="dayOff" id="dayOff1" style="vertical-align:middle;" onclick="setCookie(\'POPUP_ZONE\',\'popup\',86400000);$(\'#POPUP_ZONE\').dialog(\'close\');"><label for="dayOff1" onclick="setCookie(\'POPUP_ZONE\',\'popup\',86400000);$(\'#POPUP_ZONE\').dialog(\'close\');" class="small_gray">하루</label></li>
			<li style="float:right;margin-right:3px;"><input type="radio" name="dayOff" id="dayOff2" style="vertical-align:middle;" onclick="setCookie(\'POPUP_ZONE\',\'popup\',1296000000);$(\'#POPUP_ZONE\').dialog(\'close\');"><label for="dayOff2" onclick="setCookie(\'POPUP_ZONE\',\'popup\',1296000000);$(\'#POPUP_ZONE\').dialog(\'close\');" class="small_gray">15일</label></li>
			<li style="float:right;margin-right:3px;"><input type="radio" name="dayOff" id="dayOff3" style="vertical-align:middle;" onclick="setCookie(\'POPUP_ZONE\',\'popup\',2592000000);$(\'#POPUP_ZONE\').dialog(\'close\');"><label for="dayOff3" onclick="setCookie(\'POPUP_ZONE\',\'popup\',2592000000);$(\'#POPUP_ZONE\').dialog(\'close\');" class="small_gray">30일</label></li>
		</ol>
		<div class="clear"></div>
		</div>
		</div><script type="text/javascript">
		//<![CDATA[
		$(document).ready(function() {
			$("#POPUP_ZONE").css("display","inline");
			$("#POPUP_ZONE").dialog({width:320,height:450,position:[10,10]});
		});
		//]]>
		</script>');
	}
}

$db->query(" SELECT * FROM `mdPopup__content` WHERE speriod<'".time()."' AND (eperiod='0' OR eperiod>'".time()."') AND hidden='N' AND cate='".$cate."' ORDER BY seq ");
if($db->getNumRows() > 0)
{
	$n = 1;
	while($Rows = $db->fetch())
	{
		$URL = ($Rows['url']) ? "http://".str_replace("http://", null, $Rows['url']) : "#none";
		$content = stripslashes($Rows['content']);
		$content = str_replace("<IMG alt=", '<img style="width:100%" alt=', $content);
		$Scroll = $Rows['scroll'];
		$Size = explode(",",$Rows['size']);
		if(!$_COOKIE["POPUP".$Rows['seq']])
		{
			if($Rows['type'] == "L" || $Rows['type'] == "T")
			{
				echo('<div id="POPUP'.$Rows[seq].'" title="'.$Rows['subject'].'" class="dialog" style="height:'.intval($Size[1]).'px">
					<div onclick="go_url(\''.$URL.'\',\''.$Rows['target'].'\');" class="textContent">
						<div style="cursor:pointer;_cursor:hand; width:'.intval($Size[0]).'px; height:'.intval($Size[1]).'px; overflow:'.($Scroll=="Y" ? 'scroll':'hidden').'">'.$content.'</div>
					</div>').PHP_EOL;

				if($Rows['control'] == "Y")
				{
					echo('<div style="right:0; padding-top:3px;">
		            <ol style="margin:0; padding:0">
		              <li style="float:right;margin-right:5px"><input type="radio" name="dayOff" id="dayOff1" style="vertical-align:middle;" onclick="setCookie(\'POPUP'.$Rows[seq].'\',\'popup\',86400000);$(\'#POPUP'.$Rows[seq].'\').dialog(\'close\');"><label for="dayOff1" onclick="setCookie(\'POPUP'.$Rows[seq].'\',\'popup\',86400000);$(\'#POPUP'.$Rows[seq].'\').dialog(\'close\');" class="small_gray">하루동안 닫기</label></li>
		              <li style="float:right;margin-right:5px"><input type="radio" name="dayOff" id="dayOff2" style="vertical-align:middle;" onclick="setCookie(\'POPUP'.$Rows[seq].'\',\'popup\',1296000000);$(\'#POPUP'.$Rows[seq].'\').dialog(\'close\');"><label for="dayOff2" onclick="setCookie(\'POPUP'.$Rows[seq].'\',\'popup\',1296000000);$(\'#POPUP'.$Rows[seq].'\').dialog(\'close\');" class="small_gray">15일</label></li>
		              <li style="float:right;margin-right:5px"><input type="radio" name="dayOff" id="dayOff3" style="vertical-align:middle;" onclick="setCookie(\'POPUP'.$Rows[seq].'\',\'popup\',2592000000);$(\'#POPUP'.$Rows[seq].'\').dialog(\'close\');"><label for="dayOff3" onclick="setCookie(\'POPUP'.$Rows[seq].'\',\'popup\',2592000000);$(\'#POPUP'.$Rows[seq].'\').dialog(\'close\');" class="small_gray">30일</label></li>
		            </ol>
		            <div class="clear"></div>
					</div>').PHP_EOL;
				}

				//투명처리
				echo('</div><script type="text/javascript">$(function(){$("#POPUP'.$Rows['seq'].'").dialog({width:'.intval($Size[0]).',height:'.intval($Size[1]+50).($Rows['position']!='Y' ? ',position:['.intval($Size[2]).','.intval($Size[3]).']' : '').',show:{effect:\'drop\',direction:"up"},stack:false,sticky:false});');
				if($Rows['type'] == "L") { echo('$(".ui-dialog").css("background","#fff");'); }	//기존 레이어는 배경 흰색 표시(2012-11-23)
				if($Rows['type'] == "T" && $n == $db->getNumRows()) { echo('$(".ui-dialog").css("border","0px");$(".ui-dialog-titlebar").css({"height":"0"});'); }
				echo('});</script>').PHP_EOL;
				
			}
			else
			{
				echo('<script type="text/javascript">new_window3(\''.$cfg[droot].'modules/mdPopup/widgets/popup.html?idx='.$Rows[seq].'\',\'popup\','.intval($Size[0]).','.intval($Size[1]+15).','.intval($Size[3]).','.intval($Size[2]).',\'no\',\''.($Scroll=="Y" ? 'yes':'no').'\');</script>');
			}
		}
		$n++;
	}
}
?>