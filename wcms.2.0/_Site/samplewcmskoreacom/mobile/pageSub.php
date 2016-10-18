<div id="layout" style="background:url(<?php echo(__SKIN__);?>image/background/bg_sub_layout.jpg) repeat-x;">
	<div id="wrap">
		<div id="header">
			<div class="gnb">
				<?php
				//글로벌 네비게이션
				if($cfg['site']['navGnb'] == "Y") { require __HOME__."global.php"; }
				?>
			</div>
			<div class="lnb">
				<?php
				//디스플레이 => 서브(상)
				$display->setPrint("ST");
				?>
			</div>
		</div>
		<hr />
		<h2>본문 및 주요 콘텐츠</h2>
		<div id="container" style="padding-left:<?php echo($cfg['site']['sizeSsnb']);?>px; padding-right:<?php echo($cfg['site']['sizeSside']);?>px; background:url(<?php echo(__SKIN__);?>image/background/bg_sub_container.jpg) no-repeat;">
			<div class="snb" style="left:-<?php echo($cfg['site']['sizeSsnb']);?>px; margin-right:-<?php echo($cfg['site']['sizeSsnb']);?>px; width:<?php echo($cfg['site']['sizeSsnb']);?>px;">
				<?php
				//디스플레이 => 서브(좌)
				if($cfg['site']['sizeSsnb'] > 0) { $display->setPrint("SL"); }
				?>
			</div>
			<div id="content">

				<div id="moduleTop">
					<?php
					//디스플레이 => 서브(중)
					$display->setPrint("SC");
					?>
				</div>

				<!-- 서브 타이틀 : Start -->
				<div id="moduleTitle">
					<?php
						//$display->config['useTitle'] = 'image';
						echo($display->printTitle("sub", $cfg['cate']['name'], __CATE__));
					?>
					<div class="subMap"><?php echo($category->printHistory());?></div>
				</div>
				<!-- 서브 타이틀 : End -->

				<!-- 모듈 : Start -->
				<div id="module" class="module" style="padding:0 0 0 0;">
					<?php
					//서브카테고리 노출
					if($cfg['cate']['subCategory'] > 0) { print('<h3 class="hide">서브 카테고리</h3><div class="subCategory" style="padding:25px 0 0 10px;">'.$category->printDisplay($cfg['cate']['subCategory']).'</div><div class="subCategoryBottom"></div><br />'); }
					?>
				<!-- contents : Start--><?php echo($buffer);?><!-- contents : End-->
				</div>
				<!-- 모듈 : End -->

				<div id="moduleBottom">
					<?php
					//디스플레이 => 서브(하)
					$display->setPrint("SB");
					?>
				</div>

			</div>
			<div class="side" style="left:<?php echo($cfg['site']['sizeSside']);?>px; margin-left:-<?php echo($cfg['site']['sizeSside']);?>px; width:<?php echo($cfg['site']['sizeSside']);?>px;">
				<?php
				//디스플레이 => 서브(우)
				if($cfg['site']['sizeSside'] > 0) { $display->setPrint("SR"); }
				?>
		    </div>
		    <div class="clear"></div>
		</div>
		<hr />
		<h2>하단메뉴 및 주소,연락처 안내</h2>
		<div id="footer" style="background:url(<?php echo(__SKIN__);?>image/background/bg_footer.jpg) repeat-x;">
			<?php
			//디스플레이 => 메인(풋)
			$display->setPrint("SF");
			?>
		</div>
		<?php
		//디스플레이 => 서브(퀵) : Main과 동일하게 사용할경우 quickSub.php를 quickMain.php로 변경
		if($cfg['site']['navQnb'] == 'Y') { include __HOME__."quickSub.php"; }
		?>
        <?php
        //프로토타입 리스트 출력
        $display->loadPrototype();
        ?>
	</div>
	<!-- wrap : end -->
</div>
<!-- layout : end -->
