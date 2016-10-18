<?php
$year		= ($_POST[year])	? $_POST[year] : date("Y");
$month		= str_pad($_POST[month], 2, "0", STR_PAD_LEFT);
$day		= str_pad($_POST[day], 2, "0", STR_PAD_LEFT);

$sq						= "DATE_FORMAT(FROM_UNIXTIME(dateReg),'%Y') = '".$year."'";
if($_POST[month])	$sq	= "DATE_FORMAT(FROM_UNIXTIME(dateReg),'%Y-%m') = '".$year."-".$month."'";
if($_POST[day])		$sq	= "DATE_FORMAT(FROM_UNIXTIME(dateReg),'%Y-%m-%d') = '".$year."-".$month."-".$day."'";

$Rows		= $db->QueryFetch("SELECT SUM(if(sex='1' OR sex='3',1,0)) AS man,SUM(if(sex='2' OR sex='4',1,0)) AS women FROM `mdMember__info`");
?>

<div class="table"><div class="line">
	<fieldset id="help">
	<legend>→ 가입자 유형별 통계 ←</legend>
	<ul>
		<li>사이트 Launching 이후 사이트 가입자 통계입니다.</li>
		<li>가입자 내역은 시간이 지날수록 많은 양의 데이터가 누적되어 용량과 속도 저하의 원인이 되므로 최근 2년간 데이터만 열람하실 수 있습니다.</li>
	</ul>
	</fieldset>
  <div class="pd3">
  <table class="table_list" summary="가입자 유형별 통계" style="width:100%;">
    <caption>가입자 유형별 통계</caption>
    <col width="140" />
    <col width="140" />
    <col />
    </tr>
    <thead>
    <tr><th><p class="center">총 <span class="blue">남성</span> 가입자</p></th>
        <th><p class="center">총 <span class="red">여성</span> 가입자</p></th>
        <th><p class="center">총누적 가입자</p></th>
    </tr>
    </thead>
    <tbody>
      <tr><td><p class="center pd5"><strong class="blue"><?=number_format($Rows[man])?></strong> 명</p></td>
          <td><p class="center pd5"><strong class="red"><?=number_format($Rows[women])?></strong> 명</p></td>
          <td><p class="center pd5"><strong class="black"><?=number_format($Rows[man]+$Rows[women])?></strong> 명</p></td>
      </tr>
    </tbody>
  </table>
  </div>
</div></div>

<dl>
	<dt style="float:left; padding:3px; vertical-align:center;"></dt>
	<dd style="float:right; padding:1px;"><span class="button bmetal"><button type="submit" onclick="$.insert('#module', '../modules/mdAnalytic/manage/_controll.php', '&amp;type=logRegister&amp;year=' + $('#year').val() + '&amp;month=' + $('#month').val() + '&amp;day=' + $('#day').val(), 300)">검색</button></span></dd>
	<dd style="float:right; padding:2px;">
		<select id="day" name="day" class="bg_gray">
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
		<select id="month" name="month" class="bg_gray">
			<option value="">전체</option>
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
  <h5><span class="violet">성　별 사이트 가입자 현황</span></h5>
  <div class="clear"></div>
</div>

