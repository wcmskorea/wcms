<div id="detailSearch" style="padding: 3px;">
<form name="searchForm" method="post" enctype="multipart/form-data" onsubmit="return $.checkFarm(this, '../modules/mdMember/manage/_controll.php','insert','#module');">
<input type="hidden" name="lev" value="<?php echo($_GET['lev']);?>" />
<input type="hidden" name="sh" value="detail" />

<table class="table_list" style="width: 100%;">
	<col width="100">
	<col>
	<col width="100">
	<col>
	<col>
	<tbody>
	<?php
	$form = new Form('table');

	$form->addStart('등록일', 'syear', 1);
	$form->add('date', 's', strtotime($_GET['year'].'-'.$_GET['month'].'-'.$_GET['day']));
	$form->addHtml('<li class="opt" style=""><span class="keeping"><input type="checkbox" id="dateSearch" name="dateSearch" value="Y" /><label for="dateSearch">날짜검색</label></span></li>');
	$form->addEnd(0);
	$form->addStart('식별번호', 'idcode');
	$form->add('input', 'idcode', $_GET['idcode'], 'width:180px;');
	$form->addEnd(0);
	$form->addHtml('<td rowspan="4" class="bg_gray"><p class="center"><span class="btnPack red medium strong"><button type="submit">검색하기</button></span></p></td>');
	$form->addEnd(1);

	$form->addStart('아이디', 'userid', 1);
	$form->add('input', 'userid', $_GET['userid'], 'width:180px;');
	$form->addEnd(0);
	$form->addStart('이름', 'username');
	$form->add('input', 'username', $_GET['username'], 'width:180px;');
	$form->addEnd(1);

	$form->addStart('연락처', 'phone', 1);
	$form->add('input', 'phone', $_GET['phone'], 'width:180px;');
	$form->addEnd(0);
	$form->addStart('이메일', 'email');
	$form->add('input', 'email', $_GET['email'], 'width:180px;');
	$form->addEnd(1);

	$form->addStart('카페 닉네임', 'cafeNick', 1);
	$form->add('input', 'cafeNick', $_GET['cafeNick'], 'width:180px;');
	$form->addEnd(0);
	$form->addStart('카페 레벨', 'cafeLevel');
	$form->add('input', 'cafeLevel', $_GET['cafeLevel'], 'width:180px;');
	$form->addEnd(1);
	?>
	</tbody>
</table>
</form>

</div>
