<?php
require_once "../../../_config.php";
require_once __PATH__."/_Admin/include/commonHeader.php";

if($_POST['type'] == "cateModPost")
{
	$func->checkRefer("POST");

	//넘어온 값과 변수 동기화 및 validCheck
	foreach($_POST AS $key=>$val)
	{
		${$key} = trim($val);
	}

	$division  	= preg_replace("/,$/", '', $division);
	if($division != "") { $db->data['config']['division'] = $division;}
	$result  	= preg_replace("/,$/", '', $result);
	if($result != "") { $db->data['config']['result'] = $result;}
	$resultAdmin = preg_replace("/,$/", '', $resultAdmin);
	if($resultAdmin != "") { $db->data['config']['resultAdmin'] = $resultAdmin;}

	$db->data['skin'] = $skin;
	$db->data['cate'] = $cate;
	$db->data['config']['listing'] = $listing;
	$db->data['config']['uploadType'] = $uploadType;
	$db->data['config']['sms'] = $sms;
	$listCount = (!$listCount)? "10" : $listCount;
	$db->data['config']['listCount'] = '1,'.$listCount;
	$db->data['config']['limitDuple'] = $limitDuple;
	$db->data['config']['limitEntry'] = $limitEntry;
	$db->data['config']['limitTimeStart'] = $limitTimeStart;
	$db->data['config']['limitTimeEnd'] = $limitTimeEnd;
	$db->data['config']['uploadCount'] = $uploadCount;
	$db->data['config']['announce'] = $announce;
	$db->data['config'] = addslashes(serialize($db->data['config']));

	if($seted > 0)
	{
		$db->sqlUpdate("mdApp01__", "cate='".$db->data['cate']."'", array('cate','contentAdd'), 0);
	}
	else
	{
		$db->sqlInsert("mdApp01__", "REPLACE", 0);
	}

		//동일 리스트타입 일괄적용
	if($same == 'Y')
	{
		$db->sqlUpdate("mdApp01__", "config like '%s:7:\"listing\";s:4:\"".$db->data['config']['listing']."\"%'", array('cate'), 0);
	}

	if(!preg_match('/\/_Admin\//', $_SERVER['HTTP_REFERER']))
	{
		$func->errCfm("상담·문의 모듈 (환경설정)이 정상적으로 적용되었습니다.");
	}
	else
	{
		$func->err("상담·문의 모듈 (환경설정)이 정상적으로 적용되었습니다.");
	}


} else {
	$func->checkRefer("GET");
	if(__CATE__)
	{
		$Rows = $db->queryFetch(" SELECT * FROM `mdApp01__` WHERE cate='".__CATE__."' ");
		$config = unserialize($Rows['config']);
		$config['skin'] = ($_GET['skin']) ? $_GET['skin'] : $Rows['skin'];
		$config['listCount'] = ($config['listCount']) ? explode(",", $config['listCount']) : array("1","10");

	}
	else
	{
		$func->ajaxMsg("[".__CATE__."]카테고리 정보가 존재하지 않습니다.","", 100);
	}
}


?>
<div id="messageBox"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/delete.gif" /></spna>&nbsp;<strong>Message :</strong>&nbsp;&nbsp;&nbsp;</div>

<form id="frmCate" name="frmCate" method="post" enctype="multipart/form-data" target="hdFrame" action="<?php echo($_SERVER[PHP_SELF]);?>">
<input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />
<input type="hidden" name="skin" value="<?php echo($config['skin']);?>" />
<input type="hidden" name="seted" value="<?php echo($db->getNumRows());?>" />
<input type="hidden" name="type" value="cateModPost" />

