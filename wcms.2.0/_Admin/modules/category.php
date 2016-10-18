<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_Admin/include/commonHeader.php";
$_GET['skin'] = ($_GET['skin']) ? $_GET['skin'] : "default";
?>
<h2><span class="arrow">▶</span>카테고리 및 콘텐츠 관리</h2>
<div class="tabMenu2">
	<ul class="tabBox">
	<?php
	foreach($cfg['skinName'] as $key=>$val)
	{
		if($key == 'default' || $cfg['site'][$key.'web'] == '1')
		{
			$active = ($key == $_GET['skin']) ? ' on" style="margin-left:0;"' : '"';
			echo('<li class="tab'.$active.' id="tab'.$key.'"><p><a href="javascript:;" onclick="$.tabMenu(\'tab'.$key.'\',\'#tabBody'.$key.'\',\''.$cfg['droot'].'_Admin/modules/categoryList.php?skin='.$key.'\',null,200)" class="actgray" style="width:100px;">'.$val.'</a></p></li>');
		}
	}
	?>
	</ul>
	<div class="clear"></div>
	<?php
	foreach($cfg['skinName'] as $key=>$val)
	{
		$active = ($key == $_GET['skin']) ? " on" : " hide";
		echo('<div class="tabBody'.$active.'" id="tabBody'.$key.'"></div>');
	}
	?>
</div>
<script type="text/javascript">
//<![CDATA[
	$(document).ready(function(){$.insert("#tabBodydefault","<?php echo($cfg['droot']);?>_Admin/modules/categoryList.php?skin=<?php echo($_GET['skin']);?>",null,200);});
//]]>
</script>

