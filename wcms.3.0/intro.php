<?php
/**
 * Config
 */
require_once "_config.php";

/**
 * 로그분석 모듈
 */
if($func->checkModule('mdAnalytic'))
{
	$sess->counting('count', __HOST__);				//방문자 카운트
	$sess->countReferer($_SERVER['HTTP_REFERER']);	//방문자 Referer
}

/**
 * 방문자 Tracking
 */
if($cfg['site']['tracking']) { $sess->tracking("intro"); }


/**
 * 시스템 점검 노출
 */
if($cfg['site']['dateCheck'] > time() && !preg_match("/\_Admin/", $_SERVER['REQUEST_URI']) && (!$_SESSION['ulevel'] || $_SESSION['ulevel'] > $cfg['operator']))
{
    /**
     * Header 출력
     */
    $display->loadHeader('intro');
    /**
     * Body 출력
     */
    $display->loadBody("notice");
    /**
     * 내용 출력
     */
    echo('<div class="msgBox"><p>보다 안정된 서비스 제공을 위해 시스템을 점검하고 있습니다.<br /><br />이용에 불편을 드려 대단히 죄송합니다!<br /><br /><span style="font-weight:normal;">문의전화 : '.$cfg['site']['phone'].'<br /><br />( 정상운영 예정시간 : '.date('Y년 m월 d일',$cfg['site']['dateCheck']).' '.date('A H시i분',$cfg['site']['dateCheck']).' )</span></p></div>');
}
else 
{
    /**
     * 인트로 페이지 LOAD
     */
    $intro = trim(stripslashes(@file_get_contents(__HOME__."cache/document/000001.html")));
    if(!$intro || defined('__CATE__'))
    {
        Header("Location: ./index.php?".$_SERVER['QUERY_STRING']);
        exit(0);
    }
    /**
     * Header 출력
     */
    $display->loadHeader('intro');

    /**
     * 본문 출력
     */
    echo(stripslashes($intro));

    /**
     * Footer 출력
     */
    $display->loadFooter();
}
$db->FreeResult();
unset($buffer);
unset($cfg);
?>
