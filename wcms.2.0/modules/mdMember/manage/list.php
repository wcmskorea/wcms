<?php
/**
 * 회원모듈 - 목록
 */
require_once __PATH__."_Admin/include/commonHeader.php";

//EUC-KR의 경우 문자 인코딩
if($cfg['charset'] == 'euc-kr')
{
	foreach($_GET AS $key=>$val)
	{
		$_GET['$key'] = iconv("UTF-8//IGNORE", "CP949", $val);
	}
}

//노출순위 변경
if($_GET['type'] == "listMove")
{
	if($_GET['move'] == 'up')
	{
		$return = (intval($_GET['sort'] - 1) > 0) ? $_GET['sort'] - 1 : 1;
		$db->query(" UPDATE `mdMember__account` SET sort='".$return."' WHERE seq='".$_GET['idx']."' AND level='".$_GET['lev']."' ");
	}
	else if($_GET['move'] == 'dw') {
		$db->query(" UPDATE `mdMember__account` SET sort='".intval($_GET['sort']+1)."' WHERE seq='".$_GET['idx']."' AND level='".$_GET['lev']."' ");
	}
}

//회원그룹 조건별 검색
if(is_numeric($_GET['lev']))
{
	$sq .= "A.level='".$_GET['lev']."'";
}
else if($_GET['lev'] == 'ex') {
	$sq .= "A.level='0'";
}
else if($_GET['lev'] == 'all') {
	$sq .= "1";
}
else {
	$sq .= "A.level>'1'";
}

//회원구분별 검색
if($_GET['div']) { $sq .= " AND B.team='".$_GET['div']."'"; }

//업무(반)별 검색
if($_GET['ded']) { $sq .= " AND B.dedicate='".$_GET['ded']."'"; }

