CREATE TABLE IF NOT EXISTS `zb4_syndi_delete_content_log` (
		`content_id` bigint(11) unsigned NOT NULL, 
		`bbs_id` varchar(50) NOT NULL, 
		`title` text NOT NULL,
		`link_alternative` varchar(250) NOT NULL, 
		`delete_date` varchar(14) NOT NULL, 
		PRIMARY KEY  (`content_id`,`bbs_id`)
)
