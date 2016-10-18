<?php
/*---------------------------------------------------------------------------------------
 | Ajax를 통한 알림 메세지창
 |----------------------------------------------------------------------------------------
 | Lastest (2008.10.22 : 이성준)
 */

require_once "./_config.php";
if(!defined(__CSS__)) { header("HTTP/1.0 404 Not found"); die(); }
usleep($cfg[sleep]);
$closeTime = ($_GET[time]) ? $_GEt[time]*1000 : 5000;
?>
<div id="modal">
<div class="menu_violet">
	<p style="text-align: left;">알림!</p>
</div>
<div class="pd10 center" style="line-height: 150%;"><?=iconv("UTF-8", "CP949", trim($_GET[msg]))?></div>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function() {
	setTimeout("$.dialogRemove();", <?=$closeTime?>);}
);
//]]>
</script>
