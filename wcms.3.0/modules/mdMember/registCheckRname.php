<script language=javascript>
//<![CDATA[
	function authRealName(frm)
	{
		wWidth = 585;
		wHight = 490;
		wX = (window.screen.width - wWidth) / 2;
		wY = (window.screen.height - wHight) / 2;
		window.open("", "realAuth", "directories=no,toolbar=no,left="+wX+",top="+wY+",width="+wWidth+",height="+wHight);
		frm.submit();
		//toGetElementById('realNameForm').submit();
		//window.document.realNameForm.submit();
	}
//]]>
</script>
<form id="regForm" name="reg" method="post" action="/addon/namecheck/nc.php" enctype="multipart/form-data" onsubmit="return authRealName(this);" target="realAuth">
<input type="hidden" name="cated" value="<?php echo($_GET['cated']);?>" />
<div class="agreement">
	<?php
	if($cfg['content']['opt_agreement'] != 'N') {
		//$agreement= @file_get_contents(__HOME__."/cache/document/000999002.html");
		//print('<h3 class="bold colorGreen pd5 right">이용약관 안내</h3>
		//<div class="frame textContent pd10 bg_gray" style="height:150px;">'.stripslashes($agreement).'</div><p class="pd5"><span class="blue"><input type="checkbox" id="agree1" name="agree1" class="input_check required" title="회원 이용약관의 동의는 필수선택 입니다." value="Y" />&nbsp;<label for="agree1">회원 이용약관에 동의 합니다.</label></span></p>');
		$privacy  = @file_get_contents(__HOME__."/cache/document/000999003001.html");
		print('<h3 class="bold colorGreen pd5 right">개인정보취급방침 안내</h3>
		<div class="frame textContent pd10 bg_gray" style="height:150px;">'.stripslashes($privacy).'</div>
		<div class="pd5"><span class="red"><input type="checkbox" id="agree2" name="agree2" class="input_check required" title="개인정보 수집 및 취급방침에 동의는 필수선택 입니다." value="Y" checked="checked" />&nbsp;<label for="agree2">개인정보 수집 및 취급방침에 동의 합니다.</label></span></div>');
	}
	?>
	<div class="cube">
	<ul>
		<li><p class="pd3 bg_gray">1. 위 개인정보취급방침에 대해 동의 하셔야 합니다.</p></li>
		<li><p class="pd3 bg_gray">2. 아래의 "성인 인증하기"를 클릭하여 성인인증을 거쳐야만 해당 서비스를 이용하실 수 있습니다.</p></li>
	</ul>
    <?php if($func->checkModule('mdMileage') && $mileage > 0) { print('<p class="pd3 bg_gray orange">-. 회원가입 완료시 적립금 <strong>'.number_format($mileage).'포인트</strong>를 적립하여 드립니다!</p>'); } ?>
    <div class="line">
<!--		<p class="pd3 center"><span><input type="radio" id="memType01" name="memType" value="P" checked="checked" />&nbsp;<label for="memType01" class="bold blue">일반회원</label></span><span>&nbsp;&nbsp;&nbsp;</span><span><input type="radio" id="memType02" name="memType" value="C" />&nbsp;<label for="memType02" class="bold blue">교사 및 교내직원</label></span>&nbsp;&nbsp;&nbsp;<span><input type="radio" id="memType03" name="memType" value="S" />&nbsp;<label for="memType03" class="bold blue">교내학생</label></span>&nbsp;&nbsp;&nbsp;<span><input type="radio" id="memType04" name="memType" value="F" />&nbsp;<label for="memType04" class="bold blue">학부모</label></span></p>-->
		<p class="center pd10"><span class="btnPack large red strong"><button type="submit">실명 인증하기</button></span></p>
    </div>
	</div><!-- .cube end -->
</div><!-- .agreement end -->
</form>