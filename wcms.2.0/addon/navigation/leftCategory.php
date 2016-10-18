<?php
/* --------------------------------------------------------------------------------------
| Left Category Navigation : TEXT 방식
|----------------------------------------------------------------------------------------
*/
//$this->cate = ($this->caching) ? $this->cacheCate : __CATE__;
$this->result .= '<h2>쇼핑 카테고리</h2>';
$this->result .= '<div class="leftNaviText" id="leftNaviText'.__CATE__.'">'.PHP_EOL;
$this->result .= '<ul class="parents">'.PHP_EOL;

$len  = $this->cate ? strlen($this->cate)+3 : 3;
$rst = @mysql_query(" SELECT * FROM `site__` WHERE skin='".$GLOBALS['cfg']['skin']."' AND LENGTH(cate)='".$len."' AND cate LIKE '".$this->cate."%' AND status='normal' ORDER BY sort");
$n = 1;
while($menus = @mysql_fetch_array($rst))
{
	$moveCate = ($menus['mode'] == 'url') ? $menus['url'] : ($GLOBALS['cfg']['skin']=='default' ? "/index.php?cate=".$menus['cate'] : "/".$GLOBALS['cfg']['skin']."/index.php?cate=".$menus['cate']);
	
	//서브메뉴
	$this->result .= '<li id="leftNaviDepth1_'.$n.'" class="parent">
	<a href="'.$moveCate.'" target="'.$menus['target'].'" class="depth1">'.$menus['name'].'</a>'.PHP_EOL;

	$rst2 = @mysql_query("SELECT * FROM `site__` WHERE skin='".$GLOBALS['cfg']['skin']."' AND LENGTH(cate)='".($len + 3)."' AND cate LIKE '".$menus['cate']."%' AND status='normal' ORDER BY sort ASC");

	if(mysql_num_rows($rst2) > 0) 
	{
		$s = 1;
		$this->result .= '<div class="children"><ol id="leftNaviDepth2_'.$n.'">'.PHP_EOL;
		while($subMenus = @mysql_fetch_array($rst2))
		{
			$moveCate = ($subMenus['mode'] == 'url') ? $subMenus['url'] : ($GLOBALS['cfg']['skin']=='default' ? "/index.php?cate=".$subMenus['cate'] : "/".$GLOBALS['cfg']['skin']."/index.php?cate=".$subMenus['cate']);	//2012-12-20 영문사이트일 경우 링크에 /english 가 붙도록
			$this->result .= '<li class="child">';
			$this->result .= '<span><a href="'.$moveCate.'" target="'.$subMenus['target'].'" class="depth2">'.$subMenus['name'].'</a></span></li>'.PHP_EOL;
			//$this->result .= ($s != mysql_num_rows($rst2)) ? '<span class="bar">|</span></li>'.PHP_EOL : '</li>'.PHP_EOL;
			$s++;
		}
		unset($s);
		$n++;
		$this->result .= '</ol><div class="clear"></div></div>';
	}
	$this->result .= '</li>'.PHP_EOL;
}
$this->result .= '</ul></div>'.PHP_EOL;
?>
