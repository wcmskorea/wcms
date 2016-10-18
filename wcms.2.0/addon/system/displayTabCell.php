<?php
/**
 * 최근 게시물 탭방식
 * Relationship : /modules/displayTab.php
 */
require_once "../../_config.php";
//$func->checkRefer();

if($_GET['type'] != "recentData") { $func->ajaxMsg("정상적인 접근이 아닙니다.", "", 20); }

$query= " SELECT * FROM `display__".$cfg['skin']."` WHERE cate='".__CATE__."' AND position='".$_GET['position']."' AND form='tab".$_GET['group']."' ORDER BY sort ASC ";
$Rows = $db->queryFetch($query);
$display->displayPos= $_GET['position'];
$display->sort		= $Rows['sort'];
$display->prefix	= $display->displayPos.$display->sort;
$display->cate 		= $Rows['cate'];
$display->cateName	= htmlspecialchars($Rows['name']);
$display->form		= $Rows['form'];
$display->listing	= $Rows['listing'];
$display->target 	= ($display->form == 'frame') ? "_parent" : "_self";
$display->config	= unserialize($Rows['config']);
$display->share 	= ($display->config['share']) ? $display->config['share'] : $display->cate;
$display->config['mgt'] = 0;
$display->config['mgr'] = 0;
$display->config['mgb'] = 0;
$display->config['mgl'] = 0;
$display->config['pdt'] = 0;
$display->config['pdr'] = 0;
$display->config['pdb'] = 0;
$display->config['pdl'] = 0;
$display->config['useTitle'] = "N";

echo($display->setModuleBox());

//탭 이미지 교체 및 more버튼 노출
echo('<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){').PHP_EOL;
$db->query(" SELECT cate FROM `display__".$cfg['skin']."` WHERE position='".$display->displayPos."' AND form='tab".$_GET['group']."' ORDER BY sort ASC ");
while($tab = $db->fetch())
{
	echo('if($("#recentTab_'.$tab['cate'].'").length > 0) { $("#recentTab_'.$tab['cate'].'").attr("src", $("#recentTab_'.$tab['cate'].'").attr("src").replace("_over_","_")); }').PHP_EOL;
}
echo('if($("#recentTab_'.__CATE__.'").length > 0) { $("#recentTab_'.__CATE__.'").attr("src", $("#recentTab_'.__CATE__.'").attr("src").replace("_","_over_")); }').PHP_EOL;
//more 이미지 교체
if($display->config['useMore'] == 'Y')
{
	echo('$("#tab'.$display->displayPos.$_GET['ftab'].'_more").html(\'<a href="'.$cfg['droot'].'index.php?cate='.__CATE__.'">');
	echo('<img src="'.__SKIN__.'image/button/recentTabMore_'.strtolower($display->prefix).'.gif" alt="더보기" title="더보기" />');
	echo('</a>\');').PHP_EOL;
}
echo('});
//]]></script>');
?>
