<?php
require_once "../../../_config.php";
require_once __PATH__."/_Admin/include/commonHeader.php";

//디스플레이 등록
if($_POST['type'] == "displayPost")
{
	//리퍼러 체크
	$func->checkRefer("POST");

	foreach($_POST AS $key=>$val)
	{
		${$key} = trim($val);
//		$func->validCheck(체크할 값, 항목제목, 항목명, 체크타입)
		if($key == "cate") { $func->vaildCheck($val, "모듈 카테고리", "trim", "M"); }
		if($key == "form") { $func->vaildCheck($val, "유닛의 형태", "trim", "M"); }
		if($key == "width") { $func->vaildCheck($val, "유닛의 넓이값", "trim", "M"); }
	}

	$db->data['sort']	= $sort;
	$db->data['position'] = $position;
	$db->data['cate'] = $cate;
	$db->data['name'] = $name;
	$db->data['form'] = $form;
	$db->data['listing'] = $listing;
	$db->data['useHidden'] = $useHidden;
	$db->data['config']['share'] = $db->queryFetchOne(" SELECT share FROM `mdDocument__` WHERE skin='".$skin."' AND cate='".$cate."' ");
	$db->data['config']['module'] = "mdDocument";
	$db->data['config']['common'] = ($common) ? $common : "Y";
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
	$db->data['config']['imgWidth'] = $imgWidth ? $imgWidth : '0';
	$db->data['config']['imgHeight'] = $imgHeight ? $imgHeight : '0';
	$db->data['config']['docCount'] = $docCount;
	$db->data['config']['sortType'] = $sortType;
	$db->data['config']['docPad']  = $docPad ? $docPad : '0';
	$db->data['config']['docPadT'] = $docPadT ? $docPadT : '0';
	$db->data['config']['docType'] = $docType;
	$db->data['config']['docStart'] = $docStart;
	$db->data['config']['docTicker'] = $docTicker;
	$db->data['config']['docSpeed'] = $docSpeed;
	$db->data['config']['docDelay'] = $docDelay;
	$db->data['config']['docEasing'] = $docEasing;
	$db->data['config']['docBtn'] = $docBtn;
	$db->data['config']['docPager'] = $docPager;
	$db->data['config']['useUnion'] = $useUnion;
	$db->data['config']['useData'] = $useData;
	$db->data['config']['useNotice'] = ($useNotice) ? 'Y' : 'N';
	$db->data['config']['useTitle'] = $useTitle;
	$db->data['config']['useDate'] = $useDate;
	$db->data['config']['useMore'] = $useMore;
	$db->data['config']['useHidden'] = $useHidden;
	$db->data['config']['cutSubject'] = $cutSubject;
	$db->data['config']['cutContent'] = $cutContent;
	$db->data['config']['colorBg'] = $colorBg;
	$db->data['config']['docLink'] = $docLink;
	$db->data['config']['useCateName'] = $useCateName;
	$db->data['config'] = serialize($db->data['config']);

	if($db->sqlInsert("display__".$skin, "REPLACE", 0) > 0)
	{
		if(!preg_match('/\/_Admin\//', $_SERVER['HTTP_REFERER']))
		{
			$func->errCfm("Display(문서모듈) 위젯 정보가 정상적으로 적용되었습니다.", "parent.$.dialogRemove();".PHP_EOL."parent.$.insert('#skinSelector', '".$cfg['droot']."_Admin/modules/displayList.php?type=displayList&mode=design&skin=".$_POST['skin']."&position=".$_POST['position']."');");
		}
		else
		{
			$func->err("Display(문서모듈) 위젯 정보가 정상적으로 적용되었습니다.", "parent.$.dialogRemove();".PHP_EOL."parent.$.insert('#tabBody".$_POST['position']."', './modules/displayList.php?type=displayList&skin=".$_POST['skin']."&position=".$position."',null,300);");
		}
	}
}
//디스플레이 수정
else if($_GET['type'] == "displayModify" && $_GET['sort'] && $_GET['position'])
{
	//리퍼러 체크
	$func->checkRefer("GET");

	$Rows 	= $db->queryFetch(" SELECT * FROM `display__".$_GET['skin']."` WHERE sort='".$_GET['sort']."' AND position='".$_GET['position']."' ORDER BY sort ASC ");
	$config = unserialize($Rows['config']);
	$sort 	= $Rows['sort'];
}
else
{
	//리퍼러 체크
	$func->checkRefer("GET");

	$sort 	= $db->queryFetchOne(" SELECT MAX(sort) FROM `display__".$_GET['skin']."` WHERE position='".$_GET['position']."' ") + 1;
	$config['imgWidth'] = 80;
	$config['imgHeight'] = 60;
	$config['docCount']	= 5;
	$config['docPad'] 	= 5;
	$config['docPadT'] 	= 0;
	$config['docType']	= 'false';
	$config['useCut']	= 30;
	$config['cutSubject'] = 30;
	$config['cutContent'] = 30;
}
?>
<div id="messageBox"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/delete.gif" /></spna>&nbsp;<strong>Message :</strong>&nbsp;&nbsp;&nbsp;</div>
<script type="text/javascript" src="/common/js/jquery.color.js"></script>
<form id="frmCate" name="frmCate" method="post" enctype="multipart/form-data" action="<?php echo($_SERVER['PHP_SELF']);?>" target="hdFrame">
<input type="hidden" name="type" value="displayPost" />
<input type="hidden" name="skin" value="<?php echo($_GET['skin']);?>" />
<input type="hidden" name="position" value="<?php echo($_GET['position']);?>" />
<input type="hidden" name="sort" value="<?php echo($sort);?>" />

