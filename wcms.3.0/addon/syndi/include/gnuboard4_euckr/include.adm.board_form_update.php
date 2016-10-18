<?php
/**
 * @file include.adm.board_form_update.php
 * @author sol (ngleader@gmail.com)
 * @brief �Խ��� ������ Syndication Ping
 *        gnuboard4/adm/board_form_update.php ���Ͽ� �߰�
 *        include '../syndi/include/gnuboard4_euckr/include.adm.board_form_update.php';
 */
if(!defined("_GNUBOARD_")) return;

$syndi_dir = realpath(dirname(__FILE__) .'/../../');

// include config & Syndication Ping class
include_once $syndi_dir . '/config/site.config.php';
include_once $syndi_dir . '/libs/SyndicationHandler.class.php';
include_once $syndi_dir . '/libs/SyndicationPing.class.php';

$oPing = new SyndicationPing;
$oPing->setId(SyndicationHandler::getTag('site'));
$oPing->setType('channel');

$oPing->request();
?>