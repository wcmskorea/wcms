<?php
require_once "../../../_config.php";
require_once __PATH__."/_Admin/include/commonHeader.php";
include __PATH__."_Lib/classXMLparse.php";

$url = "http://whois.kisa.or.kr/openapi/whois.jsp?query=".$_GET['ip']."&key=2012041020565324105288";
$xml = @file_get_contents($url);
$parser = new XMLParser($xml);
$parser->Parse();
?>
<div class="menu_violet">
	<p title="드래그하여 이동하실 수 있습니다"><strong><?php echo($_GET['ip']); ?> / <?php echo(strtoupper($parser->document->countrycode[0]->tagData)); ?> / <?php echo($parser->document->korean[0]->isp[0]->netinfo[0]->orgname[0]->tagData); ?></strong></p>
</div>

<table class="table_basic" summary="" style="width: 100%;">
	<caption>회원정보</caption>
	<col width="150">
	<col>
	<thead>
		<tr>
			<th class="first"><p class="center"><span>아이디</span></p></th>
			<th><p class="center"><span>아이피 주소</span></p></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$db->query(" SELECT * FROM `mdMember__log` WHERE ip='".$_GET['ip']."' GROUP BY id ");
	if($db->getNumRows() < 1)
	{
		echo '<tr><td colspan="2"><p class="pd10 center">내역이 없습니다.</p></td></tr>';
	} else
	{
		while($Rows = $db->fetch())
		{
			echo('<tr>
			<th><a href="javascript:;" onclick="$.memberInfo(\''.$Rows['id'].'\');">'.$Rows['id'].'</a></th>
			<td><ol><li class="opt colorRed" style="width:300px">'.$Rows['ip'].'</li></ol></td>
			</tr>');
		}
	}
	?>
	</tbody>
</table>

<?php if($func->checkModule('mdOrder')) { ?>
<table class="table_basic" summary="" style="width: 100%;">
	<caption>구매정보</caption>
	<col width="150">
	<col>
	<thead>
		<tr>
			<th class="first"><p class="center"><span>주문시간</span></p></th>
			<th><p class="center"><span>주문정보</span></p></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$db->query(" SELECT * FROM `mdOrder__order` WHERE ip='".$_GET['ip']."' ORDER BY regdate ASC ");
	if($db->getNumRows() < 1)
	{
		echo '<tr><td colspan="2"><p class="pd10 center">내역이 없습니다.</p></td></tr>';
	} else
	{
		while($Rows = $db->fetch())
		{
			echo('<tr>
			<th>'.$Rows['regdate'].'</th>
			<td><ol><li class="opt" style="width:300px"><span class="colorRed">'.number_format($Rows['payPrice']).'</span> <span class="colorGreen">('.$cfg['orderStatus'][$Rows['status']].')</span></li></ol></td>
			</tr>');
		}
	}
	?>
	</tbody>
</table>
<?php } ?>

<table class="table_basic" summary="" style="width: 100%;">
	<caption>유입경로</caption>
	<col width="150">
	<col>
	<thead>
		<tr>
			<th class="first"><p class="center"><span>접속경로</span></p></th>
			<th><p class="center"><span>키 워 드</span></p></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$db->query("SELECT * FROM `mdAnalytic__refer` WHERE ip = '".$_GET['ip']."' AND date = '".$_GET['date']."'  ORDER BY time DESC");
	if($db->getNumRows() < 1)
	{
		echo '<tr><td colspan="2"><p class="pd10 center">내역이 없습니다.</p></td></tr>';
	} else
	{
		while($Rows = $db->fetch())
		{
			$refer			= explode("/", $Rows['referer']);
			$match			= (preg_match("/\bnaver\b/i", $Rows['referer'])) ? "query=" : "q=";
			$keyword		= explode($match, urldecode($Rows['referer']));
			$keyword['1']	= (mb_detect_encoding($keyword['1']) != "UTF-8") ? iconv("CP949", "UTF-8", $keyword['1']) : $keyword['1'];
			$keyword		= explode("&", $keyword['1']);
			//$keyword		= $func->cutStr($keyword['0'], 14);

			echo('<tr>
			<th><a href="http://'.$Rows['referer'].'" class="actUnder" target="_blank">'.$refer['0'].'</a></th>
			<td><ol><li class="opt colorRed">'.$keyword['0'].'</li><li class="opt colorGray">('.$Rows['time'].')</li></ol></td>
			</tr>');
		}
	}
	?>
	</tbody>
</table>

<table class="table_basic" summary="" style="width: 100%;">
	<caption>이동경로</caption>
	<col width="150">
	<col>
	<thead>
		<tr>
			<th class="first"><p class="center"><span>시간</span></p></th>
			<th><p class="center"><span>이동 페이지</span></p></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$track = $db->queryFetchOne(" SELECT info FROM `mdAnalytic__track` WHERE skin = '".$_GET['skin']."' AND ip = '".$_GET['ip']."' AND date = '".$_GET['date']."' ");
	if($db->getNumRows() < 1)
	{
		echo '<tr><td colspan="2"><p class="pd10 center">내역이 없습니다.</p></td></tr>';
	} else
	{
		$track = explode(">", $track);
		foreach($track AS $key=>$val)
		{
			$value = explode(":", $val);
			if(is_numeric($value['0']))
			{
				$delay = ($pageDelay) ? $value['0'] - $pageDelay : 0;
				$pageDelay = $value['0'];
				$pageTime = date('H:i:s', $value['0']);
				$pageName = ($value['1'] == 'main') ? "메인 페이지 (".date('i:s', $delay).")" : $db->queryFetchOne(" SELECT name FROM `site__` WHERE cate = '".$value['1']."' AND skin='".$_GET['skin']."' ", 2)." (".date('i:s', $delay).")";
		?>
				<tr>
					<th><?php echo($pageTime); ?></th>
					<td class="darkgray">
					<ol>
						<li style="width:300px"><?php echo($pageName); ?></li>
					</ol>
					</td>
				</tr>
		<?php
			}
		}
	}
	?>
	</tbody>
</table>