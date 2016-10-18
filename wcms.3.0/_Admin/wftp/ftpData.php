<?php
require_once "../../_config.php";
require_once "../include/commonHeader.php";

$_GET['dir']= preg_replace("/^\//", "", $_GET['dir']);
$_GET['dir']= str_replace("../", null, $_GET['dir']);
//$droot 	= ($cfg[site][setup] == 'Y') ? __HOME__ : __PATH__.'skin/';
$droot 		= __PATH__."_Site/".__DB__."/";

$fileArray	= $func->dirOpen($droot.$_GET['dir']);
$fileCount	= count($fileArray);
$dirs		= explode("/",$_GET['dir']);
//상위디렉토리 설정
for($i=0;$i<count($dirs)-1;$i++)
{
	$dirUp .= $dirs[$i];
	if($i < (count($dirs)-2)) { $dirUp .= "/"; }
}

?>
<form name="ftpFrm" enctype="multipart/form-data" onsubmit="return false;">
<input type="hidden" name="type" value="" />
<input type="hidden" name="rtotal" value="" />
<input type="hidden" name="dir" value="<?php echo($_GET[dir]);?>" />

<div class="pd10 bg_gray">
<p style="text-align:left; letter-spacing:1px">&nbsp;<strong>현재위치: </strong><span>/user/<?php echo($_GET[dir]);?></span></p>
</div>
<table class="table_basic" style="width:100%;">
	<colgroup>
	<col width="30">
	<col>
	<col width="80">
	<col width="120">
	</colgroup>
	<thead>
		<tr>
			<th class="first"><p class="center"><input type="checkbox" name="all" checked="checked" onclick="list_toggle(form)" style="cursor: pointer"></p></th>
			<th><p class="center normal"><span class="btnPack white small icon"><span class="check"></span><a href="javascript:;" onclick="order(3);">이름변경</a></span>
			<span class="btnPack white small icon"><span class="delete"></span><a href="javascript:;" onclick="order(2);" class="button">선택삭제</a></span>&nbsp;&nbsp;&nbsp;
			<span class="btnPack small icon"><span class="add"></span><a href="javascript:;" onclick="$.dialog('./ftpUpload.php?type=folder&dir=<?php echo($_GET[dir]);?>',null,400,90);">폴더생성</a></span>
			<span class="btnPack small icon"><span class="add"></span><a href="javascript:;" onclick="$.dialog('./ftpUpload.php?type=file&dir=<?php echo($_GET[dir]);?>',null,400,240);">파일 올리기</a></span>
			<?php if($_SESSION['uid']=='test1') { ?><span class="btnPack small icon"><span class="add"></span><a href="javascript:;" onclick="$.dialog('./ftpUpload.php?type=file2&dir=<?php echo($_GET[dir]);?>',null,415,155);">swfUpload</a></span><?php } ?>
			<?php if($_SESSION['uid']=='test1') { ?><span class="btnPack small icon"><span class="add"></span><a href="javascript:;" onclick="$.dialog('./ftpUpload.php?type=file3&dir=<?php echo($_GET[dir]);?>',null,400,240);">jqueryUpload</a></span><?php } ?>
			<span class="btnPack black small icon"><span class="download"></span><a href="javascript:;" onclick="order(1);">선택백업</a></span></p>
			</th>
			<th><p class="center"><span>파일크기</span></p></th>
			<th><p class="center"><span>저장날짜</span></p></th>
		</tr>
		<?php
		if($_GET[dir]) {
		?>
		<tr>
			<th class="first" colspan="4" style="cursor:pointer; font-weight:normal;" onclick="$.insert('#dirSub','./ftpData.php?dir=<?php echo($dirUp);?>',null,300);"><img src="<?php echo($cfg[droot]);?>common/image/files/folder_top.png" width="18" height="18" alt="상위 디렉토리" style="vertical-align: middle;" />(상위로) . .</th>
		</tr>
		<?php
		}
		?>
	</thead>
	<tbody>
	<?php
	if($fileCount > 0)
	{
		ob_start();
		@sort($fileArray);

//		폴더일 경우
		foreach ($fileArray as $key => $value)
		{
			$dir	= ($_GET[dir]) ? $droot.$_GET[dir]."/".$value : $droot.$_GET[dir].$value;
			if(is_dir($dir))
			{
				$key = (!$key || $key == '') ? 0 : $key;
				$url = str_replace($_SERVER[DOCUMENT_ROOT],"",$dir);
//				디렉토리 용량계산
				$size = $func->getDirectorySize($dir);
				$used = ($size < 1024000) ? @number_format($size/1024,1)."KB" : @number_format($size/1024000,1)."MB";
				$used = ($size >= 1024000000) ? '<span class="red">'.@number_format($size/1024000000,1).'GB</span>' : $used;
//				파일의 최종시간
				$time = date("Y-m-d H:i:s",filectime($dir));
				echo('<tr>
				<th scope="col"><p class="center"><input type="checkbox" name="choice['.$key.']" value="'.$value.'" /></p></th>
				<td class="accent" style="text-align:left; cursor:pointer;" onmouseover="this.className=\'accent open\'" onMouseOut="this.className=\'accent\'" onclick="$.insert(\'#dirSub\',\'./ftpData.php?dir='.$_GET[dir].'/'.$value.'\',null,300);"><img src="'.$cfg[droot].'common/image/files/folder_on.png" width="18" height="18" onerror="this.src=\''.$cfg[droot].'common/image/files/unKonwn.gif\'" alt="폴더" /><strong>'.$value.'</strong></td>
				<td><p class="right"><strong class="small_gray">'.$used.'</strong></p></td>
				<td class="bg_gray"><p class="center"><span class="small_gray">'.$time.'</span></p></td>
				</tr>');
			}
		}
//		파일일경우
		foreach ($fileArray as $key => $value)
		{
			//$root	= ($cfg[site][setup] == 'Y') ? $cfg[droot].'_Site/'.__DB__.'/' : $cfg[droot].'skin/';
			$root	= $cfg[droot].'_Site/'.__DB__.'/';
			$dir 	= ($_GET[dir]) ? $droot.$_GET[dir]."/".$value : $droot.$_GET[dir].$value;
			$value 	= ($cfg[charset] == 'euc-kr') ? iconv("UTF-8//IGNORE","CP949",$value) : $value;
			$skin		= (preg_match('/mobile/i', $dir)) ? "mobile" : "default";
			$skin		= (preg_match('/english/i', $dir)) ? "english" : $skin;
			if(is_file($dir))
			{
				$key = (!$key || $key == '') ? 0 : $key;
				$url = str_replace($_SERVER['DOCUMENT_ROOT'],"",$dir);
//				파일의 아이콘 설정
				$ext = array_reverse(explode(".",strtolower($value)));
				$extname = array('jpg','jpeg','gif','png','bmp','swf');
//				파일의 최종시간
				$time = date("Y-m-d H:i:s",filectime($dir));
				
				//if($_SESSION['ulevel'] == '1'){
					echo('<tr>
					<th scope="col"><p class="center"><input type="checkbox" name="choice['.$key.']" value="'.$value.'" /></p></td>
					<td><a href="'.$cfg[droot].'addon/system/download.php?file='.$sess->encode($dir).'&name='.$value.'" title="저장하기"><img src="'.$cfg[droot].'common/image/files/'.$ext[0].'.gif" width="16" height="16" align="absmiddle" onError="this.src=\''.$cfg[droot].'common/image/files/unKonwn.gif\'" border="0" alt="저장하기" /></a> ');
					$size = filesize($dir);
					$used = ($size < 1024000) ? @number_format($size/1024,1)."KB" : @number_format($size/1024000,1)."MB";
					if(in_array($ext[0], $extname)) {
						$img = @getimagesize($dir);
						echo('<a href="'.$root.$_GET[dir].'/'.$value.'" rel="facebox" title="미리보기">'.$value.'</a> <span class="small_gray">('.$img[0].'x'.$img[1].')</span></td>');
					} else {
                        if($size > 10240000)
                        {
						    echo('<a href="'.$cfg[droot].'addon/system/download.php?file='.$sess->encode($dir).'&name='.$value.'" title="저장하기">'.$value.'</a></td>');
                        } else {
                            echo('<a href="javascript:;" onclick="new_window(\'/_Admin/modules/editorFile.php?dir='.$_GET['dir'].'&cached='.$value.'\',\'editor\',1024,600,\'no\',\'yes\');">'.$value.'</a></td>');
                        }
					} //in_array.end
					echo('<td><p class="right"><strong class="small_gray">'.$used.'</strong></p></td>
					<td><p class="center"><span class="small_gray">'.$time.'</span></p></td>
					</tr>');
				
			} //is_file.end
		} //foreach.end
		$buf = ob_get_contents();
		ob_end_clean();

		echo($buf);

	}
	else
	{
		echo('<tr><td colspan="4" class="blank">폴더나 파일이 존재하지 않습니다.</td></tr>');
	}
	?>
	</tbody>
</table>
</form>
<?php include "../include/commonScript.php"; ?>
