<?php
/**
 * Web Contents Management System
 * Author : Sung-Jun, Lee (http://www.wcmskorea.com)
 * @Certification Keywords : manage,admin,login
 */

if(defined(__FILE__)) { return; }
define(__FILE__, true);
if(realpath($_SERVER["SCRIPT_FILENAME"]) == realpath(__FILE__)) { header("HTTP/1.0 404 Not found"); die(); }

/**
 * 상수선언
 */
define('__CSS__', true);
define('__LOGS__', true);
if(preg_match('/iphone|ipod|ipad|android|blackberry|windows ce|nokia|webos|opera mini|sonyericsson|opera mobi|iemobile/i', $_SERVER['HTTP_USER_AGENT'])) {
    define('__MOBILE__', true);
}

/**
 * 사이트 HostName 상수
 */
$host = explode(":", $_SERVER['HTTP_HOST']);
define('__HOST__', str_replace("www.", "", str_replace("http://", "", strtolower($host['0']))));

/**
 * Database Name 상수
 * HostName을 기본사용
 * define(__DB__, "DB명");
 */
define('__DB__', str_replace(".", "", __HOST__));

/**
 * 최상위 Path 상수
 */
define('__PATH__', str_replace('_config.php', '', str_replace('\\', '/', __FILE__)));

/**
 * PHP설정
 */
ini_set("display_errors", 1);
ini_set("upload_max_filesize", "100M");
ini_set("post_max_size", "200M");
/* ini_set('session.cookie_domain', '.'.__HOST__); */
/* ini_set("session.gc_maxlifetime", "10"); */
ini_set("max_execution_time", "120");
ini_set("error_reporting", "E_ALL ^ E_NOTICE");

/* 필수 파라메터값 확인 */
$currentPage = (!isset($_GET['currentPage']) && !isset($_POST['currentPage'])) ? 1 : trim($_GET['currentPage']).trim($_POST['currentPage']);

/**
 * 필수 파라메터값 및 상수선언
 */
if($_GET || $_POST && !preg_match("/set.php/i", $_SERVER["PHP_SELF"]))
{
    /* 필수 파라메터값 XSS 공격방지 */
    if($_GET['cate']        && !preg_match("/^([0-9]+)$/i", $_GET['cate']))         { header("HTTP/1.0 500 Not found"); die(); }
		if($_GET['skin']        && !preg_match("/^([a-z]+)$/i", $_GET['skin']))         { header("HTTP/1.0 500 Not found"); die(); }
    if($_GET['currentPage'] && !preg_match("/^([0-9]+)$/i", $_GET['currentPage']))  { header("HTTP/1.0 500 Not found"); die(); }
    if($_GET['year']        && !preg_match("/^([0-9]+)$/i", $_GET['year']))         { header("HTTP/1.0 500 Not found"); die(); }
    if($_GET['month']       && !preg_match("/^([0-9]+)$/i", $_GET['month']))        { header("HTTP/1.0 500 Not found"); die(); }
    if($_GET['day']         && !preg_match("/^([0-9]+)$/i", $_GET['day']))          { header("HTTP/1.0 500 Not found"); die(); }
    if($_GET['week']        && !preg_match("/^([0-9]+)$/i", $_GET['week']))         { header("HTTP/1.0 500 Not found"); die(); }
    if($_GET['days']        && !preg_match("/^-?([0-9]+)$/i", $_GET['days']))       { header("HTTP/1.0 500 Not found"); die(); }
    if($_GET['sh']          && !preg_match("/^([a-z0-9\_\-]+)$/i", $_GET['sh']))    { header("HTTP/1.0 500 Not found"); die(); }
    if($_GET['mode']        && !preg_match("/^([a-z]+)$/i", $_GET['mode']))         { header("HTTP/1.0 500 Not found"); die(); }

    /* 상수선언 */
    if($_GET['cate'] || $_POST['cate'])
    {
        define('__CATE__',      trim($_GET['cate']).trim($_POST['cate']));                                          /* 카테고리 코드 */
        define('__PARENT__',    substr(__CATE__, 0, 3));                                                            /* 카테고리 최상위 코드 */
        define('__FATHER__',    (strlen(__CATE__) > 3) ? substr(__CATE__, 0, (strlen(__CATE__) -3)) : __CATE__);    /* 카테고리 1단계 상위 코드 */
        define('__PARM__',      "cate=".__CATE__."&amp;currentPage=".$currentPage."&amp;menu=".trim($_GET['menu']).trim($_POST['menu'])."&amp;sub=".trim($_GET['sub']).trim($_POST['sub'])."&amp;sh=".trim($_GET['sh']).trim($_POST['sh'])."&amp;shc=".trim(urlencode($_GET['shc'])).trim(urlencode($_POST['shc']))."&amp;mode=".trim(urlencode($_GET['mode'])).trim(urlencode($_POST['mode'])));
    }
    else
    {
        define('__CATE__',        '');       /* 카테고리 코드 */
        define('__PARENT__',      '');       /* 카테고리 최상위 코드 */
        define('__FATHER__',      '');       /* 카테고리 1단계 상위 코드 */
        define('__PARM__',        '');       /* 공통 파라메터 */
    }

    /* URI값 상수 */
    if($_SERVER['HTTPS'] == 'on')
    {
        define('__URI__', "http://".$_SERVER['HTTP_HOST'].trim($_GET['uri']).trim($_POST['uri']));
    }
    else
    {
        /* uri 값이 없으면 메인페이지로 지정한다. */
        $uri = trim($_GET['uri']).trim($_POST['uri']);
        if($uri != ""){
            define('__URI__', trim($_GET['uri']).trim($_POST['uri']));
        }else{
            define('__URI__', '/');
        }
    }
} else {
    define('__CATE__', '');
    define('__PARENT__', '');
    define('__FATHER__', '');
    define('__PARM__', '');
    define('__URI__', '');
}

