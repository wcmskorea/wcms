<?php
require_once "../../_config.php";
$func->setLog(__FILE__, "WEB-FTP (".$_FILES[Filedata][name].")업로드 성공");
//if($cfg['charset'] == 'euc-kr') { $_FILES['Filedata']['name'] = iconv("UTF-8//IGNORE","CP949",$_FILES['Filedata']['name']); }
$droot	= __PATH__."_Site/".__DB__."/";
@move_uploaded_file($_FILES['Filedata']['tmp_name'], $droot.$_GET['upload_id']."/".$_FILES['Filedata']['name']);
@unlink($_FILES['Filedata']['tmp_name']);
?>
<script>
alert('업로드 실패');
</script>