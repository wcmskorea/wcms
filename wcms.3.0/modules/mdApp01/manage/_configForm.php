<?php
require_once  "../../../_config.php";
if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER'])) { $func->ajaxMsg("[경고!] 정상적인 접근이 아닙니다.","", 100); }

if($_POST['type'] == "cateModPost")
{
	//넘어온 값과 변수 동기화 및 validCheck
	foreach($_POST AS $key=>$val)
	{
		if(substr($key, 0, 4) == "opt_")
		{
			$db->data['contentAdd'][$key] = trim($val);
		}
	}
	unset($db->data['contentAdd']['cate'], $db->data['contentAdd']['type']);
	$db->data['contentAdd'] = serialize($db->data['contentAdd']);
	$db->sqlUpdate("mdApp01__", "cate='".__CATE__."'", array(), 0);

	//추가입력항목
	$addCount = count($_POST['addName']);
	for($i=1; $i<=$addCount; $i++)
	{
		if($_POST['addName'][$i])
		{
			$db->data['cate']		= __CATE__;
			$db->data['sort']		= $i;
			$db->data['addName']	= trim($_POST['addName'][$i]);
			$db->data['addEx']		= trim($_POST['addEx'][$i]);
			$db->data['addContent'] = trim($_POST['addContent'][$i]);
			$db->data['addType']	= trim($_POST['addType'][$i]);
			$db->sqlInsert("mdApp01__opt", "REPLACE", 0);
		}
	}

	//동일 카테고리 일괄적용
	if($db->data['contentAdd']['same'] == 'Y')
	{
		$db->sqlUpdate("mdApp01__", "cate like '".$db->data['cate']."%'", array('cate'), 0);
	}

	$func->err("상담·문의 모듈 (입력항목 설정)이 정상적으로 적용되었습니다.");
}
else
{
	if(defined('__CATE__'))
	{
		$Rows = $db->queryFetch(" SELECT * FROM `mdApp01__` WHERE cate='".__CATE__."' ");
		$config  = unserialize($Rows['config']);
		$content = unserialize($Rows['contentAdd']);
		$content['cate'] = $Rows['cate'];
	}
	else
	{
		$func->ajaxMsg("[".__CATE__."]카테고리 정보가 존재하지 않습니다.","", 100);
	}
}
?>
<form id="frmCate" name="frmCate" method="post" enctype="multipart/form-data" target="hdFrame" action="<?php echo($_SERVER['PHP_SELF']);?>">
<input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />
<input type="hidden" name="type" value="cateModPost" />

