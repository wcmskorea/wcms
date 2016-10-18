<?php
/**
 * 플래시 메뉴
 */
$this->title = strtolower($this->displayPos)."_menu".substr(__CATE__,0,3).'.swf';
if(is_file(__HOME__.$this->cfg['site']['lang'].'/image/'.$this->title))
{
	
	$this->result .= '<div id="flashMenu" class="design" style="width:'.$this->width.'px; height:'.$this->height.'px; position:relative;">';
	$this->result .= '<script type="text/javascript">flashWrite("'.__SKIN__.'image/'.strtolower($this->displayPos)."_menu".$this->title.'.swf",'.$this->width.','.$this->height.',"flashMenu","")</script>';
	$this->result .= '</div>';
	
} else {
	
	$this->result .= '<div id="'.$this->title.'" class="design pattern" style="width:'.$this->width.'px; height:'.$this->height.'px;" title="'.$this->title.'">
		<div style="padding:3px;"><a href="./" target=_self><strong>['.$this->displayPos.']</strong>유닛정보</a></div>
		<div class="patternWrap" style="width:'.$this->width.'px; height:'.$this->height.'px;" title="'.$this->title.'"><p class="patternInfo">→<strong>'.$this->title.'</strong>&nbsp;<span class="small_dgray">(width-'.$this->width.'px, height-'.$this->height.'px)</span></p></div>
	</div>';
	
}
?>
