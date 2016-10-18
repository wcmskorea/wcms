<?php
	require_once "./_header.php";
?>
<div id="container" style="margin-bottom:45px;">
	<div id="snb" class="snb">
	<?php
		require_once "./_left.php";
	?>
	</div>
	<div id="module" style="width:100%; min-width:1000px">
	    <h2><span class="arrow">▶</span>대시보드</h2>
	    <div class="section">

			<!-- 최근데이터 TAB : start -->
			<div class="tabMenu2" style="margin-top:5px;">
				<ul class="tabBox">
					<li class="tab on" id="tab01" style="margin-left:0;"><p><a href="javascript:;" onclick="$.tabMenu('tab01','#tabBody01','./include/recentBoard.php',null,200);" class="actgray" style="width:100px;">최근 문서</a></p></li>
					<li class="tab" id="tab02"><p><a href="javascript:;" onclick="$.tabMenu('tab02','#tabBody02','./include/recentReply.php',null,200);" class="actgray" style="width:100px;">최근 댓글</a></p></li>
					<?php if($func->checkModule('mdMember')) { ?>
					<li class="tab" id="tab03"><p><a href="javascript:;" onclick="$.tabMenu('tab03','#tabBody03','./include/recentMember.php',null,200);" class="actgray" style="width:100px;">최근 접속회원</a></p></li>
					<?php } ?>
					<li class="tab" id="tab04"><p><a href="javascript:;" onclick="$.tabMenu('tab04','#tabBody04','./include/recentKeyword.php',null,200);" class="actgray" style="width:100px;">키워드 TOP10</a></p></li>
				</ul>
				<div class="tabBody show" id="tabBody01" style="height:200px;"></div>
				<div class="tabBody hide" id="tabBody02" style="height:200px;"></div>
				<?php if($func->checkModule('mdMember')) { ?>
				<div class="tabBody hide" id="tabBody03" style="height:200px;"></div>
				<?php } ?>
				<div class="tabBody hide" id="tabBody04" style="height:200px;"></div>
			</div>
			<!-- 최근데이터 TAB : end -->

			<!-- 최신정보 -->
			<div class="table" style="margin-top:5px;"><div class="line">
				<div id="todays"></div>
			</div></div>
			
			<!-- 접속자 통계 그래프 -->
			<!--<div class="table" style="margin-top:5px;"><div class="line">
				<script type="text/javascript" src="/addon/chart/js/swfobject.js"></script>
				<script type="text/javascript">
					swfobject.embedSWF("/addon/chart/open-flash-chart.swf", "my_chart","100%", "200", "9.0.0", "expressInstall.swf", {"data-file":"include/visitStatistics.php"}, {"wmode":"transparent"} );
				</script>
			<div id="my_chart"></div>
		</div></div>-->
		</div>
		<script type="text/javascript">
		//<![CDATA[
		$(document).ready(function()
		{
			$.insert('#tabBody01','./include/recentBoard.php',null,200);
			$.insert('#service','./include/service.php',null,56);
			$.insert('#lastest','./include/lastest.php');
		});
		//]]>
		</script>
	</div><!--module : end-->
	<div class="clear"></div>
</div><!--container : end-->
<div id="navigator">
	<div id="lastest" class="line"></div>
</div>
<?php
	require_once "./_footer.php";
?>
