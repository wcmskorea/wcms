<?php
require_once "../../_config.php";
require_once "./commonHeader.php";
?>
<table class="table_basic" style="width:100%;">
<colgroup>
<col>
</colgroup>
<thead>
<tr>
  <th class="first"><p class="center"><span>신규 회원·방문</span></p></th>
</tr>
</thead>
<tbody>
<tr>
	<td style="vertical-align:top;text-align:left;width:200px;" class="sideLine">
    <ol>
    <?php
    if(count($cfg['modules']) > 1)
    {
      if($func->checkModule('mdAnalytic'))
      {
        #--- 방문자 정보
        echo('<li class="darkgray pd3"><span><img src="'.$cfg['droot'].'common/image/icon/icon_aw_right.gif" width="11" height="11" /></span>&nbsp;<span>오늘 방문</span>&nbsp;:&nbsp;<strong class="colorBlue">'.$sess->counting('today').'</strong>&nbsp;명</li>
        <li class="darkgray pd3"><span><img src="'.$cfg['droot'].'common/image/icon/icon_aw_right.gif" width="11" height="11" /></span>&nbsp;<span>전체 방문</span>&nbsp;:&nbsp;<strong class="colorBlue">'.$sess->counting('all').'</strong>&nbsp;명</li>');
      }
      if($func->checkModule('mdMember'))
      {
        #--- 회원 정보
        $count = $db->queryFetch(" SELECT SUM(if(level>'0',1,0)) AS siteJoin ,SUM(if(level='0',1,0)) AS siteOut
																			FROM `mdMember__account`
																			WHERE DATE_FORMAT(FROM_UNIXTIME(dateReg),'%Y-%m-%d')='".date("Y-m-d")."' ");

        echo('<li class="darkgray pd3"><span><img src="'.$cfg['droot'].'common/image/icon/icon_aw_right.gif" width="11" height="11" /></span>&nbsp;<span>가입 회원</span>&nbsp;:&nbsp;<strong class="colorRed">'.number_format($count['siteJoin']).'</strong>&nbsp;명&nbsp;');
        if($count[0] > 0) { echo('<img src="'.$cfg['droot'].'common/image/icon/icon_a_new.gif" width="9" height="9" />'); }
        echo('</li>');
        echo('<li class="darkgray pd3"><span><img src="'.$cfg['droot'].'common/image/icon/icon_aw_right.gif" width="11" height="11" /></span>&nbsp;<span>탈퇴 회원</span>&nbsp;:&nbsp;<strong class="colorRed">'.number_format($count['siteOut']).'</strong>&nbsp;명&nbsp;');
        if($count[1] > 0) { echo('<img src="'.$cfg['droot'].'common/image/icon/icon_a_new.gif" width="9" height="9" />'); }
        echo('</li>');
        unset($count);
      }
    }
    else
    {
      echo('<li class="darkgray pd3"><span>설정된 모듈이 존재하지 않습니다</span></li>');
    }
    ?>
    </ol>
  </td>
</tr>
</tbody>
</table>
<div class="clear"></div>
