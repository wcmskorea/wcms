<?php
/* --------------------------------------------------------------------------------------
| 사이트 정보 - 방문자 아이피
|----------------------------------------------------------------------------------------
| Lastest : 이성준 (2009년 6월 25일 목요일)
*/
$sql['mdAnalytic']['0'] = "
CREATE TABLE IF NOT EXISTS `mdAnalytic__track` (
`counting` int(11) unsigned NOT NULL default '0',
`ip` char(24) default NULL,
`skin` char(10) default NULL,
`info` varchar(255) NOT NULL default '',
`date` date NOT NULL default '0000-00-00',
`time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
PRIMARY KEY (`ip`,`date`),
KEY `date` (`date`),
KEY `ip` (`ip`,`skin`,`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

/* 사이트 정보 - 방문자 경로 */
$sql['mdAnalytic']['1'] = "
CREATE TABLE IF NOT EXISTS `mdAnalytic__refer` (
  `seq` int(11) NOT NULL auto_increment,
  `ip` char(24) default NULL,
  `referer` varchar(255) NOT NULL,
  `date` date NOT NULL default '0000-00-00',
	`time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `check` enum('Y','N') NOT NULL default 'N',
  PRIMARY KEY  (`seq`),
  KEY `ip` (`ip`),
  KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

";

/* 키워드 통계 */
$sql['mdAnalytic']['2'] = "
CREATE TABLE IF NOT EXISTS `mdAnalytic__keyword` (
  `skin` char(10) NOT NULL default 'default',
  `keyword` varchar(200) NOT NULL,
  `hit` int(11) unsigned NOT NULL,
  `date` date NOT NULL default '0000-00-00',
	`uptime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`keyword`,`date`),
  KEY `skin` (`skin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
?>
