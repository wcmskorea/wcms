<?php
require_once "../../../_config.php";
require_once __PATH__."_Admin/include/commonHeader.php";
?>

<fieldset id="help">
<legend>→ 키워드 순위 TOP 100 ←</legend>
<ul>
	<li>사이트 오픈 이후 포털 및 사이트내에서 검색한 키워드 순위</li>
	<li>"유입 사이트별 통계"를 열람해야만 정확한 유입 키워드 순위가 반영됩니다.</li>
</ul>
</fieldset>
<div class="clear"></div>

<table class="table_list" summary="TOP 100" style="width:100%;">
	<caption>TOP 100</caption>
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
	$query = " SELECT *,SUM(hit) AS total FROM `mdAnalytic__keyword` WHERE keyword<>'' GROUP BY `keyword` ORDER BY total DESC LIMIT 100 ";
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
