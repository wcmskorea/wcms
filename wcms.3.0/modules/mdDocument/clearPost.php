<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
@ob_end_clean();

//리퍼러 체크
$func->checkRefer("POST");

//파라메터 검사
if(count($_POST['select']) < 1) { $func->err($lang['doc']['notmust'], "window.history.back()"); }
if(!$_POST['cate']) { $func->err($lang['doc']['notmust'], "window.history.back()"); }

$trash = 0;		//휴지통으로 이동건수
foreach($_POST['select'] AS $key=>$value) 
{
	// 이전 상태값 조회(2013-08-07)
	$oldContent = $db->queryFetch(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE seq='".$value."'");

	$db->query(" UPDATE `".$cfg['cate']['mode']."__content".$prefix."` SET idxTrash=idx, idx='0' WHERE seq='".$value."' ");
	//휴지통 문서는 영구삭제 시킨다
	if($db->getAffectedRows() > 0) 
	{
		/**
		 * 네이버 신디케이션 처리
		*/
		if($func->checkModule("mdSyndication") && $cfg['site']['naverSyndiYN'] == 'Y')
		{
			if($oldContent['useSecret'] == 'N')
				$syndi->documentDelete($oldContent['cate'], $value, $oldContent['subject']);
		}

		$trash++;
	}
}

if($trash > 0)
{
	//문서갯수 업데이트
	$func->checkCount("trashed", $trash, $cfg['module']['cate']);
	$func->setLog(__FILE__, "게시글 (".$cfg['module']['cate']."-".$trash."건)일괄 휴지통으로 이동 성공");
}

//알림창
$func->err($lang['doc']['process'], $_SERVER['PHP_SELF']."?".__PARM__."&type=list");

?>
<script type="text/javascript">
//<![CDATA[
	document.onload = location.replace('<?php echo($_SERVER['PHP_SELF']);?>?<?php echo(__PARM__);?>&type=list&currentPage=<?php echo($currentPage);?>');
//]]>
</script>
