<?php
/**
 * 회원정보 상세보기 : 지자체 교육기관용
 * Author : Sung-Jun, Lee (http://www.aceoa.com)
 * Lastest : 2011. 1. 28.
 */
require_once "../../../_Admin/include/commonHeader.php";

$Rows		= $member->memberInfo($_GET['user']);
$birth		= explode("-", $Rows['birth']);
$memory		= explode("-", $Rows['memory']);
$sex		= ($Rows['sex']) ? ($Rows['sex']=='1' || $Rows['sex']=='3') ? "남" : "여" : "-";
$parts		= $db->queryFetch(" SELECT department,team,function FROM `mdMember__` WHERE cate='000002002' ");
foreach($parts AS $key=>$val) { $array[$key] = explode(',', $val); }
?>

<div class="menu_violet"><p title="드래그하여 이동하실 수 있습니다"><strong>회원 상세정보 및 관리 { 회원ID : <?php echo($_GET['user']);?> }</strong></p></div>

<form name="frmCate" method="post" action="<?php echo($_SERVER['PHP_SELF']);?>" enctype="multipart/form-data" target="hdFrame">
<input type="hidden" name="type" value="post" />
<input type="hidden" name="memType" value="<?php echo($Rows['type']);?>" />
<input type="hidden" name="sex" value="<?php echo($Rows['sex']);?>" />
<input type="hidden" name="age" value="<?php echo($Rows['age']);?>" />

