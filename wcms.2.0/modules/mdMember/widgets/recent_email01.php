<?php
global $sess, $lang;

$this->result .= '<div id="login_wrap02" style="width:'.$this->width.'px;height:'.$this->height.'px;">';
if(!isset($_SESSION[uid]))
{
	$this->result .= '<div id="login_box" style="padding-top:12px;">
		<form name="login" method="post" action="'.$this->cfg[droot].'index.php" enctype="multipart/form-data" target="hdFrame">
		<input type="hidden" name="cate" value="000002001" />
		<input type="hidden" name="type" value="'.$sess->encode('loginAccess').'" />
		<input type="hidden" name="uri" value="'.__URI__.'" />
		<p style="float:right;"><span class="keeping"><input type="checkbox" id="autoid" name="autoid" value="Y"';
    if($_COOKIE[USERID]) { $this->result .= 'checked="checked"'; }
    $this->result .= 'class="input_check" tabindex="3" /><label for="autoid">'.$lang[save_email].'</label></span></p>
		<dl>
		<dt><label for="uid">이메일</label></dt>
		<dd style="padding:2px 0"><input type="text" id="uid" name="uid" title="사용자 이메일" class="input_blue" style="width:105px;" value="'.$_COOKIE[USERID].'" accesskey="L" /></dd>
		<dt><label for="upw">패스워드</label></dt>
		<dd><input type="password" id="upw" name="upw" title="사용자 비밀번호" class="input_blue" style="width:105px;"  /></dd>
		</dl>
		<p><input class="submit" type="image" src="'.__SKIN__.'/image/button/loginbox_login.gif" width="46" height="40" alt="로그인" /></p>
		<div class="clear"></div>
		<ul>
			<li class="btn2"><a href="'.$cfg[droot].'?cate=000002002"><img src="'.__SKIN__.'image/button/loginbox_join.gif" width="64" height="24" alt="회원가입" /></a></li>
			<li class="btn2"><a href="'.$cfg[droot].'?cate=000002003"><img src="'.__SKIN__.'image/button/loginbox_find.gif" width="96" height="24" alt="회원가입" /></a></li>
		</ul>
		<div class="clear"></div>
		</form>
	</div>';
}
else {
  $this->result .= '<fieldset id="login_after" style="padding-top:25px;"><div class="info"><p><strong>'.$_SESSION[uname].'</strong> <span>('.$_SESSION[uposition].')</span></p>';
  if(in_array('mdPay', $this->cfg[modules]))
  {
    $this->result .= '<p class="point">적립 포인트: ( <span>'.number_format($_SESSION[upoint]).'</span> ) Point</p>';
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
