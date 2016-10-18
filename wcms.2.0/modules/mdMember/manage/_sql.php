<?php
/* 회원 모듈 - 설정 테이블 */
$sql['mdMember']['0'] = "
CREATE TABLE IF NOT EXISTS `mdMember__` (
	`skin` char(10) NOT NULL default 'default',
	`cate` char(15) NOT NULL default '000',
	`membered` int(11) unsigned NOT NULL default '0',
	`seceded` int(11) unsigned NOT NULL default '0',
	`config` text NOT NULL,
	`contentAdd` text NOT NULL,
PRIMARY KEY  (`skin`,`cate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

";
/* 회원 모듈 - 기본 환경설정 등록 */
$sql['mdMember']['1'] = "INSERT INTO `mdMember__` (`skin`, `cate`, `membered`, `seceded`, `config`, `contentAdd`) VALUES
('default', '000002001', 0, 0, 'a:4:{s:4:\"form\";s:5:\"Basic\";s:4:\"cert\";s:4:\"Pass\";s:4:\"find\";s:5:\"email\";s:3:\"sms\";s:1:\"N\";}', 'a:28:{s:4:\"data\";s:0:\"\";s:12:\"opt_division\";s:1:\"N\";s:13:\"opt_agreement\";s:1:\"M\";s:8:\"opt_name\";s:1:\"M\";s:10:\"opt_idcode\";s:1:\"N\";s:8:\"opt_nick\";s:1:\"N\";s:9:\"opt_email\";s:1:\"M\";s:11:\"opt_address\";s:1:\"M\";s:14:\"opt_mobileAuth\";s:1:\"N\";s:10:\"opt_mobile\";s:1:\"M\";s:9:\"opt_phone\";s:1:\"M\";s:10:\"opt_office\";s:1:\"N\";s:7:\"opt_fax\";s:1:\"N\";s:7:\"opt_sex\";s:1:\"M\";s:9:\"opt_birth\";s:1:\"M\";s:10:\"opt_memory\";s:1:\"N\";s:11:\"opt_receive\";s:1:\"M\";s:13:\"opt_groupName\";s:1:\"N\";s:11:\"opt_groupNo\";s:1:\"N\";s:7:\"opt_ceo\";s:1:\"N\";s:10:\"opt_status\";s:1:\"N\";s:9:\"opt_class\";s:1:\"N\";s:14:\"opt_department\";s:1:\"N\";s:8:\"opt_team\";s:1:\"N\";s:12:\"opt_function\";s:1:\"N\";s:11:\"opt_content\";s:1:\"N\";s:15:\"opt_recomSelect\";s:1:\"N\";s:14:\"opt_recomInput\";s:1:\"N\";}'),
('default', '000002002', 0, 0, 'a:4:{s:4:\"form\";s:5:\"Basic\";s:4:\"cert\";s:4:\"Pass\";s:4:\"find\";s:5:\"email\";s:3:\"sms\";s:1:\"N\";}', 'a:28:{s:4:\"data\";s:0:\"\";s:12:\"opt_division\";s:1:\"N\";s:13:\"opt_agreement\";s:1:\"M\";s:8:\"opt_name\";s:1:\"M\";s:10:\"opt_idcode\";s:1:\"N\";s:8:\"opt_nick\";s:1:\"N\";s:9:\"opt_email\";s:1:\"M\";s:11:\"opt_address\";s:1:\"M\";s:14:\"opt_mobileAuth\";s:1:\"N\";s:10:\"opt_mobile\";s:1:\"M\";s:9:\"opt_phone\";s:1:\"M\";s:10:\"opt_office\";s:1:\"N\";s:7:\"opt_fax\";s:1:\"N\";s:7:\"opt_sex\";s:1:\"M\";s:9:\"opt_birth\";s:1:\"M\";s:10:\"opt_memory\";s:1:\"N\";s:11:\"opt_receive\";s:1:\"M\";s:13:\"opt_groupName\";s:1:\"N\";s:11:\"opt_groupNo\";s:1:\"N\";s:7:\"opt_ceo\";s:1:\"N\";s:10:\"opt_status\";s:1:\"N\";s:9:\"opt_class\";s:1:\"N\";s:14:\"opt_department\";s:1:\"N\";s:8:\"opt_team\";s:1:\"N\";s:12:\"opt_function\";s:1:\"N\";s:11:\"opt_content\";s:1:\"N\";s:15:\"opt_recomSelect\";s:1:\"N\";s:14:\"opt_recomInput\";s:1:\"N\";}'),
('default', '000002003', 0, 0, 'a:4:{s:4:\"form\";s:5:\"Basic\";s:4:\"cert\";s:4:\"Pass\";s:4:\"find\";s:5:\"email\";s:3:\"sms\";s:1:\"N\";}', 'a:28:{s:4:\"data\";s:0:\"\";s:12:\"opt_division\";s:1:\"N\";s:13:\"opt_agreement\";s:1:\"M\";s:8:\"opt_name\";s:1:\"M\";s:10:\"opt_idcode\";s:1:\"N\";s:8:\"opt_nick\";s:1:\"N\";s:9:\"opt_email\";s:1:\"M\";s:11:\"opt_address\";s:1:\"M\";s:14:\"opt_mobileAuth\";s:1:\"N\";s:10:\"opt_mobile\";s:1:\"M\";s:9:\"opt_phone\";s:1:\"M\";s:10:\"opt_office\";s:1:\"N\";s:7:\"opt_fax\";s:1:\"N\";s:7:\"opt_sex\";s:1:\"M\";s:9:\"opt_birth\";s:1:\"M\";s:10:\"opt_memory\";s:1:\"N\";s:11:\"opt_receive\";s:1:\"M\";s:13:\"opt_groupName\";s:1:\"N\";s:11:\"opt_groupNo\";s:1:\"N\";s:7:\"opt_ceo\";s:1:\"N\";s:10:\"opt_status\";s:1:\"N\";s:9:\"opt_class\";s:1:\"N\";s:14:\"opt_department\";s:1:\"N\";s:8:\"opt_team\";s:1:\"N\";s:12:\"opt_function\";s:1:\"N\";s:11:\"opt_content\";s:1:\"N\";s:15:\"opt_recomSelect\";s:1:\"N\";s:14:\"opt_recomInput\";s:1:\"N\";}');";

/* 회원 모듈 - 계정정보 테이블 */
$sql['mdMember']['2'] = "
CREATE TABLE IF NOT EXISTS `mdMember__account` (
	`seq` int(11) unsigned NOT NULL auto_increment,
	`sort` int(11) unsigned NOT NULL default '1',
	`division` enum('P','C','S','F') NOT NULL default 'P',
	`level` tinyint(3) unsigned NOT NULL default '99',
	`group` varchar(30) NOT NULL,
	`id` char(16) NOT NULL,
	`name` varchar(32) NOT NULL,
	`nick` varchar(32) NOT NULL,
	`passwd` varchar(250) NOT NULL,
	`idcode` varchar(50) NOT NULL,
	`email` varchar(50) NOT NULL,
	`amountPay` int(10) unsigned NOT NULL default '0',
	`dateReg` int(13) unsigned NOT NULL default '0',
	`dateModify` int(13) unsigned NOT NULL default '0',
	`dateExpire` int(13) unsigned NOT NULL default '0',
	`passwdModify` int(13) unsigned NOT NULL default '0',
	`recom` char(16) NOT NULL default '',
	`info` varchar(40) NOT NULL default '',
PRIMARY KEY  (`seq`),
UNIQUE KEY (`id`),
KEY `division` (`division`),
KEY `level` (`level`),
KEY `listOrder` (`seq`,`level`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

/* 회원 모듈 - 부가정보 테이블 */
$sql['mdMember']['3'] = "
CREATE TABLE IF NOT EXISTS `mdMember__info` (
	`id` char(16) NOT NULL,
	`ceo` varchar(12) NOT NULL,
	`groupName` varchar(40) NOT NULL,
	`groupNo` char(12) NOT NULL,
	`status` varchar(32) NOT NULL,
	`class` varchar(32) NOT NULL,
	`zipcode` char(7) NOT NULL,
	`address01` varchar(40) NOT NULL,
	`address02` varchar(40) NOT NULL,
	`mobile` char(14) NOT NULL,
	`phone` char(14) NOT NULL,
	`office` char(14) NOT NULL,
	`fax` char(14) NOT NULL,
	`week` char(3) NOT NULL,
	`sex` char(1) NOT NULL,
	`age` tinyint(3) NOT NULL,
	`birth` int(13) NOT NULL default '0',
	`birthType` enum('S','L') NOT NULL default 'S',
	`memory` int(13) NOT NULL default '0',
	`cafeNick` varchar(32) NOT NULL,
	`cafeLevel` varchar(32) NOT NULL,
	`receive` enum('Y','N') NOT NULL default 'Y',
	`department` varchar(50) NOT NULL default '',
	`team` varchar(50) NOT NULL default '',
	`function` varchar(50) NOT NULL default '',
	`certification` mediumtext NOT NULL default '',
	`religion` varchar(50) NOT NULL default '',
	`content` text NOT NULL default '',
	`contentAdd` text NOT NULL default '',
	`info` varchar(40) NOT NULL default '',
PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

/* 회원 모듈 - 등급정보 테이블 */
$sql['mdMember']['4'] = "
CREATE TABLE IF NOT EXISTS `mdMember__level` (
	`level` tinyint(3) unsigned NOT NULL default '90',
	`position` varchar(12) NOT NULL default '',
	`rate` int(8) unsigned NOT NULL default '0',
	`default` enum('Y','N') NOT NULL default 'N',
	`recom` enum('Y','N') NOT NULL default 'N',
PRIMARY KEY  (`level`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

/* 회원 모듈 - 기본 데이터 등록 */
$sql['mdMember']['5'] = "REPLACE INTO `mdMember__level` (`level`,`position`,`rate`,`default`) VALUES ('1','관리자','4294967295','N')
,('2','운영자','0','N'),('3','부운영자','0','N'),('4','정회원','0','N'),('5','준회원','0','Y'),('99','비회원','0','N');";

/* 회원 모듈 - 닉네임 히스토리 테이블 */
$sql['mdMember']['6'] = "
CREATE TABLE IF NOT EXISTS `mdMember__nick` (
	`seq` int(11) unsigned NOT NULL auto_increment,
	`id` char(16) NOT NULL,
	`nick` varchar(12) NOT NULL,
	`nicked` varchar(12) NOT NULL,
	`info` varchar(40) NOT NULL default '',
PRIMARY KEY  (`seq`),
KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

/* 회원 모듈 - 기본 데이터 등록 */
//$sql['mdMember']['7'] = "REPLACE INTO `mdMember__account` (`seq`,`id`,`name`,`email`) VALUES ('1','master','시스템관리자','css@wcms.kr');";
//$sql['mdMember']['8'] = "REPLACE INTO `mdMember__info` (`id`, `ceo`, `groupName`, `groupNo`, `status`, `class`, `zipcode`, `address01`, `address02`, `mobile`, `phone`, `office`, `fax`, `week`, `sex`, `age`, `birth`, `memory`, `receive`, `department`, `team`, `function`, `education`, `career`, `qualifications`, `content`, `contentAdd`, `info`) VALUES ('master', '이성준', '(주)10억홈피', '410-86-30175', '소프트웨어자문개발공급업', '서비스', '506-306', '광주 광산구 신창동', '112-3 골드존타워 2층 201호', '01029840407', '0623744242', '', '0623744249', 'Tue', '1', 40, 1, 0, 'Y', '', '', '', '', '', '', '', '', '');";

/* 회원 모듈 - 닉네임 히스토리 테이블 */
$sql['mdMember']['9'] = "
CREATE TABLE IF NOT EXISTS `mdMember__log` (
	`seq` int(11) unsigned NOT NULL auto_increment,
	`id` char(16) NOT NULL,
	`login` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	`logout` timestamp NOT NULL,
	`ip` varchar(16) NOT NULL,
PRIMARY KEY  (`seq`),
KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

/* 회원 모듈 - 첨부파일 테이블 */
$sql['mdMember']['10'] = "
CREATE TABLE IF NOT EXISTS `mdMember__file` (
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
?>
