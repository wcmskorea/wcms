<?php
/**
 * 교육관련 지자체용 회원 목록
 * Author : Sung-Jun, Lee (http://www.aceoa.com)
 * Lastest : 2011. 1. 28.
 */

if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
usleep($cfg['usleep']);

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
	case "code" :
		$sq .= " AND A.idcode=PASSWORD('".$_GET['shc']."')";
		break;
	case "phone" :
		$sq .= " AND (B.mobile01 like '%".$_GET['shc']."%' OR B.phone like '%".$_GET['shc']."%')";
		break;
	case "email" :
		$sq .= " AND A.email01 like '%".$_GET['shc']."%'";
		break;
	case "detail" :
		if($_GET['userid'])    { $sq .= " AND A.id like '%".$_GET['userid']."%'"; }
		if($_GET['username'])  { $sq .= " AND A.name like '%".$_GET['username']."%'"; }
		if($_GET['idcode'])    { $sq .= " AND A.idcode=".$db->passType($cfg['site']['encrypt'], $_GET['idcode']); }
		if($_GET['email'])     { $sq .= " AND A.email like '%".$_GET['email']."%'"; }
		if($_GET['phone'])     { $sq .= " AND (B.mobile like '%".$_GET['phone']."%' OR B.phone like '%".$_GET['phone']."%')"; }
		if($_GET['dateSearch']){ $sq .= " AND A.dateReg BETWEEN '".strtotime($_GET['syear'].'-'.$_GET['smonth'].'-'.$_GET['sday'].' 00:00:00')."' AND '".strtotime($_GET['syear'].'-'.$_GET['smonth'].'-'.$_GET['sday'].' 23:59:59')."'"; }
		break;
}
//echo($sq);

//회원구분
$array = $db->queryFetch(" SELECT department,team,function FROM `mdMember__` WHERE cate='000002002' ");
foreach($array AS $key=>$val) { $array[$key] = explode(',', $val); }

//리스트 설정
$row			= ($_GET['rows']) ? $_GET['rows'] : 10;
$block			= 10;
$totalRec		= $db->queryFetchOne(" SELECT COUNT(*) FROM `mdMember__account` AS A INNER JOIN `mdMember__info` AS B ON A.id=B.id WHERE ".$sq );
$currentPage	= ($_GET['currentPage']) ? $_GET['currentPage'] : 1;
$queryString	= "&amp;type=list&amp;rows=".$_GET['rows']."&amp;lev=".$_GET['lev']."&amp;div=".$_GET['div']."&amp;sh=".$_GET['sh']."&amp;shc=".$_GET['shc']."&amp;userid=".$_GET['userid']."&amp;username=".$_GET['username']."&amp;idcode=".$_GET['idcode']."&amp;email=".$_GET['email']."&amp;phone=".$_GET['phone']."&amp;year=".$_GET['year']."&amp;month=".$_GET['month']."&amp;day=".$_GET['day'];
$pagingInstance = new Paging($totalRec, $currentPage, $row, $block);
$pagingInstance->mode = "module";
$pagingInstance->addQueryString($queryString);
$pagingResult	=  $pagingInstance->result("../modules/mdMember/manage/_controll.php");
?>

<div class="table">
<div class="line bg_white">

<div class="sub_Header">
<h5><span class="orange">회원정보 및 관리</span></h5>
<div style="float: right; padding: 6px 6px 0 0;"><span class="button bred small"><button type="button" onclick="$('#detailSearch').animate({height:'toggle', opacity:'toggle'}, 'fast');">상세검색</button></span></div>
<div class="clear"></div>
</div>
<div style="padding: 5px 0;"><?php include "./searchDetail.php"; ?></div>

