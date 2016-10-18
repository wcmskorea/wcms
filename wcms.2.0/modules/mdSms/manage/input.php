<?php
require_once "../../../_config.php";
require_once __PATH__."/_Admin/include/commonHeader.php";

$func->checkRefer("GET");
/* --------------------------------------------------------------------------------------
| SMS(Socket) 발송수량 체크
*/
$socket = $sock->smsCheck("count");

?>
<script type="text/javascript">
<!--
//예약 or 즉시
$.sendNow = function(dat) {
	if(dat == 1) {
		toGetElementById('sdate').disabled = true;
		toGetElementById('shour').disabled = true;
		toGetElementById('smin').disabled = true;
	} else {
		toGetElementById('sdate').disabled = false;
		toGetElementById('shour').disabled = false;
		toGetElementById('smin').disabled = false;
	}
};
//-->
</script>
<div id="messageBox"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/delete.gif" /></spna>&nbsp;<strong>Message :</strong>&nbsp;&nbsp;&nbsp;</div>

<form id="frmSms" name="frmSms" method="post" action="../modules/mdSms/manage/post.php" enctype="multipart/form-data" target="hdFrame">
<input type="hidden" name="type" value="<?=$sess->encode('smsPost')?>" />

	<div style="float:left;width:300px;">

		<!-- 수신자 목록 : start -->
		<div class="pd3 center">
			<div class="menu_black"><p title="드래그하여 이동하실 수 있습니다"><strong>수신자 목록</strong></p></div>
				<div class="small_gray center pd3">1회 발송시 최대 10,000건까지</div>
				<textarea name="receiveList" id="receiveList" style="background:#fff url(<?=$cfg['droot']?>common/image/editor/source_bg.gif) 0 0px; color:black; line-height:150%; border:1px solid #999; width:275px; height:192px; padding:0px 5px 0px 5px; word-break:break-all; font-size:9pt; color:#003399;" class="required" req="required" multimobile="true"><?php
				$total = 0;
				//회원 그룹별 발송시
				if($_GET[grplevel])
				{
					$db->Query("SELECT mobile,level FROM `mdMember__account` AS MEM1, `mdMember__info` AS MEM2 WHERE (MEM1.id=MEM2.id) AND MEM1.level='".$_GET[grplevel]."'");
					$all = $db->GetNumRows();
					while($Rows=$db->Fetch())
          {
						$Rows[mobile] = str_replace("--","",$Rows[mobile]);
						if($Rows[level] > '0' && $Rows[mobile])
            {
							echo str_replace("-","",$Rows[mobile])."\n";
							$total++;
						}
						if($total >= $socket[msg]) break;
					}
				} else if($_POST[mobile])
				{
					echo str_replace("-","",$_POST[mobile]);
 				} else if($_GET[rtotal])
				{
					$all = $_GET[rtotal];

					#예약현황에서 발송되는 문자는 회원정보에서 불러오는것이 아닌 예약 현황 테이블에서 전화번호를 가져온다. 2012-01-17 강인
					if($_GET['moduleName'] == 'reserve')
					{
						foreach($_GET[choice] AS $val)
						{
							$user = $_GET[choice][$i];
							$Rows = $db->QueryFetch("SELECT reserveMobile FROM `mdReserve__order` WHERE seq='".$val."'");
							if($Rows['reserveMobile'] != '' && strlen($Rows['reserveMobile']) >= 9)
							{
								echo str_replace("-","",$Rows[reserveMobile])."\n";
								$total ++;
							}
							if($total >= $socket[msg]) break;
						}
					} else if($_GET['moduleName'] == 'mdApp01') {
						foreach($_GET[choice] AS $val)
						{
							$user = $_GET[choice][$i];
							$Rows = $db->QueryFetch("SELECT mobile FROM `mdApp01__content` WHERE seq='".$val."'");
							if($Rows['mobile'] != '' && strlen($Rows['mobile']) >= 9)
							{
								echo str_replace("-","",$Rows[mobile])."\n";
								$total ++;
							}
							if($total >= $socket[msg]) break;
						}
					} else if($_GET['moduleName'] == 'mdApp02') {
						foreach($_GET[choice] AS $val)
						{
							$user = $_GET[choice][$i];
							$Rows = $db->QueryFetch("SELECT mobile FROM `mdApp02__content` WHERE seq='".$val."'");
							if($Rows['mobile'] != '' && strlen($Rows['mobile']) >= 9)
							{
								echo str_replace("-","",$Rows[mobile])."\n";
								$total ++;
							}
							if($total >= $socket[msg]) break;
						}
					} else if($_GET['moduleName'] == 'mdRental') {
						foreach($_GET[choice] AS $val)
						{
							$user = $_GET[choice][$i];
							$Rows = $db->QueryFetch("SELECT reserveMobile FROM `mdRental__order` WHERE seq='".$val."'");
							if($Rows['reserveMobile'] != '' && strlen($Rows['reserveMobile']) >= 9)
							{
								echo str_replace("-","",$Rows[reserveMobile])."\n";
								$total ++;
							}
							if($total >= $socket[msg]) break;
						}
					} else {
						#회원리스트에서 발송시
						foreach($_GET[choice] AS $val)
						{
							$user = $_GET[choice][$i];
							$Rows = $db->QueryFetch("SELECT mobile,level FROM `mdMember__account` AS MEM1, `mdMember__info` AS MEM2 WHERE (MEM1.id=MEM2.id) AND MEM1.id='".$val."'");
							if($Rows[mobile] != '' && strlen($Rows[mobile]) >= 9 && $Rows[level] > '0')
							{
								echo str_replace("-","",$Rows[mobile])."\n";
								$total ++;
							}
							if($total >= $socket[msg]) break;
						}
					}
				}
				?></textarea>
				<div class="small_red center pd3">한줄에 한명(번호만)씩 입력하세요</div>
		</div>
		<!-- 수신자 목록 : end -->

		<!-- 그룹선택 : start -->
		<div class="pd3">
				<p class="pd5">회원 등급별 발송&nbsp;<select name="group" class="blue" onchange="$.dialog('../modules/mdSms/manage/input.php', '&amp;type=<?=$sess->encode('smsInput')?>&amp;grplevel='+this.value,500,360)" style="width:180px;">
				<option value="">-------등급선택-------</option>
				<?php
				$db->Query("SELECT * FROM `mdMember__level` WHERE level BETWEEN '2' AND '98' AND LENGTH(position)>'0' ORDER BY level ASC");
				while($Rows=$db->Fetch())
				{
					echo '<option value="'.$Rows[level].'"';
					if($Rows[level] == $_GET[grplevel]) echo 'selected="selected"';
					echo '>Lv.'.$Rows[level].' ('.$Rows[position].')</option>';
				}
				?>
				</select></p>
			<p class="pd5 center" style="line-height:18px;"><u>수신거부</u> 및 <u>잘못된 번호</u>를 제외한<br />총 <strong><?=number_format($all)?>명</strong>중 <strong class="colorRed"><?=number_format($total)?>명</strong>에게 발송합니다.</p>
		</div>
		<!-- 그룹선택 : end -->

	</div>
	<div style="float:left;width:200px;overflow:hidden">

		<!-- 문자작성 : start -->
		<div class="cube"><div class="line center">
			<div class="menu_gray"><p style="padding:4px 0;"><strong style="color:#ff3300"><?php echo(number_format($socket[msg]));?>건</strong> 발송가능</p></div>
			<div class="pd3">
				<span style="position:relative;left:24px;"><img src="/common/image/sms/icon_ant.gif" width="23" height="9" /></span>
				<span style="position:relative;left:58px;" class="small_gray"><a href="javascript:;" onclick="new_window('../modules/mdSms/manage/mesg.php','msgBox',350,360,'no','yes');"><img src="/common/image/button/btn_sms_msgbox.gif" width="74" height="15" /></a></span>
			</div>
			<textarea name="msg01" rows="2" cols="20" id="tbMessage" onKeyUp="byteCheck(this,'msgBytesPop',80);" onfocus="byteCheck(this,'msgBytesPop',80);" style="color:black; background-color:#ffffcc; border:1px solid #999; width:125px; height:80px; padding:5px; word-break:break-all; ime-mode:active" title="전송 메세지를 입력해 주세요" class="required" req="required" maxlength="40"><?php echo(urldecode($_GET['msg']));?></textarea>
			<div class="pd3 center">
				<span id="msgBytesPop" class="red">0</span><span>/80byte</span>&nbsp;&nbsp;&nbsp;<span><input type="checkbox" id="savemsg" name="savemsg" value="Y" class="input_check" tabindex="3" /><label for="savemsg" class="small_gray">메세지저장</label></span>
			</div>
		</div></div>
		<!-- 문자작성 : end -->

		<!-- 예약발송 : start -->
		<div class="cube"><div id="service" class="line center">
			<p class="pd10"><input type="radio" id="rdo1" name="rdoSend" value="1" onclick="$.sendNow(1);" checked="checked" /><label for="rdo1">즉시발송</label>&nbsp;<input type="radio" id="rdo2" name="rdoSend" value="2" onclick="$.sendNow(2);" /><label for="rdo2">예약발송</label></p>
			<p class="pd3">
				<input type="text" id="sdate" name="sdate" style="text-align:center;width:120px;" class="input_gray" readonly="true" disabled="disabled" value="<?=date("Y-m-d")?>" />
			</p>
			<p class="pd3">
				<select id="shour" name="shour" class="small_gray" disabled="disabled" style="width:60px;">
				<?php for($i=0;$i<24;$i++) {
					echo "<option value='$i'";
					if($i == date("H")) echo " style='color:red;' selected='selected'";
					echo ">".$i."시</option>";
				} ?></select> <select id='smin' name='smin' class='small_gray' disabled='disabled' style="width:60px;">
				<?php for($i=0;$i<60;$i++) {
					echo "<option value='$i'";
					if($i == date("i")) echo " style='color:red;' selected='selected'";
					echo ">".$i."분</option>";
				} ?></select>
			</p>
		</div></div>
		<div class="cube"><div class="line center"><p class="pd5">발신번호&nbsp;<input type="text" class="input_text center blue required" name="sender" value="<?=$sock->sender?>" style="width:120px;" req="required" phone="true" /></p></div></div>

		<p class="center" style="height:34px;padding-top:12px;"><span class="btnPack red medium strong"><button type="submit" onclick="return $.submit(this.form)">SMS 전송하기</button></span>&nbsp;<span><a href="javascript:;" onclick="$.dialogRemove()" class="btnPack gray medium "><span>취소</span></a></span></p>
		<!-- 예약발송 : end -->

	</div>
	<div class="clear"></div>

</form>
<?php
require_once __PATH__."/_Admin/include/commonScript.php";
?>
<script type="text/javascript">
//<[!CDATA[
	initCal({id:"sdate",type:"day",today:"y",icon:"n"});
//]]>
</script>
<script type="text/javascript">
//<[!CDATA[
$(document).ready(function()
{
	$("#ajax_display").css('background','#d2d2d2');
	$('#frmSms').validate({onkeyup:function(element){$(element).valid();}});
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
</script>