<table class="table_list" summary="Display(게시판관리 모듈) 유닛설정">
	<caption>Display(게시판관리 모듈) 유닛설정</caption>
	<col width="130">
	<col width="330">
	<col>
	<thead>
		<tr>
			<th class="first"><p class="center"><span class="mg2">항 목</span></p></th>
			<th><p class="center"><span class="mg2">유닛기능 설정</span></p></th>
			<th><p class="center"><span class="mg2">도움말</span></p></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$form = new Form('table');

	if(substr($_GET['position'],0,1) == 'S')
	{
	?>
	<tr>
		<th><label for="common" class="required"><span class="colorRed" title="필수입력항목">*</span><strong>카테고리 지정</strong></label></th>
		<td>
		<ol>
			<li class="opt"><select class="bg_gray" id="common" name="common" style="width:305px" title="카테고리 지정">
			<option value="Y" style="color: #990000;">모든 서브페이지 노출</option>
			<option value="Y" style="color: #990000;">-------------------------------------------------------</option>
			<?php
			$db->query(" SELECT cate,name FROM `site__` WHERE SUBSTRING(cate,1,3)<>'000' ORDER BY cate ASC ");
			while($sRows = $db->fetch())
			{
				for($i=2;$i<=strlen($sRows['cate'])/3;$i++) { $blank .= "　"; }
				echo('<option value="'.$sRows['cate'].'"');
				if($config['common'] == $sRows['cate'])
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
	}
	?>
	<tr>
		<th><label for="cateCode1" class="required"><span class="colorRed" title="필수입력항목">*</span><strong>연결 모듈 선택</strong></label></th>
		<td>
		<ol>
			<li class="opt"><select id="cateCode1" class="bg_gray" name="cate" title="상위 카테고리" style="width:305px;"	onchange="$('#name').val(this.options[this.selectedIndex].text);">
			<option value="">모듈이 설정된 카테고리를 선택하세요</option>
			<option value="">---------------------------------------------------------------------------</option>
			<?php
			$db->query(" SELECT cate,name FROM `site__` WHERE skin='".$_GET['skin']."' AND SUBSTRING(cate,1,3)<>'000' AND mode='mdDocument' ORDER BY cate ASC ");
			while($sRows = $db->fetch())
			{
				$len = 9 - strlen($sRows['cate']);
				for($i=1;$i<=$len;$i++) { $blank .= "_"; }
				$catePrint = $sRows['cate'].$blank;
				echo('<option value="'.$sRows['cate'].'"');
				if($Rows['cate'] === $sRows['cate'])
				{
					echo(' selected="selected" class="colorRed"');
				}
				else if(strlen($sRows['cate']) == 3)
				{
					echo(' style="color:#990000;"');
				}
				echo('>'.$catePrint.' '.$sRows['name'].'</option>');
				unset($blank, $catePrint);
			}
			?>
			</select></li>
		</ol>
		</td>
		<td class="small_gray bg_gray">게시판 모듈이 설정된 카테고리 선택</td>
	</tr>
	<?php
	$form->addStart('타이틀명', 'name', 1, 0, 'M');
	$form->add('input', 'name', $Rows['name'], 'width:300px;','maxlength="50"');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>한글 타이틀 및 설명</span>');
	$form->addEnd(1);

	$form->addStart('디스플레이 형태', 'form', 1, 0, 'M');
	$form->add('select', array('box'=>'기본형','tab1'=>'TAB(그룹1)','tab2'=>'TAB(그룹2)','tab3'=>'TAB(그룹3)','tab4'=>'TAB(그룹4)','tab5'=>'TAB(그룹5)','frame'=>'FRAME',), $Rows['form'], 'width:170px;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li>노출형태 설정 (TAB방식의 경우 TAB으로 연결할 유닛을 같은 TAB방식으로 선택하여 연속등록 해야함)');
	$form->addEnd(1);

	$form->addStart('리스트 타입', 'listing', 1, 0, 'M');
	$form->add('select', array('List'=>'목록·리스트','Gallery'=>'갤러리·앨범'), $Rows['listing'], 'width:170px;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>유닛에 노출될 목록의 형태 설정</span>');
	$form->addEnd(1);

	$form->addStart('배경 색상', 'colorBg', 1);
	$form->class = "input_color";
	$form->add('input', 'colorBg', $config['colorBg'], 'width:60px;', 'maxlength="7"', 'onclick="colorPickerShow(\'colorBg\', 25);" color="true"');
	$form->addHtml('<li class="opt"><span>" # " 포함하여 입력</span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li class="opt"><span>BOX의 배경색</span>');
	$form->addEnd(1);

	$form->addStart('사이즈', 'width', 1, 0, 'M');
	$form->addHtml('<li class="opt"><span>가로</span></li>');
	$form->add('input', 'width', $config['width'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px,&nbsp;&nbsp;&nbsp;&nbsp;세로</span></li>');
	$form->id = 'height';
	$form->add('input', 'height', $config['height'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px</span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>외곽BOX의 가로,세로 사이즈 (스킨 업로드시 사이즈 설정됨)</span>');
	$form->addEnd(1);

	$form->addStart('여백 (상)', 'pdt', 1);
	$form->addHtml('<li class="opt"><span>안쪽</span></li>');
	$form->add('input', 'pdt', $config['pdt'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px, 바깥쪽</span></li>');
	$form->id = 'mgt';
	$form->add('input', 'mgt', $config['mgt'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px</span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>스킨의 상단여백 설정</span>');
	$form->addEnd(1);

	$form->addStart('여백 (우)', 'pdr', 1);
	$form->addHtml('<li class="opt"><span>안쪽</span></li>');
	$form->add('input', 'pdr', $config['pdr'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px, 바깥쪽</span></li>');
	$form->id = 'mgr';
	$form->add('input', 'mgr', $config['mgr'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px</span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>스킨의 우측여백 설정</span>');
	$form->addEnd(1);

	$form->addStart('여백 (하)', 'pdb', 1);
	$form->addHtml('<li class="opt"><span>안쪽</span></li>');
	$form->add('input', 'pdb', $config['pdb'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px, 바깥쪽</span></li>');
	$form->id = 'mgb';
	$form->add('input', 'mgb', $config['mgb'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px</span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>스킨의 하단여백 설정</span>');
	$form->addEnd(1);

	$form->addStart('여백 (좌)', 'pdl', 1);
	$form->addHtml('<li class="opt"><span>안쪽</span></li>');
	$form->add('input', 'pdl', $config['pdl'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px, 바깥쪽</span></li>');
	$form->id = 'mgl';
	$form->add('input', 'mgl', $config['mgl'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px</span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>스킨의 좌측여백 설정</span>');
	$form->addEnd(1);

	$form->addStart('이미지 크기', 'imgWidth', 1, 0, 'M');
	$form->addHtml('<li class="opt"><span>넓이</span></li>');
	$form->add('input', 'imgWidth', $config['imgWidth'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px,&nbsp;&nbsp;&nbsp;&nbsp;높이</span></li>');
	$form->id = 'imgHeight';
	$form->add('input', 'imgHeight', $config['imgHeight'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px</span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>목록에 표시되는 손톱 이미지의 사이즈 설정</span>');
	$form->addEnd(1);

	$form->addStart('게시물 노출', 'docCount', 1, 0, 'M');
	$form->addHtml('<li class="opt"><span>갯수</span></li>');
	$form->add('input', 'docCount', $config['docCount'], 'width:40px; text-align:center;','digits="true" maxlength="2"');
	$form->addHtml('<li class="opt"><span>,&nbsp;여백(좌)</span></li>');
	$form->id = 'docPad';
	$form->add('input', 'docPad', $config['docPad'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px,&nbsp;여백(상)</span></li>');	// 스킨 여백(상) 추가(2012-10-31)
	$form->id = 'docPadT';
	$form->add('input', 'docPadT', $config['docPadT'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt"><span>px</span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>스킨의 좌측여백 설정</span>');
	$form->addEnd(1);

	$form->addStart('글자수 제한', 'cutSubject', 1, 0, 'M');
	$form->addHtml('<li class="opt"><span>제목</span></li>');
	$form->add('input', 'cutSubject', $config['cutSubject'], 'width:30px; text-align:center;','digits="true" maxlength="3"');
	$form->addHtml('<li class="opt"><span>자,&nbsp;&nbsp;요약</span></li>');
	$form->id = 'cutContent';
	$form->add('input', 'cutContent', $config['cutContent'], 'width:30px; text-align:center;','digits="true" maxlength="3"');
	$form->addHtml('<li class="opt"><span>자</span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>게시물의 제목 및 요약글 노출 글자수 설정</span>');
	$form->addEnd(1);

	$form->addStart('슬라이드 형태', 'docType', 1, 0, 'M');
	$form->add('radio', array('false'=>'안함','horizontal'=>'가로형','vertical'=>'세로형','fade'=>'페이드'), $config['docType'], 'width:170px;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>슬라이딩 형태 선택</span>');
	$form->addEnd(1);	

	$form->addStart('슬라이드 티커', 'docTicker', 1, 0, 'M');
	$form->add('select', array('false'=>'사용안함','true'=>'사용'), $config['docTicker'], 'width:170px;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>슬라이드가 Ticker모드로 슬라이딩</span>');
	$form->addEnd(1);	

	$form->addStart('슬라이드 시작', 'docStart', 1, 0, 'N');
	$form->add('select', array('true'=>'자동','false'=>'수동'), $config['docStart'], 'width:170px;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>슬라이드 자동시작 설정</span>');
	$form->addEnd(1);

	$form->addStart('슬라이드 속도', 'docSpeed', 1, 0, 'N');
	$form->add('select', array('1000'=>'X1 배속','500'=>'X2 배속','250'=>'X3 배속'), $config['docSpeed'], 'width:170px;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>슬라이드시 이동속도 설정</span>');
	$form->addEnd(1);

	$form->addStart('슬라이드 지연', 'docDelay', 1, 0, 'N');
	$form->add('select', array('1000'=>'1초 지연','2000'=>'2초 지연','3000'=>'3초 지연','4000'=>'4초 지연','5000'=>'5초 지연'), $config['docDelay'], 'width:170px;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>슬라이드시 이동 지연시간 설정</span>');
	$form->addEnd(1);
	
	$form->addStart('하위문서 포함', 'useUnion', 1, 0, 'M');
	$form->add('radio', array('N'=>'포함안함','Y'=>'포함함'), $config['useUnion'], 'color:#000;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>선택 카테고리내 하위의 문서(게시물)까지 포함 여부 설정</span>');
	$form->addEnd(1);

	$form->addStart('리스트 순서', 'sortType', 1, 0, 'M');
	$form->add('radio', array('idx'=>'등록순', 'regDate'=>'등록일','readCount'=>'조회순'), $config['sortType'], 'color:black;');
	$form->addHtml('<li class="opt"><input type="checkbox" name="useNotice" id="useNotice" value="Y"'.($config['useNotice']=='Y' ? ' checked=checked' : '').' class="input_check" style="color:black;" /> <label for="useNotice">공지글만</label></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray">게시물 리스트 노출 순서설정</td>');
	$form->addEnd(1);

	$form->addStart('데이터 노출', 'useData', 1, 0, 'M');
	$form->add('radio', array('all'=>'모두', 'image'=>'이미지만', 'text'=>'텍스트만'), $config['useData'], 'color:black;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">게시물이 노출될시 원하는 유형만 노출 설정</td>');
	$form->addEnd(1);

	$form->addStart('타이틀 노출', 'useTitle', 1, 0, 'M');
	$form->add('radio', array('text'=>'노출(텍스트)','image'=>'노출(이미지)','N'=>'노출안함'), $config['useTitle'], 'color:#000;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>유닛의 타이틀 노출 설정</span>');
	$form->addEnd(1);

	$form->addStart('카테고리명 노출', 'useCateName', 1, 0, 'M');
	$form->add('radio', array('Y'=>'노출함','N'=>'노출안함'), $config['useCateName'], 'color:#000;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>유닛의 타이틀앞 카테고리명 노출 설정</span>');
	$form->addEnd(1);

	$form->addStart('날짜 노출', 'useDate', 1, 0, 'M');
	$form->add('radio', array('Y'=>'노출함','N'=>'노출안함'), $config['useDate'], 'color:#000;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>게시물의 등록날짜 노출 설정</span>');
	$form->addEnd(1);

	$form->addStart('More 노출', 'useMore', 1, 0, 'M');
	$form->add('radio', array('Y'=>'노출함','N'=>'노출안함'), $config['useMore'], 'color:#000;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>더보기(More) 버튼 노출 설정</span>');
	$form->addEnd(1);

	$form->addStart('위젯 노출', 'useHidden', 1, 0, 'M');
	$form->add('radio', array('N'=>'노출함','Y'=>'노출안함'), $Rows['useHidden'], 'color:#000;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>해당 위젯을 노출할지 여부 설정</span>');
	$form->addEnd(1);

	//내용클릭시 이동위치
	$form->addStart('게시물 링크', 'docLink', 1, 0, 'M');
	$form->add('radio', array('V'=>'뷰페이지','L'=>'리스트'), $config['docLink'], 'color:#000;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>게시물 클릭시 이동할 대상 설정</span>');
	$form->addEnd(1);
	?>
</table>
<div class="pd5 right"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit" onclick="return $.submit(this.form)">위 설정으로 적용</button></span>&nbsp;<span class="btnPack black medium"><a href="javascript:;" onclick="$.dialogRemove();">취소</a></span></div>
</form>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$("input[type=text],input[type=password],textarea").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active");});
	$('#frmCate').validate({onkeyup:function(element){$(element).valid();},errorLabelContainer:"#messageBox"});
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
//]]>
</script>
