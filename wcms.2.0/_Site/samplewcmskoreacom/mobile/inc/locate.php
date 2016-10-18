<?php
$this->result .= '
<!-- Content : Start -->
<div class="contentBody textContent">
<table class="table_list" style="width:100%">
<colgroup>
	<col width="100">
	<col>
	<col width="100">
	<col>
</colgroup>
<tbody>
<tr>
	<th scope="row"><p class="center pd5 strong">주 소</p></th>
	<td class="pd5" colspan="3"><p>'.$cfg['site']['address'].'</p></td>
</tr>
<tr>
	<th scope="row"><p class="center pd5 strong">연락처</p></th>
	<td class="pd5" colspan="3"><p>'.$cfg['site']['phone'].'</p></td>
</tr>
</tbody>
</table>
</div>
<div class="clear"></div>
<!-- Content : End -->
<!-- Map : Start -->
<div id="daumMapApi">
<iframe name="daumMap" src="/addon/api/mapDaum.php?address='.$cfg['site']['address'].'&amp;apiKey='.$cfg['site']['apiDaummap'].'&amp;type=map&amp;width=600&amp;height=600" width="100%" height="610" frameborder="0" scrolling="no"></iframe>
</div>
<!-- Map : End -->';
?>
