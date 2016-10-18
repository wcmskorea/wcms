<?php
$year		= ($_GET['year']) ? $_GET['year'] : date("Y");
$month		= ($_GET['month']) ? str_pad($_GET['month'], 2, "0", STR_PAD_LEFT) : date("m");
$day		= ($_GET['day']) ? str_pad($_GET['day'], 2, "0", STR_PAD_LEFT) : date("d");
$count		= $db->queryFetch(" SELECT SUM(if(date=CURDATE(),1,0)) AS today, SUM(if(YEAR(date)='".$year."' AND MONTH(date) = '".$month."',1,0)) AS month, COUNT(*) AS total FROM `mdAnalytic__track` ");
$sq			= "DATE_FORMAT(date,'%Y-%m-%d') = '".$year."-".$month."-".$day."'";
?>

<div class="table"><div class="line bg_white">
<fieldset id="help">
<legend>→ 사이트별 방문자 통계 ←</legend>
<ul>
	<li>사이트 Launching 이후 방문자 통계입니다.</li>
	<li>일별과 IP별 통계는 24시간내 1회 Check이며, 1회이상 방문IP만 보여집니다.</li>
	<li>특정 키워드가 깨져보일경우 해당 사이트를 클릭하여 알아볼 수 있습니다.</li>
	<li>방문자 내역은 시간이 지날수록 많은 양의 데이터가 누적되어 용량과 속도 저하의 원인이 되므로 최근 2년간 데이터만 열람하실 수 있습니다.</li>
</ul>
</fieldset>
<div class="pd3">
<table class="table_list" summary="방문자 접속현황" style="width:100%;">
	<caption>방문자 접속현황</caption>
	<col width="140">
	<col width="140">
	<col>
	</tr>
	<thead>
	<tr><th class="first"><p class="center"><span class="blue">금일</span> 접속자 수</p></th>
			<th><p class="center"><span class="red"><?=$month?>월</span> 접속자 수</p></th>
			<th><p class="center">총누적 접속자</p></th>
	</tr>
	</thead>
	<tbody>
		<tr><td><p class="center pd5"><strong class="blue"><?=number_format($count['today'])?></strong> 명</p></td>
				<td><p class="center pd5"><strong class="red"><?=number_format($count['month'])?></strong> 명</p></td>
				<td><p class="center pd5"><strong class="black"><?=number_format($count['total'])?></strong> 명</p></td>
		</tr>
	</tbody>
</table>
</div>
</div></div>

<dl>
	<dt style="float:left; padding:3px; vertical-align:center;"></dt>
	<dd style="float:right; padding:1px;"><span class="btnPack green medium strong"><button type="submit" onclick="$.insert('#module', '../modules/mdAnalytic/manage/_controll.php', '&amp;type=logVisitorRefer&amp;year=' + $('#year').val() + '&amp;month=' + $('#month').val() + '&amp;day=' + $('#day').val(), 300)">검색</button></span></dd>
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

<div class="sub_Header">
  <h5><span class="colorOrange">유입 사이트·유입 키워드·방문자 현황</span></h5>
  <div class="clear"></div>
</div>