/**
 * Public Class Include
 */
require_once    __PATH__."_Lib/classDb.php";                /* 데이터베이스 연결 및 쿼리 */
require_once    __PATH__."_Lib/classFunctions.php";         /* 기본 함수 */
require_once    __PATH__."_Lib/classDisplay.php";           /* 디자인&프로토타입 */
require_once    __PATH__."_Lib/classSession.php";           /* 세션정보 */
require_once    __PATH__."_Lib/classMember.php";            /* 회원정보 */
require_once    __PATH__."_Lib/classCategory.php";          /* 카테고리 */
require_once    __PATH__."_Lib/classSock.php";              /* System Socket */
require_once    __PATH__."_Lib/classPaging.php";            /* 페이징 */
require_once    __PATH__."_Lib/classForm.php";              /* FormMaker */

/**
 * 배열 데이터 (환경변수 설정)
 */
unset($cfg);
$cfg                = array();
$cfg['skin']        = (preg_match('/\/mobile\//i', $_SERVER['REQUEST_URI']) && $_COOKIE['PAGE'] != 'pcver') ? 'mobile' : 'default';
$cfg['skin']        = (preg_match('/\/english\//i', $_SERVER['REQUEST_URI'])) ? 'english' : $cfg['skin'];
$cfg['skin']        = (preg_match('/\/chinese\//i', $_SERVER['REQUEST_URI'])) ? 'chinese' : $cfg['skin'];
$cfg['timeip']      = date("Y-m-d H:i:s")."|".$_SERVER['REMOTE_ADDR']."|".$cfg['skin'];
$cfg                = array_merge($cfg, (array)parse_ini_file("_config.ini.php", true));
$cfg                = @array_merge($cfg, (array)parse_ini_file(__PATH__."_Site/".__DB__."/".$cfg['skin']."/cache/config.ini.php", true));
$cfg['site']['ssl'] = ($cfg['site']['ssl']) ? $cfg['site']['ssl'] : '443';
/**
 * 헤더 설정
 */
header("Pragma: no-cache");
header("Cache-control: no-store, no-cache, must-revalidate");
header("Content-Type: text/html; charset=".$cfg['charset']);
/* 타 사이트간 쿠키 공유 */
/* header("P3P : CP=\"ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC\""); */
header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');

/**
 * 모바일 페이지로 이동
 */
if(preg_match('/iphone|ipod|ipad|android|blackberry|windows ce|nokia|webos|opera mini|sonyericsson|opera mobi|iemobile/i', $_SERVER['HTTP_USER_AGENT']) && !preg_match('/'.$_SERVER['HTTP_HOST'].'/',$_SERVER['HTTP_REFERER']) && !preg_match('/mobile/i', $_SERVER['PHP_SELF']) && !preg_match('/_Admin/i', $_SERVER['PHP_SELF']) && $cfg['site']['mobileweb'])
{
    header("Location: http://".$_SERVER['HTTP_HOST']."/sites/mobile/");
    exit;
}

/**
 * 데이터베이스 연결
 */
$db = new DbMySQL();
$db->connect();
//if(!isset(__DB__)) { define('__DB__', $cfg['db']['dbName']);

if(!$db->selectDB(__DB__) && !preg_match("/set.php/i", $_SERVER["PHP_SELF"]))
{
    /* DB선택 : DB가 없다면 셋업 페이지로 이동 */
    Header("Location: ".$cfg['droot']."set.php");
    die('신규 사이트 등록페이지로 이동합니다');
}
else if(!$db->selectDB(__DB__) && is_dir(__PATH__."_Site/".__DB__))
{
    /* DB가 존재하지 않고 HOME디렉토리가 존재한다면 재설치 */
    die('HOME 디렉토리가 존재합니다. 해당 폴더를 삭제하고 재설치 하십시오.');
}
else if($db->selectDB(__DB__) && !is_dir(__PATH__."_Site/".__DB__))
{
    /* DB만 존재하고 HOME디렉토리가 없다면 재설치 */
    die('HOME 디렉토리가 존재하지 않습니다. 백업한 파일로 복원하거나 DB를 삭제하고 재설치 하십시오.');
}
else if($db->selectDB(__DB__) && $_SERVER['PHP_SELF'] == '/set.php')
{
    /* DB선택 : DB존재하고 셋업 페이지라면 메인 페이지로 이동 */
    Header("Location: ".$cfg['droot']);
    die('메인 페이지로 이동합니다');
}

