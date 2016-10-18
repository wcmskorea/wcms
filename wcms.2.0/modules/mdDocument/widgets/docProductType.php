<?php
/**--------------------------------------------------------------------------------------
 * 최근 게시물 (웹진형) : Add-on
 *---------------------------------------------------------------------------------------
 * Relationship : ./lib/classDisplay.php
 * Last (2009.11.20 : 이성준)
 *---------------------------------------------------------------------------------------
 * $this->disp 배열값
 * ['0-3']box공백, ['4-5']노출개수/공백, ['6-8']노출타입/배속/지연, ['9-11']타이틀/제목/날짜 노출
 */
$squery				 = ($this->config['useUnion'] == "Y") ? "cate like '".$this->cate."%'" : "cate='".$this->cate."'";
$squery				.= ($this->config['useNotice'] == 'Y') ? " AND useNotice='Y'" : "";
$squery				.= " AND idxTrash='0'";
$squery .= ($this->config['docType'] == 'R') ? " ORDER BY RAND() Limit ".$this->config['docCount'] : " ORDER BY idx DESC Limit ".$this->config['docCount'];

$thumbType = explode(",", $this->config['thumbType']); //비율  
$contentWidth = preg_replace('/px|%/',null,$this->config['width']) - ($this->config['docPad']*2) - preg_replace('/px|%/',null,$this->config['imgWidth']) - 10;
$scrollHeight = preg_replace('/px|%/',null,$this->config['imgHeight']) * $this->config['docSpeed'];

$this->result .= '<div class="recentBody" style="width:'.$this->config['width'].'; height:'.$this->config['height'].'; background:url('.__SKIN__.'image/background/bg_recent_'.strtolower($this->prefix).'.gif) no-repeat left top;">'.PHP_EOL;
$this->result .= ($this->config['docType'] == 'SH') ? '<ul style="width:'.$this->config['width'].';">'.PHP_EOL : '<ul style="margin:auto;">'.PHP_EOL;

$n = 0;
$rst = @mysql_query(" SELECT * FROM `".$this->config['module']."__content` WHERE productSeq > 0 AND idxDepth = 0 AND ".$squery);
while($Rows	= @mysql_fetch_assoc($rst))
{
	//썸네일 이미지
	$thumb = @mysql_fetch_assoc(@mysql_query("SELECT fileName,regDate FROM `mdProduct__file` WHERE parent='".$Rows['productSeq']."' AND LOWER(extname) IN ('jpg','gif','png','bmp') ORDER BY seq ASC Limit 1"));
	$dir = str_replace($GLOBALS['cfg']['site']['lang']."/",null,__SKIN__)."data/".date("Y",$thumb['regDate'])."/".date("m",$thumb['regDate'])."/s-".$thumb['fileName'];

	$Rows['subject'] = stripslashes($Rows['subject']);
	$Rows['subject'] = strip_tags($Rows['subject']);
	$Rows['subject'] = Functions::cutStr($Rows['subject'], $this->config['cutSubject'], "...");
	$Rows['content'] = stripslashes($Rows['content']);
	$Rows['content'] = strip_tags($Rows['content']);
	$Rows['content'] = str_replace("&nbsp;", null, $Rows['content']);
	$Rows['content'] = Functions::cutStr($Rows['content'], $this->config['cutContent'], "...");
	$icon = Functions::iconNew($Rows['regDate'], (86400*1), '<span class="icon"><img src="'.__SKIN__.'image/icon/new.gif" width="11" height="13" alt="최근 게시물" /></span>');
	$date = date("m.d", $Rows['regDate']);
	if($this->config['docLink'] == 'L')  //컨텐츠 클릭시 목록으로 이동 추가(20120522)
		$url 			= '<a href="'.$GLOBALS['cfg']['droot'].'?cate='.$Rows['cate'].'" title="'.$Rows['subject'].'" target="'.$this->target.'">';
	else
		$url 			= '<a href="'.$GLOBALS['cfg']['droot'].'?cate='.$Rows['cate'].'&type=view&num='.$Rows['seq'].'" title="'.$Rows['subject'].'" target="'.$this->target.'">';

	$this->result .= '<li style="padding:'.$this->config['docPad'].' '.$this->config['docPad'].' 0 '.$this->config['docPad'].';">
    '.$url.'<span class="imgList"><img src="'.$dir.'" alt="'.$thumb['fileName'].'" class="thumbNail" style="width:'.$this->config['imgWidth'].'; height:'.$this->config['imgHeight'].';" onerror="this.src=\''.$GLOBALS['cfg']['droot'].'common/image/noimg_m.gif\'" onmouseover="overClass(this);" onmouseout="overClass(this);" /></span><strong>'.$Rows['subject'].'</strong></a>
    <p class="content" style="width:'.$contentWidth.'px;">'.$Rows['content'].'</p>
	</li>'.PHP_EOL;
	$n++;
}
/*
while($n < $this->config['docCount'])
{
	$this->result .= '<li style="padding:'.$this->config['docPad'].' '.$this->config['docPad'].' 0 '.$this->config['docPad'].';">
    <a href="#none" title="준비중" target="'.$this->target.'">
    <span class="imgList"><img src="'.$GLOBALS['cfg']['droot'].'common/image/noimg_s.gif" alt="image" class="thumbNail" style="width:'.$this->config['imgWidth'].'; height:'.$this->config['imgHeight'].';" onerror="this.src=\''.$GLOBALS['cfg']['droot'].'common/image/noimg_m.gif\'" onmouseover="overClass(this);" onmouseout="overClass(this);" /></span><strong>'.Functions::cutStr("홈페이지를 준비중입니다...", $this->config['cutSubject'], "...").'</strong></a>
    <p class="content" style="width:'.$contentWidth.'px;">홈페이지를 준비중입니다...</p>
	</li>'.PHP_EOL;
	$n++;
}
*/
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
