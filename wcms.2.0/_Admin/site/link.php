<?php
require_once "../../_config.php";
require_once "../include/commonHeader.php";
?>
<form id="frm01" name="frm01" method="get" enctype="multipart/form-data" onsubmit="return $.checkFarm(this, '<?php echo($_SERVER['PHP_SELF']);?>' ,'insert', '#module');">
<div class="pd5"><span>URL</span> : <input type="text" id="urlLink" name="link" class="input_white" style="width:300px;" value="<?php echo($_GET['link']);?>" />&nbsp;<span class="btnPack gray small"><button type="submit">검색</button></span>
<span class="btnPack white small"><a href="javascript:$.insert('#module','./site/link.php?link=http://www.naver.com',null,'300');">네이버</a></span>
<span class="btnPack white small"><a href="javascript:$.insert('#module','./site/link.php?link=http://www.daum.net',null,'300');">다음</a></span>
<span class="btnPack white small"><a href="javascript:$.insert('#module','./site/link.php?link=http://www.google.co.kr',null,'300');">구글</a></span>
<span class="btnPack white small"><a href="javascript:$.insert('#module','./site/link.php?link=http://kr.yahoo.com',null,'300');">야후</a></span>
</div></form>
<div><iframe id="extraLink" name="extraLink" wrap="off" frameborder="0" marginwidth="0" marginheight="0" style="width:100%;height:500px;border:2px solid #999;" src="<?php echo($_GET['link']);?>"></iframe></div>
<script type="text/javascript">
//<[!CDATA[
$(document).ready(function() 
{
	$("#extraLink").height($(window).height()-140);
  	$("#urlLink").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active");}).select();
});
//]]>
</script>
