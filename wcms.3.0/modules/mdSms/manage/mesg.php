<?php
require_once  "../../../_config.php";
if(!in_array('mdSms',$cfg['modules'])) { $func->ErrMsg("문자 서비스를 이용하고 있지 않습니다.","self.close();"); }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
	<title><?=$cfg['site']['siteName']?> :: WEB을통한 파일관리 시스템 [WCMS-<?=strtoupper($cfg[site][lang])?>]</title>
	<meta http-equiv="content-type" content="application/xhtml+xml; charset=<?=$cfg[charset]?>" />
	<meta http-equiv="content-style-type" content="text/css" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta name="description" content="홈페이지,웹마스터,웹프로그래밍,웹디자인,유지보수,웹호스팅" />
	<meta name="keywords" content="자바스크립트,html,플래쉬,플래시,홈페이지,웹마스터,웹프로그래밍,웹디자인,유지보수" />
	<link rel="stylesheet" href="<?=$cfg[droot]?>common/css/admin.css" type="text/css" charset="<?=$cfg[charset]?>" media="all" />
	<script type="text/javascript" src="/common/js/common.js"></script>
  <script type="text/javascript" src="/common/js/jquery.js"></script>
  <script type="text/javascript">
  <!--
    function insertMsg(msg)
    {
      opener.$('#tbMessage').val(msg);
      opener.$('#tbMessage').select();
      self.close();
    }
  //-->
  </script>
</head>
<body style="background:url();">
<div class="menu_violet"><p title="드래그하여 이동하실 수 있습니다"><strong>보낸 메세지 보관함</strong></p></div>
	<table class="table_basic" style="width:100%">
	<colgroup>
		<col width="60">
		<col>
		<col width="40">
	</colgroup>
	<thead>
	<tr>
	<th scope="col" class="bg_gray small_gray" colspan="3"><p class="center">메세지를 클릭하시면 자동입력 됩니다.</p></th>
	</tr>
	</thead>
	<tbody>
	<?php
	if($_GET[type] == 'del' && $_GET[idx])
	{
		$db->query(" DELETE FROM `mdSms__mesg` WHERE seq='".$_GET[idx]."' ");
		$db->query(" OPTIMIZE TABLES `mdSms__mesg` ");
	}
	#--- 게시물 리스트 및 페이징 설정
	$row						= 10;
	$totalRec				= $func->getArticledCount("mdSms__mesg", "1");
	$pagingInstance = new Paging($totalRec, $currentPage, $row, 5);
	$pagingInstance->addQueryString("&amp;".__PARM__."&amp;type=list&amp;sh=".$_GET[sh]."&amp;shc=".$_GET[shc]);
	$pagingResult		= $pagingInstance->result("text");
	$n							= $totalRec - $pagingResult[LimitIndex];
	$i = 0;
	$db->Query("SELECT * FROM `mdSms__mesg` ORDER BY date DESC ".$pagingResult[LimitQuery]);
	while($Rows = $db->Fetch())
	{
	?>
	<tr>
		<th scope="col" class=""><p class="center"><?=$n?></p></th>
		<td scope="col" class=""><a href="#none" onclick="insertMsg('<?php echo($Rows[mesg]);?>')"><?=$func->cutStr($Rows[mesg],20,"...")?></td>
		<td scope="col" class=""><a href="<?=$_SERVER[PHP_SELF]?>?idx=<?=$Rows[seq]?>&type=del" class="btnPack black small"><span>삭제</span></a></td>
	</tr>
	<?php
		$n--;
		$i++;
	}
	while($i < $row)
	{
		echo('<tr>
				<th scope="col" class=""><p class="center">&nbsp;</p></td>
				<td scope="col" class=""></td>
				<td scope="col" class=""></td>
		</tr>');
		$i++;
	}
	?>
	</tbody>
	</table>
	<div class="pageNavigation"><?=$pagingResult[PageLink]?></div>
</body>
</html>
