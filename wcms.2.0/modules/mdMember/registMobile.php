<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
//약관동의 와 개인정보취급방침 둘다 없을경우 가입폼으로 이동
if($cfg['module']['agreement'] == 'N') { header("Location: /index.php?cate=000002002&type=".$sess->encode('registForm')); die(); }
?>
<div id="regist_wrap">
<div class="regist_container">
<form id="regForm" name="reg" method="get" action="<?php echo($_SERVER['PHP_SELF']);?>#content" enctype="multipart/form-data">
<input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />
<input type="hidden" name="type" value="<?php echo($sess->encode('registForm'));?>" />
<div class="agreement">
	<?php
	if($cfg['module']['opt_agreement'] != 'N')
	{
		$agreement= @file_get_contents(__HOME__."/cache/document/000999002.html");
		print('<h3 class="bold colorGreen pd5 left">이용약관 안내</h3>
		<div class="frame textContent pd10 bg_gray" style="height:120px;">'.stripslashes($agreement).'</div>
		<div class="pd5 right"><span class="colorBlue"><input type="checkbox" id="agree1" name="agree1" class="input_check required" title="회원 이용약관의 동의는 필수선택 입니다." value="Y" />&nbsp;<label for="agree1">회원 이용약관에 동의함</label></span></div>');
		$privacy  = @file_get_contents(__HOME__."/cache/document/000999003001.html");
		print('<h3 class="bold colorGreen pd5 left">개인정보취급방침 안내</h3>
		<div class="frame textContent pd10 bg_gray" style="height:120px;">'.stripslashes($privacy).'</div>
		<div class="pd5 right"><span class="colorBlue"><input type="checkbox" id="agree2" name="agree2" class="input_check required" title="개인정보 수집 및 취급방침에 동의는 필수선택 입니다." value="Y" />&nbsp;<label for="agree2">개인정보 수집 및 취급방침에 동의함</label></span></div>');
	}
	?>
	
	<div class="cube">
	<p class="pd3 bg_gray"><span>※ 위 이용약관 및 개인정보취급방침에 대해 동의 하셔야 합니다.</span></p>
	<!--<p class="pd3 bg_gray"><span>-. 학생과 교직원가입은 가입완료 후 별도의 승인을 통해 가입처리 됩니다.</p>-->
	<?php if($func->checkModule('mdMileage') && $cfg['mileage']['mileageReg'] > 0) { echo('<p class="pd3 bg_gray orange">※ 회원가입 완료시 적립금 <strong class="colorRed">'.number_format($cfg['mileage']['mileageReg']).'포인트</strong>를 적립하여 드립니다!</p>'); } ?>
	<?php if($cfg['module']['opt_idcode']=='N') { ?>
	<p class="pd3 bg_gray"><span>※ 본 사이트는 주민번호를 기입받지 않습니다.</span></p>
	<?php } ?>
	<div class="line">
	<!--		<p class="pd3 center"><span><input type="radio" id="memType01" name="memType" value="P" checked="checked" />&nbsp;<label for="memType01" class="bold blue">일반회원</label></span><span>&nbsp;&nbsp;&nbsp;</span><span><input type="radio" id="memType02" name="memType" value="C" />&nbsp;<label for="memType02" class="bold blue">교사 및 교내직원</label></span>&nbsp;&nbsp;&nbsp;<span><input type="radio" id="memType03" name="memType" value="S" />&nbsp;<label for="memType03" class="bold blue">교내학생</label></span>&nbsp;&nbsp;&nbsp;<span><input type="radio" id="memType04" name="memType" value="F" />&nbsp;<label for="memType04" class="bold blue">학부모</label></span></p>-->
		<p class="center pd10"><span class="btnPack black large strong"><button type="submit" onclick="return $.submit(this.form)">가입양식 작성하기</button></span></p>
	</div>
	</div><!-- .cube end -->
</div><!-- .agreement end -->
</form>
<br /></div>
<!-- .regist_container end --></div>
<!-- #regist_wrap end -->
