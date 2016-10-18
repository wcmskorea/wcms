<?php
/*---------------------------------------------------------------------------------------
 | 상담모듈01 리스트
 |----------------------------------------------------------------------------------------
 | Relationship : mdApp01/manage/_controll.php
 | Last (2008.10.04 : 이성준)
 */
require_once __PATH__."_Admin/include/commonHeader.php";

//EUC-KR의 경우 문자 인코딩
if($cfg['charset'] == 'euc-kr') {
	$_GET['div'] = ICONV("UTF-8", "CP949", $_GET['div']);
	$_GET['shc'] = ICONV("UTF-8", "CP949", $_GET['shc']);
}

//구분별 검색
if($_GET['div'] != "") { $sq .= " AND division='".$_GET['div']."' "; }

//상태별 검색
if(is_numeric($_GET['state'])) { $sq .= " AND state='".$_GET['state']."'"; }

//조건별 검색
switch($_GET['sh']) {
	case "username":
		$sq .= " AND name like '".$_GET['shc']."%'";
		break;
	case "userid":
		$sq .= " AND id like '".$_GET['shc']."%'";
		break;
	case "phone":
		$sq .= " AND (phone like '%".$_GET['phone']."%' OR mobile like '%".$_GET['phone']."%')";
		break;
	case "detail" :
		if($_GET['userid'])		{ $sq .= " AND id like '".$_GET['userid']."%'"; }
		if($_GET['username'])	{ $sq .= " AND name like '".$_GET['username']."%'"; }
		if($_GET['email'])		{ $sq .= " AND email like '%".$_GET['email']."%'"; }
		if($_GET['phone'])		{ $sq .= " AND (phone like '%".$_GET['phone']."%' OR mobile like '%".$_GET['phone']."%')"; }
		if($_GET['dateReg'])	{ $sq .= " AND dateReg BETWEEN '".strtotime($_GET['dateReg'].' 00:00:00')."' AND '".strtotime($_GET['dateReg'].' 23:59:59')."'"; }
		if($_GET['dateRev'])	{ $sq .= " AND schedule BETWEEN '".strtotime($_GET['dateRev'].' 00:00:00')."' AND '".strtotime($_GET['dateRev'].' 23:59:59')."'"; }
		break;
}
//echo $sq;

//구분
$config = (array)unserialize($db->queryFetchOne(" SELECT config FROM `mdApp01__` WHERE cate='".__CATE__."' "));
$division = ($config['division']) ? explode(",", $config['division']) : "";
$result = ($config['result']) ? explode(",", $config['result']) : "";

//리스트 설정
$row			= ($_GET['rows']) ? $_GET['rows'] : 30;
$block			= 30;
$totalRec		= $db->queryFetchOne(" SELECT COUNT(*) FROM `mdApp01__content` WHERE cate='".__CATE__."' ".$sq );
$currentPage	= ($_GET['currentPage']) ? $_GET['currentPage'] : 1;
$pagingInstance = new Paging($totalRec, $currentPage, $row, $block);
$pagingInstance->mode = "module";
$pagingInstance->addQueryString("&amp;type=list&amp;cate=".__CATE__."&amp;state=".$_GET['state']);
$pagingResult	=  $pagingInstance->result("../modules/mdApp01/manage/_controll.php");
?>

<h2><span class="arrow">▶</span>문의·상담 관리</h2>
<div class="table">
<div class="line bg_white">

<div style="padding-bottom:5px;">
<?php include "./searchDetail.php"; ?>
</div>

