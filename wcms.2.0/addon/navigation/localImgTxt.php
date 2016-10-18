<?php
/* --------------------------------------------------------------------------------------
| Local Navigation : IMAGE+TEXT+SLIDE 방식
|----------------------------------------------------------------------------------------
| Lastest (2012년 7월 31일 화요일 : 이성준)
*/
$this->cate = ($this->caching) ? $this->cacheCate : __CATE__;

$this->result .= '<h2>로컬메뉴</h2>'.PHP_EOL;
$this->result .= '<div class="localNavi02">'.PHP_EOL;
$this->result .= '<div class="menuBox" style="height:'.$this->config['height'].'">'.PHP_EOL;
$this->result .= '<h2 class="subBox" style="top:'.$this->config['height'].'">메인메뉴입니다.</h2>'.PHP_EOL;
$this->result .= '<ul class="navi">'.PHP_EOL;

$n		= 1;
$s		= 0;
$rst	= @mysql_query(" SELECT *  FROM `site__` WHERE skin='".$GLOBALS['cfg']['skin']."' AND LENGTH(cate)='3' AND cate<>'000' AND status='normal' ORDER BY sort,cate ASC ");
$rec	= @mysql_num_rows($rst);
if($rec < 1)
{
	/* 탭 사이즈 */
	$width = floor(preg_replace("/px|%/i","",$this->config['width'])/5);
	$unit = preg_match("/%/i", $this->config['width']) ? substr($this->config['width'], -1) : substr($this->config['width'], -2);
	$this->result .= '<li style="width:'.$width.$unit.';" class="dep1" id="lnb0"><h2><a href="#none"><img src="/user/default/image/menu/lnb001_off.png" alt="메뉴001" /></a></h2><ul class="dep2"><li><a href="">서브01</a></li><li><a href="">서브02</a></li><li><a href="">서브03</a></li></ul></li>'.PHP_EOL;
	$this->result .= '<li style="width:'.$width.$unit.';" class="dep1" id="lnb0"><h2><a href="#none"><img src="/user/default/image/menu/lnb002_off.png" alt="메뉴002" /></a></h2><ul class="dep2"><li><a href="">서브01</a></li><li><a href="">서브02</a></li><li><a href="">서브03</a></li></ul></li>'.PHP_EOL;
	$this->result .= '<li style="width:'.$width.$unit.';" class="dep1" id="lnb0"><h2><a href="#none"><img src="/user/default/image/menu/lnb003_off.png" alt="메뉴003" /></a></h2><ul class="dep2"><li><a href="">서브01</a></li><li><a href="">서브02</a></li><li><a href="">서브03</a></li></ul></li>'.PHP_EOL;
	$this->result .= '<li style="width:'.$width.$unit.';" class="dep1" id="lnb0"><h2><a href="#none"><img src="/user/default/image/menu/lnb004_off.png" alt="메뉴004" /></a></h2><ul class="dep2"><li><a href="">서브01</a></li><li><a href="">서브02</a></li><li><a href="">서브03</a></li></ul></li>'.PHP_EOL;
	$this->result .= '<li style="width:'.$width.$unit.';" class="dep1" id="lnb0"><h2><a href="#none"><img src="/user/default/image/menu/lnb005_off.png" alt="메뉴005" /></a></h2><ul class="dep2"><li><a href="">서브01</a></li><li><a href="">서브02</a></li><li><a href="">서브03</a></li></ul></li>'.PHP_EOL;
} 
else
{
	$width = floor(preg_replace("/px|%/i", "", $this->config['width'])/$rec);
	$unit = preg_match("/%/i", $this->config['width']) ? substr($this->config['width'], -1) : substr($this->config['width'], -2);

	while($sRows = @mysql_fetch_array($rst))
	{
		$xml = explode(",", $sRows['xml']);

		/* 탭 사이즈 */
		if(!$xml[0])
		{
			$toWidth	+= $width;
			$width		= ($rec == $n) ? $width + ($this->config['width']-$toWidth) : $width;
		} else {
			$width = $xml[0];
		}

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

		$this->result .= '<li style="width:'.$width.'px" class="dep1" id="lnb0"><h2><a href="'.$link.'" target="'.$target.'"><img src="/user/default/image/menu/lnb'.$sRows['cate'].'_off.png" style="width:'.$width.'px; height:'.$this->config['height'].'" alt="'.$sRows['name'].'" /></a></h2>'.PHP_EOL;
		
		if($GLOBALS['cfg']['skin'] != 'mobile' && $sRows['child'] > 0)
		{
			$this->result .= '<ul class="dep2">'.PHP_EOL;
						
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
				$this->result .= '<li style="width:'.$width.'px"><a href="'.$link.'" target="'.$target.'" title="'.$ssRows['name'].'">'.$ssRows['name'].'</a></li>'.PHP_EOL;
				$s++;
			}
			unset($s);
			$this->result .= '</ul>'.PHP_EOL;
		}

		$this->result .= '</li>'.PHP_EOL;
		$n++;
	}
}

$this->result .= '</ul>'.PHP_EOL;
$this->result .= '<div class="clear"></div>';
$this->result .= '<script type="text/javascript">$(function(){var fnLnb=function(){if($(".subBox").is(":hidden")){$(".subBox").slideDown(200);$(".dep2").slideDown(200);}$(this).parent("h2").next("ul").find("a").animate({opacity:1},20);$(this).parent().parent().siblings().find(".dep2 a").animate({opacity:0.6},20);$(".navi h2 img").attr("src",function(){return this.src.replace("_on","_off")});$(this).find("img").attr("src",function(){return this.src.replace("_off","_on")});};$(".navi h2 a").bind("focus mouseenter",fnLnb);$(".dep2").bind("mouseenter",function(){if(!$(this).hasClass("on")){$(this).addClass("on");$(this).find("a").animate({opacity:1},100);$(this).parent("li").siblings().find(".dep2 a").animate({opacity:0.6},100);$(".navi h2 img").attr("src",function(){return this.src.replace("_on","_off")});$(this).prev("h2").find("img").attr("src",function(){return this.src.replace("_off","_on")});}});$(".dep2").bind("mouseleave",function(){if($(this).hasClass("on")){$(this).removeClass("on");}});$(".navi").mouseleave(function(){$(".navi h2 img").attr("src",function(){return this.src.replace("_on","_off")});$(".dep2").slideUp(200);$(".subBox").slideUp(400);});});</script>'.PHP_EOL;
$this->result .= '</div></div>'.PHP_EOL;
?>