//조건별 검색
switch($_GET['sh'])
{
	case "username" :
		$sq .= " AND A.name like '%".$_GET['shc']."%'";
		break;
	case "userid" :
		$sq .= " AND A.id like '".$_GET['shc']."%'";
		break;
	case "nickname" :
		$sq .= " AND A.nick like '".$_GET['shc']."%' OR B.cafeNick like '%".$_GET['shc']."%'";
		break;
	case "code" :
		$sq .= " AND A.idcode=PASSWORD('".$_GET['shc']."')";
		break;
	case "phone" :
		$sq .= " AND (B.mobile like '%".$_GET['shc']."%' OR B.phone like '%".$_GET['shc']."%')";
		break;
	case "email" :
		$sq .= " AND A.email01 like '%".$_GET['shc']."%'";
		break;
    case "etc" :
		$sq .= " AND (B.groupName like '%".$_GET['shc']."%' OR B.certification like '%".$_GET['shc']."%')";
		break;
	case "detail" :
		if($_GET['userid'])    { $sq .= " AND A.id like '%".$_GET['userid']."%'"; }
		if($_GET['username'])  { $sq .= " AND A.name like '%".$_GET['username']."%'"; }
		if($_GET['idcode'])    { $sq .= " AND A.idcode=".$db->passType($cfg['site']['encrypt'], $_GET['idcode']); }
		if($_GET['email'])     { $sq .= " AND A.email like '%".$_GET['email']."%'"; }
		if($_GET['phone'])     { $sq .= " AND (B.mobile like '%".$_GET['phone']."%' OR B.phone like '%".$_GET['phone']."%')"; }
		if($_GET['dateReg'])   { 
			if($_GET['dateReg2']!='')
				$sq .= " AND DATE_FORMAT(FROM_UNIXTIME(A.dateReg),'%Y-%m-%d') between '".$_GET['dateReg']."' and '".$_GET['dateReg2']."' "; 
			else
				$sq .= " AND DATE_FORMAT(FROM_UNIXTIME(A.dateReg),'%Y-%m-%d')='".$_GET['dateReg']."'"; 
		}
		//if($_GET['cafeNick'])  { $sq .= " AND B.cafeNick like '%".$_GET['cafeNick']."%'"; }
		//if($_GET['cafeLevel']) { $sq .= " AND B.cafeLevel like '%".$_GET['cafeLevel']."%'"; }
		if($_GET['recomId'])   { $sq .= " AND A.recom='".$_GET['recomId']."'"; }
		if($_GET['dateSearchPeriod'])
		{
			if($_GET['dateSearchPeriod']=="birth") 
			{
				$_GET['syear']   = ($_GET['syear']) ? $_GET['syear'] : date('Y');
				$_GET['smonth']  = ($_GET['smonth']) ? $_GET['smonth'] : date('m');
				$_GET['sday']    = ($_GET['sday']) ? $_GET['sday'] : date('d');

				$sSolaDate = strtotime($_GET['syear'].'-'.$_GET['smonth'].'-'.$_GET['sday']);
				$sSolaDate = date('Ymd',$sSolaDate);
				$sLunarDate = $func->getSolaToLunar($sSolaDate);
				$sSolaDate = substr($sSolaDate,4,2).'-'.substr($sSolaDate,6,2);
				$sLunarDate = substr($sLunarDate,4,2).'-'.substr($sLunarDate,6,2);

				$sq .= " AND (B.birthType='S' AND FROM_UNIXTIME(B.".$_GET['dateSearchPeriod'].",'%m-%d') = '".$sSolaDate."' OR B.birthType='L' AND FROM_UNIXTIME(B.".$_GET['dateSearchPeriod'].",'%m-%d') = '".$sLunarDate."')";
			} else {	//기념일이면
				$sq .= " AND FROM_UNIXTIME(B.".$_GET['dateSearchPeriod'].",'%m-%d') = '".$_GET['smonth'].'-'.$_GET['sday']."' ";
			}
		}
		//통합검색 추가(2013-09-02)
		if($_GET['shcType'] == "group"   && $_GET['shc'] != "")      { $sq .= ' AND A.group like "%'.trim($_GET['shc']).'%"';}
		if($_GET['shcType'] == "groupName"   && $_GET['shc'] != "")      { $sq .= ' AND B.groupName like "%'.trim($_GET['shc']).'%"';}
		if($_GET['shcType'] == "department"   && $_GET['shc'] != "")     { $sq .= ' AND B.department like "%'.trim($_GET['shc']).'%"';}
		if($_GET['shcType'] == "team"   && $_GET['shc'] != "")           { $sq .= ' AND B.team like "%'.trim($_GET['shc']).'%"';}
		if($_GET['shcType'] == "function"   && $_GET['shc'] != "")       { $sq .= ' AND B.function like "%'.trim($_GET['shc']).'%"';}
		if($_GET['shcType'] == "certification"   && $_GET['shc'] != "")  { $sq .= ' AND B.certification like "%'.trim($_GET['shc']).'%"';}
		if($_GET['shcType'] == "cafeNick"   && $_GET['shc'] != "")       { $sq .= ' AND B.cafeNick like "%'.trim($_GET['shc']).'%"';}
		if($_GET['shcType'] == "cafeLevel"   && $_GET['shc'] != "")      { $sq .= ' AND B.cafeLevel like "%'.trim($_GET['shc']).'%"';}
		if($_GET['shcType'] == "" && $_GET['shc'] != "")                 { $sq .= ' AND (A.group like "%'.trim($_GET['shc']).'%" OR B.groupName like "%'.trim($_GET['shc']).'%" OR B.department like "%'.trim($_GET['shc']).'%" OR B.team like "%'.trim($_GET['shc']).'%" OR B.function like "%'.trim($_GET['shc']).'%" OR B.certification like "%'.trim($_GET['shc']).'%" OR B.cafeNick like "%'.trim($_GET['shc']).'%" OR B.cafeLevel like "%'.trim($_GET['shc']).'%")';}

		break;
}
//echo($sq);

//회원구분
//$array = $db->queryFetch(" SELECT department,team,function FROM `mdMember__` WHERE cate='000002002' ");
//foreach($array AS $key=>$val) { $array[$key] = explode(',', $val); }

//리스트 설정
if($_GET['chrows']) {
	$_GET['rows'] = $_GET['chrows'];
	$_GET['chrows'] = "";
}
$_GET['rows']			= ($_GET['rows']) ? $_GET['rows'] : 15;
$block			= 10;
$totalRec		= $db->queryFetchOne(" SELECT COUNT(*) FROM `mdMember__account` AS A LEFT JOIN `mdMember__info` AS B ON A.id=B.id WHERE ".$sq );
$currentPage	= ($_GET['currentPage']) ? $_GET['currentPage'] : 1;
$queryString	= "&amp;type=list&amp;lev=".$_GET['lev']."&amp;rows=".$_GET['rows']."&amp;div=".$_GET['div']."&amp;sh=".$_GET['sh']."&amp;shc=".$_GET['shc']."&amp;userid=".$_GET['userid']."&amp;username=".$_GET['username']."&amp;dateReg=".$_GET['dateReg']."&amp;dateReg2=".$_GET['dateReg2']."&amp;dateSearchPeriod=".$_GET['dateSearchPeriod']."&amp;email=".$_GET['email']."&amp;phone=".$_GET['phone']."&amp;ryear=".$_GET['ryear']."&amp;rmonth=".$_GET['rmonth']."&amp;rday=".$_GET['rday']."&amp;syear=".$_GET['syear']."&amp;smonth=".$_GET['smonth']."&amp;sday=".$_GET['sday']."&amp;cafeNick=".$_GET['cafeNick']."&amp;cafeLevel=".$_GET['cafeLevel']."&amp;recomId=".$_GET['recomId'];
$pagingInstance = new Paging($totalRec, $currentPage, $_GET['rows'], $block);
$pagingInstance->mode = "module";
$pagingInstance->addQueryString($queryString);
$pagingResult	=  $pagingInstance->result("../modules/mdMember/manage/_controll.php");

