<?php
/*
| 회원정보 관리
| Relationship : mdMember/manage/_controll.php
| Last (2009.2.01 : 이성준)
*/
# 리퍼러 체크
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
usleep($cfg[sleep]);

$Rows		= $db->queryFetch("SELECT * FROM `mdApp01__content` WHERE seq='".$_GET['idx']."'");
$configQuery = $db->queryFetch("SELECT * FROM `mdApp01__` WHERE cate='".$Rows['cate']."'");
$config = @array_merge((array)unserialize($configQuery['config']), (array)unserialize($configQuery['contentAdd']));

$division	= explode(",", $config['division']);
$result = ($config['resultAdmin']) ? explode(",", $config['resultAdmin']) : explode(",", $config['result']);
$contentAdd = (array)unserialize($Rows['contentAdd']);
?>
<div id="messageBox"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/delete.gif" /></spna>&nbsp;<strong>Message :</strong>&nbsp;&nbsp;&nbsp;</div>

<div class="menu_violet"><p title="드래그하여 이동하실 수 있습니다"><strong>상담내역 상세보기 및 변경 [ 코드 : <?php echo($_GET[idx]);?> ]</strong></p></div>

<form id="frmDetail" name="frmDetail" method="post" action="<?php echo($_SERVER[PHP_SELF]);?>" enctype="multipart/form-data" target="hdFrame">
<input type="hidden" name="type" value="mpost" />
<input type="hidden" name="cate" value="<?php echo($Rows['cate']);?>" />
<input type="hidden" name="idx" value="<?php echo($Rows['seq']);?>" />
<input type="hidden" name="dateReg" value="<?php echo($Rows['dateReg']);?>" />
<input type="hidden" name="id" value="<?php echo($Rows['id']);?>" />
<input type="hidden" name="currentPage" value="<?php echo($_GET['currentPage']);?>" />