/**
 * 세션
 */
$sess = new Sess();

ini_set("session.cookie_lifetime", 0); // 초
ini_set("session.cache_expire", $cfg['site']['sessionTime']); // 분
ini_set("session.gc_maxlifetime", $cfg['site']['sessionTime'] * 60); // 초

$cfg['site']['sessionType']= $cfg['site']['sessionType'] =='' ? 'sessionfile' : $cfg['site']['sessionType'];

// 세션 DB 연동
if($cfg['site']['sessionType']=='sessiondb')
{
	session_set_save_handler(array($sess, "sessionDbOpen"),array($sess, "sessionDbClose"),array($sess, "sessionDbRead"),array($sess, "sessionDbWrite"),array($sess, "sessionDbDestroy"),array($sess, "sessionDbClean"));
	session_start();
}
else if($cfg['site']['sessionType']=='sessionfile')
{
	$sess_save_path = __PATH__."_Site/".__DB__."/default/data/session";
	if(!is_dir($sess_save_path)) { mkdir($sess_save_path, 0707); }
	ini_set('session.save_path',$sess_save_path);

	session_set_save_handler(array($sess, "sessionFileOpen"),array($sess, "sessionFileClose"),array($sess, "sessionFileRead"),array($sess, "sessionFileWrite"),array($sess, "sessionFileDestroy"),array($sess, "sessionFileClean"));
	session_start();
}

/**
 * 시스템 관리툴 접근제한
 */
if((preg_match("/manage/i", $_SERVER['REQUEST_URI'])) && ($_SESSION['ulevel'] > $cfg['operator'] || !$_SESSION['ulevel']) && !preg_match("/login/i", $_SERVER['REQUEST_URI']))
{
    $func->err("세션이 종료되었습니다. 로그인 페이지로 이동합니다.", "document.location.replace('/_Admin/login.php');");
	die();
}

/**
 * 한글 도메인 REFERER 버그 해결을 위한 퓨니코드변환
 */
if(preg_match("/[가-힣]+\.[a-zA-Z]/", $_SERVER['HTTP_REFERER']))
{
    $referer = explode("/", $_SERVER['HTTP_REFERER']);
    $_SERVER['HTTP_REFERER'] = str_replace($referer['2'], $_SERVER['HTTP_HOST'], $referer['2']);
}

/**
 * 카테고리별 Contents 정보
 */
$cfg['cate']    = (__CATE__ && __CATE__ != "__CATE__") ? $db->queryFetch(" SELECT * FROM `site__` WHERE skin='".$cfg['skin']."' AND cate='".__CATE__."' ") : null;
$cfg['modules'] = explode(",", $cfg['site']['modules']);

/**
 * 스킨 폴더 - 셋업된 사이트는 생성된 디렉토리로 설정
 */
if(preg_match("/^set.php/i", $_SERVER["PHP_SELF"]))
{
    define('__SKIN__', $cfg['droot'].'_Site/'.$cfg['default'].'/'.$cfg['skin'].'/');    /* 절대경로 */
    define('__HOME__', __PATH__.'_Site/'.$cfg['default'].'/'.$cfg['skin'].'/');         /* 상대경로 */
}
else
{
    define('__SKIN__', $cfg['droot']."user/".$cfg['skin']."/");             /* 절대경로 */
    define('__HOME__', __PATH__."_Site/".__DB__."/".$cfg['skin']."/");      /* 상대경로 */
    define('__DATA__', $cfg['droot']."user/".$cfg['skin']."/data/");        /* 데이터 저장위치 상대경로 */
}

/**
 * 파일 업로드 디렉토리 설정
 */
$cfg['upload']['dir'] = __HOME__."data/";

/**
 * 언어변경시 쿠키설정 및 언어설정
 */
if(isset($_GET['lang']))
{
    setcookie ("language", $_GET['lang'], 0);
    ($_GET['uri']) ? header("Location: ".$_GET['uri']) : header("Location: ".$cfg['droot']."index.php");
    die('언어셋을 재설정 합니다');
}

/**
 * 언어설정에 따른 배열 데이터
 */
if(!preg_match("/set.php/i", $_SERVER["PHP_SELF"])) { $lang = (array)parse_ini_file(__HOME__."cache/lang.ini.php", true); }

/**
 * Class 선언
 */
$display    = new Display();
$func       = new Functions("site__");
$category   = new Category(__CATE__, $cfg['site']['lang']);
$member     = new Member($cfg['cate']['perm'], $cfg['cate']['permLmt']);
$sock       = new Syssock($cfg['site']['id'], $cfg['site']['authCode'], $cfg['site']['phone']); /* 10억홈피서버 소켓통신 : 아이디/인증코드/대표전화 */


/* Tracking Update */
if($cfg['site']['tracking'] && !defined('__CATE__')) { $sess->tracking("main"); }

/* 환경변수 확인 */
//$func->showArray($cfg['cate']);
?>
