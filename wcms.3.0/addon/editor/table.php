<?
#------------------------------------------------------------------------------
# 작업내용: 에디터 테이블 생성
# 작성일자: 2007-07-02
#------------------------------------------------------------------------------
require_once "../../_config.php";
#------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>테이블삽입</title>
<meta http-equiv="content-type" content="text/html; charset=<?php echo($cfg[charset]);?>" />
<meta http-equiv="content-style-type" content="text/css" />
<meta http-equiv="pragma" content="no-cache" />
<style>
body {background: threedface; color: windowtext; margin: 10px; border-style: none; font:9pt 돋움; text-align:center;}
body, button, div, input, select, td, legend { font:9pt 돋움; }
button {width:80px;}
fieldset { width:150px; }
</style>
<script type="text/javascript">
<!--
function insertTable()
{
	var f = document.tform;
	tbl_align = f.align[f.align.selectedIndex].value;
	tbl_width = f.width.value;
	tbl_width_unit = f.width_unit[f.width_unit.selectedIndex].value;
	tbl_cellpadding = f.cellpadding.value;
	tbl_cellspacing = f.cellspacing.value;
	tbl_border = f.border.value;
	tbl_color = f.color[f.color.selectedIndex].value;
	tbl_cols = eval(f.cols.value);
	tbl_rows = eval(f.rows.value);
	if (tbl_cols > 30 ||tbl_cols < 1) tbl_cols = 2;
	if (tbl_rows > 30 ||tbl_rows < 1) tbl_rows = 2;
	if (tbl_color=="white")
	{
		text='style=color:black';
		bgcolor='white';
	}
	else if (tbl_color=="black")
	{
		text='style=color:white';
		bgcolor='black';
	}
	else if (tbl_color=="red")
	{
		text='style=color:white';
		bgcolor='red';
	}
	else if (tbl_color=="blue")
	{
		text='style=color:white';
		bgcolor='blue';
	}
	else if (tbl_color=="yellow")
	{
		text='style=color:black';
		bgcolor='yellow';
	}
	else if (tbl_color=="green")
	{
		text='style=color:white';
		bgcolor='green';
	}
	else if (tbl_color=="parkoz")
	{
		text='style=color:#cccccc';
		bgcolor='#004f75';
	}
	else
	{
		text='';
		bgcolor='';
	}
	str = '<table bordercolor=gray '
		+ ' border="'+tbl_border+'"'
		+ ' cellpadding="'+tbl_cellpadding+'"'
		+ ' cellspacing="'+tbl_cellspacing+'"'
		+ ' align="'+tbl_align+'"'
		+ ' width="'+tbl_width+tbl_width_unit+'"'
		+ ' bgcolor="'+bgcolor+'"'
		+ '>';
	for (i = 1; i <= tbl_cols; i++)
	{
		str += '<tr>';
		for (j = 1; j <= tbl_rows; j++)
		{
			str += '<td '+text+'></td>';
		}
	}
	str += '</table>';
	opener.editor<?php echo(__CATE__);?>.innerHTML(str);
	self.close();
}
//-->
</script>
</head>
<body scroll="no">
<form name="tform" onsubmit="return false">
<table border=0 width="100%">
<tr><td>
	<fieldset style="float:left;width:100%">
	<legend>기본 Cell</legend>
	<table border=0 cellspacing=6 cellpadding=0>
	<tr>
	<td>가로</td>
	<td><input type="text" name="cols" value="2"  style="width:50px" maxlength=2></td>
	</tr>
	<tr>
	<td>세로</td>
	<td><input type="text" name="rows" value="2"  style="width:50px" maxlength=2></td>
	</tr>
	</table>
	</fieldset>
	</td>
	<td>
	<fieldset style="float:left;width:100%">
	<legend>배경 & 폭</legend>
	<table border=0 cellspacing=6 cellpadding=0>
	<tr>
	<td>배경:</td>
	<td>
	<select name="color" size=1>
	<option value="l"></option>
	<option value="white">white</option>
	<option value="black">black</option>
	<option value="red">red</option>
	<option value="blue">blue</option>
	<option value="yellow">yellow</option>
	<option value="green">green</option>
	</select>
	</td>
</tr>
<tr>
	<td>폭:</td>
	<td>
	<input type="text" name="width" value="400" style="width: 50px" maxlength=4>
	<select name="width_unit">
	<option value="" selected>px</option>
	<option value="%" >%</option>
	</select>
	</td>
	</tr>
	</table>
	</fieldset>
	</td>
</tr>
<tr><td>
	<fieldset style="clear:left;float:left;width:100%">
	<legend>레이아웃</legend>
	<table border=0 cellspacing=6 cellpadding=0>
	<tr>
	<td height=21>align</td>
	<td>
	<select name="align" size=1>
	<option value="center">center</option>
	<option value="left" selected>left</option>
	<option value="right">right</option>
	<option value="texttop">texttop</option>
	<option value="absmiddle">absmiddle</option>
	<option value="baseline">baseline</option>
	<option value="absbottom">absbottom</option>
	<option value="bottom">bottom</option>
	<option value="middle">middle</option>
	<option value="top">top</option>
	</select>
	</td>
</tr>
<tr>
	<td>border</td>
	<td><input type="text" name="border" value="0" size="4" style="width:100%"></td>
</tr>
</table>
</fieldset>

</td><td>
	<fieldset style="float:left;width:100%">
	<legend>간격</legend>
	<table border=0 cellspacing=6 cellpadding=0>
	<tr>
	<td>cell spacing</td>
	<td><input type="text" name="cellspacing" value="1" style="width:50px" maxlength="4"></td>
	</tr>
	<tr>
	<td>cell padding</td>
	<td><input type="text" name="cellpadding" value="0" style="width:50px" maxlength="4"></td>
	</tr>
	</table>
	</fieldset>
</td></tr>
<tr>
<td colspan="2" align="center">
<button onclick="insertTable()">확인</button> &nbsp;<button onclick="self.close();">취소</button>
</td>
</tr>
</table>
</form>
</body>
</html>
