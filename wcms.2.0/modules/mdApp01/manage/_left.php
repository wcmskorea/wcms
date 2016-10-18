<?php
require_once "../../../_config.php";
require_once __PATH__."/_Admin/include/commonHeader.php";
?>
<ul>
<?php
$n = 0;
foreach ($cfg['skinName'] AS $key=>$val)
{
	$db->query(" SELECT skin,cate,name FROM `site__`  WHERE skin='".$key."' AND mode='mdApp01' ORDER BY cate ASC ");
	if($db->getNumRows() > 0)
	{
		while($Rows = $db->fetch())
		{
			$config = $db->queryFetchOne(" SELECT config FROM `mdApp01__` WHERE cate='".$Rows['cate']."' ", 2);
			$config = unserialize($config);
			$result = ($config['resultAdmin']) ? explode(",", $config['resultAdmin']) : explode(",", $config['result']);

			if($n > 0) { echo('<li class="sect"></li>'); }

			echo('<li class="menu">&nbsp;<a href="javascript:;" onclick="$.dialog(\'./modules/index.php?type=cateMod&amp;skin='.$Rows['skin'].'&amp;cate='.$Rows['cate'].'&amp;name='.$Rows['name'].'\',null,800,450)"><strong>'.$Rows['name'].'</strong></a>('.str_replace(' 사이트', null, $val).')</li><li class="sect"></li>');

			foreach($result AS $key2=>$val2)
			{
				$count = $db->queryFetchOne(" SELECT SUM(if(state='".$key2."',1,0)) FROM `mdApp01__content` WHERE cate='".$Rows['cate']."' ", 2);

				echo('<li class="menu">ㆍ<a href="javascript:;" onclick="$.insert(\'#module\', \'../modules/mdApp01/manage/_controll.php?type=list&amp;cate='.$Rows['cate'].'&amp;state='.$key2.'\',null,300)">'.$val2.'&nbsp;:<span class="info small_gray colorBlue">'.number_format($count).'건</span></a></li>');
			}
			$n++;
		}
	}
	else if($n < 1)
	{
		echo('<li class="center" style="padding:10px 0px;">설정된 모듈이<br />존재하지 않습니다</li>');
		break;
	}
}
?>
</ul>