<?php
if(!defined('__CSS__')) header("HTTP/1.0 404 Not found");
/* ---------------------------------------
| 언어가 한국어가 아닐경우 email로 승인
*/
//if($cfg['lang'] != 'kr') $cfg['content']['login'] = "email";
if($cfg['lang'] != 'kr') $func->Err("Sorry!", "history.back()");
?>
<div id="find_wrap">
	<div class="find_container" style="width:670px;">

		<div class="cell" style="padding-right:20px;">
			<form name="reg" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data" onsubmit="return $.checkFarm(this,'./modules/mdMember/findPassEmail.php','30');">
			<input type="hidden" name="cate" value="<?=__CATE__?>" />
			<input type="hidden" name="type" value="<?=$sess->encode('findUserAuth')?>" />
			<div class="roundBox"><div class="box_guide"><span class="box_tl"></span><span class="box_tr"></span></div>
			<div class="box_contents">
				<ul>
					<li><p class="opt"><?=$lang['member_name']?> : <br /><input type="text" name="name" class="input_gray" style="width:190px" req="required" opt="korean" title="<?=$lang['member_name']?>" value="" /></p></li>
					<li><p class="opt"><?=$lang['member_email']?> : <br /><input type="text" name="email" class="input_gray" style="width:190px" req="required" opt="email" title="<?=$lang['member_email']?>" /></p></li>
					<li class="btn"><span class="button bblack strong"><button type="submit" class="red"><?=$lang['member_find_pw']?></button></span></li>
				</ul>
			<div class="clear"></div>
			<br />
			<div class="cube"><div class="line small_gray" style="line-height:140%; background:#fff;">
				<img src="/image/icon/icon_title02.gif" width="7" height="9" alt="아이콘" /> <span class="bold">비밀번호를 잊어버리셨나요? </span>
				<br />회원등록시 사용하였던 아이디와 E-mail을 입력하시어 인증코드를 E-mail로 받으실 수 있습니다. 등록하셨던 E-mail이 확인 불가할 경우 별도문의 바랍니다.
			</div></div>
			</div><div class="box_guide"><span class="box_bl"></span><span class="box_br"></span></div></div>
			</form>
		</div>

		<div class="cell">
			<form name="reg" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data" onsubmit="return $.checkFarm(this,'./modules/mdMember/findPassEmail.php');">
			<input type="hidden" name="cate" value="<?=__CATE__?>" />
			<input type="hidden" name="type" value="<?=$sess->encode('changeUserPw')?>" />
			<div class="roundBox"><div class="box_guide"><span class="box_tl"></span><span class="box_tr"></span></div>
			<div class="box_contents">
				<ul>
					<li><p class="opt"><?=$lang['member_email']?> : <br /><input type="text" name="email" class="input_gray" style="width:190px" req="required" opt="email" title="<?=$lang['member_email']?>" /></p></li>
					<li><p class="opt"><?=$lang['member_authcode']?> : <br /><input type="text" name="author" class="input_gray" style="width:190px" req="required" trim="trim" title="<?=$lang['member_authcode']?>" value="" /></p></li>
					<li><p class="opt"><?=$lang['member_change_pw']?> : <br /><input type="text" name="changePass" class="input_gray" style="width:190px" req="required" trim="trim" title="<?=$lang['member_change_pw']?>" value="" /></p></li>
					<li class="btn"><span class="button bblack strong"><button type="submit" class="red"><?=$lang['member_change_pw']?></button></span></li>
				</ul>
			<div class="clear"></div>
			<br />
			<div class="cube"><div class="line small_gray" style="line-height:200%; background:#fff;">
				<img src="/image/icon/icon_title02.gif" width="7" height="9" alt="아이콘" /> <span class="bold">이메일로 받은 인증코드를 입력하여 변경하세요</span>
			</div></div>
			</div><div class="box_guide"><span class="box_bl"></span><span class="box_br"></span></div></div>
			<div class="clear"></div>
			</form>
		</div>

	</div>
</div>
