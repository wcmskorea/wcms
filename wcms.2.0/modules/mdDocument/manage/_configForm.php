<?php
require_once "../../../_config.php";
require_once __PATH__."/_Admin/include/commonHeader.php";
require_once __PATH__."_Lib/classConfig.php";

if($_POST['type'] == "cateModPost")
{
	//넘어온 값과 변수 동기화 및 validCheck
	foreach($_POST AS $key=>$val)
	{
		$db->data['contentAdd'][$key] = trim($val);
	}
	unset($db->data['contentAdd']['cate'], $db->data['contentAdd']['type']);
	$db->data['contentAdd'] = serialize($db->data['contentAdd']);

	$db->sqlUpdate("mdDocument__", "cate='".__CATE__."'", array(), 0);

	//동일 카테고리 일괄적용
	if($_POST['sameUnder'] == 'Y' && strlen(__CATE__) > 3)
	{
		$db->sqlUpdate("mdDocument__", "cate like '".str_replace(substr(__CATE__, -3), null, __CATE__)."%'", array('cate'), 0);
	}

	$func->err("문서·게시물 모듈 (입력항목 설정)이 정상적으로 적용되었습니다.", "parent.$.dialogRemove();");
}
else
{
	if(defined('__CATE__'))
	{
		$Rows = $db->queryFetch(" SELECT * FROM `mdDocument__` WHERE cate='".__CATE__."' ");
		$content = unserialize($Rows['contentAdd']);
		$content['cate'] = $Rows['cate'];
	}
	else
	{
		$func->ajaxMsg("[".__CATE__."]카테고리 정보가 존재하지 않습니다.","", 100);
	}
}
?>
<form name="frmCate" method="post" enctype="multipart/form-data" target="hdFrame" action="<?php echo($_SERVER['PHP_SELF']);?>">
<input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />
<input type="hidden" name="type" value="cateModPost" />

<table class="table_list" style="width:100%;">
	<col width="120">
	<col>
	<col>
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

	$form->addStart('카테고리(구분)', 'opt_category', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_category'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>카테고리 구분 선택 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('말머리(구분)', 'opt_division', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_division'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>말머리 및 설정된 구분을  선택 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('제목', 'opt_subject', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_subject'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>제목 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('이름', 'opt_writer', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_writer'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>등록자 이름 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('이메일', 'opt_email', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_email'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>이메일 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('비밀번호', 'opt_passwd', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_passwd'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>운영자 작성시 비밀번호 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('연락처', 'opt_phone', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_phone'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>연락처 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('링크URL', 'opt_url', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_url'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>링크 URL 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('약관동의', 'opt_agreement', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_agreement'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>약관 및 개인정보취급동의 선택 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('일괄적용', 'sameUnder', 1);
	$form->add('checkbox', 'sameUnder', 'N', 'color:red;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">같은 모듈 동일한 리스트 형태의 환경설정 일괄 적용</td>');
	$form->addEnd(1);
	?>
	</tbody>
</table>
<div class="pd5 right"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit">위 설정으로 적용</button></span>&nbsp;<span class="btnPack black medium"><a href="javascript:;" onclick="$.dialogRemove();">취소</a></span></div>
</form>
<?php
require_once __PATH__."/_Admin/include/commonScript.php";
?>
