<?php
require_once "../../_config.php";
if(!defined('__CSS__')) header("Location: ./login.php");

/*--------------------------------------------------------------
| 10억홈피닷컴 서비스 이용현황 :: 타호스팅으로 이전시 삭제해야함
*/
$db->DisConnect();
$db->Connect(2);
$db->selectDB("acehost");
$Rows = $db->queryFetch("SELECT No,Product,Pcode,Edate,Progress FROM `memberServices` WHERE Id='".$cfg[site][id]."' AND Pcode like 'CSS-%'");
if($db->getNumRows() < 1)
{
	$Rows = $db->queryFetch("SELECT No,Product,Pcode,Edate,Progress FROM `memberServices` WHERE Id='".$cfg[site][id]."' AND Pcode like 'Hosting-%'");
}
if($Rows)
{
	# 프로젝트 정보
	$prj = $db->queryFetch("SELECT * FROM `project` WHERE Id='".$cfg[site][id]."'");

	# 만료일 계산
	$Ldate	= ceil(($Rows[Edate] - time())/86400);

	# 서비스 상품 정보
	$Prod	= $db->queryFetch("SELECT Title,Size,Sellprice FROM `shopProduct` WHERE No='".$Rows[Product]."'");
	if($db->getNumRows() < 1) $func->Err("서비스에 연결된 상품이 없습니다. 고객센터로 문의주십시오.","history.back()");
	switch($Prod[Size])
	{
		case "1000"	: $svcType= "Basic";	break;
		case "2000"	: $svcType= "Plus";		break;
		case "3000"	: $svcType= "Premium";	break;
		case "5000"	: $svcType= "Special";	break;
		default		: $svcType=$Prod[Size];	break;
	}
	//$Disk		= $db->queryFetch("SELECT SUM(Amount),SUM(Price) FROM `memberServices` WHERE Id='".$cfg[site][id]."' AND Pcode like 'Add-Disk%' AND Progress='Y'");
	//$Traffic	= $db->queryFetch("SELECT SUM(Amount),SUM(Price) FROM `memberServices` WHERE Id='".$cfg[site][id]."' AND Pcode like 'Add-Traffic%' AND Progress='Y'");
	//$Foward		= $db->queryFetch("SELECT COUNT(*) FROM `memberDomain` WHERE Id='".$cfg[site][id]."' AND Type<>'0'");

	#디스크 사용량 및 트래픽 사용량
	//$uses = Member::checkDisk($prj[Server], $prj[Disk], $prj[Traffic], $cfg[site][id]);

?>
<table class="table_basic" style="width:100%">
<!--<colgroup>
<col>
</colgroup>
<thead>
	<tr>
	<th class="first"><p class="center"><span>서비스 정보</span></p></th>
	</tr>
</thead>-->
<tbody>
<tr>
	<th><p class="center"><span><?php echo(str_replace("약정",null,$Prod[Title])); ?></span> {<span class="colorRed"><?php echo($svcType); ?></span>}</p></th>
</tr>
<tr>
	<th><p class="center">만료일: <span class="colorOrange"><?php echo(date("Y년 m월 d일", $Rows[Edate])); ?></span></p></th>
</tr>
</tbody>
</table>
<?php
}
else {
	echo '<p class="small_gray center pd10">10억홈피닷컴의 서비스 이용정보를 보시려면 고객센터로 문의하시기 바랍니다.</p>';
}
$db->DisConnect();
$db->Connect(1);
$db->selectDB(__DB__);