<form name="listForm" enctype="multipart/form-data"	onsubmit="return false;" target="hdFrame">
<input type="hidden" id="type" name="type" value="" />
<input type="hidden" id="rtotal" name="rtotal" value="" />
<input type="hidden" id="parm01" name="parm01" value="" />
<input type="hidden" id="parm02" name="parm02" value="" />
<input type="hidden" id="parm03" name="parm03" value="" />
<dl>
	<dt style="float: left; padding: 5px; vertical-align: center;"><span class="small_gray">총 <strong class="small_orange"><?php echo($totalRec);?></strong>명</span></dt>
	<dd style="float: right;"><select name="chrows" class="bg_gray" style="width: 100px;" onchange="$.insert('#module', '../modules/mdMember/manage/_controll.php?type=list&amp;div=<?php echo($_GET['div']);?>&amp;lev=<?php echo($_GET['lev']);?>&amp;rows='+this.value,null,300)">
		<option value="10"<?php if($_GET['rows']=='10'){echo(' selected="selected" class="green"');}?>>10건씩	보기</option>
		<option value="30"<?php if($_GET['rows']=='30'){echo(' selected="selected" class="green"');}?>>30건씩	보기</option>
		<option value="50"<?php if($_GET['rows']=='50'){echo(' selected="selected" class="green"');}?>>50건씩	보기</option>
		<option value="100"<?php if($_GET['rows']=='100'){echo(' selected="selected" class="green"');}?>>100건씩 보기</option>
	</select></dd>
	<dd style="float: right;"><select name="division" class="bg_gray" style="width: 120px;"	onchange="$.insert('#module', '../modules/mdMember/manage/_controll.php?type=list&amp;lev=<?php echo($_GET['lev']);?>&amp;rows=<?php echo($_GET['rows']);?>&amp;div=<?php echo($_GET['div']);?>&amp;ded='+this.value,null,300)">
		<option value="">업무(학급)별 정렬</option>
		<option value="">-----------</option>
		<?php
		foreach($array['dedicate'] AS $val) 
		{
			echo('<option value="'.$val.'"');
			if($val == $_GET['ded']) { echo(' selected="selected" class="green"'); }
			echo('>'.$val.'</option>');
		}
		?>
	</select></dd>
	<dd style="float: right;"><select name="division" class="bg_gray" style="width: 100px;" onchange="$.insert('#module', '../modules/mdMember/manage/_controll.php?;type=list&amp;lev=<?php echo($_GET['lev']);?>&amp;rows=<?php echo($_GET['rows']);?>&amp;div='+this.value,null,300)">
		<option value="">부서별 정렬</option>
		<option value="">-----------</option>
		<?php
		foreach($array['team'] AS $val) 
		{
			echo('<option value="'.$val.'"');
			if($val == $_GET['div']) { echo(' selected="selected" class="green"'); }
			echo('>'.$val.'</option>');
		}
		?>
	</select></dd>
	<?php if(in_array('mdSms', $cfg['modules'])) { ?>
	<dd style="float: left; padding: 0 0 5px 5px;"><span class="button small bmetal"><button type="button" onclick="return $.checkSend(2)">문자발송</button></span></dd>
	<?php } ?>
	<?php if(in_array('mdMail', $cfg['modules'])) { ?>
	<dd style="float: left; padding: 0 0 5px 5px;"><span class="button small bmetal"><button type="button" onclick="return $.checkSend(1)">메일발송</button></span></dd>
	<?php } ?>
	<dd style="float: left; padding: 0 0 5px 5px;"><span class="button small bmetal"><button onclick="return $.checkSend(3)">그룹변경</button></span></dd>
	<dd style="float: left; padding: 0 0 5px 5px;"><span class="button small bmetal"><button onclick="return $.checkSend(4)">업무(학급)변경</button></span></dd>
	<dd style="float: left; padding: 0 0 5px 5px;"><a href="../modules/mdMember/manage/toExcel.php?lev=<?php echo($_GET['lev']);?>" class="button small bmetal"><span>엑셀저장</span></a></dd>
</dl>
<div class="clear"></div>

