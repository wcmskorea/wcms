<?php
/**
 * 카테고리별 등록/수정
 * Last (2008.10.06 : 이성준)
 */
require_once "../include/commonHeader.php";

if($_POST['type'] == "cateRegPost")
{
	//넘어온 값과 변수 동기화 및 validCheck
	foreach($_POST AS $key=>$val)
	{
		$db->data[$key] = trim($val);
		if($key == "cateCode2") { $func->vaildCheck($val, "카테고리 코드", $key, "num" ,"M"); }
		if($key == "name") { $func->vaildCheck($val, "카테고리명", $key ,"M"); }
	}

	$db->data['cate'] = $db->data['cateCode1'].trim($db->data['cateCode2']);
	if(strlen($db->data['cate'])%3 != 0) { $func->err("코드는 3배수(3,6,9...)자리수로 작성하셔야 합니다"); }

	//데이터 정리
	$db->data['mode']		= ($db->data['url'])? "url" : $db->data['module'];
	$db->data['mode']		= (!$db->data['mode']) ? "mdDocument" : $db->data['mode'];
	$db->data['xml']		= $db->data['xml1'].",".$db->data['xml2'];
	$db->data['upDate']	= time();

	//순서 정리
	if(strlen($db->data['cate']) == 3)
	{
		$db->data['sort'] = ($db->queryFetchOne(" SELECT MAX(sort) FROM `site__` WHERE skin='".$db->data['skin']."' AND LENGTH(cate)='".strlen($db->data['cate'])."' ")) + 1;
	}
	else
	{
		$db->data['sort'] = ($db->queryFetchOne(" SELECT MAX(sort) FROM `site__` WHERE skin='".$db->data['skin']."' AND LENGTH(cate)='".strlen($db->data['cate'])."' AND SUBSTRING(cate,1,".intval(strlen($db->data['cate'])-3).")='".substr($db->data['cate'],0,strlen($db->data['cate'])-3)."' ")) + 1;
	}

	//카테고리 정보
	if($db->data['cated'])
	{
		$Rows 								= $db->queryFetch(" SELECT * FROM `site__` WHERE skin='".$db->data['skin']."' AND cate='".$db->data['cated']."' ");
		$db->data['perm']			= (!$Rows) ? "2,99,99,3,3" : $Rows['perm'];
		$db->data['permLmt']	= (!$Rows) ? "U,U,U,U,U" : $Rows['permLmt'];
		$db->data['sort']			= ($Rows['sort'])	? $Rows['sort'] : $db->data['sort'];

		//모듈이 연결되어있고, 카테고리가 변경되었을 경우
		if($Rows['mode'] && $db->data['cate'] != $Rows['cate'])
		{
			//$db->query(" SHOW TABLES LIKE '".$Rows['mode']."__%' ");
			if($db->checkTable($Rows['mode']."__")) {
				$db->query(" UPDATE `".$Rows['mode']."__` SET cate=REPLACE(cate,'".$db->data['cated']."','".$db->data['cate']."') WHERE skin='".$db->data['skin']."' AND cate like '".$db->data['cated']."%' ", 2);
			}
			$db->query(" UPDATE `site__` SET cate=REPLACE(cate,'".$db->data['cated']."','".$db->data['cate']."') WHERE skin='".$db->data['skin']."' AND cate like '".$db->data['cated']."%' ");
			$db->query(" OPTIMIZE TABLES `site__` ");
		}
	}

	//쿼리 실행
	$query = ($db->data['cated']) ? "REPLACE" : "INSERT";
	if($db->sqlInsert("site__", $query, 0) > 0)
	{
		if(!$db->data['cated'])
		{
			//문서.게시물 기본환경설정 적용
			$db->query(" REPLACE INTO `mdDocument__` (`skin`,`cate`, `config`, `contentAdd`) VALUES ('".$db->data['skin']."','".$db->data['cate']."', 'a:15:{s:4:\"list\";s:4:\"Page\";s:8:\"listView\";s:1:\"N\";s:10:\"listHcount\";s:1:\"1\";s:10:\"listVcount\";s:1:\"1\";s:10:\"cutSubject\";s:0:\"\";s:11:\"uploadCount\";s:1:\"0\";s:10:\"uploadType\";s:5:\"Basic\";s:9:\"recommand\";s:1:\"0\";s:7:\"comment\";s:1:\"N\";s:3:\"sms\";s:1:\"N\";s:9:\"thumbType\";s:3:\"4,3\";s:10:\"thumbSsize\";s:0:\"\";s:10:\"thumbMsize\";s:0:\"\";s:10:\"thumbBsize\";s:0:\"\";s:8:\"division\";s:0:\"\";}', 'a:6:{s:12:\"opt_division\";s:1:\"N\";s:11:\"opt_subject\";s:1:\"M\";s:10:\"opt_writer\";s:1:\"M\";s:9:\"opt_email\";s:1:\"Y\";s:9:\"opt_phone\";s:1:\"N\";s:13:\"opt_agreement\";s:1:\"N\";}') ");

			//신규 등록시 하위 카테고리 갯수 업데이트
			if(strlen($db->data['cate']) > 3) { $db->query(" UPDATE `site__` SET child=child+'1' WHERE skin='".$db->data['skin']."' AND cate='".$db->data['cate']."' "); }
		}

		//하위 카테고리 스킨동기화 query
		$query = "skin='".$db->data['skin']."'";

		//하위 모듈 동기화 query
		if($db->data['sameUnder'] == 'Y') { $query .= ",mode='".$db->data['mode']."'"; }

		//동기화 실행
		$db->query(" UPDATE `site__` SET ".$query." WHERE skin='".$db->data['skin']."' AND cate like '".$db->data['cate']."%' ");

		//카테고리 수정시 로그 생성
		if($db->data['cated'])
		{
			$func->setLog(__FILE__, "카테고리 (".$db->data['cated']."->".$db->data['cate'].")수정 성공");
		}

		//XML 업데이트
		$display->cacheXml($db->data['skin']);

		if(strlen($db->data['cate']) > 3)
		{
			$func->err("['".$db->data['cate']."']카테고리 정보가 정상적으로 적용되었습니다.", "parent.$('#td".$db->data['cateCode1']."').toggleClass('open'); parent.$.cell('#cate_".$db->data['cateCode1']."', './modules/index.php', '&amp;type=cateSub&amp;cate=".$db->data['cateCode1']."'); parent.$.dialogRemove();");
		}
		else
		{
			$func->err("['".$db->data['cate']."']카테고리 정보가 정상적으로 적용되었습니다.", "parent.$.insert('#tabBody".$db->data['skin']."', './modules/categoryList.php?skin=".$db->data['skin']."',null,300); parent.$.dialogRemove();");
		}
	}
	else
	{
		$func->err("['".$db->data['cate']."']카테고리 정보가 변경된 내용이 없거나, 적용 실패입니다.");
	}

}
else if($_GET['type'] == "cateReg")
{
	if(__CATE__)
	{
		$Rows = $db->queryFetch(" SELECT * FROM `site__` WHERE skin='".$_GET['skin']."' AND cate='".__CATE__."' ");
		$perm = explode(",", $Rows['perm']);
		$permLmt = explode(",", $Rows['permLmt']);
		$xml = explode(",", $Rows['xml']);
		$title = "기존 카테고리 변경하기";
		switch(strlen($Rows['cate']))
		{
			case 3 :
				$parent = null;
				$cate = $Rows['cate'];
				break;
			default :
				$parent = substr($Rows['cate'], 0, strlen($Rows['cate'])-3);
				$cate = substr($Rows['cate'], -3);
				break;
		}
		//카테고리 관리 제한
		if(substr($Rows['cate'], 0, 3) == '000' && $_SESSION['ulevel'] != '1') { $func->ajaxMsg("본 카테고리[".substr($Rows['cate'], 0, 3)."]는 고정된 카테고리로 수정 및 변경이 불가능합니다","", 200); }
	}
	else
	{
		$title = $cfg['skinName'][$_GET['skin']]." - 신규 카테고리 생성하기";
	}
}
?>
<div id="messageBox"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/delete.gif" /></spna>&nbsp;<strong>Message :</strong>&nbsp;&nbsp;&nbsp;</div>

