<?php
$width		= preg_replace('/px|%/',null,$this->config['width']);
$height		= preg_replace('/px|%/',null,$this->config['height']);

$this->result .= '<div id="login" style="width:'.$this->config['width'].'; height:'.$this->config['height'].'; background:url('.__SKIN__.'image/background/bg_recent_'.strtolower($this->prefix).'.gif) no-repeat left top;">
<div class="loginBox">'.PHP_EOL;
if(!isset($_SESSION['uid']))
{
	if($this->cfg['site']['ssl'])
	{
		$this->result .= '<form name="login" method="post" action="https://'.$_SERVER['HTTP_HOST'].":".$this->cfg['site']['ssl'].$this->cfg['droot'].'index.php" enctype="multipart/form-data" onsubmit="return validCheck(this)">'.PHP_EOL;
	}
	else
	{
		$this->result .= '<form name="login" method="post" action="'.$GLOBALS['cfg']['droot'].'index.php" enctype="multipart/form-data">'.PHP_EOL;
	}
	$this->result .= '<input type="hidden" name="cate" value="000002001" />
	<input type="hidden" name="type" value="'.$GLOBALS['sess']->encode('loginAccess').'" />
	<input type="hidden" name="uri" value="'.urlencode($_SERVER['REQUEST_URI']).'" />
	<input type="hidden" name="loginType" value="web" />
	<fieldset class="loginBefore">
	<legend>회원로그인</legend>
		<dl>
			<dt><label for="uid" class="hide">아이디</label></dt>
			<dd><input type="text" id="uid" name="uid" title="사용자 아이디" class="input_blue required" style="width:105px;" value="'.$_COOKIE['USERID'].'" accesskey="L" /></dd>
			<dt><label for="upw" class="hide">패스워드</label></dt>
			<dd><input type="password" id="upw" name="upw" title="사용자 비밀번호" class="input_blue required" style="width:105px;"  /></dd>
		</dl>
		<p class="submit"><span class="keeping"><input type="checkbox" id="autoid" name="autoid" value="Y"';
		if($_COOKIE['USERID']) { $this->result .= 'checked="checked"'; }
		$this->result .= 'class="input_check" /><label for="autoid">ID저장</label></span></p>
		<p class="submit"><input class="submit" type="image" src="'.__SKIN__.'image/button/loginbox_login.gif" alt="로그인" onclick="return $.submit(this.form)" /></p>
		<div class="clear"></div>
		<ul>
			<li class="btn regist"><a href="'.$GLOBALS['cfg']['droot'].'?cate=000002002" target="_top">회원가입</a></li>
			<li class="btn">|</li>
			<li class="btn"><a href="'.$GLOBALS['cfg']['droot'].'?cate=000002003" target="_top">아이디 & 비밀번호 찾기</a></li>
		</ul>
		<div class="clear"></div>
	</fieldset>
	</form>'.PHP_EOL;

} else {

	$this->result .= '<fieldset class="loginAfter"><div class="info"><p><strong>'.$_SESSION['uname'].'</strong> <span>('.$_SESSION['uposition'].')</span></p>'.PHP_EOL;
	if(Functions::checkModule('mdMileage'))
	{
		$this->result .= '<p class="point">적립 포인트 ( <strong>'.number_format(Mileage::userGetMileage($_SESSION['useq'])).'</strong> )</p>'.PHP_EOL;
	} else
	{
		$this->result .= '<p class="point">현재 로그인 중 입니다.</p>'.PHP_EOL;
	}
	$this->result .= '</div><div class="clear"></div>
	<ul>
		<li class="btn out"><a href="'.$GLOBALS['cfg']['droot'].'?cate=000002001&amp;uri='.$_SERVER['REQUEST_URI'].'">로그아웃</a></li>
		<li class="btn">|</li>
		<li class="btn"><a href="'.$GLOBALS['cfg']['droot'].'?cate=000002002&amp;uri='.$_SERVER['REQUEST_URI'].'">MyPage (회원정보)</a></li>
	</ul>
	</fieldset>'.PHP_EOL;

}
$this->result .= '</div></div>'.PHP_EOL;
?>