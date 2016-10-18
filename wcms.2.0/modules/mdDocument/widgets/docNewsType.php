<?php
/**--------------------------------------------------------------------------------------
 * 최근 게시물 (뉴스형) : widget
 *---------------------------------------------------------------------------------------
 * Relationship : ./_Lib/classDisplay.php
 * Last (2009.11.20 : 이성준)
 *---------------------------------------------------------------------------------------
 */
$squery				 = ($this->config['useUnion'] == "Y") ? "cate like '".$this->cate."%'" : "cate='".$this->cate."'";
$squery				.= ($this->config['useNotice'] == 'Y') ? " AND useNotice='Y'" : "";
$squery				.= " AND idxTrash='0'";
$squery .= ($this->config['docType'] == 'R') ? " ORDER BY RAND() Limit ".$this->config['docCount'] : " ORDER BY idx DESC Limit ".$this->config['docCount'];

$thumbType = explode(",", $this->config['thumbType']); //비율  
$scrollHeight = preg_replace('/px|%/',null,$this->config['imgHeight']) * $this->config['docSpeed'];
$contentCut = (preg_replace('/px|%/',null,$this->config['imgHeight'])/15) * $this->config['useCut'];

//썸네일 이미지
$thumb = @mysql_fetch_assoc(@mysql_query("SELECT parent,fileName,regDate FROM `".$this->config['module']."__file` WHERE ".$squery." AND LOWER(extname) IN ('jpg','gif','png','bmp') ORDER BY seq ASC Limit 1"));
$dir = str_replace($GLOBALS['cfg']['site']['lang']."/",null,__SKIN__)."data/".date("Y",$thumb['regDate'])."/".date("m",$thumb['regDate'])."/s-".$thumb['fileName'];

$this->result .= '<div class="recentBody" style="width:'.$this->config['width'].'; height:'.$this->config['height'].'; background:url('.__SKIN__.'image/background/bg_recent_'.strtolower($this->prefix).'.gif) no-repeat left top;">'.PHP_EOL;
$this->result .= ($this->config['docType'] == 'SH') ? '<ul style="width:'.$this->config['width'].';">'.PHP_EOL : '<ul style="margin:auto;">'.PHP_EOL;

//이미지 출력
if($thumb) {
	$this->result .= '<li style="margin-top:'.$this->config['docPad'].';"><a href="/?cate='.$thumb['cate'].'&type=view&num='.$thumb['parent'].'"><img src="'.$dir.'" alt="'.$thumb['fileName'].'" class="thumbNail" style="width:'.$this->config['imgWidth'].'; height:'.$this->config['imgHeight'].';" onerror="this.src=\''.$GLOBALS['cfg']['droot'].'common/image/noimg_m.gif\'" onmouseover="overClass(this);" onmouseout="overClass(this);" /></a></li>'.PHP_EOL;
}

//리스트 출력
$n = 0;
$rst = @mysql_query("SELECT * FROM `".$this->config['module']."__content` WHERE ".$squery);
while($Rows = @mysql_fetch_assoc($rst))
{
	$Rows['subject'] = stripslashes($Rows['subject']);
	$Rows['subject'] = htmlspecialchars($Rows['subject']);
	$Rows['subject'] = Functions::cutStr($Rows['subject'], $this->config['cutSubject'], "...");
	$icon = Functions::iconNew($Rows['regDate'], (86400*1), '<span class="icon"><img src="'.__SKIN__.'image/icon/new.gif" width="11" height="13" alt="최근 게시물" /></span>');
	$date = date("m.d", $Rows['regDate']);
	if($this->config['docLink'] == 'L')  //컨텐츠 클릭시 목록으로 이동 추가(20120522)
		$url 			= '<a href="'.$GLOBALS['cfg']['droot'].'?cate='.$Rows['cate'].'" title="'.$Rows['subject'].'" target="'.$this->target.'">';
	else
		$url 			= '<a href="'.$GLOBALS['cfg']['droot'].'?cate='.$Rows['cate'].'&type=view&num='.$Rows['seq'].'" title="'.$Rows['subject'].'" target="'.$this->target.'">';

	$this->result .= '<li style="margin-top:'.$this->config['docPad'].';"><span class="date">ㆍ</span><span class="title">'.$url.$Rows['subject'].'</a></span></li>'.PHP_EOL;
	$n++;
}
while($n < $this->config['docCount'])
{
	$this->result .= '<li style="margin-top:'.$this->config['docPad'].';"><span class="date">ㆍ</span><span class="title" style="color:#999;">저희 사이트를...</span></li>'.PHP_EOL;
	$n++;
}

$this->result .= '</ul><div class="clear"></div></div>'.PHP_EOL;

if($this->config['docType'] == 'SH')
{
	$this->result .= '<script type="text/javascript">$(document).ready(function(){$("#recent_'.$this->cate.' ul").scrollList("right", '.$scrollHeight.', '.$this->config['docDelay'].'000);});</script>';
} else if($this->config['docType'] == 'SV')
{
	$this->result .= '<script type="text/javascript">$(document).ready(function(){$("#recent_'.$this->cate.' ul").scrollList("up", '.$scrollHeight.', '.$this->config['docDelay'].'000);});</script>';
}
unset($squery,$thumbType,$width,$height,$scrollHeight,$n,$rst,$Rows,$thumb,$dir,$icon,$url,$date);
?>
