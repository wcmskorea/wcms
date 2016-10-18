<?php
/**
 * Author : Sung-Jun, Lee
 * Lastest : 2010. 4. 23
 */
require_once "../../_config.php";
require_once "../include/commonHeader.php";

//해당 스킨의 테이블이 존재하지 않으면 기본테이블로 추가생성한다.
if($db->checkTable("display__".$_GET['skin']) < 1)
{
	require_once __PATH__."/_Admin/sql/default.sql.php";
	$query = str_replace("`display__default`", "`display__".$_GET['skin']."`", $sql['site']['4']);
	$db->query(trim($query));
}

//디스플레이 순서를 변경처리
if($_GET['type'] == "displayMove")
{
	$db->query(" UPDATE `display__".$_GET['skin']."` SET sort='0' WHERE sort='".$_GET['sort']."' AND position='".$_GET['position']."' ");
	if($_GET['move'] == 'up')
	{
		$moveto = intval($_GET['sort']-1);
		$listing = $db->queryFetchOne(" SELECT LOWER(listing) FROM `display__".$_GET['skin']."` WHERE sort='".$moveto."' AND position='".$_GET['position']."' ");
		$db->query(" UPDATE `display__".$_GET['skin']."` SET sort='".$_GET['sort']."' WHERE sort='".$moveto."' AND position='".$_GET['position']."' ");
		$db->query(" UPDATE `display__".$_GET['skin']."` SET sort='".$moveto."' WHERE sort='0' AND position='".$_GET['position']."' ");
	}
	else if($_GET['move'] == 'dw')
	{
		$moveto = intval($_GET['sort']+1);
		$listing = $db->queryFetchOne(" SELECT LOWER(listing) FROM `display__".$_GET['skin']."` WHERE sort='".$moveto."' AND position='".$_GET['position']."' ");
		$db->query(" UPDATE `display__".$_GET['skin']."` SET sort='".$_GET['sort']."' WHERE sort='".$moveto."' AND position='".$_GET['position']."' ");
		$db->query(" UPDATE `display__".$_GET['skin']."` SET sort='".$moveto."' WHERE sort='0' AND position='".$_GET['position']."' ");
	}
//	스킨 이동시 파일명 자동 교체
	if($_GET['form'] == 'skin' || $listing == 'gif' || $listing == 'jpg' || $listing == 'swf' || $listing == 'png')
	{
		$tmpName = __PATH__."_Site/".__DB__."/".$_GET['skin']."/image/skin_".strtolower($_GET['position'])."0";
		$oldName = __PATH__."_Site/".__DB__."/".$_GET['skin']."/image/skin_".strtolower($_GET['position']).$_GET['sort'];
		$newName = __PATH__."_Site/".__DB__."/".$_GET['skin']."/image/skin_".strtolower($_GET['position']).$moveto;
		if(is_file($newName.".".$listing)) 		{ @rename($newName.".".$listing, $tmpName.".".$listing); }			//이동할 파일이 존재하면 대상 파일을 임시파일로
		if(is_file($oldName.".".$_GET['ext'])) 	{ @rename($oldName.".".$_GET['ext'], $newName.".".$_GET['ext']); }	//이동할 파일의 파일명 교체
		if(is_file($tmpName.".".$listing)) 		{ @rename($tmpName.".".$listing, $oldName.".".$listing); } 			//임시파일을 원본 파일명으로 교체
	}
}

//디스플레이 위치정보
switch($_GET['position'])
{
	case "MT" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']].'</span> 메인(상)'; break;
	case "ML" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 메인(좌)"; break;
	case "MC" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 메인(중)"; break;
	case "MR" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 메인(우)"; break;
	case "MB" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 메인(하)"; break;
	case "MF" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 메인(풋)"; break;
	case "MQ" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 메인(퀵)"; break;
	case "ST" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 서브(상)"; break;
	case "SL" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 서브(좌)"; break;
	case "SC" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 서브(중1)"; break;
	case "SM" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 서브(중2)"; break;
	case "SR" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 서브(우)"; break;
	case "SB" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 서브(하)"; break;
	case "SF" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 서브(풋)"; break;
	case "SQ" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 서브(퀵)"; break;
	case "CR" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 서브(콘)"; break;
	case "TM" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']].'</span> 메인(상)'; break;
	case "LM" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 메인(좌)"; break;
	case "CM" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 메인(중)"; break;
	case "RM" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 메인(우)"; break;
	case "BM" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 메인(하)"; break;
	case "FM" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 메인(풋)"; break;
	case "QM" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 메인(퀵)"; break;
	case "TS" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 서브(상)"; break;
	case "LS" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 서브(좌)"; break;
	case "CS" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 서브(중1)"; break;
	case "MS" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 서브(중2)"; break;
	case "RS" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 서브(우)"; break;
	case "BS" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 서브(하)"; break;
	case "FS" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 서브(풋)"; break;
	case "QS" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 서브(퀵)"; break;
	case "CS" : $displayPos = '<span class="colorRed">'.$cfg['skinName'][$_GET['skin']]."</span> 서브(콘)"; break;
}
?>

