<?php
require_once "../../../_config.php";
require_once __PATH__."/_Admin/include/commonHeader.php";

if($_POST['type'] == "smsTempPost") 
{
	$msg01	= stripslashes(trim($_POST['msg01']));
	$msg02	= stripslashes(trim($_POST['msg02']));
	$msg03	= stripslashes(trim($_POST['msg03']));
	$msg04	= stripslashes(trim($_POST['msg04']));
	$msg05	= stripslashes(trim($_POST['msg05']));

	$db->query(" REPLACE INTO `mdSms__` (mode,temp01,temp02,temp03,temp04,temp05) VALUES ('".$_POST['mode']."','".$msg01."','".$msg02."','".$msg03."','".$msg04."','".$msg05."') ");
	if($db->getAffectedRows() < 1)
	{
		$func->err("적용실패입니다! 시스템 담당자에게 문의하십시오");
	} else
	{
		$func->err("정상적으로 적용 되었습니다!");
	}
}
else {
	$temps			= array('mdMember','mdDocument','mdApp01','mdApp02','mdPayment');
	$tempsTitle	= array('회원·고객','문의·게시물','문의·상담','상담·예약','주문·결제');
	$tempsHead	= array(
									'mdMember'=>array('회원(비밀번호찾기)','회원(신청시)','회원(완료시)','생일축하'),
									'mdDocument'=>array('운영자(접수시)','회원(신청시)','회원(완료시)','임시저장'),
									'mdApp01'=>array('운영자(접수시)','회원(신청시)','회원(완료시)','임시저장'),
									'mdApp02'=>array('운영자(접수시)','회원(신청시)','회원(완료시)','임시저장'),
									'mdOrder'=>array('운영자(접수시)','회원(신청시)','회원(완료시)','임시저장'),
									'mdPayment'=>array('운영자(접수시)','회원(신청시)','회원(발송완료시)','임시저장')
								);
}
?>
<div class="table"><div class="line">
	<fieldset id="help">
	<ul>
		<li>치환될 변수는 반드시 대괄호 { }로 감싸주셔야하며, <u>절대 등록된 변수의 순서를 변경하지 마시기 바랍니다.</u></li>
		<li>직접 메세지를 변경할 경우 { }의 치환변수를 제외한 문자만 수정하시기 바랍니다.</li>
		<li>최대 문자는 80Byte이므로 치환될 변수의 글자까지 염두하셔야 합니다. (한글1자 = 2Byte, 영문/숫자1자 = 1Byte)</li>
	</ul>
	</fieldset>
</div></div>
<br />

