<?php
/*---------------------------------------------------------------------------------------
| 회원정보 관리
|----------------------------------------------------------------------------------------
| Lastest (2008.10.22 : 이성준)
*/
require_once "../../../_Admin/include/commonHeader.php";

//모듈 환경설정 취합
$cfg['module'] = (array)$db->queryFetch(" SELECT * FROM `mdMember__` WHERE 1 LIMIT 1");
//모듈 환경설정 취합
$cfg['module'] = @array_merge($cfg['module'], (array)unserialize($cfg['module']['config']));
$cfg['module'] = @array_merge($cfg['module'], (array)unserialize($cfg['module']['contentAdd']));
?>
<div id="messageBox"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/delete.gif" /></spna>&nbsp;<strong>Message :</strong>&nbsp;&nbsp;&nbsp;</div>
<div class="menu_violet"><p title="드래그하여 이동하실 수 있습니다"><strong>회원 상세정보 및 관리 { 회원ID : <?php echo($_GET['user']);?> }</strong></p></div>

<form id="frmCate" name="frmCate" method="post" action="<?php echo($_SERVER['PHP_SELF']);?>" enctype="multipart/form-data" target="hdFrame">
<input type="hidden" name="type" value="post" />
<input type="hidden" name="memType" value="<?php echo($Rows['type']);?>" />
<input type="hidden" name="sex" value="<?php echo($Rows['sex']);?>" />
<input type="hidden" name="age" value="<?php echo($Rows['age']);?>" />

