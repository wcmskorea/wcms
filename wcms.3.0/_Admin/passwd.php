<?php
require_once "../_config.php";
#--- SSL설정
$posturl = ($cfg['site']['ssl']) ? 'https://'.$_SERVER['HTTP_HOST'].":".$cfg['site']['ssl'].$cfg['droot'].'/_Admin/passwdPost.php' : '/_Admin/passwdPost.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo($cfg['site']['siteName']);?> :: <?php echo($cfg['site']['siteTitle']);?></title>
	<meta http-equiv="content-type" content="application/xhtml+xml; charset=<?php echo($cfg['charset']);?>" />
	<meta http-equiv="content-style-type" content="text/css" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="content-language" content="kr" />
	<meta name="robots" content="noindex,nofollow,noarchive" />
	<meta name="publisher" content="10억홈피닷컴- WCMS(web content management system)" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<link rel="shortcut icon" href="<?php echo($cfg['droot']);?>common/image/favicon.ico" type="image/x-ico" />
	<style type="text/css">
		@import url("<?php echo($cfg['droot']);?>common/css/admin.css");
		body {background:url()}
	</style>
	<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/common.js"></script>
	<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/ajax.js"></script>
    <script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/jquery.validate.js"></script>
</head>
<body>
<div id="messageBox"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/delete.gif" /></spna>&nbsp;</div>
<div id="login_layout" class="back_gray">
	<div id="login_wrap">
		<div id="login_header">
			<div class="line_2_gray">
				<div class="head_violet">
					<h1 class="show"><strong><?php echo($cfg['site']['siteName']);?></strong> 웹사이트 관리 시스템 ( WCMS v<?php echo($cfg['version']);?> )</h1>
				</div>
			</div>
		</div>
		<div id="login_container">
			<form id="loginForm" class="logForm" name="log" method="post" action="<?php echo($posturl);?>" enctype="multipart/form-data" target="hdFrame">
			<fieldset class="darkgray">
			
			<table>
				<tr>
					<th rowspan="2">비밀번호를 변경하세요</th>
					<td style="padding-left:20px;"><input type="password" id="uid" name="uid" class="input_text required userpw" maxlength="15" style="width:100px;ime-mode:disabled" accesskey="L" tabindex="1" /></td>
					<td style="padding-left:5px">변경할 비밀번호</td>
				</tr>
				<tr>
					<td style="padding-left:20px;"><input type="password" id="upw" name="upw" class="input_text required userpw" maxlength="15" style="width:100px;" accesskey="P" tabindex="2" onkeypress="if(event.keyCode==13){return $.submit(this.form);}" /></td>
					<td style="padding-left:5px;">변경할 비밀번호 확인</td>
				</tr>
				<tr>
					<td></td>
					<td colspan="2" style="padding: 5px; text-align:right"><span class="btnPack red small strong"><button type="submit" tabindex="4">변경하기</button></span> <span class="btnPack blue small strong"><a href="/_Admin/">다음에 변경하기</a></span></td>
				</tr>
			</table>
			</fieldset>
			</form>
		</div>
		<div id="login_footer" class="bg_gray">
			<ul>
				<li>비밀번호 변경기간이 만기(6개월)되어 변경하셔야 합니다</li>
			</ul>
		</div><!-- footer -->
	</div><!-- wrap -->
</div><!-- layout -->
<div><iframe id="hdFrame" name="hdFrame" style="width:100%;height:0px;"></iframe></div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function()
{
	$('#login_container').slideDown("slow");
	$("input[type=text],input[type=password],textarea").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active"); });
	//$('#uid').focus().toggleClass("input_active").select();
    $('#loginForm').validate({onkeyup:function(element){$(element).valid();}});
    $("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
//]]>
</script>
</body>
</html>
