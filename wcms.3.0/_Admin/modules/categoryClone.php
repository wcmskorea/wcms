<?php
require_once "../include/commonHeader.php";

if($_POST['type'] == "cateClonePost")
{
	$func->checkRefer("POST");

//	넘어온 값과 변수 동기화 및 validCheck
	foreach($_POST AS $key=>$val)
	{
		${$key} = trim($val);
		#--- $func->validCheck(체크할 값, 항목제목, 항목명, 체크타입)
		if($key == "cloneCate") { $func->vaildCheck($val, "복제할 카테고리 코드", $key, "num" ,"M"); }
		if($key == "cateCode2") { $func->vaildCheck($val, "생성할 카테고리 코드", $key, "num" ,"M"); }
	}
	if(strlen($cateCode2)%3 != 0) $func->err("코드는 3,6,9,12자리수로 작성하셔야 합니다");

//	카테고리 복제 시작
	$db->query(" SELECT * FROM `site__` WHERE skin='".$skin."' AND cate like '".$cloneCate."%' ORDER BY cate ASC ");
	while($db->data = $db->fetch())
	{
		$cated = $db->data['cate'];
		$db->data['skin'] = $skin;
		$db->data['name'] = (strlen($db->data['cate']) == strlen($cloneCate)) ? $cateName : $db->data['name'];
		$db->data['cate'] = $cateCode2.substr($db->data['cate'], strlen($cateCode2), strlen($db->data['cate']));
		$db->data['update'] = time();

		if($db->sqlInsert("site__", "REPLACE", 0, 2) > 0)
		{
			//모듈설정 복제
			if(substr($db->data['mode'],0,2) == 'md' && $db->data['mode'] != 'mdSitemap')
			{
				$modeConfig = $db->queryFetch(" SELECT * FROM `".$db->data['mode']."__` WHERE cate='".$cated."' ", 3);

				//모듈설정 복제 Query문 생성
				$query = " REPLACE INTO `".$db->data['mode']."__` (";
				foreach($modeConfig AS $key=>$val)
				{
					if($key != 'cate' && is_string($key)) { $query .= $key.","; }
				}
				$query .= "cate";
				$query .= ") VALUES (";
				foreach($modeConfig AS $key=>$val)
				{
					if($key != 'cate' && is_string($key)) { $query .= "'".$val."',"; }
				}
				$query .= "'".$db->data['cate']."')";

				//모듈설정 복제실행
				//echo $query."<br />";
				$db->query($query, 3);
			}
		}
		$skin = $db->data['skin'];
	}

	if($db->getAffectedRows() > 0)
	{
		//XML 업데이트
		$display->cacheXml($skin);
		$func->err("[".$cateCode2."]카테고리 정보가 정상적으로 복제되었습니다.", "parent.$.insert('#tabBody".$skin."', './modules/categoryList.php?skin=".$skin."',null,300); parent.$.dialogRemove();");
		//$func->errCfm("Display(Skin) 위젯 정보가 정상적으로 적용되었습니다.", "parent.$.dialogRemove();".PHP_EOL."parent.$.insert('#tabBody".$skin."', './modules/categoryList.php?skin=".$skin."',null,300);");
	}
	else
	{
		$func->err("[".$db->data['cloneCate']."]카테고리 복제 실패입니다.");
	}

}
else if($_GET[type] == "cateClone")
{
	if(substr($_GET[cloneCate],0,3) == '000' && substr($_GET[cloneCate],3,3) != '999') { $func->ajaxMsg("본 카테고리는 복제가 불가능합니다","", 200); }
	$Rows = $db->queryFetch(" SELECT cate,name FROM `site__` WHERE cate='".$_GET[cloneCate]."' ");
	$title = "[".$Rows['name']."] 카테고리 복제하기";
	$cate01 = (strlen($Rows['cate']) < 6) ? null : substr($Rows['cate'], 0, strlen($Rows['cate'])-3);
}
?>
<div id="messageBox"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/delete.gif" /></spna>&nbsp;<strong>Message :</strong>&nbsp;&nbsp;&nbsp;</div>

<form id="frmCateClone" name="frmCateClone" method="post" enctype="multipart/form-data" action="<?php echo($_SERVER['PHP_SELF']);?>" target="hdFrame">
<input type="hidden" name="type" value="cateClonePost" />
<input type="hidden" id="skin" name="skin" value="<?php echo($_GET['skin']);?>" />
<input type="hidden" name="cloneCate" value="<?php echo($_GET['cloneCate']);?>" />

<div class="menu_violet">
<p title="드래그하여 이동하실 수 있습니다"><strong><?php echo($title);?></strong></p>
</div>

<div class="bg_white">
<fieldset id="help"><legend>→ 도 움 말 ←</legend>
<ul>
	<li>카테고리 복제시 복제대상의 하위 카테고리까지 모두 복제됩니다. (부모 카테고리만 변경됨)</li>
	<li>카테고리 복제 이후 각 모듈의 설정은 따로 적용해야만 합니다.</li>
	<li>카테고리 복제는 콘텐츠 및 데이터까지 복제하지 않습니다.</li>
</ul>
</fieldset>
</div>

<table class="table_basic" style="width:100%;">
	<col width="120">
	<col width="320">
	<col>
	<thead>
		<tr>
			<th class="first"><p class="center"><span class="mg2">항 목</span></p></th>
			<th><p class="center"><span class="mg2">설 정</span></p></th>
			<th><p class="center"><span class="mg2">도움말</span></p></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th><label><strong>원본 카테고리</strong></label></th>
			<td><strong><?php echo($Rows['name']);?></strong>&nbsp;<span>(<?php echo($Rows['cate']);?>)</span></td>
			<td class="small_gray bg_gray"><ol><li class="opt">복제할 원본 카테고리 정보</li></ol></td>
		</tr>
		<?php
		$form = new Form('table');

		$form->addStart('카테고리 코드', 'cateCode2', 1, 0, 'M');
		$form->add('input', 'cateCode2', str_pad(intval($Rows['cate']+1), strlen($Rows['cate']), "0", STR_PAD_LEFT), 'width:100px; text-align:center; color:red;', 'digits="true" minlength="3" maxlength="'.strlen($Rows['cate']).'" onblur="$.checkOverLap(\''.$sess->encode("checkCate").'\', \'Cate\');"');
		$form->addHtml('<li class="opt"><span id="checkCate" class="small_orange"></span></li>');
		$form->addEnd(0);
		$form->addHtml('<td class="small_gray bg_gray"><ol><li class="opt"><span>카테고리 코드는 "001"부터 시작하는 3자리 숫자로 입력</span>');
		$form->addEnd(1);

		$form->addStart('카테고리명', 'cateName', 1, 0, 'M');
		$form->add('input', 'cateName', $Rows['name'], 'width:200px;','maxlength="30"');
		$form->addEnd(0);
		$form->addHtml('<td class="small_gray bg_gray"><ol><li class="opt"><span>복사될 카테고리명 입력</span>');
		$form->addEnd(1);
		?>
	</tbody>
</table>
<div class="pd5 right"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit">위 설정으로 적용</button></span>&nbsp;<span class="btnPack medium black"><a href="javascript:;" onclick="$.dialogRemove();">취소</a></span></div>
</form>
<?php
	include "../include/commonScript.php";
?>
<script type="text/javascript">
//<[!CDATA[
$(document).ready(function(){
	$.checkOverLap('<?php echo($sess->encode("checkCate"));?>', 'Cate');
	$('#frmCateClone').validate({onkeyup:function(element){$(element).valid();},errorLabelContainer:"#messageBox"});
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
//]]>
</script>