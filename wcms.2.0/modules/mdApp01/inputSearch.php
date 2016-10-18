<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }

/* ['0']관리, ['1']접근, ['2']열람권한, ['3']작성 */
if($member->checkPerm(3) === false) { $func->err("현재 조회 기간이 아닙니다.", "back"); }

/**
 * 입력 옵션 설정병합
 */

$cfg['module'] = @array_merge($cfg['module'], (array)unserialize($cfg['module']['contentAdd']));

/* 입력항목 옵션 체크 */
$division	= explode(",", $cfg['module']['division']);
$result		= explode(",", $cfg['module']['result']);

if($_POST['shName'] && $_POST['shCode'])
{
	$_POST['shCode'] = str_replace("-",null,$_POST['shCode']);
	$query	= "SELECT * FROM `mdApp01__content` WHERE cate='".__CATE__."' AND name='".$_POST['shName']."' AND idcode='".$_POST['shCode']."'";
	$Rows	= $db->queryFetch($query);
	//if($db->getNumRows() < 1) { $func->err("입력한 정보와 일치하는 정보가 존재하지 않습니다.", "window.history.back();"); }

} else if($_SESSION['uid'])
{
	$Rows = $db->queryFetch(" SELECT * FROM `mdMember__account` AS A,`mdMember__info` AS B WHERE A.id=B.id AND B.id='".$_SESSION['uid']."' ");
}

#--- 현재 상태별 건수정보
//$count = $db->queryFetch(" SELECT SUM(if(state='0',1,0)) AS stat0,SUM(if(state='1',1,0)) AS stat1,SUM(if(state='2',1,0)) AS stat2 FROM `mdApp01__content` WHERE cate='".__CATE__."' ");
?>
<div id="mdApp01InputSearch" class="docInput">

<form id="bbsform" name="bbsform" method="post" action="<?php echo($_SERVER['PHP_SELF']);?>" enctype="multipart/form-data">
<input type="hidden" name="type" value="<?php echo($sess->encode('inputSearchPost'));?>" />
<input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />
<input type="hidden" name="menu" value="<?php echo($menu);?>" />
<input type="hidden" name="sub" value="<?php echo($sub);?>" />
<input type="hidden" name="idx" value="<?php echo($Rows['seq']);?>" />
<input type="hidden" name="fileName" value="" />
<input type="hidden" name="fileCount" value="<?php echo($Rows['file']);?>" />

	<div id="mdApp01InputSearchForm">
		<?php
		if($_POST['shName'] && $_POST['shCode'])
		{
			switch($Rows['state'])
			{
				case "0" :
					echo('
					<div class="result"><p style="padding:5px 0"><strong>'.$Rows['name'].'</strong> ('.substr($Rows['idcode'],0,6).' - *******)님 <strong class="colorRed">[ '.$Rows['content'].' ]</strong> 당첨을 축하드립니다!</p>
					<p style="padding:5px 0">- 아래의 당첨자 계약 및 구비서류 안내를 꼭 참조하시기 바랍니다.</p>
					<p style="padding:5px 0">- 자세한 사항은 모델하우스 내방 상담 및 문의전화 ('.$cfg['site']['phone'].')로 문의 바랍니다.</p></div>
					');
					break;
				case "1" :
					$type = substr($Rows['contentAnswers'],0,3)."m²형 ";
					//$type .= (substr($Rows['contentAnswers'],1,2) == '74') ? substr($Rows['contentAnswers'],-1)."타입" : null;
					echo('
					<div class="result"><p><strong>'.$Rows['name'].'</strong> ('.substr($Rows['idcode'],0,6).' - *******)님은 <strong class="colorRed">[ '.$result[$Rows['state']].' ] '.$type.', '.$Rows['content'].'순위</strong> 입니다!</p>
					<p style="padding:10px 0">부적격자나 미계약세대에 대하여 순번에 따라 연락을 드리도록 하겠습니다.</p>
					</div>
					');
					break;
				case "2" :
					echo('
					<div class="result"><strong>'.$_POST['shName'].'</strong>('.substr($_POST['shcode'],0,6).' - *******)님 죄송합니다. 귀하께서는 당첨자명단에 없습니다. </p>
					<p style="padding:10px 0">성원에 감사드리며 지속적인 관심부탁드립니다.</p></div>
					');
					break;
				 default :
					echo('
					<div class="result"><p><strong>'.$_POST['shName'].'</strong>님은 일치하는 정보가 없습니다.</p>
					<p style="padding:10px 0">성원에 감사드리며 지속적인 관심부탁드립니다.</p></div>
					');
					 break;
			}
		} else
		{
		?><div class="result">
		<table class="table_basic" summary="회원가입을 위한 개인정보 입력 항목" style="width:340px">
		<caption>신청검색 입력항목</caption>
		<col width="100">
		<col>
			<tbody>
				<tr>
					<th scope="row"><label for="shName" class="required"><span class="colorRed" title="필수입력항목">*</span><strong>접수자명</strong></label></th>
					<td><ol><li class="opt"><input type="text" id="shName" name="shName" class="input_blue center required korean" style="width:120px;" value="" /></li></ol></td>
				</tr>
				<tr>
					<th scope="row"><label for="shPhone" class="required"><span class="colorRed" title="필수입력항목">*</span><strong>주민번호</strong></label></th>
					<td><ol><li class="opt"><input type="text" id="shCode" name="shCode" class="input_blue center required" style="width:120px;" value="" /></li>
					<li class="opt"><span class="btnPack red small strong"><button type="submit" onclick="return submitForm(this.form);">조회하기</button></span></li></ol></td>
				</tr>
				<?php if($cfg['module']['announce']) { ?>
				<tr>
					<th scope="row"><label for="announce"><strong>안내말씀</strong></label></th>
					<td><ol><li class="opt colorOrange"><?php echo($cfg['module']['announce']);?></li></ol></td>
				</tr>
				<?php } ?>
			</tbody>
		</table></div>
		<?php
		}
		?>
	</div>
</form>
</div>
<br />
<div class="resultInfo"><img src="/user/default/image/sub/sub_17.jpg" /></div>

<script language="javascript" type="text/javascript">
function submitForm(frm)
{
	$(frm).validate({
		submitHandler: function(frm) {
			frm.submit();
		},
		onkeyup:false,
		onclick:false,
		onfocusout:false,
		showErrors: function(errorMap, errorList) {
			if (errorList && errorList[0]) {
			  alert(errorList[0].message);
			}
		}
	});
}
</script>