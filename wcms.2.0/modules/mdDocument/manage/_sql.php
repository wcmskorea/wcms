<?php
/**--------------------------------------------------------------------------------------
 * 도큐멘트 모듈 - 설정 테이블
 *----------------------------------------------------------------------------------------
 * Lastest : 이성준 (2009년 6월 25일 목요일)
 */
$sql['mdDocument']['0'] = "
CREATE TABLE IF NOT EXISTS `mdDocument__` (
	`skin` char(15) NOT NULL default 'default',
	`cate` char(15) NOT NULL default '000',
	`share` char(15) NOT NULL default '',
	`articled` int(11) unsigned NOT NULL default '0',
	`articleTrashed` int(11) unsigned NOT NULL default '0',
	`listUnion` enum('Y','N') NOT NULL default 'N',
	`boardType` enum('BASIC','QNA','HUGI','HUGIBASIC') NOT NULL default 'BASIC',
	`config` text NOT NULL,
	`contentAdd` text NOT NULL,
	PRIMARY KEY  (`skin`,`cate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

$sql['mdDocument']['1'] = "
INSERT INTO `mdDocument__` (`cate`, `articled`, `articleTrashed`, `config`, `contentAdd`) VALUES
('001', 0, 0, 'a:15:{s:4:\"list\";s:4:\"Page\";s:8:\"listView\";s:1:\"N\";s:10:\"listHcount\";s:1:\"1\";s:10:\"listVcount\";s:1:\"1\";s:10:\"cutSubject\";s:0:\"\";s:11:\"uploadCount\";s:1:\"0\";s:10:\"uploadType\";s:5:\"Basic\";s:9:\"recommand\";s:1:\"0\";s:7:\"comment\";s:1:\"N\";s:3:\"sms\";s:1:\"N\";s:9:\"thumbType\";s:3:\"4,3\";s:10:\"thumbSsize\";s:0:\"\";s:10:\"thumbMsize\";s:0:\"\";s:10:\"thumbBsize\";s:0:\"\";s:8:\"division\";s:0:\"\";}', 'a:6:{s:12:\"opt_division\";s:1:\"N\";s:11:\"opt_subject\";s:1:\"M\";s:10:\"opt_writer\";s:1:\"M\";s:9:\"opt_email\";s:1:\"Y\";s:9:\"opt_phone\";s:1:\"N\";s:13:\"opt_agreement\";s:1:\"N\";}'),
('001001', 0, 0, 'a:15:{s:4:\"list\";s:4:\"Page\";s:8:\"listView\";s:1:\"N\";s:10:\"listHcount\";s:1:\"1\";s:10:\"listVcount\";s:1:\"1\";s:10:\"cutSubject\";s:0:\"\";s:11:\"uploadCount\";s:1:\"0\";s:10:\"uploadType\";s:5:\"Basic\";s:9:\"recommand\";s:1:\"0\";s:7:\"comment\";s:1:\"N\";s:3:\"sms\";s:1:\"N\";s:9:\"thumbType\";s:3:\"4,3\";s:10:\"thumbSsize\";s:0:\"\";s:10:\"thumbMsize\";s:0:\"\";s:10:\"thumbBsize\";s:0:\"\";s:8:\"division\";s:0:\"\";}', 'a:6:{s:12:\"opt_division\";s:1:\"N\";s:11:\"opt_subject\";s:1:\"M\";s:10:\"opt_writer\";s:1:\"M\";s:9:\"opt_email\";s:1:\"Y\";s:9:\"opt_phone\";s:1:\"N\";s:13:\"opt_agreement\";s:1:\"N\";}'),
('001001001', 0, 0, 'a:15:{s:4:\"list\";s:4:\"Page\";s:8:\"listView\";s:1:\"N\";s:10:\"listHcount\";s:1:\"1\";s:10:\"listVcount\";s:1:\"1\";s:10:\"cutSubject\";s:0:\"\";s:11:\"uploadCount\";s:1:\"0\";s:10:\"uploadType\";s:5:\"Basic\";s:9:\"recommand\";s:1:\"0\";s:7:\"comment\";s:1:\"N\";s:3:\"sms\";s:1:\"N\";s:9:\"thumbType\";s:3:\"4,3\";s:10:\"thumbSsize\";s:0:\"\";s:10:\"thumbMsize\";s:0:\"\";s:10:\"thumbBsize\";s:0:\"\";s:8:\"division\";s:0:\"\";}', 'a:6:{s:12:\"opt_division\";s:1:\"N\";s:11:\"opt_subject\";s:1:\"M\";s:10:\"opt_writer\";s:1:\"M\";s:9:\"opt_email\";s:1:\"Y\";s:9:\"opt_phone\";s:1:\"N\";s:13:\"opt_agreement\";s:1:\"N\";}'),
('002', 0, 0, 'a:15:{s:4:\"list\";s:4:\"Page\";s:8:\"listView\";s:1:\"N\";s:10:\"listHcount\";s:1:\"1\";s:10:\"listVcount\";s:1:\"1\";s:10:\"cutSubject\";s:0:\"\";s:11:\"uploadCount\";s:1:\"0\";s:10:\"uploadType\";s:5:\"Basic\";s:9:\"recommand\";s:1:\"0\";s:7:\"comment\";s:1:\"N\";s:3:\"sms\";s:1:\"N\";s:9:\"thumbType\";s:3:\"4,3\";s:10:\"thumbSsize\";s:0:\"\";s:10:\"thumbMsize\";s:0:\"\";s:10:\"thumbBsize\";s:0:\"\";s:8:\"division\";s:0:\"\";}', 'a:6:{s:12:\"opt_division\";s:1:\"N\";s:11:\"opt_subject\";s:1:\"M\";s:10:\"opt_writer\";s:1:\"M\";s:9:\"opt_email\";s:1:\"Y\";s:9:\"opt_phone\";s:1:\"N\";s:13:\"opt_agreement\";s:1:\"N\";}'),
('002001', 0, 0, 'a:15:{s:4:\"list\";s:4:\"Page\";s:8:\"listView\";s:1:\"N\";s:10:\"listHcount\";s:1:\"1\";s:10:\"listVcount\";s:1:\"1\";s:10:\"cutSubject\";s:0:\"\";s:11:\"uploadCount\";s:1:\"0\";s:10:\"uploadType\";s:5:\"Basic\";s:9:\"recommand\";s:1:\"0\";s:7:\"comment\";s:1:\"N\";s:3:\"sms\";s:1:\"N\";s:9:\"thumbType\";s:3:\"4,3\";s:10:\"thumbSsize\";s:0:\"\";s:10:\"thumbMsize\";s:0:\"\";s:10:\"thumbBsize\";s:0:\"\";s:8:\"division\";s:0:\"\";}', 'a:6:{s:12:\"opt_division\";s:1:\"N\";s:11:\"opt_subject\";s:1:\"M\";s:10:\"opt_writer\";s:1:\"M\";s:9:\"opt_email\";s:1:\"Y\";s:9:\"opt_phone\";s:1:\"N\";s:13:\"opt_agreement\";s:1:\"N\";}'),
('003', 0, 0, 'a:15:{s:4:\"list\";s:4:\"Page\";s:8:\"listView\";s:1:\"N\";s:10:\"listHcount\";s:1:\"1\";s:10:\"listVcount\";s:1:\"1\";s:10:\"cutSubject\";s:0:\"\";s:11:\"uploadCount\";s:1:\"0\";s:10:\"uploadType\";s:5:\"Basic\";s:9:\"recommand\";s:1:\"0\";s:7:\"comment\";s:1:\"N\";s:3:\"sms\";s:1:\"N\";s:9:\"thumbType\";s:3:\"4,3\";s:10:\"thumbSsize\";s:0:\"\";s:10:\"thumbMsize\";s:0:\"\";s:10:\"thumbBsize\";s:0:\"\";s:8:\"division\";s:0:\"\";}', 'a:6:{s:12:\"opt_division\";s:1:\"N\";s:11:\"opt_subject\";s:1:\"M\";s:10:\"opt_writer\";s:1:\"M\";s:9:\"opt_email\";s:1:\"Y\";s:9:\"opt_phone\";s:1:\"N\";s:13:\"opt_agreement\";s:1:\"N\";}'),
('003001', 0, 0, 'a:15:{s:4:\"list\";s:4:\"Page\";s:8:\"listView\";s:1:\"N\";s:10:\"listHcount\";s:1:\"1\";s:10:\"listVcount\";s:1:\"1\";s:10:\"cutSubject\";s:0:\"\";s:11:\"uploadCount\";s:1:\"0\";s:10:\"uploadType\";s:5:\"Basic\";s:9:\"recommand\";s:1:\"0\";s:7:\"comment\";s:1:\"N\";s:3:\"sms\";s:1:\"N\";s:9:\"thumbType\";s:3:\"4,3\";s:10:\"thumbSsize\";s:0:\"\";s:10:\"thumbMsize\";s:0:\"\";s:10:\"thumbBsize\";s:0:\"\";s:8:\"division\";s:0:\"\";}', 'a:6:{s:12:\"opt_division\";s:1:\"N\";s:11:\"opt_subject\";s:1:\"M\";s:10:\"opt_writer\";s:1:\"M\";s:9:\"opt_email\";s:1:\"Y\";s:9:\"opt_phone\";s:1:\"N\";s:13:\"opt_agreement\";s:1:\"N\";}'),
('004', 0, 0, 'a:15:{s:4:\"list\";s:4:\"Page\";s:8:\"listView\";s:1:\"N\";s:10:\"listHcount\";s:1:\"1\";s:10:\"listVcount\";s:1:\"1\";s:10:\"cutSubject\";s:0:\"\";s:11:\"uploadCount\";s:1:\"0\";s:10:\"uploadType\";s:5:\"Basic\";s:9:\"recommand\";s:1:\"0\";s:7:\"comment\";s:1:\"N\";s:3:\"sms\";s:1:\"N\";s:9:\"thumbType\";s:3:\"4,3\";s:10:\"thumbSsize\";s:0:\"\";s:10:\"thumbMsize\";s:0:\"\";s:10:\"thumbBsize\";s:0:\"\";s:8:\"division\";s:0:\"\";}', 'a:6:{s:12:\"opt_division\";s:1:\"N\";s:11:\"opt_subject\";s:1:\"M\";s:10:\"opt_writer\";s:1:\"M\";s:9:\"opt_email\";s:1:\"Y\";s:9:\"opt_phone\";s:1:\"N\";s:13:\"opt_agreement\";s:1:\"N\";}'),
('004001', 0, 0, 'a:15:{s:4:\"list\";s:4:\"Page\";s:8:\"listView\";s:1:\"N\";s:10:\"listHcount\";s:1:\"1\";s:10:\"listVcount\";s:1:\"1\";s:10:\"cutSubject\";s:0:\"\";s:11:\"uploadCount\";s:1:\"0\";s:10:\"uploadType\";s:5:\"Basic\";s:9:\"recommand\";s:1:\"0\";s:7:\"comment\";s:1:\"N\";s:3:\"sms\";s:1:\"N\";s:9:\"thumbType\";s:3:\"4,3\";s:10:\"thumbSsize\";s:0:\"\";s:10:\"thumbMsize\";s:0:\"\";s:10:\"thumbBsize\";s:0:\"\";s:8:\"division\";s:0:\"\";}', 'a:6:{s:12:\"opt_division\";s:1:\"N\";s:11:\"opt_subject\";s:1:\"M\";s:10:\"opt_writer\";s:1:\"M\";s:9:\"opt_email\";s:1:\"Y\";s:9:\"opt_phone\";s:1:\"N\";s:13:\"opt_agreement\";s:1:\"N\";}'),
('005', 0, 0, 'a:15:{s:4:\"list\";s:4:\"Page\";s:8:\"listView\";s:1:\"N\";s:10:\"listHcount\";s:1:\"1\";s:10:\"listVcount\";s:1:\"1\";s:10:\"cutSubject\";s:0:\"\";s:11:\"uploadCount\";s:1:\"0\";s:10:\"uploadType\";s:5:\"Basic\";s:9:\"recommand\";s:1:\"0\";s:7:\"comment\";s:1:\"N\";s:3:\"sms\";s:1:\"N\";s:9:\"thumbType\";s:3:\"4,3\";s:10:\"thumbSsize\";s:0:\"\";s:10:\"thumbMsize\";s:0:\"\";s:10:\"thumbBsize\";s:0:\"\";s:8:\"division\";s:0:\"\";}', 'a:6:{s:12:\"opt_division\";s:1:\"N\";s:11:\"opt_subject\";s:1:\"M\";s:10:\"opt_writer\";s:1:\"M\";s:9:\"opt_email\";s:1:\"Y\";s:9:\"opt_phone\";s:1:\"N\";s:13:\"opt_agreement\";s:1:\"N\";}'),
('005001', 0, 0, 'a:15:{s:4:\"list\";s:4:\"Page\";s:8:\"listView\";s:1:\"N\";s:10:\"listHcount\";s:1:\"1\";s:10:\"listVcount\";s:1:\"1\";s:10:\"cutSubject\";s:0:\"\";s:11:\"uploadCount\";s:1:\"0\";s:10:\"uploadType\";s:5:\"Basic\";s:9:\"recommand\";s:1:\"0\";s:7:\"comment\";s:1:\"N\";s:3:\"sms\";s:1:\"N\";s:9:\"thumbType\";s:3:\"4,3\";s:10:\"thumbSsize\";s:0:\"\";s:10:\"thumbMsize\";s:0:\"\";s:10:\"thumbBsize\";s:0:\"\";s:8:\"division\";s:0:\"\";}', 'a:6:{s:12:\"opt_division\";s:1:\"N\";s:11:\"opt_subject\";s:1:\"M\";s:10:\"opt_writer\";s:1:\"M\";s:9:\"opt_email\";s:1:\"Y\";s:9:\"opt_phone\";s:1:\"N\";s:13:\"opt_agreement\";s:1:\"N\";}')
";

/* 도큐멘트 모듈 - 본문 테이블 */
$sql['mdDocument']['2'] = "
CREATE TABLE IF NOT EXISTS `mdDocument__content` (
	`seq` bigint(11) unsigned NOT NULL auto_increment,
	`cate` char(15) NOT NULL default '000',
	`idx` bigint(11) unsigned NOT NULL default '0',
	`idxDepth` tinyint(3) unsigned NOT NULL default '0',
	`idxTrash` bigint(11) unsigned NOT NULL default '0',
	`productSeq` int(11) unsigned NOT NULL default '0',
	`boardType` enum('BASIC','QNA','HUGI','HUGIBASIC') NOT NULL default 'BASIC',
	`id` varchar(32) NOT NULL default '',
	`writer` varchar(20) NOT NULL default '',
	`passwd` varchar(250) NOT NULL,
	`email` varchar(40) NOT NULL default '',
	`url` varchar(125) NOT NULL default '',
	`category` varchar(50) NOT NULL default '',
	`subject` varchar(125) NOT NULL default '',
	`content` longtext NOT NULL,
	`contentAdd` longtext NOT NULL,
	`useAdmin` enum('Y','N') NOT NULL default 'N',
	`useHtml` enum('Y','N') NOT NULL default 'Y',
	`useNotice` enum('Y','N') NOT NULL default 'N',
	`useSecret` enum('Y','N') NOT NULL default 'N',
	`regDate` int(11) unsigned NOT NULL default '0',
	`endDate` int(11) unsigned NOT NULL default '0',
	`upDate` int(11) unsigned NOT NULL default '0',
	`ip` char(15) NOT NULL default '',
	`commentCount` int(11) unsigned NOT NULL default '0',
	`fileCount` int(11) unsigned NOT NULL default '0',	
	`readCount` int(11) unsigned NOT NULL default '0',
	`voteCount` int(11) unsigned NOT NULL default '0',
	PRIMARY KEY  (`cate`,`seq`),
	KEY `cate` (`cate`),
	KEY `idx` (`idx`),
	KEY `idxTrash` (`idxTrash`),
	KEY `product` (`productSeq`,`boardType`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

/* 도큐멘트 모듈 - 첨부파일 테이블 */
$sql['mdDocument']['3'] = "
CREATE TABLE IF NOT EXISTS `mdDocument__file` (
	`seq` int(11) unsigned NOT NULL auto_increment,
	`cate` char(15) NOT NULL default '000',
	`parent` int(11) unsigned NOT NULL default '0',
	`fileName` varchar(50) default NULL,
	`realName` varchar(50) default NULL,
	`extName` char(3) default NULL,
	`regDate` int(11) unsigned NOT NULL,
	`viewImg` enum('Y','N') NOT NULL default 'N',
	PRIMARY KEY  (`seq`),
	KEY `cate` (`cate`),
	KEY `parent` (`parent`),
	KEY `listOrder` (`cate`,`parent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

/* 도큐멘트 모듈 - 댓글 테이블 */
$sql['mdDocument']['4'] = "
CREATE TABLE IF NOT EXISTS `mdDocument__comment` (
	`seq` int(11) unsigned NOT NULL auto_increment,
	`cate` char(15) NOT NULL default '000',
	`parent` int(11) unsigned NOT NULL default '0',
	`id` varchar(40) default NULL,
	`writer` varchar(20) NOT NULL default '',
	`passwd` varchar(50) default NULL,
	`comment` text NOT NULL,
	`useAdmin` enum('Y','N') NOT NULL default 'N',
	`useSecret` enum('Y','N') NOT NULL default 'N',
	`trashDate` int(11) NOT NULL default '0',
	`regDate` int(11) unsigned NOT NULL default '0',
	`upDate` int(11) unsigned NOT NULL default '0',
	`ip` char(15) NOT NULL default '',
	`voteCount` int(11) unsigned NOT NULL default '0',
	PRIMARY KEY  (`seq`),
	KEY `cate` (`cate`),
	KEY `parent` (`parent`),
	KEY `listOrder` (`trashDate`,`regDate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
?>
