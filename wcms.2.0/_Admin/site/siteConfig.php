<?php
require_once "../../_config.php";
require_once "../include/commonHeader.php";
$cfg = array_merge($cfg, (array)parse_ini_file(__PATH__."_Site/".__DB__."/".$_GET['skin']."/cache/config.ini.php", true));

//해당 스킨의 테이블이 존재하지 않으면 기본테이블로 추가생성한다.
if($db->checkTable("display__".$_GET['skin']) < 1)
{
	require_once __PATH__."/_Admin/sql/default.sql.php";
	$query = str_replace("`display__default`", "`display__".$_GET['skin']."`", $sql['site']['4']);
	$db->query(trim($query));
}
?>
<div id="messageBox"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/delete.gif" /></spna>&nbsp;<strong>Message :</strong>&nbsp;&nbsp;&nbsp;</div>
<fieldset id="help">
<legend>→ TIP's ←</legend>
<ul>
	<li><?php echo($cfg['skinName'][$_GET['skin']]);?>의 기본 환경을 설정하는 페이지 입니다.</li>
	<li>유료제공 모듈을 추가 설치하는 것은 "솔루션 제작사"를 통해서만 가능합니다. 상단 "CSS요청"을 이용해주세요.</li>
</ul>
</fieldset>

<form id="frm01" name="frm01" method="post" action="./site/index.php" enctype="multipart/form-data" target="hdFrame">
<input type="hidden" id="type" name="type" value="sitePost" />
<input type="hidden" id="skin" name="skin" value="<?php echo($_GET['skin']);?>" />

<table class="table_basic" style="width: 100%;">
	<col width="310">
	<col width="200">
	<col>
	<thead>
	<tr>
		<th class="first"><p class="center"><span><?php echo($cfg['skinName'][$_GET['skin']]);?> 설정</span></p></th>
		<th><p class="center"><span>기본제공 모듈</span>&nbsp;<span>(<?php echo(count($cfg['solutionFree']));?>개)</span></p></th>
		<th><p class="center"><span>유료제공 모듈</span>&nbsp;<span>(<?php echo(count($cfg['solution'])-count($cfg['solutionFree']));?>개)</span></p></th>
	</tr>
	</thead>
	<tbody>
		<tr>
		<td class="top" style="padding:1px 0;">
			<table class="table_small" style="width:100%;">
			<col width="140">
			<col width="160">
			<tbody>
				<?php
				$form = new Form('table');

				/*$form->addStart('기본언어 설정', 'lang', 1, 0, 'M');
				$form->name = array('kr'=>'한국어','en'=>'영어','jp'=>'일어','cn'=>'중국어');
				$form->add('select', $form->name, $cfg['site']['lang'], 'width:100px;');
				$form->addEnd(0);*/

				$form->addStart('프레임셋 설정', 'frame', 1, 0, 'M');
				$form->add('select', array('N'=>'사용안함','Y'=>'사용함'), $cfg['site']['frame'], 'width:100px;');
				$form->addEnd(1);

				$form->addStart('사이트 정렬', 'align', 1, 0, 'M');
				$form->add('select', array('left'=>'왼쪽 정렬','center'=>'가운데 정렬'), $cfg['site']['align'], 'width:100px;');
				$form->addEnd(1);

//				$form->addStart('글로벌 네비게이션', 'navGnb', 1, 0, 'M');
//				$form->add('select', array('Y'=>'노출함','N'=>'노출안함'), $cfg['site']['navGnb'], 'width:100px;');
//				$form->addEnd(1);

