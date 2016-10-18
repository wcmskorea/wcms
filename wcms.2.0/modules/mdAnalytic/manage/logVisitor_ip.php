<?php
require_once __PATH__."_Admin/include/commonHeader.php";
?>
<h2><span class="arrow">▶</span>IP별 접속자 내역</span></h2>
<div class="tabMenu2">
	<ul class="tabBox">
		<li class="tab on" id="tab01" style="margin-left:0;"><p><a href="javascript:;" onclick="$.tabMenu('tab01','#tabBody01','../modules/mdAnalytic/manage/logVisitor_track.php','',200)" class="actgray" style="width:160px;">가망 방문자</a></p></li>
		<li class="tab" id="tab02"><p><a href="javascript:;" onclick="$.tabMenu('tab02','#tabBody02','../modules/mdAnalytic/manage/logVisitor_trackBlack.php','',200)" class="actgray" style="width:160px;">불량 방문자</a></p></li>
	</ul>
	<div class="tabBody show" id="tabBody01"></div>
	<div class="tabBody hide" id="tabBody02"></div>
<script type="text/javascript">
//<![CDATA[
	$(document).ready(function(){$.insert('#tabBody01','../modules/mdAnalytic/manage/logVisitor_track.php','',200);});
//]]>
</script>
</div>
