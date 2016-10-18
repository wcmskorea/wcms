<?php
/*---------------------------------------------------------------------------------------
| 카테고리 Display
|----------------------------------------------------------------------------------------
| Relationship : mdPopup/manage/_controll.php
| Last (2008.10.04 : 이성준)
*/
require __PATH__."_Admin/include/commonHeader.php";

#--- 노출순위 변경
if($_GET['type'] == "listMove")
{
  $return = (intval($_GET['idx'] - 1) > 0) ? $_GET['idx'] - 1 : 1;
	$db->query("UPDATE `mdBanner__content` SET seq='0' WHERE seq='".$_GET['idx']."' AND position='".$_GET['position']."' AND skin='".$_GET['skin']."'");
	if($_GET['move'] == 'up')
  {
		$db->query("UPDATE `mdBanner__content` SET seq='".$_GET['idx']."' WHERE seq='".$return."' AND position='".$_GET['position']."' AND skin='".$_GET['skin']."'");
		$db->query("UPDATE `mdBanner__content` SET seq='".$return."' WHERE seq='0' AND position='".$_GET['position']."' AND skin='".$_GET['skin']."'");
	}	else if($_GET['move'] == 'dw')
  {
		$db->query("UPDATE `mdBanner__content` SET seq='".$_GET['idx']."' WHERE seq='".intval($_GET['idx']+1)."' AND position='".$_GET['position']."'  AND skin='".$_GET['skin']."'");
		$db->query("UPDATE `mdBanner__content` SET seq='".intval($_GET['idx']+1)."' WHERE seq='0' AND position='".$_GET['position']."' AND skin='".$_GET['skin']."'");
	}
}

$sq = "1 ";
if($_GET['skin'])     $sq .= "AND skin='".$_GET['skin']."' ";
if($_GET['position']) $sq .= "AND position='".$_GET['position']."' ";

#--- 게시물 설정
$row						= ($_GET['rows']) ? $_GET['rows'] : 10;
$totalRec				= $db->queryFetchOne("SELECT COUNT(*) FROM `mdBanner__content` WHERE ".$sq);
$pagingInstance = new Paging($totalRec, $currentPage, $row, 10);
$pagingInstance->mode = "module";
$pagingInstance->addQueryString("&amp;type=list&amp;skin=".$_GET['skin']."&position=".$_GET['position']."&amp;rows=".$_GET['rows']);
$pagingResult		=  $pagingInstance->result("../modules/mdBanner/manage/_controll.php");
?>
<h2><span class="arrow">▶</span>배너·이미지 관리&nbsp;&nbsp;<span class="normal">&gt;&nbsp;등록된 배너 및 이미지 비주얼</span></h2>
<div class="table">
<div class="line bg_white">

<dl>
	<dt style="float:left; padding:5px; vertical-align:center;"><span class="small_gray">총 <strong class="small_orange"><?php echo($totalRec);?></strong> 건</span></dt>
	<dd style="float:right; padding:2px;"><span class="btnPack red small"><button type="button" onclick="$.dialog('../modules/mdBanner/manage/_controll.php?type=insert&amp;skin=<?php echo($_GET['skin']);?>',null,800,500)">신규 배너등록</button></span></dd>
	<dd style="float:right; padding:2px;">
	<select name="chrows" class="bg_gray" onchange="$.insert('#module', '../modules/mdBanner/manage/_controll.php?type=list&amp;skin=<?php echo($_GET['skin']);?>&amp;position=<?php echo($_GET['position']);?>&amp;rows='+this.value,null,300)">
			<option value="10" <?php echo($_GET['rows']=='10')?'selected="selected"':null; ?>>10건씩 보기</option>
			<option value="30" <?php echo($_GET['rows']=='30')?'selected="selected"':null; ?>>30건씩 보기</option>
			<option value="50" <?php echo($_GET['rows']=='50')?'selected="selected"':null; ?>>50건씩 보기</option>
	</select></dd>
</dl>
<div class="clear"></div>

<form name="listForm" enctype="multipart/form-data" onsubmit="return false;">
<input type="hidden" name="type" value="">
<input type="hidden" name="rtotal" value="">

