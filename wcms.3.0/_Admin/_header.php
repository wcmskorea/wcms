<?php
require_once "../_config.php";

if((preg_match("/\_Admin/", $_SERVER['REQUEST_URI']) || preg_match("/admin/", $_SERVER['REQUEST_URI'])) && ($_SESSION['ulevel'] > $cfg['operator'] || !$_SESSION['ulevel']))
{
	Header("Location: /_Admin/login.php");
	die();
}
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo($cfg['site']['siteName']);?> &gt; 관리시스템 [WCMS <?php echo(strtoupper($cfg['version']));?>]</title>
	<meta http-equiv="content-type" content="application/xhtml+xml; charset=<?php echo($cfg['charset']);?>" />
	<meta http-equiv="content-style-type" content="text/css" />
	<meta http-equiv="X-UA-Compatible" content="IE=8" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta http-equiv="page-enter" content="blendtrans(duration=0.2)"/>
	<meta http-equiv="page-exit" content="blendtrans(duration=0.2)"/>
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="content-language" content="kr" />
	<meta name="robots" content="none" />
	<meta name="publisher" content="10억홈피" />
	<meta name="description" content="홈페이지,웹마스터,웹프로그래밍,웹디자인,유지보수,웹호스팅" />
	<meta name="keywords" content="자바스크립트,html,플래쉬,플래시,홈페이지,웹마스터,웹프로그래밍,웹디자인,유지보수" />
	<link rel="shortcut icon" href="<?php echo($cfg['droot']);?>common/image/favicon.ico" type="image/x-ico" />
	<link rel="stylesheet" href="<?php echo($cfg['droot']);?>common/css/admin.css" type="text/css" charset="<?php echo($cfg['charset']);?>" media="all" />
	<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/common.js"></script>
	<script type="text/javascript" src="<?php echo($cfg['droot']);?>addon/editor/js/editor_loader.js" charset="<?php echo($cfg['charset']);?>"></script>
	<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/jquery.ui.js"></script>
	<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/jquery.date.js"></script>
	<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/jquery.facebox.js"></script>
	<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/ajax.js"></script>
	<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/validation.js"></script>
	<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/jquery.validate.js"></script>

</head>
<body style="background-color:#e5e5e5;">
<h1><?php echo($cfg['site']['siteName']);?> - 관리시스템</h1>
<div id="wrap" class="left">
<div id="header">
<div class="gnb">

	<ul>
		<li class="title" style="width:180px;"><a href="<?php echo($cfg['droot']);?>index.php" class="actviolet bold"><?php echo(Functions::cutStr($cfg['site']['siteName'], 20));?></a></li>
		<?php if($_SESSION['ulevel'] < $cfg['operator']) { ?>
		<li class="depth1" style="width:130px;"><a href="javascript:;" onclick="$.insert('#module','./site/index.php?type=info',null,300)" class="actwhite bold">기본정보 설정</a></li>
		<?php if($_SESSION['ulevel'] < $cfg['operator']) { ?><li class="depth1" style="width:130px;"><a href="javascript:;" onclick="$.insert('#module', './site/index.php?type=site',null,300)" class="actwhite bold">사이트 환경 설정</a></li><?php } ?>
		<li class="depth1" style="width:130px;"><a href="javascript:;" onclick="$.insert('#module', './modules/index.php?type=cateList&skin=default',null,300)" class="actwhite bold">카테고리 설정</a></li>
		<li class="depth3" style="width:130px;"><a href="http://www.aceoa.com" target="_blank" class="actaqua"><span style="font-size:10px; color:#ffffcc;">POWERD BY</span><br />WCMS <?php echo($cfg['version']);?></a></li>
		<!--<li class="depth2" style="width:60px;"><a href="./tools/css.php?type=<?php echo($sess->encode('autoLink'))?>" target="hdFrame" class="actviolet">CSS 요청</a></li>-->
		<?php if($_SESSION['ulevel'] < $cfg['operator']) { ?><li class="depth2" style="width:60px;"><a href="javascript:;" onclick="new_window('/_Admin/tools/db/','wftp','1024','768','no','yes');" class="actviolet">DB 관리</a></li><?php } ?>
		<li class="depth2" style="width:60px;"><a href="javascript:;" onclick="new_window('./wftp/ftp.php','wftp','800','600','no','yes');" class="actviolet">파일 관리</a></li>
		<?php } ?>
	</ul>
	<div class="clear"></div>
</div><!--end.gnb-->
<hr />
<div class="lnb">
	<ul>
		<li class="menu1"><span class="btnPack black small"><a href="<?php echo($cfg['droot']);?>" style="width:90px;" target="_blank">홈페이지열기</a></span>&nbsp;<span class="btnPack black small"><a href="<?php echo($cfg['droot']);?>_Admin/_logout.php">로그아웃</a></span></li>
		<li class="menu2"><span class="btnPack white small icon"><span class="refresh"></span><a href="<?php echo($cfg['droot']);?>_Admin/">대시보드</a></span></li>
		<li class="menu2"><span class="btnPack black small"><a href="javascript:;" onclick="$.dialog('../modules/mdDocument/manage/search.php',null,420,150);">문서검색</a></span></li>
		<?php
		//회원검색 버튼
		if($func->checkModule('mdMember')) { echo('<li class="menu2"><span class="btnPack black small"><a href="javascript:;" onclick="$.dialog(\'../modules/mdMember/manage/search.php\',null,420,150);">회원검색</a></span></li>'); }
		?>
	</ul>
	<div class="clear"></div>
</div>
<hr />
</div>