//모듈 환경설정 취합
$cfg['module'] = (array)$db->queryFetch(" SELECT * FROM `mdMember__` WHERE 1 LIMIT 1");
//모듈 환경설정 취합
$cfg['module'] = @array_merge($cfg['module'], (array)unserialize($cfg['module']['config']));
$cfg['module'] = @array_merge($cfg['module'], (array)unserialize($cfg['module']['contentAdd']));
?>

<h2><span class="arrow">▶</span>회원·고객 관리</h2>

<div class="table">
<div class="line bg_white">

<div style="padding-bottom:5px;">
<?php include "./searchDetail.php"; ?>
</div>

<form name="listForm" enctype="multipart/form-data"	onsubmit="return false;" target="hdFrame">
<input type="hidden" id="type" name="type" value="" />
<input type="hidden" id="rtotal" name="rtotal" value="" />
<input type="hidden" id="parm01" name="parm01" value="" />
<input type="hidden" id="parm02" name="parm02" value="" />
<input type="hidden" id="parm03" name="parm03" value="" />
<dl>
	<dd style="float: right;"><select name="chrows" class="bg_gray" style="width: 100px;" onchange="$.insert('#module', '../modules/mdMember/manage/_controll.php?type=list&amp;div=<?php echo($_GET['div']);?>&amp;lev=<?php echo($_GET['lev']);?>&amp;chrows='+this.value+'<?php echo($queryString)?>',null,300)">
		<option value="10"<?php if($_GET['rows']=='10'){echo(' selected="selected" class="green"');}?>>10건씩	보기</option>
		<option value="15"<?php if($_GET['rows']=='15'){echo(' selected="selected" class="green"');}?>>15건씩	보기</option>
		<option value="30"<?php if($_GET['rows']=='30'){echo(' selected="selected" class="green"');}?>>30건씩	보기</option>
		<option value="50"<?php if($_GET['rows']=='50'){echo(' selected="selected" class="green"');}?>>50건씩	보기</option>
		<option value="100"<?php if($_GET['rows']=='100'){echo(' selected="selected" class="green"');}?>>100건씩 보기</option>
		<option value="500"<?php if($_GET['rows']=='500'){echo(' selected="selected" class="green"');}?>>500건씩 보기</option>
		<option value="1000"<?php if($_GET['rows']=='1000'){echo(' selected="selected" class="green"');}?>>1000건씩 보기</option>
	</select></dd>
	<dd style="float: right; padding: 5px; vertical-align: center;"><span class="small_gray">총 <strong class="small_orange"><?php echo(number_format($totalRec));?></strong> 명</span></dd>
	<!--<dd style="float: right; padding:2px 6px 0 0;"><span class="btnPack black small"><button type="button" onclick="$('#detailSearch').animate({height:'toggle', opacity:'toggle'}, 'fast');">상세검색</button></span></dd>-->
	<?php if(in_array('mdSms', $cfg['modules'])) { ?>
	<dd style="float: left; padding: 0 0 5px 5px;"><span class="btnPack white small"><button type="button" onclick="return $.checkSend(2)">문자발송</button></span></dd>
	<?php } ?>
	<?php if(in_array('mdMail', $cfg['modules'])) { ?>
	<dd style="float: left; padding: 0 0 5px 5px;"><span class="btnPack white small"><button type="button" onclick="return $.checkSend(1)">메일발송</button></span></dd>
	<?php } ?>
	<dd style="float: left; padding: 0 0 5px 5px;"><span class="btnPack white small"><button onclick="return $.checkSend(3)">등급변경</button></span></dd>
	<?php if($cfg['module']['group']) { ?>
	<dd style="float: left; padding: 0 0 5px 5px;"><span class="btnPack white small"><button onclick="return $.checkSend(5)">그룹변경</button></span></dd>
	<?php } ?>
	<?php if(preg_match('/mdMember/', $_SESSION['udepartment']) || $_SESSION['ulevel'] < $cfg['operator']) { ?>
	<dd style="float: left; padding: 0 0 5px 5px;"><a href="../modules/mdMember/manage/toExcel.php?lev=<?php echo($_GET['lev']);?>" class="btnPack green small"><span>엑셀저장</span></a></dd>
	<?php } ?>
