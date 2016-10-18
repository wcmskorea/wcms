<?php
/* --------------------------------------------------------------------------------------
| Ajax 로그인창
|----------------------------------------------------------------------------------------
| Lastest : 이성준 ( 2009년 6월 16일 화요일 )
*/
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
#--- SSL설정
$posturl = ($cfg['site']['ssl']) ? 'https://'.$_SERVER['HTTP_HOST'].":".$cfg['site']['ssl'].$cfg['droot'].'index.php' : $_SERVER['PHP_SELF'];
?>
<form id="loginForm" name="login" method="post" action="<?php echo($posturl);?>" enctype="multipart/form-data" onsubmit="return validCheck(this);">
<input type="hidden" name="type" value="<?php echo($sess->encode('loginAccess'));?>" />
<input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />
<input type="hidden" name="uri" value="<?php echo($_SERVER['HTTP_REFERER']);?>" />
<input type="hidden" name="loginType" value="web" />

<div class="menu_black"><p title="드래그하여 좌우이동이 가능합니다"><strong>로그인 (Sign-In)</strong></p></div>
<div class="cube"><div class="line bg_white" style="height:120px;">
  <div style="padding:10px 0 0 20px;">
    <div class="loginBox">
      <fieldset>
      <legend class="hide">로그인</legend>
      <?php
      /* 로그인 타입별 출력 */
      switch($cfg['module']['auth'])
      {
        case 'P': case 'C': default:
      ?>
        <div id="form">
          <label for="uid">아　이　디</label> <input type="text" id="uid" name="uid" class="input_blue" required="required" opt="userid" style="width:120px; ime-mode:disabled" title="<?php echo($lang['member']['id']);?>" value="<?php echo($_COOKIE['USERID']);?>" accesskey="L" />
          <input type="checkbox" id="autoid" name="autoid" title="<?php echo($lang['login']['saveId']);?>" class="input_check" value="Y" tabindex="3" <?php if($_COOKIE['USERID']){echo('checked="checked"');}?> />&nbsp;<label for="autoid"><?php echo($lang['login']['saveId']);?></label>
        </div>
      <?php
        break;
        case 'E':
      ?>
        <div id="form">
          <input type="text" id="uid" name="uid" class="input_blue" style="width:120px" title="<?php echo($lang['member']['email']);?>" value="<?php echo($_COOKIE['USERID']);?>" accesskey="L" />
          <input type="checkbox" id="autoid" name="autoid" title="<?php echo($lang['login']['saveId']);?>" class="input_check" value="Y" tabindex="3" <?php if($_COOKIE['USERID']){echo('checked="checked"');}?> />&nbsp;<label for="autoid"><?php echo($lang['login']['saveId']);?></label>
        </div>
      <?php
        break;
        case 'B':
      ?>
        <div><span><input type="radio" id="memType1" name="memType" value="P" checked="checked" /><label for="memType1">개인회원</label></span>&nbsp;<span><input type="radio" id="memType2" name="memType" value="C" /><label for="memType2">기업회원</label></span></div>
        <div id="form">
          <input type="text" id="uid" name="uid" class="input_blue" style="width:120px" title="<?php echo($lang['member']['id']);?>" value="<?php echo($_COOKIE['USERID']);?>" accesskey="L" />
          <input type="checkbox" id="autoid" name="autoid" title="<?php echo($lang['login']['saveId']);?>" class="input_check" value="Y" tabindex="3" <?php if($_COOKIE['USERID']){echo('checked="checked"');}?> />&nbsp;<label for="autoid"><?php echo($lang['login']['saveId']);?></label>
        </div>
      <?php
        break;
      }
      ?>
      <div style="margin-top:3px;">
        <label for="upw">비 밀 번 호</label> <input type="password" id="upw" name="upw" class="input_blue" style="width:120px" required="required" trim="trim" title="<?php echo($lang['member']['pwd']);?>" accesskey="P" />
        <span class="btnPack black small"><button type="submit"><?php echo($lang['member']['login']);?></button></span>
      </div>
      </fieldset>
      <div class="clear"></div>
    </div>
  </div>
  <div style="margin-top:10px; padding-left:10px;">
    <ul>
      <?php
      if($cfg['site']['ssl']) { echo('<li class="colorRed" style="height:16px">※&nbsp;SSL(128Bit) 암호화 전송</li>'); }
      ?>
      <li class="darkgray" style="height:16px"><img src="<?php echo($cfg['droot']);?>common/image/icon/icon_aw_dw_ani.gif" width="10" height="10" alt="<?php echo($lang['member']['regist']);?>" />&nbsp;<a href="./index.php?cate=000002002"><strong><?php echo($lang['member']['regist']);?></strong> 바로가기 <?php if($func->checkModule('mdMileage') && $cfg['mileage']['mileageReg'] > 0) { echo('<span>(<strong class="colorRed">'.number_format($cfg['mileage']['mileageReg']).'포인트</strong> 적립!)</span>'); } ?></a></li>
      <li class="darkgray" style="height:16px"><img src="<?php echo($cfg['droot']);?>common/image/icon/icon_aw_dw.gif" width="10" height="10" alt="<?php echo($lang['member']['find']);?>" />&nbsp;<a href="<?php echo($cfg['droot']);?>index.php?cate=000002003"><?php echo($lang['member']['find']);?> 바로가기</a></li>
    </ul>
  </div>
</div></div>
</form>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function()
{
	$("input[type=text],input[type=password],textarea").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active");});
	$("#ajax_display").css('background','#fff');
	setTimeout ("$('#uid').select()", 500);
});
//]]>
</script>
