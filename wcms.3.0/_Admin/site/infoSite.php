<?php
require_once "../../_config.php";
require_once "../include/commonHeader.php";
?>
<fieldset id="help">
<legend>→ TIP's ←</legend>
<ul>
	<li>통신판매신고번호 및 사업자신고번호는 '-'을 반드시 포함하여 작성하시기 바랍니다.</li>
	<li>사이트 제목, 설명, 키워드는 검색결과에 반영되므로 명확히 작성하시기 바랍니다.</li>
	<li>사업자 정보는 사이트의 하단에 표기되는 기본정보이며, 별도 디자인 작업을 통해 사용자 화면에 노출됩니다.</li>
</ul>
</fieldset>

<form id="frm01" name="frm01" method="post" action="./site/index.php" enctype="multipart/form-data" target="hdFrame">
<input type="hidden" name="type" value="infoBizPost" />
<input type="hidden" name="domain" value="<?php echo(__HOST__);?>" />
<table class="table_basic" style="width:100%;">
<caption>사이트 정보 설정</caption>
	<col width="140">
	<col>
	<thead>
		<tr><th colspan="2" class="first"><p class="center"><span>사이트 정보 설정</span></p></th></tr>
	</thead>
	<tbody>
	<?php
	$form = new Form('table');

	$form->addStart('대표 도메인', 'domain', 1);
	$form->addHtml('<strong class="colorViolet">'.__HOST__.'</strong>&nbsp;<span class="small_gray">(대표도메인 변경시 유지보수 업체에 문의바랍니다.)</span>');
	$form->addEnd(1);

	$form->addStart('사이트 이름', 'siteName', 1, 0, 'M');
	$form->add('input', 'siteName', $cfg['site']['siteName'], 'width:300px;','minlength="2" maxlength="30"');
	$form->addHtml('<li class="opt"><span class="small_gray">(예) 10억홈피</span></li>');
	$form->addEnd(1);

	$form->addStart('사이트 제목', 'siteTitle', 1);
	$form->add('input', 'siteTitle', $cfg['site']['siteTitle'], 'width:300px;','minlength="2" maxlength="50"');
	$form->addHtml('<li class="opt"><span class="small_gray">(예) 방문해주셔서 감사합니다!</span></li>');
	$form->addEnd(1);

	$form->addStart('사이트 설명', 'description', 1);
	$form->add('textarea', 'description', $cfg['site']['description'], 'width:300px; height:60px;','minlength="2" maxlength="200"');
	$form->addHtml('<li class="opt"><span class="small_gray">(예)wCMS 솔루션을 통한 빠르고 쉬운 ...</span></li>');
	$form->addEnd(1);

	$form->addStart('사이트 키워드', 'keywords', 1);
	$form->add('textarea', 'keywords', $cfg['site']['keywords'], 'width:300px; height:60px;','minlength="2" maxlength="200"');
	$form->addHtml('<li class="opt"><span class="small_gray">(예)10억홈피,홈페이지 제작,홈페이 관리 ...</span></li>');
	$form->addEnd(1);

	$form->addStart('사업자 대표자', 'ceo', 1);
	$form->add('input', 'ceo', $cfg['site']['ceo'], 'width:300px; ime-mode:active','korean="true" maxlength="16"');
	$form->addHtml('<li class="opt"><span class="small_gray">(예) 홍길동</span></li>');
	$form->addEnd(1);

	$form->addStart('사업자 상호명', 'groupName', 1);
	$form->add('input', 'groupName', $cfg['site']['groupName'], 'width:300px;','maxlength="30"');
	$form->addHtml('<li class="opt"><span class="small_gray">(예) (주)10억홈피</span></li>');
	$form->addEnd(1);

	$form->addStart('사업자 번호', 'groupNo', 1);
	$form->add('input', 'groupNo', $cfg['site']['groupNo'], 'width:300px;','bizno="true" maxlength="12"');
	$form->addHtml('<li class="opt"><span class="small_gray">\' - \' 하이픈 포함 입력</span></li>');
	$form->addEnd(1);

	$form->addStart('통신판매신고번호', 'ecommerceNo', 1);
	$form->add('input', 'ecommerceNo', $cfg['site']['ecommerceNo'], 'width:300px;','maxlength="30"');
	$form->addHtml('<li class="opt"><span class="small_gray">\' - \' 하이픈 포함 입력</span></li>');
	$form->addEnd(1);

	$form->addStart('사업자 업태', 'status', 1);
	$form->add('input', 'status', $cfg['site']['status'], 'width:300px;','maxlength="30"');
	$form->addEnd(1);

	$form->addStart('사업자 종목', 'class', 1);
	$form->add('input', 'class', $cfg['site']['class'], 'width:300px;','maxlength="30"');
	$form->addEnd(1);

	$form->addStart('사업장 연락처', 'phone', 1);
	$form->add('input', 'phone', $cfg['site']['phone'], 'width:300px;','phone="true" maxlength="14"');
	$form->addHtml('<li class="opt"><span class="small_gray">\' - \' 하이픈 포함 입력</span></li>');
	$form->addEnd(1);

	$form->addStart('사업장 팩스', 'fax', 1);
	$form->add('input', 'fax', $cfg['site']['fax'], 'width:300px;','phone="true" maxlength="14"');
	$form->addHtml('<li class="opt"><span class="small_gray">\' - \' 하이픈 포함 입력</span></li>');
	$form->addEnd(1);

	$form->addStart('사업장 우편번호', 'zipcode', 1);
	$form->add('input', 'zipcode', $cfg['site']['zipcode'], 'width:80px;','zipno="true" maxlength="7"');
	$form->addHtml('<li class="opt"><span class="small_gray">(000-000) \' - \' 하이픈 포함 입력</span></li>');
	$form->addEnd(1);

	$form->addStart('사업장 주소', 'address', 1);
	$form->add('textarea', 'address', $cfg['site']['address'], 'width:300px; height:30px;','maxlength="50"');
	$form->addHtml('<li class="opt"><span class="small_gray">번지까지 상세히 입력</span></li>');
	$form->addEnd(1);

	$form->addStart('사이트 생성일', 'regyear', 1);
	$form->add('date', 'reg', $cfg['site']['dateReg'], 'width:300px;');
	$form->addHtml('<li class="opt"><span class="small_gray">약관 및 기타 정보에 노출</span></li>');
	$form->addEnd(1);

	$form->addStart('최근 변경 정보', '', 1);
	$form->addHtml('<li class="opt"><span><strong>TIME:</strong> '.str_replace("|", " <strong>IP:</strong> ", $cfg['site']['info']).'</span></li>');
	$form->addEnd(1);
	?>
</table>
<div class="pd5 center"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit" onclick="return $.submit(this.form)">위 설정으로 적용</button></span></div>
</form>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    $('#frm01').validate({onkeyup:function(element){$(element).valid();}});
    $("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
//]]>
</script>
<?php include "../include/commonScript.php"; ?>
