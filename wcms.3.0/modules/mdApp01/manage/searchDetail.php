<div id="detailSearch" style="padding: 3px;">
<form name="searchForm" method="post" enctype="multipart/form-data" onsubmit="return $.checkFarm(this, '../modules/mdApp01/manage/_controll.php','insert','#module');">
<input type="hidden" name="sh" value="detail" />
<input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />
<input type="hidden" name="state" value="<?php echo($_GET['state']);?>" />

<table class="table_list" style="width: 100%;">
	<col width="100">
	<col>
	<col width="100">
	<col>
	<col>
	<tbody>
	<?php
	$form = new Form('table');

	$form->addStart('등록일', 'cal1', 1);
	$form->addHtml('<li class="opt"><input id="cal1" name="dateReg" type="text" class="input_gray center" style="width:180px" /></li>');
	$form->addEnd(0);
	$form->addStart('예약일', 'cal2');
	$form->addHtml('<li class="opt"><input id="cal2" name="dateRev" type="text" class="input_gray center" style="width:180px" /></li>');
	$form->addEnd(0);
	$form->addHtml('<td rowspan="4" class="bg_gray"><p class="center"><span class="btnPack red medium strong"><button type="submit">검색하기</button></span></p></td>');
	$form->addEnd(1);

	$form->addStart('아이디', 'userid', 1);
	$form->add('input', 'userid', null, 'width:180px;');
	$form->addEnd(0);
	$form->addStart('이름', 'username');
	$form->add('input', 'username', null, 'width:180px;');
	$form->addEnd(1);

	$form->addStart('연락처', 'phone', 1);
	$form->add('input', 'phone', null, 'width:180px;');
	$form->addEnd(0);
	$form->addStart('이메일', 'email');
	$form->add('input', 'email', null, 'width:180px;');
	$form->addEnd(1);
	?>
	</tbody>
</table>
</form>

</div>
<script type="text/javascript">
//<[!CDATA[
	initCal({id:"cal1",type:"day",today:"y",icon:"n"});
	initCal({id:"cal2",type:"day",today:"y",icon:"n",max:"<?php echo(date('Y')+3);?>"});
//]]>
</script>