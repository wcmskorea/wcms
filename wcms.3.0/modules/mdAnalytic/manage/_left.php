<?php
require_once "../../../_config.php";
require_once __PATH__."/_Admin/include/commonHeader.php";
?>
<hr />
<ul>
	<li class="menu small_gray"><p class="center">&lt;&nbsp;현재 나의IP&nbsp;:&nbsp;<?php echo($_SERVER['REMOTE_ADDR']);?>&nbsp;&gt;</p></li>
	<li class="sect"></li>
	<li class="menu"><a href="javascript:;" onclick="$.insert('#module','../modules/mdAnalytic/manage/_controll.php?type=logVisitorDay',null,'300')">ㆍ날짜별 통계</a></li>
	<li class="menu"><a href="javascript:;" onclick="$.insert('#module','../modules/mdAnalytic/manage/_controll.php?type=logVisitorIp',null,'300')">ㆍ유입 IP별 통계</a></li>
	<li class="menu"><a href="javascript:;" onclick="$.insert('#module','../modules/mdAnalytic/manage/_controll.php?type=logVisitorRefer',null,'300')">ㆍ유입 사이트별 통계</a></li>
	<!--<li class="menu"><a href="javascript:;" onclick="$.insert('#module','../modules/mdAnalytic/manage/_controll.php?type=logVisitors',null,'300')">ㆍIP별 접속횟수</a></li>-->
	<li class="sect"></li>
	<li class="menu"><a href="javascript:;" onclick="$.insert('#module','../modules/mdAnalytic/manage/_controll.php?type=logKeyword',null,'300')">ㆍ키워드 통계</a></li>
</ul>
