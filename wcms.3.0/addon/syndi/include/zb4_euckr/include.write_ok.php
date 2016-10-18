<?php
/**
 * @file include.write_ok.php
 * @author sol (ngleader@gmail.com)
 * @brief 글 등록/수정시 Syndication Ping(zb4_euckr 용)
 *        zb4  write_ok.php 파일에 MySQL Connecntion close 하기전 삽입
 *        include './syndi/include/zb4_euckr/include.write_ok.php';
 */

if(!$no || !$id) return;

$ping_type = null;

// 수정시
if($mode=="modify"&&$no)
{
	// 비밀글 ->
	if($s_data['is_secret'])
	{
		// 비밀글 -> 공개글
		if(!$is_secret) $ping_type = 'write';
	}
	else
	{
		// 공개글 -> 비밀글
		if($is_secret) $ping_type = 'delete';
		else $ping_type = 'modify';
	}
}
else if($mode=="reply"&&$no)
{
	// 공개글
	if(!$is_secret) $ping_type = 'write';
}
else if($mode=="write")
{
	// 공개글
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