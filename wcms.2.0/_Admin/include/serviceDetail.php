<?php
require_once "../../_config.php";
if(!defined('__CSS__')) header("Location: ./login.php");
?>
<table class="table_basic" style="width:100%">
	<col width="100" />
	<col />
	<col width="100" />
	<col />
  <thead>
  <tr><th colspan="4"><p class="left"><span><img src="<?=$cfg[droot]?>image/icon/icon_aw_dw.gif" width="10" height="10" /></span><span>10억홈피 서비스 이용정보</span></p></th></tr>
  </thead>
  <tbody>
	<tr>
		<th>서비스 종류</th>
		<td><strong>리눅스 웹호스팅</strong> { <span class="green">Basic</span> }</td>
		<th>서비스 만료일</th>
		<td><strong>2029년 04월 01일</strong> { 남은기간 : <span class="green"><strong>7146일</strong></span> }</td>
	</tr>
	<tr>
		<th>대표 도메인</th>
		<td colspan="3"><p class="left">http://<a href='http://demo.aceoa.com' target='_blank'><strong>demo.aceoa.com</strong></a></p></td>
	</tr>
	<tr>
		<th>디스크 용량</th>
		<td><p class="left"><span class="red">500MB</span> ( <span class="blue">0MB</span> 사용중 )</p></td>
		<th>디스크 사용량</th>
		<td>
			<div style='height:7px; background:url(<?=$cfg[droot]?>image/background/bg_gage.gif) repeat-x;font-size:0'><p style='height:3px;width:0.0%; font-size:0px; background-color:#3366FF; text-align:left;'></p></div>
			<p class="small_gray blue" style="margin-top:3px;text-align:right;">0.0% 사용중</p>
		</td>
	</tr>
	<tr>
		<th>트래픽 용량</th>
		<td><p class="left"><span class="red">1.0GB</span> (1일)&nbsp;<strong class="blue">→&nbsp;<a href='#none' onClick="new_window('http://demo.aceoa.com/cband-me','cband','800','210','no','no');"><img src="<?=$cfg[droot]?>image/button/btn_traffic.gif" width="70" height="21" /></a></strong></p></td>
		<th>트래픽 사용량</th>
		<td>
			<div style='height:7px; background:url(<?=$cfg[droot]?>image/background/bg_gage.gif) repeat-x;font-size:0'><p style='height:3px;width:0%; font-size:0px; background-color:#3366FF; text-align:left;'></p></div>
			<p class="small_gray blue" style="margin-top:3px;text-align:right;">0% 사용중</p>
	</tr>
	<tr>
		<th>메일 용량</th>
		<td><p class="left"><span class="red">30</span> MB</p></td>
		<th>SMS 호스팅</th>
		<td><p class="left">총 <span class="red">500</span> 건</p></td>
	</tr>
  </tbody>
  <thead>
	<tr><th colspan="4"><span><img src="<?=$cfg[droot]?>image/icon/icon_aw_dw.gif" width="10" height="10" /></span><span>추가옵션 정보</span></th></tr>
  </thead>
  <tbody>
	<tr>
		<th>웹메일 사용</th>
		<td><p class="left"><span class="red">1</span> 계정</p></td>
		<th>도메인 연결</th>
		<td><p class="left"><span class="red">0</span>개 추가 연결중</p></td>
	</tr>
	<tr>
		<th>추가 디스크</th>
		<td><p class="left"><span class="red">0</span> MB</p></td>
		<th>추가 트래픽</th>
		<td><p class="left"><span class="red">0</span> MB</p></td>
	</tr>
  </tbody>
</table>
<script type="text/javascript">$(document).ready(function(){$(".table_basic").css('background','#fff');});</script>
<?php
exit(0);

