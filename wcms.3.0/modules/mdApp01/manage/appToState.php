<?php
include "../../../_config.php";
if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER[HTTP_REFERER])) { $func->err("[경고]정상적인 접근이 아닙니다."); }

if($_POST['type'] == 'statePost') 
{
	$state = $_POST['state'];
	$choices = explode("|", $_POST['choices']);

	$config = (array)unserialize($db->queryFetchOne(" SELECT config FROM `mdApp01__` WHERE cate='".__CATE__."' "));
	$result = ($config['resultAdmin']) ? explode(",", $config['resultAdmin']) : explode(",", $config['result']);

	$stateName = $result[$state] ? $result[$state] : "완료";
	$cateInfo  = $category->getCategoryInfo(__CATE__,$config['skin']);
	$cateName  = $cateInfo['name'] ? $cateInfo['name'] : '상담.문의';

	if($func->checkModule('mdSms') && $_POST['sendSMS'] == 'send')
	{
		$sock->tempMode = "mdApp01";
	} else {
		$sock->tempMode = null;
	}

	foreach($choices AS $val) 
	{
		if($val) 
		{
			$db->query(" UPDATE `mdApp01__content` SET state='".$_POST['state']."',info='".$cfg['timeip']."' WHERE seq='".$val."' ");
			if($sock->tempMode && $_POST['sendSMS'] == 'send')
			{
				$app = $db->queryFetchOne(" SELECT `name`,`mobile` FROM `mdApp01__content` WHERE seq='".$val."' ");
				if($app['mobile']) 
				{
					$sock->tempArray	= array($app['name'], $cateName, $stateName, $cfg['site']['siteName']);//{회원명},{카테고리},{처리구분명},{사이트}
					$sock->smsSend($mobile, "temp03");
					$sock->varReset();
				}
			}
		}
		unset($app);
	}
	$func->err("정상적으로 변경되었습니다.","parent.$.insert('#left_mdApp01','../modules/mdApp01/manage/_left.php',null,'20'); parent.$.insert('#module', '../modules/mdApp01/manage/_controll.php?type=list&state=".$_POST['state']."&cate=".__CATE__."',null,300); parent.$.dialogRemove();");
} 
else 
{
	//선택요청
	foreach($_GET['choice'] AS $val) {
		$choices .= $val."|";
	}
	$config = (array)unserialize($db->queryFetchOne(" SELECT config FROM `mdApp01__` WHERE cate='".__CATE__."' "));
	$result = ($config['resultAdmin']) ? explode(",", $config['resultAdmin']) : explode(",", $config['result']);
}
?>
<div id="modal">
<form name="changeForm" method="post" action="<?php echo($_SERVER['PHP_SELF']);?>" enctype="multipart/form-data" target="hdFrame">
<input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />
<input type="hidden" name="type" value="statePost" />
<input type="hidden" name="choices" value="<?php echo($choices);?>" />

<div class="menu_violet"><p title="드래그하여 좌우이동이 가능합니다">선택 요청상태 변경</p></div>
<div class="pd10 center">총[<strong style="color:red;"><?php echo(count($_GET['choice']));?></strong>]건의 요청을 선택하셨습니다.</div>
<div class="center"><select name="state" style="width:250px;">
	<?php	foreach($result AS $key=>$val) 
	{
		echo('<option value="'.$key.'">&nbsp;'.$val.'</option>');
	}
	?>
  </select>
</div>
<div class="pd5 center"><input type="checkbox" id="sendSMS" name="sendSMS" value="send" /><label for="sendSMS">결과 문자전송</label></div>
<div class="center pd10"><span class="btnPack black medium strong"><button type="submit">적용하기</button></span>&nbsp;&nbsp;<span class="btnPack medium white"><button type="button" onclick="$.dialogRemove()">취소</button></span></div>

</form>
</div>
<script type="text/javascript">
$(document).ready(function()
{
	$("input[type=text],input[type=password],textarea").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active");});
	$("#ajax_display").css('background-color','#efefef');
});
</script>
