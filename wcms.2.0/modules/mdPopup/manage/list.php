<?php
/*---------------------------------------------------------------------------------------
| 카테고리 Display
|----------------------------------------------------------------------------------------
| Relationship : mdPopup/manage/_controll.php
| Last (2008.10.04 : 이성준)
*/
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
usleep($cfg[sleep]);

/* 게시물 설정 */
$row			= ($_POST[rows]) ? $_POST[rows] : 10;
$block			= 10;
$totalRec		= $db->QueryFetchOne("SELECT COUNT(*) FROM `mdPopup__content`");
$currentPage	= ($_POST[currentPage]) ? $_POST[currentPage] : 1;
$queryString	= "&amp;type=list&amp;rows=".$_POST[rows];
$pagingInstance = new Paging($totalRec, $currentPage, $row, $block);
$pagingInstance->mode = "module";
$pagingInstance->addQueryString($queryString);
$pagingResult		=  $pagingInstance->result("../modules/mdPopup/manage/_controll.php");
?>
<h2><span class="arrow">▶</span>팝업·공지 관리</h2>

<div class="table">
<div class="line bg_white">

<dl>
	<dt style="float:left; padding:5px; vertical-align:center;"><span class="small_gray">총 <strong class="small_orange"><?php echo($totalRec);?></strong> 건</span></dt>
	<dd style="float:right; padding:2px;">
	<select name="chrows" class="bg_gray" onchange="$.insert('#module', '../modules/mdPopup/manage/_controll.php', '&amp;type=list&amp;rows='+this.value,300)">
			<option value="10" <?php echo($_POST[rows]=='10')?'selected="selected"':null; ?>>10건씩 보기</option>
			<option value="30" <?php echo($_POST[rows]=='30')?'selected="selected"':null; ?>>30건씩 보기</option>
			<option value="50" <?php echo($_POST[rows]=='50')?'selected="selected"':null; ?>>50건씩 보기</option>
	</select></dd>
</dl>
<div class="clear"></div>

<form name="listForm" enctype="multipart/form-data" onsubmit="return false;">
<input type="hidden" name="type" value="">
<input type="hidden" name="rtotal" value="">

<table class="table_basic" style="width:100%;">
  <caption></caption>
  <colgroup>
  <col width="50">
  <col width="50">
  <col>
  <col width="80">
  <col width="160">
  <col width="110">
  <col width="90">
  </colgroup>
  <thead>
  <tr>
    <th class="first"><p class="center">노출</p></th>
    <th><p class="center">위치</p></th>
    <th><p class="center">타이틀</p></th>
    <th><p class="center">사이즈</p></th>
    <th><p class="center">노출일정</p></th>
    <th><p class="center">등록일</p></th>
    <th><p class="center">관리</p></th>
  </tr>
  </thead>
  <tbody>
  <?php
  $db->query(" SELECT * FROM `mdPopup__content` ORDER BY seq DESC ".$pagingResult[LimitQuery]);
  if($db->getNumRows() < 1) {
    echo '<tr><td class="blank" colspan="7">등록된 팝업정보가 존재하지 않습니다.</td></tr>';
  } else {
    while($Rows = $db->fetch()) {
      if($Rows[cate] != "main") {
        $page = '<span class="blue">서브</span>';
      } else {
        $page = '<span class="green">메인</span>';
      }
      $eperiod  = ($Rows[eperiod] > 0) ? date("y.m.d",$Rows[eperiod]) : '<span class="small_red">무기한</span>';
      $eperiod  = ($Rows[eperiod] > 0 && $Rows[eperiod] < time()) ? '<span class="small_red">'.$eperiod.'</span>' : $eperiod;
      $hidden   = ($Rows[hidden] == 'N' && $Rows[eperiod] == 0 || $Rows[eperiod] > time()) ? '<span class="blue">표시</span>' : "미표시";
      $hidden   = ($Rows[hidden] == 'Z') ? '<span class="blue">팝업존</span>' : $hidden;
      $size     = explode(",",$Rows[size]);
      $width    = $size[0] - 10;
      $height   = $size[1];
  ?>
  <tr>
    <th><p class="center"><?php echo($hidden);?></p></th>
    <td><p class="center"><?php echo($page);?></p></td>
    <td><a href="javascript:;" onclick="$.dialog('../modules/mdPopup/manage/preview.php?idx=<?php echo($Rows[seq]);?>',null,<?php echo(intval($width+10));?>,<?php echo(intval($height));?>)"><strong><?php echo($Rows[subject]);?></strong></a></td>
    <td><p class="center"><?php echo($size[0]);?> X <?php echo($size[1]);?></p></td>
    <td><p class="center"><?php echo(date("y.m.d",$Rows[speriod]));?> ~ <?php echo($eperiod);?></p></td>
    <td><p class="center"><?php echo(date("y.m.d H:i",$Rows[date]));?></p></td>
    <td>
      <span><a href="javascript:;" onclick="new_window('../modules/mdPopup/manage/insert.php?idx=<?php echo($Rows[seq]);?>','mdPopup','1024','700','no','yes');" class="btnPack black small"><span>수정</span></a>&nbsp;<a href="javascript:;" onclick="if(delThis()){$.message('../modules/mdPopup/manage/_controll.php', '&amp;type=delete&amp;idx=<?php echo($Rows[seq]);?>')}" class="btnPack small"><span>삭제</span></a></span>
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
<div class="pageNavigation"><?php echo($pagingResult[pageLink]);?></div>
</div></div>
