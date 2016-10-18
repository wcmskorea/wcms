<?php
require_once "../../../_config.php";
require_once __PATH__."_Admin/include/commonHeader.php";

$year			= ($_GET['year']) ? $_GET['year'] : date("Y");
$month		= ($_GET['month']) ? str_pad($_GET['month'], 2, "0", STR_PAD_LEFT) : date("m");
$day			= ($_GET['day']) ? str_pad($_GET['day'], 2, "0", STR_PAD_LEFT) : date("d");
?>

<fieldset id="help">
<legend>→ IP별 방문자 통계 ←</legend>
<ul>
	<li>검색 날짜의 한달 이전부터 누적방문이 "5회" 이상인 방문자 목록입니다.</li>
	<li>해당 IP를 클릭하면 사이트내 <u>국가지역, ISP정보, 이동경로 분석(회원이 사이트에서 이동한 내역)</u>을 열람할 수 있습니다.</li>
	<li>방문자 내역은 시간이 지날수록 많은 양의 데이터가 누적되어 용량과 속도 저하의 원인이 되므로 최근 2년간 데이터만 열람하실 수 있습니다.</li>
</ul>
</fieldset>

<dl>
	<dt style="float:left; padding:3px; vertical-align:center;"></dt>
	<dd style="float:right; padding:1px;"><span class="btnPack green medium strong"><button type="submit" onclick="$.insert('#tabBody02', '../modules/mdAnalytic/manage/logVisitor_trackBlack.php', '&amp;year=' + $('#year').val() + '&amp;month=' + $('#month').val() + '&amp;day=' + $('#day').val(), 300)">검색</button></span></dd>
	<dd style="float:right; padding:2px;">
		<select id="day" name="day" class="bg_gray">
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
<div class="clear"></div>

<table class="table_list" summary="유입 IP별 방문자 현황" style="width:100%;">
	<caption>유입 IP별 방문자 현황</caption>
	<col>
	<col width="100">
	<col width="100">
	<col width="100">
	<col width="100">
	</tr>
	<thead>
	<tr><th class="first"><p class="center">IP정보</p></th>
		<th><p class="center">접속국가</p></th>
		<th><p class="center">당일방문</p></th>
		<th><p class="center">누적방문</p></th>
		<th><p class="center">페이지 이동</p></th>
	</tr>
	</thead>
	<tbody>
	<?php
	$total = $db->queryFetchOne("SELECT SUM(counting) FROM `mdAnalytic__track` WHERE DATE_FORMAT(date,'%Y-%m-%d') = '".$year."-".$month."-".$day."'");
	$db->query("SELECT * FROM `mdAnalytic__track` WHERE DATE_FORMAT(date,'%Y-%m-%d') = '".$year."-".$month."-".$day."' AND counting > '0' GROUP BY ip ORDER BY LENGTH(info) DESC");
	if($db->getNumRows() < 1)
	{
		echo('<tr><td class="blank" colspan="4">내역이 없습니다.</td></tr>');
	}
	else
	{
		while($Rows = $db->fetch())
		{
			$recent = $db->queryFetchOne("SELECT SUM(counting) FROM `mdAnalytic__track` WHERE ip='".$Rows['ip']."' AND date>'".intval(time()-(86400*30))."'", 2);
			$tracking = explode(">", $Rows['info']);
			if($recent > 5)
			{
				$per1 = ($Rows['counting'] && $total) ? substr(($Rows['counting'] * 100) / $total, 0, 4) : 0;
				echo('<tr>
					<th style="cursor:pointer" onclick="$.dialog(\'../modules/mdAnalytic/manage/logVisitor_trackInfo.php\', \'&ip='.$Rows['ip'].'&skin='.$Rows['skin'].'&date='.$Rows['date'].'\', 600, 600);">'.$Rows['ip'].' '.$key.'</th>
					<td><p class="center"><span class="blue">'.geoip_country_code_by_name($Rows['ip']).'</span></p></td>
					<td><p class="center"><span class="blue">'.$Rows['counting'].'</span></p></td>
					<td><p class="center"><span class="blue">'.$recent.'</span></p></td>
					<td><p class="center">'.intval(count($tracking)-1).' 회</p></dl>
					</td>
				</tr>');
			}
			unset($recent, $per1);
		}
	}
	?>
	</tbody>
</table>
