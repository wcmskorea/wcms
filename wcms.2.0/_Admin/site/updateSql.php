<?php
require_once "../../_config.php";
require_once "../include/commonHeader.php";

if($_POST['type'] == 'updateSqlPost')
{
	//넘어온 값과 변수 동기화 및 validCheck
	foreach($_POST AS $key=>$val)
	{
		//$func->validCheck(체크할 값, 항목제목, 항목명, 체크타입)
		if($key == "query") { $func->vaildCheck($val, "실행쿼리", "trim", "M"); }
	}
	usleep(300000);

	$n = 0;
	$_POST['query'] = str_replace("(eq)","=",$_POST['query']);

	$db_list = mysql_list_dbs($db->oConn);
	while ($row = mysql_fetch_object($db_list))
	{
		if($row->Database != 'information_schema' & $row->Database != 'mysql')
		{

			$db->selectDB($row->Database);
			//$db->Query("SHOW TABLES LIKE '".$_POST[table]."'");
			if($_POST['table'])
			{
				if($db->checkTable($_POST['table']) == 1)
				{
					echo stripslashes($_POST['query']) . "<br />";
					$db->queryForce(stripslashes($_POST['query']), 1);
					$n++;
				}
			} else if(!preg_match('/delete|drop/i', $_POST['query'])) {
				echo stripslashes($_POST['query']) . "<br />";
				$db->queryForce(stripslashes($_POST['query']), 1);
				$n++;
			}
		}
	}
	$func->err("Query가 정상적으로 실행되었습니다.");
	exit;
}
?>
<div id="messageBox"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/delete.gif" /></spna>&nbsp;<strong>Message :</strong>&nbsp;&nbsp;&nbsp;</div>
<fieldset id="help">
<legend align="center">→ Update Query ←</legend>
<ul>
	<li><span style="color:#990000">[주의]</span> 한번의 실수로 모든 데이터가 날라갈 수 있습니다. 신중하게 실행하십시오.</li>
	<li><span style="color:#990000">[주의]</span> 본 항목은 백업 상태를 확인하고 시행하시기 바랍니다.</li>
	<li><span style="color:#990000">[주의]</span> Query는 하나의 Table당 하나의 Query만 허용됩니다.</li>
</ul>
</fieldset>
<div class="pd3"></div>

<form id="frm04" name="frm04" method="post" action="./site/updateSql.php" enctype="multipart/form-data" target="hdFrame">
<input type="hidden" name="type" value="updateSqlPost" />

<table class="table_basic" style="100%" summary="">
<caption>모든 데이터베이스 Query일괄 적용</caption>
<col width="120">
<col>
<tr>
	<th scope="col">(1) 테이블 선택</th>
	<td><select name="table">
		<option value="">Select Table...</option>
		<?php
		$db->query("SHOW TABLES FROM `".__DB__."`");
		while ($row = $db->fetchRow()) echo '<option value="'.$row[0].'">'.$row[0].'</option>';
		?>
		</select>&nbsp;<span class="small_red left">" = " 이퀄표시는 Ajax에서 짤리므로 " (eq) "로 적어야 Query가 실행됩니다.</span>
	</td>
</tr>
<tr>
	<th scope="col">(2) 실행할 QUERY</th>
	<td><textarea name="query" class="input_text required" style="width:99%; height:60px;" onfocus="getFoc(this)" onblur="losFoc(this,'')" req="required"><?=$Rows[info]?></textarea></td>
</tr>
</table>
<div class="pd10 center"><p><span class="btnPack medium strong red"><button type="submit" onclick="return $.submit(this.form)">실행하기</button></span></p></div>
</form>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$('#frm04').validate({onkeyup:function(element){$(element).valid();});
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
//]]>
</script>
<?php include "../include/commonScript.php"; ?>