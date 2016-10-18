<?php
/* -------------------------------------------------
| 광고관리 모듈 - 팝업정보 테이블
*/
$sql['mdPopup']['0'] = "
CREATE TABLE IF NOT EXISTS `mdPopup__content` (
    `seq` int(11) NOT NULL auto_increment,
    `cate` varchar(15) NOT NULL,
    `type` enum('L','W','T') NOT NULL,
    `subject` varchar(200) NOT NULL,
    `content` text NOT NULL,
    `url` varchar(250) NOT NULL,
    `speriod` int(13) NOT NULL,
    `eperiod` int(13) NOT NULL,
    `emod` enum('Y','N') NOT NULL,
    `target` enum('_blank','_parent') NOT NULL,
    `control` enum('Y','N') NOT NULL,
    `size` varchar(16) NOT NULL,
    `position` ENUM('N','Y') NOT NULL DEFAULT 'N',
    `scroll` enum('Y','N') NOT NULL,
    `hidden` enum('Y','N','Z') NOT NULL,
    `date` int(13) unsigned NOT NULL,
    `info` char(40) NOT NULL default '',
PRIMARY KEY  (`seq`),
KEY `speriod` (`speriod`),
KEY `eperiod` (`eperiod`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
?>
