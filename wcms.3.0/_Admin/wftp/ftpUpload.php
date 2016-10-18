<?php
require_once "../../_config.php";
require_once "../include/commonHeader.php";

if($_GET[type] == 'folder')
{

?>
<div id="messageBox"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/delete.gif" /></spna>&nbsp;<strong>Message :</strong>&nbsp;&nbsp;&nbsp;</div>
<form id="ftpFrm" name="ftpFrm" method="post" enctype="multipart/form-data" onsubmit="return $.checkFarm(this,'./ftpProcess.php',300,100);">
<input type="hidden" name="type" value="folderPost" />
<input type="hidden" name="dir" value="<?php echo($_GET[dir]);?>" />
<div class="menu_violet">
	<p style="text-align:left;">&nbsp;생성위치 : &nbsp;/<?php echo($_GET[dir]);?></p>
</div>
<table class="table_basic" style="width: 100%;">
<col width="100" />
<col />
<tbody>
	<tr>
		<th><p class="bold center">폴더명</p></th>
		<td><input type="text" id="newName" name="newName" style="width:270px;" class="input_text required" filename="true" minlength="1" maxlength="20" req="required" /></td>
	</tr>
</tbody>
</table>
<div class="right pd5"><span class="btnPack medium icon"><span class="check"></span><button type="submit">폴더 생성하기</button></span>&nbsp;<span class="btnPack black medium"><a href="javascript:;" onclick="$.dialogRemove();">취소</a></span></div>
</form>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
		setTimeout ("$('#newName').select()", 500);
	$('#ftpFrm').validate({onkeyup:function(element){$(element).valid();}});
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
//]]>
</script>
<?php

} else if($_GET[type] == 'file') {
	$flashvars  = "flash_width=380&amp;";
	$flashvars .= "list_rows=5&amp;";
	$flashvars .= "limit_size=".str_replace("M","",INI_GET('upload_max_filesize'))."&amp;";
	$flashvars .= "file_type_name=웹파일&amp;";
	$flashvars .= "allow_filetype=*.mp4 *.html *.js *.css *.psd *.ai *.jpg *.jpeg *.gif *.png *.bmp *.swf *.wmv *.asf *.mp3 *.wav *.flv *.fla *.ico *.hwp *.ppt *.ppt *.doc *.xls *.pdf *.xml *.as *.zip&amp;";
	$flashvars .= "deny_filetype=*.cgi *.pl *.php *.php *.inc *.class&amp;";
	$flashvars .= "upload_exe=ftpUploadPost.php&amp;";
	$flashvars .= "upload_id=".$_GET['dir']."&amp;";
	$flashvars .= "browser_id=".$_REQUEST["PHPSESSID"];

?>
<div class="menu_violet">
	<p style="text-align: left;">&nbsp;업로드위치 : &nbsp;/<?php echo($_GET['dir']);?></p>
</div>
<table class="table_basic" style="width: 100%;">
<tbody>
	<tr>
		<td><object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="380" height="125" align="middle" id="fup" method="multi_upload">
			<param name="movie" value="ftpUpload.swf" />
			<param name="quality" value="high" />
			<param name="flashvars" value="<?php echo($flashvars);?>" />
			<embed src="ftpUpload.swf" width="380" height="125" align="middle" type="application/x-shockwave-flash" /></object>
		</td>
	</tr>
</tbody>
</table>
<div id="ftpUploadButton1" class="right pd5 show"><span class="btnPack medium icon"><span class="check"></span><a href="javascript:;" onclick="swfUpload(); return false;">파일 전송하기</a></span>&nbsp;<span class="btnPack black medium"><a href="javascript:;" onclick="$.dialogRemove();">취소</a></span>
</div>
<div id="ftpUploadButton2" class="right pd5 hide"><span class="btnPack medium icon"><span class="check"></span><a href="javascript:;" onclick="$.insert('#dirSub','./ftpData.php?dir=<?php echo($_GET['dir']);?>',null,300); $.dialogRemove();">전송 완료</a></span></div>

<?php
} else if($_GET[type] == 'file2') {
		session_start();

?>
<script type="text/javascript" src="./swfupload/swfupload.js"></script>
<script type="text/javascript" src="./swfupload/swfupload.queue.js"></script>
<script type="text/javascript" src="js/fileprogress.js"></script>
<script type="text/javascript" src="js/handlers.js"></script>
<script type="text/javascript">
		var swfu;

		window.onload = function() {
			var settings = {
				flash_url : "./swfupload/swfupload.swf",
				flash9_url : "./swfupload/swfupload_fp9.swf",
				upload_url: "upload.php?upload_id=<?php echo($_GET['dir']);?>",
				post_params: {"PHPSESSID" : "<?php echo session_id(); ?>"},
				file_size_limit : "100 MB",
				file_types : "*.html;*.js;*.css;*.jpg;*.gif;*.png;*.pdf;*.zip",
				file_types_description : "All Files",
				file_upload_limit : 100,
				file_queue_limit : 0,
				custom_settings : {
					progressTarget : "fsUploadProgress",
					cancelButtonId : "btnCancel"
				},
				debug: false,

				// Button settings
				button_image_url: "",
				button_width: "150",
				button_height: "29",
				button_placeholder_id: "uploadButton",
				button_text: 'File Upload',
				button_text_style: ".theFont { font-size: 16; }",
				button_text_left_padding: 12,
				button_text_top_padding: 3,
				
				// The event handler functions are defined in handlers.js
				swfupload_preload_handler : preLoad,
				swfupload_load_failed_handler : loadFailed,
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,
				queue_complete_handler : queueComplete	// Queue plugin event
			};

			swfu = new SWFUpload(settings);
	     };
</script>
<style>
span, fieldset, form, label, legend {
	margin: 0;
	padding: 0;
	border: 0;
	outline: 0;
	font-weight: inherit;
	font-style: inherit;
	font-size: 100%;
	font-family: inherit;
	vertical-align: baseline;
}

div.fieldset {
	border:  1px solid #afe14c;
	margin: 10px 0;
	padding: 20px 10px;
}
	div.fieldset span.legend {
	position: relative;
	background-color: #FFF;
	padding: 3px;
	top: -30px;
	font: 700 14px Arial, Helvetica, sans-serif;
	color: #73b304;
}
div.flash {
	width: 375px;
	margin: 10px 5px;
	border-color: #D9E4FF;

	-moz-border-radius-topleft : 5px;
	-webkit-border-top-left-radius : 5px;
    -moz-border-radius-topright : 5px;
    -webkit-border-top-right-radius : 5px;
    -moz-border-radius-bottomleft : 5px;
    -webkit-border-bottom-left-radius : 5px;
    -moz-border-radius-bottomright : 5px;
    -webkit-border-bottom-right-radius : 5px;

}

</style>
<div class="menu_violet">
	<p style="text-align: left;">&nbsp;업로드위치 : &nbsp;/<?php echo($_GET['dir']);?></p>
</div>
<table class="table_basic" style="width:100%;">
<col width="100">
<col>
<tbody>
<tr><td>
<div id="content">
	<form id="form1" action="index.php" method="post" enctype="multipart/form-data">
			<div class="fieldset flash" id="fsUploadProgress">
			<span class="legend">Upload Queue</span>
			</div>
		<div id="divStatus">0 Files Uploaded</div>
			<div>
				<span id="uploadButton"></span>
				<input id="btnCancel" type="button" value="업로드 취소" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
			</div>
	</form>
</div>
</td></tr>
</tbody>
</table>
<!--div id="ftpUploadButton1" class="right pd5 show"><span class="btnPack medium icon"><span class="check"></span><a href="javascript:;" onclick="swfUpload(); return false;">파일 전송하기</a></span>&nbsp;<span class="btnPack black medium"><a href="javascript:;" onclick="$.dialogRemove();">취소</a></span>
</div>
<div id="ftpUploadButton2" class="right pd5 hide"><span class="btnPack medium icon"><span class="check"></span><a href="javascript:;" onclick="$.insert('#dirSub','./ftpData.php?dir=<?php echo($_GET['dir']);?>',null,300); $.dialogRemove();">전송 완료</a></span></div>-->
<?php
} else if($_GET[type] == 'file3') {
?>
<div class="menu_violet">
	<p style="text-align: left;">&nbsp;업로드위치 : &nbsp;/<?php echo($_GET['dir']);?></p>
</div>
<table class="table_basic" style="width: 100%;">
<tbody>
	<tr>
		<td>
		</td>
	</tr>
</tbody>
</table>
<div id="ftpUploadButton1" class="right pd5 show"><span class="btnPack medium icon"><span class="check"></span><a href="javascript:;" onclick="swfUpload(); return false;">파일 전송하기</a></span>&nbsp;<span class="btnPack black medium"><a href="javascript:;" onclick="$.dialogRemove();">취소</a></span>
</div>
<div id="ftpUploadButton2" class="right pd5 hide"><span class="btnPack medium icon"><span class="check"></span><a href="javascript:;" onclick="$.insert('#dirSub','./ftpData.php?dir=<?php echo($_GET['dir']);?>',null,300); $.dialogRemove();">전송 완료</a></span></div>
<?php
}
else if($_GET[type] == 'rename')
{
	foreach($_GET[choice] AS $key=>$val)
	{
		$oldName = $val;
	}
?>
<div id="messageBox"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/delete.gif" /></spna>&nbsp;<strong>Message :</strong>&nbsp;&nbsp;&nbsp;</div>
<form id="ftpFrm" name="ftpFrm" method="post" enctype="multipart/form-data"	onsubmit="return $.checkFarm(this,'./ftpProcess.php',400,120);">
<input type="hidden" name="type" value="renamePost" />
<input type="hidden" name="dir" value="<?php echo($_GET[dir]);?>" />
<input type="hidden" name="oldName" value="<?php echo($oldName);?>" />
<div class="menu_violet">
	<p style="text-align: left;">&nbsp;현재위치 : &nbsp;/<?php echo($_GET[dir]);?></p>
</div>
<table class="table_basic" style="width:100%;">
<col width="100">
<col>
<tbody>
	<tr>
		<th><p class="bold center">변경전 이름</p></th>
		<td><strong><?php echo($oldName);?></strong></td>
	</tr>
	<tr>
		<th><p class="bold center">변경할 이름</p></th>
		<td><input type="text" id="newName" name="newName" style="width:270px;" class="input_text required" value="<?php echo($oldName);?>" filename="true" minlength="1" maxlength="20" req="required" /></td>
	</tr>
</tbody>
</table>
<div class="right pd5"><span class="btnPack medium icon"><span class="check"></span><button type="submit">변경하기</button></span>&nbsp;<span class="btnPack black medium"><a href="javascript:;" onclick="$.dialogRemove();">취소</a></span></div>
</form>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$('#ftpFrm').validate({onkeyup:function(element){$(element).valid();}});
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
//]]>
</script>
<?php
}
?>