exit(0);
?>
<table class="table_input">
	<caption>10억홈피 서비스 이용현황</caption>
	<col width="90" />
	<col />
	<col width="90" />
	<col />
	<tr><th class="menu_gray" colspan="4"><strong>10억홈피닷컴 서비스 이용정보</strong></th></tr>
	<tr>
		<th class="dash_sidebottom">서비스 종류</th>
		<td class="dash_sidebottom center"><p class="center"><strong class="red"><?=iconv("CP949","UTF-8",$Prod[Title])?></strong> { <span class="green"><?=$svcType?></span> }</p></td>
		<th class="dash_sidebottom">서비스 만료일</th>
		<td class="dash_bottom center"><p class="center"><strong class="blue"><?=date("Y년 m월 d일", $Rows[Edate])?></strong> { 남은기간 : <?php if($Ldate < 1){echo('<strong class="red">'.$Ldate);} else {echo('<strong class="green">'.$Ldate);}?>일</strong> } </p></td>
	</tr>
	<tr>
		<th class="dash_sidebottom">대표 도메인</th>
		<td class="dash_bottom" colspan="3">http://<a href='http://<?=$prj[Url]?>' target='_blank'><strong><?=$prj[Url]?></strong></a></td>
	</tr>
	<tr>
		<th class="dash_sidebottom">디스크 용량</th>
		<td class="dash_sidebottom"><strong><span class="red"><?=$uses[3]?></span></strong> ( <span class="blue"><?=$uses[0]?></span> 사용중 )</td>
		<th class="dash_sidebottom">디스크 사용량</th>
		<td class="dash_bottom">
			<div style='height:7px; background:url(/image/background/bg_gage.gif) repeat-x;font-size:0'><p style='height:3px;width:<?=$uses[1]?>%; font-size:0px; background-color:<?=$uses[5]?>; text-align:left;'></p></div>
			<p class="small_gray blue" style="text-align:right;"><?=$uses[1]?>% 사용중</p>
		</td>
	</tr>
	<tr>
		<th class="dash_sidebottom">트래픽 용량</th>
		<td class="dash_sidebottom"><strong><span class="red"><?=$uses[4]?></span></strong> (하루) &nbsp; <strong class="blue">→&nbsp;<a href='#none' onClick="new_window('http://<?=$cfg[site][domain]?>/cband-me','cband','800','210','no','no');"><img src="/image/button/btn_traffic.gif" width="70" height="21" /></a></td>
		<th class="dash_sidebottom">트래픽 사용량</th>
		<td class="dash_bottom">
			<div style='height:7px; background:url(/image/background/bg_gage.gif) repeat-x;font-size:0'><p style='height:3px;width:<?=$uses[2]?>%; font-size:0px; background-color:<?=$uses[6]?>; text-align:left;'></p></div>
			<p class="small_gray blue" style="text-align:right;"><?=$uses[2]?>% 사용중</p>
	</tr>
	<tr>
		<th class="dash_sidebottom">메 일 용 량</th>
		<td class="dash_sidebottom"><span class="red"><?=$prj[Maildisk]?></span> MB</td>
		<th class="dash_sidebottom">SMS 호스팅</th>
		<td class="dash_bottom">총 <span class="red"><?=$prj[Sms]?></span> 건</td>
	</tr>
	<tr><th class="menu_gray" colspan="4"><strong>추가옵션 정보</strong></td></tr>
	<tr>
		<th class="dash_sidebottom">웹메일 사용</th>
		<td class="dash_sidebottom"><span class="red"><?=number_format($prj[Mail])?></span> 계정 &nbsp; <strong class="blue">→&nbsp;<a href='#none' onClick="new_window('http://mail.<?=$cfg[site][domain]?>','webmail','1000','600','no','yes');"><img src="/image/button/btn_webmail.gif" width="70" height="21" /></a></strong></td>
		<th class="dash_sidebottom">도메인 연결</th>
		<td class="dash_bottom"><span class="red"><?=number_format($Foward[0])?></span>개 추가 연결중</td>
	</tr>
	<tr>
		<th class="dash_side">추가 디스크</th>
		<td class="dash_side"><span class="red"><?=number_format($Disk[0])?></span> MB</td>
		<th class="dash_side">추가 트래픽</th>
		<td><span class="red"><?=number_format($Traffic[0])?></span> MB</td>
	</tr>
</table>
