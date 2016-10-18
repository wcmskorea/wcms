<?php
$year		= ($_GET['year']) ? $_GET['year'] : date("Y");
$month		= ($_GET['month']) ? str_pad($_GET['month'], 2, "0", STR_PAD_LEFT) : date("m");
$day		= str_pad($_GET['day'], 2, "0", STR_PAD_LEFT);
$sq			= ($_GET['day']) ? "DATE_FORMAT(date,'%Y-%m-%d') = '".$year."-".$month."-".$day."'" : "DATE_FORMAT(date,'%Y-%m') = '".$year."-".$month."'";
$todayCnt	= $db->queryFetchOne("SELECT COUNT(*) FROM `mdAnalytic__track` WHERE date=CURDATE()");
$monthCnt	= $db->queryFetchOne("SELECT COUNT(*) FROM `mdAnalytic__track` WHERE YEAR(date)='".$year."' AND MONTH(date) = '".$month."'");
$totalCnt	= $db->queryFetchOne("SELECT COUNT(*) FROM `mdAnalytic__track`");

?>

<div class="table"><div class="line bg_white">
<fieldset id="help">
<legend>→ 날짜별 방문자 통계 ←</legend>
<ul>
	<li>사이트 Launching 이후 방문자 통계입니다.</li>
	<li>일별과 IP별 통계는 24시간내 1회 Check이며, 1회이상 방문IP만 보여집니다.</li>
	<li>방문자 내역은 시간이 지날수록 많은 양의 데이터가 누적되어 용량과 속도 저하의 원인이 되므로 최근 2년간 데이터만 열람하실 수 있습니다.</li>
</ul>
</fieldset>
<div class="pd3">
<table class="table_list" summary="방문자 접속현황" style="width:100%;">
	<caption>방문자 접속현황</caption>
	<col width="140" />
	<col width="140" />
	<col />
	</tr>
	<thead>
	<tr><th class="first"><p class="center"><span class="blue">금일</span> 접속자 수</th>
			<th><p class="center"><span class="red"><?php echo($month);?>월</span> 접속자 수</th>
			<th><p class="center">총누적 접속자</th>
	</tr>
	</thead>
	<tbody>
    <tr><td><p class="center pd5"><strong class="blue"><?php echo(number_format($todayCnt));?></strong> 명</p></td>
				<td><p class="center pd5"><strong class="red"><?php echo(number_format($monthCnt));?></strong> 명</p></td>
				<td><p class="center pd5"><strong class="black"><?php echo(number_format($totalCnt));?></strong> 명</p></td>
		</tr>
	</tbody>
</table>
</div>
</div></div>

<div class="pd3">
<dl>
	<dt style="float:left; padding:3px; vertical-align:center;"></dt>
	<dd style="float:right; padding:1px;"><span class="btnPack green medium strong"><button type="submit" onclick="$.insert('#module', '../modules/mdAnalytic/manage/_controll.php', '&amp;type=logVisitorDay&amp;year=' + $('#year').val() + '&amp;month=' + $('#month').val(), 300)">검색</button></span></dd>
	<dd style="float:right; padding:2px;">
    <select id="month" name="month" class="bg_gray">
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
    <select id="year" name="year" class="bg_gray">
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
</div>
<div class="clear"></div>

<div class="sub_Header">
	<h5><span class="colorOrange">일별 사이트 방문자 현황</span></h5>
	<div class="clear"></div>
</div>

<table class="table_list" summary="일별 사이트 방문자 현황" style="width:100%;">
	<caption>일별 사이트 방문자 현황</caption>
	<col width="100">
	<col width="60">
	<col width="60">
	<col>
	</tr>
	<thead>
	<tr><th scope="col" class="first"><p class="center">날짜(요일)</p></th>
			<th scope="col"><p class="center">방문자수</p></th>
			<th scope="col"><p class="center">방문횟수</p></th>
			<th scope="col"><p class="center">그래프</p></th>
	</tr>
	</thead>
	<tbody>
	<?php
	$total = 0;
	$db->query("SELECT COUNT(*) AS count, SUM(counting) AS cnt, WEEKDAY(date) AS week, date FROM `mdAnalytic__track` WHERE ".$sq." GROUP BY date");
	if($db->getNumRows() < 1)
	{
		echo('<tr><td class="blank" colspan="5">내역이 없습니다.</td></tr>');
	} else
	{
		while($Rows = $db->fetch())
		{
			$week = array('월','화','수','목','금','토','일');
			if($monthCnt != 0) { $per = substr(($Rows['count']*100)/$monthCnt, 0 , 4); }
			echo('<tr><td>&nbsp;'.$Rows['date']." (".$week[$Rows['week']].")".'</td>
				<td><p class="center blue">'.number_format($Rows['count']).'</p></td>
				<td><p class="center">'.number_format($Rows['cnt']).'</p></td>
				<td>
					<dl class="ratio">
					<dt>접속율</dt>
					<dd><p style="width:300px;"><span class="graph" style="width:'.$per.'%"></span></p></dd>
					<dd>'.$per.'%</dd>
					</dl>
				</td>
			</tr>');
		}
	}
	?>
	</tbody>
</table>
