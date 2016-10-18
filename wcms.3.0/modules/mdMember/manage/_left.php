<?php
/* 환경설정 파일 */
require_once "../../../_config.php";
/* 공통헤더 파일 */
require_once __PATH__."/_Admin/include/commonHeader.php";
?>
<ul>
	<?php if(preg_match('/mdMember/', $_SESSION['udepartment']) || $_SESSION['ulevel'] < $cfg['operator']) { ?>
    <li class="menu"><strong>ㆍ<a href="javascript:;" onclick="$.insert('#module', '../modules/mdMember/manage/_controll.php?type=config',null,300)">환경설정</a></strong></li>
	<li class="sect"></li>
	<?php
	}
	$query = " SELECT * FROM `mdMember__level` WHERE level BETWEEN 2 AND 98 ORDER BY level ASC ";
	$db->query($query);
	while($Rows=$db->fetch())
	{
		if($Rows['position'])
		{
			$count = $db->queryFetchOne(" SELECT COUNT(*) FROM `mdMember__account` WHERE level='".$Rows['level']."' ", 2);
			echo('<li class="menu">ㆍ<a href="javascript:;" onclick="$.insert(\'#module\', \'../modules/mdMember/manage/_controll.php?lev='.$Rows['level'].'&amp;type=memList\',null,300)">'.$Rows['position'].' :<span class="info small_gray">'.number_format($count).'</span></a></li>');
			$total += $count;
		}
	}
	?> 
	<li class="sect"></li>
	<li class="menu">ㆍ<a href="javascript:;" onclick="$.insert('#module', '../modules/mdMember/manage/_controll.php?type=memList',null,300)">전체회원
	:<span class="info small_gray red"><?php echo(number_format($total));?></span></a></li>
	<li class="menu">ㆍ<a href="javascript:;" onclick="$.insert('#module', '../modules/mdMember/manage/_controll.php?lev=ex&amp;type=memList',null,300)"
		class="act">탈퇴회원</a></li>
	<?php if(preg_match('/mdMember/', $_SESSION['udepartment']) || $_SESSION['ulevel'] < $cfg['operator']) { ?>
	<li class="sect"></li>
	<li class="menu"><strong>ㆍ<a href="javascript:;" onclick="$.dialog('../modules/mdMember/manage/_controll.php?type=insert',null,1000,500)">신규 등록</a></strong></li>
	<li class="sect"></li>
	<li class="menu">ㆍ<a href="javascript:;" onclick="$.insert('#module','../modules/mdMember/manage/_controll.php?type=analySex',null,300)">성별 가입자 통계</a></li>
	<li class="menu">ㆍ<a href="javascript:;" onclick="$.insert('#module','../modules/mdMember/manage/_controll.php?type=analyAge',null,300)">연령 가입자 통계</a></li>
	<li class="menu">ㆍ<a href="javascript:;" onclick="$.insert('#module','../modules/mdMember/manage/_controll.php?type=analyRegion',null,300)">지역 가입자 통계</a></li>
	<?php
	}
	if(is_file('./manual.pdf')) { echo('<li class="sect"></li><li class="menu"><a href="'.$cfg['droot'].'modules/mdMember/manage/manual.pdf"><img src="'.$cfg['droot'].'image/button/btn_manual_module.jpg" width="162" height="21" title="매뉴얼 다운받기" /></a></li>'); }
	?>
</ul>
<?php
/* 공통 스크립트 */
require_once __PATH__."_Admin/include/commonScript.php";
?>