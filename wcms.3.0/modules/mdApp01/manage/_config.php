<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
$name = $db->queryFetchOne(" SELECT name FROM `site__` WHERE cate='".__CATE__."' AND skin='".$_GET['skin']."' ");
?>
<div class="menu_violet">
<p><strong><?php echo($name);?> (상담·문의 모듈) 환경설정</strong></p>
</div>
<div class="pd3">
<div class="tabMenu3">
	<ul class="tabBox">
		<li class="tab" id="tabApp0102"><p><a href="#none" onclick="$.tabMenu('tabApp0102','#tabBodyApp0102','../modules/mdApp01/manage/_configForm.php?cate=<?php echo(__CATE__);?>&amp;skin=<?php echo($_GET['skin']);?>',null,200)" class="actgray">입력항목 설정</a></p></li>
		<li class="tab on" id="tabApp0101"><p><a href="#none" onclick="$.tabMenu('tabApp0101','#tabBodyApp0101','../modules/mdApp01/manage/_configBasic.php?cate=<?php echo(__CATE__);?>&amp;skin=<?php echo($_GET['skin']);?>',null,200)" class="actgray">환경설정</a></p></li>
	</ul>
	<div class="clear"></div>
	<div class="tabBody show" id="tabBodyApp0101"></div>
	<div class="tabBody hide" id="tabBodyApp0102"></div>
<script type="text/javascript">
//<![CDATA[
	$(document).ready(function(){$.insert('#tabBodyApp0101','../modules/mdApp01/manage/_configBasic.php?cate=<?php echo(__CATE__);?>&skin=<?php echo($_GET['skin']);?>',null,200);});
//]]>
</script>
</div>
</div>
