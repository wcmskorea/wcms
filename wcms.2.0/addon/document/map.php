<?php
$sizeWidth							= $cfg['site']['size'] - $cfg['site']['sizeSsnb'] - $cfg['site']['sizeSside'] - $cfg['site']['padContentLeft'] - $cfg['site']['padContentRight'];
//$sizeHeight							= "800";
$cfg['site']['address']	= trim(preg_replace("/\((.*?)\)/","", $cfg['site']['address']));  

$mapInfo = $db->queryFetch(" SELECT * FROM `mdMap__` WHERE cate='".__CATE__."' ");

$mapInfo['provider'] = $mapInfo['provider'] ? $mapInfo['provider'] : 'daum';
$mapInfo['address'] = $mapInfo['address'] ? trim(preg_replace("/\((.*?)\)/","", $mapInfo['address'])) : $cfg['site']['address'];
$config = unserialize($mapInfo['config']);
$sizeHeight = $config['mapSizeHeight'] ? $config['mapSizeHeight'] : "800";
$roadView   = $mapInfo['mapType'] == 'map' ? 'no' : 'yes';
?>
<!-- Content : Start -->
<div class="contentBody textContent">
<table class="table_basic" style="width:100%">
	<colgroup>
		<col width="100">
		<col>
	</colgroup>
	<tbody>
		<tr>
			<th scope="row"><p class="center pd7 strong">주 소</p></th>
			<td class="pd7"><p><?php echo($cfg['site']['address']); ?></p></td>
		</tr>
		<tr>
			<th scope="row"><p class="center pd7 strong">대표전화</p></th>
			<td class="pd7"><p><?php echo($cfg['site']['phone']); ?></p></td>
		</tr>
	</tbody>
</table>
</div>
<div class="clear"></div>
<!-- Content : End -->
<!-- Map : Start -->
<?php
if($mapInfo['provider'] == 'daum')
{
?>
<div id="daumMap">
	<iframe name="daumMap" src="/addon/api/mapDaum.php?address=<?php echo(urlencode($mapInfo['address']));?>&amp;apiKey=<?php echo($cfg['site']['apiDaummap']);?>&amp;type=map&amp;width=<?php echo($sizeWidth);?>&amp;height=<?php echo($sizeHeight);?>&amp;info=<?php echo($cfg['site']['groupName']);?>&amp;roadView=<?php echo($roadView);?>" width="100%" height="<?php echo($sizeHeight);?>" frameborder="0" scrolling="no"></iframe>
	<?php if($roadView == 'yes') { ?>
	<div class="center" style="border-bottom: #f3c534 1px dashed; border-left: #f3c534 1px dashed; padding-bottom: 10px; background-color: #fefeb8; padding-left: 10px; padding-right: 10px; border-top: #f3c534 1px dashed; border-right: #f3c534 1px dashed; padding-top: 10px" class=txc-textbox><p>로드뷰는 Daum에서 업데이트가 수시 이뤄지지 않아 최근 정보가 아닐 수 있으니 위치만 참고해 주십시오.</p></div>
	<?php } ?>
</div>
<?
} else if($mapInfo['provider'] == 'naver') {
?>
<div id="naverMap">
	<iframe name="naverMap" src="/addon/api/mapNaver.php?address=<?php echo(urlencode($mapInfo['address']));?>&amp;apiKey=<?php echo($cfg['site']['apiNavermap']);?>&amp;type=map&amp;width=<?php echo($sizeWidth);?>&amp;height=<?php echo($sizeHeight);?>&amp;info=<?php echo($cfg['site']['groupName']);?>" width="100%" height="<?php echo($sizeHeight);?>" frameborder="0" scrolling="no"></iframe>
</div>
<?
}
?>
<!-- Map : End -->