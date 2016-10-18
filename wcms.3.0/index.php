<?php
/**
 * Configration
 */
require_once "./_config.php";

/**
 * 로그분석 모듈
 */
if($func->checkModule('mdAnalytic'))
{
	$sess->counting('count', __HOST__);				//방문자 카운트
	$sess->countReferer($_SERVER['HTTP_REFERER']);	//방문자 Referer
}

/**
 * 카테고리별 모듈 설
 */
if(__CATE__)
{
	/**
	 * 방문자 Tracking
	 */
	if($cfg['site']['tracking']) { $sess->tracking(__CATE__); }

	//서브페이지 FULL설정에 다른 사이즈 조절
	if($cfg['cate']['useFull'] == 'Y')
	{
		$cfg['site']['sizeSsnb'] = 0;
		$cfg['site']['sizeSside'] = 0;
	}

	//서브에서 선택된 카테고리 코드가 없을경우 해당 순서를 불러온다.
	if(!$menu && $cfg['cate']['status'] == 'normal')
	{
		$menu = $db->queryFetchOne(" SELECT `sort` FROM `site__` WHERE skin='".$cfg['skin']."' AND cate='".__PARENT__."' ");
		$menu = ($menu < 1) ? 1 : $menu;
		if(strlen(__CATE__) > 3 ) $sub = $db->queryFetchOne(" SELECT `sort` FROM `site__` WHERE skin='".$cfg['skin']."' AND cate='".__CATE__."' ");
	}

	//카테고리 링크 연결일경우
	if($cfg['cate']['mode'] == 'url') { Header("Location: ".$cfg['cate']['url']); }

	if($cfg['cate']['cate'])
	{
		//<title>지정
		$display->title = $cfg['cate']['name'];

		//카테고리 조회수 업데이트
		if(($_SESSION['ulevel'] && $_SESSION['ulevel'] > $cfg['operator']) || (!$_SESSION['ulevel'] && !preg_match($cfg['operatorip'], $_SERVER['REMOTE_ADDR'])))
		{
			$db->query(" UPDATE `site__` SET hit=hit+1 WHERE skin='".$cfg['skin']."' AND cate='".__CATE__."' ");
		}

		//권한체크
		if(!$member->checkPerm('0') && $cfg['cate']['status'] == 'hide') { $func->err("본 페이지는 비공개 페이지입니다." ,"window.history.back();"); }
		if(!$member->checkPerm('1'))
		{
			if($func->checkModule('mdMember'))
			{
				if($member->permLimit[1] == 'A')
				{
					$func->err("죄송합니다! 본 페이지는 성인인증을 통하여 이용하실 수 있습니다." ,"window.location.replace('./?cate=000002002&amp;cated=".__CATE__."');");
				} else {
					$func->err("죄송합니다! 본 페이지는 ".$member->memberPosition($member->perm['1'])."에 한하여 이용하실 수 있습니다." ,"window.location.replace('".$cfg['droot']."');");
				}
			}
			else
			{
				$func->err("죄송합니다! 본 페이지는 회원에 한하여 이용하실 수 있습니다." ,"window.history.back();");
			}
		}

		//Buffer에 저장 : start
		@ob_start();

		//컨텐츠 및 모듈
		if(!trim($cfg['cate']['mode']))
		{
			$func->err("{".__CATE__."} 존재하지 않는 페이지 입니다." ,"window.history.back();");
		}
		else
		{
			//모듈 경우
			switch($cfg['cate']['mode'])
			{
				default :
					//모듈 환경 설정
					# 회원가입 입력폼 정보와 문의 상담 모듈은 site_form 에서 데이터를 가져온다.
					if($db->checkTable($cfg['cate']['mode']."__"))
					{
						//모듈 환경설정 취합
						$cfg['module'] = (array)$db->queryFetch(" SELECT * FROM `".$cfg['cate']['mode']."__` WHERE cate='".$cfg['cate']['cate']."' ");

						//모듈 콘텐츠 공유에 따른 카테고리코드 변경
						//콘텐츠 공유가 여러개 발생할경우 처리 [상품모듈에만 한정] 2012.01.13 강인
						$shareArray = explode(",",$cfg['module']['share']);
						if(count($shareArray) == 1)
						{
							$cfg['module']['cate'] = ($cfg['module']['share']) ? $cfg['module']['share'] : $cfg['module']['cate'];
						}

						//모듈 환경설정 취합
						$cfg['module'] = @array_merge($cfg['module'], (array)unserialize($cfg['module']['config']));
						//$func->showArray($cfg['module']);

						if($db->getNumRows() < 1 || !$cfg['module']['config'])
						{
							//모듈설정 되지 않은경우 문서페이지로 연결
							$cfg['module'] = array('list'=>'Page');
							include "./modules/mdDocument/_controll.php";
						} else
						{
							include "./modules/".$cfg['cate']['mode']."/_controll.php";
						}
					}
					else
					{
						$func->err("{".$cfg['solution'][$cfg['cate']['mode']]."} 모듈이 설치되지 않았습니다." ,"back");
					}
				break;

				//사이트맵일때
				case "mdSitemap" :
					include "./addon/document/siteMap.php";
				break;

				//약도일때
				case "mdMap" :
					include "./addon/document/map.php";
				break;
			}
		}
		//Buffer에 저장 : end
		$buffer = ob_get_contents();
		@ob_end_clean();

	}
	else
	{
		$func->err("{".__CATE__."} 존재하지 않는 페이지 입니다." ,"back");
	}
} else
{
	if($cfg['site']['tracking']) { $sess->tracking(); }
}
/* 모듈 설정 END */

