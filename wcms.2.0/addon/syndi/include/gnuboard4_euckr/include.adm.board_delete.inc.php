<?php
/**
 * @file include.adm.board_delete.inc.php
 * @author sol (ngleader@gmail.com)
 * @brief �Խ��� ������ Syndication Ping
 *        gnuboard4/adm/board_delete.inc.php ���Ͽ� �߰�
 *        include '../syndi/include/gnuboard4_euckr/include.adm.board_delete.inc.php';
 */
if(!defined("_GNUBOARD_")) return;

if(!$tmp_bo_table) return;

$syndi_dir = realpath(dirname(__FILE__) .'/../../');

// include config & Syndication Ping class
include_once $syndi_dir . '/config/site.config.php';
include_once $syndi_dir . '/libs/SyndicationHandler.class.php';
include_once $syndi_dir . '/libs/SyndicationPing.class.php';

$oPing = new SyndicationPing;
$oPing->setId(SyndicationHandler::getTag('site');
$oPing->setType('channel');

// delete log
$_sql = "delete from syndi_delete_content_log where  bbs_id='%s'";
sql_query(sprintf($_sql, $tmp_bo_table));
	
$oPing->request();
?>