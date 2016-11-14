<div id="layout" style="background:url(<?php echo(__SKIN__);?>image/background/bg_main_layout.jpg) repeat-x;">
	<div id="wrap">
		<div id="header">
			<div class="gnb">
				<?php
				//글로벌 네비게이션
				if($cfg['site']['navGnb'] == "Y") { include __HOME__."global.php"; }
				?>
			</div>
			<div class="lnb">
				<?php
				//디스플레이 => 메인(상)
				$display->setPrint("MT");
				?>
			</div>
		</div>
		<hr />
		<h2>본문 및 주요 콘텐츠</h2>
		<div id="container" style="padding-left:<?php echo($cfg['site']['sizeMsnb']);?>px; padding-right:<?php echo($cfg['site']['sizeMside']);?>px; background:url(<?php echo(__SKIN__);?>image/background/bg_main_container.jpg) repeat-y;">
			<div class="snb" style="left:-<?php echo($cfg['site']['sizeMsnb']);?>px; margin-right:-<?php echo($cfg['site']['sizeMsnb']);?>px; width:<?php echo($cfg['site']['sizeMsnb']);?>px;">
				<?php
				//디스플레이 => 메인(좌)
				if($cfg['site']['sizeMsnb'] > 0) { $display->setPrint("ML"); }
		    	?>
			</div>
			<!-- .snb : end -->
			<div id="content">
				<?php
				//디스플레이 => 메인(중)
				$display->setPrint("MC");
				?>
			</div>
			<!-- #content : end -->
			<div class="side" style="left:<?php echo($cfg['site']['sizeMside']);?>px; margin-left:-<?php echo($cfg['site']['sizeMside']);?>px; width:<?php echo($cfg['site']['sizeMside']);?>px;">
				<?php
				//디스플레이 => 메인(우)
				if($cfg['site']['sizeMside'] > 0) { $display->setPrint("MR"); }
				?>
		    </div>
		    <!-- .side : end -->
			<div class="clear"></div>
		</div>
		<!-- #container : end -->
		<div id="bottom">
			<?php
			//디스플레이 => 메인(하)
			$display->setPrint("MB");
			?>
		</div>
		<hr />
		<h2>하단메뉴 및 주소,연락처 안내</h2>
		<div id="footer" style="background:url(<?php echo(__SKIN__);?>image/background/bg_footer.jpg) repeat-x;">
			<?php
			//디스플레이 => 메인(풋)
			$display->setPrint("MF");
			?>
		</div>
		<?php
		//디스플레이 => 메인(퀵)
		if($cfg['site']['navQnb'] == 'Y') { include __HOME__."quickMain.php"; }
		?>
	</div>
	<!-- wrap : end -->
</div>
<!-- layout : end -->
