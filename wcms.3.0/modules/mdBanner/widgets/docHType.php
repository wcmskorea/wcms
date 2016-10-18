<?php
/* --------------------------------------------------------------------------------------
| 배너모듈 (가로형) 위젯
|----------------------------------------------------------------------------------------
| Relationship : ./_Lib/classDisplay.php
| Last (2012년 8월 3일 금요일 : 이성준)
|----------------------------------------------------------------------------------------
*/
$this->config['docEsing'] = ($this->config['docEsing']) ? $this->config['docEsing'] : "";
$this->config['docBtn'] = ($this->config['docBtn']) ? $this->config['docBtn'] : "false";
$this->config['docPager'] = ($this->config['docPager']) ? $this->config['docPager'] : "false";
$this->config['docCaption'] = ($this->config['docCaption']) ? $this->config['docCaption'] : "false";
$this->config['docThumb'] = ($this->config['docThumb']) ? $this->config['docThumb'] : "false";

$this->result .= '<div class="banner" style="width:'.$this->config['width'].'; height:'.$this->config['height'].'; background:url('.__SKIN__.'image/background/bg_recent_'.$this->sort.'.gif) no-repeat">'.PHP_EOL;
$this->result .= '<ul id="banner'.$this->prefix.'">'.PHP_EOL;

$n			= 0;
$rst    = @mysql_query(" SELECT * FROM `".$this->config['module']."__content` WHERE skin='".$GLOBALS['cfg']['skin']."' AND (position='".$this->displayPos."' OR position='') AND speriod<'".time()."' AND (eperiod='0' OR eperiod>'".time()."') AND hidden='N' ORDER BY seq ASC Limit ".$this->config['docCount']);
while($Rows	= @mysql_fetch_array($rst))
{
	//$class = ($n < 1) ? "first" : null;
	$dir  = str_replace($GLOBALS['cfg']['site']['lang']."/",null,__SKIN__)."data/".date("Y", $Rows[date])."/".date("m", $Rows[date])."/".$Rows['fileName'];
	$Rows['url'] = ($Rows['url']) ? $Rows['url'] : "javascript:;";

	if($Rows['url'])
	{
		$this->result .= '<li style="padding-left:'.$this->config['docPad'].';"><a href="'.$Rows['url'].'"'.($Rows['target'] == 'none' || $Rows['target'] == '' ? '':' target="'.$Rows['target'].'"').'><img src="'.$dir.'" class="thumbNail" style="width:'.$Rows['width'].'px; height:'.$Rows['height'].'px;" onerror="this.src=\''.$this->cfg['droot'].'common/image/noimg_m.gif\'" onmouseover="overClass(this);" onmouseout="overClass(this);" onfocus="overClass(this);" onblur="overClass(this);" alt="'.$Rows['subject'].'" title="'.$Rows['subject'].'" /></a></li>'.PHP_EOL;
	} else {
		$this->result .= '<li style="padding-left:'.$this->config['docPad'].';"><img src="'.$dir.'" class="thumbNail" style="width:'.$Rows['width'].'px; height:'.$Rows['height'].'px;" onerror="this.src=\''.$this->cfg['droot'].'common/image/noimg_m.gif\'" onmouseover="overClass(this);" onmouseout="overClass(this);" onfocus="overClass(this);" onblur="overClass(this);" alt="'.$Rows['subject'].'" title="'.$Rows['subject'].'" /></li>'.PHP_EOL;
	}
	if($this->config['docThumb'])
	{
		$docThumb .= '<a data-slide-index="'.$n.'" href=""><img class="thumbNailSmall" src="'.str_replace("/banner_","/bannerThumb_",$dir).'" style="width:'.$Rows['widthThumb'].'px;height:'.$Rows['heightThumb'].'px;" /></a>';
	}
	$n++;
}

$this->result .= '</ul><div class="clear"></div></div>'.PHP_EOL;
if($this->config['docThumb'])
{
	$this->result .= '<div class="bannerThumb" id="bx-pager"><p>'.$docThumb.'</p></div>'.PHP_EOL;
}
$this->result .= '<script type="text/javascript">$(document).ready(function(){$("#banner'.$this->prefix.'").bxSlider({mode:"horizontal",easing:"'.$this->config['docEasing'].'",controls:'.$this->config['docBtn'].',pager:'.$this->config['docPager'].',auto:'.$this->config['docStart'].',speed:'.$this->config['docSpeed'].',pause:'.$this->config['docDelay'].',ticker:'.$this->config['docType'].',tickerSpeed:'.$this->config['docDelay'].',displaySlideQty:'.$this->config['docCount'].',captions:'.$this->config['docCaption'].'});});</script>'.PHP_EOL;

unset($squery,$width,$height,$scrollHeight,$subjectWidth,$n,$rst,$Rows,$date,$icon);
?>