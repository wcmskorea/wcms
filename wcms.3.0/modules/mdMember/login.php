<?php
/* ---------------------------------------
| 언어가 한국어가 아닐경우 email로 승인
*/
if($cfg[site][lang] != 'kr') $cfg[content][auth] = "email";
#--- SSL설정
$posturl = ($cfg[site][ssl]) ? 'https://'.$_SERVER[HTTP_HOST].":".$cfg[site][ssl].$cfg[droot].'index.php' : $_SERVER[PHP_SELF];
?>
<div id="login_wrap">
<div id="login_container">
    <form id="loginForm" name="login" method="post" action="<?php echo($posturl);?>" enctype="multipart/form-data" onsubmit="return validCheck(this);">
	<input type="hidden" name="type" value="<?php echo($sess->encode('loginAccess'));?>" />
    <input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />
    <input type="hidden" name="uri" value="<?php echo(__URI__);?>" />
    <input type="hidden" name="loginType" value="web" />
	
	<div class="roundBox"><div class="box_guide"><span class="box_tl"></span><span class="box_tr"></span></div>
	<div class="box_contents">

	<div class="visual" style="float:left;"><?php
    /*$display->displayPos = "Login";
    $display->title = "login_visual";
    $display->listing = "gif";
    $display->width = 235;
    $display->height = 100;
    echo($display->printSkin());*/
    ?>
		<div id="login_visual" class="design"><img src="/user/default/image/login_visual.gif" alt="" usemap="#login_visual" onfocus="this.blur();" /></div>
		</div>
	<div class="loginBox">
	<fieldset>
      <legend class="hide">로그인</legend>
        <!--<div style="margin-top:2px;">
          <p style="padding-left:95px;"><span class="keeping"><input type="radio" id="loginType1" name="loginType" value="web" class="input_check" checked="checked" onfocus="changeLoginForm()" /><label for="loginType1">웹회원</label>&nbsp;<input type="radio" id="loginType2" name="loginType" value="loan" class="input_check" onfocus="changeLoginForm()" /><label for="loginType2" class="first">대출회원</label></span></p>
        </div>-->
		<div style="margin-top:5px;">
        	<ul><li style="float:left;width:90px; padding:3px 3px 0 0;"><p class="right"><label id="uid_label" for="uid">아　이　디</label></p></li>
            <li style="float:left;"><input type="text" id="uid" name="uid" req="required" opt="userid" class="input_blue" style="width:120px" title="<?php echo($lang['member']['id']);?>" value="<?php echo($_COOKIE[USERID]);?>" accesskey="L" />&nbsp;<input type="checkbox" id="autoid" name="autoid" title="<?php echo($lang['login']['saveId']);?>" class="input_check" value="Y"<?php if($_COOKIE[USERID]){ print(' checked="checked"'); }?> /><label for="autoid"><?php echo($lang['login']['saveId']);?></label></li>
			</ul>
			<div class="clear"></div>
		</div>
        <div style="margin-top:3px;">
			<ul><li style="float:left;width:90px; padding:3px 3px 0 0;"><p class="right"><label id="upw_label" for="upw">비 밀 번 호</label></p></li>
			<li style="float:left;"><input type="password" id="upw" name="upw" req="required" trim="trim" class="input_blue" style="width:120px" title="<?php echo($lang['member']['pwd']);?>" accesskey="P" />&nbsp;<span class="button small bblack"><button type="submit"><?php echo($lang['member']['login']);?></button></span></li>
			</ul>
		<div class="clear"></div>
		</div>
	</fieldset>
		<div style="margin-left:95px;">
			<ul>
			<li><img src="/user/default/image/icon/icon_aw_dw.gif" width="10" height="10" alt="<?php echo($lang['member']['regist']);?>" />&nbsp;<a href="./index.php?cate=000002002"><?php echo($lang['member']['regist']);?></a></li>
			<li><img src="/user/default/image/icon/icon_aw_dw.gif" width="10" height="10" alt="<?php echo($lang['member']['find']);?>" />&nbsp;<a href="./index.php?cate=000002003"><?php echo($lang['member']['find']);?></a></li>
			</ul>
		</div>
	</div>
	<div class="clear"></div>

	</div>
	<div class="box_guide"><span class="box_bl"></span><span class="box_br"></span></div></div>
	</form>
	<!--<div class="pd3 center"><?php echo($lang[login_msg1]);?></div>
	<div class="pd3 center"><?php echo($lang[login_msg2]);?></div>-->
</div>
</div><!-- login_wrap -->
<script type="text/javascript">
//<![CDATA[
function changeLoginForm()
{
    if(document.getElementById('loginType2').checked == true)
    {
      	document.getElementById('uid_label').innerHTML = '대 출 자 ID';
      	document.getElementById('upw_label').innerHTML = '주민번호뒷자리';
      	document.getElementById('loginForm').action = '<?php echo($cfg[droot]);?>index.php';
    } else {
		document.getElementById('uid_label').innerHTML = '아　이　디';
		document.getElementById('upw_label').innerHTML = '비 밀 번 호';
    }
}
//]]>
</script>
