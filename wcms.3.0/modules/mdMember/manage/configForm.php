<?php
require_once "../../../_config.php";
require_once __PATH__."/_Admin/include/commonHeader.php";

if($_POST['type'] == "cateModPost")
{
	$func->checkRefer("POST");

	//넘어온 값과 변수 동기화 및 validCheck
	foreach($_POST AS $key=>$val)
	{
		$db->data['contentAdd'][$key] = trim($val);
	}
	unset($db->data['contentAdd']['cate'], $db->data['contentAdd']['type']);
	$db->data['contentAdd'] = serialize($db->data['contentAdd']);

	$db->sqlUpdate("mdMember__", "cate IN ('000002001','000002002','000002003')", array(), 0);
	$func->err("회원·고객 모듈 (입력항목 설정)이 정상적으로 적용되었습니다.", "parent.$.dialogRemove();");
}
else
{
	$func->checkRefer("GET");

	if(defined('__CATE__'))
	{
		$Rows = $db->queryFetch(" SELECT * FROM `mdMember__` WHERE cate='000002002' ");
		$config = unserialize($Rows['config']);
		$content = unserialize($Rows['contentAdd']);
		$content['cate'] = $Rows['cate'];

		$content['opt_receiveContent'] =  $content['opt_receiveContent'] != "" ? $content['opt_receiveContent'] : "이벤트 및 공지를 메일이나 문자로 수신합니다." ;
	}
	else
	{
		$func->ajaxMsg("[".__CATE__."]카테고리 정보가 존재하지 않습니다.","", 100);
	}
}
?>
<div id="messageBox"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/delete.gif" /></spna>&nbsp;<strong>Message :</strong>&nbsp;&nbsp;&nbsp;</div>
<fieldset id="help">
<legend> < TIP's > </legend>
<ul>
	<li>본 페이지는 회원모듈에서 입력받을 입력항목을 관리합니다.</li>
    <li>메뉴얼 및 우측 도움말을 확인하시어 신중히 설정하시기 바랍니다.</li>
</ul>
</fieldset>
<form id="frmCate" name="frmCate" method="post" action="<?php echo($_SERVER['PHP_SELF']);?>"	enctype="multipart/form-data" target="hdFrame">
<input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />
<input type="hidden" name="type" value="cateModPost" />
<input type="hidden" name="data" value="<?php echo($Rows['data']);?>" />

<table class="table_list" style="width:100%;">
<colgroup>
    <col width="140">
    <col width="400">
    <col>
</colgroup>
	<thead>
		<tr>
			<th class="first"><p class="center"><span class="mg2">항 목</span></p></th>
			<th><p class="center"><span class="mg2">기본정보 설정</span></p></th>
			<th><p class="center"><span class="mg2">도움말</span></p></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$form = new Form('table');
    
	$form->addStart('약관동의', 'opt_agreement', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_agreement'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>약관 및 개인정보취급동의 선택 여부</span></td>');
	$form->addEnd(1);

	if($config['group'] != "") {
		$form->addStart('회원그룹', 'opt_group', 1, 0, 'M');
		$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_group'], 'color:black;');
		$form->addEnd(0);
		$form->addHtml('<td class="small_gray bg_gray"><span>등록시 회원그룹의 사용자 선택 여부</span></td>');
		$form->addEnd(1);	
	}

	/*$form->addStart('주민번호', 'opt_idcode', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_idcode'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>주민등록번호 입력 여부</span></td>');
	$form->addEnd(1);*/

	$form->addStart('닉네임', 'opt_nick', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_nick'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>등록자 닉네임 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('이메일', 'opt_email', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_email'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>이메일 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('주소', 'opt_address', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_address'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>주소(우편번호,주소,상세주소) 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('휴대전화', 'opt_mobile', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_mobile'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>휴대전화번호 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('휴대폰 본인인증', 'opt_mobileAuth', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_mobileAuth'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>휴대전화를 통한 본인인증 입력여부</span></td>');
	$form->addEnd(1);

	$form->addStart('일반전화', 'opt_phone', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_phone'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>전화번호 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('사무실전화', 'opt_office', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_office'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>사무실 전화번호 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('팩스번호', 'opt_fax', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_fax'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>팩스번호 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('성별', 'opt_sex', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_sex'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>성별 선택 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('생년월일', 'opt_birth', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_birth'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>생년월일 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('결혼 기념일', 'opt_memory', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_memory'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>기념일 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('수신동의', 'opt_receive', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_receive'], 'color:black;','maxlength="50"');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>소식지 및 공지알림 수신 선택 여부</span></td>');
	$form->addEnd(1);

	//수신동의 문구 설정 추가(2013-09-10)
	$form->addStart('수신동의 문구', 'opt_receiveContent', 1);
	$form->add("input", 'opt_receiveContent', $content['opt_receiveContent'], 'width:384px; text-align:left;','maxlength="30"');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>수신동의 옆에 들어갈 문구를 입력합니다.</span></td>');
	$form->addEnd(1);

	$form->addStart('회사(단체)명', 'opt_groupName', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_groupName'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>회사(단체)명 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('사업자(등록)번호', 'opt_groupNo', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_groupNo'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>사업자 및 각종 등록번호 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('대표자명', 'opt_ceo', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_ceo'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>대표(담당)자 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('업태', 'opt_status', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_status'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>업태 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('업종', 'opt_class', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_class'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>업종 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('자기소개', 'opt_content', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_content'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>자기소개 입력정보 입력 여부</span></td>');
	$form->addEnd(1);

	?>
	</tbody>
</table>
<div class="pd5 center"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit">위 설정으로 적용</button></span></div>
</form>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$('#frmCate').validate({onkeyup:function(element){$(element).valid();}});
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
//]]>
</script>
<?php
/* 공통 스크립트 */
require_once __PATH__."_Admin/include/commonScript.php";
?>
