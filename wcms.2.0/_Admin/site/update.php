<?php
	require "../../_config.php";
	require "../include/commonHeader.php";
?>
<h2><span class="arrow">▶</span>업데이트</h2>
<div class="tabMenu2">
	<ul class="tabBox">
		<li class="tab on" id="tab01" style="margin-left:0;"><p><a href="javascript:;" onclick="$.tabMenu('tab01','#tabBody01','./site/updateSql.php',null,200)" class="actgray" style="width:120px;">DB 일괄 업데이트</a></p></li>
	</ul>
	<div class="clear"></div>
	<div class="tabBody show" id="tabBody01"></div>
	<div class="tabBody hide" id="tabBody02"></div>
	<script type="text/javascript">
	//<![CDATA[
		$(document).ready(function()
		{
		$.insert('#tabBody01', './site/updateSql.php', null, 200);
		});
	//]]>
	</script>
</div>