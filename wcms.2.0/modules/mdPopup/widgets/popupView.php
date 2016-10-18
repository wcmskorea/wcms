<?php
require_once "../../../_config.php";

# 리퍼러 체크
if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER']))	{ $func->err("[경고!] 정상적인 접근이 아닙니다."); }

$Rows		= $db->queryFetch(" SELECT * FROM `mdPopup__content` WHERE seq='".$_GET[idx]."' ");
$url		= ($Rows[url]) ? $Rows[url] : null;
$content	= stripslashes($Rows[content]);
$size		= explode(",",$Rows[size]);

if($url) {
	echo('<div style="width:'.$size[0].'px; height:'.$size[1].'px; cursor:pointer;_cursor:hand;" onclick="go_url(\'http://'.$url.'\',\''.$Rows[target].'\');" class="textContent">');
} else {
	echo('<div style="width:'.$size[0].'px; height:'.$size[1].'px; cursor:pointer;_cursor:hand;" onclick="$.dialogRemove();" class="textContent">');
}
?>
<div class="textContent"><?php echo($content);?></div>
</div>
