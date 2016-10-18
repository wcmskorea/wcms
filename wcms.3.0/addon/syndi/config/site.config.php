<?php

// 도메인
$GLOBALS['syndi_tag_domain'] = __HOST__;//'www.mydomain.com';

// 홈페이지 제목
$GLOBALS['syndi_homepage_title'] = $GLOBALS['cfg']['site']['siteName'];//$cfg['site']['title'];//'mydomain 홈페이지';

// 타임존
$GLOBALS['syndi_time_zone'] = $GLOBALS['cfg']['site']['naverSyndiTimeZone'];//'+09:00';

// 도메인 연결 날짜(년도)
$GLOBALS['syndi_tag_year'] = $GLOBALS['cfg']['site']['naverSyndiTagYear'];//'2010';

// Syndication 출력 url
$GLOBALS['syndi_echo_url'] = 'http://' . $GLOBALS['syndi_tag_domain'] . '/addon/syndi/syndi_echo.php';



// 데이타 인코딩
$GLOBALS['syndi_from_encoding'] = $cfg['charset'];//'utf-8';

?>