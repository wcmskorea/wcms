<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }

//페이지명 재설정
$cfg['cate']['name'] = "My Page";
$display->title = "My Page";

$recomLevel = $member->memberRecom();	//추천레벨이상일 경우 작성 가능

//$func->showArray($cfg);
?>
<div class="mypageTab" style="margin-top:15px;">
	<ul class="tabBox">
	<li class="tab on"><p><a href="/?cate=000002002#content">회원정보</a></p></li>
	</ul>
	<div class="clear"></div>
</div>
<table class="table_list" summary="회원정보 열람 및 탈퇴신청 항목" style="width:100%">
	<caption>회원정보 열람 및 탈퇴신청</caption>
	<col width="140">
	<col>
	<?php if($_SESSION['urecom']){ ?>
	<tr>
		<th><label><strong><?php echo($lang['member']['recom']);?></strong></label></th>
		<td><ul><li class="opt"><strong><?php echo($_SESSION['urecom']);?></strong></li></ul></td>
	</tr>
	<?php	} ?>
	<tr>
		<th><label><strong>아이디</strong></label></th>
		<td><ul><li class="opt"><strong><?php echo($Rows['id']);?></strong>&nbsp;(회원등급 : <span class="colorOrange"><?php echo($member->memberPosition($Rows['level']));?></span>)</li></ul></td>
	</tr>
	<tr>
		<th><label><strong>이름</strong></label></th>
		<td><ul><li class="opt"><strong><?php echo($Rows[name]);?></strong></li></ul></td>
	</tr>
	<tr>
		<th><label><strong>최근 정보변경일</strong></label></th>
		<td><ul><li class="opt"><span><?php echo(date('Y년 m월 d일 H시 i분 s초', $Rows['dateModify']));?></span></li></ul></td>
	</tr>
	<tr>
		<th><label for="secede"><strong>회원 탈퇴신청</strong></label></th>
		<td><ul><li class="opt"><span><input type="checkbox" id="secede" name="secede" class="input_check" title="회원탈퇴" value="Y" onclick="return confirm('회원 탈퇴시 모든 활동정보 및 개인정보가 삭제됩니다.\n\n진행하시겠습니까?');" /> <label for="secede" class="colorBlack">회원탈퇴 신청</label></span></li></ul></td>
	</tr>
</table>
<br />