/*--------------------------------------------------------------
| 10억홈피닷컴 서비스 이용현황 :: 타호스팅으로 이전시 삭제해야함
*/
$db->DisConnect();
$db->Connect(2);
$db->selectDB("acehost");
$Rows = $db->QueryFetch("SELECT No,Product,Pcode,Edate,Progress FROM `memberServices` WHERE Id='".$cfg[site][id]."' AND Pcode like 'CSS-%'");
if($db->getNumRows() < 1)
{
	$Rows = $db->QueryFetch("SELECT No,Product,Pcode,Edate,Progress FROM `memberServices` WHERE Id='".$cfg[site][id]."' AND Pcode like 'Hosting-%'");
}
if($Rows)
{
	# 프로젝트 정보
	$prj = $db->QueryFetch("SELECT * FROM `project` WHERE Id='".$cfg[site][id]."'");
	# 만료일 계산
	$Ldate	= ceil(($Rows[Edate] - time())/86400);
	# 서비스 상품 정보
	$Prod	= $db->QueryFetch("SELECT Title,Size,Sellprice FROM `shopProduct` WHERE No='".$Rows[Product]."'");
		if($db->getNumRows() < 1) $func->Err("서비스에 연결된 상품이 없습니다. 고객센터로 문의주십시오.","history.back()");
		switch($Prod[Size]) {
			case "500"	: $svcType= "Basic";	break;
			case "1000"	: $svcType= "Plus";		break;
			case "3000"	: $svcType= "Premium";	break;
			case "5000"	: $svcType= "Special";	break;
			default		: $svcType=$Prod[Size];	break;
		}
	$Disk		= $db->QueryFetch("SELECT SUM(Amount),SUM(Price) FROM `memberServices` WHERE Id='".$cfg[site][id]."' AND Pcode like 'Add-Disk%' AND Progress='Y'");
	$Traffic	= $db->QueryFetch("SELECT SUM(Amount),SUM(Price) FROM `memberServices` WHERE Id='".$cfg[site][id]."' AND Pcode like 'Add-Traffic%' AND Progress='Y'");
	$Foward		= $db->QueryFetch("SELECT COUNT(*) FROM `memberDomain` WHERE Id='".$cfg[site][id]."' AND Type<>'0'");

	#디스크 사용량 및 트래픽 사용량
	$uses = Member::checkDisk($prj[Server], $prj[Disk], $prj[Traffic], $cfg[site][id]);
?>
<table class="table_input">
	<caption>10억홈피 서비스 이용현황</caption>
	<col width="100" />
	<col />
	<col width="100" />
	<col />
	<tr><th class="menu_gray" colspan="4"><strong>10억홈피닷컴 서비스 이용정보</strong></th></tr>
	<tr>
		<th>서비스 종류</th>
		<td><p><strong class="red"><?=iconv("CP949","UTF-8",$Prod[Title])?></strong> { <span class="green"><?=$svcType?></span> }</p></td>
		<th>서비스 만료일</th>
		<td><p><strong class="blue"><?=date("Y년 m월 d일", $Rows[Edate])?></strong> { 남은기간 : <?php if($Ldate < 1) echo '<span class="red"><strong>'.$Ldate; else echo '<span class="green"><strong>'.$Ldate; ?>일</strong></span> } </p></td>
	</tr>
	<tr>
		<th>대표 도메인</th>
		<td colspan="3">http://<a href='http://<?=$prj[Url]?>' target='_blank'><strong><?=$prj[Url]?></strong></a></td>
	</tr>
	<tr>
		<th>디스크 용량</th>
		<td><strong><span class="red"><?=$uses[3]?></span></strong> ( <span class="blue"><?=$uses[0]?></span> 사용중 )</td>
		<th>디스크 사용량</th>
		<td>
			<div style='height:7px; background:url(/image/background/bg_gage.gif) repeat-x;font-size:0'><p style='height:3px;width:<?=$uses[1]?>%; font-size:0px; background-color:<?=$uses[5]?>; text-align:left;'></p></div>
			<p class="small_gray blue" style="text-align:right;"><?=$uses[1]?>% 사용중</p>
		</td>
	</tr>
	<tr>
		<th>트래픽 용량</th>
		<td><strong><span class="red"><?=$uses[4]?></span></strong> (하루) &nbsp; <strong class="blue">→&nbsp;<a href='#none' onClick="new_window('http://<?=$cfg[site][domain]?>/cband-me','cband','800','210','no','no');"><img src="/image/button/btn_traffic.gif" width="70" height="21" /></a></strong></td>
		<th>트래픽 사용량</th>
		<td>
			<div style='height:7px; background:url(/image/background/bg_gage.gif) repeat-x;font-size:0'><p style='height:3px;width:<?=$uses[2]?>%; font-size:0px; background-color:<?=$uses[6]?>; text-align:left;'></p></div>
			<p class="small_gray blue" style="text-align:right;"><?=$uses[2]?>% 사용중</p>
	</tr>
	<tr>
		<th>메 일 용 량</th>
		<td><span class="red"><?=$prj[Maildisk]?></span> MB</td>
		<th>SMS 호스팅</th>
		<td>총 <span class="red"><?=$prj[Sms]?></span> 건</td>
	</tr>
	<tr><th class="menu_gray" colspan="4"><strong>추가옵션 정보</strong></th></tr>
	<tr>
		<th>웹메일 사용</th>
		<td><span class="red"><?=number_format($prj[Mail])?></span> 계정 &nbsp; <strong class="blue">→&nbsp;<a href='#none' onClick="new_window('http://mail.<?=$cfg[site][domain]?>','webmail','1000','600','no','yes');"><img src="/image/button/btn_webmail.gif" width="70" height="21" /></a></strong></td>
		<th>도메인 연결</th>
		<td><span class="red"><?=number_format($Foward[0])?></span>개 추가 연결중</td>
	</tr>
	<tr>
		<th>추가 디스크</th>
		<td><span class="red"><?=number_format($Disk[0])?></span> MB</td>
		<th>추가 트래픽</th>
		<td><span class="red"><?=number_format($Traffic[0])?></span> MB</td>
	</tr>
</table>
<?php
}
else {
	echo '<p class="small_gray center pd10">10억홈피닷컴의 서비스 이용정보를 보시려면 고객센터로 문의하시기 바랍니다.</p>';
}
$db->DisConnect();
$db->Connect(1);
$db->selectDB(__DB__);
?>
