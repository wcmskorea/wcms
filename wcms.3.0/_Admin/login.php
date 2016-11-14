<?php
require_once "../_config.php";
#--- SSL설정
$posturl = ($cfg['site']['ssl']) ? 'https://'.$_SERVER['HTTP_HOST'].":".$cfg['site']['ssl'].$cfg['droot'].'/_Admin/loginAccess.php' : '/_Admin/loginAccess.php';
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
					<h1 class="show"><strong><?php echo($cfg['site']['siteName']);?></strong> 웹사이트 관리 시스템 ( WCMS <?php echo($cfg['version']);?> )</h1>
				</div>
			</div>
		</div>
		<div id="login_container">
			<form id="loginForm" class="logForm" name="log" method="post" action="<?php echo($posturl);?>" enctype="multipart/form-data" target="hdFrame">
			<fieldset class="darkgray">
			<div style="padding-left:20px;">Powered by </div>
			<table>
				<tr>
					<th rowspan="2" class="logo"><a href="http://www.aceoa.com/" target="_blank"><img src="<?php echo($cfg['droot']);?>user/default/image/icon/logo.jpg" alt="" /></a></th>
					<td style="padding-left:20px;"><input type="text" id="uid" name="uid" class="input_text required userid" maxlength="15" style="width:100px;ime-mode:disabled" value="<?php echo($_COOKIE['uauto']);?>" accesskey="L" tabindex="1" /></td>
					<td style="text-align:center;padding-left:3px"><p class="keeping"><input type="checkbox" id="autoid" name="autoid" value="Y" tabindex="3" <?php if($_COOKIE['uauto']){print('checked="checked"');}?> class="input_check" /><label for="autoid">ID저장</label></p></td>
				</tr>
				<tr>
					<td style="padding-left:20px;"><input type="password" id="upw" name="upw" class="input_text required userpw" maxlength="15" style="width:100px;" accesskey="P" tabindex="2" onkeypress="if(event.keyCode==13){return $.submit(this.form);}" /></td>
					<td style="padding-left:5px;"><span class="btnPack black small strong"><button type="submit" tabindex="4">로 그 인</button></span></td>
				</tr>
			</table>
			</fieldset>
			</form>
		</div>
		<div id="login_footer" class="bg_gray">
			<ul>
				<li>이용권한을 받으신분에 한하여 이용이 제한되오니, 관계자외 접근을 금합니다.</li>
				<!--<li>계정분실 및 기타문의는 아래 고객센터로 문의주십시오.</li>
				<li class="colorBlue">유지보수센터 : T) 062-374-4242~4, F) 062-374-4249</li>-->
        		<li>홈페이지 바로가기 → http://<a href="/" class="actred" title="홈페이지 가기"><?php echo(strtoupper(__HOST__));?></a></li>
        		<li>관리 시스템 : <a href="#none" onclick="window.external.addfavorite('http://<?php echo(__HOST__);?>/_Admin/', '<?php echo($cfg['site']['siteName']);?> 홈페이지 관리시스템 (WCMS)');"><strong>즐겨찾기 등록하기</strong></a></li>
			</ul>
		</div><!-- footer -->
	</div><!-- wrap -->
</div><!-- layout -->
<div><iframe id="hdFrame" name="hdFrame" style="width:100%;height:100px;"></iframe></div>
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
