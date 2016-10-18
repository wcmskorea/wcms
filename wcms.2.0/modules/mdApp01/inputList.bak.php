<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }

/* ['0']관리, ['1']접근, ['2']열람권한, ['3']작성 */
//if($member->checkPerm('3') === false) { $func->err("신청 권한이 없습니다. 로그인 후 이용해주시기 바랍니다.", "back"); }

/**
 * 입력 옵션 설정병합
 */
$cfg['module'] = @array_merge($cfg['module'], (array)unserialize($cfg['module']['contentAdd']));

/* 입력항목 옵션 체크 */
$division = explode(",", $cfg['module']['division']);
$result = explode(",", $cfg['module']['result']);

//등록시간 체크
if($cfg['module']['limitTimeStart'] && $cfg['module']['limitTimeEnd'])
{
	$am = ($cfg['module']['limitTimeStart'] < 13) ? "오전" : "오후";
	$pm = ($cfg['module']['limitTimeEnd'] < 13) ? "오전" : "오후";
	if(date('H') < $cfg['module']['limitTimeStart']) { $func->err("죄송합니다! 금일 접수는 [ ".$am." ".$cfg['module']['limitTimeStart']."시 ] 부터 신청하실 수 있습니다 ^^","back"); }
	if(date('H') >= $cfg['module']['limitTimeEnd']) { $func->err("죄송합니다! 금일 접수는 [ ".$pm." ".$cfg['module']['limitTimeEnd']."시 ] 까지 마감 되었습니다. 다음날 참여부탁드립니다 ^^","back"); }
}

if($_POST['sh'] && $_POST['shName'])
{
	$_POST['shSubject'] = str_replace("-",null,$_POST['shKeyword']);
	switch($_POST['sh'])
	{
		case "mobile" : $sq = " AND mobile='".$_POST['shKeyword']."'"; break;
		case "phone"  : $sq = " AND phone='".$_POST['shKeyword']."'";  break;
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
	}
} else if($_SESSION['uid'])
{
  $Rows = $db->queryFetch(" SELECT * FROM `mdMember__account` AS A,`mdMember__info` AS B WHERE A.id=B.id AND B.id='".$_SESSION['uid']."' ");
	$Rows['seq'] = null;
}

#--- 현재 상태별 건수정보
//$count = $db->queryFetch(" SELECT SUM(if(state='0',1,0)) AS stat0,SUM(if(state='1',1,0)) AS stat1,SUM(if(state='2',1,0)) AS stat2 FROM `mdApp01__content` WHERE cate='".__CATE__."' ");
?>
<div class="boardInput">
<!--
<table class="table_basic" summary="문의.상담을 위한 개인정보 입력 항목" style="width:100%;">
<caption>문의.상담 항목</caption>
<thead>
<tr><th class="first"><p class="center">신청(접수)</p></th>
    <th class="first"><p class="center">확인(진행)</p></th>
    <th class="first"><p class="center">완료(종료)</p></th>
</tr>
<tbody>
<tr><td><p class="center"><strong class="blue"><?php echo(number_format($count['stat0']));?> 건</strong></p></td>
    <td><p class="center"><strong class="green"><?php echo(number_format($count['stat1']));?> 건</strong></p></td>
    <td><p class="center"><strong class="red"><?php echo(number_format($count['stat2']));?> 건</strong></p></td>
</tr>
</tbody>
</table>
<br />
-->