<table class="table_list" style="width:100%;">
	<col width="120">
	<col width="350">
	<col>
	<thead>
		<tr>
			<th class="first"><p class="center"><span class="mg2">항 목</span></p></th>
			<th><p class="center"><span class="mg2">사용항목 설정</span></p></th>
			<th><p class="center"><span class="mg2">도움말</span></p></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$form = new Form('table');

	$form->addStart('접수현황', 'opt_display', 1, 0, 'M');
	$form->add("radio", array('N'=>'노출안함','Y'=>'노출함'), $content['opt_display'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>신청접수 현황 노출 여부</span></td>');
	$form->addEnd(1);

	if($config['division']) {
		$form->addStart('상담구분', 'opt_division', 1, 0, 'M');
		$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_division'], 'color:black;');
		$form->addEnd(0);
		$form->addHtml('<td class="small_gray bg_gray"><span>상담구분 선택 여부</span></td>');
		$form->addEnd(1);
	}

	$form->addStart('개인정보동의', 'opt_agreement', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_agreement'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>개인정보취급방침 동의 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('이름', 'opt_name', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_name'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>등록자 이름 입력 여부</span></td>');
	$form->addEnd(1);
    
    /*
	$form->addStart('주민번호', 'opt_idcode', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_idcode'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>주민번호 입력 여부</span></td>');
	$form->addEnd(1);	
    */

	$form->addStart('휴대폰 본인인증', 'opt_mobileAuth', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_mobileAuth'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>휴대전화를 통한 본인인증 입력여부</span></td>');
	$form->addEnd(1);

	$form->addStart('휴대전화', 'opt_mobile', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_mobile'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>휴대전화번호 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('전화번호', 'opt_phone', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_phone'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>전화번호 입력 여부</span></td>');
	$form->addEnd(1);

	/*$form->addStart('연 락 처', 'opt_tel', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_tel'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>연락처(휴대폰,일반전화 입력 여부</span></td>');
	$form->addEnd(1);*/

	$form->addStart('주소', 'opt_address', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_address'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>주소 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('이메일', 'opt_email', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_email'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>이메일 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('상세내용', 'opt_content', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_content'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>상세 입력정보 입력 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('예약시간', 'opt_schedule', 1, 0, 'M');
	$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수항목'), $content['opt_schedule'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><span>상담예약시간 선택 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('일괄적용', 'same', 1);
	$form->add('checkbox', 'same', 'N', 'color:red;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">같은 모듈 동일한 리스트 형태의 환경설정 일괄 적용</td>');
	$form->addEnd(1);
	?>
	</tbody>
</table>
<div class="pd5 right"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit">위 설정으로 적용</button></span>&nbsp;<span class="btnPack black medium"><a href="javascript:;" onclick="$.dialogRemove();">취소</a></span></div>
<!--<table class="table_list" style="width:100%;">
	<col width="120">
	<col width="350">
	<col>
	<tbody>
	<?php
	for($i=1; $i<=20; $i++)
	{
		if($content['opt_add'.$i] != 'N')
		{
			$opt = $db->queryFetch(" SELECT * FROM `mdApp01__opt` WHERE cate='".__CATE__."' AND sort='".$i."' ");
		}
		$form->addStart('추가항목['.$i.']', 'opt_add'.$i, 1, 0, 'M');
		$form->add("radio", array('N'=>'사용안함','Y'=>'사용','M'=>'필수'), $content['opt_add'.$i], 'color:black;','');
		$form->addHtml('<li class="opt">/</li><li class="opt"><input type="radio" id="addType1" name="addType['.$i.']" value="input"');
		if($opt['addType'] == 'input') { $form->addHtml(' checked="checked"'); }
		$form->addHtml(' /><label for="addType1">입력</label></li>');
		$form->addHtml('<li class="opt"><input type="radio" id="addType2" name="addType['.$i.']" value="radio"');
		if($opt['addType'] == 'radio') { $form->addHtml(' checked="checked"'); }
		$form->addHtml(' /><label for="addType2">라디오</label></li>');
		$form->addHtml('<li class="opt"><input type="radio" id="addType3" name="addType['.$i.']" value="checkboxs"');
		if($opt['addType'] == 'checkboxs') { $form->addHtml(' checked="checked"'); }
		$form->addHtml(' /><label for="addType3">체크</label></li>');
		$form->addHtml('<li class="opt">항목: </li>');
		$form->add('input', 'addName['.$i.']', $opt['addName'], 'width:120px;','maxlength="100"');
		$form->addHtml('<li class="opt">&nbsp;설명: </li>');
		$form->add('input', 'addEx['.$i.']', $opt['addEx'], 'width:130px;','maxlength="30"');
		$form->addHtml('<li class="opt">내용: </li>');
		$form->add('input', 'addContent['.$i.']', $opt['addContent'], 'width:295px;','maxlength="100"');
		$form->addEnd(0);
		$form->addHtml('<td class="small_gray bg_gray"><p>별도 추가로 입력받을 내용을 생성합니다.</p><p>(라디오와 체크박스 항목의 구분자는 " | "입니다.)</p></td>');
		$form->addEnd(1);
		unset($opt);
	}
	?>
	</tbody>
</table>
<div class="pd5 right"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit">위 설정으로 적용</button></span>&nbsp;<span class="btnPack black medium"><a href="javascript:;" onclick="$.dialogRemove();">취소</a></span></div>-->
</form>
<?php
require_once __PATH__."/_Admin/include/commonScript.php";
?>
