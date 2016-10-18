<?php
//require_once  "../../../_config.php";
if($_GET[sh] == 'detail') {
	echo('<div id="detailSearch" style="margin-bottom:5px;">');
} else {
	echo('<div id="detailSearch" style="display:none; margin-bottom:5px;">');
}
?>
<form name="searchForm" method="post" enctype="multipart/form-data" onsubmit="return $.checkFarm(this, '../modules/mdDocument/manage/_controll.php','insert','#module');">
<input type="hidden" name="lev" value="<?php echo($_GET['lev']);?>" />
<input type="hidden" name="sh" value="detail" />

<table class="table_basic" style="width: 100%;">
	<col width="90">
	<col width="200">
	<col width="90">
	<col width="200">
	<col>
	<tbody>
	<?php 
	$form = new Form('table');
	
	$form->addStart('등록일', 'syear', 1);
	$form->add('date', 's', strtotime($_GET['syear'].'-'.$_GET['smonth'].'-'.$_GET['sday']));
	$form->addEnd(0);
	$form->addStart('아이피', 'userip');
	$form->add('input', 'userip', '', 'width:180px;');
	$form->addEnd(0);
	$form->addHtml('<td rowspan="3" class="bg_gray"><p class="center"><span class="btnPack black medium strong"><button type="submit">검색하기</button></span></p></td>');
	$form->addEnd(1);
	
	$form->addStart('제목', 'subject', 1);
	$form->add('input', 'subject', '', 'width:180px;');
	$form->addEnd(0);
	$form->addStart('제목+내용', 'content');
	$form->add('input', 'content', '', 'width:180px;');
	$form->addEnd(1);
	
	$form->addStart('아이디', 'userid', 1);
	$form->add('input', 'userid', '', 'width:180px;');
	$form->addEnd(0);
	$form->addStart('작성자', 'writer');
	$form->add('input', 'writer', '', 'width:180px;');
	$form->addEnd(1);
	
	?>
	</tbody>
</table>
</form>

</div>