<table class="table_basic">
<col width="350">
<col>
<tr>
	<td style="vertical-align:top; padding:8px;" class="bg_gray">
		<?php
			include "./detailView.php";

			if(!$Rows['seq'])
			{
				//$Rows['birth'] = 1;
			}
		?>
		<div style="border-top:1px dashed #999; padding:5px 0;">
			<p class="pd5 center">
			<?php if($_GET['user'] && (preg_match('/mdMember/', $_SESSION['udepartment']) || $_SESSION['ulevel'] < $cfg['operator'])) { ?><span class="btnPack red medium strong"><button type="submit" onclick="return $.submit(this.form)">정보 수정하기</button></span>&nbsp;<span class="pd3"><a href="#none" onclick="if(confirm('정말 탈퇴회원으로 전환 하시겠습니까?')){$.dialog('../modules/mdMember/manage/_controll.php', '&amp;type=delete&amp;idx=<?php echo($Rows['seq']);?>','800','480')}" class="btnPack black medium"><span>탈퇴시키기</span></a></span><?php } ?><?php if($Rows['level'] == '0') { ?><span class="pd3"><a href="#none" onclick="if(confirm('영구삭제는 모든 연관정보가 삭제됩니다. 진행 하시겠습니까?')){$.dialog('../modules/mdMember/manage/_controll.php', '&amp;type=delComplete&amp;idx=<?php echo($Rows['seq']);?>','800','480')}" class="btnPack black medium"><span>영구삭제</span></a></span><?php } ?>
			<?php if(!$Rows['seq']) { ?><span class="btnPack red medium strong"><button type="submit" onclick="return $.submit(this.form)">등록하기</button></span><?php } ?>
			</p>
			<p class="pd5"><strong>기타 및 메모사항</strong> 100자이내</p>
			<p><textarea name="content" style="width:99%;height:170px;" class="input_white_line"><?php echo($Rows['content']);?></textarea></p>
		</div>
	</td>
	<td style="vertical-align:top;">
	<div class="cube"><div class="line">
	    <table class="table_list" style="width:600px">
	    <col width="120">
	    <col>
	    <thead>
	      <th class="first"><p class="center">입력항목</p></th>
	      <th><p class="center">입력정보</p></th>
	    </thead>
	    <tbody>
	    <?php
		$form = new Form('table');
		?>
		<tr class="active">
			<th><label for="level"><strong>회원등급</strong></label></td>
			<td><ol>
				<li class="opt"><select id="level" name="level" class="bg_gray required" style="width:124px;" req="required">
		        <?php
		        $db->query(" SELECT level,position FROM `mdMember__level` WHERE level BETWEEN 2 AND 98 ORDER BY level DESC ");
		        while($sRows=$db->fetch())
		        {
					if($sRows['position'])
					{
						echo('<option value="'.$sRows['level'].'"');
						if($sRows['level'] == $Rows['level']) { echo(' selected="selected" class="red"'); }
						echo('>(Lv.'.$sRows['level'].')'.$sRows['position'].'</option>');
					}
		        }
		        ?>
		        <option value="0" class="blue"<?php if($Rows['level'] == '0'){ echo(' selected="selected"'); }?>>(Lv.0)탈퇴회원</option>
				</select></li>
				<li class="opt">노출순서 : <input type="text" name="sort" class="input_gray center" style="width:30px;" value="<?php echo($Rows['sort']);?>" digits="true" maxlength="4" />
				</ol>
			</td>
		</tr>
		<?php
		if($cfg['module']['opt_group'] != 'N' && $cfg['module']['group']) {
			$group = explode(',', $cfg['module']['group']);
			$form->addStart('회원그룹', 'group', 1, 0 ,$cfg['module']['opt_group']);
			$form->add('selectValue', $group, $Rows['group'], 'width:124px;');
			$form->addEnd(1);
		}

		if(!$Rows['seq'])
		{
			$form->addStart('아이디', 'id', 1, 0 ,'M');
			//$form->class = "userid";
			$form->add('input', 'id', $Rows['id'], 'width:120px; ime-mode:disabled', 'onblur="$.checkOverLap(\''.$sess->encode("checkUserId").'\', \'Id\');" userid="true" minlength="5" maxlength="15"');
			$form->addHtml('<li class="opt"><span id="checkId" class="small_orange">5~15자의 영문을 포함한 숫자, _ 를 혼용가능</span></li>');
			$form->addEnd(1);
		} else {
			echo('<input type="hidden" id="userid" name="userid" value="'.$_GET['user'].'" />');
		}

		if(!$Rows['seq'])
		{
			$form->addStart('비밀번호', 'passwd', 1, 0,'M');
			$form->add('input', 'passwd', '', 'width:120px;','onblur="$.checkOverLap(\''.$sess->encode("checkUserPwd").'\',\'Pwd\');" minlength="8" maxlength="16"');
			$form->addHtml('<li class="opt"><span id="checkPwd" class="small_orange">영문/특수문자/숫자를 포함한 8자리 이상</span></li>');
			$form->addEnd(1);
		} else {
			$form->addStart('새로운 비밀번호', 'passwd', 1, 0);
			$form->add('input', 'passwd', '', 'width:120px;','onblur="$.checkOverLap(\''.$sess->encode("checkUserPwd").'\',\'Pwd\');" minlength="8" maxlength="16"');
			$form->addHtml('<li class="opt"><span id="checkPwd" class="small_gray">(변경시만 입력하세요)</span></li>');
			$form->addEnd(1);
		}

		$form->addStart('이름', 'name', 1, 0 ,'M');
		$form->add('input', 'name', $Rows['name'], 'width:120px; ime-mode:active','korean="true" minlength="2" maxlength="16"');
		$form->addEnd(1);

		if($cfg['module']['opt_nick'] != 'N') {
			$form->addStart('닉네임', 'nick', 1, 0 ,$cfg['module']['opt_nick']);
			$form->add('input', 'nick', $Rows['nick'], 'width:120px;', 'nick="true" maxlength="16"');
			$form->addEnd(1);
		}

		if($cfg['module']['opt_email'] != 'N') {
			$form->addStart('이메일', 'email', 1, 0 ,$cfg['module']['opt_email']);
			$form->add('input', 'email', $Rows['email'], 'width:330px; ime-mode:disabled','email="true" maxlength="50"');
			$form->addEnd(1);
		}

		if($cfg['module']['opt_address'] != 'N')
		{
			$form->addStart('우편번호', 'zipcode', 1, 0 ,$cfg['module']['opt_address']);
			$form->add('input', 'zipcode', $Rows['zipcode'], 'width:120px;','zipno="true" maxlength="7"');
			$form->addEnd(1);

			$form->addStart('주소', 'address01', 1, 0 ,$cfg['module']['opt_address']);
			$form->add('input', 'address01', $Rows['address01'], 'width:330px;','maxlength="30"');
			$form->addEnd(1);

			$form->addStart('나머지주소', 'address02', 1, 0 ,$cfg['module']['opt_address']);
			$form->add('input', 'address02', $Rows['address02'], 'width:330px;','maxlength="30"');
			$form->addEnd(1);
		}

		if($cfg['module']['opt_mobile'] != 'N')
		{
			$form->addStart('휴대전화', 'mobile', 1, 0 ,$cfg['module']['opt_mobile']);
			$form->add('input', 'mobile', $Rows['mobile'], 'width:120px;','mobile="true" maxlength="14"');
			$form->addEnd(1);
		}

		if($cfg['module']['opt_phone'] != 'N')
		{
			$form->addStart('일반전화', 'phone', 1, 0 ,$cfg['module']['opt_phone']);
			$form->add('input', 'phone', $Rows['phone'], 'width:120px;', 'phone="true" maxlength="14"');
			$form->addEnd(1);
		}

		if($cfg['module']['opt_office'] != 'N')
		{
			$form->addStart('직장전화', 'office', 1, 0 ,$cfg['module']['opt_office']);
			$form->add('input', 'office', $Rows['office'], 'width:120px;', 'phone="true" maxlength="14"');
			$form->addEnd(1);
		}

		if($cfg['module']['opt_fax'] != 'N')
		{
			$form->addStart('팩스번호', 'fax', 1, 0 ,$cfg['module']['opt_fax']);
			$form->add('input', 'fax', $Rows['fax'], 'width:120px;', 'phone="true" maxlength="14"');
			$form->addEnd(1);
		}

		if($cfg['module']['opt_sex'] != 'N')
		{
			$form->addStart('성별', 'sex', 1, 0 ,$cfg['module']['opt_sex']);
			$form->add('radio', array('1'=>'남성','2'=>'여성'), $Rows['sex'], 'color:black;');
			$form->addEnd(1);
		}

		if($cfg['module']['opt_birth'] != 'N')
		{
			$form->addStart('생년월일', 'birthyear', 1, 0 ,$cfg['module']['opt_birth']);
			$form->add('birthDay', 'birth', $Rows['birth'], 'color:blue;');
			$form->id = 'birthType';
			$form->add('select', array('S'=>'양력','L'=>'음력'), $Rows['birthType'], 'color:black;');
			$form->addEnd(1);
		}

		if($cfg['module']['opt_memory'] != 'N')
		{
			$form->addStart('결혼 기념일', 'memoryyear', 1, 0 ,$cfg['module']['opt_memory']);
			$form->add('date', 'memory', $Rows['memory'], 'color:blue;');
			$form->addEnd(1);
		}

		if($cfg['module']['opt_receive'] != 'N')
		{
			$form->addStart('수신동의', 'receive', 1, 0 ,$cfg['module']['opt_receive']);
			$form->add('radio', array('Y'=>'수신동의','N'=>'동의안함'), $Rows['receive'], 'color:black;');
			$form->addEnd(1);
		}

		if($cfg['module']['opt_groupName'] != 'N')
		{
			$form->addStart('회사명', 'groupName', 1, 0 ,$cfg['module']['opt_groupName']);
			$form->add('input', 'groupName', $Rows['groupName'], 'width:300px;','maxlength="30"');
			$form->addEnd(1);
		}

		if($cfg['module']['opt_groupNo'] != 'N')
		{
			$form->addStart('사업자번호', 'groupNo', 1, 0 ,$cfg['module']['opt_groupNo']);
			$form->add('input', 'groupNo', $Rows['groupNo'], 'width:300px;','bizno="true" maxlength="12"');
			$form->addEnd(1);
		}

		if($cfg['module']['opt_ceo'] != 'N')
		{
			$form->addStart('대표자명', 'ceo', 1, 0 ,$cfg['module']['opt_ceo']);
			$form->add('input', 'ceo', $Rows['ceo'], 'width:300px;','maxlength="16"');
			$form->addEnd(1);
		}

		if($cfg['module']['opt_status'] != 'N')
		{
			$form->addStart('업태', 'status', 1, 0 ,$cfg['module']['opt_status']);
			$form->add('input', 'status', $Rows['status'], 'width:300px;','maxlength="30"');
			$form->addEnd(1);
		}

		if($cfg['module']['opt_class'] != 'N')
		{
			$form->addStart('업종', 'class', 1, 0 ,$cfg['module']['opt_class']);
			$form->add('input', 'class', $Rows['class'], 'width:300px;','maxlength="30"');
			$form->addEnd(1);
		}
		?>
	    </tbody>
		</table>
	</div></div>
	</td>
</tr>
</table>
</form>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$('#frmCate').validate({onkeyup:function(element){$(element).valid();}});
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
//]]>
</script>
<?php
require_once "../../../_Admin/include/commonScript.php";
?>