<?php if($_SESSION['uid']) { ?>
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
		echo('<div class="agreement">');
		$privacy  = @file_get_contents(__HOME__."/cache/document/000999003001.html");
		echo('<h3 class="hide">개인정보취급방침 안내</h3>
		<div class="frame textContent pd10 bg_gray" style="height:100px;">'.stripslashes($privacy).'</div>
		<div class="pd5"><span class="colorBlue"><input type="checkbox" id="agree2" name="agree2" class="input_check required" title="개인정보 수집 및 취급방침에 동의는 필수선택 입니다." value="Y" />&nbsp;<label for="agree2">개인정보 수집 및 취급방침에 동의 합니다.</label></span></div>');
		echo('</div><!-- agreement end -->');
	}
	?>
	<br />
	<p class="right gray pd5">(<span class="colorRed" title="필수입력항목">*</span>)나 하늘색 입력항목은 필수 입력항목입니다.</p>
	<table class="table_basic" summary="회원가입을 위한 개인정보 입력 항목" style="width:100%;">
	<caption>회원가입 항목</caption>
	<col width="140" />
	<col />
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
		$form->add('input', 'name', $contentAdd['name'], 'width:120px;');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_email'] != 'N') {
		$form->addStart('이메일', 'email', 1, 0 , $cfg['module']['opt_email']);
		$form->add('input', 'email', $contentAdd['email'], 'width:185px;', 'opt="email"');
		$form->addHtml('<li class="opt"><span id="checkEmail" class="small_orange"></span></li>');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_mobileAuth'] && $cfg['module']['opt_mobileAuth'] != 'N') {
		$form->addStart('인증코드', 'authcode', 1, 0 , 'M');
		$form->add('input', 'authcode', null, 'width:120px;', 'opt="num"');
		$form->addHtml('<li class="opt"><span><a href="#none" onclick="$.message(\''.$cfg['droot'].'modules/mdMember/widgets/checkCertSms.php?&amp;'.__PARM__.'&amp;type='.$sess->encode('checkCert').'\');" class="btnPack white small"><span>인증요청</span></a></span><span class="small_gray">(휴대폰인증 클릭하여 인증 코드를 받으세요)</span></li>');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_mobile'] != 'N') {
		$form->addStart('휴대전화', 'mobile', 1, 0 , $cfg['module']['opt_mobile']);
		$form->add('input', 'mobile', $contentAdd['mobile'], 'width:120px;', 'opt="mobile"');
		$form->addHtml('<li class="opt"><span class="small_gray"> (예 : 010-1234-1234)</span></li>');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_phone'] != 'N') {
		$form->addStart('전화번호', 'phone', 1, 0 , $cfg['module']['opt_phone']);
		$form->add('input', 'phone', $contentAdd['phone'], 'width:120px;', 'opt="phone"');
		$form->addHtml('<li class="opt"><span class="small_gray"> (예 : 02-1234-1234)</span></li>');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_address'] && $cfg['module']['opt_address'] != 'N') {
		$form->addStart('우편번호', 'zipcode', 1, 0 , $cfg['module']['opt_address']);
		$form->add('input', 'zipcode', $Rows['zipcode'], 'width:120px;', 'onfocus="$.dialog(\''.$cfg['droot'].'modules/mdMember/widgets/checkZip.php?'.__PARM__.'&amp;type='.$sess->encode('checkZip').'\',null,450,305);" readonly="readonly"');
		$form->addHtml('<li class="opt"><span><a href="javascript:;" onclick="$.dialog(\''.$cfg['droot'].'modules/mdMember/widgets/checkZip.php?'.__PARM__.'&amp;target=1&amp;type='.$sess->encode('checkZip').'\',null,450,305);" class="btnPack white small" title="새창으로 주소검색창을 띄웁니다"><span>주소검색</span></a></span></li>');
		$form->addEnd(1);
		$form->addStart('주소1', 'address01', 1, 0 , $cfg['module']['opt_address']);
		$form->add('input', 'address01', $Rows['addr01'], 'width:330px;');
		$form->addEnd(1);
		$form->addStart('주소2', 'address02', 1, 0 , $cfg['module']['opt_address']);
		$form->add('input', 'address02', $Rows['addr02'], 'width:330px;');
		$form->addEnd(1);
	}

	if($cfg['module']['opt_schedule'] != 'N') {
		if($contentAdd['scheduleyear'] && $contentAdd['schedulemonth'] && $contentAdd['scheduleday']){
			$schedule = mktime($contentAdd['schedulehour'], $contentAdd['schedulemin'], $contentAdd['schedulesec'], $contentAdd['schedulemonth'], $contentAdd['scheduleday'], $contentAdd['scheduleyear']);
		}else{
			$schedule = time();
		}
		$form->addStart('예약일정', 'scheduleyear', 1, 0 , $cfg['module']['opt_schedule']);
		$form->add('datemin', 'schedule', $schedule);
		$form->addEnd(1);
	}

  if($cfg['module']['announce'])
  {
		$form->addStart('예약안내', 'scheduleend', 1);
		$form->addHtml('<li class="opt colorOrange">'.stripslashes($cfg['module']['announce']).'</li>');
		$form->addEnd(1);
  }

	for($i=1; $i<=20; $i++)
	{
		if($cfg['module']['opt_add'.$i] != 'N')
		{
			$opt = $db->queryFetch(" SELECT * FROM `mdApp01__opt` WHERE cate='".__CATE__."' AND sort='".$i."' ");
			$addValue = ($opt['addType'] != 'input') ? explode("|", $opt['addContent']) : 'addContent'.$i;
			$width		= ($opt['addType'] == 'input' && $opt['addContent']) ? $opt['addContent'] : 230;

			$form->addStart($opt['addName'], 'addContent'.$i, 1, 0 , $cfg['module']['opt_add'.$i]);
			$form->add($opt['addType'], $addValue, $contentAdd['addContent'.$i], 'width:'.$width.'px;');
			if($opt['addEx'])
			{
				$form->addHtml('<li class="opt"><span class="small_gray">( '.$opt['addEx'].' )</span></li>');
			}
			$form->addEnd(1);
		}
	}

	if($cfg['module']['opt_content'] != 'N') {
		$form->addStart('상세내용(기타)', 'contents', 1, 0 , $cfg['module']['opt_content']);
		$form->add('textarea', 'content', stripslashes(nl2br($Rows['content'])), 'width:330px; height:100px;');
		$form->addEnd(1);
	}
	?>
	</table>

