<?php
require_once "../../_config.php";
if(!$_GET['host']) { $func->err("경고!! 잘못된 경로로 접근하였습니다.", "window.self.close()"); }
?>
<!DOCTYPE html>
<html>
<head>
<title>다른 사이트로 이동하기</title>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />
</head>
<body>
<form name="logChange" method="post" action="/_Admin/loginAccess.php" enctype="multipart/form-data">
<input type="hidden" name="uid" value="master" />
<input type="hidden" name="upw" value="wcms2580" />
</form>
<script type="text/javascript">
<!--
if(confirm("[<?php echo($_GET['host']);?>]사이트로 이동하시겠습니까?") == true)
{
	document.logChange.submit();
} else {
	document.top.location.replace('/_Admin/index.php');
}
//-->
</script>
</body></html>