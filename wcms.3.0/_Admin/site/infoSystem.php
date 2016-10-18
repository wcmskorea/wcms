<?php
require_once "../../_config.php";
require_once "../include/commonHeader.php";
?>
<fieldset id="help">
<legend>→ TIP's ←</legend>
<ul>
	<li>시스템 아이디 (10억홈피닷컴 계정 아이디)는 변경하실 수 없습니다.</li>
	<li>운영자 이름과 비밀번호 변경시 10억홈피 사이트의 정보도 동일 변경됩니다.</li>
	<li>시스템 아이디 및 인증키는 외부에 노출되서는 안되므로 주의하시기 바랍니다.</li>
	<!--li>PG상점 아이디와, PG상점 인증키는 PG사와 계약후 발급되는 정보를 입력하시면 됩니다.</li-->
</ul>
</fieldset>
<form id="frm02" name="frm02" method="post" action="./site/index.php" enctype="multipart/form-data" target="hdFrame">
<input type="hidden" name="type" value="infoSystemPost" />
<table class="table_basic" style="width:100%;">
	<caption>시스템 정보 설정</caption>
	<colgroup>
	<col width="140">
	<col>
	</colgroup>
	<thead>
		<tr><th scope="col" colspan="2" class="first"><p class="center"><span>시스템 정보 설정</span></p></th></tr>
	</thead>
	<tbody>
	<?php
		$form = new Form('table');

		$form->addStart('운영자 아이디', 'admid', 1);
		$form->add('input', 'admid', $cfg['site']['id'], 'width:200px;', 'minlength="4" maxlength="15"', 'readonly="readonly"');
		$form->addHtml('<li class="opt"><span class="small_gray">( 수정불가 )</span></li>');
		$form->addEnd(1);

		$form->addStart('운영자 이　름', 'operator', 1, 0, 'M');
		$form->add('input', 'operator', $cfg['site']['operator'], 'width:200px; ime-mode:active','maxlength="20"');
		$form->addEnd(1);

		$form->addStart('운영자 휴대폰', 'mobile', 1, 0, 'M');
		$form->add('input', 'mobile', $cfg['site']['mobile'], 'width:200px;','mobile="true" maxlength="14"');
		$form->addHtml('<li class="opt"><span class="small_gray">주문정보 및 알림 메세지 수신</span></li>');
		$form->addEnd(1);

		$form->addStart('운영자 이메일', 'email', 1, 0, 'M');
		$form->add('input', 'email', $cfg['site']['email'], 'width:200px; ime-mode:disabled','email="true" maxlength="50"');
		$form->addHtml('<li class="opt"><span class="small_gray">주문정보 및 알림 메세지 수신</span></li>');
		$form->addEnd(1);

		$form->addStart('운영자 비밀번호', 'admpasswd', 1);
		$form->add('input', 'admpasswd', '', 'width:200px;','minlength="8" maxlength="16"');
		$form->addHtml('<li class="opt"><span class="small_gray">변경시에만 입력하세요</span></li>');
		$form->addEnd(1);

		$form->addStart('시스템 인증키', 'authCode', 1);
		$form->add('input', 'authCode', $cfg['site']['authCode'], 'width:200px; color:red;','maxlength="20"');
		$form->addHtml('<li class="opt"><span class="small_gray">10억홈피의 서비스와 연동하기 위한 API키 값</span></li>');
		$form->addEnd(1);

		/*$form->addStart('다음지도 API키', 'apiDaummap', 1);
		$form->add('input', 'apiDaummap', $cfg['site']['apiDaummap'], 'width:200px; color:red;');
		$form->addHtml('<li class="opt"><span class="small_gray">다음지도 서비스와 연동하기 위한 API키 값</span></li>');
		$form->addEnd(1);

		$form->addStart('네이버지도 API키', 'apiNavermap', 1);
		$form->add('input', 'apiNavermap', $cfg['site']['apiNavermap'], 'width:200px; color:red;');
		$form->addHtml('<li class="opt"><span class="small_gray">네이버 지도 서비스와 연동하기 위한 API키 값</span></li>');
		$form->addEnd(1);*/

		$form->addStart('파비콘 설정', 'upfile', 1);
		$form->addHtml('<li class="opt"><span class="small_gray">파일 확장자가 ico 인 파일을 "파일관리"를 통해 /user/사이트타입/image/icon/ 폴더에 업로드하세요. 사이즈(16x16)</span></li>');
		$form->addEnd(1);

		$form->addStart('시스템 시작일', 'regyear', 1, 0, 'M');
		$form->add('date', 'reg', $cfg['site']['dateReg'], 'width:200px;');
		$form->addHtml('<li class="opt"><span class="small_gray">웹사이트 런칭일</span></li>');
		$form->addEnd(1);

		$form->addStart('시스템 점검일', 'checkyear', 1);
		$form->add('datemin', 'check', $cfg['site']['dateCheck'], 'width:315px;');
		$form->addHtml('<li class="opt"><span class="small_gray">현재시간 보다 커야 적용되며, 시간경과 후 자동해제됨</span></li>');
		$form->addEnd(1);

		$form->addStart('최근 변경 정보', '', 1);
		$form->addHtml('<li class="opt"><span><strong>TIME:</strong> '.str_replace("|", " <strong>IP:</strong> ", $cfg['site']['info']).'</span></li>');
		$form->addEnd(1);
	?>
	</tbody>
</table>
<div class="pd5 center"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit" onclick="return $.submit(this.form)">위 설정으로 적용</button></span></div>
</form>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    $('#frm02').validate({onkeyup:function(element){$(element).valid();}});
    $("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
    $("input[type=file]").change(function(){
            if((new RegExp("\.(ico)$", "i")).test(this.value)) return true;
			alert("아이콘(.ico) 파일만 첨부하실 수 있습니다.");
			this.value = '';
            this.select();
            document.selection.clear();
            return false;
	});
});
//]]>
</script>
<?php include "../include/commonScript.php"; ?>