<?php
/*
 * 파일첨부
 */
if($cfg['module']['uploadCount'] > 0)
{
	include __PATH__."addon/system/upLoadFile.php";

	if($cfg['module']['uploadType'] == 'Multi')
	{
		$uploadSubmit = ' onclick="javascript:NfUpload.FileUpload();return false;"';
	}
	else
	{
		$uploadSubmit = ' onclick="return $.submit(this.form)"';
	}
} else {
	$uploadSubmit = ' onclick="return $.submit(this.form)"';
}

if(!$Rows || $Rows['state'] < 1)
{
	if($Rows['state'] == '0')
	{
		echo('<div class="pd10 center"><span class="btnPack black medium strong"><button type="submit" onclick="return $.submit(this.form)">위 내용으로 변경하기</button></span></div>');
	} else {

		echo('<div class="pd10 center"><span class="btnPack black medium strong"><button type="submit" '.$uploadSubmit.'>위 정보로 신청합니다</button></span>&nbsp;&nbsp;<a href="#none" onclick="$.dialog(\''.$cfg['droot'].'index.php\', \'&amp;'.__PARM__.'&amp;type=search&amp;mode=dialog\',400,165)" class="btnPack white medium strong"><span>신청내역 확인 및 변경</span></a></div>');
		if($cfg['module']['listing'] == 'List') {include __PATH__."modules/".$cfg['cate']['mode']."/list.php"; }
	}
} else {
	//if($Rows['contentAnswers']) 
	//{
	//	echo('<div class="pd10 center red"><strong>&lt;ㆍ답변 및 처리내용ㆍ&gt;</strong></div>
	//	<div class="cube">
	//	<div class="line">
	//		<p class="pd5 textContent"><strong>'.nl2br($contentAdd['contentAnswers']).'</strong></p>
	//	</div>
	//	</div>');
	//} else {
		echo('<div class="cube"><div class="line"><p class="pd10 center strong colorOrange">" '.$result[$Rows['state']].' 상태는 접수내용을 변경하실 수 없습니다. "</p></div></div>');
	//}
}

//include __PATH__."/modules/".$cfg['cate']['mode']."/list.php";
?>
</form>
<?php 
} else {
	echo('<div class="pd10 center colorOrange strong">회원가입 후 신청하실 수 있습니다. <a href="/index.php?cate=000002002#module">회원가입 ▶ 바로가기</a></div>');
	if($cfg['module']['listing'] == 'List') {include __PATH__."modules/".$cfg['cate']['mode']."/list.php"; }
} ?>
</div>