<form name="listForm" id="listForm" enctype="multipart/form-data"	onsubmit="return false;">
<input type="hidden" name="cate" value="<?php echo(__CATE__);?>">
<input type="hidden" name="type" value="" />
<input type="hidden" name="rtotal" value="" />
<input type="hidden" name="moduleName" value="mdApp01" />
<input type="hidden" name="state" value="<?php echo($_GET['state']);?>" />
<dl>
	<dt style="float: left; padding: 5px; vertical-align: center;"><span class="small_gray">총 <strong class="small_orange"><?php echo($totalRec);?></strong>명</span></dt>
	<dd style="float: right;"><select name="chrows" class="bg_gray" style="width: 100px;" onchange="$.insert('#module', '../modules/mdApp01/manage/_controll.php?cate=<?php echo(__CATE__);?>&amp;div=<?php echo($_GET['div']);?>&amp;state=<?php echo($_GET['state']);?>&amp;rows='+this.value,null,300)">
		<option value="10"<?php if($_GET['rows']=='10'){echo('selected="selected" class="green"');}?>>10건씩 보기</option>
		<option value="30"<?php if($_GET['rows']=='30'){echo('selected="selected" class="green"');}?>>30건씩 보기</option>
		<option value="50"<?php if($_GET['rows']=='50'){echo('selected="selected" class="green"');}?>>50건씩 보기</option>
		<option value="100"<?php if($_GET['rows']=='100'){echo('selected="selected" class="green"');}?>>100건씩 보기</option>
		<option value="300"<?php if($_GET['rows']=='300'){echo('selected="selected" class="green"');}?>>300건씩 보기</option>
		<option value="500"<?php if($_GET['rows']=='500'){echo('selected="selected" class="green"');}?>>500건씩 보기</option>
	</select></dd>

	<dd style="float: right;"><select name="division" class="bg_gray" onchange="$.insert('#module', '../modules/mdApp01/manage/_controll.php?cate=<?php echo(__CATE__);?>&amp;state=<?php echo($_GET['state']);?>&amp;rows=<?php echo($_GET['rows']);?>&amp;div='+this.value,null,300)">
		<option value="">요청 구분별 정렬</option>
		<?php
		foreach($division AS $key=>$val)
		{
			if($val) {
				echo('<option value="'.$key.'"');
				if($_GET['div'] == "$key") { echo(' selected="selected" class="green"'); }
				echo('>('.intval($key+1).') '.$val.'</option>');
			}
		}
		?>
	</select></dd>
	<dd style="float: left; padding: 0 0 5px 5px;"><span class="btnPack white small"><button type="button" onclick="return $.checkSend(3)">상태변경</button></span></dd>
	<dd style="float: left; padding: 0 0 5px 5px;"><span class="btnPack white small"><button type="button" onclick="return $.checkSend(2)">문자발송</button></span></dd>
	<dd style="float: left; padding: 0 0 5px 5px;"><a href="../modules/mdApp01/manage/appToExcel.php?cate=<?php echo(__CATE__);?>&state=<?php echo($_GET['state']);?>" class="btnPack white small"><span>엑셀저장</span></a></dd>
</dl>
<div class="clear"></div>

