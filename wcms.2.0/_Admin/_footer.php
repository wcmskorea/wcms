</div>
<!--<div style="position:fixed; _position:absolute; bottom:27px;right:0">Memory Usage : <strong><?php echo(number_format((memory_get_usage()/1024)/1024,1)); ?></strong> Mbyte</div>-->
<script type="text/javascript">
//<![CDATA[
	$.memberInfo = function(user)
	{
		$.dialog('../modules/mdMember/manage/_controll.php', '&type=detail&user='+user, 1000, 500);
		return true;
	};
	$(document).ready(function()
	{
		$("#ajax_body").draggable();
		$.insert('#todays','./include/todays.php','',50);
		//$.insert('#service','./include/service.php',null,150);
		$.insert('#lastest','./include/lastest.php','',98);
		$("input[type=text],input[type=password],textarea").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active"); });
		$("a[rel*=facebox]").facebox();
		$(document).keypress(function(e){if(e.which == 27) $.dialogRemove();});
	});
    //setInterval(function(){$("#messageBox").fadeOut("slow").fadeIn("slow");},1000);
//]]>
</script>
</body>
</html>
<?php
$db->Disconnect();
?>