<form id="frmCateReg" name="frmCateReg" method="post" enctype="multipart/form-data" action="<?php echo($_SERVER['PHP_SELF']);?>" target="hdFrame">
<input type="hidden" name="type" value="cateRegPost" />
<input type="hidden" id="cated" name="cated" value="<?php echo($Rows['cate']);?>"/>
<input type="hidden" name="sort" value="<?php echo($Rows['sort']);?>" />
<input type="hidden" id="skin" name="skin" value="<?php echo($_GET['skin']);?>" />

<div class="menu_violet"><p title="드래그하여 이동하실 수 있습니다"><strong><?php echo($title);?></strong></p></div>
<table class="table_basic" style="width:100%;">
	<col width="130">
	<col>
	<col>
	<thead>
	<tr><th class="first"><p class="center"><span class="mg2">항 목</span></p></th>
		<th><p class="center"><span class="mg2">설 정</span></p></th>
		<th><p class="center"><span class="mg2">도움말</span></p></th>
	</tr>
	</thead>
	<tbody>
	<?php
	$form = new Form('table');
	?>
	<tr><th><label for="cateCode1" class="required"><span class="red" title="필수입력항목">*</span><strong>상위 카테고리</strong></label></th>
		<td><ol><li class="opt"><select id="cateCode1" class="bg_gray" name="cateCode1" style="width:300px" title="상위 카테고리" onchange="$('#cateCode2').val('');">
			<option value="">상위 선택 안함</option>
			<option value="">-------------------------------------------------------</option>
			<?php
			$db->query(" SELECT cate,name FROM `site__` WHERE skin='".$_GET['skin']."' ORDER BY cate,sort ASC ");
			while($sRows = $db->fetch())
			{
				for($i=2;$i<=strlen($sRows['cate'])/3;$i++) { $blank .= "　"; }
				echo('<option value="'.$sRows['cate'].'"');
				if($parent === $sRows['cate'])
				{
					echo(' selected="selected" style="color:#003399;"');
				}
				else if(strlen($sRows['cate']) == 3)
				{
					echo(' style="color:#990000;"');
				}
				echo('>'.$blank.' ('.substr($sRows['cate'],-3).')'.$sRows['name'].'</option>');
				unset($blank);
			}
			?>
			</select></li>
			</ol>
			<p class="clear"></p>
		</td>
		<td class="small_gray bg_gray"><ol><li class="opt">하위 카테고리의 경우 선택</li></ol></td>
	</tr>
	<?php
	$form->addStart('카테고리 코드', 'cateCode2', 1, 0, 'M');
	$form->add('input', 'cateCode2', $cate, 'width:60px; text-align:center; color:red;', 'digits="true" minlength="3" maxlength="3" onblur="$.checkOverLap(\''.$sess->encode("checkCate").'\', \'Cate\');"');
	$form->addHtml('<li class="opt"><span id="checkCate" class="small_orange"></span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li class="opt"><span>카테고리 코드는 "001"부터 시작하는 3자리 숫자로 입력</span>');
	$form->addEnd(1);

	$form->addStart('카테고리명', 'name', 1, 0, 'M');
	$form->add('input', 'name', $Rows['name'], 'width:300px; color:red;','maxlength="30"');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li class="opt"><span>카테고리 제목</span>');
	$form->addEnd(1);

	$form->addStart('카테고리명(서브)', 'nameExtra', 1);
	$form->add('input', 'nameExtra', $Rows['nameExtra'], 'width:300px; color:red;','maxlength="30"');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li class="opt"><span>카테고리 서브 제목 (영문이나 별도 표기시 사용)</span>');
	$form->addEnd(1);
	?>
	<tr><th><label for="link1"><strong>모듈 연결</strong></label></th>
		<td><ol><li class="opt"><select id="link1" class="bg_gray" name="module" style="width:190px" title="모듈 연결" onchange="toGetElementById('link2').options['0'].selected = true;">
			<option value="mdDocument">문서·게시물 모듈</option>
			<option value="">-------------------------------------------------------</option>
			<?php
			foreach($cfg['modules'] as $val)
			{
				if(!in_array($val, $cfg['solutionExcept']))
				{
					echo('<option value="'.$val.'"');
					if($Rows['mode'] == $val) { echo(' selected="selected" style="color:red;"'); } else { echo(' style="color:green;"'); }
					echo('>'.$cfg['solution'][$val].' 모듈</option>');
				}
			}
			?>
			</select></li>
			<li class="opt"><span class="keeping"><input type="checkbox" id="sameUnder" name="sameUnder" value="Y" /><label for="sameUnder">하위메뉴 동일적용</label></span></li>
			</ol>
		</td>
		<td class="small_gray bg_gray"><ol><li class="opt">설정된 프로그램 모듈로 연결</li></ol></td>
	</tr>
	<tr><th><label for="link2"><strong>콘텐츠 링크</strong></label></th>
		<td><ol><li class="opt"><select id="link2" class="bg_gray" name="page" style="width:305px" title="페이지 연결" onchange="$('#url').val(this.value);">
			<option value="">연결 안함</option>
			<option value="">-------------------------------------------------------</option>
			<?php
			$db->query(" SELECT cate,sort,name FROM `site__` WHERE skin='".$_GET['skin']."' AND cate NOT IN ('000001','000997','000998') ORDER BY cate ASC ");
			while($sRows = $db->fetch())
			{
				$menu = (strlen($sRows['cate']) == 3) ? $sRows['sort'] : $menu;
				$sub = (strlen($sRows['cate']) == 6) ? $sRows['sort'] : $sub;

				for($i=2;$i<=strlen($sRows['cate'])/3;$i++) { $blank .= "　"; }
				echo('<option value="'.$cfg['droot'].'?cate='.$sRows['cate'].'&menu='.$menu.'&sub='.$sub.'"');
				if($Rows['mode'] === $sRows['cate']) { echo(' selected="selected" style="color:red;"'); }
				echo('>'.$blank.' ('.substr($sRows['cate'],-3).')'.$sRows['name'].'</option>');
				unset($blank);
			}
			?>
			</select></li>
			</ol>
		</td>
		<td class="small_gray bg_gray"><ol><li class="opt">이미 생성된 다른 카테고리로 연결</li></ol></td>
	</tr>
	<?php
	$form->addStart('URL 연결', 'url', 1);
	$form->add('input', 'url', $Rows['url'], 'width:300px;','maxlength="100"');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li class="opt"><span>URL 연결 (외부 URL은 "http://"를 포함해서 입력)</span>');
	$form->addEnd(1);

	$form->addStart('타겟 설정', 'target', 1, 0, 'M');
	$form->add('radio', array('_self'=>'부모창열기','_blank'=>'새창열기'), $Rows['target'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li class="opt"><span>카테고리 클릭시 창이 열리는 방식설정</span>');
	$form->addEnd(1);

	$form->addStart('노출 설정', 'status', 1, 0, 'M');
	$form->add('radio', array('normal'=>'공개','hide'=>'비공개','dep'=>'메뉴 노출안함'), $Rows['status'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li class="opt"><span>공개설정 (비공개시 운영자외 모두 접근불가)</span>');
	$form->addEnd(1);

	$form->addStart('전체폭 활용', 'useFull', 1, 0, 'M');
	$form->add('radio', array('N'=>'사용안함','Y'=>'사용함'), $Rows['useFull'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li class="opt"><span>해당 카테고리만 전체 사이즈(Container)를 사용</span>');
	$form->addEnd(1);

	$form->addStart('카테고리 노출', 'useCate', 1, 0, 'M');
	$form->add('radio', array('N'=>'사용안함','Y'=>'사용함'), $Rows['useCate'], 'color:black;');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li class="opt"><span>서브 페이지 중앙에 노출되는 카테고리 위젯</span>');
	$form->addEnd(1);

	$form->addStart('하위메뉴 공백', 'xml1', 1);
	$form->add('input', 'xml1', $xml['0'], 'width:60px; text-align:center;','digits="true" maxlength="3"');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li class="opt"><span>상단 서브메뉴 좌측 공백값 (1차만 해당)</span>');
	$form->addEnd(1);

	$form->addStart('하위메뉴 색상', 'bgColor', 1);
	$form->class = "input_color";
	$form->add('input', 'bgColor', $config['bgColor'], 'width:60px;', 'maxlength="7"', 'onclick="colorPickerShow(\'bgColor\', 0);"');
	$form->addHtml('<li class="opt"><span>" # " 포함하여 입력</span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li class="opt"><span>BOX의 배경색</span>');
	$form->addEnd(1);

	$form->addStart('하위메뉴 갯수', 'child', 1);
	$form->class = "input_color";
	$form->add('input', 'child', $Rows['child'], 'width:60px; text-align:center','digits="true" maxlength="2"');
	$form->addHtml('<li class="opt"><span>개</span></li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li class="opt"><span>해당 카테고리의 하위 카테고리 갯수</span>');
	$form->addEnd(1);
	?>
	</tbody>
</table>
<div class="pd5 right"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit">위 설정으로 적용</button></span>&nbsp;<span class="btnPack medium black"><a href="javascript:;" onclick="$.dialogRemove();">취소</a></span></div>
</form>
<?php
	include "../include/commonScript.php";
?>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$('#frmCateReg').validate({onkeyup:function(element){$(element).valid();},errorLabelContainer:"#messageBox"});
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length  maxlen) alert('최대 ' + maxlen + '자로만 입력하실 수 있습니다!');});
});
//]]>
</script>