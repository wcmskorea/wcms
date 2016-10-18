<?php
require_once "../../../_config.php";
if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER'])) { $func->err("['경고!'] 정상적인 접근이 아닙니다."); }

if($_POST['type'] == 'layoutPost')
{

//	넘어온 값과 변수 동기화 및 validCheck
	foreach($_POST AS $key=>$val)
	{
		$cfg['site'][$key] = trim($val);
//		$func->validCheck(체크할 값, 항목제목, 항목명, 체크타입)
		if($key == "lang")        $func->vaildCheck($val, "언어설정", null, "M");
		if($key == "frame")       $func->vaildCheck($val, "프레임셋", null, "M");
		if($key == "align")       $func->vaildCheck($val, "사이트 정렬", null, "M");
		if($key == "navGnb")      $func->vaildCheck($val, "글로벌 메뉴", null, "M");
		if($key == "navUnb")      $func->vaildCheck($val, "다국어 메뉴", null, "M");
		if($key == "navQnb")      $func->vaildCheck($val, "퀵 메뉴", null, "M");
		if($key == "openSkin")    $func->vaildCheck($val, "스킨정보", null, "M");
		if($key == "size")        $func->vaildCheck($val, "전체폭", "num", "M");
		if($key == "sizeMsnb")    $func->vaildCheck($val, "메인좌폭", "num", "M");
		if($key == "sizeMside")   $func->vaildCheck($val, "메인우폭", "num", "M");
		if($key == "sizeSsnb")    $func->vaildCheck($val, "서브좌폭", "num", "M");
		if($key == "sizeSside")   $func->vaildCheck($val, "서브우폭", "num", "M");
	}

	$cfg['site']['info'] = $cfg['timeip'];

	require_once __PATH__."_Lib/classConfig.php";
	$config = new Config($cfg);

	$config->configMake(array('type','uri'));
	$config->configSave(__PATH__."_Site/".__DB__."/".$_POST['skin']."/cache/config.ini.php");

//	데이터 등록 처리
	if($db->data['frame'] == 'Y')
	{
		$func->err("사이트 환경설정이  정상적으로 적용되었습니다.", "top.location.replace('".$cfg['droot']."');");
	}
	else
	{
		$func->err("사이트 환경설정이  정상적으로 적용되었습니다.", "location.replace('".urldecode(__URI__)."');");
	}
}
else
{
	$cfg = array_merge($cfg, (array)parse_ini_file(__PATH__."/_Site/".__DB__."/".$_GET['skin']."/cache/config.ini.php", true));
}

?>
<form id="frm01" name="frm01" method="post" action="<?php echo($cfg['droot']);?>addon/system/manage/layout.php" enctype="multipart/form-data">
<input type="hidden" name="type" value="layoutPost" />
<input type="hidden" name="skin" value="<?php echo($_GET['skin']);?>" />
<input type="hidden" name="uri" value="<?php echo(__URI__);?>" />

<div class="menu_black">
	<p class="bold"><?php echo($cfg['skinName'][$_GET['skin']]);?> 환경 설정하기</p>
