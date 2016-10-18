<?php
require_once "../../_config.php";
require_once "../include/commonHeader.php";

//디스플레이 등록
if($_POST['type'] == "displayPost")
{
//	넘어온 값과 변수 동기화 및 validCheck
	foreach($_POST AS $key=>$val)
	{
		${$key} = trim($val);
//		$func->validCheck(체크할 값, 항목제목, 항목명, 체크타입)
		if($key == "width") { $func->vaildCheck($val, "유닛의 가로 사이즈", "trim" ,"M"); }
		//if($key == "height") { $func->vaildCheck($val, "유닛의 세로 사이즈", "trim" ,"M"); }
	}
//	공백값
	$db->data['cate'] = $cate;
	$db->data['sort'] = $sort;
	$db->data['position'] = $position;
	$db->data['name'] = $name;
	$db->data['form'] = $form;
	$db->data['listing'] = $listing;
	$db->data['useHidden'] = $useHidden;
	$db->data['config']['module'] = "menu";
	$db->data['config']['common'] = str_replace(" ", null, $common);
	$db->data['config']['common'] = (preg_match("/,$/", $db->data['config']['common'])) ? $db->data['config']['common'] : $db->data['config']['common'].",";
	$db->data['config']['common'] = (preg_match("/^,/", $db->data['config']['common'])) ? $db->data['config']['common'] : ",".$db->data['config']['common'];
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
	$db->data['config']['colorBg'] = $colorBg;
	$db->data['config']['colorLine'] = $colorLine;
	$db->data['config']['rollOver'] = $rollOver;
	$db->data['config'] = serialize($db->data['config']);

	if($db->sqlInsert("display__".$skin, "REPLACE", 0) > 0)
	{
		if(!preg_match('/\/_Admin\//', $_SERVER['HTTP_REFERER']))
		{
			$func->errCfm("Display(Navigation) 유닛정보가 정상적으로 적용되었습니다.", "parent.$.dialogRemove();".PHP_EOL."parent.$.insert('#skinSelector', '".$cfg['droot']."_Admin/modules/displayList.php?type=displayList&mode=design&skin=".$_POST['skin']."&position=".$_POST['position']."');");
		} else
		{
			$func->err("Display(Navigation) 유닛정보가 정상적으로 적용되었습니다.", "parent.$.dialogRemove();".PHP_EOL."parent.$.insert('#tabBody".$_POST['position']."', './modules/displayList.php?type=displayList&skin=".$_POST['skin']."&position=".$_POST['position']."',null,300);");
		}
	}
}
#--- 디스플레이 수정
else if($_GET['type'] == "displayModify" && $_GET['sort'] && $_GET['position'])
{
	$Rows = $db->queryFetch("SELECT * FROM `display__".$_GET['skin']."` WHERE sort='".$_GET['sort']."' AND position='".$_GET['position']."' ORDER BY sort ASC");
	$config = unserialize($Rows['config']);
	$config['common'] = preg_replace('/^,|,$/', null, $config['common']);
	$sort = $Rows['sort'];
}
else
{
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
<input type="hidden" name="form" value="menu" />

<table class="table_list">
	<col width="130">
	<col width="340">
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
	?>
	<tr>
		<th><label for="common" class="required"><span class="colorRed" title="필수입력항목">*</span><strong>카테고리 지정</strong></label></th>
		<td>
		<ol>
			<li class="opt"><select class="bg_gray" id="cate" name="cate" style="width:313px" title="카테고리 지정">
			<option value="" style="color: #990000;">1차 카테고리</option>
			<option value="" style="color: #990000;">-------------------------------------------------------</option>
			<?php
			$db->query(" SELECT cate,name FROM `site__` WHERE skin='".$_GET['skin']."' AND SUBSTRING(cate,1,3)<>'000'  AND SUBSTRING(cate,1,3)<>'' ORDER BY cate ASC ");
			while($sRows = $db->fetch())
			{
				for($i=2;$i<=strlen($sRows['cate'])/3;$i++) { $blank .= "　"; }
				echo('<option value="'.$sRows['cate'].'"');
				if($Rows['cate'] == $sRows['cate'])
				{
					echo(' selected="selected" class="colorRed"');
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
		</ol>
		</td>
		<td class="small_gray bg_gray">지정된 서브 카테고리별 노출 설정</td>
	</tr>
	<?php
	$form->addStart('디스플레이 형태', 'listing', 1, 0, 'M');
	$form->add('select', array('TextSlide'=>'상단 텍스트(슬라이드)'), $Rows['listing'], 'width:313px;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>노출형태 설정 (TAB방식의 경우 TAB으로 연결할 유닛을 같은 TAB방식으로 선택하여 연속등록 해야함)</span>');
	$form->addEnd(1);

	$form->addStart('제목/설명(alt)', 'name', 1, 0, 'M');
	$form->add('textarea', 'name', $Rows['name'], 'width:310px; height:70px;','maxlength="50"');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>한글 타이틀 및 설명 (웹접근성 향상 문제로 반드시 이미지나 플래시의 타이틀이나 설명을 넣어야 함 (100자이내)</span>');
	$form->addEnd(1);

	$form->addStart('노출 제외설정', 'common', 1);
	$form->add('input', 'common', $config['common'], 'width:310px;','maxlength="100"');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li class="opt"><span>노출을 제외할 카테고리 코드 (하위포함하여 노출제외)</span>');
	$form->addEnd(1);

	$form->addStart('사이즈', 'width', 1, 0, 'M');
	$form->addHtml('<li class="opt"><span>가로</span></li>');
	$form->add('input', 'width', $config['width'], 'width:40px; text-align:center;','maxlength="6"');
	$form->addHtml('<li class="opt"><span>px,&nbsp;&nbsp;&nbsp;&nbsp;세로</span></li>');
	$form->add('input', 'height', $config['height'], 'width:40px; text-align:center;','maxlength="6"');
	$form->addHtml('<li class="opt"><span>px</span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>외곽BOX의 가로,세로 사이즈 (스킨 업로드시 사이즈 설정됨)</span>');
	$form->addEnd(1);

	$form->addStart('여백 (상)', 'pdt', 1);
	$form->addHtml('<li class="opt"><span>안쪽</span></li>');
	$form->add('input', 'pdt', $config['pdt'], 'width:40px; text-align:center;','maxlength="6"');
	$form->addHtml('<li class="opt"><span>px, 바깥쪽</span></li>');
	$form->add('input', 'mgt', $config['mgt'], 'width:40px; text-align:center;','maxlength="6"');
	$form->addHtml('<li class="opt"><span>px</span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>스킨의 상단여백 설정</span>');
	$form->addEnd(1);

	$form->addStart('여백 (우)', 'pdr', 1);
	$form->addHtml('<li class="opt"><span>안쪽</span></li>');
	$form->add('input', 'pdr', $config['pdr'], 'width:40px; text-align:center;','maxlength="6"');
	$form->addHtml('<li class="opt"><span>px, 바깥쪽</span></li>');
	$form->add('input', 'mgr', $config['mgr'], 'width:40px; text-align:center;','maxlength="6"');
	$form->addHtml('<li class="opt"><span>px</span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>스킨의 우측여백 설정</span>');
	$form->addEnd(1);

	$form->addStart('여백 (하)', 'pdb', 1);
	$form->addHtml('<li class="opt"><span>안쪽</span></li>');
	$form->add('input', 'pdb', $config['pdb'], 'width:40px; text-align:center;','maxlength="6"');
	$form->addHtml('<li class="opt"><span>px, 바깥쪽</span></li>');
	$form->add('input', 'mgb', $config['mgb'], 'width:40px; text-align:center;','maxlength="6"');
	$form->addHtml('<li class="opt"><span>px</span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>스킨의 하단여백 설정</span>');
	$form->addEnd(1);

	$form->addStart('여백 (좌)', 'pdl', 1);
	$form->addHtml('<li class="opt"><span>안쪽</span></li>');
	$form->add('input', 'pdl', $config['pdl'], 'width:40px; text-align:center;','maxlength="6"');
	$form->addHtml('<li class="opt"><span>px, 바깥쪽</span></li>');
	$form->add('input', 'mgl', $config['mgl'], 'width:40px; text-align:center;','maxlength="6"');
	$form->addHtml('<li class="opt"><span>px</span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>스킨의 좌측여백 설정</span>');
	$form->addEnd(1);

	$form->addStart('노출  설정', 'useHidden', 1, 0, 'M');
	$form->add('radio', array('N'=>'노출함','Y'=>'노출안함'), $Rows['useHidden'], 'color:#000;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>노출 여부 설정</span>');
	$form->addEnd(1);
	?>
	</tbody>
</table>
<div class="pd5 right"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit">위 설정으로 적용</button></span>&nbsp;<span class="btnPack black medium"><a href="javascript:;" onclick="$.dialogRemove();">취소</a></span></div>
</form>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$('#frmCate').validate({onkeyup:function(element){$(element).valid();});
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
//]]>
</script>
<?php
require_once __PATH__."/_Admin/include/commonScript.php";
?>
