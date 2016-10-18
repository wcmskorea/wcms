<?php
require_once "../../../_config.php";
require_once __PATH__."/_Admin/include/commonHeader.php";
$articleCount = $db->queryFetch(" SELECT SUM(articled) AS article, SUM(articleTrashed) AS articleTrash FROM `mdDocument__` WHERE share='' ");
?>
<ul>
	<li class="menu">ㆍ<a href="javascript:;" onclick="$.insert('#module', '../modules/mdDocument/manage/_controll.php?type=list',null,300)">등록된 문서:<span class="info colorBlue"><?php echo(number_format($articleCount['article']-$articleCount['articleTrash']));?> 건</span></a></li>
<?php
$db->query(" SELECT skin,cate,name FROM `site__`  WHERE skin='".$cfg['skin']."' AND mode='mdDocument' ORDER BY cate ASC ");
if($db->getNumRows() > 0)
{
	while($Rows = $db->fetch())
	{
		$config = $db->queryFetchOne(" SELECT config FROM `mdDocument__` WHERE cate='".$Rows['cate']."' ", 2);
		$config = unserialize($config);
		if(!($config['list'] == 'Page' || $config['list'] == '')) {
			$newCnt = $db->queryFetchOne(" SELECT count(seq) as newCnt FROM `mdDocument__content` WHERE cate='".$Rows['cate']."' and regDate + (86400*1)>= UNIX_TIMESTAMP(now()) ", 2);

			if($newCnt > 0)
				$icon = '<span class="icon"><img src="/user/default/image/icon/new.gif" alt="최근 게시물" /></span>';
			else
				$icon = '';
?>
	<li class="menu"> &nbsp;&nbsp;└ <a href="javascript:;" onclick="$.insert('#module', '../modules/mdDocument/manage/_controll.php?type=list&cated=<?php echo($Rows['cate']);?>&skind=<?php echo($Rows['skin']);?>',null,300)"><?php echo($Rows['name']);?></a><?php echo($icon); ?></li>
<?php
		}
	}
}
?>
	<li class="menu">ㆍ<a href="javascript:;" onclick="$.insert('#module', '../modules/mdDocument/manage/_controll.php?type=list&amp;sh=trash',null,300)">삭제된 문서:<span class="info colorRed"><?php echo(number_format($articleCount['articleTrash']));?> 건</span></a></li>
	<li class="sect"></li>
	<li class="menu">ㆍ<a href="javascript:;" onclick="$.insert('#module', '../modules/mdDocument/manage/_controll.php?type=file',null,300)">등록된 파일</a></li>
	<li class="sect"></li>
	<li class="menu">ㆍ<a href="javascript:;" onclick="$.insert('#module', '../modules/mdDocument/manage/_controll.php?type=comment',null,300)">등록된 댓글</a></li>
	<li class="menu">ㆍ<a href="javascript:;" onclick="$.insert('#module', '../modules/mdDocument/manage/_controll.php?type=comment&amp;sh=trash',null,300)">삭제된 댓글</a></li>
</ul>
