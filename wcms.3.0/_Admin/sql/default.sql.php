<?php
/**--------------------------------------------------------------------------------------
 * 사이트 정보 - 카테고리 정보
 */
$sql['site'][0] = "
CREATE TABLE IF NOT EXISTS `site__` (
	`skin` char(10) NOT NULL default 'default',
	`cate` varchar(15) NOT NULL default '000',
	`sort` int(3) unsigned NULL default NULL,
	`child` int(3) unsigned NULL default NULL,
	`hit` int(11) unsigned NULL default NULL,
	`name` varchar(250) NOT NULL,
	`nameExtra` varchar(250) NOT NULL,
	`mode` char(15) NOT NULL default 'mdDocument',
	`perm` char(32) NOT NULL default '2,99,99,99,2',
	`permLmt` char(16) NOT NULL default 'U,U,U,U,U',
	`xml` char(20) NOT NULL default '10,990000',
	`url` varchar(250) NOT NULL,
	`target` enum('_self','_blank') NOT NULL default '_self',
	`status` enum('normal','hide','dep') NOT NULL default 'normal',
	`useFull` enum('Y','N') NOT NULL default 'N',
	`useCate` enum('Y','N') NOT NULL default 'N',
	`upDate` int(11) unsigned NOT NULL default '0',
PRIMARY KEY  (`skin`,`cate`),
KEY `sort` (`sort`),
KEY `skin` (`skin`),
KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

/**--------------------------------------------------------------------------------------
 * 사이트 정보 - 기본설정 데이터
 */
$sql['site'][1] = "
REPLACE INTO `site__` (`skin`, `cate`, `sort`, `child`, `hit`, `name`, `nameExtra`, `mode`, `perm`, `permLmt`, `xml`, `url`, `target`, `status`, `useFull`, `useCate`, `upDate`) VALUES
('default', '000', 0, 5, NULL, '기본설정', '', '', '2,2,2,2,2,2', 'U,U,U,U,U,U', '10,990000', '', '_self', 'hide', 'N', 'N', 0),
('default', '000001', 1, 0, NULL, '인트로', '', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'hide', 'N', 'N', 0),
('default', '000002', 2, 6, NULL, '회원정보', 'Membership', 'mdMember', '2,99,2,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', '', 'N', 'N', 0),
('default', '000002001', 1, 0, NULL, '로그인', 'Sign in', 'mdMember', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', '', 'N', 'N', 0),
('default', '000002002', 2, 0, NULL, '회원가입 및 정보변경', 'Sign up', 'mdMember', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', '', 'N', 'N', 0),
('default', '000002003', 3, 0, NULL, '아이디 및 비밀번호찾기', 'Forget your ID or password?', 'mdMember', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', '', 'N', 'N', 0),
('default', '000003', 3, 1, NULL, '검색', 'Search', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', '', 'N', 'N', 0),
('default', '000003001', 1, 0, NULL, '상품검색', 'Product Search', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', '', 'N', 'N', 0),
('default', '000999', 4, 6, NULL, '이용안내', 'Information', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'hide', 'N', 'N', 0),
('default', '000999001', 1, 0, NULL, '사이트맵', 'Sitemap', 'mdSitemap', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', '', 'N', 'N', 0),
('default', '000999002', 2, 0, NULL, '회원이용약관', 'Agreement', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'hide', 'N', 'N', 0),
('default', '000999003', 3, 0, NULL, '개인정보취급방침', 'Privacy', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'hide', 'N', 'N', 0),
('default', '000999003001', 1, 0, NULL, '개인정보취급방침(약식)', 'Privacy', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'hide', 'N', 'N', 0),
('default', '000999005', 4, 0, NULL, '이메일무단수집거부', 'Spam', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'hide', 'N', 'N', 0),
('default', '000999006', 5, 0, NULL, '저작권안내·신고', 'Licence', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'hide', 'N', 'N', 0),
('default', '000997', 5, 0, NULL, '코드삽입(Header)', '', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'hide', 'N', 'N', 0),
('default', '000998', 6, 0, NULL, '코드삽입(Footer)', '', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'hide', 'N', 'N', 0),
('default', '001', 1, 1, NULL, '메뉴01', 'menu01', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', ',', '', '_self', 'normal', 'N', 'N', 1377240574),
('default', '001001', 1, 1, NULL, '메뉴01-01', 'menu01-01', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'normal', 'N', 'N', 0),
('default', '001001001', 1, 0, NULL, '메뉴01-01-01', 'menu01-01-01', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'normal', 'N', 'N', 0),
('default', '002', 2, 1, NULL, '메뉴02', 'menu02', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', ',', '', '_self', 'normal', 'N', 'N', 1377240578),
('default', '002001', 1, 0, NULL, '메뉴02-01', 'menu02-1', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'normal', 'N', 'N', 0),
('default', '003', 3, 1, NULL, '메뉴03', 'menu03', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', ',', '', '_self', 'normal', 'N', 'N', 1377240581),
('default', '003001', 1, 0, NULL, '메뉴03-01', 'menu03-01', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'normal', 'N', 'N', 0),
('default', '004', 4, 1, NULL, '메뉴04', 'menu04', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', ',', '', '_self', 'normal', 'N', 'N', 1377240585),
('default', '004001', 1, 0, NULL, '메뉴04-01', 'menu04-01', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'normal', 'N', 'N', 0),
('default', '005', 5, 1, NULL, '메뉴05', 'menu05', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', ',', '', '_self', 'normal', 'N', 'N', 1377240589),
('default', '005001', 1, 0, NULL, '메뉴05-01', 'menu05-01', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'normal', 'N', 'N', 0);
";

$sql['site'][2] = "
REPLACE INTO `site__` (`skin`, `cate`, `sort`, `child`, `hit`, `name`, `nameExtra`, `mode`, `perm`, `permLmt`, `xml`, `url`, `target`, `status`, `useFull`, `useCate`, `upDate`) VALUES
('mobile', '101', 1, 1, NULL, '메뉴01', 'menu01', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', ',', '', '_self', 'normal', 'N', 'N', 1377240574),
('mobile', '101001', 1, 1, NULL, '메뉴01-01', 'menu01-01', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'normal', 'N', 'N', 0),
('mobile', '101001001', 1, 0, NULL, '메뉴01-01-01', 'menu01-01-01', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'normal', 'N', 'N', 0),
('mobile', '102', 2, 1, NULL, '메뉴02', 'menu02', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', ',', '', '_self', 'normal', 'N', 'N', 1377240578),
('mobile', '102001', 1, 0, NULL, '메뉴02-01', 'menu02-1', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'normal', 'N', 'N', 0),
('mobile', '103', 3, 1, NULL, '메뉴03', 'menu03', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', ',', '', '_self', 'normal', 'N', 'N', 1377240581),
('mobile', '103001', 1, 0, NULL, '메뉴03-01', 'menu03-01', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'normal', 'N', 'N', 0),
('mobile', '104', 4, 1, NULL, '메뉴04', 'menu04', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', ',', '', '_self', 'normal', 'N', 'N', 1377240585),
('mobile', '104001', 1, 0, NULL, '메뉴04-01', 'menu04-01', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'normal', 'N', 'N', 0),
('mobile', '105', 5, 1, NULL, '메뉴05', 'menu05', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', ',', '', '_self', 'normal', 'N', 'N', 1377240589),
('mobile', '105001', 1, 0, NULL, '메뉴05-01', 'menu05-01', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'normal', 'N', 'N', 0);
";

$sql['site'][3] = "
REPLACE INTO `site__` (`skin`, `cate`, `sort`, `child`, `hit`, `name`, `nameExtra`, `mode`, `perm`, `permLmt`, `xml`, `url`, `target`, `status`, `useFull`, `useCate`, `upDate`) VALUES
('english', '201', 1, 1, NULL, '메뉴01', 'menu01', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', ',', '', '_self', 'normal', 'N', 'N', 1377240574),
('english', '201001', 1, 1, NULL, '메뉴01-01', 'menu01-01', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'normal', 'N', 'N', 0),
('english', '201001001', 1, 0, NULL, '메뉴01-01-01', 'menu01-01-01', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'normal', 'N', 'N', 0),
('english', '202', 2, 1, NULL, '메뉴02', 'menu02', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', ',', '', '_self', 'normal', 'N', 'N', 1377240578),
('english', '202001', 1, 0, NULL, '메뉴02-01', 'menu02-1', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'normal', 'N', 'N', 0),
('english', '203', 3, 1, NULL, '메뉴03', 'menu03', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', ',', '', '_self', 'normal', 'N', 'N', 1377240581),
('english', '203001', 1, 0, NULL, '메뉴03-01', 'menu03-01', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'normal', 'N', 'N', 0),
('english', '204', 4, 1, NULL, '메뉴04', 'menu04', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', ',', '', '_self', 'normal', 'N', 'N', 1377240585),
('english', '204001', 1, 0, NULL, '메뉴04-01', 'menu04-01', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'normal', 'N', 'N', 0),
('english', '205', 5, 1, NULL, '메뉴05', 'menu05', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', ',', '', '_self', 'normal', 'N', 'N', 1377240589),
('english', '205001', 1, 0, NULL, '메뉴05-01', 'menu05-01', 'mdDocument', '2,99,99,99,2,99', 'U,U,U,U,U,U', '10,990000', '', '_self', 'normal', 'N', 'N', 0);
";

/**--------------------------------------------------------------------------------------
 * 사이트 정보 - 기본 정보
 */
$sql['site'][4] = "
CREATE TABLE IF NOT EXISTS `display__default` (
	`sort` tinyint(3) unsigned NOT NULL,
	`position` char(3) NOT NULL default 'MC',
	`cate` varchar(15) NOT NULL,
	`name` varchar(200) NOT NULL,
	`form` char(6) NOT NULL default '',
	`listing` char(12) NOT NULL default '',
	`useHidden` enum('Y','N','H','T') NOT NULL default 'N',
	`config` text NOT NULL,
PRIMARY KEY  (`sort`,`position`),
KEY `position` (`position`),
KEY `listOrder` (`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

$sql['site'][5] = "
CREATE TABLE IF NOT EXISTS `display__mobile` (
	`sort` tinyint(3) unsigned NOT NULL,
	`position` char(3) NOT NULL default 'MC',
	`cate` varchar(15) NOT NULL,
	`name` varchar(200) NOT NULL,
	`form` char(6) NOT NULL default '',
	`listing` char(12) NOT NULL default '',
	`useHidden` enum('Y','N','H','T') NOT NULL default 'N',
	`config` text NOT NULL,
PRIMARY KEY  (`sort`,`position`),
KEY `position` (`position`),
KEY `listOrder` (`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

$sql['site'][6] = "
CREATE TABLE IF NOT EXISTS `display__english` (
	`sort` tinyint(3) unsigned NOT NULL,
	`position` char(3) NOT NULL default 'MC',
	`cate` varchar(15) NOT NULL,
	`name` varchar(200) NOT NULL,
	`form` char(6) NOT NULL default '',
	`listing` char(12) NOT NULL default '',
	`useHidden` enum('Y','N','H','T') NOT NULL default 'N',
	`config` text NOT NULL,
PRIMARY KEY  (`sort`,`position`),
KEY `position` (`position`),
KEY `listOrder` (`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

/**--------------------------------------------------------------------------------------
 * 디스플레이 - 기본설정 데이터
 */
$sql['site'][7] = "
REPLACE INTO `display__default` (`sort`, `position`, `cate`, `name`, `form`, `listing`, `useHidden`, `config`) VALUES
(1, 'MT', '', '회사의 로고를 삽입합니다.', 'skin', 'pdf', 'N', 'a:16:{s:3:\"url\";s:2:\"./\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"200\";s:6:\"height\";s:2:\"80\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:2:\"50\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'ML', '', '메인 좌측', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"200\";s:6:\"height\";s:3:\"320\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'MC', '', '메인 중앙', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"540\";s:6:\"height\";s:3:\"320\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"5\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'MR', '', '메인 좌측', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"200\";s:6:\"height\";s:3:\"320\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'MB', '', '메인 하단', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"950\";s:6:\"height\";s:3:\"200\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'MF', '', '하단 주소영역', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"950\";s:6:\"height\";s:3:\"120\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'MQ', '', '메인 퀵메뉴', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:2:\"60\";s:6:\"height\";s:3:\"100\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:3:\"135\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"5\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'ST', '', '로고', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:2:\"./\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"200\";s:6:\"height\";s:2:\"80\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:2:\"50\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(3, 'ST', '', '서브 비주얼', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"950\";s:6:\"height\";s:3:\"120\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'SL', '', '좌측 서브메뉴', 'menu', 'Text', 'N', 'a:15:{s:6:\"module\";s:4:\"menu\";s:6:\"common\";s:3:\",Y,\";s:5:\"width\";s:3:\"200\";s:6:\"height\";s:3:\"200\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"0\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"colorBg\";s:0:\"\";s:9:\"colorLine\";s:0:\"\";s:8:\"rollOver\";s:1:\"N\";}'),
(1, 'SC', '', '서브 중앙 상단', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"745\";s:6:\"height\";s:3:\"100\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'SB', '', '서브 중앙 하단', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"745\";s:6:\"height\";s:3:\"100\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'SF', '', '서브 하단 사이트 정보', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"950\";s:6:\"height\";s:3:\"100\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'SQ', '', '서브 퀵 메뉴', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:2:\"60\";s:6:\"height\";s:3:\"100\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:3:\"135\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"5\";s:7:\"bgColor\";s:0:\"\";}'),
(2, 'MT', '', '로컬 네비게이션', 'menu', 'TextSlide', 'N', 'a:15:{s:6:\"module\";s:4:\"menu\";s:6:\"common\";s:1:\",\";s:5:\"width\";s:3:\"750\";s:6:\"height\";s:2:\"80\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:2:\"50\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"colorBg\";s:0:\"\";s:9:\"colorLine\";s:0:\"\";s:8:\"rollOver\";s:1:\"N\";}'),
(2, 'ST', '', '로컬 네비게이션', 'menu', 'TextSlide', 'N', 'a:15:{s:6:\"module\";s:4:\"menu\";s:6:\"common\";s:1:\",\";s:5:\"width\";s:3:\"750\";s:6:\"height\";s:2:\"80\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:2:\"50\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"colorBg\";s:0:\"\";s:9:\"colorLine\";s:0:\"\";s:8:\"rollOver\";s:1:\"N\";}'),
(3, 'MT', '000999999', '메인 비주얼', 'box', 'H', 'N', 'a:34:{s:6:\"module\";s:8:\"mdBanner\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"950\";s:6:\"height\";s:3:\"150\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"0\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:8:\"imgWidth\";s:3:\"0px\";s:9:\"imgHeight\";s:3:\"0px\";s:8:\"docCount\";s:1:\"1\";s:6:\"docPad\";s:1:\"0\";s:7:\"docType\";s:5:\"false\";s:8:\"docStart\";s:5:\"false\";s:8:\"docSpeed\";s:4:\"1000\";s:8:\"docDelay\";s:4:\"3000\";s:9:\"docEasing\";s:0:\"\";s:6:\"docBtn\";s:5:\"false\";s:8:\"docPager\";s:5:\"false\";s:8:\"docThumb\";N;s:10:\"docCaption\";s:5:\"false\";s:8:\"useUnion\";N;s:8:\"useTitle\";s:1:\"N\";s:7:\"useDate\";N;s:7:\"useMore\";s:1:\"N\";s:9:\"useHidden\";s:1:\"N\";s:10:\"cutSubject\";N;s:10:\"cutContent\";N;s:7:\"bgColor\";N;}');
";

$sql['site'][8] = "
REPLACE INTO `display__mobile` (`sort`, `position`, `cate`, `name`, `form`, `listing`, `useHidden`, `config`) VALUES
(1, 'MT', '', '회사의 로고를 삽입합니다.', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:2:\"./\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"200\";s:6:\"height\";s:2:\"80\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:2:\"50\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(3, 'MT', '', '메인 비주얼', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"950\";s:6:\"height\";s:3:\"150\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'ML', '', '메인 좌측', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"200\";s:6:\"height\";s:3:\"320\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'MC', '', '메인 중앙', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"540\";s:6:\"height\";s:3:\"320\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"5\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'MR', '', '메인 좌측', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"200\";s:6:\"height\";s:3:\"320\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'MB', '', '메인 하단', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"950\";s:6:\"height\";s:3:\"200\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'MF', '', '하단 주소영역', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"950\";s:6:\"height\";s:3:\"120\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'MQ', '', '메인 퀵메뉴', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:2:\"60\";s:6:\"height\";s:3:\"100\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:3:\"135\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"5\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'ST', '', '로고', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:2:\"./\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"200\";s:6:\"height\";s:2:\"80\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:2:\"50\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(3, 'ST', '', '서브 비주얼', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"950\";s:6:\"height\";s:3:\"120\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'SC', '', '서브 중앙 상단', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"745\";s:6:\"height\";s:3:\"100\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'SB', '', '서브 중앙 하단', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"745\";s:6:\"height\";s:3:\"100\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'SF', '', '서브 하단 사이트 정보', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"950\";s:6:\"height\";s:3:\"100\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'SQ', '', '서브 퀵 메뉴', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:2:\"60\";s:6:\"height\";s:3:\"100\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:3:\"135\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"5\";s:7:\"bgColor\";s:0:\"\";}'),
(2, 'MT', '', '로컬 네비게이션', 'menu', 'TextSlide', 'N', 'a:15:{s:6:\"module\";s:4:\"menu\";s:6:\"common\";s:1:\",\";s:5:\"width\";s:3:\"750\";s:6:\"height\";s:2:\"80\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:2:\"50\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"colorBg\";s:0:\"\";s:9:\"colorLine\";s:0:\"\";s:8:\"rollOver\";s:1:\"N\";}'),
(2, 'ST', '', '로컬 네비게이션', 'menu', 'TextSlide', 'N', 'a:15:{s:6:\"module\";s:4:\"menu\";s:6:\"common\";s:1:\",\";s:5:\"width\";s:3:\"750\";s:6:\"height\";s:2:\"80\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:2:\"50\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"colorBg\";s:0:\"\";s:9:\"colorLine\";s:0:\"\";s:8:\"rollOver\";s:1:\"N\";}'),
(1, 'SL', '', 'rwar', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"200\";s:6:\"height\";s:3:\"320\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}');
";

$sql['site'][9] = "
REPLACE INTO `display__english` (`sort`, `position`, `cate`, `name`, `form`, `listing`, `useHidden`, `config`) VALUES
(1, 'MT', '', '회사의 로고를 삽입합니다.', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:2:\"./\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"200\";s:6:\"height\";s:2:\"80\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:2:\"50\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(3, 'MT', '', '메인 비주얼', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"950\";s:6:\"height\";s:3:\"150\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'ML', '', '메인 좌측', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"200\";s:6:\"height\";s:3:\"320\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'MC', '', '메인 중앙', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"540\";s:6:\"height\";s:3:\"320\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"5\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'MR', '', '메인 좌측', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"200\";s:6:\"height\";s:3:\"320\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'MB', '', '메인 하단', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"950\";s:6:\"height\";s:3:\"200\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'MF', '', '하단 주소영역', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"950\";s:6:\"height\";s:3:\"120\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'MQ', '', '메인 퀵메뉴', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:2:\"60\";s:6:\"height\";s:3:\"100\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:3:\"135\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"5\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'ST', '', '로고', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:2:\"./\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"200\";s:6:\"height\";s:2:\"80\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:2:\"50\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(3, 'ST', '', '서브 비주얼', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"950\";s:6:\"height\";s:3:\"120\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'SL', '', '좌측 서브메뉴', 'menu', 'Text', 'N', 'a:15:{s:6:\"module\";s:4:\"menu\";s:6:\"common\";s:3:\",Y,\";s:5:\"width\";s:3:\"200\";s:6:\"height\";s:3:\"200\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"0\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"colorBg\";s:0:\"\";s:9:\"colorLine\";s:0:\"\";s:8:\"rollOver\";s:1:\"N\";}'),
(1, 'SC', '', '서브 중앙 상단', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"745\";s:6:\"height\";s:3:\"100\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'SB', '', '서브 중앙 하단', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"745\";s:6:\"height\";s:3:\"100\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'SF', '', '서브 하단 사이트 정보', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:3:\"950\";s:6:\"height\";s:3:\"100\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:1:\"5\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"bgColor\";s:0:\"\";}'),
(1, 'SQ', '', '서브 퀵 메뉴', 'skin', '', 'N', 'a:16:{s:3:\"url\";s:0:\"\";s:6:\"target\";s:5:\"_self\";s:6:\"module\";s:4:\"skin\";s:6:\"common\";s:1:\"Y\";s:12:\"commonExcept\";s:1:\",\";s:5:\"width\";s:2:\"60\";s:6:\"height\";s:3:\"100\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:3:\"135\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"5\";s:7:\"bgColor\";s:0:\"\";}'),
(2, 'MT', '', '로컬 네비게이션', 'menu', 'TextSlide', 'N', 'a:15:{s:6:\"module\";s:4:\"menu\";s:6:\"common\";s:1:\",\";s:5:\"width\";s:3:\"750\";s:6:\"height\";s:2:\"80\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:2:\"50\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"colorBg\";s:0:\"\";s:9:\"colorLine\";s:0:\"\";s:8:\"rollOver\";s:1:\"N\";}'),
(2, 'ST', '', '로컬 네비게이션', 'menu', 'TextSlide', 'N', 'a:15:{s:6:\"module\";s:4:\"menu\";s:6:\"common\";s:1:\",\";s:5:\"width\";s:3:\"750\";s:6:\"height\";s:2:\"80\";s:3:\"pdt\";s:1:\"0\";s:3:\"pdr\";s:1:\"0\";s:3:\"pdb\";s:1:\"0\";s:3:\"pdl\";s:1:\"0\";s:3:\"mgt\";s:2:\"50\";s:3:\"mgr\";s:1:\"0\";s:3:\"mgb\";s:1:\"0\";s:3:\"mgl\";s:1:\"0\";s:7:\"colorBg\";s:0:\"\";s:9:\"colorLine\";s:0:\"\";s:8:\"rollOver\";s:1:\"N\";}');
";

/**--------------------------------------------------------------------------------------
 * 사이트 정보 - 우편번호
 */
$sql['site'][10] = "
CREATE TABLE IF NOT EXISTS `site__zipcode` (
	`seq` int(10) unsigned NOT NULL auto_increment,
	`zipcode` varchar(7) NOT NULL default '',
	`sido` varchar(4) NOT NULL default '',
	`gugun` varchar(13) NOT NULL default '',
	`dong` varchar(44) NOT NULL default '',
	`bunji` varchar(30) NOT NULL default '',
PRIMARY KEY  (`seq`),
KEY `sido` (`sido`),
KEY `gugun` (`gugun`),
KEY `dong` (`dong`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

/**--------------------------------------------------------------------------------------
 * 사이트 정보 - 세션
 */
$sql['site'][11] = "
CREATE TABLE IF NOT EXISTS `site__session` (
  `id` varchar(32) NOT NULL,
  `ssDatetime` datetime NOT NULL,
  `ssData` text NOT NULL,
  `userid` varchar(20) NOT NULL,
  `ssIP` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ss_datetime` (`ssDatetime`),
  KEY `userid` (`userid`,`ssIP`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

?>
