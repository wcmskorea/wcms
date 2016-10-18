<?php
/**
 * Author : Sung-Jun, Lee
 * Lastest : 2010. 4. 30.
 */
if($Rows['fileCount'] > 0 && !preg_match('/rewrite|relate|reply/i', $sess->decode($_GET['type'])))
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
			$dir = ($cfg['module']['share']) ? str_replace($cfg['skin'], "default", $cfg['upload']['dir']) : $cfg['upload']['dir'];
			$dir .= date("Y",$sRows['regDate'])."/".date("m",$sRows['regDate'])."/".$sRows['fileName'];
			echo('<li class="pd3"><span><img src="'.$cfg['droot'].'common/image/files/'.strtolower($sRows['extName']).'.gif" align="absmiddle" onError="this.src=\''.$cfg['droot'].'common/image/files/unKonwn.gif\'" width="16" height="16" /></span><span><a href="'.$cfg['droot'].'addon/system/download.php?'.__PARM__.'&amp;file='.$sess->encode($dir).'&amp;name='.urlencode($sRows['realName']).'">'.$sRows['realName'].'</a></span>&nbsp;<span><input type="checkbox" id="att'.$n.'" name="oldFile[]" value="'.$sRows['seq'].'" />&nbsp;<label for="att'.$n.'">삭제</label></span></li>');
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

if($cfg['module']['uploadCount'] > 0 && $cfg['module']['uploadType'] == 'Multi')
{
?>

<div class="fileAttatchMulti">
<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/upload.js"></script>
<script type="text/javascript">
//<![CDATA[
	var _NF_UploadUrl = "<?php echo($_SERVER['PHP_SELF']);?>?<?php echo(__PARM__);?>&mode=dialog&type=<?php echo($sess->encode('upLoadFile'));?>";
	var _NF_FileFilter = "";
	var _NF_DataFieldName = "upfile";
	var _NF_Flash_Url = "<?php echo($cfg['droot']);?>common/js/upload.swf";
	var _NF_Width = "100%";
	var _NF_Height = 120;
	var _NF_ColumnHeader1 = "첨부 파일명";
	var _NF_ColumnHeader2 = "첨부용량";
	var _NF_FontFamily = "돋움";
	var _NF_FontSize = "11";
	var _NF_MaxFileSize = "<?php echo($cfg['upload']['max_size_total']);?>";
	var _NF_MaxFileCount = "<?php echo($cfg['module']['uploadCount']);?>";
	var _NF_File_Overwrite = "<?php if($cfg['upload']['file_overwrite']) { echo('true'); } else { echo('false'); }?>";
	var _NF_Limit_Ext = "<?php echo($cfg['upload']['limit_ext']);?>";
	var _NF_Img_FileBrowse = "<?php echo(__SKIN__);?>image/button/btn_bbs_file.gif";
	var _NF_Img_FileBrowse_Width = "70";
	var _NF_Img_FileBrowse_Height = "21";
	var _NF_Img_FileDelete = "<?php echo(__SKIN__);?>image/button/btn_bbs_file_del.gif";
	var _NF_Img_FileDelete_Width = "70";
	var _NF_Img_FileDelete_Height = "21";
	var _NF_TotalSize_Text = "첨부용량 ";
	var _NF_TotalSize_FontFamily = "돋움";
	var _NF_TotalSize_FontSize = "11";

	function NFU_Complete(value) {
		var files = "";
		var fileCount = 0;
		var i = 0;
		if (value == null) {
			return submitForm(document.bbsform);
		} else {
			fileCount = value.length;
			for (i = 0; i < fileCount; i++) {
				var fileName = value[i].name;
				var realName = value[i].realName;
				var fileSize = value[i].size;
				files += "<?php echo(__CATE__);?>_" + fileName + "/" + realName + "|:|";
			}

			if (files.substring(files.length - 3, files.length) == "|:|")
			files = files.substring(0, files.length - 3);
			document.bbsform.fileName.value = files;

			document.bbsform.submit();
		}
	}
	function NF_Cancel() {
		NfUpload.AllFileDelete();
		bbsForm.reset();
	}
	function NF_ShowUploadSize(value) {
		sUploadSize.innerHTML = value;
	}
	function NFUpload_Debug(value) {
		Debug("업로드가 실패하였습니다." + value);
	}
	window.onload=function() {
		document.bbsform.fileName.value = "";
	}
//]]>
</script>
<script type="text/javascript">
//<![CDATA[
	NfUpload = new NFUpload({nf_upload_id : _NF_Uploader_Id,nf_width : _NF_Width,nf_height : _NF_Height,nf_field_name1 : _NF_ColumnHeader1,nf_field_name2 : _NF_ColumnHeader2,nf_max_file_size : _NF_MaxFileSize,nf_max_file_count : _NF_MaxFileCount,nf_upload_url : _NF_UploadUrl,nf_file_filter : _NF_FileFilter,nf_data_field_name : _NF_DataFieldName,nf_font_family : _NF_FontFamily,nf_font_size : _NF_FontSize,nf_flash_url : _NF_Flash_Url,nf_file_overwrite : _NF_File_Overwrite,nf_limit_ext : _NF_Limit_Ext,nf_img_file_browse : _NF_Img_FileBrowse,nf_img_file_browse_width : _NF_Img_FileBrowse_Width,nf_img_file_browse_height : _NF_Img_FileBrowse_Height,nf_img_file_delete : _NF_Img_FileDelete,nf_img_file_delete_width : _NF_Img_FileDelete_Width,nf_img_file_delete_height : _NF_Img_FileDelete_Height,nf_total_size_text : _NF_TotalSize_Text,nf_total_size_font_family : _NF_TotalSize_FontFamily,nf_total_size_font_size : _NF_TotalSize_FontSize});
//]]>
</script>
<p class="right pd3"><span class="keeping"><input type="checkbox" id="insertFile" name="insertFile" class="input_check" value="Y" />&nbsp;<label for="insertFile">첨부된 파일중 이미지를 본문에 자동삽입</label></span></p>
</div>

<?php
}
else if($cfg['module']['uploadCount'] > 0 && $cfg['module']['uploadType'] != 'Multi')
{
?>

<div class="cube"><div class="line">
	<div class="fileAttatch">
	<table>
		<tr>
			<th>파일첨부<br />(총 <?php echo(@ini_get("post_max_size"));?>)</th>
			<td>
				<p class="pd3"><input type="file" name="upfile" style="height:18px;" class="input_white" /></p>
				<?php
				for($i=1; $i<$cfg['module']['uploadCount']; $i++)
				{
					echo('<p class="pd3"><input type="file" name="upfile'.$i.'" style="height:18px;" class="input_white" /></p>');
				}
				?>
			</td>
			<?php if($cfg['cate']['mode'] != 'mdApp01') { ?>
			<td style="vertical-align:top;"><p class="pd3"><span class="keeping"><input type="checkbox" id="insertFile" name="insertFile" class="input_check" value="Y" />&nbsp;<label for="insertFile">첨부된 파일중 이미지를 본문에 자동삽입</label></span></p></td>
			<?php } ?>
		</tr>
	</table>
	</div>
</div></div>

<?php } ?>
