<?php
/**--------------------------------------------------------------------------------------
 * 최근 게시물 (일반형) : Add-on
 *---------------------------------------------------------------------------------------
 * Relationship : ./lib/classDisplay.php
 *---------------------------------------------------------------------------------------
 * $this->disp 배열값
 * ['0-3']box공백, ['4-5']노출개수/공백, ['6-8']노출타입/배속/지연, ['9-12']타이틀/제목/날짜노출/more노출
 */
$scrollHeight = 20 * $this->disp['7'];
$squery = ($this->disp['6'] == 'R') ? " ORDER BY RAND() Limit ".$this->disp['4'] : " ORDER BY notice*date DESC Limit ".$this->disp['4'];

$this->result .= '<div class="recentBody" style="width:'.$this->width.'px; height:'.$this->height.'px; background:url('.__SKIN__.'image/background/bg_recent_'.$this->cate.'.gif) no-repeat left bottom;">'.PHP_EOL;
$this->result .= '<table summary="'.$this->cateName.' 최근 게시물 입니다."><caption>'.$this->cateName.'</caption><col width="'.$this->width.'" /><col /><tbody>'.PHP_EOL;

$n = 0;

$rst = @mysql_query(" SELECT cate FROM `".$this->module."__` WHERE 1 ");
while($Rows = @mysql_fetch_assoc($rst)) 
{
	$mquery .= "(SELECT seq,cate,notice,subject,date FROM `".$this->module."__content".$Rows['cate']."` WHERE 1 ".$squery.") UNION ";
}
$mquery = preg_replace('/ UNION $/', null, $mquery);
$squery	= " SELECT * FROM (".$mquery.") A WHERE 1  ORDER BY A.notice*A.date DESC Limit 5";
$rst = mysql_query($squery);
while($Rows = @mysql_fetch_assoc($rst)) 
{
	$Rows['subject'] = stripslashes($Rows['subject']);
	$Rows['subject'] = htmlspecialchars($Rows['subject']);
	$Rows['subject'] = Functions::cutStr($Rows['subject'],$this->disp['10'],"...");
	$icon = Functions::newImg($Rows['date'], (86400*3), '<span class="icon"><img src="'.__SKIN__.'image/icon/new.gif" width="11" height="13" alt="최근 게시물" style="vertical-align:top;" /></span>');
	$date = ($this->disp['11'] == 'Y') ? date("Y.m.d",$Rows['date']).'&nbsp;' : null;
	$notice = ($Rows['notice'] > 1) ? '<span><img src="'.$this->cfg['droot'].'image/icon/icon_title02.gif" width="7" height="9" alt="" /></span> ' : 'ㆍ';
	if($this->config['docLink'] == 'L')  //컨텐츠 클릭시 목록으로 이동 추가(20120522)
		$url 			= '<a href="./?cate='.$Rows['cate'].'" title="'.$Rows['subject'].'">';
	else
		$url 			= '<a href="./?cate='.$Rows['cate'].'&type=view&num='.$Rows['seq'].'" title="'.$Rows['subject'].'">';

	$this->result .= '<tr><td class="boardTitle" style="padding-top:'.$this->disp['5'].'px; padding-left:'.intval($this->disp['5']/2).'px;"><span class="title">'.$notice.$url.$Rows['subject'].'</a></span>'.$icon.'</td>
	<td class="boardTitle" style="padding-top:'.$this->disp['5'].'px; padding-right:'.intval($this->disp['5']/2).'px;"><span class="date">'.$date.'</span></td></tr>'.PHP_EOL;
	$n++;
}
while($n < $this->disp['4']) 
{
	$this->result .= '<tr><td class="boardTitle" style="padding-top:'.$this->disp['5'].'px; padding-left:'.intval($this->disp['5']/2).'px;">ㆍ<span class="title" style="color:#999;">-</span></td>
	<td class="boardTitle" style="padding-top:'.$this->disp['5'].'px; padding-right:'.intval($this->disp['5']/2).'px;"><span class="date">ㆍ</span></td></tr>'.PHP_EOL;
	$n++;
}

$this->result .= '</tbody></table></div>'.PHP_EOL;
unset($data,$squery,$scrollHeight,$n,$limit,$rst,$Rows);
?>