<table class="table_list" style="width: 100%;">
	<caption></caption>
	<col width="30">
	<col width="100">
	<col>
	<col width="250">
	<thead>
		<tr>
			<th class="first"><p class="center"><input type="checkbox" checked="checked" id="allarticle" name="allarticle" style="vertical-align: top; cursor: pointer;" title="전체선택" /></p></th>
			<th><p class="center">아이디</p></th>
			<th><p class="center">회원 간략정보</p></th>
			<th><p class="center">관리 및 설정</p></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$db->query(" SELECT * FROM `mdMember__account` AS A LEFT JOIN `mdMember__info` AS B ON A.id=B.id WHERE ".$sq." ORDER BY A.sort ASC,A.dateReg DESC ".$pagingResult['LimitQuery'] );

	if($db->getNumRows() < 1) {
		echo('<tr><td class="blank" colspan="4">등록된 회원이 존재하지 않습니다.</td></tr>');
	} else {
		while($Rows = $db->fetch()) {
			$userid				= ($Rows['level'] < 1) 	? '<span class="blue">'.$Rows['id'].'</span>' : $Rows['id'];
			$address01			= ($Rows['address01'])	? explode(" ", $Rows['address01']) : "-";
			$Rows['mobile01']	= ($Rows['mobile01'])	? $Rows['mobile01'] : "-";
			$Rows['sex']		= ($Rows['sex'])		? ($Rows['sex']=='1' || $Rows['sex']=='3') ? "남" : "여" : "-";
			echo('<tr>
			<th><p class="center"><input type="checkbox" id="choice_'.$Rows['seq'].'" class="articleCheck" name="choice[]" value="'.$Rows['id'].'" /></p></th>
			<td><label for="choice_'.$Rows['seq'].'">'.$Rows['id'].'</label></td>
			<td title="'.$Rows['name'].'회원 상세보기"><span><a href="#none" onclick="memberInfo(\''.$Rows['id'].'\');"><strong>'.$Rows['name'].'</strong></span>&nbsp;<span class="small_gray">('.$Rows['sort'].'ㆍ'.$Rows['department'].'ㆍ'.$Rows['team'].'ㆍ'.$Rows['function'].'ㆍ'.$address01['0'].')</span></a></td>
			<td class="dash_bottom bg_gray">');
			if($_GET['lev'] == '3' || $_GET['lev'] == '4') {
				echo('<span><a href="#none" onclick="$.insert(\'#module\',\'../modules/mdMember/manage/_controll.php?type=listMove&amp;idx='.$Rows['seq'].'&amp;sort='.$Rows['sort'].'&amp;div='.$Rows['team'].'&amp;lev='.$Rows['level'].'&amp;rows='.$_POST['rows'].'&amp;move=up\',300)"><img src="'.$cfg['droot'].'image/button/btn_s_up.gif" alt="위로" title="위로" /></a>&nbsp;<a href="#none" onclick="$.insert(\'#module\', \'../modules/mdMember/manage/_controll.php?type=listMove&amp;idx='.$Rows['seq'].'&amp;sort='.$Rows['sort'].'&amp;div='.$Rows['team'].'&amp;lev='.$Rows['level'].'&amp;rows='.$_POST['rows'].'&amp;move=dw\',300)"><img src="'.$cfg['droot'].'image/button/btn_s_down.gif" alt="아래로" title="아래로" /></a></span>');
			} else if($Rows['level'] == 0) {
				echo('<span class="orange">탈퇴회원</span>');
			}
			echo('&nbsp;<span><a href="#none" onclick="memberInfo(\''.$Rows['id'].'\');" class="button small bgray"><span>정보</span></a>');
			if($func->checkModule('mdMessage')) {
				echo('&nbsp;<a href="#none" onclick="$.dialog(\'../modules/mdMember/manage/_controll.php?type=message&amp;user='.$Rows['id'].'\',800,480)" class="button small bgray"><span>쪽지</span></a>');
			}
			if($func->checkModule('mdMileage')) {
				echo('&nbsp;<a href="#none" onclick="$.dialog(\'../modules/mdMember/manage/_controll.php?type=mileage&amp;user='.$Rows['id'].'\',800,480)" class="button small bgray"><span>적립</span></a>');
			}
			if($func->checkModule('mdPayment')) {
				echo('&nbsp;<a href="#none" onclick="$.dialog(\'../modules/mdMember/manage/_controll.php?type=money&amp;user='.$Rows['id'].'\',800,480)" class="button small bgray"><span>예치</span></a>&nbsp;<a href="#none" onclick="$.dialog(\'../modules/mdMember/manage/_controll.php?type=billing&amp;user='.$Rows['id'].'\',800,480)" class="button small bgray"><span>결제</span></a>');
			}
			if($func->checkModule('mdOrder')) {
				echo('&nbsp;<a href="#none" onclick="$.dialog(\'../modules/mdMember/manage/_controll.php?type=order&amp;user='.$Rows['id'].'\',800,480)" class="button small bgray"><span>주문</span></a></span>');
			}
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
$(document).ready(function() 
		{
	$("tbody tr").bind("mouseenter mouseleave", function(e){
		$(this).toggleClass("this");
	});
  	$("input[type=text],input[type=password],textarea").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active");});
	$("#allarticle").click(function() {
		if ($("#allarticle:checked").length > 0) {
			$("input[className=articleCheck]:checked").attr("checked", "");
		} else {
			$("input[className=articleCheck]:not(checked)").attr("checked", "checked");
		}
	});
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
				$.dialog('../modules/mdMail/manage/input.php?type=<?php echo($sess->encode('smsInput'));?>',null,500,340);
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
				$.checkFarm(frm, '../modules/mdSms/manage/input.php','dialog',null,500,340);
			}
    	} else if(a == 3) {
			if(confirm(cnt + "명의 등급을 변경하시습니까?") == true){
        		frm.type.value = "group";
				frm.rtotal.value = cnt;
				$.checkFarm(frm, '../modules/mdMember/manage/toGroup.php','dialog',null,300,120);
			}
    	} else if(a == 4) {
			if(confirm(cnt + "명의 업무(학급)을 변경하시습니까?") == true){
        		frm.type.value = "division";
				frm.rtotal.value = cnt;
				$.checkFarm(frm, '../modules/mdMember/manage/toDivision.php','dialog',null,300,120);
			}
		} else {
			alert('잘못된 코드입니다.');
			return false;
		}
	}
}
//]]>
</script>

