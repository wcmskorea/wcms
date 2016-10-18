<?php
/**--------------------------------------------------------------------------------------
 * 최근 게시물 (일반형)
 *---------------------------------------------------------------------------------------
 * Relationship : /_Lib/classDisplay.php
 *---------------------------------------------------------------------------------------
 */

//리스트 순서 설정 : 2013-06-05 - 환경설정 추가
$this->config['sortType'] = ($this->config['sortType']) ? $this->config['sortType'] : "regDate";

//슬라이드 설정
$this->config['docStart'] = ($this->config['docStart']) ? $this->config['docStart'] : "false";
$this->config['docEsing'] = ($this->config['docEsing']) ? $this->config['docEsing'] : "";
$this->config['docBtn'] = ($this->config['docBtn']) ? $this->config['docBtn'] : "false";
$this->config['docPager'] = ($this->config['docPager']) ? $this->config['docPager'] : "false";
$this->config['docTicker'] = ($this->config['docTicker']) ? $this->config['docTicker'] : "false";

//$this->config['docPadT']  = ($this->config['docPadT']) ? $this->config['docPadT'] : $this->config['docPad'];	// 스킨 여백(상) 추가(2012-11-22)

$squery				 = ($this->config['useUnion'] == "Y") ? "cate like '".$this->share."%'" : "cate='".$this->share."'";
$squery				.= ($this->config['useNotice'] == 'Y') ? " AND useNotice='Y'" : "";
$squery				.= " AND idxTrash='0'";
$squery				.= ($this->config['docType'] == 'R') ? " ORDER BY RAND() Limit ".$this->config['docCount'] : " ORDER BY ".$this->config['sortType']." DESC Limit ".$this->config['docCount'];

$width				= preg_replace('/px|%/',null,$this->config['width']);
$height				= preg_replace('/px|%/',null,$this->config['height']);
$scrollHeight	= 20 * $this->config['docSpeed'];
$subjectWidth	= $width - ($this->config['docPad']*2);

$this->result	.= '<div class="recentBody" style="width:'.$this->config['width'].'; height:'.$this->config['height'].'; background:url('.__SKIN__.'image/background/bg_recent_'.strtolower($this->prefix).'.gif) no-repeat">'.PHP_EOL;
$this->result	.= '<ul id="docRecent'.$this->prefix.'" style="width:'.$this->config['width'].'">'.PHP_EOL;

$n = 0;
$rst = mysql_query(" SELECT * FROM `".$this->config['module']."__content` WHERE ".$squery);
//$this->result	.= '<!--query '." SELECT * FROM `".$this->config['module']."__content` WHERE ".$squery.' -->';
while($Rows = @mysql_fetch_assoc($rst))
{
	$Rows['subject'] = stripslashes($Rows['subject']);
	$Rows['subject'] = htmlspecialchars($Rows['subject']);
	$Rows['subject'] = Functions::cutStr($Rows['subject'], $this->config['cutSubject'],"...");
	preg_match_all("/\[(.+)\]/", $Rows['subject'], $out, PREG_PATTERN_ORDER);
	$subject = str_replace($out[1][0], '<span style="color:'.$GLOBALS['cfg']['docHeadColor'][$out[1][0]].'">'.$out[1][0].'</span>', $Rows['subject']);
	$date = ($this->config['useDate'] == 'Y') ? date("y.m.d",$Rows['regDate']) : null;
	$icon = Functions::iconNew($Rows['regDate'], (86400*3), '<span class="icon"><img src="'.__SKIN__.'image/icon/new.gif" width="11" height="13" alt="최근 게시물" /></span>');

	if($this->config['docLink'] == 'L')  //컨텐츠 클릭시 목록으로 이동 추가(20120522)
		$url 			= '<a href="./?cate='.$this->cate.'" title="'.$Rows['subject'].'" target="'.$this->target.'">';
	else {
		if($Rows['useSecret'] == "Y"){ $typeString = 'type='.$GLOBALS['sess']->encode('secret');}
		else{ $typeString = "type=view";}
		$url = '<a
	href="./?cate='.$this->cate.'&amp;'.$typeString.'&amp;num='.$Rows['seq'].'&amp;productNum='.$Rows['productSeq'].'" title="'.$Rows['subject'].'" target="'.$this->target.'">';
	}
	$this->result .= '<li class="docTitle" style="width:'.$subjectWidth.'px; margin:0 '.$this->config['docPad'].'; '.($this->config['docPadT'] ? 'padding-top:'.$this->config['docPadT'] : '').';"><span class="title">'.$url.$subject.'</a></span>'.$icon.'<span class="date" style="right:'.$this->config['docPad'].';">'.$date.'</span></li>'.PHP_EOL;
	$n++;
}

while($n < $this->config['docCount'])
{
	$this->result .= '<li class="docTitle" style="width:'.$subjectWidth.'px; margin:0 '.$this->config['docPad'].';"><span class="title" style="color:#999;">...</span><span class="date" style="right:'.$this->config['docPad'].';"></span></li>'.PHP_EOL;
	$n++;
}

$this->result .= '</ul><div class="clear"></div></div>'.PHP_EOL;

$this->result .= '<script type="text/javascript">$(document).ready(function(){$("#docRecent'.$this->prefix.'").bxSlider({mode:"'.$this->config['docType'].'",controls:'.$this->config['docBtn'].',pager:'.$this->config['docPager'].',wrapperClass:"docRecent'.$this->prefix.'_wrap",auto:'.$this->config['docStart'].',speed:'.$this->config['docSpeed'].',pause:'.$this->config['docDelay'].',ticker:'.$this->config['docTicker'].',tickerSpeed:'.$this->config['docDelay'].',displaySlideQty:'.$this->config['docCount'].'});});</script>'.PHP_EOL;

unset($config,$squery,$width,$height,$scrollHeight,$subjectWidth,$n,$rst,$Rows,$date,$icon);
?>