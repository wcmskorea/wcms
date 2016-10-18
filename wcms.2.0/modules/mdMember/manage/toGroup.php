<?php
/* --------------------------------------------------------------------------------------
| 회원그룹 일괄 변경하기
|----------------------------------------------------------------------------------------
| Lastest : 이성준 (2009년 11월 30일 월요일)
*/
include "../../../_config.php";
require_once "../../../_Admin/include/commonHeader.php";

if($_POST[type] == 'groupPost') {
  $members = explode("|", $_POST[members]);
  foreach($members AS $val) {
    if($val) {
      $db->query(" UPDATE `mdMember__account` SET `group`='".$_POST[group]."' WHERE id='".$val."' ");
    }
  }
	$func->err("정상적으로 변경되었습니다.","parent.$.insert('#left_mdMember','../modules/mdMember/manage/_left.php',null,50); parent.$.insert('#module', '../modules/mdMember/manage/_controll.php?type=list',null,300); parent.$.dialogRemove();");
} else {
	foreach($_GET[choice] AS $key=>$val) {
		$members .= $val."|";
	}

	//모듈 환경설정 취합
	$cfg['module'] = (array)$db->queryFetch(" SELECT * FROM `mdMember__` WHERE 1 LIMIT 1");
	//모듈 환경설정 취합
	$cfg['module'] = @array_merge($cfg['module'], (array)unserialize($cfg['module']['config']));
	$cfg['module'] = @array_merge($cfg['module'], (array)unserialize($cfg['module']['contentAdd']));
}
?>
<div id="modal">
  <form name="changeForm" method="post" action="<?php echo($_SERVER[PHP_SELF]);?>" enctype="multipart/form-data" target="hdFrame">
  <input type="hidden" name="type" value="groupPost" />
  <input type="hidden" name="members" value="<?php echo($members);?>" />

  <div class="menu_violet"><p title="드래그하여 좌우이동이 가능합니다">선택 회원그룹 변경</p></div>
  <div class="pd10 center small_gray">총[<strong style="color:red;"><?php echo(count($_GET[choice]));?></strong>]명의 회원을 선택하셨습니다.</div>
  <div class="center"><select name="group" style="width:250px;">
    <?php
      $group = explode(',', $cfg['module']['group']);
      foreach($group AS $key=>$val) {
        print('<option value="'.$val.'">'.$val.'</option>');
      }
    ?>
    <option value="" class="red">그룹명 없음</option>
    </select>
  </div>
  <div class="center pd10"><span class="btnPack medium black strong"><button type="submit">적용하기</button></span>&nbsp;&nbsp;<span class="btnPack medium gray"><button type="button" onclick="$.dialogRemove()">취소</button></span></div>

  </form>
</div>
<?php
require_once "../../../_Admin/include/commonScript.php";
?>