<table class="table_list" summary="사이트 방문 경로 현황" style="width:100%;">
	<caption>사이트 방문 경로 현황</caption>
	<col width="250">
	<col width="200">
	<col width="65">
	<col>
	</tr>
	<thead>
	<tr><th class="first"><p class="center">사이트명</p></th>
			<th><p class="center">키워드</p></th>
			<th><p class="center">방문횟수</p></th>
			<th><p class="center">그래프</p></th>
	</tr>
	</thead>
	<tbody>
	<?php
	$cnt = 0;
	$per = 0;
	$total = $db->queryFetchOne("SELECT COUNT(*) FROM `mdAnalytic__refer` WHERE ".$sq);
	
	$db->query("SELECT *,COUNT(*) AS total FROM `mdAnalytic__refer` WHERE ".$sq." GROUP BY referer ORDER BY total DESC");
	//$db->query("SELECT * FROM `mdAnalytic__refer` WHERE ".$sq." ORDER BY ip ");
	if($db->getNumRows() < 1)
	{
		echo '<tr><td class="blank" colspan="4">내역이 없습니다.</td></tr>';
	} else
	{
		while($Rows = $db->fetch())
		{
			$per1					= ($Rows['total'] && $total) ? substr(($Rows['total'] * 100) / $total, 0, 4)	: 0;
			$refer				= explode("/", $Rows['referer']);
			$match				= (preg_match("/\bnaver\b/i", $Rows['referer'])) ? "query=" : "q=";
			$keyword			= explode($match, urldecode($Rows['referer']));
			$keyword['1']	= (mb_detect_encoding($keyword['1']) != "UTF-8") ? iconv("CP949", "UTF-8", $keyword['1']) : $keyword['1'];
			$keywords			= explode("&", $keyword['1']);
			$keyword			= $func->cutStr($keywords['0'], 14);

			echo('<tr><td><a href="http://'.$Rows['referer'].'" class="actUnder" target="_blank" title="'.$Rows['referer'].'">'.$refer['0'].'</a> <!--('.$Rows['ip'].')--></td>
				<td>'.$keyword.'</td>
				<td><p class="center">'.$Rows['total'].'</p></td>
				<td>
					<dl class="ratio">
					<dt>접속율</dt>
					<dd><p style="width:200px;"><span class="graph" style="width:'.$per1.'%"></span></p></dd>
					<dd>'.$per1.'%</dd>
					</dl>
				</td>
			</tr>');
			$per  = $per + $per1;
			$cnt  = $cnt + $Rows['total'];

			//2012-11-22 이성준 검색어 통계입력
			$realKey = str_replace(" ", null, trim($keywords['0']));
			if($db->queryFetchOne("SELECT uptime FROM `mdAnalytic__keyword` WHERE keyword='".$realKey."' AND date='".$Rows['date']."'", 2) < $Rows['time'] && $keyword)
			{
				$db->query(" UPDATE `mdAnalytic__keyword` SET hit=hit+1 WHERE keyword='".$realKey."' AND date='".$Rows['date']."' ", 2);
				if(!$db->getAffectedRows(2))
				{
					$db->queryForce(" INSERT INTO `mdAnalytic__keyword` (keyword,hit,date) VALUES ('".$realKey."','1','".$Rows['date']."') ", 2);
				}
				//$db->query(" UPDATE `mdAnalytic__refer` SET `check`='Y' WHERE `seq`='".$Rows['seq']."' ", 3);
			}
			$totalCnt ++;
			$totalMobile += (preg_match('/^m./', $refer['0'])) ? 1 : 0;
			$totalPc += (preg_match('/^m./', $refer['0'])) ? 0 : 1;
		}
		$totalPc = ($totalPc && $totalCnt) ? number_format(($totalPc/$totalCnt)*100) : 0;
		$totalMobile = ($totalMobile && $totalCnt) ? number_format(($totalMobile/$totalCnt)*100) : 0;

		$dirPer = ((100 - $per) > 0) ? 100 - $per : 0;
		$dirCnt = (($total - $cnt) > 0) ? $total - $cnt : 0;
		echo('<tr><td><span class="red">Direct Connection</span></td>
				<td>-</td>
				<td><p class="center">'.$dirCnt.'</p></td>
				<td>
					<dl class="ratio">
					<dt>접속율</dt>
					<dd><p style="width:200px;"><span class="graph" style="width:'.$dirPer.'%"></span></p></dd>
					<dd>'.$dirPer.'%</dd>
					</dl>
				</td>
		</tr>');
		
	}
	?>
	</tbody>
</table>

<table class="table_list" summary="방문자 접속현황" style="width:100%;">
	<caption>방문자 접속현황</caption>
	<col width="250">
	<col width="265">
	<col>
	</tr>
	<thead>
	<tr><th class="first"><p class="center"><span class="blue">PC</span> 접속률</p></th>
			<th><p class="center"><span class="red">모바일</span> 접속률</p></th>
			<th><p class="center">총유입 사이트</p></th>
	</tr>
	</thead>
	<tbody>
		<tr><td><p class="center pd5"><strong class="colorBlue"><?=$totalPc?> %</strong></p></td>
				<td><p class="center pd5"><strong class="colorRed"><?=$totalMobile?> %</strong></p></td>
				<td><p class="center pd5"><strong class="colorBlack"><?=number_format($totalCnt)?> 건</strong></p></td>
		</tr>
	</tbody>
</table>
