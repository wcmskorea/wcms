<?php
//if($_SESSION['ulevel'] == '1') $func->Err("시스템 관리자는 정보변경이 불가합니다.","history.back()");
//회원구분 설정
$division	= explode(",", $cfg['module']['division']);

//SSL설정
$posturl = ($cfg['site']['ssl']) ? 'https://'.$_SERVER['HTTP_HOST'].":".$cfg['site']['ssl'].$cfg['droot'].'index.php' : $_SERVER['PHP_SELF'];
if(isset($_SESSION['uid'])) {
	$Rows = $member->memberInfo($_SESSION['uid']);
} else {
	$func->err("시스템 관리자는 정보변경이 불가합니다.");
}

$func->setLog(__FILE__, "회원정보 열람");

/**
 * 입력 옵션 설정병합
 */
$cfg['module'] = @array_merge($cfg['module'], (array)unserialize($cfg['module']['contentAdd']));
?>
<div id="regist_wrap">
<form id="frm" name="login" method="post" action="<?php echo($posturl);?>" enctype="multipart/form-data">
<input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />
<input type="hidden" name="uri" value="<?php echo(__URI__);?>" />
<input type="hidden" name="type" value="<?php echo($sess->encode('modifyPost'));?>" />
<input type="hidden" name="memType" value="<?php echo($Rows['type']);?>" />
<?php
	include __PATH__."modules/mdMember/modifyBase.php";
