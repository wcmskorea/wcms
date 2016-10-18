<?php
/**
 * 카테고리별 등록/수정
 * Last (2008.10.06 : 이성준)
 */
require_once "../include/commonHeader.php";

if($_POST['type'] == "cateSortPost")
{
	$sort = explode("|", $_POST['displayOrder']);
	foreach($sort AS $key=>$val)
	{
		$j = (strlen($val) == 3) ? $key : $key + 1;
		$db->query(" UPDATE `site__` SET sort='".$j."' WHERE skin='".$_POST['skin']."' AND cate='".$val."' ");
	}
	$display->cacheXml($_POST['skin']);
	$func->setLog(__FILE__, "카테고리 (".$_POST['displayOrder'].")순서 변경");
	$func->err("카테고리 순서가 정상적으로 변경되었습니다.", "parent.$.insert('#tabBody".$_POST['skin']."', './modules/categoryList.php?skin=".$_POST['skin']."',null,300); parent.$.dialogRemove();");
}
else
{
	$len = (defined('__CATE__')) ? strlen(__CATE__)+3 : 0;
    if($len > 3)
    {
		$db->query(" SELECT cate,sort,name FROM `site__` WHERE skin='".$_GET['skin']."' AND LENGTH(cate)='".$len."' AND cate like '".__CATE__."%' ORDER BY sort ASC ");
    }
    else
    {
		$db->query(" SELECT * FROM `site__` WHERE skin='".$_GET['skin']."' AND LENGTH(cate)='3' ORDER BY sort ASC ");
    }
}
?>

<form name="frmCate" method="post" action="<?php echo($_SERVER['PHP_SELF']);?>" enctype="multipart/form-data" target="hdFrame">
<input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />
<input type="hidden" name="skin" value="<?php echo($_GET['skin']);?>" />
<input type="hidden" name="type" value="cateSortPost" />

<div class="menu_violet"><p title="드래그하여 이동하실 수 있습니다"><strong>카테고리 순서변경 (<?php echo($_GET['skin']);?>)</strong></p></div>
<div style="width:30px; float:left;"><p class="pd3 center"><a href='#none' onclick="moveSortCategory('up');"><img src="<?php echo($cfg['droot']);?>common/image/button/btn_s_up.gif" alt="위로" title="위로" /></a></p><p class="pd3 center"><a href='#none' onclick="moveSortCategory('dw');"><img src="<?php echo($cfg['droot']);?>common/image/button/btn_s_down.gif" alt="아래로" title="아래로" /></a></p></div>
<div style="float:left;">
	<select id="cateList" name="cateList" size="13" class="bg_gray" style="width:470px; padding:3px;">
	<?php
    if($db->getNumRows() < 1)
    {
		echo('<option>등록된 카테고리가 없습니다.</option>');
    }
    else
    {
		$n = 1;
		while($Rows = $db->fetch())
		{
			echo('<option value="'.$Rows['cate'].'">('.$n.'). '.$Rows['name'].'</option>');
			$displayOrder .= $Rows['cate']."|";
			$n++;
		}
	}
	?>
	</select>
</div>
<div class="clear"></div>
<div class="pd5 small_gray" style="float:left; padding-left:30px;">[1]카테고리 선택 &gt; [2]이동버튼 클릭 &gt; [3]적용</div>
<div class="pd5 center" style="float:right;"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit">위 설정으로 적용</button></span>&nbsp;<span class="btnPack black medium"><a href="javascript:;" onclick="$.dialogRemove()">취소</a></span></div>
<input type="hidden" name="displayOrder" value="<?php echo($displayOrder);?>" />
</form>

<script type="text/javascript">
//<![CDATA[
function moveSortCategory(mode)
{
	var obj = document.getElementById('cateList');
	f_index = 0
	e_index = obj.length - 1
	select_index = obj.selectedIndex;
	if(select_index < 0)
	{
		alert('목록에서 이동할 카테고리를 선택하세요');
		return false;
	}
	if( mode == "dw" )
	{
		if( select_index < e_index )
		{
			select_obj = obj[select_index];
			dest_obj = obj[select_index+1];
			temp_value = select_obj.value;
			temp_text = select_obj.text;
			select_obj.value = dest_obj.value;
			select_obj.text = dest_obj.text;
			dest_obj.value = temp_value;
			dest_obj.text = temp_text;
			dest_obj.selected = true;
		}
	}
	else
	{
		if( select_index > f_index )
		{
			select_obj = obj[select_index];
			dest_obj = obj[select_index-1];
			temp_value = select_obj.value;
			temp_text = select_obj.text;
			select_obj.value = dest_obj.value;
			select_obj.text = dest_obj.text;
			dest_obj.value = temp_value;
			dest_obj.text = temp_text;
			dest_obj.selected = true;
		}
	}
	document.frmCate.displayOrder.value = '';
	for (i=0 ;i <= obj.length-1 ;i++)
	{
		document.frmCate.displayOrder.value += obj[i].value;
		if(i < obj.length-1) document.frmCate.displayOrder.value += '|';
	}
}
//]]>
</script>
<?php include "../include/commonScript.php"; ?>
