<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/selectBox.js"></script>
<script type="text/javascript">
//<[!CDATA[
$(document).ready(function()
{
	$("#ajax_display").css('background','#fff');
	//$("tr.active").bind("mouseenter mouseleave", function(e){$(this).toggleClass("this");});
	$("input[type=text],input[type=password],textarea").focus(function(){$(this).toggleClass("input_active").select();}).blur(function(){$(this).toggleClass("input_active");});
	$("#allarticle").click(function() {
		if ($("#allarticle").is(":checked")) {
			$("input[class=articleCheck]:checked").prop("checked", false);
		} else {
			$("input[class=articleCheck]:not(checked)").prop("checked", true);
		}
	});
	$("a[rel*=facebox]").facebox();

	$("#today").click(function(){
		var startDate = gtDate(0,0,0);
		var endDate   = gtDate(0,0,0);
		stDate(startDate,endDate,'today');
	});

	$("#yesterday").click(function(){
		var startDate = gtDate(0,0,-1);
		var endDate   = gtDate(0,0,-1);
		stDate(startDate,endDate,'yesterday');
	});

	$("#month").click(function(){
		var startDate = gtDate(0,-1,0);
		var endDate   = gtDate(0,0,0);
		stDate(startDate,endDate,'month');
	});

	$("#todayMonth").click(function(){
		var startDate = fixdayDate(1);
		var endDate   = gtDate(0,0,0);
		stDate(startDate,endDate,'todayMonth');
	});

	/* 날짜 출력 */
	function stDate(arr,arr2,key){
		$("#syear").val(arr[0]);
		$("#smonth").val(arr[1]);
		$("#sday").val(arr[2]);

		$("#eyear").val(arr2[0]);
		$("#emonth").val(arr2[1]);
		$("#eday").val(arr2[2]);

		$("#dateType").val(key);
	}

	/*날짜 변경 */
	function gtDate(year,month,day){
		var date = new Date();
		var returnArray = new Array(3);

		date.setYear(date.getFullYear() + year );
		date.setMonth(date.getMonth() + month );
		date.setDate(date.getDate() + day);

		var getYear  = date.getFullYear();
		var getMonth = date.getMonth()+1;
		var getDay   = date.getDate();

		returnArray[0] = getYear;
		returnArray[1] = getMonth;
		returnArray[2] = getDay;

		return returnArray;
	}

	/* 일자 고정 */
	function fixdayDate(day){
		var date = new Date();
		var returnArray = new Array(3);

		date.setDate(day);
		var getYear = date.getFullYear();
		var getMonth = date.getMonth()+1;
		var getDay = date.getDate();

		returnArray[0] = getYear;
		returnArray[1] = getMonth;
		returnArray[2] = getDay;
		return returnArray;
	}
});
//]]>
</script>
