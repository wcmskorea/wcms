<?php
/**
 * 최근 게시물 탭방식
 * Relationship : /modules/displayTabCell.php
 */

$sql	= " SELECT * FROM `display__".$GLOBALS['cfg']['skin']."` WHERE position='".$this->displayPos."' AND form='".$this->form."' ORDER BY sort ASC ";
$rst 	= @mysql_query($sql);
$tabCnt = @mysql_num_rows($rst);

$n		= 0;
$subTab	= array();
$tabClass = ($this->config['useTitle'] == 'image') ? "tabRecentImg" : "tabRecent";

$this->result .= '<div id="tab'.$this->prefix.'" class="'.$tabClass.'" style="float:left; width:'.$this->config['width'].'; height:'.intval(preg_replace('/px|%/',null,$this->config['height'])-2).'px; background-color:'.$this->config['bgColor'].'; margin:'.$this->config['mgt'].' '.$this->config['mgr'].' '.$this->config['mgb'].' '.$this->config['mgl'].'; padding:'.$this->config['pdt'].' '.$this->config['pdr'].' '.$this->config['pdb'].' '.$this->config['pdl'].'; overflow:hidden;">'.PHP_EOL;
$this->result .= '<ul class="tabBox" style="background:url('.__SKIN__.'image/background/bg_recentTabMenu_'.strtolower($this->prefix).'.gif) repeat-x left bottom;">'.PHP_EOL;

while($Rows = @mysql_fetch_array($rst))
{
	$this->skin		= null;
	$tabClass		= ($n == 0) ? " on" : null;
	$first			= ($n == 0) ? $Rows['cate'] : $first;
	$borderClass	= ($n + 1) == $tabCnt ? " no_border_r" : null;
	$action			= "$.tabMenu('tab".$this->displayPos.$Rows['cate']."','#tabBody".$this->displayPos.$Rows['cate']."','./addon/system/displayTabCell.php?type=recentData&cate=".$Rows['cate']."&position=".$this->displayPos."&ftab=".$first."&group=".$this->tabCount."',null,".preg_replace('/px|%/',null,$this->config['height']).");";
	$this->result	.= '<li class="tab'.$tabClass.$borderClass.'" id="tab'.$this->displayPos.$Rows['cate'].'"><p><a href="javascript:;" onmouseover="'.$action.'" onfocus="'.$action.'">'.$this->printTab("recentTab", $Rows['name'], $Rows['cate']).'</a></p></li>'.PHP_EOL;
	array_push($subTab, $Rows['cate']);
	$n++;
}

$this->result .= '</ul>'.PHP_EOL;
//$this->result .= '<div class="clear"></div>'.PHP_EOL;

//more버튼 노출 여부
if($this->config['useMore'] == 'Y') { $this->result .= '<div class="tabMore" id="tab'.$this->displayPos.$first.'_more"></div>'.PHP_EOL; }

foreach($subTab AS $key=>$value)
{
	//메뉴 활성화
	$tabClass = ($key == 0) ? "show" : "hide";
	$this->result .= '<div id="tabBody'.$this->displayPos.$value.'" class="tabBody"></div>'.PHP_EOL;
}
$this->result .= '<script type="text/javascript">
$(document).ready(function()
{
	$.insert("#tabBody'.$this->displayPos.$first.'","./addon/system/displayTabCell.php?type=recentData&cate='.$first.'&position='.$this->displayPos.'&ftab='.$first.'&group='.$this->tabCount.'",null,'.preg_replace('/px|%/',null,$this->config['height']).');
});
</script>'.PHP_EOL;
$this->result .= '</div>'.PHP_EOL;
unset($tabClass,$first,$action,$subTab,$n,$rst,$Rows);
?>
