<?php
require_once "../../_config.php";

include 'libs/SyndicationStatus.class.php';

$mydomain = __HOST__;

$oStatus = new SyndicationStatus;
$oStatus->setSite($mydomain);
$result = $oStatus->request();

//print_r($result);
?>
<table width="600" class="table_list">
<col width="100">
<col>
<tbody>
<tr>
	<th>상태</th>
	<td><?php echo($result[error] < 0 ? 'Ping 테스트 에러입니다. 도메인 및 서버 설정을 확인해주세요':'정상적 동작으로 확인되었습니다.');?></td>
</tr>
</tbody>
</table>