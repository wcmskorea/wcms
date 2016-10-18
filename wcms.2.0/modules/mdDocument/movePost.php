<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
@ob_end_clean();

//리퍼러 체크
$func->checkRefer("POST");

//파라메터 검사
if(count($_POST['select']) < 1) { $func->err($lang['doc']['notmust'], "window.history.back()"); }
if(!$_POST['cate']) { $func->err($lang['doc']['notmust'], "window.history.back()"); }
if(!$_POST['moveCate']) { $func->err($lang['doc']['notmust'], "window.history.back()"); }

foreach($_POST['select'] AS $key=>$value) 
{
	$db->query(" UPDATE `".$cfg['cate']['mode']."__content".$prefix."` SET cate='".$_POST['moveCate']."' WHERE seq='".$value."' AND cate='".$cfg['module']['cate']."' ");
	$db->query(" UPDATE `".$cfg['cate']['mode']."__comment".$prefix."` SET cate='".$_POST['moveCate']."' WHERE parent='".$value."' AND cate='".$cfg['module']['cate']."' ");
	$db->query(" UPDATE `".$cfg['cate']['mode']."__file".$prefix."` SET cate='".$_POST['moveCate']."' WHERE parent='".$value."' AND cate='".$cfg['module']['cate']."' ");
}

//문서 갯수 업데이트
$func->checkCount("move", count($_POST['select']), $cfg['module']['cate'], $_POST['moveCate']);
$func->setLog(__FILE__, "게시글 (".$cfg['module']['cate']."-".count($_POST['select'])."건)일괄 카테고리 이동 성공");
?>
<script type="text/javascript">
//<![CDATA[
	document.onload = location.replace('<?php echo($_SERVER['PHP_SELF']);?>?cate=<?php echo($_POST['moveCate']);?>&type=list&currentPage=<?php echo($currentPage);?>');
//]]>
</script>
