<?php
/**
 * @file include.bbs.delete.php
 * @author sol (ngleader@gmail.com)
 * @brief �� ������ Syndication Ping
 *        gnuboard4/bbs/bbs.delete.php ���Ͽ� �߰�
 *        include '../syndi/include/gnuboard4_euckr/include.bbs.delete.php';
 */
if(!defined("_GNUBOARD_")) return;

if(!$write || !$row) return;

// ��ȸ�� access�� �Ұ��� �� �Խ����̸� pass
$sql = "select count(*) as cnt from " . $g4['board_table'] . " b, ". $g4['group_table'] . " g where b.bo_table='". $bo_table ."' and b.bo_read_level=1 and b.bo_list_level=1 and g.gr_use_access=0 and g.gr_id = b.gr_id";
$cnt_row = sql_fetch($sql);
if($cnt_row['cnt']<1) return;

$syndi_dir = realpath(dirname(__FILE__) .'/../../');

// include config & Syndication Ping class
include_once $syndi_dir . '/config/site.config.php';
include_once $syndi_dir . '/libs/SyndicationHandler.class.php';
include_once $syndi_dir . '/libs/SyndicationPing.class.php';


$sql = "select wr_subject from $write_table where wr_id='" .$row['wr_id'] ."'";
$subject_row = sql_fetch($sql);

$_link = './bbs/board.php?bo_table=%s&wr_id=%s';
$_sql = "insert into g4_syndi_delete_content_log(content_id, bbs_id, title, link_alternative, delete_date) values('%s','%s','%s','%s','%s')";
sql_query(sprintf($_sql, $row['wr_id'], $bo_table, $subject_row['wr_subject'], sprintf($_link, $bo_table, $row['wr_id']), date('YmdHis')));

$oPing = new SyndicationPing;
$oPing->setId(SyndicationHandler::getTag('channel', $bo_table));
$oPing->setType('deleted');
$oPing->request();
?>