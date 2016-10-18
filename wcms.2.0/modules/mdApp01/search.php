<?php
/* --------------------------------------------------------------------------------------
| 게시물 검색 입력창
|----------------------------------------------------------------------------------------
| Lastest : 이성준 ( 2009년 6월 16일 화요일 )
*/
if(!defined('__CSS__')) header("HTTP/1.0 404 Not found");
?>
<div id="modal">
	<form name="frmBoard" method="post" action="<?php echo($cfg['droot']);?>index.php?mode=<?php echo($_GET['moded']);?>" enctype="multipart/form-data" onsubmit="return checkSubmit(this)">
		<input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />

		<div class="menu_black"><p title="드래그하여 좌우이동이 가능합니다">신청내역 변경 및 열람하기</p></div>

		<table class="table_basic" summary="회원가입을 위한 개인정보 입력 항목" style="width:100%;">
		<caption>신청내용 입력항목</caption>
		<col width="100">
		<col>
			<tbody>
				<tr>
					<th scope="row"><label for="shName" class="required"><span class="colorRed" title="필수입력항목">*</span><strong>신청자명</strong></label></th>
					<td><ol><li class="opt"><input type="text" id="shName" name="shName" title="신청자명" class="input_blue center" style="width:250px;" value="" /></li></ol>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sh1" class="required"><span class="colorRed" title="필수입력항목">*</span><strong>검색구분</strong></label></th>
					<td><ol><li class="opt"><span class="keeping"><input type="radio" id="sh1" name="sh" value="mobile" checked="checked" /><label for="sh1">휴대전화번호</label></span></li>
					<li class="opt"><span class="keeping"><input type="radio" id="sh2" name="sh" value="phone" /><label for="sh2">전화번호</label></span></li>
					<li class="opt"><span class="keeping"><input type="radio" id="sh3" name="sh" value="email" /><label for="sh3">E-mail</label></span></li>
					<!--<li class="opt"><span class="keeping"><input type="radio" id="sh4" name="sh" value="idcode" /><label for="sh4">주민번호</label></span></li>-->
					</ol></td>
				</tr>
				<tr>
					<th scope="row"><label for="shKeyword" class="required"><span class="colorRed" title="필수입력항목">*</span><strong>검 색 어</strong></label></th>
					<td><ol><li class="opt"><input type="text" id="shKeyword" name="shKeyword" title="신청자명" class="input_blue center" style="width:250px;" value="" /></li></ol>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="center pd10"><span class="btnPack black medium strong"><button type="submit" class="red">검색하기</button></span></div>
	</form>
</div>
<script type="text/javascript">
//<![CDATA[
function checkSubmit(frm)
{
	if(frm.shName.value == '') {
		alert("신청자명을 입력해주십시오");
		frm.shName.select();
		return false;
	} else if(frm.shSubject.value == '') {
		alert("선택 입력항목을 입력해주십시오");
		frm.shSubject.select();
		return false;
	} else {
		return true;
	}
}
$(document).ready(function()
{
	$("input[type=text],input[type=password],textarea").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active");});
	$("#ajax_display").css('background','#fff');
  	setTimeout ("$('#shName').select()", 500);
});
//]]>
</script>
