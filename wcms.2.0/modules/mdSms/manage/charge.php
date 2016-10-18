<?php
require_once "../../../_config.php";
require_once __PATH__."/_Admin/include/commonHeader.php";
?>

<h2><span class="arrow">▶</span>문자·SMS 관리&nbsp;&nbsp;<span class="normal">&gt;&nbsp;문자충전 하기</span></h2>

<div class="table"><div class="line">
	<fieldset id="help">
	<ul>
		<li>하기 금액은 VAT별도가 입니다.</li>
		<li>무통장 입금을 선택하실 경우 입금 후 연락을 주셔야 합니다.</li>
	</ul>
	</fieldset>
</div></div>

<script type="text/javascript">
<!--
$.payment = function(frm)
{
	if($("#total").val() < 1000)
  {
		alert('신청항목을 확인하십시오. 결제금액이 잘못되었습니다.');
		return false;
	} else
	{
		if(confirm("총 결제비용은 [" + $.setComma($("#total").val()) + "원]입니다. 결제하시겠습니까?") == true)
		{
			//window.open("","dacomPay","width=330, height=430, status=yes, scrollbars=no,resizable=no, menubar=no");
			new_window("","dacomPay",330,430,"no","no");
			frm.target = "dacomPay";
			return true
		} else
    {
			return false
		}
	}
};

$.smsPrice = function(va) {
	var arr = va.split('|');
	$("#total").val(arr[2]);
};

$.checkPayType = function(type)
{
	switch(type)
	{
		case 4 :
			$('#tr_idcode').css("display","none");
			$('#idcode').attr("disabled","disabled");
		break;
		case 5 :
			$('#tr_idcode').css("display","none");
			$('#idcode').attr("disabled","disabled");
		break;
		case 6 :
			$('#tr_idcode').css("display","block");
			$('#idcode').removeAttr('disabled');
		break;
	}
	return false;
};
//-->
</script>
<div class="pd3"></div>
<form name="frm02" method="post" action="http://www.aceoa.com/account/payMent.asp" enctype="multipart/form-data" onsubmit="return $.payment(this);">
<input type="hidden" name="svc" value="2" />
<input type="hidden" name="userid" value="<?=$cfg[site][id]?>" />
<input type="hidden" name="username" value="<?=$cfg[site][siteName]?>" />
<input type="hidden" name="useremail" value="<?=$cfg[site][email]?>" />
<input type="hidden" name="usermobile" value="<?=$cfg[site][mobile]?>" />
<input type="hidden" id="total" name="total" value="0" />

<div class="table">
<div class="line bg_white">

<table class="table_list" style="width:100%">
<caption>사이트 정보 설정</caption>
<col width="110">
<col>
<tr>
	<th>ㆍ수량선택</th>
	<td style="line-height:20px;">
		<input type="radio" id="opt1" name="opt[]" value="1147|500|11000" onfocus="$.smsPrice(this.value);" req="required" mincheck="1" title="신청수량" /> <label for="opt1"><strong>500</strong>건 (1건-20원) 10,000원</label><br />
		<input type="radio" id="opt2" name="opt[]" value="1148|1000|20900" onfocus="$.smsPrice(this.value);" /> <label for="opt2"><strong>1,000</strong>건 (1건-19원) 19,000원</label><br />
		<input type="radio" id="opt3" name="opt[]" value="1149|3000|59400" onfocus="$.smsPrice(this.value);" /> <label for="opt3"><strong>3,000</strong>건 (1건-18원) 54,000원</label><br />
		<input type="radio" id="opt4" name="opt[]" value="1150|5000|93500" onfocus="$.smsPrice(this.value);" /> <label for="opt4"><strong>5,000</strong>건 (1건-17원) 85,000원</label><br />
		<input type="radio" id="opt5" name="opt[]" value="1151|10000|176000" onfocus="$.smsPrice(this.value);" /> <label for="opt5"><strong>10,000</strong>건 (1건-16원) 160,000원</label><br />
		<input type="radio" id="opt6" name="opt[]" value="1152|30000|495000" onfocus="$.smsPrice(this.value);" /> <label for="opt6"><strong>30,000</strong>건 (1건-15원) 450,000원</label><br />
	</td>
</tr>
<tr>
	<th>ㆍ결제방법</th>
	<td><p><input name="ptype" type="radio" id="mode5" class="input_radio" value="5" onclick="$.checkPayType(5);" checked="checked" />&nbsp;<label for="mode5">카드 결제</label>&nbsp;
		<!--<input name="ptype" type="radio" id="mode6" class="input_radio" value="6" onclick="$.checkPayType(6);" /><label for="mode6">실시간 계좌이체</label>--></p>
	</td>
</tr>
<tr id="tr_idcode" style="display:none;">
	<th>ㆍ주민번호</th>
	<td><p><input id="idcode" class="input_text" type="text" name="idcode" style="width:100px;" title="주민번호" maxlength="13" disabled="disabled" /> ('-' 제외)</p></td>
</tr>
</table>
</div></div>

<div class="pd5 center"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit">문자충전 요청하기</button></span></div>
</form>
<?php
require_once __PATH__."/_Admin/include/commonScript.php";
?>