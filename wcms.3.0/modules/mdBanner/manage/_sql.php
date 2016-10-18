<?php
/* -------------------------------------------------
| 배너관리 모듈 - 배너 Content 테이블
*/
$sql[mdBanner][0] = "
CREATE TABLE IF NOT EXISTS `mdBanner__` (
	`cate` char(15) NOT NULL default '000',
	`division` varchar(100) NOT NULL,
	`listing` enum('Basic','Gallery') NOT NULL default 'Basic',
	`listCount` char(5) NOT NULL default '00,00',
	`subjectCut` int(3) NOT NULL default '50',
	`opt_division` enum('Y','N','M') NOT NULL default 'N',
	`opt_subject` enum('Y','N','M') NOT NULL default 'M',
	`opt_url` enum('Y','N','M') NOT NULL default 'N',
	PRIMARY KEY  (`cate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

$sql[mdBanner][1] = "
CREATE TABLE IF NOT EXISTS `mdBanner__content` (
	`seq` int(11) unsigned NOT NULL,
	`skin` char(15) NOT NULL default 'default',
	`position` char(2) NOT NULL,
	`type` enum('img','flash') NOT NULL,
	`subject` varchar(200) NOT NULL,
	`url` varchar(250) NOT NULL,
	`target` enum('_blank','_parent') NOT NULL default '_parent',
	`speriod` int(13) unsigned NOT NULL,
	`eperiod` int(13) unsigned NOT NULL,
	`fileName` varchar(40) NOT NULL,
	`width` int(3) unsigned NOT NULL,
	`height` int(3) unsigned NOT NULL,
	`widthThumb` int(3) unsigned NOT NULL,
	`heightThumb` int(3) unsigned NOT NULL,
	`hidden` enum('Y','N') NOT NULL,
	`date` int(13) unsigned NOT NULL,
	`info` char(40) NOT NULL default '',
	PRIMARY KEY (`seq`,`position`),
	KEY `speriod` (`speriod`),
	KEY `eperiod` (`eperiod`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
?>
