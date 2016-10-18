<?php
require_once "../../_config.php";
require_once "../include/commonHeader.php";

//디스플레이 등록
if($_POST['type'] == "displayPost")
{
	$func->checkRefer("POST");

	//넘어온 값과 변수 동기화 및 validCheck
	foreach($_POST AS $key=>$val)
	{
		${$key} = addslashes(trim($val));
		//$func->validCheck(체크할 값, 항목제목, 항목명, 체크타입)
		if($key == "name") { $func->vaildCheck($val, "유닛의 타이틀 및 설명", $key, "trim" ,"M"); }
		if($key == "width") { $func->vaildCheck($val, "유닛의 가로 사이즈", "trim" ,"Y"); }
		if($key == "height") { $func->vaildCheck($val, "유닛의 세로 사이즈", "trim" ,"Y"); }
	}

	//파일삭제
	if($fileDel == 'Y') { @unlink(__PATH__."_Site/".__DB__."/".$_POST['skin']."/image/skin_".strtolower($position).$sort.".".$extName); }
	if($fileDel1 == 'Y') { @unlink(__PATH__."_Site/".__DB__."/".$_POST['skin']."/image/background/bg_skin_".strtolower($position).$sort.".jpg"); }

	//첨부파일 처리
	require_once __PATH__."_Lib/classUpLoad.php";
	$up = new upLoad($cfg['upload']['dir'], $_FILES);
	$up->count = 2;

	//배경 이미지
	if(is_uploaded_file($_FILES['upfile1']['tmp_name']))
	{
		$up->dir = __PATH__."_Site/".__DB__."/".$_POST['skin']."/image/background/";
		$up->thumbIsFix = 'F'; //원본사이즈 그대로
		$up->upFiles("bg_skin_".strtolower($position).$sort);
	}

	//스킨 이미지
	if(is_uploaded_file($_FILES['upfile']['tmp_name']))
	{
		$up->dir = __PATH__."_Site/".__DB__."/".$_POST['skin']."/image";
		if($common && $common != 'Y')
		{
			$fileName = "skin_".strtolower($position).$sort.$common;
		}
		else
		{
			$fileName = "skin_".strtolower($position).$sort;
		}
		$up->thumbIsFix = 'F'; //원본사이즈 그대로
		$up->upFiles($fileName);
		$width = ($up->basicSize['0']) ? $up->basicSize['0'].'px' : $width;
		$height = ($up->basicSize['1']) ? $up->basicSize['1'].'px' : $height;
	}
	else
	{
		$up->fileExt = $extName;
	}

	$db->data['sort']	= $sort;
	$db->data['position'] = $position;
	$db->data['cate'] = ($common != 'Y') ? $common : null;
	$db->data['name'] = $name;
	$db->data['form'] = $form;
	$db->data['listing'] = $up->fileExt;
	$db->data['useHidden'] = $useHidden;
	$db->data['config']['url'] = $url;
	$db->data['config']['target'] = $target;
	$db->data['config']['module'] = "skin";
	$db->data['config']['common'] = ($common) ? ($local == 'Y') ? "N" : $common : "Y";
	$db->data['config']['commonExcept'] = str_replace(" ", null, $commonExcept);
	$db->data['config']['commonExcept'] = (preg_match("/,$/", $db->data['config']['commonExcept'])) ? $db->data['config']['commonExcept'] : $db->data['config']['commonExcept'].",";
	$db->data['config']['commonExcept'] = (preg_match("/^,/", $db->data['config']['commonExcept'])) ? $db->data['config']['commonExcept'] : ",".$db->data['config']['commonExcept'];
	$db->data['config']['width'] = $width;
	$db->data['config']['height'] = $height;
	$db->data['config']['pdt'] = $pdt ? $pdt : '0';
	$db->data['config']['pdr'] = $pdr ? $pdr : '0';
	$db->data['config']['pdb'] = $pdb ? $pdb : '0';
	$db->data['config']['pdl'] = $pdl ? $pdl : '0';
	$db->data['config']['mgt'] = $mgt ? $mgt : '0';
	$db->data['config']['mgr'] = $mgr ? $mgr : '0';
	$db->data['config']['mgb'] = $mgb ? $mgb : '0';
	$db->data['config']['mgl'] = $mgl ? $mgl : '0';
	$db->data['config']['bgColor'] = $bgColor;
	$db->data['config'] = serialize($db->data['config']);

	if($db->sqlInsert("display__".$skin, "REPLACE", 0) > 0)
	{
		if(!preg_match('/\/_Admin\//', $_SERVER['HTTP_REFERER']))
		{
			$func->errCfm("Display(Skin) 위젯 정보가 정상적으로 적용되었습니다.", "parent.$.dialogRemove();".PHP_EOL."parent.$.insert('#skinSelector', '".$cfg['droot']."_Admin/modules/displayList.php?type=displayList&mode=design&skin=".$_POST['skin']."&position=".$_POST['position']."');");
		}
		else
		{
			$func->err("Display(Skin) 위젯 정보가 정상적으로 적용되었습니다.", "parent.$.dialogRemove();".PHP_EOL."parent.$.insert('#tabBody".$_POST['position']."', './modules/displayList.php?type=displayList&skin=".$_POST['skin']."&position=".$_POST['position']."',null,300);");
		}
	}
}
else if($_GET['type'] == "displayModify" && $_GET['sort'] && $_GET['position'])
{
	$func->checkRefer("GET");

	//디스플레이 수정
	$Rows = $db->queryFetch(" SELECT * FROM `display__".$_GET['skin']."` WHERE sort='".$_GET['sort']."' AND position='".$_GET['position']."' ORDER BY sort ASC ");
	$config = unserialize($Rows['config']);
	$sort = $Rows['sort'];
}
else
{
	$func->checkRefer("GET");
	$sort = $db->queryFetchOne(" SELECT MAX(sort) FROM `display__".$_GET['skin']."` WHERE position='".$_GET['position']."' ") + 1;
}
?>
<div id="messageBox"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/delete.gif" /></spna>&nbsp;<strong>Message :</strong>&nbsp;&nbsp;&nbsp;</div>
<script type="text/javascript" src="/common/js/jquery.color.js"></script>
<form id="frmCate" name="frmCate" method="post" enctype="multipart/form-data" action="<?php echo($_SERVER['PHP_SELF']);?>" target="hdFrame">
<input type="hidden" name="type" value="displayPost" />
<input type="hidden" name="skin" value="<?php echo($_GET['skin']);?>" />
<input type="hidden" name="position" value="<?php echo($_GET['position']);?>" />
<input type="hidden" name="sort" value="<?php echo($sort);?>" />
<input type="hidden" name="extName" value="<?php echo($Rows['listing']);?>" />
<input type="hidden" name="form" value="skin" />

