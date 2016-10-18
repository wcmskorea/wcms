<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
/**
 * 입력 옵션 설정병합
 */
$cfg['module'] = @array_merge($cfg['module'], (array)unserialize($cfg['module']['contentAdd']));

/* 입력항목 옵션 체크 */
$division	= explode(",", $cfg['module']['division']);
$result		= explode(",", $cfg['module']['result']);

if($_POST['shName'] && $_POST['shCode'])
{
	$_POST['shCode'] = str_replace("-",null,$_POST['shCode']);
	$query	= "SELECT * FROM `mdApp01__content` WHERE cate='".__CATE__."' AND name='".$_POST['shName']."' AND SUBSTRING(idCode,7,13)='".$_POST['shCode']."'";
	$Rows	= $db->queryFetch($query);
	if($db->getNumRows() < 1) { $func->err("입력한 정보와 일치하는 정보가 없습니다.\\n다시 확인 후 재시도해 보시기바랍니다.", "window.history.back();"); }

}
?>
<div id="mdApp01InputSearch" class="docInput">

<form id="bbsform" name="bbsform" method="post" action="<?php echo($_SERVER['PHP_SELF']);?>" enctype="multipart/form-data">
<input type="hidden" name="type" value="<?php echo($sess->encode('inputSearchPost'));?>" />
<input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />
<input type="hidden" name="menu" value="<?php echo($menu);?>" />
<input type="hidden" name="sub" value="<?php echo($sub);?>" />

	<div id="mdApp01InputSearchForm">
		<?php
		if($_POST['shName'] && $_POST['shCode'])
		{
			echo('<div class="result"><p style="padding:5px 0"><strong>'.$Rows['name'].'</strong> ('.substr($Rows['idcode'],0,6).'-*******)님은 현재 <strong class="colorRed">[ '.$result[$Rows['state']].' ]</strong> 상태 입니다.</p>
			<p style="padding:5px 0">'.$Rows['contentAnswers'].'</p></div>');
			
		} else {
		?><div class="result">
		<table class="table_basic" summary="회원가입을 위한 개인정보 입력 항목" style="width:360px">
		<caption>신청검색 입력항목</caption>
		<col width="150">
		<col>
			<tbody>
				<tr>
					<th scope="row"><label for="shName" class="required"><span class="colorRed" title="필수입력항목">*</span><strong>접수자명</strong></label></th>
					<td><ol><li class="opt"><input type="text" id="shName" name="shName" class="input_blue center required korean" style="width:120px;" value="" /></li></ol></td>
				</tr>
				<tr>
					<th scope="row"><label for="shPhone" class="required"><span class="colorRed" title="필수입력항목">*</span><strong>주민번호(뒤 7자리)</strong></label></th>
					<td><ol><li class="opt"><input type="text" id="shCode" name="shCode" class="input_blue center required" style="width:120px;" value="" /></li>
					<li class="opt"><span class="btnPack red small strong"><button type="submit" onclick="return submitForm(this.form);">조회하기</button></span></li></ol></td>
				</tr>
			</tbody>
		</table></div>
		<?php
		}
		?>
	</div>
</form>
</div>
<script language="javascript" type="text/javascript">
function submitForm(frm)
{
	$(frm).validate({
		submitHandler: function(frm) {
			frm.submit();
		},
		onkeyup:false,
		onclick:false,
		onfocusout:false,
		showErrors: function(errorMap, errorList) {
			if (errorList && errorList[0]) {
			  alert(errorList[0].message);
			}
		}
	});
}
</script>
<div style="width:605px; margin:auto">
<?php
//운영자 권한을 소유한 회원만 등록이 가능함
if($_SESSION['ulevel'] && $_SESSION['ulevel'] <= $cfg['operator'])
{
	include __PATH__."modules/".$cfg['cate']['mode']."/inputForm.php";
}
?>
</div>