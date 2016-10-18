<?php
# 리퍼러 체크
require __PATH__."_Admin/include/commonHeader.php";

if($_GET['idx'])
{
	$Rows		= $db->queryFetch("SELECT * FROM `mdBanner__content` WHERE seq='".$_GET['idx']."' AND skin='".$_GET['skin']."' AND position='".$_GET['position']."'");
	$pad		= explode(",", $Rows['padding']);
	$upinfo	= explode("|", $Rows['upinfo']);
	$speriod	= ($Rows['speriod'])    ? $Rows['speriod']    : time();
	$eperiod	= ($Rows['eperiod'])    ? $Rows['eperiod']    : time();
	$title	= "['".$_GET['idx']."']배너 수정하기";

} else
{

	$Rows = $db->queryFetch(" SELECT width,height FROM `mdBanner__content` ORDER BY seq DESC LIMIT 1 ");
	$title = "신규 배너 등록하기(".$_GET['skin'].")";
	$speriod = time();
	$eperiod = time();

}
?>
<div id="messageBox"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/delete.gif" /></spna>&nbsp;<strong>Message :</strong>&nbsp;&nbsp;&nbsp;</div>

<form id="frmBanner" name="frmBanner" method="post" action="<?php echo($_SERVER['PHP_SELF']);?>" enctype="multipart/form-data" target="hdFrame">
<input type="hidden" name="type" value="insertPost" />
<input type="hidden" name="skin" value="<?php echo($_GET['skin']);?>" />
<input type="hidden" name="idx" value="<?php echo($Rows['seq']);?>" />
<input type="hidden" name="date" value="<?php echo($Rows['date']);?>" />
<input type="hidden" name="fileName" value="<?php echo($Rows['fileName']);?>" />

<div class="menu_violet"><p title="드래그하여 이동하실 수 있습니다"><strong><?php echo($title);?></strong></p></div>
<table class="table_basic" summary="" style="width:100%;">
<caption>신규 배너 등록 입력항목</caption>
<col width="120">
<col>
<col>
<thead>
<tr>
  <th class="first"><p class="center">항 목</p></th>
  <th><p class="center">기본정보 설정</p></th>
  <th><p class="center">도움말</p></th>
</tr>
</thead>
<tbody>
<tr><th>노 출 위 치</td>
    <td class="darkgray">
      <ol>
      <li class="opt"><select name="position" class="bg_gray" title="노출위치" style="width:330px;">
      <?php
      $n = 0;
      $query = " SELECT position,name FROM `display__".$_GET['skin']."` WHERE config like '%mdBanner%' ORDER BY sort ASC ";
      $db->query($query);
      while($sRows = $db->fetch())
      {
        echo('<option value="'.$sRows['position'].'"');
        if($Rows['position'] == $sRows['position']) { echo(' selected="selected" style="color:#003399;"'); }
        echo('>['.$sRows['position'].'] '.$sRows['name'].'</option>');
        $n++;
      }
      if($n < 1) { echo('<option value="">노출 위치가 설정되지 않았습니다</option>'); }
      ?>
      </select></li>
      </ol>
    </td>
    <td class="small_gray bg_gray">디스플레이 설정에서 등록된 위치정보 설정</td>
</tr>
<tr><th>이 미 지 명</td>
    <td class="darkgray">
      <ol>
      <li class="opt"><input class="input_text required" type="text" name="subject" style="width:325px;" value="<?php echo($Rows['subject']);?>" req="required" maxlength="100" /></li>
    </ol>
    </td>
    <td class="small_gray bg_gray">등록될 배너의 명칭</td>
</tr>
<tr><th>이 동 URL</td>
    <td class="darkgray">
      <ol>
      <li class="opt"><input class="input_gray" type="text" name="url" style="width:325px;" title="이 동 URL" value="<?php echo($Rows['url']);?>" maxlength="100" /></li>
      </ol>
    </td>
    <td class="small_gray bg_gray">배너 클릭시 이동될 URL정보 ("http://" 포함 입력)</td>
</tr>
<tr><th>이 동 Target</th>
    <td class="darkgray">
      <ol>
      <li class="opt"><input name="target" type="radio" id="target1" class="input_radio" value="_parent" <?php if($Rows['target']=='_parent'){echo('checked="checked"');}?>/><label for="target1">부모창 열기</label></li>
      <li class="opt"><input name="target" type="radio" id="target2" class="input_radio" value="_blank" <?php if($Rows['target']=='_blank'){echo('checked="checked" ');}?>/><label for="target2">새창 열기</label></li>
      <li class="opt"><input name="target" type="radio" id="target3" class="input_radio" value="none" <?php if(!$Rows['target'] || $Rows['target']=='none'){echo('checked="checked" ');}?>/><label for="target3">연결 안함</label></li>
      </ol>
    </td>
    <td class="small_gray bg_gray">배너 클릭시 새창열기 여부</td>
</tr>
<tr><th>이미지 첨 부</td>
    <td class="darkgray">
      <ol>
      <li class="opt"><input class="input_gray" type="file" name="upfile" style="width:330px;height:20px;" title="배너첨부" /></li>
      </ol>
    </td>
    <td class="small_gray bg_gray">배너로 노출될 파일 첨부 (jpg,gif,png,swf)</td>
