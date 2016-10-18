<div id="detailSearch" style="padding: 3px;">
<form name="searchForm" method="post" enctype="multipart/form-data" onsubmit="return $.checkFarm(this, '../modules/mdMember/manage/_controll.php','insert','#module');">
<input type="hidden" name="lev" value="<?php echo($_GET['lev']);?>" />
<input type="hidden" name="rows" value="<?php echo($_GET['rows']);?>" />
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

	$regTimeYear   = ($_GET['ryear']) ? $_GET['ryear'] : date('Y');
	$regTimeMonth  = ($_GET['rmonth']) ? $_GET['rmonth'] : date('m');
	$regTimeDay    = ($_GET['rday']) ? $_GET['rday'] : date('d');
	$regTimeSearch = strtotime($regTimeYear.'-'.$regTimeMonth.'-'.$regTimeDay.' 00:00:00');


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
	?>
	</tbody>
</table>
</form>
<script type="text/javascript">
//<[!CDATA[
	initCal({id:"cal1",type:"day",today:"y",icon:"n"});
	initCal({id:"cal2",type:"day",today:"y",icon:"n"});
//]]>
</script>
</div>
