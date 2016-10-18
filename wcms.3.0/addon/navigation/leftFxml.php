<div class="clear"></div>
<?php
/* --------------------------------------------------------------------------------------
| Left Sub Navigation : 플래시 XML연동
|----------------------------------------------------------------------------------------
| Last (2010년 1월 23일 토요일 : 이성준)
*/
$this->cate = ($this->caching) ? $this->cacheCate : __CATE__;
$this->result .= '<h2>서브메뉴</h2>';
$this->title = strtolower($this->displayPos)."_menu".$this->sort.'.swf';
if(is_file(__HOME__.'image/'.$this->title))
{
	$this->result .= '<div id="flashXsleft'.$this->cate.'" class="flashXsleft"><div class="design" style="width:'.$this->config['width'].'; height:'.$this->config['height'].'; position:relative;">';
	if(substr($this->cate, 0, 3) == '000')
	{

		if(in_array('mdMember', $GLOBALS['cfg']['modules']))
		{
			if($_SESSION['ulevel'])
			{
				//로그인후
				$this->result .= '<script type="text/javascript">flashWrite("'.__SKIN__.'image/'.$this->title.'?pageNum='.$GLOBALS['menu'].'&subNum='.$GLOBALS['sub'].'","'.$this->config['width'].'","'.$this->config['height'].'","flashMenu","xmlUrl='.__SKIN__.'cache/submenu02.xml");</script>';
			}
			else
			{
				//로그인전
				$this->result .= '<script type="text/javascript">flashWrite("'.__SKIN__.'image/'.$this->title.'?pageNum='.$GLOBALS['menu'].'&subNum='.$GLOBALS['sub'].'","'.$this->config['width'].'","'.$this->config['height'].'","flashMenu","xmlUrl='.__SKIN__.'cache/submenu01.xml");</script>';
			}
		}
		else
		{
			$this->result .= '<script type="text/javascript">flashWrite("'.__SKIN__.'image/'.$this->title.'?pageNum='.$GLOBALS['menu'].'&subNum='.$GLOBALS['sub'].'","'.$this->config['width'].'","'.$this->config['height'].'","flashMenu","xmlUrl='.__SKIN__.'cache/submenu00.xml");</script>';
		}

	}
	else
	{
		$this->result .= '<script type="text/javascript">flashWrite("'.__SKIN__.'image/'.$this->title.'?pageNum='.$GLOBALS['menu'].'&subNum='.$GLOBALS['sub'].'","'.$this->config['width'].'","'.$this->config['height'].'","flashMenu","xmlUrl='.__SKIN__.'cache/menu.xml&menu='.$GLOBALS['menu'].'&sub='.$GLOBALS['sub'].'");</script>';
	}
	$this->result .= '</div></div>';

}
else
{

	$this->result .= '<div id="'.$this->title.'" class="design pattern" style="width:'.$this->config['width'].'; height:'.$this->config['height'].';" title="'.$this->title.'">
		<div style="padding:3px;"><a href="./" target=_self><strong>['.$this->displayPos.']</strong>유닛정보</a></div>
		<div class="patternWrap" style="width:'.$this->config['width'].'; height:'.$this->config['height'].';" title="'.$this->title.'"><p class="patternInfo">→<strong>'.$this->title.'</strong>&nbsp;<span class="small_dgray">('.$this->config['width'].'_'.$this->config['height'].')</span></p></div>
	</div>';

}
?>
