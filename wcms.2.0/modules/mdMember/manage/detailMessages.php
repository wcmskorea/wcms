<?php
/**
 * 회원별 적립 포인트 관리
 * Author : Sung-Jun, Lee (http://www.aceoa.com)
 * Lastest : 2011. 1. 28.
 */
# 리퍼러 체크
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
usleep($cfg[sleep]);

if($_POST[type] == 'mileagePost')
{
  foreach($_POST AS $key=>$val)
  {
    ${$key} = trim($val);
    # $func->validCheck(체크할 값, 항목제목, 항목명, 체크타입)
    if($key == "user")    $func->vaildCheck($val, "회원 아이디", "user", "userid", "M");
    if($key == "mesg")    $func->vaildCheck($val, "적립 사유", "mesg", "trim", "M");
    if($key == "choice")  $func->vaildCheck($val, "적립 형태", "choice", "trim", "M");
    if($key == "point")   $func->vaildCheck($val, "적립 금액", "point", "money", "M");
  }
  $sign = ($_POST[choice] == 'plus') ? "+" : "-";
  $mesg = $mesg."『".$point."』Point";
  $point= str_replace(",", null, $point);
  Member::memberMileages($user, $_POST[choice], $sign.$point, $mesg);
  $func->Err("정상적으로 적용되었습니다.", "parent.$.dialog('../modules/mdMessage/manage/_controll.php', '&type=mileage&user=".$user."',800,480);");

} else if($_POST[type] == 'mileageDel')
{
  $Rows = $db->QueryFetch(" SELECT * FROM `mdMessage__box` WHERE seq='".$_POST[idx]."' ");
  $db->QueryForce(" DELETE FROM `mdPayment__moneys` WHERE mileages='".$Rows[seq]."' ");
  $db->Query(" DELETE FROM `mdMessage__box` WHERE seq='".$Rows[seq]."' ");
  $db->Query(" OPTIMIZE TABLE `mdMessage__box`,`mdPayment__moneys` ");
  $func->altMsg("정상적으로 삭제되었습니다.");
}
?>

<div class="menu_violet"><p title="드래그하여 이동하실 수 있습니다"><strong>회원 포인트 내역 및 관리 [ 회원ID : <?=$_POST[user]?> ]</strong></p></div>

<table>
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
      <strong>&lt; 포인트 변동사유 &gt;</strong> 100자이내
      <p><textarea name="mesg" style="width:99%;height:55px;" class="input_white_line"></textarea></p>
      <div style="padding:3px 0;">
      <span class="pd3"><input type="radio" id="choice01" name="choice" value="plus" checked="checked" /><label for="choice01">추가</label></span>
      <span><input type="radio" id="choice02" name="choice" value="minus" /><label for="choice02">삭감</label></span>
      <span><input type="radio" id="choice03" name="choice" value="switch" /><label for="choice03">예치금 전환</label></span>
      <span>&nbsp;&nbsp;<input type="text" name="point" class="input_white_line" style="width:100px;text-align:center;" onkeyup="checkComma(this);" />&nbsp;<strong>Point</strong></span>
      </div>
      <div class="center pd5">
        <span class="pd3"><span class="button bred strong"><button type="submit">포인트 적용하기</button></span></span>
      </div>
    </div>
    </form>
  </td>
  <td style="vertical-align:top;">
    <div class="cube"><div class="line">
    <table class="table_list" style="width:100%;">
    <col width="50">
    <col width="80">
    <col width="80">
    <col width="80">
    <col>
    <thead>
      <th class="first">상태</th>
      <th>요청시간</th>
      <th>상담자</th>
      <th>상담시간</th>
      <th>상담내용</th>
    </thead>
    <tbody>
    <?php
    #--- 게시물 리스트 및 페이징 설정
    $row						= 10;
    $block					= 10;
    $totalRec				= $func->getTotalCount("mdMessage__box", "sender='".$Rows[id]."'");
    $queryString		= "&amp;".__PARM__."&amp;type=message&amp;idx=".$Rows[seq];
    $pagingInstance = new Paging($totalRec, $currentPage, $row, $block);
    $pagingInstance->mode  = "dialog";
    $pagingInstance->width = 800;
    $pagingInstance->height= 480;
    $pagingInstance->addQueryString($queryString);
    $pagingResult		= $pagingInstance->result("../modules/mdMessage/manage/_controll.php");
    $n							= $totalRec - $pagingResult[LimitIndex];
    $i = 0;
    $db->Query("SELECT * FROM `mdMessage__box` WHERE sender='".$Rows[id]."' ORDER BY sendDate DESC ".$pagingResult[LimitQuery]);
    while($sRows = $db->Fetch())
    {
      $mileageType  = ($sRows[point] < 0) ? '<span class="small_red">( - )</span>' : '<span class="small_blue">( + )</span>';
    ?>
    <tr>
      <th style="height:32px;"><?=$mileageType?></br ><span class="small_gray"><?=substr($sRows[mode],0,4)?></span></th>
      <td><div class="center small_gray"><?=date("Y-m-d H:i:s", $sRows[date])?></div></td>
      <td style="word-break:break-all;"><?php if(!$sRows[moneys]){?><a href="#none" onclick="if(confirm('정말 삭제하시겠습니까? (전환 예치금은 복구됨)')){$.dialog('../modules/mdMessage/manage/_controll.php', '&amp;type=mileageDel&amp;idx=<?=$sRows[seq]?>&amp;user=<?=$Rows[id]?>','800','480')}" class="actSmall">[삭제]</a><?php }?><?=$sRows[mesg]?></td>
    </tr>
    <?php
      $n--;
      $i++;
    }
    while($i < $row)
    {
      print('<tr">
          <td style="height:32px;">　</td>
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

<script type="text/javascript">
$(document).ready(function(){$("input[@type=text],input[@type=password],textarea").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active");});});
</script>