<table class="table_list" summary="성별 사이트 가입자 현황" style="width:100%;">
	<caption>성별 사이트 가입자 현황</caption>
	<col width="100" />
	<col width="60" />
	<col />
	</tr>
	<thead>
	<tr><th class="first"><p class="center">성　별</p></th>
			<th><p class="center">가입자수</p></th>
			<th><p class="center">그래프</p></th>
	</tr>
	</thead>
	<tbody>
		<?php
		$Rows = $db->QueryFetch("SELECT SUM(if(sex='1' OR sex='3',1,0)) AS man,
																		SUM(if(sex='2' OR sex='4',1,0)) AS women,
																		COUNT(*) AS total
														FROM `mdMember__info` WHERE ".$sq."");
		$per_man		= ($Rows[man]) ? substr(($Rows[man] * 100) / $Rows[total], 0 , 4) : 0;
		$per_women	= ($Rows[women]) ? substr(($Rows[women] * 100) / $Rows[total], 0 , 4) : 0;
		?>
		<tr><th><p class="center"><span class="red">여 성</span></p></th>
				<td><p class="center"><?=$Rows[women]?></p></td>
				<td>
					<dl class="ratio">
					<dt>가입율</dt>
					<dd><p style="width:300px;"><span class="graph" style="width:<?=$per_women?>%"></span></p></dd>
					<dd><?=$per_women?>%</dd>
					</dl>
				</td>
		</tr>
		<tr><th><p class="center"><span class="blue">남 성</span></p></th>
				<td><p class="center"><?=$Rows[man]?></p></td>
				<td>
					<dl class="ratio">
					<dt>가입율</dt>
					<dd><p style="width:300px;"><span class="graph" style="width:<?=$per_man?>%"></span></p></dd>
					<dd><?=$per_man?>%</dd>
					</dl>
				</td>
		</tr>
	</tbody>
</table>
<br />

<div class="sub_Header">
  <h5><span class="violet">연령별 사이트 가입자 현황</span></h5>
  <div class="clear"></div>
</div>
<table class="table_list" summary="연령별 사이트 가입자 현황" style="width:100%;">
	<caption>연령별 사이트 가입자 현황</caption>
	<col width="100" />
	<col width="60" />
	<col />
	</tr>
	<thead>
	<tr><th><p class="center">연령별</p></th>
			<th><p class="center">가입자수</p></th>
			<th><p class="center">그래프</p></th>
	</tr>
	</thead>
	<tbody>
	<?php
	$total = $db->QueryFetchOne("SELECT COUNT(*) FROM `mdMember__info` WHERE ".$sq."");;
	$db->Query("SELECT SUBSTRING((age+10),1,1) AS ageGroup,COUNT(*) AS total FROM `mdMember__info` WHERE ".$sq." GROUP BY ageGroup ORDER BY total DESC");
	if($db->getNumRows() < 1)
	{
		echo '<tr><td class="blank" colspan="4">내역이 없습니다.</td></tr>';
	} else
	{
		while($Row = $db->Fetch())
		{
			$per = ($Row[total]) ? substr(($Row[total] * 100) / $total, 0, 4)	: 0;
			print('<tr>
        <th><p class="center">'.intval($Row[ageGroup]-1).'0대</p></th>
				<td><p class="center"><span class="blue">'.$Row[total].'</span></p></td>
				<td>
					<dl class="ratio">
					<dt>가입율</dt>
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
<br />

<div class="sub_Header">
  <h5><span class="violet">요일별 사이트 가입자 현황</span></h5>
  <div class="clear"></div>
</div>
<table class="table_list" summary="요일별 사이트 가입자 현황" style="width:100%;">
	<caption>요일별 사이트 가입자 현황</caption>
	<col width="100" />
	<col width="60" />
	<col />
	</tr>
	<thead>
	<tr><th><p class="center">요일명</p></th>
			<th><p class="center">가입자수</p></th>
			<th><p class="center">그래프</p></th>
	</tr>
	</thead>
	<tbody>
	<?php
	$db->Query("SELECT week,COUNT(*) AS total FROM `mdMember__info` WHERE ".$sq." GROUP BY week ORDER BY total DESC");
	if($db->getNumRows() < 1)
	{
		echo '<tr><td class="blank" colspan="4">내역이 없습니다.</td></tr>';
	} else
	{
		while($Row = $db->Fetch())
		{
			$per = ($Row[total]) ? substr(($Row[total] * 100) / $total, 0, 4)	: 0;
			print('<tr>
        <th><p class="center">'.$Row[week].'</p></th>
				<td><p class="center"><span class="blue">'.$Row[total].'</span></p></td>
				<td>
					<dl class="ratio">
					<dt>가입율</dt>
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