?>
<p class="right gray pd5">(<span class="colorRed" title="필수입력항목">*</span>)나 하늘색 입력항목은 필수 입력항목입니다.</p>
<table class="table_basic" summary="개인정보 수정을 위한 입력 항목" style="width:100%;">
<caption>개인정보 수정 항목</caption>
<col width="140" />
<col />
	<?php
	$form = new Form('table');

	$form->addStart('현재 비밀번호', 'oldpasswd', 1, 0 ,'M');
	$form->add('input', 'oldpasswd', null, 'width:120px;','minlength="8" maxlength="16"');
	$form->addHtml('<li class="opt"><span class="small_gray">사용중인 비밀번호</span></li>');
	$form->addEnd(1);

	$form->addStart('비밀번호 변경', 'passwd', 1);
	$form->add('input', 'passwd', null, 'width:120px;', 'onblur="$.checkOverLap(\''.$sess->encode("checkUserPwd").'\',\'Pwd\');" minlength="8" maxlength="16"');
	$form->addHtml('<li class="opt"><span id="checkPwd" class="small_gray">변경시에만 입력하세요.</span></li>');
	$form->addEnd(1);

	$form->addStart('비밀번호 확인', 'repasswd', 1);
	$form->add('input', 'repasswd', null, 'width:120px;', 'match="passwd" minlength="8" maxlength="16"');
	$form->addHtml('<li class="opt"><span class="small_gray">변경할 비밀번호와 동일하게 입력하세요.</span></li>');
	$form->addEnd(1);

	if($cfg['module']['opt_nick'] != 'N') {
		$form->addStart('닉네임', 'nick', 1, 0 , $cfg['module']['opt_nick']);
		$form->add('input', 'nick', $Rows['nick'], 'width:120px;', 'onblur="$.checkOverLap(\''.$sess->encode("checkUserNick").'\',\'Nick\');" nick="true" minlength="3" maxlength="15"');
		$form->addHtml('<li class="opt"><span id="checkNick" class="small_orange"></span></li>');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_email'] != 'N') {
		$form->addStart('이메일', 'email', 1, 0 , $cfg['module']['opt_email']);
		$form->add('input', 'email', $Rows['email'], 'width:185px; ime-mode:disabled', 'onblur="$.checkOverLap(\''.$sess->encode("checkUserEmail").'\',\'Email\');" email="true" maxlength="50"');
		$form->addHtml('<li class="opt"><span id="checkEmail" class="small_orange"></span></li>');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_address'] != 'N') {
		$form->addStart('우편번호', 'zipcode', 1, 0 , $cfg['module']['opt_address']);
		$form->add('input', 'zipcode', $Rows['zipcode'], 'width:120px;', 'zipno="true" maxlength="7"');
		$form->addHtml('<li class="opt"><span><a href="javascript:;" onclick="$.dialog(\''.$cfg['droot'].'modules/mdMember/widgets/checkZip.php?'.__PARM__.'&amp;target=1&amp;type='.$sess->encode('checkZip').'\',null,450,305);" class="btnPack white small" title="새창으로 주소검색창을 띄웁니다"><span>주소검색</span></a></span></li>');
		$form->addEnd(1);
		$form->addStart('주소', 'address01', 1, 0 , $cfg['module']['opt_address']);
		$form->add('input', 'address01', $Rows['address01'], 'width:330px; ime-mode:active','maxlength="30"');
		$form->addEnd(1);
		$form->addStart('나머지 주소', 'address02', 1, 0 , $cfg['module']['opt_address']);
		$form->add('input', 'address02', $Rows['address02'], 'width:330px; ime-mode:active','maxlength="30"');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_mobile'] != 'N') {
		$form->addStart('휴대전화', 'mobile', 1, 0 , $cfg['module']['opt_mobile']);
		$form->add('input', 'mobile', $Rows['mobile'], 'width:120px;', 'onblur="$.checkOverLap(\''.$sess->encode("checkUserMobile").'\',\'Mobile\');" mobile="true" maxlength="14"');
		if($cfg['module']['opt_mobileAuth'] != 'N') {
			$form->addHtml('<li class="opt"><span class="btnPack white small"><button type="button" id="certSmsBtn">인증요청</button></span></li>');
		}
		$form->addHtml('<li class="opt"><span class="small_gray"> (예 : 010-000-0000)</span></li>');
		$form->addHtml('<li class="opt"><span id="checkMobile" class="small_orange"></span></li>');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_mobileAuth'] != 'N') {
		$form->addStart('인증코드', 'authcode', 1, 0 , 'M');
		$form->add('input', 'authcode', null, 'width:120px;', 'digits="true"');
		$form->addHtml('<li class="opt"><span class="small_gray">(휴대전화로 발송된 인증코드를 입력해주세요)</span></li>');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_phone'] != 'N') {
		$form->addStart('일반전화', 'phone', 1, 0 , $cfg['module']['opt_phone']);
		$form->add('input', 'phone', $Rows['phone'], 'width:120px;', 'phone="true" maxlength="14"');
		$form->addHtml('<li class="opt"><span class="small_gray"> (예 : 062-000-0000)</span></li>');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_office'] != 'N') {
		$form->addStart('사무실 전화번호', 'office', 1, 0 , $cfg['module']['opt_office']);
		$form->add('input', 'office', $Rows['office'], 'width:120px;', 'phone="true" maxlength="14"');
		$form->addHtml('<li class="opt"><span class="small_gray"> (예 : 062-000-0000)</span></li>');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_fax'] != 'N') {
		$form->addStart('팩스번호', 'fax', 1, 0 , $cfg['module']['opt_fax']);
		$form->add('input', 'fax', $Rows['fax'], 'width:120px;', 'phone="true" maxlength="14"');
		$form->addHtml('<li class="opt"><span class="small_gray"> (예 : 062-000-0000)</span></li>');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_sex'] != 'N') {
		$form->addStart('성별', 'sex', 1, 0, $cfg['module']['opt_sex']);
		$form->add('radio', array('1'=>'남성','2'=>'여성'), $Rows['sex'], 'color:black;');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_birth'] != 'N') {
		$form->addStart('생년월일', 'birthyear', 1, 0 , $cfg['module']['opt_birth']);
		$form->add('birthDay', 'birth', $Rows['birth']);
		$form->id = 'birthType';
		$form->add('select', array('S'=>'양력','L'=>'음력'), $Rows['birthType'], 'color:black;');
		if($cfg['member']['birth']) { $form->addHtml('<li class="opt"><span class="small_gray">('.$cfg['member']['birth'].')</span></li>'); }
		$form->addEnd(1);
	}

	if($cfg['module']['opt_memory'] != 'N') {
		$form->addStart('결혼 기념일', 'memoryyear', 1, 0 , $cfg['module']['opt_memory']);
		$form->add('date', 'memory', $Rows['membory']);
		if($cfg['member']['memory']) { $form->addHtml('<li class="opt"><span class="small_gray">('.$cfg['member']['memory'].')</span></li>'); }
		$form->addEnd(1);
	}

	if($cfg['module']['opt_receive'] != 'N') {
		$form->addStart('수신동의', 'receive', 1, 0 , $cfg['module']['opt_receive']);
		$form->add('checkbox', 'receive', 'Y', 'color:black;');
		$form->addHtml('<li class="opt"><span class="small_gray">('.($cfg['module']['opt_receiveContent'] ? $cfg['module']['opt_receiveContent'] : '이벤트 및 공지를 메일이나 문자로 수신합니다.').')</span></li>');	//설정된 수신동의 문구로 표시(2013-09-10)
		$form->addEnd(1);
	}

	if($cfg['module']['opt_groupName'] != 'N') {
		$form->addStart('회사(단체)명', 'groupName', 1, 0 , $cfg['module']['opt_groupName']);
		$form->add('input', 'groupName', $Rows['groupName'], 'width:330px;','maxlength="20"');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_groupNo'] != 'N') {
		$form->addStart('등록(사업자)번호', 'groupNo', 1, 0 , $cfg['module']['opt_groupNo']);
		$form->add('input', 'groupNo', $Rows['groupNo'], 'width:330px;','bizno="true"');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_ceo'] != 'N') {
		$form->addStart('대표(담당)자명', 'ceo', 1, 0 , $cfg['module']['opt_ceo']);
		$form->add('input', 'ceo', $Rows['ceo'], 'width:330px; ime-mode:active','korean="true" maxlength="6"');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_status'] == 'M') {
		$form->addStart('업태', 'status', 1, 0 , $cfg['module']['opt_status']);
		$form->add('input', 'status', $Rows['status'], 'width:330px;','maxlength="16"');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_class'] == 'M') {
		$form->addStart('업종', 'class', 1, 0 , $cfg['module']['opt_class']);
		$form->add('input', 'class', $Rows['class'], 'width:330px;','maxlength="16"');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_content'] != 'N') {
		$form->addStart('자기소개', 'content', 1, 0 , $cfg['module']['opt_content']);
		$form->add('textarea', 'content', $Rows['content'], 'width:330px; height:100px;');
		$form->addEnd(1);
	}
	?>
	<!--<tr>
		<th scope="row"><label for="upfile"><strong>파일첨부</strong></label></th>
		<td><ol><li class="opt"><input type="file" id="upfile" name="upfile" style="width:330px; height:18px;" class="input_white" /></li>
			<?php
			for($i=1; $i<$cfg['module']['uploadCount']; $i++)
			{
				echo('<li class="opt"><input type="file" name="upfile'.$i.'" style="height:18px;" class="input_white" /></li>');
			}
			?></ol>
		</td>
	</tr>-->
	</table>
	<div class="pd5 center"><span class="btnPack black large strong"><button type="submit" onclick="return $.submit(this.form)"><?php echo($lang['member']['modify']);?></button></span></div>
	</form>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$('#frm').validate({onkeyup:function(element){$(element).valid();}});
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
//]]>
$(function(){
	$("input:radio[@name=religion]").click(function() {
		if($(this).val() == "9"){
				$("input[name=religion_etc]").attr("disabled",false);
		}else{
				$("input[name=religion_etc]").val("").attr("disabled","disabled");
		}
	});
});
</script>