//				$form->addStart('다국어 네비게이션', 'navUnb', 1, 0, 'M');
//				$form->add('select', array('N'=>'노출안함','Y'=>'노출함'), $cfg['site']['navUnb'], 'width:100px;');
//				$form->addEnd(1);

				$form->addStart('퀵 스크롤', 'navQnb', 1, 0, 'M');
				$form->add('select', array('N'=>'노출안함','Y'=>'노출함'), $cfg['site']['navQnb'], 'width:100px;');
				$form->addEnd(1);

				$form->addStart('스킨패턴 노출', 'openSkin', 1, 0, 'M');
				$form->add('select', array('Y'=>'노출함','N'=>'노출안함'), $cfg['site']['openSkin'], 'width:100px;');
				$form->addEnd(1);

				$form->addStart('암호화 설정', 'encrypt', 1, 0, 'M');
				$form->add('select', array('sha256'=>'SHA-256','sha512'=>'SHA-512'), $cfg['site']['encrypt'], 'width:100px;');
				$form->addEnd(1);

				$form->addStart('전체 폭', 'size', 1, 0, 'M');
				$form->add('input', 'size', $cfg['site']['size'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
				$form->addHtml('<li class="opt">px (#wrap)</li>');
				$form->addEnd(1);

				$form->addStart('메인(좌)폭', 'sizeMsnb', 1, 0, 'M');
				$form->add('input', 'sizeMsnb', $cfg['site']['sizeMsnb'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
				$form->addHtml('<li class="opt">px (.snb)</li>');
				$form->addEnd(1);

				$form->addStart('메인(우)폭', 'sizeMside', 1, 0, 'M');
				$form->add('input', 'sizeMside', $cfg['site']['sizeMside'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
				$form->addHtml('<li class="opt">px (.side)</li>');
				$form->addEnd(1);

				$form->addStart('서브(좌)폭', 'sizeSsnb', 1, 0, 'M');
				$form->add('input', 'sizeSsnb', $cfg['site']['sizeSsnb'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
				$form->addHtml('<li class="opt">px (.snb)</li>');
				$form->addEnd(1);

				$form->addStart('서브(우)폭', 'sizeSside', 1, 0, 'M');
				$form->add('input', 'sizeSside', $cfg['site']['sizeSside'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
				$form->addHtml('<li class="opt">px (.side)</li>');
				$form->addEnd(1);

				$form->addStart('내용(좌)공백', 'padContentLeft', 1, 0, 'M');
				$form->add('input', 'padContentLeft', $cfg['site']['padContentLeft'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
				$form->addHtml('<li class="opt">px (#module)</li>');
				$form->addEnd(1);

				$form->addStart('내용(우)공백', 'padContentRight', 1, 0, 'M');
				$form->add('input', 'padContentRight', $cfg['site']['padContentRight'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
				$form->addHtml('<li class="opt">px (#module)</li>');
				$form->addEnd(1);

				$form->addStart('SSL보안', 'ssl', 1, 0, 'Y');
				$form->add('input', 'ssl', $cfg['site']['ssl'] ? $cfg['site']['ssl'] : '443', 'width:40px; text-align:center;','digits="true" maxlength="3"','readonly="readonly"');
				$form->addHtml('<li class="opt">포트 (수정안됨)</li>');
				$form->addEnd(1);

				if($_GET['skin'] == 'default')
				{
					if($_SESSION['ulevel'] == '1') {
                        $form->addStart('디버깅', 'debug', 1, 0, 'M');
                        $form->add('radio', array('0'=>'사용안함','1'=>'사용함'), $cfg['site']['debug'], 'color:red;');
                        $form->addEnd(1);
                    }

					$form->addStart('트래킹', 'tracking', 1, 0, 'M');
					$form->add('radio', array('0'=>'사용안함','1'=>'사용함'), $cfg['site']['tracking'], 'color:red;');
					$form->addEnd(1);

					$form->addStart('페이스북 연동', 'facebook', 1, 0, 'M');
					$form->add('radio', array('0'=>'사용안함','1'=>'사용함'), $cfg['site']['facebook'], 'color:red;');
					$form->addEnd(1);

					$form->addStart('트위터 연동', 'twitter', 1, 0, 'M');
					$form->add('radio', array('0'=>'사용안함','1'=>'사용함'), $cfg['site']['twitter'], 'color:red;');
					$form->addEnd(1);

					$form->addStart('모바일사이트', 'mobileweb', 1, 0, 'M');
					$form->add('radio', array('0'=>'사용안함','1'=>'사용함'), $cfg['site']['mobileweb'], 'color:red;');
					$form->addEnd(1);

					$form->addStart('다국어(영문)', 'englishweb', 1, 0, 'M');
					$form->add('radio', array('0'=>'사용안함','1'=>'사용함'), $cfg['site']['englishweb'], 'color:red;');
					$form->addEnd(1);

					//$form->addStart('중문사이트', 'chineseweb', 1, 0, 'M');
					//$form->add('radio', array('0'=>'사용안함','1'=>'사용함'), $cfg['site']['chineseweb'], 'color:red;');
					//$form->addEnd(1);

					$form->addStart('컨텍스트 메뉴차단', 'contextmenu', 1, 0, 'M');
					$form->add('radio', array('0'=>'사용안함','1'=>'사용함'), $cfg['site']['contextmenu'], 'color:red;');
					$form->addEnd(1);

					$form->addStart('마우스 드래그차단', 'mouseDrag', 1, 0, 'M');
					$form->add('radio', array('0'=>'사용안함','1'=>'사용함'), $cfg['site']['mouseDrag'], 'color:red;');
					$form->addEnd(1);
                    /*
					$form->addStart('생일문자 예약발송', 'birthSms', 1, 0, 'M');
					$form->add('radio', array('0'=>'사용안함','1'=>'사용함'), $cfg['site']['birthSms'], 'color:red;');
					$form->addEnd(1);

					$form->addStart('생일문자 발송시간', 'bsTime', 1, 0, 'M');
					$form->add('hourMin', 'bsTime', $cfg['site']['bsTime']);
					$form->addEnd(1);
                    */
				}

				$form->addStart('세션유지 설정', 'sessionTime', 1, 0, 'M');
				$form->add('select', array('24'=>'기본설정(24분)','60'=>'1시간','120'=>'2시간','180'=>'3시간','240'=>'4시간','1440'=>'24시간'), $cfg['site']['sessionTime'], 'width:150px;');
				$form->addEnd(1);

				$form->addStart('세션저장 설정', 'sessionType', 1, 0, 'M');
				$form->add('radio', array('sessiondb'=>'DB 저장','sessionfile'=>'FILE저장'), $cfg['site']['sessionType'], 'color:red;');//
				$form->addEnd(1);

				$form->addStart('접속제한 설정', 'allowCountry', 1, 0, 'M');
				$form->add('select', array('1'=>'높음(국내만허용)','2'=>'중간(국내+미국허용)','3'=>'낮음(일부차단)','ALL'=>'전체허용'), $cfg['site']['allowCountry'], 'width:150px;');
				$form->addEnd(1);

				?>
				</tbody>
			</table>
		</td>
		<td class="top">
			<ol>
			<?php
			if($_GET['skin'] == 'default')
			{
				foreach($cfg['solutionFree'] as $val)
				{
					echo('<li class="opt" style="width:120px;">');
					echo('<input name="'.$val.'" type="checkbox" id="'.$val.'" class="input_radio" value="'.$val.'" ');
					if(in_array($val, $cfg['modules']))
					{
						if($val == 'mdDocument')
						{
							echo('checked="checked" disabled="disabled"');
						}
						else
						{
							echo('checked="checked"');
						}
						echo(' /></span><label for="'.$val.'"><span class="colorBlue">');
					}
					else
					{
						if($val == 'mdSitemap')
						{
							echo('checked="checked" disabled="disabled" /></span><label for="'.$val.'"><span class="colorBlue">');
						}
						else
						{
							echo(' /></span><label for="'.$val.'"><span class="colorDarkgray">');
						}
					}
					echo($cfg['solution'][$val].'</span></label></li>');
				}
			}
			else
			{
				echo('<li class="opt">모듈은 "<strong>기본 사이트</strong>"에서만 설정합니다.</li>');
			}
			?>
			</ol>
		</td>
		<td class="top">
			<ol>
			<?php
			if($_GET['skin'] == 'default')
			{
				foreach($cfg['solution'] as $key=>$val)
				{
					if(!in_array($key, $cfg['solutionFree'])) //유료제공 모듈 제외하고 노출
					{
						echo('<li class="opt" style="width:200px;">');
						echo('<input name="'.$key.'" type="checkbox" id="'.$key.'" class="input_radio" value="'.$key.'" ');
						if(in_array($key, $cfg['modules']))
						{
							echo('checked="checked" /></span><label for="'.$key.'"><span class="colorBlue">');
						}
						else if($_SESSION['ulevel'] != 1) //슈퍼관리자외에는 유료제공모듈을 선택할 수 없다
						{
							echo('disabled="disabled" /></span><label for="'.$key.'"><span class="colorGray">');
						}
						else
						{
							echo(' /></span><label for="'.$key.'"><span class="colorDarkgray">');
						}
						echo($cfg['solution'][$key].'('.$key.')</span></label></li>');
					}
				}
			}
			else
			{
				echo('<li class="opt">모듈은 "<strong>기본 사이트</strong>"에서만 설정합니다.</li>');
			}
			?>
			</ol>
		</td>
		</tr>
		<?php
		$form->addStart('최근 변경 정보', '', 2);
		$form->addHtml('<li class="opt"><span><strong>TIME:</strong> '.str_replace("|", " <strong>IP:</strong> ", $cfg['site']['info']).'</span></li>');
		$form->addEnd(1);
		?>
	</tbody>
</table>
<div class="pd5 center"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit" onclick="return $.submit(this.form)">위 설정으로 적용</button></span></div>
</form>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    $('#frm01').validate({onkeyup:function(element){$(element).valid();}});
    $("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
//]]>
</script>
<?php include "../include/commonScript.php"; ?>
