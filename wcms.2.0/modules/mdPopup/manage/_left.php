<?php
require_once "../../../_config.php";
if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER[HTTP_REFERER])) { $func->err("[경고!] 정상적인 접근이 아닙니다."); }
?>
<ul>
	<li class="menu">ㆍ<a href="javascript:$.insert('#module', '../modules/mdPopup/manage/_controll.php?type=list',null,300)">팝업목록 보기</a></li>
	<li class="sect"></li>
	<li class="menu"><strong>ㆍ<a href="#none" onclick="new_window('../modules/mdPopup/manage/insert.php','mdPopup','1024','700','no','yes');">팝업 등록</a></strong></li>
	<?php
	if(is_file('./manual.pdf')) { print('<li class="sect"></li><li class="menu"><a href="'.$cfg[droot].'modules/mdPopup/manage/manual.pdf"><img src="'.$cfg[droot].'image/button/btn_manual_module.jpg" width="162" height="21" title="매뉴얼 다운받기" /></a></li>'); }
	?>
</ul>