<?php
foreach($temps AS $key=>$val)
{
	if($func->checkModule($val))
	{
		$Rows = $db->queryFetch("SELECT * FROM `mdSms__` WHERE mode='".$val."'");
		$Rows['temp01'] = str_replace('{게시판}', '{카테고리}', $Rows['temp01']);
		$Rows['temp02'] = str_replace('{게시판}', '{카테고리}', $Rows['temp02']);

		echo('<div style="clear:both; margin-bottom:5px">
		<form name="frmMsg" method="post" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data" target="hdFrame">
		<input type="hidden" name="type" value="smsTempPost" />
		<input type="hidden" name="mode" value="'.$val.'" />

		<!-- 운영자 : start --><div style="border:3px #d2d2d2 solid; background:#d2d2d2; text-align:center; float:left; width:170px"><div class="line center" style="background:#fff">
		<div class="menu_gray" style="margin-bottom:2px"><p style="padding:4px 0;" class="colorBlue">'.$tempsHead[$val][0].'</p></div>
		<textarea name="msg01" rows="2" cols="20" onkeyup="byteCheck(this,\'msgBytes'.$key.'1\',80);" onfocus="byteCheck(this,\'msgBytes'.$key.'1\',80);" style="color:black; background-color:#ffffcc; border:1px solid #999; width:125px; height:80px; padding:5px; word-break:break-all; ime-mode:active" title="회원 전송 메세지">'.$Rows['temp01'].'</textarea>
		<div class="pd3 center"><span id="msgBytes'.$key.'1" class="colorRed">0</span> / 80Byte	</div>
		</div></div><!-- 운영자 : end -->

		<!-- 신청시 : start --><div style="border:3px #d2d2d2 solid; background:#d2d2d2; text-align:center; float:left; width:170px"><div class="line center" style="background:#fff">
		<div class="menu_gray" style="margin-bottom:2px"><p style="padding:4px 0;" class="colorBlue">'.$tempsHead[$val][1].'</p></div>
		<textarea name="msg02" rows="2" cols="20" onkeyup="byteCheck(this,\'msgBytes'.$key.'2\',80);" onfocus="byteCheck(this,\'msgBytes'.$key.'2\',80);" style="color:black; background-color:#ffffcc; border:1px solid #999; width:125px; height:80px; padding:5px; word-break:break-all; ime-mode:active" title="운영자 전송 메세지">'.$Rows['temp02'].'</textarea>
		<div class="pd3 center"><span id="msgBytes'.$key.'2" class="colorRed">0</span> / 80Byte</div>
		</div></div><!-- 신청시 : end -->

		<!-- 완료시 : start --><div style="border:3px #d2d2d2 solid; background:#d2d2d2; text-align:center; float:left; width:170px"><div class="line center" style="background:#fff">
		<div class="menu_gray" style="margin-bottom:2px"><p style="padding:4px 0;" class="colorBlue">'.$tempsHead[$val][2].'</p></div>
		<textarea name="msg03" rows="2" cols="20" onkeyup="byteCheck(this,\'msgBytes'.$key.'3\',80);" onfocus="byteCheck(this,\'msgBytes'.$key.'3\',80);" style="color:black; background-color:#ffffcc; border:1px solid #999; width:125px; height:80px; padding:5px; word-break:break-all; ime-mode:active" title="운영자 전송 메세지">'.$Rows['temp03'].'</textarea>
		<div class="pd3 center"><span id="msgBytes'.$key.'3" class="colorRed">0</span> / 80Byte</div>
		</div></div><!-- 완료시 : end -->

		<!-- 기타 : start --><div style="border:3px #d2d2d2 solid; background:#d2d2d2; text-align:center; float:left; width:170px"><div class="line center" style="background:#fff">
		<div class="menu_gray" style="margin-bottom:2px"><p style="padding:4px 0;" class="colorBlue">'.$tempsHead[$val][3].'</p></div>
		<textarea name="msg04" rows="2" cols="20" onkeyup="byteCheck(this,\'msgBytes'.$key.'4\',80);" onfocus="byteCheck(this,\'msgBytes'.$key.'4\',80);" style="color:black; background-color:#ffffcc; border:1px solid #999; width:125px; height:80px; padding:5px; word-break:break-all; ime-mode:active" title="운영자 전송 메세지">'.$Rows['temp04'].'</textarea>
		<div class="pd3 center"><span id="msgBytes'.$key.'4" class="colorRed">0</span> / 80Byte</div>
		</div></div><!-- 기타 : end -->

		<div style="float:left; padding:5px">
		<p class="pd3"><span><img src="/common/image/icon/icon_aw_dw.gif" width="10" height="10" /></span>&nbsp;<strong><span style="color:#ff3300">'.$tempsTitle[$key].'</span></strong> 이용시 사용자 또는 운영자에게 보내지는 메세지 입니다</p>
		<p class="pd3">각 모듈별 환경설정에서 발송여부를 확인하세요.</p>
		<div class="code">$sock->tempMode : <strong>'.$val.'</strong></div>
		<p class="pd3"><span class="btnPack medium blue"><button type="submit">저장하기</button></span></p>
		</form>
		</div>
		</div>');
	}
}
require_once __PATH__."/_Admin/include/commonScript.php";
?>