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

	$form->addStart('가입일', 'cal1', 1);
	$form->addHtml('<li class="opt"><input id="cal1" name="dateReg" type="text" class="input_gray center" style="width:78px" value="'.$_GET['dateReg'].'" /></li>');
	$form->addHtml('<li class="opt">~ <input id="cal2" name="dateReg2" type="text" class="input_gray center" style="width:78px" value="'.$_GET['dateReg2'].'" /></li>');
	$form->addEnd(0);

	$startTimeYear   = ($_GET['syear']) ? $_GET['syear'] : date('Y');
	$startTimeMonth  = ($_GET['smonth']) ? $_GET['smonth'] : date('m');
	$startTimeDay    = ($_GET['sday']) ? $_GET['sday'] : date('d');
	$startTimeSearch = strtotime($startTimeYear.'-'.$startTimeMonth.'-'.$startTimeDay.' 00:00:00');

	$form->addStart('기념일', 'dateSearchPeriod');
	$form->add('select', array(''=>'선택','birth'=>'생일','memory'=>'결혼기념일'), $_GET['dateSearchPeriod'], 'width:60px');
	$form->add('dateMonthDay', 's', $startTimeSearch);
	$form->addHtml('<td rowspan="5" class="bg_gray"><p class="center"><span class="btnPack red medium strong"><button type="submit">검색하기</button></span></p></td>');
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

	/*$form->addStart('카페 닉네임', 'cafeNick', 1);
	$form->add('input', 'cafeNick', $_GET['cafeNick'], 'width:180px; text-align:center');
	$form->addEnd(0);
	$form->addStart('카페 레벨', 'cafeLevel', 0);
	$form->add('input', 'cafeLevel', $_GET['cafeLevel'], 'width:180px; text-align:center');
	$form->addEnd(1);*/

	//통합검색 추가(2013-09-02)
	$form->addStart('통합검색', 'shcType',1);
	$form->add('select', array(''=>'전체검색','group'=>'회원그룹','groupName'=>'회사명','department'=>'부서명','team'=>'팀명','function'=>'직함','certification'=>'자격증','cafeNick'=>'카페 닉네임','cafeLevel'=>'카페 회원등급'), $_GET['shcType'], 'color:black;');
	$form->add('input', 'shc', $_GET['shc'], 'width:180px;');
	$form->addEnd(0);

	$form->addStart('추천회원', 'recomId', 0);
	$form->addHtml('<ol><li class="opt"><select id="recomId" name="recomId" class="bg_gray" style="width:183px">
		<option value="">-- 선택하세요 --</option>');
		$db->query(" SELECT id,name FROM `mdMember__level` AS A INNER JOIN `mdMember__account` AS B ON A.level=B.level AND A.recom='Y' ORDER BY B.nick ASC ");
		while($sRows = $db->fetch())
		{
			$form->addHtml('<option value="'.$sRows['id'].'" style="color:#990000;">'.$sRows['name'].' ('.$sRows['id'].')</option>');
		}
	$form->addHtml('</select></li></ol>');
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
