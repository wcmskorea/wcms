<?php

/**
 * @brief 사이트 정보
 **/
function Syndi_getSiteInfo($args)
{
	$title = $GLOBALS['syndi_homepage_title'];
	$tag = SyndicationHandler::getTag('site');

	$oSite = new SyndicationSite;
	$oSite->setId($tag);
	$oSite->setTitle($title);
	$oSite->setUpdated(date('YmdHis'));

	// 홈페이지 주소
	$link_alternative = sprintf('http://%s',  $GLOBALS['syndi_tag_domain']);
	$oSite->setLinkAlternative($link_alternative);

	$link_self = sprintf('%s?id=%s&type=%s',$GLOBALS['syndi_echo_url'],$tag,$args->type);
	$oSite->setLinkSelf($link_self);

	return $oSite;
}

/**
 * @brief Channel(게시판) 목록 
 **/
function Syndi_getChannelList($args)
{

	$where = '';
	if($args->target_channel_id) $where .= " AND cate='". $args->target_channel_id . "'";

	$sql = "SELECT cate,name FROM `site__` WHERE skin='default' AND perm LIKE '%,99,99,%,%' AND mode='mdDocument' ". $where;
	$sql .= " ORDER BY cate ";

	if($args->type=='channel')
	{
		$sql .= sprintf(" LIMIT %s,%s", ($args->page-1)*$args->max_entry, $args->max_entry);
	}

	$result = @mysql_query($sql);

	$channel_list = array();
	while($row = @mysql_fetch_assoc($result))
	{
		$row['name'] = $row['name']?$row['name']:$row['cate'];

		$tag = SyndicationHandler::getTag('channel',$row['cate']);
		$oChannel = new SyndicationChannel;
		$oChannel->setId($tag);
		$oChannel->setTitle($row['name']);
		$oChannel->setType('web');
		$oChannel->setUpdated(date('YmdHis'));

		$link_self = sprintf('%s?id=%s&type=%s',$GLOBALS['syndi_echo_url'],$tag,$args->type);
		$oChannel->setLinkSelf($link_self);

		// 게시판 웹주소
		$link_alternative = sprintf('http://%s/index.php?cate=%s', $GLOBALS['syndi_tag_domain'], $row['cate']);
		$oChannel->setLinkAlternative($link_alternative);

		$channel_list[] = $oChannel;
	}

	mysql_free_result($result);

	return $channel_list;
}

/**
 * @brief 삭제 게시물 목록 
 * 삭제된 게시물에 대해 logging이 필요
 **/
function Syndi_getDeletedList($args)
{
	// get article list
	$where = '';
	if($args->target_content_id) $where .= ' AND content_id='. $args->target_content_id;
	if($args->target_channel_id) $where .= ' AND bbs_id='. $args->target_channel_id;
	if($args->start_time) $where .= ' AND delete_date >= '. _getTime($args->start_time);
	if($args->end_time) $where .= ' AND delete_date <= '. _getTime($args->end_time);

	$sql = "SELECT content_id, bbs_id, title, delete_date, link_alternative FROM site__syndiDeleteContentLog WHERE 1=1" . $where;
	$sql .= " ORDER BY delete_date desc ";	
	$sql .= sprintf(" limit %s,%s", ($args->page-1)*$args->max_entry, $args->max_entry);
	$result = @mysql_query($sql);

	$deleted_list = array();
	while($row = @mysql_fetch_assoc($result))
	{
		$oDeleted = new SyndicationDeleted;
		$tag = SyndicationHandler::getTag('article', $row['bbs_id'], $row['content_id']);
		$oDeleted->setId($tag);
		$oDeleted->setTitle($row['title']);
		$oDeleted->setUpdated($row['delete_date']);
		$oDeleted->setDeleted($row['delete_date']);
		$oDeleted->setLinkAlternative($row['link_alternative']);

		$deleted_list[] = $oDeleted;
	}

	mysql_free_result($result);

	return $deleted_list;
}


/**
 * @brief 게시물 목록 
 **/
