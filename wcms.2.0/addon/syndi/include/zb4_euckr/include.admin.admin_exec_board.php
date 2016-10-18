<?php
/**
 * @file include.admin.admin_exec_board.php
 * @author sol (ngleader@gmail.com)
 * @brief 글 삭제시 Syndication Ping(zb4_euckr 용)
 *        zb4  admin/admin_exec_board.php 파일에 각각의 페이지 이동 하기전 삽입 
 *        include './syndi/include/zb4_euckr/include.delete_ok.php';
 */

if(!$name || !in_array($exec2,array("modify_ok","add_ok","del","category_move","modify_grant_ok")) return;

$syndi_dir = realpath(dirname(__FILE__) .'/../../');

// include config & Syndication Ping class
include $syndi_dir . '/config/site.config.php';
include $syndi_dir . '/libs/SyndicationHandler.class.php';
include $syndi_dir . '/libs/SyndicationPing.class.php';

$oPing = new SyndicationPing;

switch($exec2)
{
	/*
	case "modify_ok":	// 게시판 수정
	case "add_ok": 	// 게시판 추가 
		$oPing->setId(SyndicationHandler::getTag('site', $name));
		$oPing->setType('channel');
	break;
	*/

	case "del":	// 게시판 삭제
		$_sql = "delete from syndi_delete_content_log where bbs_id='%s'";
		mysql_query(sprintf($_sql, $id));

	case "modify_grant_ok":	// 권한 설정 
		$oPing->setId(SyndicationHandler::getTag('site'));
		$oPing->setType('channel');
	break;

	case "category_move":	// 카테고리 내용 이동 
		$oPing->setId(SyndicationHandler::getTag('channel', $name));
		$oPing->setType('article');
	break;
}

$oPing->request();
?>