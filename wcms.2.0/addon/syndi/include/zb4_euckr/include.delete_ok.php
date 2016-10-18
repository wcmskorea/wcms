<?php
/**
 * @file include.delete_ok.php
 * @author sol (ngleader@gmail.com)
 * @brief 글 삭제시 Syndication Ping(zb4_euckr 용)
 *        zb4  delete_ok.php 파일에 MySQL Connecntion close 하기전 삽입 
 *        include './syndi/include/zb4_euckr/include.delete_ok.php';
 */

if(!$no || !$id || $s_data['is_secret'] || $s_data['child']) return;

$syndi_dir = realpath(dirname(__FILE__) .'/../../');

// include config & Syndication Ping class
include $syndi_dir . '/config/site.config.php';
include $syndi_dir . '/libs/SyndicationHandler.class.php';
include $syndi_dir . '/libs/SyndicationPing.class.php';

$oPing = new SyndicationPing;
$oPing->setId(SyndicationHandler::getTag('channel', $id));
$oPing->setType('deleted');

$_link = './view.php?id=%s&no=%s';
$_sql = "insert into syndi_delete_content_log(content_id, bbs_id, title, link_alternative, delete_date) values('%s','%s','%s','%s','%s')";
mysql_query(sprintf($_sql, $no, $id, $s_data['subject'], sprintf($_link, $id, $no), date('YmdHis')));
	
$oPing->request();
?>