<?php
require_once "../../_config.php";
$func->checkRefer();

$db->data['sort']	= $db->queryFetchOne(" SELECT MAX(sort) FROM `display__".$_GET['skin']."` WHERE position='".$_GET['position']."' ") + 1;
$db->data['position'] = $position;
$db->data['name'] = "줄바꿈";
$db->data['form'] = "clear";
$db->data['config']['common'] = "Y";
$db->data['config']['width'] = (!preg_match('/px|%/',$width)) ? $width.'px' : 0;
$db->data['config']['height'] = (!preg_match('/px|%/',$height)) ? $height.'px' : 0;
$db->data['config']['pdt'] = ($pdt) ? (!preg_match('/px|%/',$pdt)) ? $pdt.'px' : $pdt : '0px';
$db->data['config']['pdr'] = ($pdr) ? (!preg_match('/px|%/',$pdr)) ? $pdt.'px' : $pdr : '0px';
$db->data['config']['pdb'] = ($pdb) ? (!preg_match('/px|%/',$pdb)) ? $pdb.'px' : $pdb : '0px';
$db->data['config']['pdl'] = ($pdl) ? (!preg_match('/px|%/',$pdl)) ? $pdl.'px' : $pdl : '0px';
$db->data['config']['mgt'] = ($mgt) ? (!preg_match('/px|%/',$mgt)) ? $mgt.'px' : $mgt : '0px';
$db->data['config']['mgr'] = ($mgr) ? (!preg_match('/px|%/',$mgr)) ? $mgr.'px' : $mgr : '0px';
$db->data['config']['mgb'] = ($mgb) ? (!preg_match('/px|%/',$mgb)) ? $mgb.'px' : $mgb : '0px';
$db->data['config']['mgl'] = ($mgl) ? (!preg_match('/px|%/',$mgl)) ? $mgl.'px' : $mgl : '0px';
$db->data['config']['bgColor'] = $bgColor;
$db->data['config'] = serialize($db->data['config']);

if($db->sqlInsert("display__".$skin, "REPLACE", 0) > 0)
{
	if(!preg_match('/\/_Admin\//', $_SERVER['HTTP_REFERER']))
	{
		$func->ajaxMsg("Display(Clear) 위젯 정보가 정상적으로 적용되었습니다.", "$.insert('#skinSelector', '".$cfg['droot']."_Admin/modules/displayList.php?type=displayList&mode=design&skin=".$_GET['skin']."&position=".$_GET['position']."',null,300);$.dialogRemove();", 20); 
	}
	else
	{
		$func->ajaxMsg("Display(Clear) 위젯 정보가 정상적으로 적용되었습니다.", "$.insert('#tabBody".$_GET['position']."', './modules/displayList.php?type=displayList&skin=".$_GET['skin']."&position=".$_GET['position']."',null,300);$.dialogRemove();", 20); 
	}
}
?>
