<?php
/* --------------------------------------------------------------------------------------
| 상담(일발)모듈 - 설정 테이블
*/
$sql['mdApp01']['0']  = "
CREATE TABLE IF NOT EXISTS `mdApp01__` (
	`skin` char(10) NOT NULL default 'default',
	`cate` varchar(15) NOT NULL default '000',
	`config` text NOT NULL default '',
	`contentAdd` text NOT NULL default '',
PRIMARY KEY  (`skin`,`cate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

//기본 설정값
$sql['mdApp01']['1'] = "INSERT INTO `mdApp01__` (`skin`, `cate`, `config`, `contentAdd`) VALUES
('default', '005', 'a:6:{s:6:\"result\";s:35:\"상담접수,상담중,상담완료\";s:7:\"listing\";s:4:\"Form\";s:10:\"uploadType\";s:5:\"Basic\";s:3:\"sms\";s:1:\"N\";s:9:\"listCount\";s:4:\"1,10\";s:11:\"uploadCount\";s:1:\"0\";}', 'a:29:{s:12:\"opt_division\";s:1:\"N\";s:13:\"opt_agreement\";s:1:\"M\";s:8:\"opt_name\";s:1:\"M\";s:10:\"opt_idcode\";s:1:\"N\";s:9:\"opt_email\";s:1:\"M\";s:10:\"opt_mobile\";s:1:\"M\";s:9:\"opt_phone\";s:1:\"Y\";s:11:\"opt_content\";s:1:\"Y\";s:12:\"opt_schedule\";s:1:\"N\";s:8:\"opt_add1\";s:1:\"N\";s:8:\"opt_add2\";s:1:\"N\";s:8:\"opt_add3\";s:1:\"N\";s:8:\"opt_add4\";s:1:\"N\";s:8:\"opt_add5\";s:1:\"N\";s:8:\"opt_add6\";s:1:\"N\";s:8:\"opt_add7\";s:1:\"N\";s:8:\"opt_add8\";s:1:\"N\";s:8:\"opt_add9\";s:1:\"N\";s:9:\"opt_add10\";s:1:\"N\";s:9:\"opt_add11\";s:1:\"N\";s:9:\"opt_add12\";s:1:\"N\";s:9:\"opt_add13\";s:1:\"N\";s:9:\"opt_add14\";s:1:\"N\";s:9:\"opt_add15\";s:1:\"N\";s:9:\"opt_add16\";s:1:\"N\";s:9:\"opt_add17\";s:1:\"N\";s:9:\"opt_add18\";s:1:\"N\";s:9:\"opt_add19\";s:1:\"N\";s:9:\"opt_add20\";s:1:\"N\";}');";

/* 상담(일반)모듈 - 신청정보 테이블 */
$sql['mdApp01']['2']  = "
CREATE TABLE IF NOT EXISTS `mdApp01__content` (
	`seq` int(10) unsigned NOT NULL auto_increment,
	`cate` varchar(15) NOT NULL default '',
	`division` varchar(16) NOT NULL default '',
	`id` varchar(16) NOT NULL default '',
	`name` varchar(32) NOT NULL default '',
	`idCode` varchar(13) NOT NULL default '',
	`email` varchar(50) NOT NULL default '',
	`mobile` varchar(14) NOT NULL default '',
	`phone` varchar(14) NOT NULL default '',
	`zipcode` char(7) NOT NULL,
	`address01` varchar(40) NOT NULL,
	`address02` varchar(40) NOT NULL,
	`content` text NOT NULL,
	`contentAdd` text NOT NULL,
	`contentAnswers` text NOT NULL,
	`schedule` int(10) unsigned NOT NULL default '0',
	`dateReg` int(10) unsigned NOT NULL default '0',
	`dateModify` int(10) unsigned NOT NULL default '0',
	`state` char(2) NOT NULL default '0',
	`file` tinyint(3) NOT NULL default '0',
	`info` varchar(40) NOT NULL default '',
PRIMARY KEY  (`seq`),
KEY `cate` (`cate`),
KEY `state` (`state`),
KEY `divistion` (`division`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

/* 상담(일반)모듈 - 첨부파일 테이블 */
$sql['mdApp01']['3'] = "
CREATE TABLE IF NOT EXISTS `mdApp01__file` (
	`seq` int(10) unsigned NOT NULL auto_increment,
	`parent` int(10) unsigned NOT NULL default '0',
	`cate` varchar(15) NOT NULL default '000',
	`fileName` varchar(50) default NULL,
	`realName` varchar(50) default NULL,
	`extName` varchar(50) default NULL,
	`regDate`int(10) unsigned NOT NULL,
	`viewImg` enum('Y','N') NOT NULL default 'N',
PRIMARY KEY  (`seq`),
KEY `cate` (`cate`),
KEY `parent` (`parent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

$sql['mdApp01']['4'] = "
CREATE TABLE IF NOT EXISTS `mdApp01__opt` (
  `cate` char(15) NOT NULL,
  `sort` tinyint(3) NOT NULL,
  `addName` varchar(40) NOT NULL,
  `addEx` varchar(200) NOT NULL,
  `addContent` varchar(200) NOT NULL,
  `addType` enum('input','radio','checkboxs') NOT NULL default 'input',
  PRIMARY KEY  (`cate`,`sort`),
  KEY `cate` (`cate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
?>
