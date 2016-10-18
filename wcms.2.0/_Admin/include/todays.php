<?php
require_once "../../_config.php";
require_once "./commonHeader.php";
?>
<table class="table_basic" style="width:100%;">
<colgroup>
<col width="300">
<col width="200">
<col width="150">
<col width="150">
<col>
</colgroup>
<thead>
<tr>
	<th class="first"><p class="center"><span>업데이트 안내</span></p></th>
  <th><p class="center"><span>신규 회원·방문</span></p></th>
  <?php if($func->checkModule('mdOrder')) { echo('<th colspan="2"><p class="center"><span>주문·배송 확인</span></p></th>'); } ?>
	<th></th>
</tr>
</thead>
<tbody>
<tr>
	<td style="vertical-align:top;text-align:left" class="bg_gray">
		<ol>
		<?php
		$db->selectDB("wcmskoreacom");
		$db->query(" SELECT * FROM `mdDocument__content` WHERE cate='005004' ORDER BY idx DESC Limit 6 ");
		while($Rows = $db->fetch())
		{
			echo('<li class="pd3 small_red">'.$Rows['subject'].'</li>');
		}
		$db->selectDB(__DB__);
		?>
		</ol>
	</td>
	<td style="vertical-align:top;text-align:left;width:200px;" class="sideLine">
    <ol>
    <?php
    if(count($cfg['modules']) > 1)
    {
      if($func->checkModule('mdAnalytic'))
      {
        #--- 방문자 정보
        echo('<li class="darkgray pd3"><span><img src="'.$cfg['droot'].'common/image/icon/icon_aw_right.gif" width="11" height="11" /></span>&nbsp;<span>오늘 방문</span>&nbsp;:&nbsp;<strong class="colorBlue">'.$sess->counting('today').'</strong>&nbsp;명</li>
        <li class="darkgray pd3"><span><img src="'.$cfg['droot'].'common/image/icon/icon_aw_right.gif" width="11" height="11" /></span>&nbsp;<span>전체 방문</span>&nbsp;:&nbsp;<strong class="colorBlue">'.$sess->counting('all').'</strong>&nbsp;명</li>');
      }
      if($func->checkModule('mdMember'))
      {
        #--- 회원 정보
        $count = $db->queryFetch(" SELECT SUM(if(level>'0',1,0)) AS siteJoin ,SUM(if(level='0',1,0)) AS siteOut
																			FROM `mdMember__account`
																			WHERE DATE_FORMAT(FROM_UNIXTIME(dateReg),'%Y-%m-%d')='".date("Y-m-d")."' ");

        echo('<li class="darkgray pd3"><span><img src="'.$cfg['droot'].'common/image/icon/icon_aw_right.gif" width="11" height="11" /></span>&nbsp;<span>가입 회원</span>&nbsp;:&nbsp;<strong class="colorRed">'.number_format($count['siteJoin']).'</strong>&nbsp;명&nbsp;');
        if($count[0] > 0) { echo('<img src="'.$cfg['droot'].'common/image/icon/icon_a_new.gif" width="9" height="9" />'); }
        echo('</li>');
        echo('<li class="darkgray pd3"><span><img src="'.$cfg['droot'].'common/image/icon/icon_aw_right.gif" width="11" height="11" /></span>&nbsp;<span>탈퇴 회원</span>&nbsp;:&nbsp;<strong class="colorRed">'.number_format($count['siteOut']).'</strong>&nbsp;명&nbsp;');
        if($count[1] > 0) { echo('<img src="'.$cfg['droot'].'common/image/icon/icon_a_new.gif" width="9" height="9" />'); }
        echo('</li>');
        unset($count);
      }
    }
    else
    {
      echo('<li class="darkgray pd3"><span>설정된 모듈이 존재하지 않습니다</span></li>');
    }
    ?>
    </ol>
  </td>
  <?php
	if($func->checkModule('mdOrder')) {
		$orderCount = $db->queryFetch("SELECT
															SUM(if(status='0' AND payType<>'',1,0)) AS s0,
															SUM(if(status='1',1,0)) AS s1,
															SUM(if(status='2',1,0)) AS s2,
															SUM(if(status='3',1,0)) AS s3,
															SUM(if(status='4',1,0)) AS s4,
															SUM(if(status='5',1,0)) AS s5,
															SUM(if(status='6' AND payType<>'',1,0)) AS s6,
															SUM(if(status='7' AND payType<>'',1,0)) AS s7,
															SUM(if(status='8',1,0)) AS s8,
															SUM(if(status='9',1,0)) AS s9,
															SUM(if(status='10',1,0)) AS s10 FROM `mdOrder__order` ");
	?>
  <td style="vertical-align:top;text-align:left;" class="sideLine">
    <ol>
    <li class="darkgray pd3"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/icon_aw_right.gif" width="11" height="11" /></span>&nbsp;<span>입금대기</span> : <strong class="colorBlue"><?php echo(number_format($orderCount['s0']));?></strong> 건</li>
    <li class="darkgray pd3"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/icon_aw_right.gif" width="11" height="11" /></span>&nbsp;<span>결제완료</span> : <strong class="colorRed"><?php echo(number_format($orderCount['s1']));?></strong> 건</li>
		<li class="darkgray pd3"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/icon_aw_right.gif" width="11" height="11" /></span>&nbsp;<span>상품준비</span> : <strong class="colorRed"><?php echo(number_format($orderCount['s2']));?></strong> 건</li>
    <li class="darkgray pd3"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/icon_aw_right.gif" width="11" height="11" /></span>&nbsp;<span>배송준비</span> : <strong class="colorRed"><?php echo(number_format($orderCount['s3']));?></strong> 건</li>
		<li class="darkgray pd3"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/icon_aw_right.gif" width="11" height="11" /></span>&nbsp;<span>발송완료</span> : <strong class="colorBlue"><?php echo(number_format($orderCount['s4']));?></strong> 건</li>
		<!--<li class="darkgray pd3"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/icon_aw_right.gif" width="11" height="11" /></span>&nbsp;<span>구매완료</span> : <strong class="colorBlack"><?php echo(number_format($orderCount['s5']));?></strong> 건</li>-->
    </ol>
  </td>
  <td style="vertical-align:top;text-align:left;" class="sideLine">
    <ol>
		<li class="darkgray pd3"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/icon_aw_right.gif" width="11" height="11" /></span>&nbsp;<span>취소접수</span>: <strong class="colorRed"><?php echo(number_format($orderCount['s6']));?></strong> 건</li>
		<li class="darkgray pd3"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/icon_aw_right.gif" width="11" height="11" /></span>&nbsp;<span>취소완료</span>: <strong class="colorBlack"><?php echo(number_format($orderCount['s7']));?></strong> 건</li>
    <li class="darkgray pd3"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/icon_aw_right.gif" width="11" height="11" /></span>&nbsp;<span>반품접수</span>: <strong class="colorRed"><?php echo(number_format($orderCount['s8']));?></strong> 건</li>
    <li class="darkgray pd3"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/icon_aw_right.gif" width="11" height="11" /></span>&nbsp;<span>반품완료</span>: <strong class="colorBlack"><?php echo(number_format($orderCount['s9']));?></strong> 건</li>
    <li class="darkgray pd3"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/icon_aw_right.gif" width="11" height="11" /></span>&nbsp;<span>교환접수</span>: <strong class="colorRed"><?php echo(number_format($orderCount['s10']));?></strong> 건</li>
    </ol>
  </td>
  <?php } ?>
	<td class="sideLine"></td>
</tr>
</tbody>
</table>
<div class="clear"></div>
<?php
if($func->checkModule('mdSms') && $cfg['site']['bsTime'] && $cfg['site']['birthSms'] == "1") {
?>
<br>
<table class="table_basic" style="width:100%;">
<colgroup>
<col>
</colgroup>
<thead>
<tr>
	<th class="first"><p class="left"><span>오늘 생일자 SMS 발송내역</span></p></th>
</tr>
</thead>
<tbody>
<?php
	$sendDate   = date('Y-m-d');
	$sSolaDate  = strtotime(date('Y').'-'.date('m').'-'.date('d'));
	$sSolaDate  = date('Ymd',$sSolaDate);
	$sLunarDate = $func->getSolaToLunar($sSolaDate);
	$sSolaDate  = substr($sSolaDate,4,2).'-'.substr($sSolaDate,6,2);
	$sLunarDate = substr($sLunarDate,4,2).'-'.substr($sLunarDate,6,2);

	//자동발송내역이 있는지 확인
	$smsCount = $db->QueryFetchOne("SELECT count(*) as cnt FROM `mdSms__history` WHERE sendType='A' and FROM_UNIXTIME(sendDate, '%Y-%m-%d')='".$sendDate."'");

	#--- 회원 정보
	$db->query(" SELECT A.name, B.mobile, B.birth, B.birthType FROM `mdMember__account` AS A LEFT JOIN `mdMember__info` AS B ON A.id=B.id WHERE B.birthType='S' AND FROM_UNIXTIME(B.birth,'%m-%d') = '".$sSolaDate."' OR B.birthType='L' AND FROM_UNIXTIME(B.birth,'%m-%d') = '".$sLunarDate."' AND B.receive='Y' ");
	if($db->getNumRows() < 1)
	{
		echo('<tr><td class="blank">오늘 생일인 회원이 존재하지 않습니다.</td></tr>');
	}
	else
	{
?>
<tr>
	<td style="vertical-align:top;text-align:left;width:200px;">
<?php

		$birthCnt=0;
		while($Rows = $db->fetch())
		{
			//발송한 내역이 없다면
			if($smsCount == 0)
			{
				//SMS 발송
				$sock->tempMode		= "mdMember";
				$sock->tempArray	= array($Rows['name'], $cfg['site']['groupName']);
				$sock->date				= strtotime($sendDate." ".date('H:i:s',$cfg['site']['bsTime']));
				$sock->sendType		= 'A';	//자동예약발송
				$socket						= $sock->smsSend($Rows['mobile'], "temp04");
				$sock->varReset();  //데이터 리셋
			}
?>
		<!--<span class="darkgray pd3"><?php echo($Rows['name']);?></span>-->
<?php
			$birthCnt++;
		}
		echo '<strong class="colorBlue">'.$birthCnt.'명에게 생일축하 메세지를 오전 11시에 예약발송 하였습니다.</strong>';
?>
  </td>
</tr>
<?php
	}
?>
</tbody>
</table>
<div class="clear"></div>
<?php } ?>
