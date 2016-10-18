<?php
/**
 * @file include.bbs.write_update.php
 * @author sol (ngleader@gmail.com)
 * @brief �� ���/������ Syndication Ping
 *        gnuboard4/bbs/write_update.php ���Ͽ� �߰�
 *        include '../syndi/include/gnuboard4_euckr/include.bbs.write_update.php';
 */
if(!defined("_GNUBOARD_")) return;

if(!$board) return;

// ��� �Խ����̸� pass
if($board['bo_use_secret'] && $secret) return;

// ��ȸ�� ����ڰ� �� �� ���ٸ� pass
if($board['bo_list_level']>1 || $board['bo_view_level']>1) return;

if($w == "u" && $wr && !$wr['wr_id']) return;


// ���� ��� �Ǵ� �ű� �Է��� id�� �ִٸ� ping�� ����
if($wr['wr_id'] || $wr_id)
{
	$syndi_dir = realpath(dirname(__FILE__) .'/../../');

	// include config & Syndication Ping class
	include $syndi_dir . '/config/site.config.php';
	include $syndi_dir . '/libs/SyndicationHandler.class.php';
	include $syndi_dir . '/libs/SyndicationPing.class.php';

	$oPing = new SyndicationPing;
	$oPing->setId(SyndicationHandler::getTag('channel', $board['bo_table']));
	$oPing->setType('article');

	// if deleted 
	$_sql = "delete from syndi_delete_content_log where content_id='%s' and bbs_id='%s'";
	sql_query(sprintf($_sql, $wr_id ? $wr_id : $wr[wr_id], $board['bo_table']));
		
	$oPing->request();
}
?>