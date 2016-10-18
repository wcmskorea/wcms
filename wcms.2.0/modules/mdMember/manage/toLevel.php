<?php
/* --------------------------------------------------------------------------------------
| 회원등급 일괄 변경하기
|----------------------------------------------------------------------------------------
| Lastest : 이성준 (2009년 11월 30일 월요일)
*/
include "../../../_config.php";
require_once "../../../_Admin/include/commonHeader.php";

if($_POST[type] == 'levelPost') {
  $members = explode("|", $_POST[members]);
  foreach($members AS $val) {
    if($val) {
      $db->query(" UPDATE `mdMember__account` SET level='".$_POST[level]."' WHERE id='".$val."' ");
      //회원탈퇴일 경우 : 개인정보 삭제
      if($_POST[level] == '0') { $db->query(" UPDATE `mdMember__info` SET dateExpire='".time()."' WHERE id='".$val."'"); }
    }
  }
	$func->err("정상적으로 변경되었습니다.","parent.$.insert('#left_mdMember','../modules/mdMember/manage/_left.php',null,50); parent.$.insert('#module', '../modules/mdMember/manage/_controll.php?type=list',null,300); parent.$.dialogRemove();");
} else {
	foreach($_GET[choice] AS $key=>$val) {
		$members .= $val."|";
	}
}
?>
<div id="modal">
  <form name="changeForm" method="post" action="<?php echo($_SERVER[PHP_SELF]);?>" enctype="multipart/form-data" target="hdFrame">
  <input type="hidden" name="type" value="levelPost" />
  <input type="hidden" name="members" value="<?php echo($members);?>" />

  <div class="menu_violet"><p title="드래그하여 좌우이동이 가능합니다">선택 회원등급 변경</p></div>
  <div class="pd10 center small_gray">총[<strong style="color:red;"><?php echo(count($_GET[choice]));?></strong>]명의 회원을 선택하셨습니다.</div>
  <div class="center"><select name="level" style="width:250px;">
    <?php
      $db->query(" SELECT * FROM `mdMember__level` WHERE level BETWEEN '2' AND '98' AND position <> '' ORDER BY level DESC ");
      while($Rows = $db->fetch()) {
        print('<option value="'.$Rows[level].'">['.str_pad($Rows[level], 2, "0", STR_PAD_LEFT).']&nbsp;'.$Rows[position].'</option>');
      }
    ?>
    <option value="0" class="red">[00] 탈퇴회원 (개인정보 삭제)</option>
    </select>
  </div>
  <div class="center pd10"><span class="btnPack medium black strong"><button type="submit">적용하기</button></span>&nbsp;&nbsp;<span class="btnPack medium gray"><button type="button" onclick="$.dialogRemove()">취소</button></span></div>

  </form>
</div>
<?php
require_once "../../../_Admin/include/commonScript.php";
?>