<table class="table_list" style="width:100%;">
  <caption></caption>
  <col width="50">
	<col width="60">
  <col>
  <col width="60">
  <col width="70">
  <col width="130">
  <col width="110">
  <col width="90">
  <thead>
  <tr>
    <th class="first"><p class="center">노출</p></th>
		<th><p class="center">이동</p></th>
    <th><p class="center">제목</p></th>
    <th><p class="center">형태</p></th>
    <th><p class="center">사이즈</p></th>
    <th><p class="center">노출기간</p></th>
    <th><p class="center">등록일</p></th>
		<th><p class="center">관리</p></th>
  </tr>
  </thead>
  <tbody>
  <?php
  $db->query(" SELECT * FROM `mdBanner__content` WHERE ".$sq." ORDER BY seq ASC ".$pagingResult['LimitQuery'] );

  if($db->getNumRows() < 1)
	{
		echo('<tr><td class="blank" colspan="8">등록된 배너정보가 존재하지 않습니다.</td></tr>');
  } else
	{
    while($Rows = $db->fetch())
		{
      $dir      = "/user/data/".date("Y",$Rows['date'])."/".date("m",$Rows['date'])."/".$Rows['filename'];
      $eperiod  = ($Rows['eperiod'] > 0) ? date("y.m.d",$Rows['eperiod']) : '<span class="small_red">무기한</span>';
      $eperiod  = ($Rows['eperiod'] > 0 && $Rows['eperiod'] < time()) ? '<span class="small_red">'.$eperiod.'</span>' : $eperiod;
      $hidden   = ($Rows['hidden'] == 'N' && $Rows['eperiod'] == 0 || $Rows['eperiod'] > time()) ? '<span class="blue">표시</span>' : "미표시";
  ?>
    <tr>
      <th><p class="center"><?php echo($hidden);?></p></th>
      <td class="bg_gray"><p class="center"><span><a href="javascript:$.insert('#module', '../modules/mdBanner/manage/_controll.php?type=listMove&amp;idx=<?php echo($Rows['seq']);?>&amp;skin=<?php echo($_GET['skin']);?>&amp;position=<?php echo($Rows['position']);?>&amp;rows=<?php echo($_GET['rows']);?>&amp;move=up',null,300)"><img src="<?php echo($cfg['droot']);?>common/image/button/btn_s_up.gif" alt="위로" title="위로" /></a></span>
        <span><a href="javascript:$.insert('#module', '../modules/mdBanner/manage/_controll.php?type=listMove&amp;idx=<?php echo($Rows['seq']);?>&amp;skin=<?php echo($_GET['skin']);?>&amp;position=<?php echo($Rows['position']);?>&amp;rows=<?php echo($_GET['rows']);?>&amp;move=dw',null,300)"><img src="<?php echo($cfg['droot']);?>common/image/button/btn_s_down.gif" alt="아래로" title="아래로" /></a></span></p></td>
			<td>
        <p><span>[<?php echo($Rows['seq']);?>]</span>
        <span><a href="#none" onclick="$.dialog('../modules/mdBanner/manage/_controll.php?type=insert&amp;idx=<?php echo($Rows['seq']);?>&amp;skin=<?php echo($_GET['skin']);?>&amp;position=<?php echo($Rows['position']);?>',null,800,500);"><strong><?php echo($Rows['subject']);?></strong></a></span></div><div style="display:none;"><img src="<?php echo($dir);?>" width="<?php echo($Rows['width']);?>" height="<?php echo($Rows['height']);?>" /></p>
      </td>
      <td class="bg_gray"><p class="center"><?php echo($Rows['type']);?></p></td>
      <td class="bg_gray"><p class="center"><?php echo($Rows['width']);?> X <?php echo($Rows['height']);?></p></td>
      <td><p class="center"><?php echo(date("y.m.d",$Rows['speriod']));?> ~ <?php echo($eperiod);?></p></td>
      <td class="bg_gray"><p class="center"><?php echo(date("y.m.d H:i",$Rows['date']));?></p></td>
			<td class="bg_gray">
        <span><a href="#none" onclick="$.dialog('../modules/mdBanner/manage/_controll.php?type=insert&amp;idx=<?php echo($Rows['seq']);?>&amp;skin=<?php echo($_GET['skin']);?>&amp;position=<?php echo($Rows['position']);?>',null,800,500);" class="btnPack black small"><span>수정</span></a>
        <a href="#none" onclick="if(delThis()){$.message('../modules/mdBanner/manage/_controll.php?type=delete&amp;idx=<?php echo($Rows['seq']);?>&amp;skin=<?php echo($_GET['skin']);?>&amp;position=<?php echo($Rows['position']);?>')}" class="btnPack gray small"><span>삭제</span></a></span>
      </td>
    </tr>
  <?php
    }
  }
  ?>
  </tbody>
</table>
<input type="hidden" name="total" value="<?php echo($i);?>" />
</form>
<div class="pageNavigation"><?php echo($pagingResult['pageLink']);?></div>
</div></div>
<?php
require __PATH__."_Admin/include/commonScript.php";
?>
