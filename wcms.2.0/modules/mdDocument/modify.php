<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }

/**
 * 입력 옵션 설정병합
 */
$cfg['module'] = @array_merge($cfg['module'], (array)unserialize($cfg['module']['contentAdd']));

/**
 * 인증 처리
 */
if(!$_SESSION['docSecret'])
{
	$Rows = $db->queryFetch(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE seq='".$_GET['num']."' ");
	if($db->getNumRows() < 1) { $func->err($lang['doc']['notfound']); }
	if(($Rows['id'] != $_SESSION['uid'] || !$_SESSION['uid']) && !$member->checkPerm('0')) { $func->err($lang['doc']['notperm'], "back"); }

}
else
{
	$Rows = $db->queryFetch(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE seq='".$_GET['num']."' AND passwd='".$db->passType($cfg['site']['encrypt'], $_SESSION['docSecret'])."' ");
	if($db->getNumRows() < 1) { $func->err($lang['doc']['notmatch']); }
}

$Rows['writer']		= ($Rows['writer']) ? stripslashes($Rows['writer']) : $_SESSION['uname'];
$Rows['subject'] 	= stripslashes($Rows['subject']);
$Rows['subject'] 	= htmlspecialchars($Rows['subject']);
if($cfg['module']['division'] && $cfg['module']['opt_division'] != 'N')
{
	@preg_match_all("/\[(.*?)\]/", $Rows['subject'] ,$division);
	$Rows['subject']= trim(preg_replace("/\[(.*?)\]/","", $Rows['subject']));
}
$Rows['content'] 	= stripslashes($Rows['content']);
$Rows['content'] 	= htmlspecialchars($Rows['content']);
$contentAdd				= explode("|", $Rows['contentAdd']);
?>

<div class="docInput">
<form id="bbsform" name="bbsform" method="post" action="<?php echo($_SERVER['PHP_SELF']);?>" enctype="multipart/form-data">
<input type="hidden" name="type" value="<?php echo($sess->encode('modifyPost'));?>" />
<input type="hidden" name="cate" value="<?php echo($Rows['cate']);?>" />
<input type="hidden" name="mode" value="<?php echo($_GET['mode']);?>" />
<input type="hidden" name="num" value="<?php echo($Rows['seq']);?>" />
<input type="hidden" name="trashed" value="<?php echo($Rows['trashed']);?>" />
<input type="hidden" name="fileName" value="" />
<input type="hidden" name="fileCount" value="<?php echo($Rows['fileCount']);?>" />
<input type="hidden" name="currentPage" value="<?php echo($currentPage);?>" />
<input type="hidden" name="boardType" value="<?php echo($Rows['boardType']);?>" />
<input type="hidden" name="regDate" value="<?php echo($Rows['regDate']);?>" />
<?php
if($Rows['productSeq'] != 0){
?>
<input type="hidden" name="productSeq" value="<?php echo($Rows['productSeq']);?>" />
<?php
}
?>
<?php
if($cfg['module']['list'] != 'Page')
{
	echo('<table class="table_basic" summary="게시물 수정양식 입니다." style="width:100%;">
	<caption>게시물 답변하기</caption>
	<col width="17%">
	<col width="33%">
	<col width="17%">
	<col width="33%">');

	//카테고리 선택
	if($cfg['module']['listUnion'] == 'Y')
	{
		echo('<tr>
			<th scop="row"><label for="subject" class="required"><span class="red" title="필수입력항목">*</span><strong>등 록 위 치</strong></label></th>
			<td colspan="3"><ol><li class="opt"><select name="move" class="bg_gray">');
			$db->query(" SELECT A.cate AS cate,B.name AS name FROM `".$cfg['cate']['mode']."__` AS A INNER JOIN `site__` AS B ON A.cate=B.cate AND SUBSTRING(A.cate,1,".strlen($cfg['module']['cate']).")='".$cfg['module']['cate']."' AND A.listUnion<>'Y' ORDER BY A.cate ASC ");
			while($sRows = $db->fetch())
			{
				echo('<option value="'.$sRows['cate'].'"');
				if($Rows['cate'] == $sRows['cate']) { echo(' selected="selected" style="color:#990000;"'); }
				echo('>'.$sRows['name'].'</option>');
			}
		echo('</select></li></ol></td></tr>');
	}

	$form = new Form('table');

	//카테고리 설정
	if($cfg['module']['category'] && $cfg['module']['opt_category'] != 'N')
	{
		$cateClass = explode(",", $cfg['module']['category']);
		$form->addStart('카테고리', 'category', 3, 0, $cfg['module']['opt_category']);
		$form->add('selectValue', $cateClass, $Rows['category'], 'color:black;');
		$form->addEnd(1);
	}
	/**
	 * 말머리 설정
	 */
	if($cfg['module']['division'] && $cfg['module']['opt_division'] != 'N') {
		$class = explode(",", $cfg['module']['division']);
		$form->addStart('구 분', 'division', 3);
		$form->add('radio', $class, array_search($division[1][0], $class), 'color:black;');
		$form->addEnd(1);
	}
	if($cfg['module']['opt_subject'] != 'N') {
		$form->addStart('제 목', 'subject', 3, 0, $cfg['module']['opt_subject']);
		$form->add('input', 'subject', $Rows['subject'], 'width:99%;');
		$form->addEnd(1);
	}
	if($cfg['module']['opt_writer'] != 'N') {
		$form->addStart('작 성 자', 'writer', 1, 0, $cfg['module']['opt_writer']);
		$form->add('input', 'writer', $Rows['writer'], 'width:150px;');
		$form->addEnd();
	}
	if($cfg['module']['opt_email'] != 'N') {
		$form->addStart('이 메 일', 'email', 0, 0, $cfg['module']['opt_email']);
		$form->add('input', 'email', $Rows['email'], 'width:150px;', 'email="true"');
		$form->addEnd(1);
	}
	if($cfg['module']['opt_phone'] != 'N') {
		if(!$_SESSION['uid']) {
			$form->addStart('전 화 번 호', 'phone', 1, 0, $cfg['module']['opt_phone']);
			$form->add('input', 'phone', $Rows['phone'], 'width:150px;', 'phone="true"');
			$form->addEnd(0);
		} else {
			$form->addStart('전 화 번 호', 'phone', 3, 0, $cfg['module']['opt_phone']);
			$form->add('input', 'phone', $Rows['phone'], 'width:150px;', 'phone="true"');
			$form->addEnd(1);
		}
	}
	if($cfg['module']['opt_url'] != 'N')
	{
		$form->addStart('링크 URL', 'url', 3, 0, $cfg['module']['opt_url']);
		$form->add('input', 'url', $Rows['url'], 'width:99%;');
		$form->addEnd(1);
	}
	if(!$_SESSION['uid']) {
		if($cfg['module']['opt_phone'] != 'N') {
			$form->addStart('비 밀 번 호', 'passwd', 0, 0, 'M');
			$form->add('input', 'passwd', "", 'width:150px;');
			$form->addEnd(1);
		} else {
			$form->addStart('비 밀 번 호', 'passwd', 3, 0, 'M');
			$form->add('input', 'passwd', "", 'width:150px;');
			$form->addEnd(1);
		}
	}
	/* 추가입력사항 : addContent */
	if($cfg['module']['addContent'])
	{
		$addOpt = explode(",", $cfg['module']['addContent']);
		foreach($addOpt AS $key=>$val)
		{
			$form->addStart($val, 'addopt['.$key.']', 3);
			$form->add('input', 'addopt['.$key.']', $contentAdd[$key], 'width:99%;');
			$form->addEnd(1);
		}
	}
	echo('</tbody>
	</table>'.PHP_EOL);

	//table -> element로 형태변환
	$form->type = 'element';

	//공지 및 비밀글 옵션
	echo('<div class="docOpt"><div class="secret">');
	$form->addStart('비밀글', 'useSecret', 1, 0, 'Y');
	$form->add('checkbox', 'useSecret', $Rows['useSecret'], 'font-weight:bold;');
	$form->addEnd();
	echo('</div>');
	if($member->checkPerm('0'))
	{
		switch($cfg['module']['list'])
		{
			case "List": default: $notice = "공지글"; break;
			case "Cal": $notice = "중요일정"; break;
			case "Faq": $notice = "공통질문"; break;
		}
		echo('<div class="notice">');
		$form->addStart($notice, 'useNotice', 1, 0, 'Y');
		$form->add('checkbox', 'useNotice', $Rows['useNotice'], 'font-weight:bold;');
		$form->addEnd();
		echo('</div>');
	}
	echo('<div class="clear"></div></div>').PHP_EOL;
}

//상세내용
if($cfg['module']['opt_content'] != 'N') { include __PATH__."addon/editor/editor.php"; }

//첨부파일
include __PATH__."addon/system/upLoadFile.php";

if($member->checkPerm('0') === true)
{
	if($cfg['module']['list'] == 'Page') { $form = new Form('element'); } else { $form->type = 'element'; }
	echo('<div class="cube" id="dateSelector"><div class="line">');

	if(preg_match('/Cal|Forum/', $cfg['module']['list']))
	{
		$form->addStart('일정 시작일', 'reyear', 3, 0, "Y");
		$form->addHtml('<span class="keeping"><input type="checkbox" id="redate" name="redate" class="input_check active" value="Y" /><label for="redate" class="bold">시작일 변경</label></span>&nbsp;');
		$form->add('datemin', 're', $Rows['regDate']);
		$form->addHtml('&nbsp;<span>부터</span>&nbsp;<span class="small_gray">(미선택시 금일 날짜로 등록)</span><br />');
		$form->addEnd();
		$form->addStart('일정 종료일', 'reyear', 3, 0, "Y");
		$form->addHtml('<span class="keeping"><input type="checkbox" id="endate" name="endate" class="input_check active" value="Y" /><label for="endate" class="bold">종료일 변경</label></span>&nbsp;');
		$form->add('datemin', 'en', $Rows['endDate']);
		$form->addHtml('&nbsp;<span>까지</span>&nbsp;<span class="small_gray">(미선택시 시작일 날짜로 등록)</span>');
		$form->addEnd();
	}
	else
	{
		$form->addStart('작　성　일', 'reyear', 3, 0, "Y");
		$form->addHtml('<span class="keeping"><input type="checkbox" id="redate" name="redate" class="input_check active" value="Y" /><label for="redate" class="bold">작성일 변경</label></span>&nbsp;');
		$form->add('datetime', 're', $Rows['regDate']);
		$form->addHtml('&nbsp;<span class="small_gray">(앞쪽 체크박스를 선택하면 자동 적용됩니다)</span>');
		$form->addEnd();
	}

	echo('</div></div>');
}
?>
<div class="center pd5"><span class="btnPack black medium strong"><button type="submit"<?php if($cfg['module']['uploadCount'] > 0 && $cfg['module']['uploadType'] == 'Multi'){echo(' onclick="javascript:NfUpload.FileUpload();"');}else{echo(' onclick="submitForm(this.form);"');}?>><?php echo($lang['doc']['modifySubmit']);?></button></span>&nbsp;<span class="btnPack gray medium"><a href="<?php echo($_SERVER['PHP_SELF']);?>?<?php echo(__PARM__);?>&type=view&num=<?php echo($_GET['num'].$_POST['num']);?>" class="button bgray"><?php echo($lang['doc']['cancel']);?></a></span></div>
</form>
</div>
<script language="javascript" type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$('#bbsform').validate({onkeyup:function(element){$(element).valid();}});
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
jQuery(function($)
{
	$("#redate").click(function()
	{
		if($("#redate:checked").length > 0)
		{
			var thisDates = new Date();
			alert(thisDates.getDay());
			$("#reyear").val(thisDates.getFullYear()).css('color','red');
			$("#remonth").val(thisDates.getMonth()+1).css('color','red');
			$("#reday").val(thisDates.getDay()).css('color','red');
			$("#rehour").val(thisDates.getHours()).css('color','red');
			$("#remin").val('00').css('color','red');
			$("#resec").val('0').css('color','red');
		}
		else
		{
			$("#reyear option:first").attr("selected", true).css('color','black');
			$("#remonth option:first").attr("selected", true).css('color','black');
			$("#reday option:first").attr("selected", true).css('color','black');
			$("#rehour option:first").attr("selected", true).css('color','black');
			$("#remin option:first").attr("selected", true).css('color','black');
			$("#resec option:first").attr("selected", true).css('color','black');
		}
	});
	$("#endate").click(function()
	{
		if($("#endate:checked").length > 0)
		{
			var thisDate = new Date();

			$("#enyear").val(thisDate.getFullYear()).css('color','red');
			$("#enmonth").val(thisDate.getMonth()+1).css('color','red');
			$("#enday").val(thisDate.getDay()).css('color','red');
			$("#enhour").val(thisDate.getHours()).css('color','red');
			$("#enmin").val(thisDate.getMinutes()).css('color','red');
			$("#ensec").val(thisDate.getSeconds()).css('color','red');
		}
		else
		{
			$("#enyear option:first").attr("selected", true).css('color','black');
			$("#enmonth option:first").attr("selected", true).css('color','black');
			$("#enday option:first").attr("selected", true).css('color','black');
			$("#enhour option:first").attr("selected", true).css('color','black');
			$("#enmin option:first").attr("selected", true).css('color','black');
			$("#ensec option:first").attr("selected", true).css('color','black');
		}
	});
});
//]]>
</script>
