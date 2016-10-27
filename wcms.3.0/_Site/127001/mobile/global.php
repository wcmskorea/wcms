<h2>글로벌 메뉴</h2>
<ul class="direct">
	<li><a href="#content" class="skip">본문바로가기</a></li>
	<li><a href="#footer" class="skip">하단메뉴 및 주소,전화번호 안내 바로가기</a></li>
	<li><a href="<?php echo($cfg['droot']);?>index.php">홈</a></li>
	<?php
	if($func->checkModule('mdMember'))
	{
		if(isset($_SESSION['uid']))
		{
			echo('<li><a href="'.$cfg['droot'].'?cate=000002001">로그아웃</a></li>');
			echo('<li><a href="'.$cfg['droot'].'?cate=000002002">회원정보</a></li>');
		} else
		{
			echo('<li><a href="javascript:;" onclick="$.login();">로그인</a></li>');
			echo('<li><a href="'.$cfg['droot'].'?cate=000002002">회원가입</a></li>');
		}
	}
	?>
	<li><a href="<?php echo($cfg['droot']);?>index.php?cate=000999001">사이트맵</a></li>
	<li><a href="<?php echo($cfg['droot']);?>sites/mobile/">PC버전</a></li>
	<?php if($cfg['site']['englishweb'] == '1') { ?><li><a href="<?php echo($cfg['droot']);?>sites/english/">English</a></li><?php } ?>
	<!--<li><a href="javascript:;" onclick="this.style.behavior='url(#default#homepage)';this.setHomePage('http://<?php echo($cfg['site']['domain']);?>');">시작페이지설정</a></li>
	<li><a href="javascript:;" onclick="window.external.AddFavorite('http://<?php echo($cfg['site']['domain']);?>','<?php echo($cfg['site']['siteName']);?>');">즐겨찾기추가</a></li>-->
    <?php if($_SESSION['ulevel'] && $_SESSION['ulevel'] < 3) { ?><li><a href="<?php echo($cfg['droot']);?>_Admin/" class="bold">관리시스템</a></li><?php } ?>
    <li><span><img src="<?php echo(__SKIN__);?>image/icon/top_zoom01.gif" width="24" height="16" alt="크게" style="cursor: pointer;" onclick="zoomIn();" onkeypress="zoomIn();" /></span><span><img src="<?php echo(__SKIN__);?>image/icon/top_zoom02.gif" width="26" height="16" alt="기본" style="cursor: pointer;" onclick="zoomReset();" onkeypress="zoomReset();" /></span><span><img src="<?php echo(__SKIN__);?>image/icon/top_zoom03.gif" width="15" height="16" alt="작게" style="cursor: pointer;" onclick="zoomOut();" onkeypress="zoomOut();" /></span>
	</li>
</ul>
<div class="clear"></div>
