<?php
require_once "../include/commonHeader.php";
$menuType = $_GET['menuType'];
switch($menuType) {
	case "tab01" :
		$tab01_On   = "on";
		$tab01_Show = "show";
		break;
	case "tab02" :
		$tab02_On   = "on";
		$tab02_Show = "show";
		break;
	default :
		$tab01_On   = "on";
		$tab01_Show = "show";
		break;
}
?>
<h2><span class="arrow">▶</span>기본정보 설정</h2>
<div class="tabMenu2">
	<ul class="tabBox">
		<li class="tab on" id="tab01" style="margin-left:0;"><p><a href="javascript:;" onclick="$.tabMenu('tab01','#tabBody01','./site/infoSystem.php',null,200)" class="actgray" style="width:100px;">시스템 정보</a></p></li>
		<li class="tab" id="tab02"><p><a href="javascript:;" onclick="$.tabMenu('tab02','#tabBody02','./site/infoSite.php',null,200)" class="actgray" style="width:110px;">사이트 기본 정보</a></p></li>
	</ul>
	<div class="tabBody show" id="tabBody01"></div>
	<div class="tabBody hide" id="tabBody02"></div>
<script type="text/javascript">
//<![CDATA[
	<?php
	switch($menuType) {
  case "tab01" :
    echo ('$(document).ready(function(){$.insert("#tabBody01","./site/infoSystem.php",null,200);});');
    break;
  case "tab02" :
    echo ('$(document).ready(function(){$.insert("#tabBody02","./site/infoSite.php",null,200);});');
    break;
  default :
    echo ('$(document).ready(function(){$.insert("#tabBody01","./site/infoSystem.php",null,200);});');
    break;
	}
	?>
//]]>
</script>
</div>
