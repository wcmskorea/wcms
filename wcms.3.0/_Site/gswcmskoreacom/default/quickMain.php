<hr />
<div id="quick_right" style="position:absolute; z-index:901;">
	<?php
		//디스플레이 => 퀵
		$display->setPrint("MQ");
	?>
</div>
<script type="text/javascript">
//<![CDATA[
$.quickScrolls = function()
{
	var center	= <?php echo($cfg['site']['size'] / 2); ?>;
	var right_v = getScrollTop() + "px";
	var right_h = <?php echo($cfg['site']['size']);?> + "px";
	$("#quick_right").css({"top":right_v, "left":right_h, "display":"block"});
}
$(document).ready(function(){$.quickScrolls();});
$(window).scroll(function(){$.quickScrolls();});
//]]>
</script>
