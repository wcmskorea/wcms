<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
//약관동의 와 개인정보취급방침 둘다 없을경우 가입폼으로 이동
if($cfg['module']['agreement'] == 'N') { header("Location: /index.php?cate=000002002&type=".$sess->encode('registForm')); die(); }
?>
<div id="regist_wrap">
<div class="regist_container">
<?php
switch($cfg['module']['form'])
{
	case 'Basic' :
		include __PATH__."modules/mdMember/registCheck".$cfg['module']['cert'].".php";
		break;
	case 'Gov' :
		include __PATH__."modules/mdMember/registCheck".$cfg['module']['cert'].".php";
		break;
	case 'School' :
		include __PATH__."modules/mdMember/registCheck".$cfg['module']['cert'].".php";
		break;
	default :
		include __PATH__."modules/mdMember/registCheck".$cfg['module']['cert'].".php";
		break;
}
?>
</div>
<!-- .regist_container end --></div>
<!-- #regist_wrap end -->