<table>
<col width="300">
<col>
<tr>
	<td style="vertical-align:top; padding:10px;">
	    <?php 
	    	include "./detailView.php"; 
	    ?>
	    <div style="border-top:1px dashed #999; padding:10px 0;">
			<p class="pd5"><strong>기타 및 업무분장</strong> 100자이내</p>
			<p><textarea name="content" style="width:99%;height:120px;" class="input_white_line"><?php echo($Rows['content']);?></textarea></p>
			<p class="pd5 center">
			<?php if($Rows['level'] > '0') { ?><span class="button bred strong"><button type="submit">수정하기</button></span>&nbsp;<span class="pd3"><a href="#none" onclick="if(confirm('정말 탈퇴회원으로 전환 하시겠습니까?')){$.dialog('../modules/mdMember/manage/_controll.php', '&amp;type=delete&amp;idx=<?php echo($Rows['seq']);?>','800','480')}" class="button bblack"><span>탈퇴시키기</span></a></span><?php } ?><?php if($Rows['level'] == '0') { ?><span class="button bred strong"><button type="submit">수정하기</button></span>&nbsp;<span class="pd3"><a href="#none" onclick="if(confirm('영구삭제는 모든 연관정보가 삭제됩니다. 진행 하시겠습니까?')){$.dialog('../modules/mdMember/manage/_controll.php', '&amp;type=delComplete&amp;idx=<?php echo($Rows['seq']);?>','800','480')}" class="button bblack"><span>영구삭제</span></a></span><?php } ?>
			<?php if(!$Rows) { ?><span class="button bred strong"><button type="submit">등록하기</button></span><?php } ?>
			</p>
			<p class="pd5"><strong>학력사항</strong> 100자이내</p>
			<p><textarea name="education" style="width:99%;height:90px;" class="input_white_line"><?php echo($Rows['education']);?></textarea></p>
			<p class="pd5"><strong>경력사항</strong> 100자이내</p>
			<p><textarea name="career" style="width:99%;height:100px;" class="input_white_line"><?php echo($Rows['career']);?></textarea></p>
			<p class="pd5"><strong>자격사항</strong> 100자이내</p>
			<p><textarea name="qualifications" style="width:99%;height:100px;" class="input_white_line"><?php echo($Rows['qualifications']);?></textarea></p>
		</div>
	</td>
	<td style="vertical-align:top;">
	    <div class="cube"><div class="line">
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
		
		$form->addStart('회원형태', 'division', 1);
		$form->add('radio', array('P'=>'일반회원','C'=>'사내직원'), $Rows['division'] ,'color:black;');
		$form->addEnd(1);
		?>
		<tr>
	     	<th><label for="level"><strong>회원그룹</strong></label></td>
			<td><ol>
				<li class="opt"><select id="level" name="level" class="bg_gray" style="width:124px;">
		        <?php
		        $db->query("SELECT level,position FROM `mdMember__level` WHERE level BETWEEN 2 AND 98 ORDER BY level DESC");
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
		        <option value="0" class="blue"<?php if($Rows['level'] == '0'){print(' selected="selected"');}?>>(Lv.0)탈퇴회원</option>
				</select></li>
				<li class="opt">순서 : <input type="text" name="sort" class="input_text center" style="width:30px;" value="<?php echo($Rows['sort']);?>" />
				</ol>
			</td>
		</tr>
	    <?php 
	    if($parts['department']) { 
			$form->addStart('부서명', 'department', 1);
			$form->add('select', $array['department'], $Rows['department'], 'width:124px;');
			$form->addEnd(1);
	    }
	    if($parts['team']) {
	    	$form->addStart('팀명', 'team', 1);
			$form->add('select', $array['team'], $Rows['team'], 'width:124px;');
			$form->addEnd(1);
	    }
	    if($parts['dedicate']) {
	    	$form->addStart('주요업무', 'function', 1);
			$form->add('select', $array['function'], $Rows['function'], 'width:124px;');
			$form->addEnd(1);
	    }
	    
	    $form->addStart('닉네임', 'nick', 1);
		$form->add('input', 'nick', $Rows['nick'], 'width:120px;');
		$form->addEnd(1);
		
		if(!$Rows) {
			$form->addStart('아이디', 'id', 1, 0 ,'M');
			$form->add('input', 'id', $Rows['id'], 'width:120px;', 'onblur="$.checkOverLap(\''.$sess->encode("checkUserId").'\', \'Id\');" opt="userid"');
			$form->addHtml('<li class="opt"><span id="checkId" class="small_orange"></span></li>');
			$form->addEnd(1);
		} else {
			echo('<input type="hidden" id="userid" name="userid" value="'.$_GET['user'].'" />');
		}
		
		$form->addStart('이름', 'name', 1, 0, 'M');
		$form->add('input', 'name', $Rows['name'], 'width:120px;');
		$form->addEnd(1);
		
		$form->addStart('식별코드<br />(주민번호)', 'idcode', 1);
		$form->add('input', 'idcode', '', 'width:120px;');
		$form->addHtml('<li class="opt">(변경시만 입력하세요)</li>');
		$form->addEnd(1);
		
		$form->addStart('새로운 비밀번호', 'passwd', 1);
		$form->add('input', 'passwd', '', 'width:120px;');
		$form->addHtml('<li class="opt">(변경시만 입력하세요)</li>');
		$form->addEnd(1);
		
		$form->addStart('이메일', 'email', 1, 0, 'M');
		$form->add('input', 'email', $Rows['email'], 'width:300px;');
		$form->addEnd(1);
		
		$form->addStart('우편번호', 'zipcode', 1);
		$form->add('input', 'zipcode', $Rows['zipcode'], 'width:120px;');
		$form->addEnd(1);
		
		$form->addStart('주소1', 'address01', 1);
		$form->add('input', 'address01', $Rows['address01'], 'width:300px;');
		$form->addEnd(1);
		
		$form->addStart('주소2', 'address02', 1);
		$form->add('input', 'address02', $Rows['address02'], 'width:300px;');
		$form->addEnd(1);
		
		$form->addStart('휴대전화', 'mobile', 1);
		$form->add('input', 'mobile', $Rows['mobile'], 'width:120px;');
		$form->addEnd(1);
		
		$form->addStart('일반전화', 'phone', 1);
		$form->add('input', 'phone', $Rows['phone'], 'width:120px;');
		$form->addEnd(1);
		
		$form->addStart('직장전화', 'office', 1);
		$form->add('input', 'office', $Rows['office'], 'width:120px;');
		$form->addEnd(1);
		
		$form->addStart('팩스번호', 'fax', 1);
		$form->add('input', 'fax', $Rows['fax'], 'width:120px;');
		$form->addEnd(1);
		
		$form->addStart('성별', 'sex', 1);
		$form->add('radio', array('1'=>'남성','2'=>'여성'), $Rows['sex'], 'color:black;');
		$form->addEnd(1);
		
		$form->addStart('생일', 'birthyear', 1);
		$form->add('date', 'birth', $Rows['birth'], 'color:blue;');
		$form->addEnd(1);
		
		$form->addStart('기념일', 'memoryyear', 1);
		$form->add('date', 'memory', $Rows['memory'], 'color:blue;');
		$form->addEnd(1);
		
		$form->addStart('수신동의', 'receive', 1);
		$form->add('radio', array('Y'=>'수신동의','N'=>'동의안함'), $Rows['receive'], 'color:black;');
		$form->addEnd(1);
		
		$form->addStart('회사명', 'groupName', 1);
		$form->add('input', 'groupName', $Rows['groupName'], 'width:300px;');
		$form->addEnd(1);
		
		$form->addStart('사업자번호', 'groupNo', 1);
		$form->add('input', 'groupNo', $Rows['groupNo'], 'width:300px;');
		$form->addEnd(1);
		
		$form->addStart('대표자명', 'ceo', 1);
		$form->add('input', 'ceo', $Rows['ceo'], 'width:300px;');
		$form->addEnd(1);
		
		$form->addStart('업태', 'status', 1);
		$form->add('input', 'status', $Rows['status'], 'width:300px;');
		$form->addEnd(1);
		
		$form->addStart('업종', 'class', 1);
		$form->add('input', 'class', $Rows['class'], 'width:300px;');
		$form->addEnd(1);
		?>
	    </tbody>
		</table>
	    </div></div>
	</td>
</tr>
</table>
</form>
<?php 
require_once "../../../_Admin/include/commonScript.php";
?>

