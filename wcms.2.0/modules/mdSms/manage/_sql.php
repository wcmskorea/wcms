<?php
/* --------------------------------------------------------------------------------------
| SMS Component - 설정 테이블
*/
$sql[mdSms][0]  = "
CREATE TABLE IF NOT EXISTS `mdSms__` (
`mode` char(15) NOT NULL default '',
`temp01` varchar(160) NOT NULL default '',
`temp02` varchar(160) NOT NULL default '',
`temp03` varchar(160) NOT NULL default '',
`temp04` varchar(160) NOT NULL default '',
`temp05` varchar(160) NOT NULL default '',
PRIMARY KEY  (`mode`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$sql[mdSms][1]  = "REPLACE INTO `mdSms__` (`mode`, `temp01`, `temp02`, `temp03`, `temp04`, `temp05`) VALUES
('mdDocument', '{카테고리}에 신규 글이 등록 되었습니다{사이트}', '{이름}님의 내용에 답변이 등록되었습니다.{사이트}', '', '', ''),
('mdApp01', '{고객명}님의 신규 상담건이 접수되었습니다! 빠른확인바랍니다.{사이트}', '{고객명}님의 요청하신 상담건이 정상적으로 접수되었습니다.{사이트}', '', '', ''),
('mdMember', '{아이디}님의 임시 비밀번호는 {비밀번호}입니다.{사이트}', '{이름}님! {사이트} 사이트에 회원가입을 진심으로 환영합니다.', '{이름}님께서 {아이디}로 {사이트}에 회원가입을 완료하셨습니다.', '{이름}님의 생일을 진심으로 축하드립니다. 행복한 하루되세요-{사이트}-', '');";

/* --------------------------------------------------------------------------------------
| SMS Component - 발송내역 테이블
*/
$sql[mdSms][3]  = "
CREATE TABLE IF NOT EXISTS `mdSms__history` (
  `seq` int(11) unsigned NOT NULL auto_increment,
  `sendDate` int(13) unsigned NOT NULL,
	`regDate` int(13) unsigned NOT NULL,
  `receiver` text NOT NULL default '',
  `sender` char(16) NOT NULL default '',
  `mesg` varchar(160) NOT NULL default '',
  `rst` char(2) NOT NULL default '',
  `user` char(16) NOT NULL default '',
  `userip` char(16) NOT NULL default '',
	`sendType` enum('A','M') NOT NULL default 'M',
  `count` int(5) NOT NULL default '0',
  PRIMARY KEY  (`seq`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

/* --------------------------------------------------------------------------------------
| SMS Component - 발송문자 저장 테이블
*/
$sql[mdSms][4]  = "
CREATE TABLE IF NOT EXISTS `mdSms__mesg` (
  `seq` int(11) unsigned NOT NULL auto_increment,
  `mesg` varchar(160) NOT NULL default '',
  `date` int(13) NOT NULL,
  PRIMARY KEY  (`seq`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
?>
