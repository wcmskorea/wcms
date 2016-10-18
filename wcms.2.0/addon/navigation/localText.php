<?php
/* --------------------------------------------------------------------------------------
| Local Navigation : TEXT 방식
|----------------------------------------------------------------------------------------
| Last (2010년 1월 23일 토요일 : 이성준)
*/
$this->cate = ($this->caching) ? $this->cacheCate : __CATE__;

$this->result .= '<h2>로컬메뉴</h2>'.PHP_EOL;
$this->result .= '<div class="localNavi">'.PHP_EOL;
$this->result .= '<ul>'.PHP_EOL;

$n		= 1;
$s		= 0;
$rst	= @mysql_query(" SELECT *  FROM `site__` WHERE skin='".$GLOBALS['cfg']['skin']."' AND LENGTH(cate)='3' AND cate<>'000' AND status='normal' ORDER BY sort,cate ASC ");
$rec	= @mysql_num_rows($rst);
if($rec < 1)
{
	/* 탭 사이즈 */
	$width = floor(preg_replace("/px|%/i","",$this->config['width'])/5);
	$unit = preg_match("/%/i", $this->config['width']) ? substr($this->config['width'], -1) : substr($this->config['width'], -2);
	$this->result .= '<li style="width:'.$width.$unit.';" class="navi" id="lnb0"><p><a href="#">menu1</a></p></li>'.PHP_EOL;
	$this->result .= '<li style="width:'.$width.$unit.';" class="navi" id="lnb1"><p><a href="#">menu2</a></p></li>'.PHP_EOL;
	$this->result .= '<li style="width:'.$width.$unit.';" class="navi" id="lnb2"><p><a href="#">menu3</a></p></li>'.PHP_EOL;
	$this->result .= '<li style="width:'.$width.$unit.';" class="navi" id="lnb3"><p><a href="#">menu4</a></p></li>'.PHP_EOL;
	$this->result .= '<li style="width:'.$width.$unit.';" class="navi" id="lnb4"><p><a href="#">menu5</a></p></li>'.PHP_EOL;
} 
else
{
	$width = floor(preg_replace("/px|%/i", "", $this->config['width'])/$rec);
	$unit = preg_match("/%/i", $this->config['width']) ? substr($this->config['width'], -1) : substr($this->config['width'], -2);
	while($sRows = @mysql_fetch_array($rst))
	{
	    $xml = explode(",", $sRows['xml']);
	    /* 탭 사이즈 */
	    $toWidth	+= $width;
	    $width		= ($rec == $n) ? $width + ($this->config['width']-$toWidth) : $width;
	    /* 메뉴 활성화 */
	    $naviClass = ($sRows['cate'] == $this->cateParent) ? " on" : null;
	    /* 링크 */
	    $moveCate = (substr($sRows['mode'],0,2) == "md" || $sRows['mode'] == "") ? $sRows['cate'] : $sRows['mode'];
	    if($sRows['mode'] == 'url')
	    {
			$link = $sRows['url'];
			$target = $sRows['target'];
	    }
	    else
	    {
			$link = './index.php?cate='.$sRows['cate'];
			$target = $sRows['target'];
	    }
	    /* 출력 */
	    $sRows['name'] = str_replace("&", "&amp;", $sRows['name']);
	    $this->result .= '<li style="width:'.$width.$unit.';" class="navi'.$naviClass.'" id="lnb'.$sRows['cate'].'"><p><a href="'.$link.'#content" target="'.$target.'">';
		$this->result .= $sRows['name'];
		if($sRows['nameExtra'])
	    {
			$this->result .= '<br /><span class="english">'.$sRows['nameExtra'].'</span>'.PHP_EOL;
	    }
	    $this->result .= '</a></p>'.PHP_EOL;
	    
	    if($GLOBALS['cfg']['skin'] != 'mobile' && $sRows['child'] > 0)
	    {
	    	$this->result .= '<ul id="lnb'.$sRows['cate'].'sub" class="naviSub hide" style="width:'.$this->config['width'].'; background-color:'.$xml['1'].';">'.PHP_EOL;
	    		    
		    $rst2 = @mysql_query(" SELECT cate,name,mode,url,target FROM `site__` WHERE skin='".$GLOBALS['cfg']['skin']."' AND LENGTH(cate)='6' AND SUBSTRING(cate,1,3)='".$sRows['cate']."' AND status='normal' ORDER BY sort ASC ");
	        while($ssRows = @mysql_fetch_array($rst2))
	    	{
				if($ssRows['mode'] == 'url')
				{
					$link = $ssRows['url'];
					$target = $ssRows['target'];
				}
				else
				{
					$link = $GLOBALS['cfg']['droot'].'index.php?cate='.$ssRows['cate'];
					$target = $ssRows['target'];
				}
				$this->result .= '<li class="naviSubMenu" style="';
				$this->result .= ($s == 0) ? 'padding-left:'.$xml['0'].'px;' : null;
				$this->result .= 'float:left;">ㆍ<a href="'.$link.'#content" target="'.$target.'" title="'.$ssRows['name'].'">';
				$this->result .= ($ssRows['cate'] == substr($this->cate, 0, 6)) ? '<span class="colorOrange">'.$ssRows['name'].'</span>' : $ssRows['name'];
				$this->result .= '</a></li>'.PHP_EOL;
				$s++;
			}
			unset($s);
			$this->result .= '</ul></li>'.PHP_EOL;
	    }
	$n++;
	}
}

$this->result .= '</ul>'.PHP_EOL;
$this->result .= '<div class="clear"></div>';
$this->result .= '</div>'.PHP_EOL;
if($this->cateParent)
{
	$this->result .= '<script type="text/javascript">
	//<![CDATA[
	$(document).ready(function(){
		$("#lnb'.$this->cateParent.'").addClass("on");
		$("#lnb'.$this->cateParent.'sub").stop().fadeTo("slow", 1).show();
		$.changeTopMenu();
	});
	//]]>
	</script>';
} 
else
{
	$this->result .= '<script type="text/javascript">
	//<![CDATA[
		$(document).ready(function(){$.changeTopMenu();});
	//]]>
	</script>';
}
?>
