<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
@ob_end_clean();

$func->err($cfg['cate']['mode']);

//리퍼러 체크
$func->checkRefer("POST");

//파라메터 검사
if(count($_POST['choice']) < 1) { $func->err($lang['doc']['notmust'], "window.history.back()"); }
if(!$_POST['cate']) { $func->err($lang['doc']['notmust'], "window.history.back()"); }

//해당정보 삭제

foreach($_POST['choice'] AS $key=>$value) 
{
	//연관 첨부파일 삭제
	$db->query(" SELECT * FROM `mdApp01__file` WHERE parent='".$value."' ORDER BY regDate ASC ");
	while($sRows = $db->fetch()) {
		@unlink($cfg['upload']['dir'].date("Y",$sRows['regDate'])."/".date("m",$sRows['regDate'])."/".$sRows['fileName']);
	}

	$db->query(" DELETE FROM `".$cfg['cate']['mode']."__file` WHERE parent='".$value."' ");
	$db->query(" DELETE FROM `".$cfg['cate']['mode']."__content` WHERE seq='".$value."' ");

}

$db->query(" OPTIMIZE TABLES `".$cfg['cate']['mode']."__content` ");

$func->err("정상적으로 삭제 되었습니다.", "
parent.$.dialogRemove(); 
parent.$.insert('#module', '../modules/mdApp01/manage/_controll.php', '&type=list&cate=".$_POST['cate']."&state=".$_POST['state']."',300); 
parent.$.insert('#left_mdApp01','../modules/mdApp01/manage/_left.php');
");
?>