<table class="table_list">
	<col width="130">
	<col width="330">
	<col>
	<thead>
	<tr>
		<th class="first"><p class="center">항목</p></th>
		<th><p class="center">설정값</p></th>
		<th><p class="center">도움말</p></th>
	</tr>
	</thead>
	<tbody>
	<?php
	$form = new Form('table');

	$form->addStart('스킨파일 업로드', 'upfile', 1, 0, 'M');
	$form->add('file', 'upfile', '', 'width:245px; height:20px;');
	$form->addHtml('<li class="opt"><span class="keeping"><input type="checkbox" id="fileDel" name="fileDel" value="Y" /><label for="fileDel">파일삭제</label></span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>해당 스킨의 이미지를 업로드 (자동으로 설정됨)</span>');
	$form->addEnd(1);

	$form->addStart('스킨배경 업로드', 'upfile1', 1, 0, 'M');
	$form->add('file', 'upfile1', '', 'width:245px; height:20px;');
	$form->addHtml('<li class="opt"><span class="keeping"><input type="checkbox" id="fileDel1" name="fileDel1" value="Y" /><label for="fileDel1">파일삭제</label></span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>해당 스킨의 배경이미지를 업로드 (자동으로 설정됨)</span>');
	$form->addEnd(1);

	$form->addStart('제목/설명(alt)', 'name', 1, 0, 'M');
	$form->add('textarea', 'name', $Rows['name'], 'width:310px; height:50px;','maxlength="50"');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>한글 타이틀 및 설명 (웹접근성 향상 문제로 반드시 이미지나 플래시의 타이틀이나 설명을 넣어야 함 (100자이내)</span>');
	$form->addEnd(1);

	$form->addStart('URL 링크', 'url', 1, 0, 'N');
	$form->add('input', 'url', $config['url'], 'width:310px;','maxlength="100"');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>클릭시 이동할 URL을 지정합니다. ( "http://" 를 포함입력 )</span>');
	$form->addEnd(1);

	if(substr($_GET['position'],0,1) == 'S')
	{
	?>
	<tr>
		<th><label for="common" class="required"><span class="colorRed" title="필수입력항목">*</span><strong>노출 영역설정</strong></label></th>
		<td>
		<ol>
			<li class="opt"><select class="bg_gray" name="common" style="width:245px" title="카테고리 지정">
			<option value="Y" class="blue"
			<?php if($config['common'] == 'Y') { echo(' selected="selected"'); }?>>모든 서브 페이지 공통노출</option>
			<option value="Y" class="red">-------------------------------------------------------</option>
			<?php
			$db->query(" SELECT cate,name FROM `site__` WHERE skin='".$_GET['skin']."' ORDER BY cate ASC ");
			while($sRows = $db->fetch())
			{
				for($i=2;$i<=strlen($sRows['cate'])/3;$i++) { $blank .= "　"; }
				echo('<option value="'.$sRows['cate'].'"');
				if($Rows['cate'] == $sRows['cate'])
				{
					echo(' selected="selected" class="colorBlue"');
				}
				else if(strlen($sRows['cate']) == 3)
				{
					echo(' class="colorRed"');
				}
				echo('>'.$blank.' ('.substr($sRows['cate'],-3).')'.$sRows['name'].'</option>');
				unset($blank);
			}
			?>
			</select></li>
			<li class="opt"><span class="keeping"><input type="checkbox" id="local" name="local" value="Y"<?php if($config['common']=='N'){echo(' checked="checked"');}?> /><label for="local">지역노출</label></span></li>
		</ol>
		</td>
		<td class="small_gray bg_gray">지정된 서브 카테고리별 노출 설정<br />(지역노출: 선택된 카테고리내 하위 동일 노출)</td>
	</tr>
	<?php
	}
	
	$form->addStart('노출 제외설정', 'commonExcept', 1);
	$form->add('input', 'commonExcept', $config['commonExcept'], 'width:310px;','maxlength="100"');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li class="opt"><span>노출을 제외할 카테고리 코드 (하위포함하여 노출제외)</span>');
	$form->addEnd(1);

	$form->addStart('배경 색상', 'bgColor', 1);
	$form->class = "input_color";
	$form->add('input', 'bgColor', $config['bgColor'], 'width:60px;', 'maxlength="7"', 'onclick="colorPickerShow(\'bgColor\', 25);" color="true"');
	$form->addHtml('<li class="opt"><span>" # " 포함하여 입력</span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li class="opt"><span>BOX의 배경색</span>');
	$form->addEnd(1);

	$form->addStart('사이즈', 'width', 1,0,'M');
	$form->addHtml('<li class="opt"><span>가로</span></li>');
	$form->add('input', 'width', $config['width'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px,&nbsp;&nbsp;&nbsp;&nbsp;세로</span></li>');
	$form->add('input', 'height', $config['height'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px</span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>외곽BOX의 가로,세로 사이즈 (스킨 업로드시 사이즈 설정됨)</span>');
	$form->addEnd(1);

	$form->addStart('여백 (상)', 'pdt', 1);
	$form->addHtml('<li class="opt"><span>안쪽</span></li>');
	$form->add('input', 'pdt', $config['pdt'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px, 바깥쪽</span></li>');
	$form->add('input', 'mgt', $config['mgt'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px</span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>스킨의 상단여백 설정</span>');
	$form->addEnd(1);

	$form->addStart('여백 (우)', 'pdr', 1);
	$form->addHtml('<li class="opt"><span>안쪽</span></li>');
	$form->add('input', 'pdr', $config['pdr'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px, 바깥쪽</span></li>');
	$form->add('input', 'mgr', $config['mgr'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px</span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>스킨의 우측여백 설정</span>');
	$form->addEnd(1);

	$form->addStart('여백 (하)', 'pdb', 1);
	$form->addHtml('<li class="opt"><span>안쪽</span></li>');
	$form->add('input', 'pdb', $config['pdb'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px, 바깥쪽</span></li>');
	$form->add('input', 'mgb', $config['mgb'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px</span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>스킨의 하단여백 설정</span>');
	$form->addEnd(1);

	$form->addStart('여백 (좌)', 'pdl', 1);
	$form->addHtml('<li class="opt"><span>안쪽</span></li>');
	$form->add('input', 'pdl', $config['pdl'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px, 바깥쪽</span></li>');
	$form->add('input', 'mgl', $config['mgl'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px</span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>스킨의 좌측여백 설정</span>');
	$form->addEnd(1);

	$form->addStart('타겟 설정', 'target', 1, 0, 'M');
	$form->add('radio', array('_self'=>'부모창열기','_blank'=>'새창열기'), $config['target'], 'color:#000;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>클릭시 창이 열리는 방식설정</span>');
	$form->addEnd(1);

	$form->addStart('노출  설정', 'useHidden', 1, 0, 'M');
	$form->add('radio', array('N'=>'노출함','Y'=>'노출안함'), $Rows['useHidden'], 'color:#000;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>노출 여부 설정</span>');
	$form->addEnd(1);
	?>

	</tbody>
</table>
<div class="pd5 right"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit" onclick="return $.submit(this.form)">위 설정으로 적용</button></span>&nbsp;<span class="btnPack black medium"><a href="javascript:;" onclick="$.dialogRemove();">취소</a></span></div>
</form>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$('#frmCate').validate({onkeyup:function(element){$(element).valid();}});
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
    $("input[type=file]").change(function(){
			var IMG_FORMAT = "\.(gif|jpg|jpge|swf|png)$";
			if((new RegExp(IMG_FORMAT, "i")).test(this.value)) return true;
			alert("이미지 파일만 첨부하실 수 있습니다.");
            this.select();
            document.selection.clear();
	});
});
//]]>
</script>
<?php
require_once __PATH__."/_Admin/include/commonScript.php";
?>
