<?
/**
 * @file sample_write_act.php
 * @author sol (ngleader@gmail.com)
 * @brief 글수정시 Syndication Ping 동작 샘플
 */

// content write action 
// do something...

/*
$content_id // 게시물 번호 
$bbs_id // 게시판 id
*/


// include Syndication Ping class
include 'libs/SyndicationHandler.class.php';
include 'libs/SyndicationPing.class.php';

$oPing = new SyndicationPing;
$id = SyndicationHandler::getTag('article', $content_id, $bbs_id);
$oPing->setId($id);
//$oPing->request();

?>
