<?php
/* ---------------------------------------------------------------------------------------
 | 우편번호 & 주소검
 |-----------------------------------------------------------------------------------------
 | Lastest : 2008-12-15
 */
require_once "../../../_config.php";

if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER']))	{ echo("[경고!] 정상적인 접근이 아닙니다."); }
usleep($cfg[sleep]);

if($sess->decode($_GET[type]) != "checkZip")
{
	$func->ajaxMsg("잘못된 접근입니다", "", "100");
} else
{
	$_GET['addr'] = ($cfg[charset] == 'euc-kr') ? iconv("UTF-8", "CP949", $_GET['addr']) : $_GET['addr'];
}
?>
<div id="modal">
<form name="frmMember" method="post" action="" enctype="multipart/form-data" onsubmit="return checkSubmit(this);">
<input type="hidden" name="type" value="<?php echo($sess->encode("checkZip"));?>" />

<div class="menu_black">
	<p>우편번호 및 주소검색</p>
</div>
<div class="pd10 center">주소검색은 "동이름" 혹은 "마을이름" 으로 검색하세요.</div>
<div class="input">
<ul>
	<li><input type="text" id="addrSearch" name="addr" title="검색할 주소" class="input_gray center" style="width:320px;" value="<?php echo($_GET['addr']);?>" /></li>
	<li><span class="btnPack green small strong"><button type="submit">검색하기</button></span></li>
</ul>
<div class="clear"></div>
</div>
<div class="frame" style="width:430px; height:200px;">
<?php
if($_GET['addr'])
{
	$db->DisConnect();
	$db->Connect(1);
	$db->selectDB("commonSql");
	if($db->CheckTable("site__zipcode"))
	{
		$db->query(" SELECT * FROM `site__zipcode` WHERE DONG like '%".$_GET['addr']."%' ");
		while($Rows = $db->fetch())
		{
			$address = $Rows['sido']." ".$Rows['gugun']." ".$Rows['dong'];
			printf('<p class="address"><a href="#none" onclick="$.insertAddress(\'%s\',\'%s\',\'%s\');return false;">(%s) %s %s %s %s</a></p>', $Rows['zipcode'], $address, $_GET['target'], $Rows['zipcode'], $Rows['sido'], $Rows['gugun'], $Rows['dong'], $Rows['bunji']);
		}
	} else
	{
		$func->err("주소검색 데이터베이스가 구성되어 있지 않습니다.", "$.dialogRemove();");
	}
	$db->DisConnect();
	$db->Connect(1);
	$db->selectDB(__DB__);
}
?>
</div>
</form>
</div>
<script type="text/javascript">
//<![CDATA[
function checkSubmit(frm) {
	if(frm.addr.value == '') {
		alert("검색명을 입력해주십시오");
		frm.addr.select();
		return false;
	} else {
		$.checkFarm(frm, './modules/mdMember/widgets/checkZip.php','dialog','',450,305);
		return true;
	}
}
$(document).ready(function() {
	$("input, textarea").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active");});
	$("#ajax_display").css('background','#fff');
	$('#addrSearch').focus().toggleClass("input_active").select();
});
//]]>
</script>
