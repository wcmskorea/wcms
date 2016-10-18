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
		$up 			= new upLoad($upload['dir'], $_FILES);			//썸네일 크기 ['0']=>소, ['1']=>중, ['2']=>대, ['3']['4']=>비율
		$up->insertFile	= "Y";
		$insert 		= $up->upFiles();
		$fileName		= $up->fileRename."/".$up->fileRealName;	//파일이름/원본파일이름
	} else
	{
		$func->err("업로드할 파일이 없습니다.","window.self.close()");
	}

} else if($sess->decode($_POST['type']) == 'editorMediaPost')
{
	$up->fileExt = array_reverse(explode(".", strtolower($_POST['murl'])));
} else
{
	$func->err("정상적인 접근이 아닙니다.", "window.self.close()");
}

$func->showArray($up->basicSize);
exit;
$width= ($up->basicSize['0'] > 600) ? "100%" : $up->basicSize['0'];
?>

