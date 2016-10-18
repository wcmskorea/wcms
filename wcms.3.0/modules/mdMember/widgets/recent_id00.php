<?php
global $sess, $lang;

$this->result .= '<div id="login_wrap02" style="width:'.$this->width.'px;height:'.$this->height.'px;">';
if(!isset($_SESSION[uid]))
{
	$this->result .= '<div class="login_box" style="padding-top:12px;">
		<form name="login" method="post" action="'.$this->cfg[droot].'index.php" enctype="multipart/form-data" onsubmit="return validCheck(this)">
		<input type="hidden" name="cate" value="000002001" />
		<input type="hidden" name="type" value="'.$sess->encode('loginAccess').'" />
		<input type="hidden" name="uri" value="'.__URI__.'" />
		<fieldset>
    <legend>회원로그인</legend>
      <p style="float:right;"><span class="keeping"><input type="radio" id="loginType1" name="loginType" value="web"';
      if($_COOKIE[USERID]) { $this->result .= 'checked="checked"'; }
      $this->result .= 'class="input_check" /><label for="loginType1">웹회원</label><input type="radio" id="loginType2" name="loginType" value="loan" class="input_check" /><label for="loginType2" class="first">대출회원</label></span></p>
      <dl>
        <dt><label for="uid">아이디</label></dt>
        <dd><input type="text" id="uid" name="uid" req="required" opt="userid" title="사용자 아이디" class="input_blue" style="width:105px;" value="'.$_COOKIE[USERID].'" accesskey="L" /></dd>
        <dt><label for="upw">패스워드</label></dt>
        <dd><input type="password" id="upw" name="upw" req="required" trim="trim" title="사용자 비밀번호" class="input_blue" style="width:105px;"  /></dd>
      </dl>
		  <p class="submit"><input class="submit" type="image" src="'.__SKIN__.'/image/button/loginbox_login.gif" width="46" height="40" alt="로그인" /></p>
		  <div class="clear"></div>
      <ul>
        <li class="btn2"><a href="'.$cfg[droot].'?cate=000002002"><img src="'.__SKIN__.'image/button/loginbox_join.gif" width="64" height="24" alt="회원가입" /></a></li>
        <li class="btn2"><a href="'.$cfg[droot].'?cate=000002003"><img src="'.__SKIN__.'image/button/loginbox_find.gif" width="96" height="24" alt="회원가입" /></a></li>
      </ul>
      <div class="clear"></div>
    </fieldset>
		</form>
	</div>';
}
else {
  $this->result .= '<fieldset class="login_after" style="padding-top:25px;"><div class="info"><p><strong>'.$_SESSION[uname].'</strong>: <span>('.$_SESSION[uposition].')</span></p>';
  if($_SESSION[upoint] > 0)
  {
    $this->result .= '<p class="point">적립 포인트: ( <strong>'.number_format(Member::memberGetPoint($_SESSION[uid])).'</strong> )</p>';
  } else
  {
    $this->result .= '<p class="point">현재 로그인 중 입니다.</p>';
  }
  $this->result .= '</div><div class="clear"></div>
  <ul>
    <li class="btn2"><a href="'.$cfg[droot].'?cate=000002001&amp;uri='.__URI__.'"><img src="'.__SKIN__.'image/button/loginbox_logout.gif" width="64" height="24" alt="로그아웃" /></a></li>
    <li class="btn2"><a href="'.$cfg[droot].'?cate=000002002"><img src="'.__SKIN__.'image/button/loginbox_modify.gif" width="96" height="24" alt="회원정보변경" /></a></li>
  </ul>
  </fieldset>';
}
$this->result .= '</div>';
?>
