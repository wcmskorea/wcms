<?php
/**--------------------------------------------------------------------------------------
 * 최근 댓글(원글+댓글)
 *---------------------------------------------------------------------------------------
 * Relationship : /_Lib/classDisplay.php
 *---------------------------------------------------------------------------------------
 */

//슬라이드 설정
$this->config['docStart'] = ($this->config['docStart']) ? $this->config['docStart'] : "false";
$this->config['docEsing'] = ($this->config['docEsing']) ? $this->config['docEsing'] : "";
$this->config['docBtn'] = ($this->config['docBtn']) ? $this->config['docBtn'] : "false";
$this->config['docPager'] = ($this->config['docPager']) ? $this->config['docPager'] : "false";
$this->config['docTicker'] = ($this->config['docTicker']) ? $this->config['docTicker'] : "false";

//$this->config['docPadT']  = ($this->config['docPadT']) ? $this->config['docPadT'] : $this->config['docPad'];	// 스킨 여백(상) 추가(2012-11-22)

if($this->menuCate) {
	$squery				= ($this->config['useUnion'] == "Y") ? "cate like '".$this->menuCate."%'" : "cate='".$this->menuCate."'";
} else {
	$squery				= '1';
}
$squery				.= ($this->config['docType'] == 'R') ? " ORDER BY RAND() Limit ".$this->config['docCount'] : " ORDER BY seq DESC Limit ".$this->config['docCount'];

$width				= preg_replace('/px|%/',null,$this->config['width']);
$height				= preg_replace('/px|%/',null,$this->config['height']);
$scrollHeight	= 20 * $this->config['docSpeed'];
$subjectWidth	= $width - ($this->config['docPad']*2);

$this->result	.= '<div class="replyGroup">'.PHP_EOL;
$this->result	.= '<ul id="docRecent'.$this->prefix.'">'.PHP_EOL;

$n = 0;
$rst = mysql_query(" SELECT * FROM `".$this->config['module']."__comment` WHERE ".$squery);
//$this->result .='<!--'." SELECT * FROM `".$this->config['module']."__comment` WHERE ".$squery.'-->';
while($Rows = @mysql_fetch_assoc($rst))
{
	$Rows['comment'] = stripslashes($Rows['comment']);
	$Rows['comment'] = htmlspecialchars($Rows['comment']);
	$Rows['comment'] = Functions::cutStr($Rows['comment'], $this->config['cutSubject'],"...");
	preg_match_all("/\[(.+)\]/", $Rows['comment'], $out, PREG_PATTERN_ORDER);
	$comment = str_replace($out[1][0], '<span style="color:'.$GLOBALS['cfg']['docHeadColor'][$out[1][0]].'">'.$out[1][0].'</span>', $Rows['comment']);

	//원본글
	$replyRows = @mysql_fetch_array(@mysql_query(" SELECT subject FROM `".$this->config['module']."__content` WHERE seq ='".$Rows['seq']."'"));
	$replyRows['subject'] = stripslashes($replyRows['subject']);
	$replyRows['subject'] = htmlspecialchars($replyRows['subject']);
	$replyRows['subject'] = Functions::cutStr($replyRows['subject'], $this->config['cutContent'],"...");
	//preg_match_all("/\[(.+)\]/", $replyRows['subject'], $out, PREG_PATTERN_ORDER);
	$subject = $replyRows['subject']; //str_replace($out[1][0], '<span style="color:'.$GLOBALS['cfg']['docHeadColor'][$out[1][0]].'">'.$out[1][0].'</span>', $replyRows['subject']);

	if($this->config['docLink'] == 'L')  //컨텐츠 클릭시 목록으로 이동 추가(20120522)
		$url 			= '<a href="./?cate='.$Rows['cate'].'" title="'.$replyRows['subject'].'" target="'.$this->target.'">';
	else {
		if($Rows['useSecret'] == "Y"){ $typeString = 'type='.$GLOBALS['sess']->encode('secret');}
		else{ $typeString = "type=view";}
		$url = '<a
	href="./?cate='.$Rows['cate'].'&amp;'.$typeString.'&amp;num='.$Rows['parent'].'" title="'.$subject.'" target="'.$this->target.'">';
	}

	$this->result	.= '		<li'.(($n+1) == $this->config['docCount'] ? ' style="border-bottom:none"' : '').'><span>'.($n+1).'</span>'.$url.$subject.'</a><br />
		<p><A href="#"><img src="user/default/image/main/icon.gif" /> '.$comment.'</A></p>
		</li>'.PHP_EOL;
	$n++;
}

while($n < $this->config['docCount'])
{
	$this->result .= '<li'.(($n+1) == $this->config['docCount'] ? ' style="border-bottom:none"' : '').'><span>'.($n+1).'</span><a href="#">...</a><br />
		<p><A href="#"><img src="user/default/image/main/icon.gif" />  ...</A></p>
		</li>'.PHP_EOL;
	$n++;
}
$this->result .= '	</ul></div>'.PHP_EOL;

$this->result .= '<script type="text/javascript">$(document).ready(function(){$("#docRecent'.$this->prefix.'").bxSlider({mode:"'.$this->config['docType'].'",controls:'.$this->config['docBtn'].',pager:'.$this->config['docPager'].',wrapperClass:"docRecent'.$this->prefix.'_wrap",auto:'.$this->config['docStart'].',speed:'.$this->config['docSpeed'].',pause:'.$this->config['docDelay'].',ticker:'.$this->config['docTicker'].',tickerSpeed:'.$this->config['docDelay'].',displaySlideQty:'.$this->config['docCount'].'});});</script>'.PHP_EOL;

unset($config,$squery,$width,$height,$scrollHeight,$subjectWidth,$n,$rst,$Rows,$date,$icon);
?>