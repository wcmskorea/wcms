<?php
global $sess, $lang;

$this->result .= '<div id="login_wrap02" style="width:'.$this->width.'px;height:'.$this->height.'px;">';
if(!isset($_SESSION[uid]))
{
	$this->result .= '<div class="login_box02" style="padding-top:5px;">
		<form name="login" method="post" action="'.$this->cfg[droot].'index.php" enctype="multipart/form-data">
		<input type="hidden" name="cate" value="000002001" />
		<input type="hidden" name="type" value="'.$sess->encode('loginAccess').'" />
		<input type="hidden" name="uri" value="'.__URI__.'" />
    <ul>
      <li class="btn"><input type="text" id="uid" name="uid" title="사용자 아이디" class="input_blue" style="width:105px;" value="'.$_COOKIE[USERID].'" accesskey="L" /></li>
      <li class="btn"><input type="password" id="upw" name="upw" title="사용자 비밀번호" class="input_blue" style="width:105px;"  /></li>
      <li class="btn"><input class="submit" type="image" src="'.__SKIN__.'/image/button/loginbox_login.gif" width="46" height="40" alt="로그인" /></li>
			<li class="btn"><a href="'.$cfg[droot].'?cate=000002002"><img src="'.__SKIN__.'image/button/loginbox_join.gif" width="64" height="24" alt="회원가입" /></a></li>
			<li class="btn"><a href="'.$cfg[droot].'?cate=000002003"><img src="'.__SKIN__.'image/button/loginbox_find.gif" width="96" height="24" alt="회원가입" /></a></li>
		</ul>
		<div class="clear"></div>
		</form>
	</div>';
}
else {
  $this->result .= '<fieldset class="login_box02" style="padding-top:5px;">
  <ul>
    <li class="btn" style="padding:9px;"><strong>'.$_SESSION[uname].'</strong>: <span>('.$_SESSION[uposition].')</span>';
  if($_SESSION[upoint] > 0)
  {
    $this->result .= '<p class="point">적립 포인트: ( <strong>'.number_format(Member::memberGetPoint($_SESSION[uid])).'</strong> )</p>';
  } else
  {
    $this->result .= '<p class="point">현재 로그인 중 입니다.</p>';
  }
  $this->result .= '</li>
  <ul>
    <li class="btn"><a href="'.$cfg[droot].'?cate=000002001&amp;uri='.__URI__.'"><img src="'.__SKIN__.'image/button/loginbox_logout.gif" width="64" height="24" alt="로그아웃" /></a></li>
    <li class="btn"><a href="'.$cfg[droot].'?cate=000002002"><img src="'.__SKIN__.'image/button/loginbox_modify.gif" width="96" height="24" alt="회원정보변경" /></a></li>
  </ul>
  </fieldset>';
}
$this->result .= '</div>';
?>
