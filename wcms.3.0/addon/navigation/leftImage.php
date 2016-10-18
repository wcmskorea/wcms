<?php
/* --------------------------------------------------------------------------------------
| Left Sub Navigation : IMAGE 방식
|----------------------------------------------------------------------------------------
| Last (2010년 1월 23일 토요일 : 이성준)
*/
$this->cate = ($this->caching) ? $this->cacheCate : __CATE__;
$this->config['useTitle'] = "image";
$this->result .= '<h2>서브메뉴</h2>'.PHP_EOL;
$this->result .= '<div id="leftNaviImg">'.PHP_EOL;
$this->result .= $this->printTitle("subMenuTitle", $subTitle['0'], substr($this->cate, 0, $len));
$this->result .= '<ul class="parents">'.PHP_EOL;
if(substr($this->cate,0,6) == '000002')
{
	$rst = @mysql_query("SELECT * FROM `site__` WHERE skin='".$GLOBALS['cfg']['skin']."' AND LENGTH(cate)='9' AND SUBSTRING(cate,1,6)='000002' AND status='normal' ORDER BY sort ASC");
}
else
{
	if(substr($this->displayPos, 0, 1) == 'S')
	{
		$rst = @mysql_query("SELECT * FROM `site__` WHERE skin='".$GLOBALS['cfg']['skin']."' AND LENGTH(cate)='6' AND SUBSTRING(cate,1,3)='".substr($this->cate,0,3)."' AND SUBSTRING(cate,1,3)<>'000' AND status='normal' ORDER BY sort ASC");	
	} else
	{
		$rst = @mysql_query("SELECT * FROM `site__` WHERE skin='".$GLOBALS['cfg']['skin']."' AND LENGTH(cate)='3' AND status='normal' ORDER BY sort ASC");
	}
	//2012-12-20 영문사이트일 경우 링크에 /english 가 붙도록
}
$n = 1;
while($menus = @mysql_fetch_array($rst))
{
	$moveCate = ($menus['mode'] == 'url') ? $menus['url'] : ($GLOBALS['cfg']['skin']=='default' ? "/index.php?cate=".$menus['cate'] : "/".$GLOBALS['cfg']['skin']."/index.php?cate=".$menus['cate']);
	
	//2차메뉴
	$this->result .= '<li id="leftNaviImgDepth1_'.$n.'" class="leftNaviImgDepth1" style="margin:0px; padding:0px; line-height:0px">
	<a href="'.$moveCate.'" target="'.$menus['target'].'">'.$this->printMenu("submenu", $menus['name'], $menus['cate']).'</a>
	<ul id="leftNaviImgDepth2_'.$n.'" class="Depth2">'.PHP_EOL;

	if(substr($this->displayPos, 0, 1) == 'S')
	{
		$rst2 = @mysql_query("SELECT * FROM `site__` WHERE skin='".$GLOBALS['cfg']['skin']."' AND LENGTH(cate)='9' AND SUBSTRING(cate,1,6)='".$menus['cate']."' AND status='normal' ORDER BY sort ASC");
	} else {
		$rst2 = @mysql_query("SELECT * FROM `site__` WHERE skin='".$GLOBALS['cfg']['skin']."' AND LENGTH(cate)='6' AND SUBSTRING(cate,1,3)='".$menus['cate']."' AND status='normal' ORDER BY sort ASC");
	}
	while($subMenus = @mysql_fetch_array($rst2))
	{
		$thisCate = (substr($this->displayPos, 0, 1) == 'S') ? 6 : 3; //메인 노출이면 3 or 6
		$moveCate = ($subMenus['mode'] == 'url') ? $subMenus['url'] : ($GLOBALS['cfg']['skin']=='default' ? "/index.php?cate=".$subMenus['cate'] : "/".$GLOBALS['cfg']['skin']."/index.php?cate=".$subMenus['cate']);	//2012-12-20 영문사이트일 경우 링크에 /english 가 붙도록
		//3차메뉴
		$this->result .= '<li style="margin:0px; padding:0px; line-height:0px" class="leftNaviImgDepth2';
		//rollOver
		if($this->config['rollOver'] != 'Y' && substr($this->cate,0,$thisCate) != $menus['cate']) { $this->result .= ' hide'; }
		$this->result .= '"><a href="'.$moveCate.'" target="'.$subMenus['target'].'">'.$this->printMenu("submenu", $subMenus['name'], $subMenus['cate']).'</a></li>'.PHP_EOL;
	}
	$n++;
	$this->result .= '</ul></li>'.PHP_EOL;
}
$this->result .= '</ul></div>'.PHP_EOL;
if($this->config['rollOver'] == 'N') 
{ 
	$this->result .= '<script type="text/javascript">
	$("li.leftNaviImgDepth1").bind("mouseenter", function(e){
		$("#" + this.id.replace("leftNaviImgDepth1_","leftNaviImgDepth2_") + " > li.hide").fadeTo("slow","1");
	});
	$("li.leftNaviImgDepth1").bind("mouseleave", function(e){
		$("#" + this.id.replace("leftNaviImgDepth1_","leftNaviImgDepth2_") + " > li.hide").hide();
	});
	</script>'.PHP_EOL;
}
unset($n);
?>