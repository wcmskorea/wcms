<?php if($_SESSION['author']) { echo('<div class="pd10 center red bold">본인 인증을 거치지 않은 회원의 경우 반드시 아래의 인증을 거쳐야 이용할 수 있습니다.</div>'); } ?>
	<div class="realname" style="width:600px; height:220px; background:URL(<?php echo(__SKIN__);?>image/background/bg_regist_both.gif) no-repeat;">
		<fieldset>
		<legend>실명인증</legend>
		<table summary="회원가입을 위한 공공I-PIN 및 실명인증 신청양식 입니다." style="margin:55px 0 0 0px;">
		<caption>공공I-PIN 및 실명인증</caption>
		<col width="200" />
		<col width="200" />
		<col width="200" />
		<tbody>
		<tr>
			<td><p class="right"><input type="radio" id="memType01" name="memType" value="P" checked="checked" onfocus="authTab(1);" />&nbsp;<label for="memType01"><strong>공공I-PIN</strong></label></p></td>
			<td><p class="center"><input type="radio" id="memType02" name="memType" value="C" onfocus="authTab(2);" />&nbsp;<label for="memType02"><strong>실명인증</strong></label></p></td>
			<td><p class="left"><span class="button bred strong"><button type="button" onclick="authRealName()">실명확인</button></span></p></td>
		</tr>
		</tbody>
		</table>
	</fieldset>
	<div id="tab01" style="margin-top:20px; text-align:center; line-height:20px;">
		<p>공공 아이핀(I-PIN)은 인터넷상의 개인식별번호를 의미하며, 대면확인이 어려운<br />인터넷에서 주민등록번호를 사용하지 않도록 본인임을 확인 할 수 있는 수단입니다.<br />[등록방법은 좌측 "공공I-PIN 가입안내" 참조]</p>
	</div>
	<div id="tab02" style="margin-top:20px; text-align:center; line-height:20px; display:none;">
		<p>개정 “주민등록법”에 의해 타인의 주민등록번호를 부정사용하는 자는<br />3년 이하의 징역 또는 1천만원 이하의 벌금이 부과될 수 있습니다.<br />관련법률 : 주민등록법 제 37조(벌칙) 제 9호(시행일 2006.09.24)<br />[등록방법은 좌측 "실명인증 가입안내" 참조]</p>
	</div>
</div><!-- .realname end -->

</form><!-- #regForm end -->
<?php
	include __PATH__."modules/mdMember/embed/realName.php";
?>
<script language=javascript>
//<![CDATA[
function authTab(val)
{
	if(val == '1') {
		toGetElementById('tab02').style.display = 'none';
		toGetElementById('tab01').style.display = 'block';
	} else {
		toGetElementById('tab02').style.display = 'block';
		toGetElementById('tab01').style.display = 'none';
	}
}
function authRealName()
{
	if(toGetElementById('memType01').checked == true)
	{
		wWidth = 360;
		wHight = 120;
		wX = (window.screen.width - wWidth) / 2;
		wY = (window.screen.height - wHight) / 2;
		//Request Page Call
		window.open("./modules/mdMember/embed/GPIN/AuthRequest.php", "gPinLoginWin", "directories=no,toolbar=no,left="+wX+",top="+wY+",width="+wWidth+",height="+wHight);
	} else if(toGetElementById('memType02').checked == true) {
		wWidth = 410;
		wHight = 560;
		wX = (window.screen.width - wWidth) / 2;
		wY = (window.screen.height - wHight) / 2;
		window.open("", "realAuth", "directories=no,toolbar=no,left="+wX+",top="+wY+",width="+wWidth+",height="+wHight);
		//toGetElementById('realNameForm').submit();
		window.document.realNameForm.submit();
	}
}
//]]>
</script>
<!--실명확인팝업 요청 form --------------------------->
<form id="realNameForm" action="https://cert.namecheck.co.kr/certnc_input.asp" target="realAuth" name="realNameForm" method="post">
<input type="hidden" name="enc_data" value="<?php echo($enc_data);?>" />
</form>
<!--End 실명확인팝업 요청 form ----------------------->
