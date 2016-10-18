<?php
/**
 * 플래시 XML연동 메뉴
 */
$this->cate = ($this->caching) ? $this->cacheCate : __CATE__;
$this->title = strtolower($this->displayPos)."_menu".$this->sort.'.swf';
if(is_file(__HOME__.'image/'.$this->title))
{
	$this->result .= '<div id="flashXnavi'.$this->cate.'" class="flashXnavi" style="width:'.$this->config['width'].'; height:'.$this->config['height'].'; position:relative;"><div class="design"><script type="text/javascript">flashWrite("'.__SKIN__.'image/'.$this->title.'","'.$this->config['width'].'","'.$this->config['height'].'","flashMenu","xmlUrl='.__SKIN__.'cache/menu.xml&menu='.$GLOBALS['menu'].'&sub='.$GLOBALS['sub'].'")</script></div></div>';
}
else
{
	$this->result .= '<div class="visual" style="float:left; margin:'.$this->config['mgt'].'px '.$this->config['mgr'].'px '.$this->config['mgb'].'px '.$this->config['mgl'].'px; padding:'.$this->config['pdt'].'px '.$this->config['pdr'].'px '.$this->config['pdb'].'px '.$this->config['pdl'].'px;">
	<div id="'.$this->title.'" class="design pattern" style="width:'.$this->config['width'].'; height:'.$this->config['height'].';" title="'.$this->title.'">
		<div style="padding:3px;"><a href="./" target=_self><strong>['.$this->displayPos.']</strong>유닛정보</a></div>
		<div class="patternWrap" style="width:'.$this->config['width'].'px; height:'.$this->config['height'].'px;" title="'.$this->title.'"><p class="patternInfo">→<strong>'.$this->title.'</strong>&nbsp;<span class="small_dgray">('.$this->config['width'].'_'.$this->config['height'].')</span></p></div>
	</div>
	</div>';
}
?>