<table class="table_list" style="width: 100%;">
	<caption>카테고리 및 모듈 설정</caption>
	<col width="30">
	<col width="120">
	<col width="100">
	<col>
	<col width="90">
	<col width="90">
	<thead>
		<tr>
			<th class="first"><p class="center"><input type="checkbox" checked="checked" id="allarticle" name="allarticle" style="vertical-align: top; cursor: pointer;" title="전체선택" /></p></th>
			<th><p class="center">상 태</p></th>
			<th><p class="center">등록일</p></th>
			<th><p class="center">신청자 정보</p></th>
			<th><p class="center">수정일</p></th>
			<th><p class="center">관 리</p></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$db->query(" SELECT * FROM `mdApp01__content` WHERE cate='".__CATE__."' ".$sq." ORDER BY dateReg DESC ".$pagingResult['LimitQuery'] );
	if($db->getNumRows() < 1)
	{
		echo('<tr><td class="blank" colspan="5">등록된 내역이 존재하지 않습니다.</td></tr>');
	} else
	{
		while($Rows = $db->fetch())
		{
			$contentAdd = (array)unserialize($Rows['contentAdd']);
			$appDivision = ($division && $Rows['division']) ? $division[$Rows['division']] : $division['0'];
			$Rows['division'] = ($Rows['division'] == '100') ? "문자상담" : $appDivision;
			$Rows['division'] = (!$Rows['division']) ? "상담" : $Rows['division'];
			$Rows['id'] = (!$Rows['id']) ? "" : "(".$Rows['id'].")";	//회원로그인후 예약신청자 아이디 표시

			//스케줄 일정표기 추가 - 2012년 5월 9일 수요일 : 이성준
			//$schedule = mktime($contentAdd['schedulehour'], $contentAdd['schedulemin'], $contentAdd['schedulesec'], $contentAdd['schedulemonth'], $contentAdd['scheduleday'], $contentAdd['scheduleyear']);
			//$db->query("UPDATE `mdApp01__content` SET schedule='".$schedule."' WHERE seq='".$Rows['seq']."'", 2);
			$schedule = ($Rows['schedule']) ? date('y.m.d H:i', $Rows['schedule'])." / " : null;

			echo('<tr><td><p class="center"><input type="checkbox" class="articleCheck" name="choice[]" value="'.$Rows['seq'].'" /></p></td>
			<th><p class="center">'.$result[$Rows['state']].'</p></th>
			<td><p class="center">'.date('Y/m/d H시', $Rows['dateReg']).'</p></td>
			<td title="요청건 상세보기"><a href="javascript:;" onclick="$.dialog(\'../modules/mdApp01/manage/_controll.php?type=detail&amp;idx='.$Rows['seq'].'\',null,800,480)"><strong>'.$Rows['name'].'</strong>'.$Rows['id'].'님께서 [ '.$schedule.'<span class="colorOrange">'.$Rows['division'].'</span> ] 요청 하셨습니다.</a></td>
			<td><p class="center wrap80">'.$Rows['info'].'</p></td>
			<td><p class="center"><span><a href="javascript:;" onclick="$.dialog(\'../modules/mdApp01/manage/_controll.php?type=detail&amp;idx='.$Rows['seq'].'&amp;currentPage='.$currentPage.'\',null,800,480);" class="btnPack black small"><span>수정</span></a></span>
			<span><a href="javascript:;" onclick="if(delThis()){$.message(\'../modules/mdApp01/manage/_controll.php?type=dpost&amp;idx='.$Rows['seq'].'&amp;currentPage='.$currentPage.'\')}" class="btnPack red small"><span>삭제</span></a></span></p>
			</td>
			</tr>');
			unset($schedule);
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
$.checkSend = function (a)
{
	var frm = document.getElementById('listForm');
	var cnt = 0;
	for(var i=0; i<frm.elements.length; i++) {
		var e = frm.elements[i];
		if(e.type == "checkbox" && e.id != "allarticle") {
			if(e.checked == true) {
				cnt++;
			}
		}
	}
	if(cnt < 1) {
		alert('선택된 내역이 없습니다.');
		return false;
	} else {
		if(a == 1) {
			if(confirm(cnt + "명에게 그룹메일을 발송하시습니까?") == true){
				$.dialog('../modules/mdMail/manage/input.php?type=<?php echo($sess->encode('smsInput'));?>',null,500,340)
				frm.action = "member_groupMail.php";
				frm.method = 'POST';
				frm.target = 'group';
				frm.rtotal.value = cnt;
				frm.submit();
			}
		} else if(a == 2) {
			if(confirm(cnt + "명에게 SMS를 발송하시습니까?") == true){
				frm.type.value = "<?php echo($sess->encode('smsInput'));?>";
				frm.rtotal.value = cnt;
				$.checkFarm(frm, '../modules/mdSms/manage/input.php','dialog','',500,340)
			}
	} else if(a == 3) {
			//if(confirm(cnt + "건의 상태를 변경하시습니까?") == true){
				frm.type.value = "state";
				frm.rtotal.value = cnt;
				$.checkFarm(frm, '../modules/mdApp01/manage/appToState.php','dialog','',300,150);
			//}
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