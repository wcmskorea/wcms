<?php
/**
 * 회원별 적립 포인트 관리
 * Author : Sung-Jun, Lee (http://www.aceoa.com)
 * Lastest : 2011. 1. 28.
 */
require_once "../../../_Admin/include/commonHeader.php";

if($_POST[type] == 'mileagePost')
{
  foreach($_POST AS $key=>$val)
  {
    ${$key} = trim($val);
    # $func->validCheck(체크할 값, 항목제목, 항목명, 체크타입)
    if($key == "user")    $func->vaildCheck($val, "회원 아이디", "userid", "M", "pass");
    if($key == "mesg")    $func->vaildCheck($val, "적립 사유", "trim", "M", "pass");
    if($key == "choice")  $func->vaildCheck($val, "적립 형태", "trim", "M", "pass");
    if($key == "point")   $func->vaildCheck($val, "적립 금액", "money", "M", "pass");
  }

	$addMent = null;
	$sign = ($_POST[choice] == 'plus') ? "A" : "U";
  $point= str_replace(",", null, $point);

	$data = array("mileageType"=>$sign,"mileage"=>$point,"code"=>"007001","memo"=>addslashes($mesg),"id"=>$user);
	if($mileage->MileageInsert($data)){
		$addMent .= ($sign == 'A') ? "\\n\\n[적립금 추가] ".$point." Point 적립!" : "\\n\\n[적립금 차감] ".$point." Point 차감!";
	}

  $func->err("정상적으로 적용되었습니다.".$addMent, "parent.$.dialog('../modules/mdMember/manage/_controll.php', '&type=mileage&user=".$user."',1000,500);");

} else if($_POST[type] == 'mileageDel')
{
//  $Rows = $db->QueryFetch(" SELECT * FROM `mdMessage__box` WHERE seq='".$_POST[idx]."' ");
//  $db->QueryForce(" DELETE FROM `mdPayment__moneys` WHERE mileages='".$Rows[seq]."' ");
//  $db->Query(" DELETE FROM `mdMessage__box` WHERE seq='".$Rows[seq]."' ");
//  $db->Query(" OPTIMIZE TABLE `mdMessage__box`,`mdPayment__moneys` ");
//  $func->altMsg("정상적으로 삭제되었습니다.");
}
?>

<div class="menu_violet"><p title="드래그하여 이동하실 수 있습니다"><strong>회원 포인트 내역 및 관리 [ 회원ID : <?=$_GET['user']?> ]</strong></p></div>

<table class="table_basic">
<col width="350">
<col>
<tr>
  <td style="vertical-align:top; padding:8px;" class="bg_gray">
    <form name="frmMember" method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data" target="hdFrame">
    <input type="hidden" name="type" value="mileagePost" />
    <input type="hidden" name="user" value="<?php echo($_GET['user']); ?>" />
    <?php
    	include "./detailView.php";
    ?>
    <div style="border-top:1px dashed #999; padding:10px 0;">
      <p class="pd3"><strong>&lt; 포인트 변동사유 &gt;</strong> 100자이내</p>
      <p><textarea name="mesg" style="width:99%;height:130px;" class="input_blue"></textarea></p>
      <div style="padding:3px 0;">
			<ul>
				<li class="opt"><span class="pd3"><input type="radio" id="choice01" name="choice" value="plus" checked="checked" /><label for="choice01">적립</label></span></li>
				<li class="opt"><span><input type="radio" id="choice02" name="choice" value="minus" /><label for="choice02">차감</label></span></li>
				<!--
				<li class="opt"><span><input type="radio" id="choice03" name="choice" value="switch" /><label for="choice03">예치금 전환</label></span></li>
				-->
				<li class="opt"><span>&nbsp;&nbsp;<input type="text" name="point" class="input_blue" style="width:100px; text-align:center" onkeyup="checkComma(this);" />&nbsp;<strong>Point</strong></span></li>
				<li class="opt"><span class="btnPack red small strong"><button type="submit">포인트 적용</button></span></li>
			</ul>
      </div>
    </div>
    </form>
  </td>
  <td style="vertical-align:top;">
    <div class="cube"><div class="line">
    <table class="table_list" style="width:100%;">
    <col width="80">
    <col>
		<col width="80">
    <col width="150">
    <thead>
      <th style="text-align:center" class="first"><p class="center">적립/차감</p></th>
      <th style="text-align:center">내용</th>
      <th style="text-align:center">적립금</th>
      <th style="text-align:center">등록일자</th>
    </thead>
    <tbody>
    <?php
    #--- 게시물 리스트 및 페이징 설정
    $row						= 15;
    $block					= 10;
		$memberInfo = $member->memberInfo($user);

		$db->query(" SELECT COUNT(*) AS cmt FROM `mdMileage__mileage` WHERE memberSeq='".$memberInfo['seq']."'");
		$rst = $db->Fetch();
		$totalRec = $rst['cmt'];

		$queryString		= "&amp;".__PARM__."&amp;type=mileage&amp;user=".$_GET['user'];
    $pagingInstance = new Paging($totalRec, $currentPage, $row, $block);
    $pagingInstance->mode  = "dialog";
    $pagingInstance->width = 1000;
    $pagingInstance->height= 500;
    $pagingInstance->addQueryString($queryString);
    $pagingResult		= $pagingInstance->result("../modules/mdMember/manage/_controll.php");
    $n							= $totalRec - $pagingResult[LimitIndex];
    $i = 0;

		$db->query(" SELECT * FROM `mdMileage__mileage` WHERE memberSeq='".$memberInfo['seq']."' ORDER BY regdate DESC ".$pagingResult['LimitQuery'] );
    while($sRows = $db->Fetch())
    {
			if($sRows['mileageType'] == "A") { $mileageType = "<span style='color:blue'>적립</span>";}
			elseif($sRows['mileageType'] == "U") { $mileageType = "<span style='color:red'>차감</span>";}
    ?>
    <tr>
      <th><p class="center"><?=$mileageType?></p></th>
			<td><p class="left"><?=$sRows['memo']?></p></td>
			<td class="bg_gray"><p class="center"><?=number_format($sRows['mileage'])?></p></td>
			<td><p class="center"><?=$sRows['regdate']?></p></td>
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