</dl>
<div class="clear"></div>

<table class="table_basic" style="width:100%; margin-top:5px;">
	<caption></caption>
	<colgroup>
	<col width="30">
	<col width="100">
	<col width="100">
	<?php if($cfg['module']['group']) { ?><col width="100"><?php } ?>
	<col>
	<col width="80">
	<col width="250">
	</colgroup>
	<thead>
		<tr>
			<th class="first"><p class="center"><input type="checkbox" checked="checked" id="allarticle" name="allarticle" style="vertical-align: top; cursor: pointer;" title="전체선택" /></p></th>
			<th><p class="center">아이디</p></th>
			<th><p class="center">회원등급</p></th>
			<?php if($cfg['module']['group']) { ?><th><p class="center">회원그룹</p></th><?php } ?>
			<th><p class="center">회원 간략정보</p></th>
			<th><p class="center">가입일</p></th>
			<th><p class="center">관리 및 설정</p></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$db->query(" SELECT *, A.seq AS seq FROM `mdMember__account` AS A LEFT JOIN `mdMember__info` AS B ON A.id=B.id 
	LEFT JOIN `mdMember__level` AS C ON A.level=C.level WHERE ".$sq." ORDER BY A.dateReg DESC ".$pagingResult['LimitQuery'] );

	if($db->getNumRows() < 1)
	{
		echo('<tr><td class="blank" colspan="7">등록된 회원이 존재하지 않습니다.</td></tr>');
	}
	else
	{
		while($Rows = $db->fetch())
		{
			$userid				= ($Rows['level'] < 1) 	? '<span class="blue">'.$Rows['id'].'</span>' : $Rows['id'];
			$address01			= ($Rows['address01'])	? explode(" ", $Rows['address01']) : "-";
			$Rows['mobile']	= ($Rows['mobile'])	? $Rows['mobile'] : "-";
			$Rows['sex']		= ($Rows['sex'])		? ($Rows['sex']=='1' || $Rows['sex']=='3') ? "남" : "여" : "-";
			$Rows['birth']		= ($Rows['birth'])		? date('Y-m-d',$Rows['birth']) : "-";
			$Rows['birthType']		= ($Rows['birthType'])		? ($Rows['birthType']=='L') ? "<span class='colorRed'>음</span>" : "양" : "-";

			echo('<tr onmouseover="this.style.backgroundColor=\'#ffffcc\';" onmouseout="this.style.backgroundColor=\'#fff\';">
			<th><p class="center"><input type="checkbox" id="choice_'.$Rows['seq'].'" class="articleCheck" name="choice[]" value="'.$Rows['id'].'" /></p></th>
			<td><label for="choice_'.$Rows['seq'].'">'.$Rows['id'].'</label></td>
			<td class="bg_gray"><p class="center">'.$Rows['position'].'</p></td>'.($cfg['module']['group'] ? '
			<td class="bg_gray"><p class="center">'.$Rows['group'].'</p></td>' : '').'
			<td title="'.$Rows['name'].'회원 상세보기"><span><a href="javascript:;" onclick="$.memberInfo(\''.$Rows['id'].'\');"><strong>'.$Rows['name'].'</strong></span>&nbsp;<span class="">( '.$Rows['seq'].'ㆍ'.$address01['0'].'ㆍ'.$Rows['phone'].'ㆍ'.$Rows['mobile'].'ㆍ'.$Rows['birth'].' ('.$Rows['birthType'].')<!--ㆍ'.str_replace("|","ㆍ",$Rows['info']).' )--></span></a></td>
			<td class="bg_gray"><p class="center">'.date('Y-m-d',$Rows['dateReg']).'</p></td>
			<td>');
			if($Rows['level'] == 0)
			{
				echo('<span class="btnPack red small"><button>탈퇴</button></span>');
			}
			echo('&nbsp;<span><a href="javascript:;" onclick="$.memberInfo(\''.$Rows['id'].'\');" class="btnPack white small"><span>정보</span></a>');
			if($func->checkModule('mdMessage'))
			{
				echo('&nbsp;<a href="javascript:;" onclick="$.dialog(\'../modules/mdMember/manage/_controll.php?type=message&amp;user='.$Rows['id'].'\',1000,500)" class="btnPack small bgray"><span>쪽지</span></a>');
			}
			if($func->checkModule('mdMileage'))
			{
				echo('&nbsp;<a href="javascript:;" onclick="$.dialog(\'../modules/mdMember/manage/_controll.php\', \'&amp;type=mileage&amp;user='.$Rows['id'].'\',1000,500)" class="btnPack white small"><span>적립</span></a>');
			}
			if($func->checkModule('mdPayment'))
			{
				//echo('&nbsp;<a href="javascript:;" onclick="$.dialog(\'../modules/mdMember/manage/_controll.php?type=money&amp;user='.$Rows['id'].'\',800,480)" class="button small bgray"><span>예치</span></a>&nbsp;<a href="javascript:;" onclick="$.dialog(\'../modules/mdMember/manage/_controll.php?type=billing&amp;user='.$Rows['id'].'\',800,480)" class="button small bgray"><span>결제</span></a>');
			}
			if($func->checkModule('mdOrder'))
			{
				echo('&nbsp;<a href="javascript:;" onclick="$.dialog(\'../modules/mdMember/manage/_controll.php\', \'&amp;type=order&amp;user='.$Rows['id'].'\',1000,500)" class="btnPack white small"><span>주문</span></a>');
			}
			echo('&nbsp;<a href="javascript:;" onclick="$.dialog(\'../modules/mdMember/manage/_controll.php\', \'&amp;type=log&amp;user='.$Rows['id'].'\',1000,500)" class="btnPack white small"><span>접속</span></a>');
			echo('</td></tr>');
		}
	}
	?>
	</tbody>
</table>
<div class="pageNavigation"><?php echo($pagingResult['PageLink']);?></div>
</div>
</div>

<input type="hidden" name="total" value="<?php echo($i);?>" />
</form>
<script type="text/javascript">
//<![CDATA[
$("#allarticle").click(function() {
	if ($("#allarticle:checked").length > 0) {
		$("input[class=articleCheck]:checked").attr("checked", false);
	} else {
		$("input[class=articleCheck]:not(checked)").attr("checked", "checked");
	}
});
$.checkSend = function(a)
{
	var frm = document.listForm;
	var cnt = 0;
	for(var i=0; i<frm.elements.length; i++) {
		var e = frm.elements[i];
		if(e.type == "checkbox" && e.id != "allarticle") {
			if(e.checked == true) {	cnt++; }
		}
	}
	if(cnt < 1) {
		alert('선택된 회원이 없습니다.');
		return false;
	} else {
		if(a == 1) {
			if(confirm(cnt + "명에게 그룹메일을 발송하시습니까?") == true){
				$.dialog('../modules/mdMail/manage/input.php?type=<?php echo($sess->encode('smsInput'));?>',null,500,360);
				frm.action = "member_groupMail.php";
				frm.method = 'POST';
				frm.target = 'mail';
				frm.rtotal.value = cnt;
				frm.submit();
			}
		} else if(a == 2) {
			if(confirm(cnt + "명에게 SMS를 발송하시습니까?") == true) {
				frm.type.value = "<?php echo($sess->encode('smsInput'));?>";
				frm.rtotal.value = cnt;
				$.checkFarm(frm, '../modules/mdSms/manage/input.php','dialog',null,500,360);
			}
		} else if(a == 3) {
			if(confirm(cnt + "명의 등급을 변경하시습니까?") == true){
				frm.type.value = "level";
				frm.rtotal.value = cnt;
				$.checkFarm(frm, '../modules/mdMember/manage/toLevel.php','dialog',null,300,120);
			}
		} else if(a == 4) {
			if(confirm(cnt + "명의 업무(학급)을 변경하시습니까?") == true){
				frm.type.value = "division";
				frm.rtotal.value = cnt;
				$.checkFarm(frm, '../modules/mdMember/manage/toDivision.php','dialog',null,300,120);
			}
		} else if(a == 5) {
			if(confirm(cnt + "명의 그룹을 변경하시습니까?") == true){
				frm.type.value = "group";
				frm.rtotal.value = cnt;
				$.checkFarm(frm, '../modules/mdMember/manage/toGroup.php','dialog',null,300,120);
			}
		} else {
			alert('잘못된 코드입니다.');
			return false;
		}
	}
}
//]]>
</script>
<?php
require_once __PATH__."_Admin/include/commonScript.php";
?>
