<?php

// ������
$GLOBALS['syndi_tag_domain'] = __HOST__;//'www.mydomain.com';

// Ȩ������ ����
$GLOBALS['syndi_homepage_title'] = $GLOBALS['cfg']['site']['siteName'];//$cfg['site']['title'];//'mydomain Ȩ������';

// Ÿ����
$GLOBALS['syndi_time_zone'] = $GLOBALS['cfg']['site']['naverSyndiTimeZone'];//'+09:00';

// ������ ���� ��¥(�⵵)
$GLOBALS['syndi_tag_year'] = $GLOBALS['cfg']['site']['naverSyndiTagYear'];//'2010';

// Syndication ��� url
$GLOBALS['syndi_echo_url'] = 'http://' . $GLOBALS['syndi_tag_domain'] . '/addon/syndi/syndi_echo.php';



// ����Ÿ ���ڵ�
$GLOBALS['syndi_from_encoding'] = $cfg['charset'];//'utf-8';

?>