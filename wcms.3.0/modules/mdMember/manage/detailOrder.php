<?php
/**
 * 회원별 적립 포인트 관리
 * Author : Sung-Jun, Lee (http://www.aceoa.com)
 * Lastest : 2011. 1. 28.
 */
require_once "../../../_Admin/include/commonHeader.php";

if($_POST[type] == 'orderPost')
{
  $db->Query(" UPDATE `mdMember__info` SET content ='".$_POST['content']."' WHERE id='".$_POST['user']."' ");
}
?>

<div class="menu_violet">
  <p title="드래그하여 이동하실 수 있습니다"><strong>회원 주문내역 및 관리 [ 회원ID : <?=$_GET['user']?> ]</strong></p>
</div>

<table class="table_basic">
<col width="350">
<col>
<tr>
  <td style="vertical-align:top; padding:8px;" class="bg_gray">
    <form name="frmMember" method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data" target="hdFrame">
    <input type="hidden" name="type" value="orderPost" />
    <input type="hidden" name="user" value="<?php echo($_GET['user']); ?>" />
    <?php
    	include "./detailView.php";
    ?>
		<div style="border-top:1px dashed #999; padding:5px 0;">
			<p class="pd5 center">
			<?php if($_GET['user'] && (preg_match('/mdMember/', $_SESSION['udepartment']) || $_SESSION['ulevel'] < $cfg['operator'])) { ?><span class="btnPack red medium strong"><button type="submit">정보 수정하기</button></span><?php } ?>
			</p>
			<p class="pd5"><strong>관리자 메모</strong> 100자이내</p>
			<p><textarea name="content" style="width:99%;height:120px;" class="input_white_line"><?php echo($Rows['content']);?></textarea></p>
		</div>
    </form>
  </td>
  <td style="vertical-align:top;">
    <div class="cube"><div class="line">
    <table class="table_list" style="width:100%;">
    <col width="120">
    <col>
		<col width="70">
    <col width="100">
    <thead>
      <th style="text-align:center" class="first"><p class="center">주문일</p></th>
      <th style="text-align:center">주문내역</th>
      <th style="text-align:center">결제금액</th>
      <th style="text-align:center">주문상태</th>
    </thead>
    <tbody>
    <?php
    #--- 게시물 리스트 및 페이징 설정
    $row						= 15;
    $block					= 10;
		$memberInfo = $member->memberInfo($user);

		$db->query(" SELECT COUNT(*) AS cmt FROM `mdOrder__order` WHERE memberSeq='".$memberInfo['seq']."'");
		$rst = $db->Fetch();
		$totalRec = $rst['cmt'];

		$queryString		= "&amp;".__PARM__."&amp;type=order&amp;user=".$_GET['user'];
    $pagingInstance = new Paging($totalRec, $currentPage, $row, $block);
    $pagingInstance->mode  = "dialog";
    $pagingInstance->width = 1000;
    $pagingInstance->height= 500;
    $pagingInstance->addQueryString($queryString);
    $pagingResult		= $pagingInstance->result("../modules/mdMember/manage/_controll.php");
    $n							= $totalRec - $pagingResult[LimitIndex];
    $i = 0;

		$db->query(" SELECT * FROM `mdOrder__order` WHERE memberSeq='".$memberInfo['seq']."' ORDER BY seq DESC ".$pagingResult['LimitQuery'] );
    while($sRows = $db->Fetch())
    {
			$regdate = explode(" ",$sRows['regdate']);
			$payType = ($sRows['paytype']) ? $cfg['payType'][$sRows['paytype']] : "미결제";

			$orderProductInfo = $order->orderInfoSelect($sRows['orderCode']);
			$cmt = count($orderProductInfo);
			if($cmt > 1)
				$productName = $orderProductInfo[0]['productName'].'+'.$orderProductInfo[0]['optionName'].' '.$orderProductInfo[0]['buyAmount'].'개 외 '.($cmt -1).'건';
			else if($cmt == 1)
				$productName = $orderProductInfo[0]['productName'].'+'.$orderProductInfo[0]['optionName'].' '.$orderProductInfo[0]['buyAmount'].'개';
			else
				$productName = '';

			//if($sRows['mileageType'] == "A") { $mileageType = "<span style='color:blue'>적립</span>";}
			//elseif($sRows['mileageType'] == "U") { $mileageType = "<span style='color:red'>차감</span>";}
    ?>
    <tr>
      <th><p class="center small_gray"><?=$sRows['regdate']?></p></th>
			<td><p class="left"><?=$productName?></p></td>
			<td class="bg_gray"><p class="center"><?=number_format($sRows['payPrice'] - $sRows['useMileage'])?></p></td>
			<td><p class="center small_gray"><span style="color:<?=$statusColor[$sRows['status']]?>"><?=$cfg['orderStatus'][$sRows['status']]?></span></p></td>
    </tr>
    <?php
      $n--;
      $i++;
    }
    while($i < $row)
    {
      print('<tr">
          <td>　</td>
          <td>　</td>
          <td>　</td>
          <td>　</td>
      </tr>');
      $i++;
    }
    ?>
    </tbody>
    </table>
    <div class="pageNavigation" style="height:20px;"><?=$pagingResult[PageLink]?></div>
    </div></div>
  </td>
</tr>
</table>
<?php
require_once "../../../_Admin/include/commonScript.php";
?>

