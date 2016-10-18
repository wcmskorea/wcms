<?php
require_once "../../../_config.php";
require_once __PATH__."_Admin/include/commonHeader.php";

$year			= ($_GET['year']) ? $_GET['year'] : date("Y");
$month		= ($_GET['month']) ? str_pad($_GET['month'], 2, "0", STR_PAD_LEFT) : date("m");
$day			= ($_GET['day']) ? str_pad($_GET['day'], 2, "0", STR_PAD_LEFT) : date("d");
?>

<fieldset id="help">
<legend>→ 날짜별 키워드 순위 ←</legend>
<ul>
	<li>사이트 오픈 이후 포털 및 사이트내에서 검색한 날짜별 키워드 순위</li>
	<li>"유입 사이트별 통계"를 열람해야만 정확한 유입 키워드 순위가 반영됩니다.</li>
</ul>
</fieldset>

<dl>
	<dt style="float:left; padding:3px; vertical-align:center;"></dt>
	<dd style="float:right; padding:1px;"><span class="btnPack green medium strong"><button type="submit" onclick="$.insert('#tabBody02', '../modules/mdAnalytic/manage/logVisitor_keywordDay.php', '&amp;year=' + $('#year').val() + '&amp;month=' + $('#month').val() + '&amp;day=' + $('#day').val(), 300)">검색</button></span></dd>
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

<table class="table_list" summary="TOP 100" style="width:100%;">
	<caption>날짜별 키워드 순위</caption>
	<col>
	<col width="100">
	<col width="100">
	<col width="100">
	</tr>
	<thead>
	<tr><th class="first"><p class="center">키워드</p></th>
		<th><p class="center">순위</p></th>
		<th><p class="center">횟수</p></th>
	</tr>
	</thead>
	<tbody>
	<?php
	$n = 1;
	$query = " SELECT *,SUM(hit) AS total FROM `mdAnalytic__keyword` WHERE keyword<>'' AND date='".$year."-".$month."-".$day."' GROUP BY `keyword` ORDER BY total DESC ";
	$db->query($query);
	if($db->getNumRows() < 1)
	{
		echo('<tr><td class="blank" colspan="4">내역이 없습니다.</td></tr>');
	}
	else
	{
		while($Rows = $db->fetch())
		{
			if($n < 11) { $Rows['keyword'] = "<strong>".$Rows['keyword']."</strong>"; }
			echo('<tr>
				<th>'.$Rows['keyword'].' '.$key.'</th>
				<td><p class="center"><span class="blue">'.$n.'위</span></p></td>
				<td><p class="center"><span class="blue">'.$Rows['total'].' 회</span></p></td>
				</td>
			</tr>');
			$n++;
			unset($recent, $per1);
		}
	}
	?>
	</tbody>
</table>