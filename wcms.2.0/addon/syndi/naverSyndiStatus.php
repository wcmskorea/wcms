<?php
require_once "../../_config.php";

include 'libs/SyndicationStatus.class.php';

$mydomain = __HOST__;

$oStatus = new SyndicationStatus;
$oStatus->setSite($mydomain);
$result = $oStatus->request();

//print_r($result);
?>
<table width="700" class="table_list">
<col width="150">
<col>
<col width="150">
<col>
<tbody>
<tr>
	<th>사이트</th>
	<td><?php echo($result[site_name]);?></td>
	<th>동기화 상태</th>
	<td><strong><?php echo($result[status]);?></strong></td>
</tr>
<tr>
	<th>서버 등록일</th>
	<td><?php echo($result[first_update]);?></td>
	<th>마지막 업데이트</th>
	<td><?php echo($result[last_update]);?></td>
</tr>
<tr>
	<th>Ping 연속접속 성공 횟수</th>
	<td><?php echo($result[visit_ok_count]);?></td>
	<th>Ping 실패 횟수</th>
	<td><?php echo($result[visit_fail_count]);?></td>
</tr>
</tbody>
</table>