/**
 * 페이지 출력 : Start
 */
if($_POST['mode'] == 'dialog' || $_GET['mode'] == 'dialog')
{
	//Ajax와 연동된 페이지는 Buffer만 출력
	echo('<!--contents start-->'.$buffer.'<!--contents end-->');
}
else
{
	//Header 출력
	$display->loadHeader();

	//시스템 점검노출
	if($cfg['site']['dateCheck'] > time() && !preg_match("/\_Admin/", $_SERVER['REQUEST_URI']) && (!$_SESSION['ulevel'] || $_SESSION['ulevel'] > $cfg['operator']))
	{
		//Body 출력
		$display->loadBody("notice");

		//내용출력
		echo('<div class="msgBox"><p>보다 안정된 서비스 제공을 위해 시스템을 점검하고 있습니다.<br /><br />이용에 불편을 드려 대단히 죄송합니다!<br /><br /><span style="font-weight:normal;">문의전화 : '.$cfg['site']['phone'].'<br /><br />( 정상운영 예정시간 : '.date('Y년 m월 d일',$cfg['site']['dateCheck']).' '.date('A H시i분',$cfg['site']['dateCheck']).' )</span></p></div>');
	}
	else
	{
		//Body 출력
		if(isset($_GET['mode']) == 'content') { $display->mode = "content"; }
		$display->loadBody();


		//Layout 출력
		if(__CATE__ && ($cfg['cate']['status'] == '_dep' || $_GET['print'] == 'y' || $_GET['mode'] == 'content'))
		{
			echo($display->printTitle("sub", $cfg['cate']['name'], __CATE__));
			echo('<div id="module" class="module"><!--contents start-->'.$buffer.'<!--contents end--></div>');
			//프린트일 경우 레이아웃 제외하고 Buffer만 출력
			if(isset($_GET['print'])) { echo('<script type="text/javascript">window.onload=function(){if(confirm(\'현재 페이지를 프린트 하시겠습니까?\')==true) window.print();}</script>'); }
		}
		else
		{
			include (__CATE__) ? __HOME__."pageSub.php" : __HOME__."pageMain.php";
		}

		//POP-UP module
		if($func->checkModule('mdPopup'))
		{
			include "./modules/mdPopup/widgets/popup.php";
		}
	}

	//Footer 출력
	$display->loadFooter();
}

//$func->showArray($_COOKIE);

/**
 * 페이지 출력 : End
 */
$db->freeResult();
?>