</tr>
<tr><th>이미지 크 기</td>
    <td class="darkgray">
      <ol>
      <li class="opt">가로: <input class="input_text center required" type="text" name="width" style="width:40px" digits="true" maxlength="4" value="<?php echo($Rows['width']);?>" />px</li>
      <li class="opt">세로: <input class="input_text center required" type="text" name="height" style="width:40px" digits="true" maxlength="4" value="<?php echo($Rows['height']);?>" />px</li>
      </ol>
    </td>
    <td class="small_gray bg_gray">배너의 가로,세로 사이즈 (픽셀(px) 단위)</td>
</tr>
<tr><th>썸네일 첨 부</td>
    <td class="darkgray">
      <ol>
      <li class="opt"><input class="input_gray" type="file" name="upfile1" style="width:330px;height:20px;" title="썸네일첨부" /></li>
      </ol>
    </td>
    <td class="small_gray bg_gray">배너로 노출될 파일 첨부 (jpg,gif,png,swf)</td>
</tr>
<tr><th>썸네일 크 기</td>
    <td class="darkgray">
      <ol>
      <li class="opt">가로: <input class="input_gray center" type="text" name="widthThumb" style="width:40px" digits="true" maxlength="4" value="<?php echo($Rows['widthThumb']);?>" />px</li>
      <li class="opt">세로: <input class="input_gray center" type="text" name="heightThumb" style="width:40px" digits="true" maxlength="4" value="<?php echo($Rows['heightThumb']);?>" />px</li>
      </ol>
    </td>
    <td class="small_gray bg_gray">배너의 가로,세로 사이즈 (픽셀(px) 단위)</td>
</tr>
<tr><th>노 출 여 부</th>
    <td class="darkgray">
      <ol>
      <li class="opt"><input name="hidden" type="radio" id="hidden1" class="input_radio" value="N" <?php if(!$Rows['hidden'] || $Rows['hidden']=='N'){echo('checked="checked" ');}?>/><label for="hidden1">노출함</label></li>
      <li class="opt"><input name="hidden" type="radio" id="hidden2" class="input_radio" value="Y" <?php if($Rows['hidden']=='Y'){echo('checked="checked" ');}?>/><label for="hidden2">노출안함</label></li>
    </td>
    <td class="small_gray bg_gray">배너광고 노출여부 설정</td>
</tr>
<tr><th class="dash_side">노 출 기 간</td>
    <td class="dash_side darkgray">
      <ol>
      <li class="opt"><input type="text" id="speriod" name="speriod" style="text-align:center;width:80px;" class="input_gray" value="<?php echo(date("Y-m-d", $speriod));?>" />
      <select id="speriodhour" name="speriodhour" class="bg_gray" style="width:60px;">
      <?php for($i=0;$i<24;$i++) {
        $i = str_pad($i, 2, "0", STR_PAD_LEFT);
        echo('<option value="'.$i.'"');
        if($i == date("H", $speriod)) { echo(' selected="selected"'); }
        echo('>'.$i.'시</option>');
      } ?></select>
      <select id='speriodmin' name="speriodmin" class="bg_gray" style="width:60px;">
      <?php for($i=0;$i<60;$i++) {
        $i = str_pad($i, 2, "0", STR_PAD_LEFT);
        echo('<option value="'.$i.'"');
        if($i == date("i", $speriod)) { echo(' selected="selected"'); }
        echo('>'.$i.'분</option>');
      } ?></select>부터<br />
      <input type="text" id="eperiod" name="eperiod" style="text-align:center;width:80px;" class="input_gray" value="<?php echo(date("Y-m-d", $eperiod));?>" />
      <select id="eperiodhour" name="eperiodhour" class="bg_gray" style="width:60px;">
      <?php for($i=0;$i<24;$i++) {
        $i = str_pad($i, 2, "0", STR_PAD_LEFT);
        echo('<option value="'.$i.'"');
        if($i == date("H", $eperiod)) { echo(' selected="selected"'); }
        echo('>'.$i.'시</option>');
      } ?></select>
      <select id='eperiodmin' name="eperiodmin" class="bg_gray" style="width:60px;">
      <?php for($i=0;$i<60;$i++) {
        $i = str_pad($i, 2, "0", STR_PAD_LEFT);
        echo('<option value="'.$i.'"');
        if($i == date("i", $eperiod)) { echo(' selected="selected"'); }
        echo('>'.$i.'분</option>');
      } ?></select>까지 - <input name="unlimit" type="checkbox" id="unlimit" value="Y" <?php if(!$Rows['eperiod']){echo('checked="checked"');}?>/><label for="unlimit" style="color:red;">무기한</label></li>
      </ol>
    </td>
    <td class="small_gray bg_gray">배너광고 노출기간 설정<br />일정제한이 없을경우 "무기한"을 체크하시기 바랍니다.</td>
</tr>
</table>

<div class="pd5 right"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit" onclick="return $.submit(this.form)">등록하기</button></span>&nbsp;<span class="btnPack black medium"><a href="javascript:;" onclick="$.dialogRemove();">취소</a></span></div>
</form>
<script type="text/javascript">
//<[!CDATA[
	initCal({id:"speriod",type:"day",today:"y",icon:"n"});
	initCal({id:"eperiod",type:"day",today:"y",icon:"n"});
//]]>
</script>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$("input[type=text],input[type=password],textarea").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active");});
	$('#frmBanner').validate({onkeyup:function(element){$(element).valid()}});
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
    $("input[type=file]").change(function(){
			var IMG_FORMAT = "\.(gif|jpg|jpge|swf|png)$";
			if((new RegExp(IMG_FORMAT, "i")).test(this.value)) return true;
			alert("이미지 파일만 첨부하실 수 있습니다.");
            this.value = '';
			return false;
	});
});
//]]>
</script>
