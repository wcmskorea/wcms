<?php
require_once "../../../_config.php";

# 리퍼러 체크
if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER'])){ $func->err("[경고!] 정상적인 접근이 아닙니다.", "window.self.close();"); }

if($_GET[type] == "skinPost") {
	
	if($_GET[disp] == "") { $func->ajaxMsg("선택한 스킨정보가 없습니다.","", 20); }
?>
<script type="text/javascript">
//<![CDATA[
	var htm = "<?php echo($_GET[disp]);?>";
/*
	editor._doc.open();
	editor._doc.write('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko"><head><link rel="stylesheet" href="<?php echo($cfg[droot]);?>common/css/default.css" type="text/css" charset="<?php echo($cfg[charset]);?>" media="all" /><link rel="stylesheet" href="<?php echo($cfg[droot]);?>skin/default/data/stylesheet.css" type="text/css" charset="<?php echo($cfg[charset]);?>" media="all" /></head><body class="textContent"><table><tr><td style="width:280px;height:380px;background:URL(<?php echo($cfg[droot]);?>modules/mdPopup/skin/'+htm+') no-repeat;padding:10px;vertical-align:top;" class="textContent"></td></tr></table></body></html>');
	editor._doc.close();
	toGetElementById("contents").value = "";
	toGetElementById("sizeLeft").value = "300";
	toGetElementById("sizeHeight").value = "400";
*/
/* 다음에디터에 스킨 삽입하기 2012.01.09 강인 */
Editor.getCanvas().pasteContent('<html><head><link rel="stylesheet" href="<?php echo($cfg[droot]);?>common/css/default.css" type="text/css" charset="<?php echo($cfg[charset]);?>" media="all" /><link rel="stylesheet" href="<?php echo($cfg[droot]);?>skin/default/data/stylesheet.css" type="text/css" charset="<?php echo($cfg[charset]);?>" media="all" /></head><body class="textContent"><table><tr><td style="width:280px;height:380px;background:URL(<?php echo($cfg[droot]);?>modules/mdPopup/skin/'+htm+') no-repeat;padding:10px;vertical-align:top;" class="textContent"></td></tr></table></body></html>');

		$.dialogRemove();
//]]>
</script>
<?php
}
?>
<form name="frm022" method="post" action="" enctype="multipart/form-data" onsubmit="return $.checkFarm(this, './skinSet.php');">
<input type="hidden" name="type" value="skinPost" />
<div id="popCate">
<div class="menu_violet"><p title="드래그하여 이동하실 수 있습니다"><strong>팝업 스킨선택</strong></p></div>

<div id="content" style="padding:10px;">
	<ol>
	<?php
		$fileArray	= $func->dirOpen(__PATH__."/modules/mdPopup/skin/");
		sort($fileArray);
		$wide	 = 1;
		foreach ($fileArray as $key=>$val) {
			$skin = explode(".",$val);
			echo('<li style="float:left;padding:3px;"><input type="radio" id="check01_'.$key.'" name="disp" value="'.$val.'" /><label for="check01_'.$key.'"><span>');
			echo('<b>'.$skin[0].'</b></span></label><br /><img src="'.$cfg[droot].'modules/mdPopup/skin/'.$val.'" width="100" height="133" style="border:1px solid #666;" /></li>');
		}
	?>
	</ol>
	<div class="clear"></div>
</div>
<div class="pd5 center"><span class="button bred strong"><button type="submit">적용하기</button></span></div>
</div></form>
