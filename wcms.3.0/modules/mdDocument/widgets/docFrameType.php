<?php
/**--------------------------------------------------------------------------------------
 * 최근 게시물 (일반형)
 *---------------------------------------------------------------------------------------
 * Relationship : /_Lib/classDisplay.php
 *---------------------------------------------------------------------------------------
 */
require_once "../../../_config.php";
$func->checkRefer();

$display->mode = "recent";

$query= " SELECT * FROM `display__".$cfg['skin']."` WHERE sort='".$_GET['sort']."' AND position='".$_GET['position']."' AND useHidden='N' ORDER BY sort ASC ";
$Rows = $db->queryFetch($query);
$display->sort		= $Rows['sort'];
$display->cate 		= $Rows['cate'];
$display->displayPos= $Rows['position'];
$display->cateName	= htmlspecialchars($Rows['name']);
$display->config	= unserialize($Rows['config']);
$display->config['mgt'] = 0;
$display->config['mgr'] = 0;
$display->config['mgb'] = 0;
$display->config['mgl'] = 0;
$display->form			= $Rows['form'];
$display->listing		= $Rows['listing'];
$display->target 		= ($display->form == 'frame') ? "_parent" : "_self";

//Header 출력
$display->loadHeader();
//Body 출력
$display->loadBody();
//widget 출력
echo($display->setModuleBox());
//Footer 출력
$display->loadFooter();

$db->freeResult();
?>
