--
-- 테이블 구조 `sample_bbs_info`
--

CREATE TABLE IF NOT EXISTS `sample_bbs_info` (
			`bbs_id` int(11) NOT NULL auto_increment,
			`name` varchar(255) NOT NULL,
			`content` text NOT NULL,
			`regdate` char(14) NOT NULL,
			PRIMARY KEY  (`bbs_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;


INSERT INTO `sample_bbs_info` (`bbs_id`, `name`, `content`, `regdate`) VALUES
(1, '자유게시판', '우리사이트 자유로운 자유게시판', '20100525000000'),
(2, '문서자료실', '우리사이트 문서자료실', '20100526000000');

-- --------------------------------------------------------

--
-- 테이블 구조 `sample_contents`
--

CREATE TABLE IF NOT EXISTS `sample_contents` (
		`content_id` int(11) NOT NULL auto_increment,
		`bbs_id` int(11) NOT NULL,
		`title` varchar(255) NOT NULL,
		`content` text NOT NULL,
		`category` varchar(50) NOT NULL,
		`user_name` varchar(50) NOT NULL,
		`user_email` varchar(255) NOT NULL,
		`user_homepage` varchar(255) NOT NULL,
		`regdate` char(14) NOT NULL,
		PRIMARY KEY  (`content_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `sample_contents` (`content_id`, `bbs_id`, `title`, `content`, `category`, `user_name`, `user_email`, `user_homepage`, `regdate`) VALUES
(1, 1, '제목제목', '내용내용내용내용내용내용내용내용내용내용내용내용내용내용내용내용내용내용내용내용내용내용내용내용내용', '', '사용자', '', '', '20100526120000'),
(2, 1, '22222222', '내용내용내용내용내용내용내용내용내용내용내용내용내용내용내용내용내용내용내용내용내용내용내용내용내용22', '', '사용자', '', '', '201005261210000');

-- --------------------------------------------------------

--
-- 테이블 구조 `syndi_delete_content_log`
--

CREATE TABLE IF NOT EXISTS `syndi_delete_content_log` (
		`content_id` bigint(11) unsigned NOT NULL, 
		`bbs_id` varchar(50) unsigned NOT NULL, 
		`title` text NOT NULL,
		`link_alternative` varchar(250) NOT NULL, 
		`delete_date` varchar(14) NOT NULL, 
		PRIMARY KEY  (`content_id`,`bbs_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 테이블의 덤프 데이터 `syndi_delete_content_log`
--

