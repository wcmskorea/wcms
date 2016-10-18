<?php
include "../../../_config.php";
if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER'])) { $func->err("[경고]정상적인 접근이 아닙니다."); }
if($_POST[type] == 'groupPost') {
	if($_SERVER[REQUEST_METHOD] == 'GET' ) { $func->err("[경고]정상적인 접근이 아닙니다."); }
	$members = explode("|", $_POST[members]);
	foreach($members AS $val) {
	    if($val) {
			$db->query(" UPDATE `mdMember__account` SET dedicate='".$_POST[division]."' WHERE id='".$val."' ");
	    }
	}
	$func->err("정상적으로 변경되었습니다.","parent.$.insert('#module', '../modules/mdMember/manage/_controll.php?type=list&div=".$_POST[division]."',null,300); parent.$.dialogRemove();");
} else {
	//회원구분
	$division = $db->queryFetchOne(" SELECT dedicate FROM `mdMember__` WHERE cate='000002002' ");
	$division = explode(',', $division);
	//선택회원
	foreach($_GET[choice] AS $val) {
		$members .= $val."|";
	}
}
?>
<div id="modal">
<form name="changeForm" method="post" action="<?=$_SERVER[PHP_SELF]?>" enctype="multipart/form-data" target="hdFrame">
<input type="hidden" name="type" value="groupPost" />
<input type="hidden" name="members" value="<?=$members?>" />

<div class="menu_violet"><p title="드래그하여 좌우이동이 가능합니다">선택 회원구분 변경</p></div>
<div class="pd10 center small_gray">총[<strong style="color:red;"><?=count($_GET[choice])?></strong>]명의 회원을 선택하셨습니다.</div>
<div class="center"><select name="division" style="width:250px;">
  <?php
    foreach($division AS $val)
    {
      print('<option value="'.$val.'">&nbsp;'.$val.'</option>');
    }
  ?>
  </select>
</div>
<div class="center pd10"><span class="button bblack strong"><button type="submit">적용하기</button></span>&nbsp;&nbsp;<span class="button bgray"><button type="button" onclick="$.dialogRemove()">취소</button></span></div>

</form>
</div>
<script type="text/javascript">
$(document).ready(function()
{
	$("input[type=text],input[type=password],textarea").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active");});
  setTimeout ("$('#shc').select()", 500);
});
</script>
