<?php
/**--------------------------------------------------------------------------------------
 * 최근 게시물 (갤러리 레이어드+웹진형) : Add-on
 *---------------------------------------------------------------------------------------
 * Relationship : ./lib/classDisplay.php
 * Last (2013-08-08 : 오혜진)
 */

//슬라이드 설정
$this->config['docStart'] = ($this->config['docStart']) ? $this->config['docStart'] : "false";
$this->config['docEsing'] = ($this->config['docEsing']) ? $this->config['docEsing'] : "";
$this->config['docBtn'] = ($this->config['docBtn']) ? $this->config['docBtn'] : "false";
$this->config['docPager'] = ($this->config['docPager']) ? $this->config['docPager'] : "false";
$this->config['docTicker'] = ($this->config['docTicker']) ? $this->config['docTicker'] : "false";
$this->config['docPadT']  = ($this->config['docPadT']) ? $this->config['docPadT'] : $this->config['docPad'];	// 스킨 여백(상) 추가(2012-10-31)

//쿼리
$squery = ($this->config['useUnion'] == "Y") ? "cate like '".$this->cate."%'" : "cate='".$this->cate."'";
switch($this->config['useData']) 
{
	case "all": default: $squery .= null; break;
	case "image": $squery .= " AND fileCount>'0'"; break;
	case "text": $squery .= " AND fileCount='0'"; break;
}
$squery .= ($this->config['useNotice'] == 'Y') ? " AND useNotice='Y'" : "";
$squery .= " AND idxDepth='0' AND idxTrash='0'";
$squery .= ($this->config['docType'] == 'R') ? " ORDER BY RAND() Limit ".$this->config['docCount'] : " ORDER BY regDate DESC, idx DESC Limit ".$this->config['docCount'];

$thumbType = explode(",", $this->config['thumbType']); //비율
$scrollHeight = preg_replace('/px|%/',null,$this->config['imgHeight']) * $this->config['docSpeed'];

$this->result .= '<div id="recentLayerd'.$this->prefix.'" class="recentLayerdZine" style="width:'.$this->config['width'].'; height:'.$this->config['height'].'; background:url('.__SKIN__.'image/background/bg_recent_'.strtolower($this->prefix).'.gif) no-repeat left top;">'.PHP_EOL;
$this->result .= '<div class="box">'.PHP_EOL;

$n = 0;
$rst = @mysql_query(" SELECT * FROM `".$this->config['module']."__content` WHERE ".$squery);
//$this->result .='<!-- '." SELECT * FROM `".$this->config['module']."__content` WHERE ".$squery.' -->';
while($Rows	= @mysql_fetch_assoc($rst))
{
	$Rows['subject']= stripslashes($Rows['subject']);
	$Rows['subject']= strip_tags($Rows['subject']);
	//$Rows['subject']= htmlspecialchars($Rows['subject']);
	$Rows['subject']= Functions::cutStr($Rows['subject'],$this->config['cutSubject'],"...");
	$Rows['content']= stripslashes($Rows['content']);
	$Rows['content']= strip_tags($Rows['content']);
	$Rows['content'] = str_replace("&nbsp;", null, $Rows['content']);
	$Rows['content']= Functions::cutStr($Rows['content'],$this->config['cutContent'],"...");

	$thumb 			= @mysql_fetch_assoc(@mysql_query("SELECT fileName,regDate FROM `".$this->config['module']."__file` WHERE parent='".$Rows['seq']."' AND LOWER(extname) IN ('jpg','gif','png','bmp') ORDER BY seq ASC Limit 1"));
	$sdir 			= str_replace($GLOBALS['cfg']['site']['lang']."/",null,__SKIN__)."data/".date("Y",$thumb['regDate'])."/".date("m",$thumb['regDate'])."/s-".$thumb['fileName'];
	$mdir 			= str_replace($GLOBALS['cfg']['site']['lang']."/",null,__SKIN__)."data/".date("Y",$thumb['regDate'])."/".date("m",$thumb['regDate'])."/m-".$thumb['fileName'];
	//$icon 			= Functions::iconNew($Rows['regDate'], (86400*1), '<span class="icon"><img src="'.__SKIN__.'image/icon/new.gif" width="11" height="13" alt="최근 게시물" /></span>');
	if($this->config['docLink'] == 'L')  //컨텐츠 클릭시 목록으로 이동 추가(20120522)
	{
		$url 			= $GLOBALS['cfg']['droot'].'?cate='.$Rows['cate'];
	} else {
		$url 			= $GLOBALS['cfg']['droot'].'?cate='.$Rows['cate'].'&type=view&num='.$Rows['seq'];
	}
	$date 			= date("Y.m.d", $Rows['regDate']);

	if($n==0) {
		$this->result .= '  <div class="big"><a href="'.$url.'" title="'.$Rows['subject'].'" target="'.$this->target.'"><img src="'.$mdir.'" onerror="this.src=\'/common/image/noimg_m.gif\'" alt="'.$Rows['subject'].'" /></a><div class="black"><a href="'.$url.'" title="'.$Rows['subject'].'" target="'.$this->target.'"><h6>'.$Rows['subject'].'</h6><p>'.$Rows['content'].'</p></a></div></div>'.PHP_EOL;
	} else {
		$this->result .= '  <div class="small"><a href="'.$url.'" title="'.$Rows['subject'].'" target="'.$this->target.'"><img src="'.$sdir.'" onerror="this.src=\'/common/image/noimg_m.gif\'" alt="'.$Rows['subject'].'" /></a><a href="'.$url.'" title="'.$Rows['subject'].'" target="'.$this->target.'" class="link"><h6>'.$Rows['subject'].'</h6><p>'.$Rows['content'].'</p></a></div>'.PHP_EOL;
	}
	$n++;
}
while($n < $this->config['docCount'])
{
	if($n==0) {
		$this->result .= '  <div class="big"><a href="#"><img src="/user/default/image/icon/noimg_m.gif" onerror="this.src=\'/common/image/noimg_m.gif\'" alt="준비중" /></a><div class="black"><a href="#"><h6>-</h6><p>-</p></a></div></div>'.PHP_EOL;
	} else {
		$this->result .= '  <div class="small"><a href="#"><img src="/user/default/image/icon/noimg_m.gif" onerror="this.src=\'/common/image/noimg_m.gif\'" alt="준비중" /></a><A href="#" class="link"><h6>-</h6><p>-</p></a></div>'.PHP_EOL;
	}
	$n++;
}
$this->result .= '</div>'.PHP_EOL;
$this->result .= '</div><script type="text/javascript">$(function(){$(\'#recentLayerd'.$this->prefix.'\').imagesLoaded(function(){$(\'#recentLayerd'.$this->prefix.'\').masonry({itemSelector :\'.box\'});});});</script>'.PHP_EOL;
unset($squery,$thumbType,$width,$height,$scrollHeight,$n,$rst,$Rows,$thumb,$dir,$icon,$url,$date);
?>