<?php
/**--------------------------------------------------------------------------------------
 * 최근 게시물 (일반형)
 *---------------------------------------------------------------------------------------
 * Relationship : /_Lib/classDisplay.php
 *---------------------------------------------------------------------------------------
 */

$squery				= ($this->config['useUnion'] == "Y") ? "cate like '".$this->cate."%'" : "cate='".$this->cate."'";
$squery				.= ($this->config['docType'] == 'R') ? " ORDER BY RAND() Limit ".$this->config['docCount'] : " ORDER BY dateReg DESC Limit ".$this->config['docCount'];

$width				= preg_replace('/px|%/',null,$this->config['width']);
$height				= preg_replace('/px|%/',null,$this->config['height']);
$scrollHeight	= 20 * $this->config['docSpeed'];
$subjectWidth	= $width - ($this->config['docPad']*2);

$this->result	.= '<div class="recentBody" style="width:'.$this->config['width'].'; height:'.$this->config['height'].'; background:url('.__SKIN__.'image/background/bg_recent_'.strtolower($this->prefix).'.gif) no-repeat left top;">'.PHP_EOL;
$this->result	.= ($this->config['docType'] == 'SH') ? '<ul style="width:'.$this->config['width'].';">'.PHP_EOL : '<ul>'.PHP_EOL;

$n = 0;
$rst = mysql_query(" SELECT * FROM `".$this->config['module']."__content` WHERE ".$squery);


$statusQuery = @mysql_fetch_array(@mysql_query(" SELECT config FROM `mdApp01__` WHERE cate='".$this->cate."' "));
$statusArray = (array)unserialize($statusQuery['config']);
$status = ($statusArray['result']) ? explode(",", $statusArray['result']) : "";

while($Rows = @mysql_fetch_assoc($rst))
{
	$appStatus  = '['.$status[$Rows['state']].']';
	$name       = $Rows['name'];
	$name       = mb_strcut($name,0,4,'utf-8'); //글자 한자 자르기
	$name      .= "OO";
	$mobile     = substr($Rows['mobile'], 0, 3);
	$date       = ($this->config['useDate'] == 'Y') ? date("m.d",$Rows['dateReg']) : null;

	
	if($Rows['state'] == "0"):
		$strColor = "colorRed";
	//elseif($Rows['state'] == "1"):
	//	$strColor = "blue";
	else:
		$strColor = "colorGreen";
	endif;

	$this->result .= '<li class="docTitle" style="width:'.$subjectWidth.'px; margin:0px '.$this->config['docPad'].'">
										<span class="'.$strColor.'">'.$appStatus.'</span>
										<span class="title"><a href="'.$GLOBALS['cfg']['droot'].'?cate='.$Rows['cate'].'&amp;'.$typeString.'&amp;num='.$Rows['seq'].'" title="'.$name.'" target="'.$this->target.'">'.$name.'</a></span>
										<span class="phone">'.$mobile.'-****-****</span>
										<span class="date" style="right:'.$this->config['docPad'].';">'.$date.'</span></li>'.PHP_EOL;

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
