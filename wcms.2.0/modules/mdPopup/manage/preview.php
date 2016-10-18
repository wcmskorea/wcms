<?php
require_once "../../../_config.php";

# 리퍼러 체크
if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER']))	{ $func->err("[경고!] 정상적인 접근이 아닙니다."); }

$Rows		= $db->queryFetch(" SELECT * FROM `mdPopup__content` WHERE seq='".$_GET[idx]."' ");
$URL		= ($Rows[url] && $Rows[url] != NULL) ? $Rows[url] : "#none";
$content	= stripslashes($Rows[content]);
$Size		= explode(",",$Rows[size]);
?>
<div style="width:<?php echo($Size[0]);?>px; height:<?php echo($Size[1]);?>px;">
	<div class="textContent"><?php echo($content);?></div>
</div>
