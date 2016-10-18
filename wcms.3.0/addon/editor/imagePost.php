<?php
#------------------------------------------------------------------------------
# 작업내용: 에디터 파일 업로드
# 작성일자: 2007-07-02
#------------------------------------------------------------------------------
require_once "../../_config.php";
if(!preg_match("/".$_SERVER['HTTP_HOST']."/", $_SERVER['HTTP_REFERER']) ) { $func->err("(경고)정상적인 접근이 아닙니다.", "window.history.back()"); }
if($_SERVER['REQUEST_METHOD'] == 'GET' ) { $func->err("(경고)정상적인 접근이 아닙니다.", "window.history.back()"); }

/* 카테고리 코드 */
$skin_dir = $db->queryFetchOne(" SELECT skin FROM `site__` WHERE cate='".__CATE__."' ");
$skin_dir = (!$skin_dir) ? "default" : $skin_dir;

if($sess->decode($_POST['type']) == 'editorFilePost')
{
	if(is_uploaded_file($_FILES['upfile']['tmp_name']))
	{
		require			__PATH__."_Lib/classUpLoad.php";
		$up 			= new upLoad($cfg['upload']['dir'], $_FILES);
		$up->contentWidth = $cfg['site']['size'] > 100 ? ($cfg['site']['size'] - $cfg['site']['sizeSsnb'] - $cfg['site']['sizeSside']) : 600;
		$up->insertFile	= "Y";
		$up->resizeOriginImageHeight = 0;

		$insert 		= $up->upFiles("editor_".time().str_replace(" ", null, microtime()));
   	$fileName		= $up->fileRename."/".$up->fileRealName; //파일이름/원본파일이름
	} 
	else
	{
		$func->err("업로드할 파일이 없습니다.","window.history.back()");
	}

} 
else if($sess->decode($_POST['type']) == 'editorMediaPost')
{
	$up->fileExt = array_reverse(explode(".", strtolower($_POST['murl'])));
} 
else
{
	$func->err("정상적인 접근이 아닙니다.", "window.self.close()");
}

$width= ($up->basicSize['0'] > 600) ? $_POST['width'] : $up->basicSize['0'];
?>
<!DOCTYPE html>
<html>
<head>
<title>그림 넣기</title>
<meta http-equiv="content-type" content="text/html; charset=<?php echo($cfg['charset']);?>" />
<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/jquery.js"></script>
<script src="./js/popup.js" type="text/javascript" charset="euc-kr"></script>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function()
{
	var filename = opener.document.bbsform.fileName.value.replace('|:|<?php echo($fileName);?>', '');
	opener.document.bbsform.fileName.value = filename + "|:|<?php echo($fileName);?>";
	opener.Editor.getCanvas().pasteContent('<?php echo($up->insert);?>');
	closeWindow();
});
//]]>
</script>
</head>
</html>
