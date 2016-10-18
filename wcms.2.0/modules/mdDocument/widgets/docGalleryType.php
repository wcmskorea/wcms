<?php
/**--------------------------------------------------------------------------------------
 * 최근 게시물 (갤러리형/일반형) : Add-on
 *---------------------------------------------------------------------------------------
 * Relationship : ./lib/classDisplay.php
 * Last (2012.05.22 : 오혜진)
 */

//슬라이드 설정
$this->config['docStart'] = ($this->config['docStart']) ? $this->config['docStart'] : "false";
$this->config['docEsing'] = ($this->config['docEsing']) ? $this->config['docEsing'] : "";
$this->config['docBtn'] = ($this->config['docBtn']) ? $this->config['docBtn'] : "false";
$this->config['docPager'] = ($this->config['docPager']) ? $this->config['docPager'] : "false";
$this->config['docTicker'] = ($this->config['docTicker']) ? $this->config['docTicker'] : "false";

$this->config['docPadT']  = ($this->config['docPadT']) ? $this->config['docPadT'] : $this->config['docPad'];	// 스킨 여백(상) 추가(2012-10-31)

//쿼리
$squery  = ($this->config['useUnion'] == "Y") ? "cate like '".$this->cate."%'" : "cate='".$this->cate."'";
$squery .= ($this->config['useNotice'] == 'Y') ? " AND useNotice='Y'" : "";
switch($this->config['useData']) {
	case "all": default: $squery .= null; break;
	case "image": $squery .= " AND fileCount>'0'"; break;
	case "text": $squery .= " AND fileCount='0'"; break;
}
$squery .= " AND idxDepth='0' AND idxTrash='0'";
$squery .= ($this->config['docType'] == 'R') ? " ORDER BY RAND() Limit ".$this->config['docCount'] : " ORDER BY regDate DESC, idx DESC Limit ".$this->config['docCount'];

$thumbType = explode(",", $this->config['thumbType']); //비율
$scrollHeight = preg_replace('/px|%/',null,$this->config['imgHeight']) * $this->config['docSpeed'];

$this->result .= '<div class="recentThumb" style="width:'.$this->config['width'].'; height:'.$this->config['height'].'; background:url('.__SKIN__.'image/background/bg_recent_'.strtolower($this->prefix).'.gif) no-repeat left top;">'.PHP_EOL;
$this->result	.= '<ul id="docRecent'.$this->prefix.'" style="width:'.$this->config['width'].'">'.PHP_EOL;

$n = 0;
$rst = @mysql_query(" SELECT * FROM `".$this->config['module']."__content` WHERE ".$squery);
while($Rows	= @mysql_fetch_assoc($rst))
{
	$Rows['subject']= stripslashes($Rows['subject']);
	$Rows['subject']= htmlspecialchars($Rows['subject']);
	$Rows['subject']= Functions::cutStr($Rows['subject'],$this->config['cutSubject'],"...");
	$thumb 			= @mysql_fetch_assoc(@mysql_query("SELECT fileName,regDate FROM `".$this->config['module']."__file` WHERE parent='".$Rows['seq']."' AND LOWER(extname) IN ('jpg','gif','png','bmp') ORDER BY seq ASC Limit 1"));
	$dir 			= str_replace($GLOBALS['cfg']['site']['lang']."/",null,__SKIN__)."data/".date("Y",$thumb['regDate'])."/".date("m",$thumb['regDate'])."/s-".$thumb['fileName'];
	$icon 			= Functions::iconNew($Rows['regDate'], (86400*1), '<span class="icon"><img src="'.__SKIN__.'image/icon/new.gif" width="11" height="13" alt="최근 게시물" /></span>');
	if($this->config['docLink'] == 'L')  //컨텐츠 클릭시 목록으로 이동 추가(20120522)
		$url 			= '<a href="'.$GLOBALS['cfg']['droot'].'?cate='.$Rows['cate'].'" title="'.$Rows['subject'].'" target="'.$this->target.'">';
	else
		$url 			= '<a href="'.$GLOBALS['cfg']['droot'].'?cate='.$Rows['cate'].'&type=view&num='.$Rows['seq'].'" title="'.$Rows['subject'].'" target="'.$this->target.'">';
	$date 			= date("Y.m.d", $Rows['regDate']);

	$this->result .= '<li class="cell" style="padding-top:'.$this->config['docPadT'].'; padding-left:'.$this->config['docPad'].';"><div style="position:relative;">'.$url.'<img src="'.$dir.'" class="thumbNail" style="width:'.$this->config['imgWidth'].'; height:'.$this->config['imgHeight'].';" onerror="this.src=\'/common/image/noimg_m.gif\'" onmouseover="overClass(this);" onmouseout="overClass(this);" onfocus="overClass(this);" onblur="overClass(this);" alt="'.$Rows['subject'].'" /></a><div class="icon">'.$icon.'</div></div>';
	if($this->config['cutSubject']!='0') { $this->result .= '<div class="title" style="width:'.$this->config['imgWidth'].';">'.$url.$Rows['subject'].'</a></div>'.PHP_EOL; }
	if($this->config['useDate'] == 'Y') { $this->result .= '<div class="nameAndDate"><p class="date" style="width:'.$this->config['imgWidth'].';">'.$date.'</p></div>'.PHP_EOL; }
	$this->result .= '</li>'.PHP_EOL;
	$n++;
}
while($n < $this->config['docCount'])
{
	$this->result .= '<li class="cell" style="padding-top:'.$this->config['docPadT'].'; padding-left:'.$this->config['docPad'].';"><div style="position:relative;"><a href="#none"><img src="/user/default/image/icon/noimg_m.gif" onerror="this.src=\''.$GLOBALS['cfg']['droot'].'common/image/noimg_s.gif\'" alt="준비중" class="thumbNail" style="width:'.$this->config['imgWidth'].'; height:'.$this->config['imgHeight'].';" /></a></div>';
	if($this->config['useTitle'] != 'N') $this->result .= '<div class="title" style="'.$this->config['imgWidth'].';">...</div>';
	if($this->config['useDate'] == 'Y') $this->result .= '<div class="nameAndDate"><p class="date" style="width:'.$this->config['imgWidth'].';">0000.00.00</p></div>';
	$this->result .= '</li>'.PHP_EOL;
	$n++;
}
$this->result .= '</ul><div class="clear"></div>'.PHP_EOL;
//more버튼 노출 여부
if($this->config['useMore'] == 'Y') { $this->result .= '<div class="more"><a href="'.$GLOBALS['cfg']['droot'].'index.php?cate='.$this->cate.'"><img src="'.__SKIN__.'image/button/recentTabMore_'.strtolower($this->prefix).'.gif" alt="더보기" title="더보기" /></a></div>'.PHP_EOL; }
$this->result .= '</div>'.PHP_EOL;

if($this->config['docType'] != "false") {
	$this->result .= '<script type="text/javascript">$(document).ready(function(){$("#docRecent'.$this->prefix.'").bxSlider({mode:"'.$this->config['docType'].'",controls:'.$this->config['docBtn'].',pager:'.$this->config['docPager'].',wrapperClass:"docRecent'.$this->prefix.'_wrap",auto:'.$this->config['docStart'].',speed:'.$this->config['docSpeed'].',pause:'.$this->config['docDelay'].',ticker:'.$this->config['docTicker'].',tickerSpeed:'.$this->config['docDelay'].',displaySlideQty:'.$this->config['docCount'].'});});</script>'.PHP_EOL;
}
unset($squery,$thumbType,$width,$height,$scrollHeight,$n,$rst,$Rows,$thumb,$dir,$icon,$url,$date);
?>
