<div class="sitemap">
<?php
$lineCut = 3;
$db->query(" SELECT cate,name,mode,url FROM `site__` WHERE skin='".$cfg['skin']."' AND LENGTH(cate)='3' AND cate<>'000' AND status='normal' ORDER BY sort ASC ");
while($Rows = $db->fetch())
{
	/* 링크 */
	$moveCate = (substr($Rows['mode'],0,2) == "md" || $Rows['mode'] == "") ? $Rows['cate'] : $Rows['mode'];
	echo('<div><div id="map_'.$Rows['cate'].'" class="cell" style="width:30%;">'."\n");
	echo('<div class="cube"><div class="line"><p class="center pd5 strong"><a href="'.($moveCate == 'url' ? $Rows['url'] : $cfg['droot'].'index.php?cate='.$moveCate).'" class="act">'.$Rows['name'].'</a></p></div></div>');	//2013-05-29 moveCate가 url일 경우 링크로 연결되도록

	$db->query(" SELECT cate,name FROM `site__` WHERE skin='".$cfg['skin']."' AND LENGTH(cate)>='6' AND SUBSTRING(cate,1,3)='".$Rows['cate']."' AND status='normal' ORDER BY cate ASC, sort ASC ", 2);
	$n = 1;
	$total = $db->getNumRows(2);
	echo('<ul>');

	while($sRows = $db->fetch(2))
	{
		//$sRows['name'] = $func->cutStr($sRows['name'],25,"...");
		$depth		= intval((strlen($sRows['cate'])/3)-3);
		for($i=1; $i<=$depth; $i++) { $blank .= '　'; }
		$moveCate = (substr($sRows['mode'],0,2) == "md" || $sRows['mode'] == "") ? $sRows['cate'] : $sRows['mode'];

		switch(strlen($sRows['cate']))
		{
			case '9':
				echo('<li class="depth3"><a href="'.$cfg['droot'].'index.php?cate='.$moveCate.'" title="'.$sRows['sort'].'" class="actGray">'.$sRows['name'].'</a></li>');
				break;
			case '12': case '15':
				echo('<li class="depth3">'.$blank.'<a href="'.$cfg['droot'].'index.php?cate='.$moveCate.'" title="'.$sRows['sort'].'" class="actGray">'.$sRows['name'].'</a></li>');
				break;
			default:
				echo('<li class="depth2"><a href="'.$cfg['droot'].'index.php?cate='.$moveCate.'" title="'.$sRows['sort'].'">'.$sRows['name'].'</a></li>');
				break;
		}
		$n++;
		unset($blank);
	}
    echo('<ul>');
  echo('</div>');
  if($lineCut % 3 == 2) { echo('<div class="clear"></div></div>'); } else { echo('</div>'); }
  $lineCut++;
}
?>
	<div class="clear"></div>
</div>