<?php
if($_GET['mode'] == 'design')
{
	$cacheFile = ( __CATE__ ) ? __PATH__."_Site/".__DB__."/".$_GET['skin']."/cache/display/".$_GET['position'].".".__CATE__.".html" : __PATH__."_Site/".__DB__."/".$_GET['skin']."/cache/display/".$_GET['position'].".html";
	echo('<div class="menu_black"><p class="bold">'.$cfg['skinName'][$_GET['skin']].'('.__CATE__.') - 디스플레이 설정</p></div>
	<ul>
	<li class="pd5" style="float:right;"><span class="btnPack black small"><a href="javascript:;" onclick="$(\'#skin .selector\').animate({height:\'toggle\', opacity:\'toggle\'}, \'fast\');">창닫기</a></span></li>
	<li class="pd5" style="float:right;"><span class="btnPack small icon"><span class="add"></span><a href="javascript:;" onclick="$.message(\''.$cfg['droot'].'_Admin/site/index.php?type=displayCache&skin='.$_GET['skin'].'&position='.$_GET['position'].'&cate='.__CATE__.'\');">캐시생성·갱신</a></span></li>');
	if(is_file($cacheFile)) { echo('<li class="pd5 colorRed" style="float:left; padding-top:10px;"><strong>★ 캐시파일이 생성되어 있습니다.</strong></li>
	<li class="pd5" style="float:right;"><span class="btnPack small icon"><span class="delete"></span><a href="javascript:;" onclick="$.message(\''.$cfg['droot'].'_Admin/site/index.php?type=displayCacheDel&skin='.$_GET['skin'].'&position='.$_GET['position'].'&cate='.__CATE__.'\');">캐시삭제</a></span></li>'); }
	echo('</ul>
	<div><iframe id="hdFrame" name="hdFrame" style="width:100%;');
	if($cfg['site']['debug']){ echo('height:50px;'); } else { echo('display:none;'); }
	echo('"></iframe></div><div class="clear"></div>');
}
else
{
?>
<fieldset id="help">
<legend>→ 도 움 말 ←</legend>
<ul>
	<li>메인과 서브 페이지의 모듈 디스플레이를 설정하는 페이지 입니다.</li>
	<li>사이트내 설정되어 이용중인 모듈만 삽입 가능합니다.</li>
</ul>
</fieldset>
<?php
}
?>

<div style="float:left; width:600px;">
<table class="table_list" id="display_list" style="width:600px;">
	<caption>사이트 정보 설정</caption>
	<col width="45">
	<col>
	<col width="40">
	<col width="40">
	<col width="40">
	<col width="150">
	<col width="65">
	<thead>
		<tr>
			<th class="first"><p class="center normal">순서</p></th>
			<th><p class="center colorViolet"><?php echo(strtoupper($_GET['skin']));?> (<span class="black"><?php echo(strtoupper($_GET['position']));?></span>)</p></th>
			<th><p class="center normal">형태</p></th>
			<th><p class="center normal">W</p></th>
			<th><p class="center normal">H</p></th>
			<th><p class="center normal">여백</p></th>
			<th><p class="center">관리</p></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$n = 0;
	//$sq = (__CATE__) ? " AND cate like '".__CATE__."%'" : null;
	$db->query(" SELECT * FROM `display__".$_GET['skin']."` WHERE position='".$_GET['position']."'".$sq." ORDER BY sort ASC ");
	while($Rows = $db->fetch())
	{
		//외부설정과 관리모드내 설정의 차이
		$insertBox 	= ($_GET['mode'] == 'design') ? "#skinSelector" : "#tabBody".$Rows['position'];
		if($Rows['config'])
		{
			$config 	= unserialize($Rows['config']);
			foreach($config AS $key=>$val) { $config[$key] = preg_replace('/px|%/', null, $val); }
		}
		$cateName 	= ($Rows['form'] == 'skin') ? strtolower($Rows['position'])."_skin".$Rows['sort'] : $Rows['name'];
		switch($Rows['form'])
		{
			case "skin": $url = $cfg['droot']."_Admin/modules/displaySkin.php"; break;
			case "menu": $url = $cfg['droot']."_Admin/modules/displayMenu.php"; break;
			case "html": $url = $cfg['droot']."_Admin/modules/displayHtml.php"; break;
			case "inc": $url = $cfg['droot']."_Admin/modules/displayInc.php"; break;
			case "sns": $url = $cfg['droot']."_Admin/modules/displaySns.php"; break;
			default: $url = $cfg['droot']."modules/".$config['module']."/manage/_display.php"; break;
		}
		$Rows['name'] = ($config['common'] && $config['common'] != 'Y' && $config['common'] != ',') ? '('.$config['common'].') '.$Rows['name'] : $Rows['name'];
		$Rows['name'] = ($config['common'] == 'N') ? str_replace("(".$config['common'].")", "(지역) ", $Rows['name']) : $Rows['name'];
		$Rows['name'] = $func->cutStr($Rows['name'], 24, "...");
		$Rows['name'] = ($Rows['useHidden'] == "Y") ? '<span class="noblock colorRed">(hide)</span> <strike>'.$Rows['name'].'</strike>' : $Rows['name'];
		$Rows['name'] = ($Rows['sort'] == $moveto) ? '<span class="noblock colorOrange">'.$Rows['name'].'</span>' : $Rows['name'];
		?>
		<tr>
			<td class="bg_gray"><p class="center"><a href="javascript:;" onclick="$.insert('<?php echo($insertBox);?>', '<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayMove&mode=<?php echo($_GET['mode']);?>&sort=<?php echo($Rows['sort']);?>&skin=<?php echo($_GET['skin']);?>&position=<?php echo($Rows['position']);?>&move=up&form=<?php echo($Rows['form']);?>&ext=<?php echo($Rows['listing']);?>',null,300)"><img src="<?php echo($cfg['droot']);?>common/image/button/btn_s_up.gif" alt="위로" /></a>
			<a href="javascript:;" onclick="$.insert('<?php echo($insertBox);?>', '<?php echo($cfg['droot']);?>_Admin/modules/displayList.php?type=displayMove&mode=<?php echo($_GET['mode']);?>&sort=<?php echo($Rows['sort']);?>&skin=<?php echo($_GET['skin']);?>&position=<?php echo($Rows['position']);?>&move=dw&form=<?php echo($Rows['form']);?>&ext=<?php echo($Rows['listing']);?>',null,300)"><img src="<?php echo($cfg['droot']);?>common/image/button/btn_s_down.gif" alt="아래로" /></a></p></td>
			<td><span class="small_gray"><?php echo($Rows['sort']);?>. <?php echo($Rows['name']);?></span></td>
			<td class="bg_gray"><p class="center small_gray"><?php echo($Rows['form']);?></p></td>
			<td><p class="center small_gray"><?php echo($config['width']);?></p></td>
			<td><p class="center small_gray"><?php echo($config['height']);?></p></td>
			<td><p class="small_gray"><?php echo($config['pdt']);?>,<?php echo($config['pdr']);?>,<?php echo($config['pdb']);?>,<?php echo($config['pdl']);?> / <?php echo($config['mgt']);?>,<?php echo($config['mgr']);?>,<?php echo($config['mgb']);?>,<?php echo($config['mgl']);?></p></td>

            <td class="bg_gray"><p class="center"><?php if($Rows['form']!='clear'){?><a href="javascript:;" onclick="$.dialog('<?php echo($url);?>?type=displayModify&cate=<?php echo($Rows['cate']);?>&sort=<?php echo($Rows['sort']);?>&skin=<?php echo($_GET['skin']);?>&position=<?php echo($Rows['position']);?>',null,800,600)" class="act"><img src="<?php echo($cfg['droot']);?>common/image/button/btn_s_modify.gif" width="15" height="14" alt="수정" title="수정" /></a><?php } ?>
			<a href="javascript:;" onclick="$.dialog('<?php echo($cfg['droot']);?>_Admin/modules/index.php?type=displayClone&cloneSort=<?php echo($Rows['sort']);?>&skin=<?php echo($_GET['skin']);?>&position=<?php echo($Rows['position']);?>',null,350,120);" class="act"><img src="<?php echo($cfg['droot']);?>common/image/button/btn_s_clone.gif" width="15" height="14" alt="복제" title="복제" /></a>
			<a href="javascript:;" onclick="if(delThis()){$.message('<?php echo($cfg['droot']);?>_Admin/modules/index.php?type=displayDel&sort=<?php echo($Rows['sort']);?>&skin=<?php echo($_GET['skin']);?>&position=<?php echo($Rows['position']);?>');}" class="act"><img src="<?php echo($cfg['droot']);?>common/image/button/btn_s_del.gif" width="15" height="14" alt="삭제" title="삭제" /></a></p>
			</td>
		</tr>
		<?php
		unset($config);
		$n++;
	}
	while($n < 14)
	{
		?>
		<tr>
			<td class="bg_gray"><p class="center">-</p></td>
			<td>-</td>
			<td class="bg_gray"></td>
			<td><p class="center small_gray"></p></td>
			<td><p class="center small_gray"></p></td>
			<td><p class="center small_gray"></p></td>
			<td class="bg_gray"><p class="center small_gray"></p></td>
		</tr>
		<?php
		$n++;
	}
	?>
	</tbody>
</table>
</div>
<?php
if($_SESSION['ulevel'] < $cfg['operator'])
{
?>
<div style="float:left; width:200px;">
<div style="padding:3px; font-weight:bold;"><span class="colorViolet">▼ 등록할 위젯 선택</span></div>
<ul>
	<li class="pd3">←&nbsp;<a href="javascript:;" onclick="$.dialog('<?php echo($cfg['droot']);?>_Admin/modules/displaySkin.php?skin=<?php echo($_GET['skin']);?>&position=<?php echo($_GET['position']);?>',null,800,600)"><strong>Skin</strong></a></li>
	<li class="pd3">←&nbsp;<a href="javascript:;" onclick="$.dialog('<?php echo($cfg['droot']);?>_Admin/modules/displayMenu.php?skin=<?php echo($_GET['skin']);?>&position=<?php echo($_GET['position']);?>',null,800,600)"><strong>Navigation</strong></a></li>
	<li class="pd3">←&nbsp;<a href="javascript:;" onclick="$.dialog('<?php echo($cfg['droot']);?>_Admin/modules/displayInc.php?skin=<?php echo($_GET['skin']);?>&position=<?php echo($_GET['position']);?>',null,800,600)"><strong>File Include</strong></a></li>
	<!--<li class="pd3">←&nbsp;<a href="javascript:;" onclick="$.dialog('<?php echo($cfg['droot']);?>_Admin/modules/displaySns.php?skin=<?php echo($_GET['skin']);?>&position=<?php echo($_GET['position']);?>',null,800,600)"><strong>SNS API</strong></a></li>
	<li class="pd3">←&nbsp;<a href="javascript:;" onclick="$.message('<?php echo($cfg['droot']);?>_Admin/modules/displayClear.php?skin=<?php echo($_GET['skin']);?>&position=<?php echo($_GET['position']);?>');"><strong>Clear</strong></a></li>-->
	<?php
	foreach($cfg['modules'] AS $val)
	{
		if(is_file(__PATH__.'modules/'.$val.'/manage/_display.php'))
		{
			echo('<li class="pd3">←&nbsp;<a href="javascript:;" onclick="$.dialog(\''.$cfg['droot'].'modules/'.$val.'/manage/_display.php?skin='.$_GET['skin'].'&position='.$_GET['position'].'&module='.$val.'\',null,800,600)">'.$cfg['solution'][$val].'</a></li>');
		}
	}
	?>
</ul>
</div>
<?php
}
?>
<div class="clear"></div>
<?php require_once __PATH__."/_Admin/include/commonScript.php"; ?>