<table class="table_list" style="width:100%;">
<col width="140">
<col>
<col>
	<thead>
		<tr>
			<th class="first"><p class="center"><span class="mg2">항 목<?php echo($config['skin']);?></span></p></th>
			<th><p class="center"><span class="mg2">기본정보 설정</span></p></th>
			<th><p class="center"><span class="mg2">도움말</span></p></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$form = new Form('table');

	$form->addStart('상담구분 설정', 'division', 1);
	$form->add('textarea', 'division', $config['division'], 'width:270px; height:32px;','maxlength="100"');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>상담 유형별 구분을 위한 설정</span><span class="small_blue">( 콤마[ , ]구분입력 )</span><br /><span>( ex : 전화상담,방문상담,Email상담,A/S요청,제품구매상담 )</span></td>');
	$form->addEnd(1);

	$form->addStart('처리구분 (사용자)', 'result', 1, 0, 'M');
	$form->add('textarea', 'result', $config['result'], 'width:270px; height:32px;','maxlength="100"');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>상담 처리별 구분을 위한 설정 (사용자 식별용)</span><span class="small_blue">( 콤마[ , ]구분입력 )</span><br /><span>( ex : 신청,접수,확인,진행,완료 등 )</span></td>');
	$form->addEnd(1);

	$form->addStart('처리구분 (운영자)', 'resultAdmin', 1);
	$form->add('textarea', 'resultAdmin', $config['resultAdmin'], 'width:270px; height:32px;','maxlength="100"');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>상담 처리별 구분을 위한 설정 (운영자 식별용)</span><span class="small_blue">( 콤마[ , ]구분입력 )</span><br /><span>( ex : 신청,접수,확인,진행,완료 등 )</span></td>');
	$form->addEnd(1);

	$form->addStart('안내멘트 설정', 'announce', 1);
	$form->add('textarea', 'announce', stripslashes($config['announce']), 'width:270px; height:32px;','maxlength="100"');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>상담신청란에 표기될 안내 메세지</span></td>');
	$form->addEnd(1);

	$form->addStart('리스트 형태', 'listing', 1, 0, 'M');
	$form->name = array('Form'=>'입력폼');
	$form->add('select', $form->name, $config['listing'], 'width:270px;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray">게시물 리스트 형태 설정</td>');
	$form->addEnd(1);

	$form->addStart('게시물 출력 (세로)', 'listCount', 1, 0, 'M');
	$form->add('input', 'listCount', $config['listCount'][1], 'width:30px; text-align:center;','digits="true" maxlength="2"');
	$form->addHtml('<li class="opt gray">개(행)</li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray">게시 목록의 세로 노출 갯수 설정 (ex: 기본 10행)</td>');
	$form->addEnd(1);

	$form->addStart('SMS발송 허용', 'sms', 1);
	$form->add('radio', array('N'=>'발송안함','M'=>'신청자만','O'=>'운영자만','B'=>'둘다발송'), $config['sms'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray">게시글 등록시 운영자에게 알림메세지 문자발송 여부 설정</td>');
	$form->addEnd(1);

	$form->addStart('첨부 파일갯수', 'uploadCount', 1);
	$form->add('select', array('0'=>'0개','1'=>'1개','3'=>'3개','5'=>'5개','10'=>'10개'), $config['uploadCount'], 'width:60px;');
	$form->addHtml('<li class="opt gray">개</li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray">첨부 가능갯수 설정 (자료실의 용도일 경우만 선택)</td>');
	$form->addEnd(1);

	$form->addStart('첨부 파일형태', 'uploadType', 1);
	$form->add('radio', array('Basic'=>'일반형', 'Multi'=>'멀티형'), $config['uploadType'], 'color:black;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">첨부 가능갯수 설정 (자료실의 용도일 경우만 선택)</td>');
	$form->addEnd(1);

	$form->addStart('일괄적용', 'same', 1);
	$form->add('checkbox', 'same', 'N', 'color:red;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray">같은 모듈 동일한 리스트 형태의 환경설정 일괄 적용</td>');
	$form->addEnd(1);
	?>
</tbody>
</table>
<div class="pd5 right"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit" onclick="return $.submit(this.form)">위 설정으로 적용</button></span>&nbsp;<span class="btnPack black medium"><a href="javascript:;" onclick="$.dialogRemove();">취소</a></span></div>
</form>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function() {
	$("input[type=text],input[type=password],textarea").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active");});
	$(".table_input").css('background','#fff');
	$('#frmCate').validate({onkeyup:function(element){$(element).valid();}});
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
//]]>
</script>
