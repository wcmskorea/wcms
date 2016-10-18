<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }

/* ['0']관리, ['1']접근, ['2']열람권한, ['3']작성 */
if($member->checkPerm('3') === false) { $func->err("신청할 권한이 없습니다. 회원가입 후 이용해주시기 바랍니다.", "back"); }

/**
 * 입력 옵션 설정병합
 */

$cfg['module'] = @array_merge($cfg['module'], (array)unserialize($cfg['module']['contentAdd']));

/* 입력항목 옵션 체크 */
$division = explode(",", $cfg['module']['division']);

if($_POST['sh'] && $_POST['shName'] && $_POST['shKeyword'])
{
	$_POST['shSubject'] = str_replace("-",null,$_POST['shKeyword']);
	switch($_POST['sh'])
	{
		case "mobile" : $sq = " AND mobile='".$_POST['shSubject']."'"; break;
		case "phone"  : $sq = " AND phone='".$_POST['shSubject']."'";  break;
		case "email"  : $sq = " AND email='".$_POST['shKeyword']."'";  break;
		default       : $sq = " AND name='".$_POST['shKeyword']."'";   break;
	}
	$query	= "SELECT * FROM `mdApp01__content` WHERE cate='".__CATE__."' AND name='".$_POST['shName']."'".$sq;
	$Rows	= $db->queryFetch($query);

	if($db->getNumRows() < 1)
	{
		$func->err("입력한 정보와 일치하는 정보가 존재하지 않습니다.", "window.history.back()");
	} else
	{
		$contentAdd = (array)unserialize($Rows['contentAdd']); //변동이 되는 부분을 contentAdd 필드에 배열로 들어가 있음
		switch($Rows['state'])
		{
			case '0'  : $state = '<span class="blue">신청(접수)</span>'; break;
			case '1'  : $state = '<span class="green">확인(진행)</span>'; break;
			case '2'  : $state = '<span class="red">완료(종료)</span>'; break;
			default   : $state = '<span class="blue">신청(접수)</span>'; break;
		}
	}
} else if($_SESSION['uid'])
{
	$Rows = $db->queryFetch(" SELECT * FROM `mdMember__account` AS A,`mdMember__info` AS B WHERE A.id=B.id AND B.id='".$_SESSION['uid']."' ");
}
?>
<div class="boardInput">

