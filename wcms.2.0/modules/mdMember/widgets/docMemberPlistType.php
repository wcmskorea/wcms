<?php
$this->result .= '<table class="table_basic" style="width:100%;">
<caption>회원목록</caption>
<colgroup>
	<col width="80">
	<col>
	<col width="80">
	<col>
</colgroup>
<thead>
<tr><th scope="col" class="first"><p class="center pd7">사진</p></th>
		<th scope="col"><p class="center pd7">소개</p></th>
		<th scope="col" class="first"><p class="center pd7">사진</p></th>
		<th scope="col"><p class="center pd7">소개</p></th>
</tr>
</thead>
<tbody>'.PHP_EOL;

$n = 0;
$wide = 2;
$rst = mysql_query(" SELECT * FROM `mdMember__account` AS A LEFT JOIN `mdMember__info` AS B ON A.id=B.id WHERE A.level BETWEEN '".$this->config['levelMin']."' AND '".$this->config['levelMax']."' ORDER BY A.level ASC, A.sort ASC, A.dateReg ASC");
$total = mysql_num_rows($rst);
while($Rows = @mysql_fetch_assoc($rst))
{
	$n++;
	$groupName = ($Rows['groupName']) ? ''.$Rows['groupName'] : null;
	$function = ($Rows['function']) ? ' ('.$Rows['function'].')' : null;
	$Rows['content'] = Functions::cutStr($Rows['content'], 100,"...");
	$thumb	= @mysql_fetch_assoc(@mysql_query("SELECT fileName,regDate FROM `".$this->config['module']."__file` WHERE parent='".$Rows['seq']."' AND LOWER(extname) IN ('jpg','gif','png','bmp') ORDER BY seq ASC Limit 1"));
	$dir 		= str_replace($GLOBALS['cfg']['site']['lang']."/",null,__SKIN__)."data/".date("Y",$thumb['regDate'])."/".date("m",$thumb['regDate'])."/s-".$thumb['fileName'];
	
	if($n % $wide == 1) { $this->result .= '<tr>'; }

	$this->result .= '
	<th scop="row"><p class="center pd5"><img src="'.$dir.'" class="thumbNail" style="width:'.$this->config['imgWidth'].'; height:'.$this->config['imgHeight'].';" onerror="this.src=\'/common/image/noimg_m.gif\'" onmouseover="overClass(this);" onmouseout="overClass(this);" onfocus="overClass(this);" onblur="overClass(this);" alt="'.$Rows['name'].'" /></p></th>
	<td style="vertical-align:top"><p class="pd3 strong colorBlack">'.$Rows['nick'].' '.$Rows['name'].'</p>
			<p class="pd3">'.$groupName.$function.'</p>
			<p class="pd3 colorBlack line150" style="text-align: justify;">'.$Rows['content'].'</p>
	</td>'.PHP_EOL;

	if($n % $wide == 0) { 
		$this->result .= '</tr>'; 
	} else if($total == $n) {
		$this->result .= '
		<th scop="row"><p class="center pd5"><img src="/common/image/noimg_m.gif" class="thumbNail" style="width:'.$this->config['imgWidth'].'; height:'.$this->config['imgHeight'].';" onerror="this.src=\'/common/image/noimg_m.gif\'" onmouseover="overClass(this);" onmouseout="overClass(this);" onfocus="overClass(this);" onblur="overClass(this);" alt="" /></p></th>
		<td style="vertical-align:top"><p class="pd3 strong colorBlack"></p>
				<p class="pd3"></p>
				<p class="pd3 colorBlack line150"></p>
		</td>
		</tr>'.PHP_EOL;
	}
}

$this->result .= '</tbody>
</table>'.PHP_EOL;
unset($groupName, $function, $n, $wide, $rst, $Rows, $thumb, $dir);
?>