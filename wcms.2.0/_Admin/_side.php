<?php
if($_SESSION[ulevel] == '1')
{
?>
<?php
foreach ($cfg[skinName] as $key => $val)
{
  $display = ($key == 'default') ? "none" : "none";
  print('<hr />
  <div class="cube"><div class="line">
    <h3 class="menu_gray" style="text-align:left;" onclick="$(\'#'.$key.'_main\').animate({height:\'toggle\',opacity:\'toggle\'}, \'fast\');"><span class="orange">DP</span>-<span class="violet">'.$val.'</span></h3>
    <div id="'.$key.'_main" class="sub" style="border-top:1px solid #d2d2d2; display:'.$display.';">
      <ul class="menuWrap">
        <li class="menuSide"><a href="#none" onclick="$.insert(\'#module\', \'./modules/index.php\', \'&amp;type=display&amp;skin='.$key.'&amp;pos=MT\',300)"><img src="'.$cfg[droot].'image/button/btn_dmt.gif" width="44" height="15" alt="상" /></a></li>
        <li class="menuSide"><a href="#none" onclick="$.insert(\'#module\', \'./modules/index.php\', \'&amp;type=display&amp;skin='.$key.'&amp;pos=ML\',300)"><img src="'.$cfg[droot].'image/button/btn_dml.gif" width="44" height="15" alt="좌" /></a></li>
        <li class="menuSide"><a href="#none" onclick="$.insert(\'#module\', \'./modules/index.php\', \'&amp;type=display&amp;skin='.$key.'&amp;pos=MC\',300)"><img src="'.$cfg[droot].'image/button/btn_dmc.gif" width="44" height="15" alt="중" /></a></li>
        <li class="menuSide"><a href="#none" onclick="$.insert(\'#module\', \'./modules/index.php\', \'&amp;type=display&amp;skin='.$key.'&amp;pos=MR\',300)"><img src="'.$cfg[droot].'image/button/btn_dmr.gif" width="44" height="15" alt="우" /></a></li>
        <li class="menuSide"><a href="#none" onclick="$.insert(\'#module\', \'./modules/index.php\', \'&amp;type=display&amp;skin='.$key.'&amp;pos=MB\',300)"><img src="'.$cfg[droot].'image/button/btn_dmb.gif" width="44" height="15" alt="하" /></a></li>
        <li class="menuSide"><a href="#none" onclick="$.insert(\'#module\', \'./modules/index.php\', \'&amp;type=display&amp;skin='.$key.'&amp;pos=MQ\',300)"><img src="'.$cfg[droot].'image/button/btn_dmq.gif" width="44" height="15" alt="퀵" /></a></li>
        <li class="menuSide"><a href="#none" onclick="$.insert(\'#module\', \'./modules/index.php\', \'&amp;type=display&amp;skin='.$key.'&amp;pos=ST\',300)"><img src="'.$cfg[droot].'image/button/btn_dst.gif" width="44" height="15" alt="상" /></a></li>
        <li class="menuSide"><a href="#none" onclick="$.insert(\'#module\', \'./modules/index.php\', \'&amp;type=display&amp;skin='.$key.'&amp;pos=SL\',300)"><img src="'.$cfg[droot].'image/button/btn_dsl.gif" width="44" height="15" alt="좌" /></a></li>
        <li class="menuSide"><a href="#none" onclick="$.insert(\'#module\', \'./modules/index.php\', \'&amp;type=display&amp;skin='.$key.'&amp;pos=SC\',300)"><img src="'.$cfg[droot].'image/button/btn_dsc.gif" width="44" height="15" alt="중" /></a></li>
        <li class="menuSide"><a href="#none" onclick="$.insert(\'#module\', \'./modules/index.php\', \'&amp;type=display&amp;skin='.$key.'&amp;pos=SR\',300)"><img src="'.$cfg[droot].'image/button/btn_dsr.gif" width="44" height="15" alt="우" /></a></li>
        <li class="menuSide"><a href="#none" onclick="$.insert(\'#module\', \'./modules/index.php\', \'&amp;type=display&amp;skin='.$key.'&amp;pos=SB\',300)"><img src="'.$cfg[droot].'image/button/btn_dsb.gif" width="44" height="15" alt="하" /></a></li>
        <li class="menuSide"><a href="#none" onclick="$.insert(\'#module\', \'./modules/index.php\', \'&amp;type=display&amp;skin='.$key.'&amp;pos=SQ\',300)"><img src="'.$cfg[droot].'image/button/btn_dsq.gif" width="44" height="15" alt="퀵" /></a></li>
        <li class="menuSide"><a href="#none" onclick="$.message(\'./site/index.php\', \'&amp;type=stylePost&amp;skin='.$key.'\');"><span class="orange">업데이트</span> : <strong>Style-Sheet</strong></a></li>
        <li class="menuSide"><a href="#none" onclick="$.message(\'./site/index.php\', \'&amp;type=xmlPost&amp;skin='.$key.'\');"><span class="orange">업데이트</span> : <strong>Flash-XML</strong></a></li>
      </ul>
      <div class="clear"></div>
    </div>
  </div></div>');
}
?>
<hr />
<div class="cube"><div class="line">
  <h3 class="menu_gray" style="text-align:left;"><img src="<?=$cfg[droot]?>image/icon/icon_aw_dw.gif" width="10" height="10" alt="버튼 생성하기" />&nbsp;<span>일괄 업데이트</span></h3>
  <div id="updates" class="sub bg_gray" style="border-top:1px solid #d2d2d2;">
    <ul class="wrap">
      <li class="menu"><a href="#none" onclick="$.message('./site/index.php', '&amp;type=allStylePost');">업데이트 : All-StyleSheet</a></li>
      <li class="menu"><a href="#none" onclick="$.insert('#module','./site/index.php','&amp;type=updateSql',300)">업데이트 : DB-Schema</a></li>
    </ul>
   </div>
</div></div>
<hr />
<div class="cube"><div class="line">
		<h3 class="menu_gray" style="text-align:left;"><img src="<?=$cfg[droot]?>image/icon/icon_aw_dw.gif" width="10" height="10" alt="버튼 생성하기" />&nbsp;<a href="#none" onclick="$.insert('#module', './site/makeBtn.php', '&amp;type=<?=$sess->encode('makeBtn')?>',300)"><span>버튼 생성하기</span></a></h3>
</div></div>
<?php
}
?>
<hr />
<div class="cube"><div class="line">
		<h3 class="menu_gray" style="text-align:left;"><img src="<?=$cfg[droot]?>image/icon/icon_aw_dw.gif" width="10" height="10" alt="버튼 생성하기" />&nbsp;<a href="./site/backup_db.php" target="hdFrame"><span>DB 백업하기</span></a></h3>
</div></div>
<hr />
<div class="cube"><div class="line">
	<h3><img src="<?=$cfg[droot]?>image/icon/icon_aw_dw.gif" width="10" height="10" alt="버튼 생성하기" />&nbsp;<a href="./_auto_link.asp?sh_type=<?=$sess->encode('autoLink')?>&sh_log_kbn=<?=$sess->encode('css')?>" target="hdFrame" class="act"><span>유지보수관련 요청하기</span></a></h3>
  <div class="sub bg_white" style="border-top:1px solid #d2d2d2">
  <ul class="wrap">
    <li class="blank small_gray" style="text-align:justify;">10억홈피 고객만족 시스템을 통하여 고객 요구사항을 온라인으로 실시간 처리 및 응대 합니다.</li>
    <li class="blank small_gray" style="text-align:justify;"><a href="./_css.php?sh_type=<?=$sess->encode('autoLink')?>&sh_log_kbn=<?=$sess->encode('css')?>" target="hdFrame"><img src="<?=$cfg[droot]?>image/button/btn_css.jpg" width="162" height="21" title="CSS 요청하기" /></a></li>
  </ul>
  </div>
</div></div>
<!--<hr />
<div class="cube"><div class="line">
  <div class="bg_white">
  <ul class="wrap">
    <li class="blank small_gray" style="text-align:justify;"><a href="<?=$cfg[droot]?>common/manual_system.pdf"><img src="<?=$cfg[droot]?>image/button/btn_manual.jpg" width="162" height="21" title="시스템 매뉴얼 다운받기" /></a></li>
    <li class="blank small_gray" style="text-align:justify;"><a href="<?=$cfg[droot]?>common/adobeacrobat9.exe"><img src="<?=$cfg[droot]?>image/button/btn_free_pdf.gif" width="162" height="40" title="PDF뷰어 다운로드" /></a></li>
    <li class="blank small_gray" style="text-align:center;">본 시스템은 Explorer8.0 이상에 최적화 되어있습니다.<br /> <a href="" class="act">[ IE8.0 설치하기 ]</a></li>
  </ul>
  </div>
</div></div>-->
