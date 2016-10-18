<?php
/* --------------------------------------------------------------------------------------
| 배너모듈 (가로형) : Add-on
|----------------------------------------------------------------------------------------
| Relationship : ./addon/display.php
| Last (2009-11-14 : 이성준)
|----------------------------------------------------------------------------------------
| $this->disp 배열값
| [0-3]box공백, [4-5]노출개수/공백, [6-8]노출타입/배속/지연, [9-11]타이틀/제목/날짜 노출
*/
$squery				= ($this->config['docType'] == 'R') ? "ORDER BY RAND() Limit ".$this->config['docCount'] : "ORDER BY seq ASC Limit ".$this->config['docCount'];
$width				= preg_replace('/px|%/',null,$this->config['width']);
$height				= preg_replace('/px|%/',null,$this->config['height']);
$scrollHeight = preg_replace('/px|%/',null,$this->config['height']) * $this->config['docSpeed'];

$this->result .= '<div class="banner" style="width:'.intval($this->config['width']-10).'px; height:'.intval($this->config['height']-10).'px; background:url('.__SKIN__.'image/background/bg_recent_'.$this->sort.'.gif) no-repeat;">'.PHP_EOL;
$this->result .= '<ul id="banner_'.$this->displayPos.'">'.PHP_EOL;


$query	= " SELECT * FROM `".$this->config['module']."__content` WHERE (position='".$this->displayPos."' OR position='') AND speriod<'".time()."' AND (eperiod='0' OR eperiod>'".time()."') AND hidden='N' ".$squery;
$rst    = @mysql_query($query);
$n			= 0;
while($Rows	= @mysql_fetch_array($rst))
{
	$docPad = ($n < 1) ? "0" : $this->config['docPad'];
	$dir  = str_replace($GLOBALS['cfg']['site']['lang']."/",null,__SKIN__)."data/".date("Y", $Rows[date])."/".date("m", $Rows[date])."/".$Rows['fileName'];

	if($Rows['url'])
	{
		$this->result .= '<li style="width:'.$this->config['width'].'px"><a href="'.$Rows['url'].'"'.($Rows['target'] == 'none' || $Rows['target'] == '' ? '':' target="'.$Rows['target'].'"').'"><img src="'.$dir.'" class="thumbNail" style="width:'.$Rows['width'].'px; height:'.$Rows['height'].'px;" onerror="this.src=\''.$this->cfg['droot'].'common/image/noimg_m.gif\'" onmouseover="overClass(this);" onmouseout="overClass(this);" onfocus="overClass(this);" onblur="overClass(this);" alt="'.$Rows['subject'].'" title="'.$n.'" /></a></li>'.PHP_EOL;
	} else {
		$this->result .= '<li style="width:'.$this->config['width'].'px"><img src="'.$dir.'" class="thumbNail" style="width:'.$Rows['width'].'px; height:'.$Rows['height'].'px;" onerror="this.src=\''.$this->cfg['droot'].'common/image/noimg_m.gif\'" onmouseover="overClass(this);" onmouseout="overClass(this);" onfocus="overClass(this);" onblur="overClass(this);" alt="'.$Rows['subject'].'" title="'.$n.'" /></li>'.PHP_EOL;
	}
	$n++;
}
$this->result .= '</ul><div class="clear"></div></div>'.PHP_EOL;
if($this->config['docType'] == 'SH')
{
	$this->result .= '<script type="text/javascript">$(document).ready(function(){$("#recent'.$this->prefix.' ul").scrollList("right", '.$scrollHeight.', '.$this->config['docDelay'].'000);});</script>'.PHP_EOL;
}
unset($squery,$width,$height,$scrollHeight,$subjectWidth,$n,$rst,$Rows,$date,$icon);
?>


<div class="section3">
	<h2><img src="http://www.nonghyup.com/images/menu/main_stit3.gif" alt="이벤트 &amp; 배너" /></h2>
	<ul id="slides1" class="slide-list">
		<li style="width:308px;"><a href=""><img src="http://www.nonghyup.com/Upload/images/Data/634677681500611495.jpg" alt="" /></a></li>
		<li style="width:308px;"><a href=""><img src="http://www.nonghyup.com/Upload/images/Data/634789127978476557.jpg" alt="" /></a></li>
		<li style="width:308px;"><a href=""><img src="http://www.nonghyup.com/Upload/images/Data/634764781471174134.jpg" alt="" /></a></li>
	</ul>