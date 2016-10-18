<?php
/**
 * Author : Sung-Jun, Lee (http://www.aceoa.com)
 * Lastest : 2011. 1. 31.
 */
require_once "../../_config.php";

if((preg_match("/\_Admin/", $_SERVER['REQUEST_URI']) || preg_match("/admin/", $_SERVER['REQUEST_URI'])) && ($_SESSION['ulevel'] > $cfg['operator'] || !$_SESSION['ulevel']))
{
	Header("Location: /_Admin/login.php");
	die();
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<title><?php echo($cfg['site']['siteName']);?> :: [WCMS-<?php echo(strtoupper($cfg['site']['lang']));?>]</title>
	<meta http-equiv="content-type"	content="application/xhtml+xml; charset=<?php echo($cfg['charset']);?>" />
	<meta http-equiv="content-style-type" content="text/css" />
	<link rel="stylesheet" href="<?php echo($cfg['droot']);?>common/css/admin.css" type="text/css" charset="<?php echo($cfg['charset']);?>" media="all" />
	<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/common.js"></script>
	<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/ajax.js"></script>
</head>
<?php
if($_POST['type'] == 'styleContentPost')
{
	/**
	 * 리퍼러 체크
	 */
	$func->checkRefer("POST");

	$path = __PATH__."_Site/".__DB__."/".$_POST['dir']."/".$_POST['cached'];

	$content = str_replace('http://'.__HOST__.'/', $cfg['droot'], trim($_POST['content']));
	$content = str_replace('/source/', '/'.__DB__.'/', $content);
	$content = str_replace('/_Site/'.__DB__.'/', '/user/', $content);
	//$content = str_replace('/default/', '/'.$_POST['skin'].'/', $content);
	$content = (preg_match('/xml/i', $path)) ? iconv("UTF-8//IGNORE", "CP949", $content) : $content;

	$fp = fopen($path, 'w');
	fwrite($fp, stripslashes($content));
	fclose($fp);

	$func->err("성공적으로 저장되었습니다.", "back");

} else
{
	$path = __PATH__."_Site/".__DB__."/".$_GET['dir']."/".$_GET['cached'];
	//카테고리 모듈
	if(!is_file($path))
	{
		$func->err("파일(".$path.")이 존재하지 않습니다.","window.self.close()");
	} else
	{
		$Rows['content'] = (preg_match('/xml/i', $path)) ? stripslashes(iconv("CP949", "UTF-8//IGNORE", file_get_contents($path))) : stripslashes(file_get_contents($path));
		//$Rows['content'] = stripslashes(file_get_contents($path));
	}
}
?>
<body style="background:url();">
<div id="content">
<form id="bbsform" name="bbsform" method="post" action="<?php echo($_SERVER['PHP_SELF']);?>" enctype="multipart/form-data">
<input type="hidden" name="type" value="styleContentPost" />
<input type="hidden" name="dir" value="<?php echo($_GET['dir']);?>" />
<input type="hidden" name="cached" value="<?php echo($_GET['cached']);?>" />
<input type="hidden" id="mode" name="mode" value="text" />

<table class="table_basic" style="width: 100%;">
	<col width="100">
	<col>
	<tbody>
		<tr>
			<th>자동 업데이트</th>
			<td><input name="update" type="radio" id="update1" class="input_radio" value="Y" checked="checked" /><label for="update1">반영함</label>
			<input name="update" type="radio" id="update2" class="input_radio" value="N" /><label for="update2">반영안함</label>
			</td>
		</tr>
		<tr>
			<th>파일위치</th>
			<td><?php echo("/".$_GET['dir']."/".$_GET['cached']);?></td>
		</tr>
	</tbody>
</table>

<div class="pd5 right"><span class="btnPack violet medium strong"><button type="submit" onclick="submitForm(this.form);">적용하기</button></span></div>

<!-- 텍스트모드 : start -->
<div id="editor01" style="display: block;">
	<div class="line"><textarea id="content" name="content"	title="텍스트모드" wrap="off" style="background-color:#f4f4f4; border:0px solid #000; width:100%; height:470px; font-size:13px; font-style:normal; font-family:Courier New; line-height:1.5; white-space:2px;"><?php echo($Rows['content']);?></textarea></div>
</div>
<!-- 텍스트모드 : end -->

<div class="pd5 right"><span class="btnPack violet medium strong"><button type="submit" onclick="submitForm(this.form);">적용하기</button></span></div>
</form>
</div>
<script type="text/javascript">
//<[!CDATA[
$(document).ready(function()
{
	$("textarea").resizehandle();
	$("textarea").css({"height":screen.availHeight-250});
});

//]]>
</script>
<script language="Javascript">
   self.moveTo(0,0);
	 self.resizeTo(screen.availWidth,screen.availHeight);
   function x(){window.status=''}
   function y(){self.focus()}
</script>
</body>
</html>

