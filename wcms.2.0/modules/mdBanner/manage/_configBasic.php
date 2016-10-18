<?php
require $_SERVER['DOCUMENT_ROOT']."/_Admin/include/commonHeader.php";

if($_POST['type'] == "cateModPost")
{

	//넘어온 값과 변수 동기화 및 validCheck
	foreach($_POST AS $key=>$val)
	{
		${$key} = trim($val);
	}

	$listCount = $listcnt1.",".$listcnt2;
	$query = " REPLACE INTO `mdBanner__` (cate,division,listing,listCount,subjectCut) VALUES ('".__CATE__."','".$division."','".$listing."','".$listCount."','".$subjectCut."') ";
	$db->query($query);

	//동일 카테고리 일괄적용
	if($same == 'Y')
	{
		$db->query(" UPDATE `mdBanner__` SET including='".$including."',listCount='".$listCount."' WHERE listing='".$listing."' ");
	}

	//스타일 적용
	//foreach ($func->dirOpen(__PATH__."/skin/") as $key => $val) { $display->makeCss($val);}
	$func->err("배너·이미지 모듈 (환경 설정)이 정상적으로 적용되었습니다.");

}
else {

	if(defined('__CATE__'))
	{
		$Rows = $db->queryFetch(" SELECT * FROM `mdBanner__` WHERE cate='".__CATE__."' ");
		$Rows['subjectCut'] = ($Rows['subjectCut']) ? $Rows['subjectCut'] : 50;
		$listCount = ($Rows['listCount']) ? explode(",", $Rows['listCount']) : array("1","10");
		$name = $db->queryFetchOne(" SELECT name FROM `site__` WHERE cate='".__CATE__."' ");
	}
	else {
		$func->ajaxMsg("[".__CATE__."]카테고리 정보가 존재하지 않습니다.","", 100);
	}

}
?>

<form name="frmCate" method="post" enctype="multipart/form-data" target="hdFrame" action="<?php echo($_SERVER[PHP_SELF]);?>">
<input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />
<input type="hidden" name="prefix" value="<?php echo(substr(__CATE__,0,3));?>" />
<input type="hidden" name="type" value="cateModPost" />

<table class="table_list" style="width:100%;">
<col width="140" />
<col />
<col />
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

	$form->addStart('말머리 설정', 'division', 1);
	$form->add('textarea', 'division', $Rows['division'], 'width:270px; height:32px;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray"><span>게시물 머릿말 설정</span><span class="small_blue">( 콤마[ , ]구분입력 )</span><br /><span>( ex : 질문, 답변 )</span></td>');
	$form->addEnd(1);

	$form->addStart('리스트 형태', 'listing', 1, 0, 'M');
	$form->name = array('Basic'=>'일반형','Gallery'=>'갤러리형');
	$form->add('select', $form->name, $Rows['listing'], 'width:270px;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">게시물 리스트 형태 설정</td>');
	$form->addEnd(1);

	$form->addStart('게시물 제목 글자수', 'subjectCut', 1, 0, 'M');
	$form->add('input', 'subjectCut', $Rows['subjectCut'], 'width:30px; text-align:center;');
	$form->addHtml('<li class="opt gray">자</li>');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">게시 목록의 제목 글자 수 (기본: 50자)</td>');
	$form->addEnd(1);

	$form->addStart('게시물 출력 (가로)', 'listcnt1', 1, 0, 'M');
	$form->add('input', 'listcnt1', $listCount[0], 'width:30px; text-align:center;');
	$form->addHtml('<li class="opt gray">개(열)</li>');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">게시 목록의 가로 노출 갯수 설정 (ex: 일반:1열, 갤러리:4열)</td>');
	$form->addEnd(1);

	$form->addStart('게시물 출력 (세로)', 'listcnt2', 1, 0, 'M');
	$form->add('input', 'listcnt2', $listCount[1], 'width:30px; text-align:center;');
	$form->addHtml('<li class="opt gray">개(행)</li>');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">게시 목록의 세로 노출 갯수 설정 (ex: 일반:10행, 갤러리:3행)</td>');
	$form->addEnd(1);

	?>
	</tbody>
</table>
<div class="pd5 right"><span class="button bblack strong"><button type="submit">위 설정으로 적용하기</button></span>&nbsp;<span class="pd3"><a href="#none" onclick="$.dialogRemove();" class="button bgray"><span>취소</span></a></span></div>
</form>
<?php
require __PATH__."/_Admin/include/commonScript.php";
?>