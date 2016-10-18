<?php
require $_SERVER['DOCUMENT_ROOT']."/_Admin/include/commonHeader.php";

if($_POST[type] == "cateModPost")
{
	//넘어온 값과 변수 동기화 및 validCheck
	foreach($_POST AS $key=>$val) {
		${$key} = trim($val);
	}
	$sql = "opt_division='".$opt_division."',opt_subject='".$opt_subject."',opt_url='".$opt_url."'";
	$db->query(" UPDATE `mdBanner__` SET ".$sql." WHERE cate='".__CATE__."' ");

	//스타일 적용
	foreach ($func->dirOpen(__PATH__."/skin/") as $key => $val) { $display->makeCss($val);}
	$func->err("배너 모듈 (입력항목 설정)이 정상적으로 적용되었습니다.");

} else {
	if(defined('__CATE__')) {
		$Rows = $db->queryFetch(" SELECT * FROM `mdBanner__` WHERE cate='".__CATE__."' ");
	} else {
		$func->ajaxMsg("[".__CATE__."]카테고리 정보가 존재하지 않습니다.","", 100);
	}
}
?>

<form id="frmCate" name="frmCate" method="post" enctype="multipart/form-data" target="hdFrame" action="<?php echo($_SERVER[PHP_SELF]);?>">
<input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />
<input type="hidden" name="prefix" value="<?php echo(substr(__CATE__, 0, 3));?>" />
<input type="hidden" name="type" value="cateModPost" />

<table class="table_list" style="width:100%;">
	<col width="120" />
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

	$form->addStart('말머리', 'opt_division', 1, 0, 'M');
	$form->add("radio", array('N'=>'노출안함','Y'=>'노출'), $Rows['opt_division'], 'color:black;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray"><span>분류 노출 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('제목', 'opt_subject', 1, 0, 'M');
	$form->add("radio", array('N'=>'노출안함','Y'=>'노출'), $Rows['opt_subject'], 'color:black;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray"><span>제목 노출 여부</span></td>');
	$form->addEnd(1);

	$form->addStart('참조 URL', 'opt_url', 1);
	$form->add("radio", array('N'=>'노출안함','Y'=>'노출'), $Rows['opt_url'], 'color:black;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray"><span>연결 URL 노출 여부</span></td>');
	$form->addEnd(1);
	?>
	</tbody>
</table>
<div class="pd5 right"><span class="button bblack strong"><button type="submit">위 설정으로 적용하기</button></span>&nbsp;<span class="pd3"><a href="#none" onclick="$.dialogRemove();" class="button bgray"><span>취소</span></a></span></div>
</form>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$('#frmCate').validate({onkeyup:function(element){$(element).valid();},errorLabelContainer:"#messageBox"});
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
//]]>
</script>
<?php
require __PATH__."/_Admin/include/commonScript.php";
?>