<table>
	<col>
	<col width="250">
	<tr>
		<td style="vertical-align:top">

			<table class="table_list" style="width:100%;">
				<col width="120">
				<col>
				<thead>
					<th class="first"><p class="center">입력항목</p></th>
					<th><p class="center">입력정보</p></th>
				</thead>
				<tbody>
				<?php
				$form = new Form('table');

				$form->addStart('처리구분', 'state', 1, 0, 'M');
				$form->add('select', $result, $Rows['state']);
				if($func->checkModule('mdSms')) {
					$form->addHtml('<li class="opt"><input type="checkbox" id="sendSMS" name="sendSMS" value="send" /><label for="sendSMS">결과 문자전송</label></li>');	//신청자 아이디 표시
				}
				$form->addEnd(1);

				if($config['division'] && $config['opt_division'] != 'N') {
					$form->addStart('상담구분', 'division', 1, 0, 'M');
					$form->add('select', $division, $Rows['division'], 'width:123px;');
					$form->addEnd(1);
				}

				if($config['opt_name'] != 'N') {
					$form->addStart('신청자명', 'name', 1, 0, 'M');
					$form->add('input', 'name', $Rows['name'], 'width:120px; ime-mode:active','minlength="2" maxlength="10"');
					if($Rows['id'])
					$form->addHtml('<li class="opt"><span class="small_orange">('.$Rows['id'].')</span></li>');	//신청자 아이디 표시
					$form->addEnd(1);
				}

				/*if($config['opt_idcode'] != 'N') {
					$form->addStart('주민번호', 'idcode', 1, 0, 'M');
					$form->add('input', 'idcode', $Rows['idcode'], 'width:120px;');
					$form->addEnd(1);
				}*/

				/*if($config['opt_tel'] && $config['opt_tel'] != 'N')
				{
					$form->addStart('연락처', 'tel', 1, 0 , $config['opt_tel']);
					$form->add('input', 'tel', $contentAdd['tel'], 'width:120px;', 'opt="phone"');
					$form->addHtml('<li class="opt"><span class="small_gray"> (예 : 062-000-0000)</span></li>');
					$form->addEnd(1);
				}*/

				if($config['opt_mobile'] != 'N') {
					$form->addStart('휴대전화', 'mobile', 1, 0 , $config['opt_mobile']);
					$form->add('input', 'mobile', $Rows['mobile'], 'width:120px;', 'mobile="true" maxlength="14"');
					$form->addHtml('<li class="opt"><span class="small_gray"> (예 : 010-000-0000)</span></li>');
					$form->addEnd(1);
				}

				if($config['opt_phone'] != 'N')
				{
					$form->addStart('전화번호', 'phone', 1, 0 , $config['opt_phone']);
					$form->add('input', 'phone', $Rows['phone'], 'width:120px;', 'phone="true" maxlength="14"');
					$form->addHtml('<li class="opt"><span class="small_gray"> (예 : 062-000-0000)</span></li>');
					$form->addEnd(1);
				}

				if($config['opt_email'] != 'N') {
					$form->addStart('이메일', 'email', 1, 0 , $config['opt_email']);
					$form->add('input', 'email', $Rows['email'], 'width:185px; ime-mode:disabled', 'email="true" maxlength="50"');
					$form->addHtml('<li class="opt"><span id="checkEmail" class="small_orange"></span></li>');
					$form->addEnd(1);
				}

				if($config['opt_address'] && $config['opt_address'] != 'N')
				{
					$form->addStart('우편번호', 'zipcode', 1);
					$form->add('input', 'zipcode', $Rows['zipcode'], 'width:120px;','zipno="true" maxlength="7"');
					$form->addEnd(1);

					$form->addStart('주소1', 'address01', 1);
					$form->add('input', 'address01', $Rows['address01'], 'width:330px; ime-mode:active','maxlength="30"');
					$form->addEnd(1);

					$form->addStart('주소2', 'address02', 1);
					$form->add('input', 'address02', $Rows['address02'], 'width:330px; ime-mode:active','maxlength="30"');
					$form->addEnd(1);
				}

				if($config['opt_schedule'] != 'N')
				{

					if($contentAdd['scheduleyear'] && $contentAdd['schedulemonth'] && $contentAdd['scheduleday'])
					{
						$schedule = mktime($contentAdd['schedulehour'], $contentAdd['schedulemin'], $contentAdd['schedulesec'], $contentAdd['schedulemonth'], $contentAdd['scheduleday'], $contentAdd['scheduleyear']);
					} else
					{
						$schedule = time();
					}

					$form->addStart('예약일정', 'scheduleyear', 1, 0 , $config['opt_schedule']);
					$form->add('datetime', 'schedule', $schedule);
					$form->addEnd(1);
				}

				if($config['opt_file'] != 'N')
				{
					echo('<tr><th><label for=""><strong>첨부된 파일</strong></label></th>
						<td><ul>');
						$n = 1;
						$db->query(" SELECT * FROM `mdApp01__file` WHERE parent='".$Rows['seq']."' ");
						while($sRows = $db->fetch())
						{
							$dir = $cfg['upload']['dir'].date("Y",$sRows['regDate'])."/".date("m",$sRows['regDate'])."/".$sRows['fileName'];

							echo('<li class="opt"><span><img src="'.$cfg['droot'].'common/image/files/'.strtolower($sRows['extName']).'.gif" align="absmiddle" onError="this.src=\'/image/files/unKonwn.gif\'" width="16" height="16" /></span><span><a href="'.$cfg['droot'].'addon/system/download.php?'.__PARM__.'&file='.$sess->encode($dir).'&name='.$sRows['realName'].'">'.$sRows['realName'].'</a></span></li>');
							$n++;
						}
						if($n == 1) { echo('<li class="opt"><span>첨부파일 없음</span></li>'); }
						echo('</ul>
						</td>
					</tr>');
				}

				for($i=1; $i<=20; $i++)
				{
					if($config['opt_add'.$i] && $config['opt_add'.$i] != 'N')
					{
						$opt = $db->queryFetch(" SELECT * FROM `mdApp01__opt` WHERE cate='".$Rows['cate']."' AND sort='".$i."' ");
						$addValue = ($opt['addType'] != 'input') ? explode("|", $opt['addContent']) : 'addContent'.$i;
						$contentAddValue = $opt['addType']=='checkboxs' ? ($contentAdd['addContent'.$i] ? explode('|',$contentAdd['addContent'.$i]) : array()) : $contentAdd['addContent'.$i];

						$form->addStart($opt['addName'], 'addContent'.$i, 1, 0 , $config['module']['opt_add'.$i]);
						$form->add($opt['addType'], $addValue, $contentAddValue, 'width:230px;');
						$form->addEnd(1);
					}
				}
				/*
				if($config['opt_content'] != 'N')
				{
					$form->addStart('상세내용(기타)', 'content', 1, 0 , $config['opt_content']);
					$form->add('textarea', 'content', stripslashes($Rows['content']), 'width:330px; height:100px;');
					$form->addEnd(1);
				}
				*/
			 	?>
			</table>

		</td>
		<td style="vertical-align:top; padding:10px;">
			<p class="left pd5"><strong>&lt; 상세내용 &gt;</strong></p>
			<p><textarea name="content" style="width:270px;height:150px;" class="input_gray"><?php echo(stripslashes($Rows['content']));?></textarea></p>
			<br />
			<p class="left pd5"><strong>&lt; 답변 및 처리내용 &gt;</strong></p>
			<p><textarea name="answers" style="width:270px;height:150px;" class="input_blue"><?php echo(stripslashes($Rows['contentAnswers']));?></textarea></p>
			<p class="center" style="padding:10px 0">
			<span><span class="btnPack red medium"><button type="submit" onclick="return $.submit(this.form);">수정</button></span></span>
			<span><a href="#none" onclick="if(delThis()){$.message('../modules/mdApp01/manage/_controll.php?type=dpost&amp;idx=<?php echo($Rows['seq']);?>',null)}" class="btnPack black medium"><span>삭제</span></a></span>
			<span><a href="#none" onclick="new_window('../modules/mdApp01/manage/appDetailPrint.php?idx=<?php echo($Rows['seq']);?>','print',710,500,'no','yes');" class="btnPack gray medium"><span>내역인쇄</span></a></span>
			<span><a href="#none" onclick="$.dialogRemove();" class="btnPack gray medium"><span>닫기</span></a></span>
			</p>
			<p class="center colorGray"><?php echo($Rows['info']);?></p>
		</td>
	</tr>
</table>
</form>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$('#frmDetail').validate({onkeyup:function(element){$(element).valid();}});
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
//]]>
</script>
<?php
require_once __PATH__."/_Admin/include/commonScript.php";
?>