function Syndi_getArticleList($args)
{
	// get article list
	$where = '';
	if($args->target_content_id) $where .= ' AND seq='. $args->target_content_id;
	if($args->target_channel_id) $where .= ' AND cate='. $args->target_channel_id;
	if($args->start_time) $where .= ' AND regDate >= '. _getTime($args->start_time);
	if($args->end_time) $where .= ' AND regDate <= '. _getTime($args->end_time);

	$sql = "SELECT seq, cate, subject, content, writer, email, url, regDate
		FROM `mdDocument__content` WHERE 1=1" . $where;
	$sql .= " ORDER BY regDate desc ";
	$sql .= sprintf(" limit %s,%s", ($args->page-1)*$args->max_entry, $args->max_entry);
	$result = @mysql_query($sql);

	$article_list = array();
	while($row = @mysql_fetch_assoc($result))
	{
		$oArticle = new SyndicationArticle;
		$tag = SyndicationHandler::getTag('article', $row['cate'], $row['seq']);
		$oArticle->setId($tag);
		$oArticle->setTitle($row['subject']);
		$oArticle->setContent($row['content']);
		$oArticle->setType('web');
		//$oArticle->setCategory($row['category']);
		$oArticle->setName($row['writer']);
		$oArticle->setEmail($row['email']);
		$oArticle->setUrl($row['url']);
		$oArticle->setPublished(date('YmdHis',$row['regDate']));
		if($row['upDate']) $oArticle->setUpdated(date('YmdHis',$row['upDate']));
		
		// 게시물 웹주소
		$link_alternative = 'http://'.$GLOBALS['syndi_tag_domain'].'/index.php?cate='.$row['cate'].'&num='.$row['seq'];
		// 게시판 웹주소
		$link_channel_alternative = 'http://'.$GLOBALS['syndi_tag_domain'].'/index.php?cate='.$row['cate'];

		$oArticle->setLinkChannel($tag);
		$oArticle->setLinkAlternative($link_alternative);
		$oArticle->setLinkChannelAlternative($link_channel_alternative);

		$article_list[] = $oArticle;
	}

	mysql_free_result($result);

	return $article_list;
}


/**
 * @brief 게시물 목록 출력시 다음 페이지 번호 
 **/
function Syndi_getArticleNextPage($args)
{
	$where = '';
	if($args->target_content_id) $where .= ' AND seq='. $args->target_content_id;
	if($args->target_channel_id) $where .= ' AND cate='. $args->target_channel_id;
	if($args->start_time) $where .= ' AND regDate >= '. _getTime($args->start_time);
	if($args->end_time) $where .= ' AND regDate <= '. _getTime($args->end_time);

	$count_sql = "SELECT count(*) as cnt FROM `mdDocument__content` WHERE 1=1" .$where;
	$row = @mysql_fetch_assoc(@mysql_query($count_sql));

	$total_count = $row['cnt'];
	$total_page = ceil($total_count / $args->max_entry);

	if($args->page < $total_page)
	{
		return array('page'=>$args->page+1);
	}
	else
	{
		return array('page'=>1); 
	}
}

/**
 * @brief 게시물 삭제 목록 출력시 다음 페이지 번호 
 **/
function Syndi_getDeletedNextPage($args)
{
	$where = '';
	if($args->target_content_id) $where .= ' AND no='. $args->target_content_id;
	if($args->target_channel_id) $where .= ' AND bbs_id='. $args->target_channel_id;
	if($args->start_time) $where .= ' AND delete_date >= '. _getTime($args->start_time);
	if($args->end_time) $where .= ' AND delete_date <= '. _getTime($args->end_time);

	$count_sql = "SELECT count(*) as cnt FROM site__syndiDeleteContentLog WHERE 1=1" .$where;
	$row = @mysql_fetch_assoc(@mysql_query($count_sql));

	$total_count = $row['cnt'];
	$total_page = ceil($total_count / $args->max_entry);

	if($args->page < $total_page)
	{
		return array('page'=>$args->page+1);
	}
	else
	{
		return array('page'=>1); 
	}
}

/**
 * @brief Channel 목록 출력시 다음 페이지 번호 
 **/
function Syndi_getChannelNextPage($args)
{
	$where = '';
	if($args->target_channel_id) $where .= ' AND cate='. $args->target_channel_id;

	$count_sql = "SELECT count(*) as cnt FROM `site__` WHERE skin='default' AND perm LIKE '%,99,99,%,%' AND mode='mdDocument' ". $where;
	$row = @mysql_fetch_assoc(@mysql_query($count_sql));

	$total_count = $row['cnt'];
	$total_page = ceil($total_count / $args->max_entry);

	if($args->page < $total_page)
	{
		return array('page'=>$args->page+1);
	}
	else
	{
		return array('page'=>1); 
	}
}

function _getTime($date)
{
	return strtotime($date);
}


$oSyndicationHandler = &SyndicationHandler::getInstance();
$oSyndicationHandler->registerFunction('site_info','Syndi_getSiteInfo');
$oSyndicationHandler->registerFunction('channel_list','Syndi_getChannelList');
$oSyndicationHandler->registerFunction('channel_next_page','Syndi_getChannelNextPage');
$oSyndicationHandler->registerFunction('article_list','Syndi_getArticleList');
$oSyndicationHandler->registerFunction('article_next_page','Syndi_getArticleNextPage');
$oSyndicationHandler->registerFunction('deleted_list','Syndi_getDeletedList');
$oSyndicationHandler->registerFunction('deleted_next_page','Syndi_getDeletedNextPage');

?>
