<?php
//if($_SESSION['ulevel'] == '1') $func->Err("시스템 관리자는 정보변경이 불가합니다.","history.back()");

//SSL설정
$posturl = ($cfg['site']['ssl']) ? 'https://'.$_SERVER['HTTP_HOST'].":".$cfg['site']['ssl'].$cfg['droot'].'index.php' : $_SERVER['PHP_SELF'];
if(isset($_SESSION['uid'])) {
	$Rows = $member->memberInfo($_SESSION['uid']);
} else {
	$func->err("시스템 관리자는 정보변경이 불가합니다.");
}

//페이지명 재설정
$cfg['cate']['name'] = "비밀번호 변경";
$display->title = "비밀번호 변경";

?>
<div id="regist_wrap">
<form id="regForm" name="login" method="post" action="<?php echo($posturl);?>" enctype="multipart/form-data">
<input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />
<input type="hidden" name="uri" value="<?php echo(__URI__);?>" />
<input type="hidden" name="type" value="<?php echo($sess->encode('passwdChangePost'));?>" />
<input type="hidden" name="memType" value="<?php echo($Rows['type']);?>" />
<?php
	//include __PATH__."modules/mdMember/modifyBase.php";
?>
<p class="right gray pd5">(<span class="colorRed" title="필수입력항목">*</span>)나 하늘색 입력항목은 필수 입력항목입니다.</p>
<table class="table_basic" summary="비밀번호 변경을 위한 입력 항목" style="width:100%;">
<caption>비밀번호 변경 항목</caption>
<col width="140" />
<col />
	<?php
	$form = new Form('table');

	$form->addStart('현재 비밀번호', 'oldpasswd', 1, 0 ,'M');
	$form->add('input', 'oldpasswd', null, 'width:120px;');
	$form->addHtml('<li class="opt"><span class="small_gray">사용중인 비밀번호</span></li>');
	$form->addEnd(1);

	$form->addStart('비밀번호 변경', 'passwd', 1, 0 ,'M');
	$form->add('input', 'passwd', null, 'width:120px;', 'onblur="$.checkOverLap(\''.$sess->encode("checkUserPwd").'\',\'Pwd\');"');
	$form->addHtml('<li class="opt"><span id="checkPwd" class="small_gray">변경시에만 입력하세요.</span></li>');
	$form->addEnd(1);

	$form->addStart('비밀번호 확인', 'repasswd', 1, 0 ,'M');
	$form->add('input', 'repasswd', null, 'width:120px;', 'match="passwd"');
	$form->addHtml('<li class="opt"><span class="small_gray">변경할 비밀번호와 동일하게 입력하세요.</span></li>');
	$form->addEnd(1);
	?>
	</table>
	<div class="pd5 center"><span class="btnPack black large strong"><button type="submit" onclick="return $.submit(this.form)">비밀번호 변경</button></span></div>
	</form>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$('#regForm').validate({onkeyup:function(element){$(element).valid();}});
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
//]]>
</script>