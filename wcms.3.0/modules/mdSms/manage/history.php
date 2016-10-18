<?php
require_once "../../../_config.php";
require_once __PATH__."/_Admin/include/commonHeader.php";

$year			= ($_GET['year'])	? $_GET['year'] : date("Y");
$month		= ($_GET['month']) ? str_pad($_GET['month'], 2, "0", STR_PAD_LEFT)	: date("m");
$day			= str_pad($_GET['day'], 2, "0", STR_PAD_LEFT);

$row			= 15;
$block		= 10;
$sq				= ($_GET['day']) ? "FROM_UNIXTIME(sendDate,'%Y%m%d') = '".$year.$month.$day."'" : "FROM_UNIXTIME(sendDate,'%Y%m') = '".$year.$month."'";

$totalRec		= $db->QueryFetchOne("SELECT COUNT(*) FROM `mdSms__history` WHERE ".$sq);
$queryString	= "&amp;type=".$sess->encode('smsInput')."&amp;year=".$year."&amp;month=".$month;
$pagingInstance = new Paging($totalRec, $currentPage, $row, $block);
$pagingInstance->mode = "module";
$pagingInstance->addQueryString($queryString);
$pagingResult	=  $pagingInstance->result("../modules/mdSms/manage/history.php");

#전송결과
$rec			= $db->QueryFetch("SELECT SUM(if(rst = '01', 1, 0)) AS success,
				SUM(if(rst = '02', 1, 0)) AS ing,
				SUM(if(rst = '00', 1, 0)) AS fail,
				SUM(if(rst = '98', 1, 0)) AS mount
				FROM `mdSms__history` WHERE ".$sq);
$etc			= ($totalRec) - ($rec[success] + $rec[spam] + $rec[out]);
?>

<h2><span class="arrow">▶</span>문자·SMS 관리&nbsp;&nbsp;<span class="normal">&gt;&nbsp;발송내역 조회</span></h2>

<div class="table"><div class="line">
	<fieldset id="help">
	<ul>
		<li>발송시 별도 저장된 문자내역입니다.</li>
		<li>발송 내역은 <span class="red">최근 3개월</span>의 내역만 확인 가능합니다.</li>
		<li>발송후 발송대기에 오래 머문경우 통신업체와 수신이 불안정하거나 사용자가 많아 늦어지는 현상입니다</li>
	</ul>
	</fieldset>
</div></div>
<br />

<div class="sub_Header">
  <h5><span class="colorOrange">SMS문자 발송현황</span></h5>
  <div class="clear"></div>
</div>
<table class="table_basic" summary="SMS문자 발송현황" style="width:100%;">
  <caption>SMS문자 발송현황</caption>
  <col width="80">
  <col width="80">
  <col width="80">
  <col width="80">
  <col>
  </tr>
  <thead>
  <tr><th class="first"><p class="center">발송성공</p></th>
      <th><p class="center">발송실패</p></th>
      <th><p class="center">수량부족</p></th>
      <th><p class="center">기타처리</p></th>
      <th><p class="center">전체 발송건수</p></th>
  </tr>
  </thead>
  <tbody>
    <tr><td><p class="center pd3"><strong class="colorBlue"><?=str_pad($rec[success], strlen($totalRec), "0", STR_PAD_LEFT)?></strong> 건</p></td>
        <td><p class="center pd3"><span class="colorBlack"><?=str_pad($rec[fail], strlen($totalRec), "0", STR_PAD_LEFT)?></span> 건</p></td>
        <td><p class="center pd3"><span class="colorBlack"><?=str_pad($rec[mount], strlen($totalRec), "0", STR_PAD_LEFT)?></span> 건</p></td>
        <td><p class="center pd3"><span class="colorBlue"><?=str_pad($etc, strlen($totalRec), "0", STR_PAD_LEFT)?></span> 건</p></td>
        <td><p class="center pd3"><strong class="colorRed"><?=number_format($totalRec)?></strong> 건</p></td>
    </tr>
  </tbody>
</table>

<div class="pd3"></div>

<dl>
	<dt style="float:left; padding:3px; vertical-align:center;"><span class="small_gray">총 <strong class="small_orange"><?=$totalRec?></strong> 건</span></dt>
	<dd style="float:right; padding:1px;"><span class="btnPack small green"><button type="button" onclick="$.insert('#module', '../modules/mdSms/manage/history.php', '&amp;type=<?=$sess->encode('smsInput')?>&amp;year=' + $('#year').val() + '&amp;month=' + $('#month').val() + '&amp;day=' + $('#day').val(), 300)">검색</button></span></dd>
	<dd style="float:right; padding:2px;">
		<select id="day" name="day">
			<option value="">전체</option>
			<?php
			for($i=1; $i<=31; $i++) {
				echo '<option value="'.$i.'"';
				if($i == $day) echo ' selected="selected" style="color:#990000"';
				echo '>'.$i.'일</option>';
			}
			?>
		</select>
	</dd>
	<dd style="float:right; padding:2px;">
		<select id="month" name="month">
			<?php
			for($i=1; $i<=12; $i++) {
				echo '<option value="'.$i.'"';
				if($i == $month) echo ' selected="selected" style="color:#990000"';
				echo '>'.$i.'월</option>';
			}
			?>
		</select>
	</dd>
	<dd style="float:right; padding:2px;">
		<select id="year" name="year">
			<?php
			for($i=date("Y")-1; $i<=date("Y"); $i++) {
				echo '<option value="'.$i.'"';
				if($i == $year) echo ' selected="selected" style="color:#990000"';
				echo '>'.$i.'년</option>';
			}
			?>
		</select>
	</dd>
</dl>
<div class="clear"></div>

<div class="sub_Header">
  <h5><span class="colorOrange">SMS문자 발송내역</span></h5>
  <div class="clear"></div>
</div>
<table class="table_list" summary="SMS문자 발송내역" style="width:100%;">
	<caption>SMS문자 발송내역</caption>
	<col width="60">
	<col width="100">
	<col width="40">
	<col>
	<col width="80">
	<col width="80">
	</tr>
	<thead>
	<tr><th class="first"><p class="center">발신여부</p></th>
		<th><p class="center">발신시간</p></th>
		<th><p class="center">형태</p></th>
		<th><p class="center">발신문자</p></th>
		<th><p class="center">발신번호</p></th>
		<th><p class="center">수신번호</p></th>
	</tr>
	</thead>
	<tbody>
	<?php
	$db->Query(" SELECT * FROM `mdSms__history` WHERE ".$sq." ORDER BY regDate DESC ".$pagingResult[LimitQuery]);
	if($db->getNumRows() < 1)
	{
		echo '<tr><td class="blank" colspan="6">발송된 내역이 없습니다.</td></tr>';
	}
	else
	{
		while($Rows = $db->Fetch())
		{
			$mesg	= $func->cutStr($Rows[mesg],30,"...");
			$tag	= ($Rows[sendDate] > $Rows[regDate]) ? '<span class="red">예약</span>' : '<span class="blue">일반</span>';
			switch($Rows[rst]) {
				case '01':	$rst = '<span class="blue">발신성공</span>';	break;
				case '00':	$rst = '<span class="red">발신실패</span>';		break;
				case '96':	$rst = '<span class="red">번호오류</span>';		break;
				case '98':	$rst = '<span class="red">수량부족</span>';		break;
				default:		$rst = '<span class="black">기　　타</span>';			break;
			}
			$count = count(explode("||", $Rows[receiver]));
			$Rows[receiver] = ($count > 1) ? $count."건" : $Rows[receiver];
	?>
		<tr><th><p class="center"><?php echo($rst);?></p></th>
				<td><p class="center"><?php echo(date("y-m-d H:i", $Rows[sendDate]));?></p></td>
				<td><p class="center"><?php echo($tag);?></p></td>
				<td><?php echo($Rows[mesg]);?></td>
				<td><p class="center"><?php echo(str_replace("-",null,$Rows[sender]));?></p></td>
				<td><p class="center"><?php echo(str_replace("-",null,$Rows[receiver]));?></p></td>
		</tr>
	<?php
		}
	}
	?>
	</tbody>
</table>
<div class="pageNavigation"><?php echo($pagingResult[PageLink]);?></div>

<?php
require_once __PATH__."/_Admin/include/commonScript.php";
?>