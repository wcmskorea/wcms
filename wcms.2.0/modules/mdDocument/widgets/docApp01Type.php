<?php
/**--------------------------------------------------------------------------------------
 * 최근 게시물 (일반형)
 *---------------------------------------------------------------------------------------
 * Relationship : /_Lib/classDisplay.php
 *---------------------------------------------------------------------------------------
 */

$squery				 = ($this->config['useUnion'] == "Y") ? "cate like '".$this->cate."%'" : "cate='".$this->cate."'";
$squery				.= ($this->config['useNotice'] == 'Y') ? " AND useNotice='Y'" : "";
$squery				.= " AND idxTrash='0'";
$squery				.= ($this->config['docType'] == 'R') ? " ORDER BY RAND() Limit ".$this->config['docCount'] : " ORDER BY idx DESC Limit ".$this->config['docCount'];

$width				= preg_replace('/px|%/',null,$this->config['width']);
$height				= preg_replace('/px|%/',null,$this->config['height']);
$scrollHeight	= 20 * $this->config['docSpeed'];
$subjectWidth	= $width - ($this->config['docPad']*2);

$this->result	.= '<div class="recentBody" style="width:'.$this->config['width'].'; height:'.$this->config['height'].'; background:url('.__SKIN__.'image/background/bg_recent_'.strtolower($this->prefix).'.gif) no-repeat left top;">'.PHP_EOL;
$this->result	.= ($this->config['docType'] == 'SH') ? '<ul style="width:'.$this->config['width'].';">'.PHP_EOL : '<ul>'.PHP_EOL;

$n = 0;
$rst = mysql_query(" SELECT * FROM `".$this->config['module']."__content` WHERE ".$squery);
while($Rows = @mysql_fetch_assoc($rst))
{
	$Rows['subject'] = stripslashes($Rows['subject']);
	$Rows['subject'] = htmlspecialchars($Rows['subject']);
	$Rows['subject'] = Functions::cutStr($Rows['subject'], $this->config['cutSubject'],"...");
	$date = ($this->config['useDate'] == 'Y') ? date("m.d",$Rows['regDate']) : null;
	$icon = Functions::iconNew($Rows['regDate'], (86400*3), '<span class="icon"><img src="'.__SKIN__.'image/icon/new.gif" width="11" height="13" alt="최근 게시물" /></span>');

	//비밀글
	global $sess;

	if($this->config['docLink'] == 'L')  //컨텐츠 클릭시 목록으로 이동 추가(20120522)
		$url 			= '<a href="'.$GLOBALS['cfg']['droot'].'?cate='.$Rows['cate'].'" title="'.$Rows['subject'].'" target="'.$this->target.'">';
	else {
		if($Rows['useSecret'] == "Y"){ $typeString = 'type='.$sess->encode('secret');}
		else{ $typeString = "type=view";}
		$url = '<a
	href="'.$GLOBALS['cfg']['droot'].'?cate='.$Rows['cate'].'&amp;'.$typeString.'&amp;num='.$Rows['seq'].'&amp;productNum='.$Rows['productSeq'].'" title="'.$Rows['subject'].'" target="'.$this->target.'">';
	}

	$this->result .= '<li class="docTitle" style="width:'.$subjectWidth.'px; margin:0 '.$this->config['docPad'].'"><span class="title">'.$url.$Rows['subject'].'</a></span>'.$icon.'<span class="date" style="right:'.$this->config['docPad'].';">'.$date.'</span></li>'.PHP_EOL;
	$n++;
}
while($n < $this->config['docCount'])
{
	$this->result .= '<li class="docTitle" style="width:'.$subjectWidth.'px; margin:0 '.$this->config['docPad'].';"><span class="title" style="color:#999;">'.Functions::cutStr('저희 사이트를 방문해주셔서 감사합니다...', $this->config['cutSubject'],"...").'</span><span class="date" style="right:'.$this->config['docPad'].';">'.date("m.d").'</span></li>'.PHP_EOL;
	$n++;
}

$this->result .= '</ul><div class="clear"></div></div>'.PHP_EOL;

if($this->config['docType'] == 'SH')
{
	$this->result .= '<script type="text/javascript">$(document).ready(function(){$("#recent_'.$this->cate.' ul").scrollList("right", '.$scrollHeight.', '.$this->config['docDelay'].'000);});</script>';
}
else if($this->config['docType'] == 'SV')
{
	$this->result .= '<script type="text/javascript">$(document).ready(function(){$("#recent_'.$this->cate.' ul").scrollList("up", '.$scrollHeight.', '.$this->config['docDelay'].'000);});</script>';
}
unset($config,$squery,$width,$height,$scrollHeight,$subjectWidth,$n,$rst,$Rows,$date,$icon);
?>
