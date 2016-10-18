<?php
require $_SERVER['DOCUMENT_ROOT']."/_Admin/include/commonHeader.php";
?>
<div class="menu_violet"><p><strong><?php echo($name);?> (배너·이미지 모듈) 환경설정</strong></p></div>
<div class="pd3">
<div class="tabMenu2">
	<ul class="tabBox">
		<li class="tab on" id="tabBanner01"><p><a href="#none" onclick="$.tabMenu('tabBanner01','#tabBodyBanner01','../modules/mdBanner/manage/_configBasic.php?cate=<?php echo(__CATE__);?>',null,200)" class="actgray">환경설정</a></p></li>
		<li class="tab" id="tabBanner02"><p><a href="#none" onclick="$.tabMenu('tabBanner02','#tabBodyBanner02','../modules/mdBanner/manage/_configForm.php?cate=<?php echo(__CATE__);?>',null,200)" class="actgray">입력항목 설정</a></p></li>
	</ul>
	<div class="clear"></div>
	<div class="tabBody show" id="tabBodyBanner01"></div>
	<div class="tabBody hide" id="tabBodyBanner02"></div>
<script type="text/javascript">
//<![CDATA[
	$(document).ready(function(){$.insert('#tabBodyBanner01','../modules/mdBanner/manage/_configBasic.php?cate=<?php echo(__CATE__);?>',null,200);});
//]]>
</script>
</div>
</div>
