<?php
/**
 * Author : Sung-Jun, Lee
 * Lastest : 2010. 4. 30.
 */
if($Rows['fileCount'] > 0 && $sess->decode($_GET['type']) != 'rewrite')
{
?>
<div class="cube"><div class="line">
	<div class="fileAttatch">
	<table>
	<tr><th>등록된 파일</th>
		<td id="fileAttatchList">
		<ul>
		<?php
		$n = 1;
		$db->query(" SELECT * FROM `".$cfg['cate']['mode']."__file".$prefix."` WHERE parent='".$Rows['seq']."' ");
		while($sRows = $db->fetch())
		{
			$dir = $cfg['upload']['dir'].date("Y",$sRows['regDate'])."/".date("m",$sRows['regDate'])."/".$sRows['fileName'];
			echo('<li class="pd3"><span><img src="'.$cfg['droot'].'common/image/files/'.strtolower($sRows['extName']).'.gif" align="absmiddle" onError="this.src=\''.$cfg['droot'].'common/image/files/unKonwn.gif\'" width="16" height="16" /></span><span><a href="'.$cfg['droot'].'addon/system/download.php?'.__PARM__.'&amp;file='.$sess->encode($dir).'&amp;name='.$sRows['realName'].'">'.$sRows['realName'].'</a></span>&nbsp;<span><input type="checkbox" id="att'.$n.'" name="oldFile[]" value="'.$sRows['seq'].'" />&nbsp;<label for="att'.$n.'">삭제</label></span></li>');
			$n++;
		}
		?></ul>
		</td>
	</tr>
	</table>
	</div>
</div></div>
<?php
}
?>
<div class="cube"><div class="line">
	<div class="fileAttatch">
	<table>
		<tr>
			<th>파일첨부<br />(총 <?php echo(@ini_get("post_max_size"));?>)</th>
			<td>
				<p class="pd3">시술전 사진 : <input type="file" name="upfile" style="height:18px;" class="input_white" /></p>
				<p class="pd3">시술후 사진 : <input type="file" name="upfile1" style="height:18px;" class="input_white" /></p>
			</td>
		</tr>
	</table>
	</div>
</div></div>