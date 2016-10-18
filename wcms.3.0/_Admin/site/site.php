<?php
require_once "../include/commonHeader.php";
?>
<h2><span class="arrow">▶</span>사이트 환경 설정</h2>
<div class="tabMenu2">
<ul class="tabBox">
<?php
foreach ($cfg['skinName'] as $key => $val)
{
	if($key == 'default' || $cfg['site'][$key.'web'] == '1')
	{
		echo('<li class="tab');
		if($key == 'default') { echo(' on" style="margin-left:0;"'); } else { echo('"'); }
		echo(' id="tab'.$key.'"><p><a href="javascript:;" onclick="$.tabMenu(\'tab'.$key.'\',\'#tabBody'.$key.'\',\'./site/siteConfig.php?skin='.$key.'\',null,200);" class="actgray" style="width:100px;">'.$val.'</a></p></li>');
	}
}
?>
</ul>
<div class="clear"></div>
<?php
foreach ($cfg['skinName'] as $key => $val)
{
	echo('<div class="tabBody');
	if($key == 'default') { echo(' show'); } else { echo(' hide'); }
	echo('" id="tabBody'.$key.'"></div>');
}
?>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$.insert('#tabBodydefault', './site/siteConfig.php?skin=default', null, 200);
});
//]]>
</script>
</div>
