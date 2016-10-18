<?php
/* --------------------------------------------------------------------------------------
| Mobile Local Navigation : Image 방식
|----------------------------------------------------------------------------------------
| Last (2011년 2월 22일 화요일 : 이성준)
*/
$this->result .= '<h2>로컬메뉴</h2>'.PHP_EOL;
$this->result .= '<div style="width:100%; padding:2px 0; overflow:hidden;">'.PHP_EOL;
$this->result .= '<ul>'.PHP_EOL;

$n		= 1;
$s		= 0;
$rst	= @mysql_query(" SELECT cate,name,mode,target  FROM `site__` WHERE skin='".$GLOBALS['cfg']['skin']."' AND LENGTH(cate)='3' AND cate<>'000' AND status='normal' ORDER BY sort,cate ASC ");
$rec	= @mysql_num_rows($rst);
if($rec < 1)
{
	/* 탭 사이즈 */
	$width = floor($this->config['width']/4);
	$this->result .= '<li style="float:left; width:'.$width.'%; text-align:center;"><p style="border:2px solid '.$this->config['bgColor'].'; border-right:0; background-color:'.$bg01.';"><a href="'.$this->cfg['droot'].'index.php"><img src="/user/mobile/image/menu/menu01.png" width="85" height="107" /></a></p></li>'.PHP_EOL;
	$this->result .= '<li style="float:left; width:'.$width.'%; text-align:center;"><p style="border:2px solid '.$this->config['bgColor'].'; border-right:0; background-color:{$bg02};"><a href="'.$this->cfg['droot'].'index.php?cate=001"><img src="/user/mobile/image/menu/menu02.png" width="85" height="107" /></a></p></li>'.PHP_EOL;
	$this->result .= '<li style="float:left; width:'.$width.'%; text-align:center;"><p style="border:2px solid '.$this->config['bgColor'].'; border-right:0; background-color:{$bg03};"><a href="'.$this->cfg['droot'].'index.php?cate=002"><img src="/user/mobile/image/menu/menu03.png" width="85" height="107" /></a></p></li>'.PHP_EOL;
	$this->result .= '<li style="float:left; width:'.$width.'%; text-align:center;"><p style="border:2px solid '.$this->config['bgColor'].'; background-color:{$bg04};"><a href="'.$this->cfg['droot'].'index.php?cate=003"><img src="/user/mobile/image/menu/menu04.png" width="85" height="107" /></a></p></li>'.PHP_EOL;
}
else
{
	$width = floor($this->config['width']/$rec);
	while($sRows = @mysql_fetch_array($rst))
	{
		$bgColor = ($sRows['cate'] == __CATE__) ? "#0072bc" : "#438ccb";
		$this->result .= '<li style="float:left; width:'.$width.'%; text-align:center;"><p style="border:2px solid '.$this->config['colorLine'].'; border-right:0; background-color:'.$bgColor.'"><a href="'.$this->cfg['droot'].'index.php?cate='.$sRows['cate'].'"><img src="/user/mobile/image/menu/menu'.$sRows['cate'].'.png" width="85" height="107" alt="'.$sRows['name'].'" /></a></p></li>'.PHP_EOL;
	$n++;
	}
}

$this->result .= '</ul>'.PHP_EOL;
$this->result .= '<div class="clear"></div>';
$this->result .= '</div>'.PHP_EOL;
?>
