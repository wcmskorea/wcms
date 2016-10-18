<?php
#------------------------------------------------------------------------------
# 작업내용: 에디터 파일 업로드
# 작성일자: 2007-07-02
#------------------------------------------------------------------------------
require_once "../../_config.php";
#------------------------------------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<title>이미지 삽입하기</title>
<meta http-equiv="content-type" content="text/html; charset=<?php echo($cfg['charset']);?>" />
<style type="text/css">
body { background: threedface; color: windowtext; margin: 10px; border: 0px; padding: 0px; }
td, input, select { font-family: "gulim, MS Sans Serif"; font-size: 9pt; vertical-align: middle; }
.handCursor { cursor: pointer;}
td.hover { background-color : Fuchsia; }
table.dlg { border:0; }
fieldset { border: 1px solid #c5c5cc; padding: 2px; }
.dlg td { text-align: left; height: 20px; }
form { display: inline; }
.dlg input { border: 2px; }
.img { border: 0; vertical-align: middle; }
.normal { font-family: gulim; font-size: 9pt; }
.button { font-family: gulim; font-size: 9pt; padding-top: 2px; height: 21px; width: 6em; }
.button8em { font-family: gulim; font-size: 9pt; padding-top: 2px; height: 21px; width: 8em; }
.button10em { font-family: gulim; font-size: 9pt; padding-top: 2px; height: 21px; width: 10em; }
.spacer { margin: 10px 0px 0px 0px; }
.wrapper { text-align: center; }
.clear {display:block; float:none; clear:both; height:0; width:100%; font-size:0 !important; line-height:0 !important; overflow:hidden; margin:0 !important; padding:0 !important;}
</style>
<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/common.js"></script>
<script type="text/javascript" language="javascript">
//<![CDATA[
function chkImg (el)
{
  var file = el.value;
  var allowSubmit = false;
  var extArray = new Array('.jpg','.jpeg','.gif','.png','.swf','.flv');
  extArray.join(" ");
  if (!file) return false;
  while (file.indexOf("\\") != -1)
     file = file.slice(file.indexOf("\\") + 1);
  var ext = file.slice(file.lastIndexOf(".")).toLowerCase();
  for (var i = 0; i < extArray.length; i++) {
      if (extArray[i] == ext) {
          allowSubmit = true;
          break;
      }
  }
  if (allowSubmit) {
      return true;
  }
  else {
      alert("그림 삽입은 GIF, JPG, PNG 파일만 가능합니다. 다시 선택하여 주십시요.");
      el.select();
      document.selection.clear();
      return false;
  }
}
//]]>
</script>
</head>
<body>
<form name="insertImage" action="./upPost.php" method="post" enctype="multipart/form-data" onsubmit="return checkForm(this)">
<input type="hidden" name="type" value="<?php echo($sess->encode('editorFilePost'));?>" />
<input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />
<input type="hidden" name="op" value="image" />

<fieldset style="float:left;"><legend><strong class="normal">파일선택 (<?php echo(@ini_get('upload_max_filesize'));?>이하)</strong></legend>
    <div style="padding:10px; text-align:center">
			<input type="file" size="15" name="upfile" req="required" trim="trim" title="첨부파일" onchange="chkImg(this)" />
    </div>
</fieldset>
<fieldset style="float:right;"><legend><strong class="normal">사 이 즈</strong></legend>
    <div style="padding:10px; text-align:center">
			<select name="width" title="이미지 사이즈" class="small_gray">
				<option value="98%">100%</option>
				<option value="90%">90%</option>
				<option value="80%">80%</option>
				<option value="70%">70%</option>
				<option value="60%">60%</option>
				<option value="50%">50%</option>
				<option value="40%">40%</option>
				<option value="30%">30%</option>
				<option value="20%">20%</option>
				<option value="10%">10%</option>
			</select>
    </div>
</fieldset>
<div class="clear"></div>

<div style="margin:10px 0px 10px 0px">
	<fieldset><legend><strong class="normal">화면의 위치선택</strong></legend>
	<div style="padding:10px; text-align:center">
	<input type="radio" name="alignment" value="left" checked="checked" title="왼쪽정렬" /><img src="<?php echo($cfg['droot']);?>image/editor/image_align_left.gif" align="middle" alt="왼쪽정렬" title="왼쪽정렬" />
	<input type="radio" name="alignment" value="right"title="오른쪽정렬"  /><img src="<?php echo($cfg['droot']);?>image/editor/image_align_right.gif" align="middle" alt="" title="우측정렬" />
	<input type="radio" name="alignment" value="break" title="연속이미지 넣기" /><img src="<?php echo($cfg['droot']);?>image/editor/image_align_breakline.gif" align="middle" alt="" title="가운데 연속붙이기" />
	<input type="radio" name="alignment" value="top" title="상단정렬" /><img src="<?php echo($cfg['droot']);?>image/editor/image_align_top.gif" align="middle" alt="" title="상단정렬" />
	<input type="radio" name="alignment" value="middle" title="중앙정렬" /><img src="<?php echo($cfg['droot']);?>image/editor/image_align_middle.gif" align="middle" alt="" title="가운데정렬" />
	<input type="radio" name="alignment" value="bottom" title="하단정렬" /><img src="<?php echo($cfg['droot']);?>image/editor/image_align_bottom.gif" align="middle" alt="" title="하단정렬" />
	</div>
	</fieldset>
</div>
<div style="text-align:center;margin-top:10px">
    <span class="button bbasic strong"><button type="submit">삽입하기</button></span>&nbsp;
    <span class="button bbasic"><button onclick="self.close(); return false;">취소</button></span>
</div>
</form>
</body>
</html>
