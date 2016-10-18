<?php
/**
 * 게시판 모듈 : VIEW페이지 하단 리스트
 */
if(!defined('__CSS__')) header("HTTP/1.0 404 Not found");

/**
 * 게시물 답글 : Start
 */

echo('<table summary="'.$cfg['content']['name'].'게시물 답글 목록입니다." class="table_basic" style="width:100%;">
<caption>'.$cfg['content']['name'].'게시물 답글 목록입니다.</caption>
<col width="70">
<col>
<col width="80">
'.($cfg['module']['readCount'] != 'N' ? '<col width="50">':'').'
<col width="80">
<tbody>');

/**
 * 게시글 : Start
 */
$row			= $cfg['module']['listHcount'] * $cfg['module']['listVcount'];

$db->query("SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE cate='".$cfg['module']['cate']."' AND '".$_GET['num']."' IN (productSeq,seq)  AND idxTrash='0' ORDER BY seq ASC ");
while($Rows = $db->fetch())
{
	$date				= date("Y-m-d",$Rows['regDate']);
	$Rows['subject']	= stripslashes($Rows['subject']);
	$commentCount 		= ($Rows['commentCount'] > 0) ? '&nbsp;<span class="small_red strong">('.$Rows['commentCount'].')</span>' : null;
	//아이콘 설정
	$icon				.= $func->iconNew($Rows['regDate'], 86400, '<span><img src="'.$cfg['droot'].'user/default/image/icon/new.gif" alt="최근 게시물" /></span>');
	$icon				.= ($Rows['fileCount'] > 0) ? '<span><img src="'.$cfg['droot'].'common/image/icon/disk.gif" alt="첨부파일" /></span>' : '';
	//URL 설정
	$url				= '<a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Rows['seq'].'">';
	if($Rows['useSecret'] == "Y")
	{
		$icon .= '<span><img src="'.$cfg['droot'].'user/default/image/icon/secret.gif" alt="비밀글" /></span>';
		$url = '<span><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type='.$sess->encode('secret').'&amp;num='.$Rows['seq'].'">';
	}
	if($_GET['num'] == $Rows['seq'])
	{
		echo('<tr>
		<td><p class="pd3">현재글</p></td>
		<td>'.$Rows['subject'].$commentCount.''.$icon.'</td>
		<td><p class="wrap80">'.$Rows['writer'].'</p></td>
		'.($cfg['module']['readCount'] != 'N' ? '<td>'.$Rows['readCount'].'</td>':'').'
		<td>'.$date.'</td></tr>');
	} else
	{
		echo('<tr>
		<td><p class="pd3">답글</p></td>
		<td>'.$url.$Rows['subject'].$commentCount.'</a>'.$icon.'</td>
		<td><p class="wrap80">'.$Rows['writer'].'</p></td>
		'.($cfg['module']['readCount'] != 'N' ? '<td>'.$Rows['readCount'].'</td>':'').'
		<td>'.$date.'</td></tr>');
	}
	unset($icon,$url);
} 
echo('</tbody></table>');
?>
