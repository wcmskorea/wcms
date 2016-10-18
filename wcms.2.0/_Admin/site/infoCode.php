<?php
require_once "../../_config.php";
require_once "../include/commonHeader.php";
?>
<fieldset id="help">
<legend align="center">→ 도 움 말 ←</legend>
<ul>
	<li>구분명을 삭제하실 경우 하위코드까지 함께 삭제됩니다.</li>
	<li>구분코드와 하위코드는 3자리 숫자로 입력하시기 바랍니다.</li>
</ul>
</fieldset>
<div style='width:240px;float:left;margin:10px 0 0 10px;'>
	<?php if($_SESSION['ulevel'] == '1') { ?><span class="btnPack gray small"><button type="button" onclick="$.dialog('./site/index.php?type=dialog',null,400,280)">구분코드 추가</button></span><? } ?>
	<div style='border:1px solid #DCDCDC;width:240px;'>
		<table class="table_basic" style="width:100%;">
			<col width="30">
			<col>
			<col width="100">
			<thead>
				<tr>
					<th class="first"><p class="center"></p></th>
					<th class="first"><p class="center">구분명</p></th>
					<th class="first"><p class="center">설정</p></th>
				</tr>
			</thead>
		</table>
		<div id='divCate' style='border:0;width:100%;height:428px;overflow-x:hidden;overflow-y:auto;'>
			<!--구분목록 영역 (AJAX를 이용 호출되는 영역)-->
		</div>
	</div>
</div>
<div style='width:240px;float:left;margin:10px 0 0 10px;'>
	<span class="btnPack small"></span>
	<div style='border:1px solid #DCDCDC;width:240px;'>
		<table class="table_basic" style="width:100%;">
			<col width="30">
			<col>
			<col width="100">
			<col>
			<thead>
				<tr>
					<th class="first"><p class="center"></p></th>
					<th class="first"><p class="center">코드명</p></th>
					<th class="first"><p class="center">설정</p></th>
				</tr>
			</thead>
		</table>
		<div id='divCodeStep1' style='border:0;width:100%;height:428px;overflow-x:hidden;overflow-y:auto;'>
		<!-- 코드목록 (AJAX를 이용 호출되는 영역) -->
		</div>
	</div>
</div>

<div style='width:240px;float:left;margin:10px 0 0 10px;'>
	<span class="btnPack small"></span>
	<div style='border:1px solid #DCDCDC;width:240px;'>
		<table class="table_basic" style="width:100%;">
			<col width="30">
			<col>
			<col width="70">
			<col>
			<thead>
				<tr>
					<th class="first"><p class="center"></p></th>
					<th class="first"><p class="center">코드명</p></th>
					<th class="first"><p class="center">설정</p></th>
				</tr>
			</thead>
		</table>
		<div id='divCodeStep2' style='border:0;width:100%;height:428px;overflow-x:hidden;overflow-y:auto;'>
		<!-- 코드목록 (AJAX를 이용 호출되는 영역) -->
		</div>
	</div>
</div>

<div style='width:240px;float:left;margin:10px 0 0 10px;'>
	<span class="btnPack small"></span>
	<div style='border:1px solid #DCDCDC;width:240px;'>
		<table class="table_basic" style="width:100%;">
			<col width="30">
			<col>
			<col width="70">
			<col>
			<thead>
				<tr>
					<th class="first"><p class="center"></p></th>
					<th class="first"><p class="center">코드명</p></th>
					<th class="first"><p class="center">설정</p></th>
				</tr>
			</thead>
		</table>
		<div id='divCodeStep3' style='border:0;width:100%;height:428px;overflow-x:hidden;overflow-y:auto;'>
		<!-- 코드목록 (AJAX를 이용 호출되는 영역) -->
		</div>
	</div>
</div>

<div class='ssClear'></div>
<?php include "../include/commonScript.php"; ?>


<script type="text/javascript">
//<![CDATA[

//---------------------------------------------------------------------------------------
// 구분목록표시
// --------------------------------------------------------------------------------------
function f_getCate(pType)
{
	$.insert("#divCate", "./site/index.php", '&type='+pType, '336');
}
//---------------------------------------------------------------------------------------
// 코드목록표시
// --------------------------------------------------------------------------------------
function f_getCode(pType,pCate,viewType,pCode)
{

	if(viewType == "ALL" || viewType < 3){
		$.insert("#divCodeStep1", "./site/index.php", '&type='+pType+'&cate='+pCate+'&code='+pCode+'&step=3', '336');
	}

	if(viewType == "ALL" || viewType < 6){
		$.insert("#divCodeStep2", "./site/index.php", '&type='+pType+'&cate='+pCate+'&code='+pCode+'&step=6', '336');
	}

	if(viewType == "ALL" || viewType < 9){
		$.insert("#divCodeStep3", "./site/index.php", '&type='+pType+'&cate='+pCate+'&code='+pCode+'&step=9', '336');
	}
}
//---------------------------------------------------------------------------------------
// 구분목록클릭
// --------------------------------------------------------------------------------------
function f_setCate(pCate,pType,viewType)
{
	f_getCode(pType,pCate,viewType,"");
}
//---------------------------------------------------------------------------------------
// 코드목록클릭
// --------------------------------------------------------------------------------------
function f_setCode(pCate,pCateName,pCateSeq,pCodeSeq,pName,pSeq,pUse)
{
  $("#cate").val(pCate);
  $("#cateName").val(pCateName);
	$("#cateViewSeq").val(pCateSeq);
  $("#code").val(pCode);
  $("#name").val(pName);
  $("#codeViewSeq").val(pCodeSeq);
}
//---------------------------------------------------------------------------------------
// 구분삭제
// --------------------------------------------------------------------------------------
function f_cateDelete(pCate,pCateName,pType)
{
	if ( confirm("구분명["+pCateName+"]를 삭제 하시겠습니까?\n - 삭제시 하위코드까지 삭제됩니다.") )
	{
		$.message('./site/index.php', '&type='+pType+'&cate='+pCate)
  }
}
//---------------------------------------------------------------------------------------
// 코드삭제
// --------------------------------------------------------------------------------------
function f_codeDelete(pCate,pCode,pName,pType)
{
  if ( confirm("코드["+pName+"]를 삭제 하시겠습니까?") )
	{
		$.message('./site/index.php', '&type='+pType+'&cate='+pCate+"&code="+pCode)
  }
}
//]]>
</script>
<script type="text/javascript">
//<![CDATA[
f_getCate('codeLeft');
<?php
if($_GET['cate']) {
	echo ('f_setCate("'.$_GET['cate'].'","codeRight","ALL");');
}
?>
//]]>
</script>