<form id="bbsform" name="bbsform" method="post" action="<?php echo($_SERVER['PHP_SELF']);?>" enctype="multipart/form-data">
<input type="hidden" name="type" value="<?php echo($sess->encode('inputPost'));?>" />
<input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />
<input type="hidden" name="menu" value="<?php echo($menu);?>" />
<input type="hidden" name="sub" value="<?php echo($sub);?>" />
<input type="hidden" name="idx" value="<?php echo($Rows['seq']);?>" />
<input type="hidden" name="fileName" value="" />
<input type="hidden" name="fileCount" value="<?php echo($Rows['file']);?>" />

	<?php
	if($cfg['module']['opt_agreement'] != 'N')
	{
		$privacy  = @file_get_contents(__HOME__."/cache/document/000999003001.html");
		echo('<div class="agreement">
		<h3 class="hide">개인정보취급방침 안내</h3>
		<div class="frame textContent pd10 bg_gray" style="height:100px; font-size:11px;">'.stripslashes(str_replace("본 웹사이트", "<strong>".$cfg['site']['siteName']."</strong>", $privacy)).'</div>
		<div class="pd5"><span class="colorRed"><input type="checkbox" id="agree2" name="agree2" class="input_check required" title="개인정보 수집 및 취급방침에 동의는 필수선택 입니다." value="Y" />&nbsp;<label for="agree2">개인정보 수집 및 취급방침에 동의 합니다.</label></span></div>
		</div><!-- .agreement end -->').PHP_EOL;
	}
	?>
	<br />
	<p class="right colorGray pd5">(<span class="colorRed" title="필수입력항목">*</span>)나 하늘색 입력항목은 필수 입력항목입니다.</p>
	<table class="table_basic" summary="회원가입을 위한 개인정보 입력 항목" style="width:100%;">
	<caption>신청내용 입력항목</caption>
	<col width="100">
	<col>
	<?php
	$form = new Form('table');

	if($cfg['module']['opt_division'] != 'N') {
		$division = explode(",", $cfg['module']['division']);
		$form->addStart('신청구분', 'division', 1, 0, 'M');
		$form->add('select', $division,$Rows['division'], 'width:123px;');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_name'] != 'N') {
		$form->addStart('신청자명', 'name', 1, 0, 'M');
		$form->add('input', 'name', $contentAdd['name'], 'width:120px; ime-mode:active');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_mobile'] != 'N') {
		$form->addStart('휴대전화', 'mobile', 1, 0 , $cfg['module']['opt_mobile']);
		$form->add('input', 'mobile', $contentAdd['mobile'], 'width:120px;', 'mobile="true" maxlength="14"');
		$form->addHtml('<li class="opt"><span class="small_gray"> (예: 010-123-1234)</span></li>');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_phone'] != 'N') {
		$form->addStart('전화번호', 'phone', 1, 0 , $cfg['module']['opt_phone']);
		$form->add('input', 'phone', $contentAdd['phone'], 'width:120px;', 'phone="true" maxlength="14"');
		$form->addHtml('<li class="opt"><span class="small_gray"> (예: 02-123-1234)</span></li>');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_email'] != 'N') {
		$form->addStart('이메일', 'email', 1, 0 , $cfg['module']['opt_email']);
		$form->add('input', 'email', $contentAdd['email'], 'width:230px; ime-mode:disabled', 'email="true" maxlength="50"');
		$form->addHtml('<li class="opt"><span id="checkEmail" class="small_orange"></span></li>');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_address'] != 'N') {
		$form->addStart('우편번호', 'zipcode', 1, 0 , $cfg['module']['opt_address']);
		$form->add('input', 'zipcode', $Rows['zipcode'], 'width:120px;', 'zipno="true" maxlength="7"');
		$form->addHtml('<li class="opt"><span><a href="javascript:;" onclick="$.dialog(\''.$cfg['droot'].'modules/mdMember/widgets/checkZip.php?'.__PARM__.'&amp;target=1&amp;type='.$sess->encode('checkZip').'\',null,450,305);" class="btnPack gray small" title="새창으로 주소검색창을 띄웁니다"><span>주소검색</span></a></span></li>');
		$form->addEnd(1);
		$form->addStart('주소1', 'address01', 1, 0 , $cfg['module']['opt_address']);
		$form->add('input', 'address01', $Rows['addr01'], 'width:330px; ime-mode:active','maxlength="30"');
		$form->addEnd(1);
		$form->addStart('주소2', 'address02', 1, 0 , $cfg['module']['opt_address']);
		$form->add('input', 'address02', $Rows['addr02'], 'width:330px; ime-mode:active','maxlength="30"');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_schedule'] != 'N') {
		if($contentAdd['scheduleyear'] && $contentAdd['schedulemonth'] && $contentAdd['scheduleday']){
			$schedule = mktime($contentAdd['schedulehour'], $contentAdd['schedulemin'], $contentAdd['schedulesec'], $contentAdd['schedulemonth'], $contentAdd['scheduleday'], $contentAdd['scheduleyear']);
		}else{
			$schedule = time();
		}
		$form->addStart('예약일정', 'scheduleyear', 1, 0 , $cfg['module']['opt_schedule']);
		$form->add('date', 'schedule', $schedule);
		$form->addEnd(1);
	}

	/*for($i=1; $i<=20; $i++)
	{
		if($cfg['module']['opt_add'.$i] != 'N')
		{
			$opt			= $db->queryFetch(" SELECT * FROM `mdApp01__opt` WHERE cate='".__CATE__."' AND sort='".$i."' ");
			$addValue	= ($opt['addType'] != 'input') ? explode("|", $opt['addContent']) : 'addContent'.$i;
			$width		= ($opt['addType'] == 'input' && $opt['addContent']) ? $opt['addContent'] : 230;
			$contentAddValue = $opt['addType']=='checkboxs' ? ($contentAdd['addContent'.$i] ? explode('|',$contentAdd['addContent'.$i]) : array()) : $contentAdd['addContent'.$i];

			$form->addStart($opt['addName'], 'addContent'.$i, 1, 0 , $cfg['module']['opt_add'.$i]);
			$form->add($opt['addType'], $addValue, $contentAddValue, 'width:'.$width.'px;');
			if($opt['addEx'])
			{
				$form->addHtml('<li class="opt"><span class="small_gray">(예: '.$opt['addEx'].')</span></li>');
			}
			$form->addEnd(1);
		}
	}*/

	if($cfg['module']['opt_content'] != 'N') {
		$form->addStart('상세내용<br />(기타)', 'contents', 1, 0 , $cfg['module']['opt_content']);
		$form->add('textarea', 'content', stripslashes(nl2br($Rows['content'])), 'width:220px; height:100px;');
		$form->addEnd(1);
	}
	?>
	</table>

<?php
/*
 * 파일첨부
 */
include __PATH__."addon/system/upLoadFile.php";

if($cfg['module']['uploadCount'] > 0 && $cfg['module']['uploadType'] == 'Multi')
{
	$uploadSubmit = ' onclick="javascript:NfUpload.FileUpload();return false;"';
}
else
{
	$uploadSubmit = ' onclick="return $.submit(this.form);"';
}
	if(!$Rows || $Rows['state'] < 1)
	{
		if($Rows['state'] == '0')
		{
			echo('<div class="pd10 center"><span class="btnPack green medium strong"><button type="submit" onclick="return $.submit(this.form);">위 정보로 변경하기</button></span></div>');
		} else
		{

			echo('<div class="pd10 center"><span class="btnPack black medium strong"><button type="submit" '.$uploadSubmit.'>위 정보로 신청합니다</button></span>&nbsp;&nbsp;<a href="#none" onclick="$.dialog(\''.$cfg['droot'].'index.php\', \'&amp;'.__PARM__.'&amp;type=search&amp;mode=dialog\',400,165)" class="btnPack green medium strong"><span>신청내역 열람 및 변경신청</span></a></div>');
			if($cfg['module']['listing'] == 'List') {include __PATH__."modules/".$cfg['cate']['mode']."/inputList.php"; }
		}
	} else {
		if($Rows['contentAnswers'])
		{
			echo('<div class="pd10 center red"><strong>&lt;ㆍ답변 및 처리내용ㆍ&gt;</strong></div>
			<div class="cube">
			<div class="line">
				<p class="pd5 textContent"><strong>'.nl2br($contentAdd['contentAnswers']).'</strong></p>
			</div>
			</div>');
		} else
		{
			echo('<div class="cube"><div class="line"><p class="pd10 center strong">" '.$state.' 상태는 접수내용을 변경하실 수 없습니다. "</p></div></div>');
		}
	}

//	if($cfg['module']['listing'] == 'List') {
//		include __PATH__."/modules/".$cfg['cate']['mode']."/inputList.php";
//	}
	?>
</form>
</div>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$('#bbsform').validate({onkeyup:function(element){$(element).valid();}});
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
function submitForm(frm)
{
	$(frm).validate({
		onkeyup:false,
		onclick:false,
		onfocusout:false,
		showErrors: function(errorMap, errorList) {
			if (errorList && errorList[0]) {
			  alert(errorList[0].message);
			}
		}
	})
}
</script>