</div>
<div class="pd5 right"><span class="btnPack small icon strong"><span class="check"></span><button type="submit">적용하기</button></span>&nbsp;<span class="btnPack black small"><a href="javascript:;" onclick="$('#skinSelector').slideUp('fast');">창 닫기</a></span></div>
<table class="table_list" style="width: 100%;">
	<caption>사이트 정보 설정</caption>
	<col width="140">
	<col width="165">
	<col>
	<tbody>
	<?php
		$form = new Form('table');

		$form->addStart('기본언어 설정', 'lang', 1, 0, 'M');
		$form->name = array('kr'=>'한국어','en'=>'영어','jp'=>'일어','cn'=>'중국어');
		$form->add('select', $form->name, $cfg['site']['lang'], 'width:150px;');
		$form->addEnd(0);
		$form->addHtml('<td class="small_gray bg_gray">사이트 기본 언어를 설정합니다.</td>');
		$form->addEnd(1);

		$form->addStart('프레임셋 설정', 'frame', 1, 0, 'M');
		$form->add('select', array('N'=>'사용안함','Y'=>'사용함'), $cfg['site']['frame'], 'width:150px;');
		$form->addEnd(0);
		$form->addHtml('<td class="small_gray bg_gray">Index를 FrameSet으로 구성할지 설정합니다.</td>');
		$form->addEnd(1);

		$form->addStart('사이트 정렬', 'align', 1, 0, 'M');
		$form->add('select', array('left'=>'왼쪽 정렬','center'=>'가운데 정렬'), $cfg['site']['align'], 'width:150px;');
		$form->addEnd(0);
		$form->addHtml('<td class="small_gray bg_gray">사이트의 정렬을 설정합니다.</td>');
		$form->addEnd(1);

		$form->addStart('글로벌 네비게이션', 'navGnb', 1, 0, 'M');
		$form->add('select', array('Y'=>'노출함','N'=>'노출안함'), $cfg['site']['navGnb'], 'width:150px;');
		$form->addEnd(0);
		$form->addHtml('<td class="small_gray bg_gray">최상단 Global Navigation의 노출 여부를 설정합니다.</td>');
		$form->addEnd(1);

		$form->addStart('퀵 스크롤', 'navQnb', 1, 0, 'M');
		$form->add('select', array('N'=>'노출안함','Y'=>'노출함'), $cfg['site']['navQnb'], 'width:150px;');
		$form->addEnd(0);
		$form->addHtml('<td class="small_gray bg_gray">우측에 스크롤 메뉴 노출 여부를 설정합니다.</td>');
		$form->addEnd(1);

		$form->addStart('스킨패턴 노출', 'openSkin', 1, 0, 'M');
		$form->add('select', array('Y'=>'노출함','N'=>'노출안함'), $cfg['site']['openSkin'], 'width:150px;');
		$form->addEnd(0);
		$form->addHtml('<td class="small_gray bg_gray">스킨정보 노출 여부를 설정합니다.</td>');
		$form->addEnd(1);

		$form->addStart('전체 폭', 'size', 1, 0, 'M');
		$form->add('input', 'size', $cfg['site']['size'], 'width:30px;');
		$form->addHtml('<li class="opt">px (#wrap)</li>');
		$form->addEnd(0);
		$form->addHtml('<td class="small_gray bg_gray">사이트의 총 넓이값을 설정합니다.</td>');
		$form->addEnd(1);

		$form->addStart('메인(좌)폭', 'sizeMsnb', 1, 0, 'M');
		$form->add('input', 'sizeMsnb', $cfg['site']['sizeMsnb'], 'width:30px;');
		$form->addHtml('<li class="opt">px (.snb)</li>');
		$form->addEnd(0);
		$form->addHtml('<td class="small_gray bg_gray">"Container > snb"의 넓이값을 설정합니다.</td>');
		$form->addEnd(1);

		$form->addStart('메인(우)폭', 'sizeMside', 1, 0, 'M');
		$form->add('input', 'sizeMside', $cfg['site']['sizeMside'], 'width:30px;');
		$form->addHtml('<li class="opt">px (.side)</li>');
		$form->addEnd(0);
		$form->addHtml('<td class="small_gray bg_gray">"Container > side"의 넓이값을 설정합니다.</td>');
		$form->addEnd(1);

		$form->addStart('서브(좌)폭', 'sizeSsnb', 1, 0, 'M');
		$form->add('input', 'sizeSsnb', $cfg['site']['sizeSsnb'], 'width:30px;');
		$form->addHtml('<li class="opt">px (.snb)</li>');
		$form->addEnd(0);
		$form->addHtml('<td class="small_gray bg_gray">"Sub Container > snb"의 넓이값을 설정합니다.</td>');
		$form->addEnd(1);

		$form->addStart('서브(우)폭', 'sizeSside', 1, 0, 'M');
		$form->add('input', 'sizeSside', $cfg['site']['sizeSside'], 'width:30px;');
		$form->addHtml('<li class="opt">px (.side)</li>');
		$form->addEnd(0);
		$form->addHtml('<td class="small_gray bg_gray">"Sub Container > side"의 넓이값을 설정합니다.</td>');
		$form->addEnd(1);

		$form->addStart('내용(좌)공백', 'padContentLeft', 1, 0, 'M');
		$form->add('input', 'padContentLeft', $cfg['site']['padContentLeft'], 'width:30px; text-align:center;');
		$form->addHtml('<li class="opt">px (#module)</li>');
		$form->addEnd(0);
		$form->addHtml('<td class="small_gray bg_gray">"Sub Container > module"의 좌측 공백을 설정합니다.</td>');
		$form->addEnd(1);

		$form->addStart('내용(우)공백', 'padContentRight', 1, 0, 'M');
		$form->add('input', 'padContentRight', $cfg['site']['padContentRight'], 'width:30px; text-align:center;');
		$form->addHtml('<li class="opt">px (#module)</li>');
		$form->addEnd(0);
		$form->addHtml('<td class="small_gray bg_gray">"Sub Container > module"의 우측 공백을 설정합니다.</td>');
		$form->addEnd(1);

	?>
</table>
</form>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function() {
	$("input[type=text],input[type=password],textarea").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active");});
});
$(document).keypress(function(e){if(e.which == 27) $("#skinSelector").slideUp("fast");});
//]]>
</script>
