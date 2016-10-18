<hr />
<div id="service" class="widget line"></div>
<hr />
<!--<script language="javascript">flashWrite("/common/flash/time.swf", '200', '79', "time", "luckyTime=20110310105959&currentTime=20110310100400");</script>-->
<div class="tabMenu">
	<ul class="tabBox">
		<li class="tab on" id="tabModule" style="margin-left:0;"><p style="width:86px;"><a href="javascript:;" onclick="$.tabPage('tabModule')" class="actgray">모듈별 관리</a></p></li>
		<li class="tab" id="tabExpert"><p style="width:86px;"><a href="javascript:;" onclick="$.tabPage('tabExpert')" class="actgray">디자인 관리</a></p></li>
	</ul>
	<div class="clear"></div>
	<div class="tabBody show" id="tabBodyModule">
		<?php

		//설치된 모듈별 메뉴
		$n = 0;
		foreach ($cfg['modules'] as $val)
		{
			if($val && is_file(__PATH__.'modules/'.$val.'/manage/_left.php'))
			{
				echo('<div class="box"><div class="line">');
				echo('<h3 class="bg_gray left">');
				echo('<a id="left_'.$val.'_a" href="javascript:;" onclick="$.menus(\'#left_'.$val.'\', \'../modules/'.$val.'/manage/_left.php\', this);" title="열기/닫기" onfocus="this.blur()">'.$cfg['solution'][$val].'</a></h3>');
				echo('<div id="left_'.$val.'" class="leftMenu"></div>');
				echo('</div></div>');
				$n++;
			}
		}

		//모듈이 없을때
		if($n < 1)
		{
			echo('<div class="box"><div class="line">');
			echo('<div class="bg_gray">');
			echo('<ul class="wrap">');
			echo('<li class="blank" style="text-align:center; padding:30px 0px;">설정된 모듈이<br />존재하지 않습니다</li>');
			echo('</ul>');
			echo('</div>');
			echo('</div></div>');
		}
		unset($n);
		?>
	</div>
	<div class="tabBody hide" id="tabBodyExpert">

	<?php
	if($_SESSION['ulevel'] <= $cfg['operator'])
	{
		//디자인관리 메뉴
		foreach ($cfg['skinName'] as $key => $val)
		{
			$class = ($key == 'default') ? "leftMenuOpen" : "leftMenu";
			if($key == 'default')
			{
				echo('
				<hr />
				<div class="box"><div class="line">
				<h3 class="bg_gray left"><a id="left_'.$key.'_a" href="javascript:;" onclick="$.menus(\'#left_'.$key.'\', \'\', this);" alt="열기/닫기" title="열기/닫기" onfocus="this.blur()"><span class="orange">DP</span>: <span class="colorViolet">'.$val.'</span></a></h3>
				<div id="left_'.$key.'" class="leftMenu">
				<ul>
					<li class="menu"><strong>ㆍ</strong><a href="javascript:;" onclick="$.insert(\'#module\', \'./modules/index.php?type=display&skin='.$key.'&position=MT\',null,300)">메인 디자인 설정</a></li>
					<li class="menu"><strong>ㆍ</strong><a href="javascript:;" onclick="$.insert(\'#module\', \'./modules/index.php?type=display&skin='.$key.'&position=ST\',null,300)">서브 디자인 설정</a></li>
					<li class="sect"></li>
					<li class="menu"><strong>ㆍ</strong><a href="javascript:;" onclick="$.message(\'./site/index.php?type=stylePost&skin='.$key.'\');"><span class="colorRed">캐시 업데이트</span> (CSS)</a></li>
					<li class="menu"><strong>ㆍ</strong><a href="javascript:;" onclick="$.message(\'./site/index.php?type=xmlPost&skin='.$key.'\');"><span class="colorRed">캐시 업데이트</span> (XML)</a></li>
				</ul>
				<div class="clear"></div>
				</div>
				</div></div>
				');
			}
			else
			{
				if($cfg['site'][$key.'web']) 
				{
					echo('
					<hr />
					<div class="box"><div class="line">
					<h3 class="bg_gray left"><a id="left_'.$key.'_a" href="javascript:;" onclick="$.menus(\'#left_'.$key.'\', \'\', this);" alt="열기/닫기" title="열기/닫기" onfocus="this.blur()"><span class="orange">DP</span>: <span class="colorViolet">'.$val.'</span></a></h3>
					<div id="left_'.$key.'" class="leftMenu">
					<ul>
						<li class="menu"><strong>ㆍ</strong><a href="javascript:;" onclick="$.insert(\'#module\', \'./modules/index.php?type=display&skin='.$key.'&position=MT\',null,300)">메인 디자인 설정</a></li>
						<li class="menu"><strong>ㆍ</strong><a href="javascript:;" onclick="$.insert(\'#module\', \'./modules/index.php?type=display&skin='.$key.'&position=ST\',null,300)">서브 디자인 설정</a></li>
						<li class="sect"></li>
						<li class="menu"><strong>ㆍ</strong><a href="javascript:;" onclick="$.message(\'./site/index.php?type=stylePost&skin='.$key.'\');"><span class="colorRed">캐시 업데이트</span> (CSS)</a></li>
						<li class="menu"><strong>ㆍ</strong><a href="javascript:;" onclick="$.message(\'./site/index.php?type=xmlPost&skin='.$key.'\');"><span class="colorRed">캐시 업데이트</span> (XML)</a></li>
					</ul>
					<div class="clear"></div>
					</div>
					</div></div>
					');
				}
			}
		}
		?>
		<hr />
		<div class="box"><div class="line">
			<h3 class="bg_gray" style="text-align:left;"><a id="left_updateAll_a" href="javascript:;" onclick="$.menus('#left_updateAll', '', this);" title="열기/닫기" onfocus="this.blur()"><span>기타 업데이트</span></a></h3>
			<div id="left_updateAll" class="leftMenuOpen">
				<ul>
					<li class="menu"><strong>ㆍ</strong><a href="javascript:;" onclick="$.insert('#module','./site/update.php',null,300)"><span class="colorRed">DB 업데이트</span> (schema)</a></li>
				</ul>
				<div class="clear"></div>
			</div>
		</div></div>
		<!--<hr />
		<div class="box"><div class="line">
			<h3 class="bg_gray" style="text-align:left;"><a href="javascript:;" onclick="$.insert('#module', './site/makeBtn.php?type=<?php echo($sess->encode('makeBtn'));?>',300)"><span>버튼 생성하기</span></a></h3>
		</div></div>
		<hr />
		<div class="box"><div class="line">
			<h3 class="bg_gray" style="text-align:left;"><a href="./tools/backupDb.php" target="hdFrame" class="none"><span>DB 백업하기</span></a></h3>
		</div></div>-->
	<?php
	}
	?>

	</div><!--end.tabBody-->
</div><!--end.tabMenu-->
<br />
<div class="center colorGray">ⓒ<strong>10억홈피</strong> All rights reserved</div>
<?php if($_SESSION['ulevel'] <= $cfg['operator']) { ?>
<div><iframe id="hdFrame" name="hdFrame" wrap="off" style="width:100%;<?php if($cfg['site']['debug']){echo('height:100px;');}else{echo('display:none;');}?>"></iframe></div>
<?php } ?>
