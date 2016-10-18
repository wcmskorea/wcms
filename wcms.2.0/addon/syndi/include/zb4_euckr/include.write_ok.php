<?php
/**
 * @file include.write_ok.php
 * @author sol (ngleader@gmail.com)
 * @brief �� ���/������ Syndication Ping(zb4_euckr ��)
 *        zb4  write_ok.php ���Ͽ� MySQL Connecntion close �ϱ��� ����
 *        include './syndi/include/zb4_euckr/include.write_ok.php';
 */

if(!$no || !$id) return;

$ping_type = null;

// ������
if($mode=="modify"&&$no)
{
	// ��б� ->
	if($s_data['is_secret'])
	{
		// ��б� -> ������
		if(!$is_secret) $ping_type = 'write';
	}
	else
	{
		// ������ -> ��б�
		if($is_secret) $ping_type = 'delete';
		else $ping_type = 'modify';
	}
}
else if($mode=="reply"&&$no)
{
	// ������
	if(!$is_secret) $ping_type = 'write';
}
else if($mode=="write")
{
	// ������
	if(!$is_secret) $ping_type = 'write';
}


if($ping_type && in_array($ping_type, array('write','modify','delete')))
{
	$syndi_dir = realpath(dirname(__FILE__) .'/../../');

	// include config & Syndication Ping class
	include $syndi_dir . '/config/site.config.php';
	include $syndi_dir . '/libs/SyndicationHandler.class.php';
	include $syndi_dir . '/libs/SyndicationPing.class.php';

	$oPing = new SyndicationPing;
	$oPing->setId(SyndicationHandler::getTag('channel', $id));

	if($ping_type=='delete')
	{
		$oPing->setType('deleted');

		$_link = './view.php?id=%s&no=%s';
		$_sql = "insert into syndi_delete_content_log(content_id, bbs_id, title, link_alternative, delete_date) values('%s','%s','%s','%s','%s')";
		mysql_query(sprintf($_sql, $no, $id, $s_data['subject'], sprintf($_link, $id, $no), date('YmdHis')));
	}
	else
	{
		$_sql = "delete from syndi_delete_content_log where content_id='%s' and bbs_id='%s'";
		mysql_query(sprintf($_sql, $no, $id));
	}
		
	$oPing->request();
}

?>