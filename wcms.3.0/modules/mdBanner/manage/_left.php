<?php
require "../../../_config.php";
require __PATH__."/_Admin/include/commonHeader.php";
?>
<ul>
<?php
$db->query(" SELECT * FROM `display__default` WHERE config like '%mdBanner%' ORDER BY sort ASC ");
if($db->getNumRows() > 0)
{
	if($n > 0) { echo('<li class="sect"></li>'); }
	while($Rows = $db->fetch())
	{
		echo('<li class="menu"><strong>ㆍ</strong><a href="javascript:$.insert(\'#module\', \'../modules/mdBanner/manage/_controll.php?type=list&amp;skin=default&amp;position='.$Rows['position'].'\',null,300)">'.$Rows['name'].'</a></li>');
	}
	$n++;
} else if($n < 1)
{
	echo('<li class="center" style="padding:10px 0px;">설정된 모듈이<br />존재하지 않습니다</li>');
}

if($cfg['site']['mobileweb'] == '1')
{
	$db->query(" SELECT * FROM `display__mobile` WHERE config like '%mdBanner%' ORDER BY sort ASC ");
	if($db->getNumRows() > 0)
	{
		if($n > 0) { echo('<li class="sect"></li>'); }
		while($Rows = $db->fetch())
		{
			echo('<li class="menu"><strong>ㆍ</strong><a href="javascript:$.insert(\'#module\', \'../modules/mdBanner/manage/_controll.php?type=list&amp;skin=mobile&amp;position='.$Rows['position'].'\',null,300)">'.$Rows['name'].'(모바일)</a></li>');
		}
		$n++;
	} else if($n < 1)
	{
		echo('<li class="center" style="padding:10px 0px;">설정된 모듈이<br />존재하지 않습니다</li>');
	}
}

if($cfg['site']['englishweb'] == '1')
{
	$db->query(" SELECT * FROM `display__english` WHERE config like '%mdBanner%' ORDER BY sort ASC ");
	if($db->getNumRows() > 0)
	{
		if($n > 0) { echo('<li class="sect"></li>'); }
		while($Rows = $db->fetch())
		{
			echo('<li class="menu"><strong>ㆍ</strong><a href="javascript:$.insert(\'#module\', \'../modules/mdBanner/manage/_controll.php?type=list&amp;skin=english&amp;position='.$Rows['position'].'\',null,300)">'.$Rows['name'].'(영문)</a></li>');
		}
		$n++;
	} else if($n < 1)
	{
		echo('<li class="center" style="padding:10px 0px;">설정된 모듈이<br />존재하지 않습니다</li>');
	}
}

if($cfg['site']['chineseweb'] == '1')
{
	$db->query(" SELECT * FROM `display__chinese` WHERE config like '%mdBanner%' ORDER BY sort ASC ");
	if($db->getNumRows() > 0)
	{
		if($n > 0) { echo('<li class="sect"></li>'); }
		while($Rows = $db->fetch())
		{
			echo('<li class="menu"><strong>ㆍ</strong><a href="javascript:$.insert(\'#module\', \'../modules/mdBanner/manage/_controll.php?type=list&amp;skin=chinese&amp;position='.$Rows['position'].'\',null,300)">'.$Rows['name'].'(중문)</a></li>');
		}
		$n++;
	} else if($n < 1)
	{
		echo('<li class="center" style="padding:10px 0px;">설정된 모듈이<br />존재하지 않습니다</li>');
	}
}

?>
</ul>
<?php
require __PATH__."/_Admin/include/commonScript.php";
?>