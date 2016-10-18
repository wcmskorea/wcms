<?php
if(!defined('__CSS__')) header("HTTP/1.0 404 Not found");
$type = ($_GET['type']) ? $_GET['type']	: $_POST['type'];

switch($type)
{
	#---- 목록
	case "list" :
		include __PATH__."modules/".$cfg['cate']['mode']."/inputList.php";
		break;
		#---- 열람
	case "view" :
		include __PATH__."modules/".$cfg['cate']['mode']."/view.php";
		break;
		#---- 검색
	case "search" :
		include  __PATH__."modules/".$cfg['cate']['mode']."/search.php";
		break;
	default :
		/* ----------------------------------------------------------------------------------
		 | 인코딩 타입 : Star
		 */
		switch($sess->decode($type))
		{
			#---- 작성하기
			case "input" :
				include __PATH__."modules/".$cfg['cate']['mode']."/input.php";
				break;
				#---- 입력데이터 전송
			case "inputPost" :
				include __PATH__."modules/".$cfg['cate']['mode']."/inputPost.php";
				break;
			case "inputSearchPost" :
				include __PATH__."modules/".$cfg['cate']['mode']."/input".$cfg['module']['listing'].".php";
				break;
//			Flex 파일업로드
			case "upLoadFile" :

				if($_FILES)
				{
					require_once __PATH__."_Lib/classUpLoad.php";
					$up = new upLoad($cfg['upload']['dir'], $_FILES);
					$up->upFiles($cfg['module']['cate']."_".$_POST['RenameFile']);
				}
				else
				{
					header("HTTP/1.0 500 Not found");
				}
				break;

				#---- 수정입력
			case "modify"	:
				include __PATH__."modules/".$cfg['cate']['mode']."/modify.php";
				break;
				#---- 수정 데이터 전송
			case "modifyPost" :
				include __PATH__."modules/".$cfg['cate']['mode']."/modifyPost.php";
				break;
				#---- 게시물&댓글 수정,삭제,비밀글 열람
			case "articleView" : case "articleModify" : case "articleDel" : case "articleDelAccess" :
				include __PATH__."modules/".$cfg['cate']['mode']."/process.php";
				break;
				#---- null일때
			default :
				include __PATH__."modules/".$cfg['cate']['mode']."/input".$cfg['module']['listing'].".php";
				break;
		}
		/* ----------------------------------------------------------------------------------
		 | 인코딩 타입 : End
		 */
		break;
}
?>
