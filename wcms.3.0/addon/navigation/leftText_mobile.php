<?php
$this->result .= '<h2>서브메뉴</h2>';
$this->result .= '<div class="sub_menu">';
$this->result .= '<ul class="parents">';

if($_SESSION['ulevel'] && $_SESSION['ulevel'] <= 3) {
	$this->result .= '<li class="parent first"><a href="'.$this->cfg[droot].'?cate=100'.$this->base.'001&type=info" class="actRed">학급 관리</a>';
	$this->result .= '<ul class="childes">';
	$this->result .= '<li class="childe"><a href="'.$this->cfg[droot].'?cate=100'.$this->base.'001&type=info"><span class="gray">└</span> 학급정보 관리</a></li>';
	$this->result .= '<li class="childe"><a href="'.$this->cfg[droot].'?cate=100'.$this->base.'001&type=skin"><span class="gray">└</span> 학급스킨 변경</a></li>';
	$this->result .= '<li class="childe"><a href="'.$this->cfg[droot].'?cate=100'.$this->base.'001&type=member"><span class="gray">└</span> 학급회원 관리</a></li>';
	$this->result .= '<li class="childe"><a href="'.$this->cfg[droot].'?cate=100'.$this->base.'001&type=scheduleModify"><span class="gray">└</span> 시간표 관리</a></li>';
	$this->result .= '<li class="childe"><a href="'.$this->cfg[droot].'?cate=100'.$this->base.'001&type=photo"><span class="gray">└</span> 앨범게시판 관리</a></li>';
	$this->result .= '<li class="childe"><a href="'.$this->cfg[droot].'?cate=100'.$this->base.'001&type=board"><span class="gray">└</span> 일반게시판 관리</a></li>';
	$this->result .= '<li class="childe"><a href="'.$this->cfg[droot].'?cate=100'.$this->base.'001&type=favor"><span class="gray">└</span> 즐겨찾기 관리</a></li>';
	$this->result .= '</ul></li>';
}
$this->result .= '<li class="parent first"><a href="'.$this->cfg[droot].'?cate=100'.$this->base.'001" class="actBold">우리반 소개</a>';
$this->result .= '<ul class="childes">';
$this->result .= '<li class="childe"><a href="'.$this->cfg[droot].'?cate=100'.$this->base.'001"><span class="gray">└</span> 학급소개</a></li>';
$this->result .= '<li class="childe"><a href="'.$this->cfg[droot].'?cate=100'.$this->base.'001&type=schedule"><span class="gray">└</span> 시간표</a></li>';
$this->result .= '<li class="childe"><a href="'.$this->cfg[droot].'?cate=100'.$this->base.'002"><span class="gray">└</span> 알림장</a></li>';
$this->result .= '</ul></li>';

$this->result .= '<li class="parent first"><a href="'.$this->cfg[droot].'?cate=100'.$this->base.'003" class="actBold">우리반 행사</a>';
$this->result .= '<ul class="childes">';
$this->result .= '</ul></li>';
//$this->result .= '<li class="parent"><a href="'.$this->cfg[droot].'?cate=100'.$this->base.'001&type=students" class="actBold">우리반 친구들</a>';
//$this->result .= '<ul class="childes">';
//$this->result .= '</ul></li>';

$this->result .= '<li class="parent first"><a href="'.$this->cfg[droot].'?cate=100'.$this->base.'004" class="actBold">우리반 앨범</a>';
$this->result .= '<ul class="childes">';
$rst = @mysql_query(" SELECT cate,name_".$this->cfg[site][lang]." AS name,mode,url,target FROM `site__` WHERE LENGTH(cate)='15' AND SUBSTRING(cate,1,12)='".substr(__CATE__, 0, 9)."004' AND target<>'_dep' AND hidden='N' ORDER BY sort ASC ");
while($sRows = @mysql_fetch_array($rst)) {
	$moveCate = (substr($sRows[mode],0,2) == "md" || $sRows[mode] == "") ? substr($sRows[cate],0,3).$this->base.substr($sRows[cate],-6) : $sRows[mode];
	$this->result .= '<li class="childe';
	if($sRows[cate] == __CATE__) { $this->result .= ' active'; } //현재 선택된 카테고리
	if($sRows[mode] == 'url') {
		$this->result .= '"><a href="'.$sRows[url].'" target="'.$sRows[target].'"><span class="gray">└</span> '.$sRows[name].'</a>';
	} else {
		$this->result .= '"><a href="'.$cfg[droot].'index.php?cate='.$moveCate.'" target="'.$sRows[target].'"><span class="gray">└</span> '.$sRows[name].'</a>';
	}
	$this->result .= '</li>';
}
$this->result .= '</ul></li>';

$this->result .= '<li class="parent first"><a href="'.$this->cfg[droot].'?cate=100'.$this->base.'005" class="actBold">우리반 게시판</a>';
$this->result .= '<ul class="childes">';
$rst = @mysql_query(" SELECT cate,name_".$this->cfg[site][lang]." AS name,mode,url,target FROM `site__` WHERE LENGTH(cate)='15' AND SUBSTRING(cate,1,12)='".substr(__CATE__, 0, 9)."005' AND target<>'_dep' AND hidden='N' ORDER BY sort ASC ");
while($sRows = @mysql_fetch_array($rst)) {
	$moveCate = (substr($sRows[mode],0,2) == "md" || $sRows[mode] == "") ? substr($sRows[cate],0,3).$this->base.substr($sRows[cate],-6) : $sRows[mode];
	$this->result .= '<li class="childe';
	if($sRows[cate] == __CATE__) { $this->result .= ' active'; } //현재 선택된 카테고리
	if($sRows[mode] == 'url') {
		$this->result .= '"><a href="'.$sRows[url].'" target="'.$sRows[target].'"><span class="gray">└</span> '.$sRows[name].'</a>';
	} else {
		$this->result .= '"><a href="'.$cfg[droot].'index.php?cate='.$moveCate.'" target="'.$sRows[target].'"><span class="gray">└</span> '.$sRows[name].'</a>';
	}
	$this->result .= '</li>';
}
$this->result .= '</ul></li>';

$this->result .= '</ul>';
$this->result .= '</div>';
unset($n);
?>
