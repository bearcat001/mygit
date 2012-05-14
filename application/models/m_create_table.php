<?php
	class M_create_table extends CI_Model{
		private $tables;
		function __construct(){
			parent::__construct();
			
			$this->tables=array(
					'feed',
					'group',
					'group_category',
					'group_member',
					'friend',
					'blog',
					'blog_category',
					'blog_tag',
					'album',
					'picture',
					'event',
					'event_member',
					'notify',
					'online',
					'place',
					'place_category',
					'place_member',
					'user',
					'session',
					'user_meta',
					'bluetooth',
					'bluetooth_search',
					'comment',
					'place_meta'
					);
			
			foreach($this->tables as $table){
				$real_table=$this->db->dbprefix($table);
				if(!$this->db->table_exists($real_table))
					call_user_func(array("M_create_table",$table),$real_table);
			}
			
		}
		
		function feed($real_table){
			$this->db->simple_query("
					CREATE TABLE IF NOT EXISTS `{$real_table}` (
					`feed_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`user_id` INT( 11 ) NOT NULL ,
					`feed_type` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
					`feed_content` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
					`picture_url` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
					`create_time` INT( 10 ) NOT NULL ,
					`transpond_id` INT( 11 ) NOT NULL ,
					`transpond_count` INT( 11 ) NOT NULL ,
					`comment_count` INT( 11 ) NOT NULL,
					`visibility` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
					) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;"
			);
		}
		
		function group($real_table){
			$this->db->simple_query("
					CREATE TABLE IF NOT EXISTS `{$real_table}` (
					`group_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					`user_id` int(11) unsigned NOT NULL,
					`group_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					`group_category` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					`group_destription` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					`group_states` int(11) unsigned NOT NULL,
					`member_count` INT( 11 ) UNSIGNED NOT NULL,
					`create_time` int(10) unsigned NOT NULL,
					PRIMARY KEY (`group_id`),
					KEY `user_id` (`user_id`,`group_name`,`group_states`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
			);
		}
		
		function group_category($real_table){
			$this->db->simple_query("
					CREATE TABLE IF NOT EXISTS `{$real_table}` (
					`group_category_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`group_category_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					`group_count` INT( 11 ) UNSIGNED NOT NULL
					) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;
			");
		}
		
		function group_member($real_table){	
			$this->db->simple_query("
					CREATE TABLE IF NOT EXISTS `{$real_table}` (
					`group_member_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`group_id` INT( 11 ) UNSIGNED NOT NULL ,
					`user_id` INT( 11 ) UNSIGNED NOT NULL ,
					`inviter_id` INT( 11 ) UNSIGNED NOT NULL ,
					`is_admin` TINYINT( 1 ) UNSIGNED NOT NULL ,
					`is_confirmed` TINYINT( 1 ) UNSIGNED NOT NULL,
					`create_time` INT( 10 ) UNSIGNED NOT NULL
					) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci;
			");
		}
		
		function friend($real_table){
			$this->db->simple_query("
				CREATE TABLE IF NOT EXISTS `{$real_table}` (
					`friendship_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`user_id` INT( 11 ) UNSIGNED NOT NULL ,
					`friend_id` INT( 11 ) UNSIGNED NOT NULL ,
					`create_time` INT( 10 ) UNSIGNED NOT NULL ,
					INDEX (  `user_id` ,  `friend_id` )
					) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;"
				);
		}
		
		function blog($real_table){
			$this->db->simple_query("
					CREATE TABLE IF NOT EXISTS `{$real_table}` (
						`blog_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
						`user_id` int(11) unsigned NOT NULL,
						`create_time` int(10) unsigned NOT NULL,
						`blog_title` varchar(200) NOT NULL,
						`blog_content` text NOT NULL,
						`blog_category` int(11) unsigned NOT NULL,
						`transpond_id` int(11) unsigned NOT NULL,
						`transpond_count` int(11) unsigned NOT NULL,
						`comment_count` int(11) unsigned NOT NULL,
						`blog_type` int(2) unsigned NOT NULL,
						`blog_visible` int(11) unsigned NOT NULL,
						`blog_password` varchar(49) NOT NULL,
						PRIMARY KEY (`blog_id`)
						) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
			");
		}
		
		function blog_category($real_table){
			$this->db->simple_query("
					CREATE TABLE IF NOT EXISTS `{$real_table}` (
					`blog_category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					`blog_category_name` varchar(200) NOT NULL,
					`blog_count` int(11) unsigned NOT NULL,
					PRIMARY KEY (`blog_category_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			");
		}
		
		function blog_tag($real_table){
			$this->db->simple_query("
					CREATE TABLE IF NOT EXISTS `{$real_table}` (
					`blog_tag_id` int(11) unsigned NOT NULL,
					`blog_tag_name` varchar(200) NOT NULL,
					`blog_id` int(11) unsigned NOT NULL,
					PRIMARY KEY (`blog_tag_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;
			");
		}
		
		function album($real_table){
			$this->db->simple_query("
					CREATE TABLE IF NOT EXISTS `{$real_table}` (
					`album_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`album_name` VARCHAR( 50 ) NOT NULL ,
					`user_id` INT( 11 ) NOT NULL ,
					`album_destription` VARCHAR(255) NOT NULL,
					`create_time` INT( 10 ) NOT NULL ,
					`picture_count` INT( 11 ) NOT NULL ,
					`album_appearance` INT( 11 ) NOT NULL ,
					`album_visible` INT( 11 ) NOT NULL ,
					`album_password` VARCHAR( 49 ) NOT NULL
					) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;"
			);
		}
		
		function picture($real_table){
			$this->db->simple_query("
					CREATE TABLE IF NOT EXISTS `{$real_table}` (
					`picture_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`album_id` INT( 11 ) NOT NULL ,
					`user_id` INT( 11 ) NOT NULL ,
					`picture_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					`picture_destription` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					`file_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					`file_size` INT( 11 ) NOT NULL ,
					`file_type` INT( 1 ) NOT NULL ,
					`create_time` INT( 10 ) NOT NULL
					) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;
			");
		}
		
		function event($real_table){
			$this->db->simple_query("
					CREATE TABLE IF NOT EXISTS `{$real_table}` (
					`event_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`event_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					`event_destription` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					`start_time` INT( 10 ) UNSIGNED NOT NULL ,
					`end_time` INT( 10 ) UNSIGNED NOT NULL ,
					`user_id` INT( 10 ) UNSIGNED NOT NULL ,
					`place_id` INT( 10 ) UNSIGNED NOT NULL ,
					`status_count` INT( 10 ) UNSIGNED NOT NULL ,
					`member_count` INT( 10 ) UNSIGNED NOT NULL
					) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;
			");
		}
		
		function event_member($real_table){
			$this->db->simple_query("
					CREATE TABLE IF NOT EXISTS `{$real_table}` (
					`event_member_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`event_id` INT( 11 ) UNSIGNED NOT NULL ,
					`user_id` INT( 11 ) UNSIGNED NOT NULL ,
					`is_admin` TINYINT( 1 ) UNSIGNED NOT NULL ,
					`is_confirmed` TINYINT( 1 ) UNSIGNED NOT NULL
					) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci;
			");
		}
		
		function message($real_table){
			$place= $this->db->simple_query("
					CREATE TABLE IF NOT EXISTS `{$real_table}` (
					`message_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`from_id` INT( 11 ) NOT NULL ,
					`to_id` INT( 11 ) NOT NULL ,
					`message_content` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					`is_read` TINYINT( 1 ) UNSIGNED NOT NULL ,
					`create_time` INT( 10 ) UNSIGNED NOT NULL
					) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci;
			");
		}
		
		function notify($real_table){
			$this->db->simple_query("
					CREATE TABLE IF NOT EXISTS `{$real_table}` (
					`notify_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`from_id` INT( 11 ) UNSIGNED NOT NULL ,
					`to_id` INT( 11 ) UNSIGNED NOT NULL ,
					`notify_type` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					`notify_type_id` INT( 11 ) UNSIGNED NOT NULL,
					`notify_content` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  ,
					`is_read` TINYINT( 1 ) NOT NULL ,
					`create_time` INT( 10 ) UNSIGNED NOT NULL
					) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci;
			");
		}
		
		function online($real_table){
			$this->db->simple_query("
					CREATE TABLE IF NOT EXISTS `{$real_table}` (
					`user_id` INT( 11 ) UNSIGNED NOT NULL ,
					`display_name` VARCHAR( 32 ) NOT NULL ,
					`online_type` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
					`create_time` INT( 10 ) UNSIGNED NOT NULL ,
					PRIMARY KEY (  `user_id` )
					) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;"
					);
		}
		
		function place($real_table){
			$this->db->simple_query("
					CREATE TABLE IF NOT EXISTS `{$real_table}` (
					`place_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`place_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					`place_destription` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					`place_category` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					`place_states` INT( 11 ) UNSIGNED NOT NULL ,
					`member_count` INT( 11 ) UNSIGNED NOT NULL ,
					`wifi_mac` varchar(12) ,
					`bluetooth_id` INT( 11 ) UNSIGNED NOT NULL,
					`create_time` INT( 10 ) UNSIGNED NOT NULL
					) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;
			");
		}
		
		function place_category($real_table){
			$this->db->simple_query("
					CREATE TABLE  IF NOT EXISTS `{$real_table}` (
					`place_category_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`place_category_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					`place_count` INT( 11 ) NOT NULL
					) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;
			");
		}
		
		function place_member($real_table){
			$this->db->simple_query("
					CREATE TABLE IF NOT EXISTS `{$real_table}` (
					`place_member_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`place_id` INT( 11 ) UNSIGNED NOT NULL ,
					`user_id` INT( 11 ) UNSIGNED NOT NULL ,
					`is_admin` TINYINT( 1 ) UNSIGNED NOT NULL ,
					`is_confirmed` TINYINT( 1 ) UNSIGNED NOT NULL,
					`create_time` INT( 11 ) UNSIGNED NOT NULL
					) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci;
			");
		}
		
		function user($real_table){
			$this->db->simple_query("
					CREATE TABLE IF NOT EXISTS `{$real_table}` (
					`user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					`email` varchar(200) NOT NULL UNIQUE,
					`password` varchar(49) DEFAULT NULL,
					`real_name` varchar(32) NOT NULL,
					`display_name` varchar(32) DEFAULT NULL,
					`create_time` int(10) unsigned NOT NULL,
					`bluetooth_id` int(10) unsigned NOT NULL,
					`last_activity` int(10) unsigned NOT NULL,
					`user_type` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
					PRIMARY KEY (`user_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8;"
			);
		}
		
		function session($real_table){
			$this->db->simple_query("
					CREATE TABLE IF NOT EXISTS  `{$real_table}` (
					`session_id` varchar(40) DEFAULT '0' NOT NULL,
					`ip_address` varchar(16) DEFAULT '0' NOT NULL,
					`user_agent` varchar(120) NOT NULL,
					`last_activity` int(10) unsigned DEFAULT 0 NOT NULL,
					`user_data` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
					PRIMARY KEY (session_id)
			);");
		}
		
		function user_meta($real_table){
			$this->db->simple_query("
					CREATE TABLE  IF NOT EXISTS  `{$real_table}` (
					`user_meta_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`user_id` INT( 11 ) NOT NULL ,
					`meta_key` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					`meta_value` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
					) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;
			");
		}
		
		function bluetooth($real_table){
			$this->db->simple_query("
					CREATE TABLE IF NOT EXISTS `{$real_table}` (
					`bluetooth_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`user_id` INT( 11 ) UNSIGNED NOT NULL ,
					`bluetooth_mac` VARCHAR( 12 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					`bluetooth_name` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					`create_time` INT( 10 ) UNSIGNED NOT NULL
					) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;
			");
		}
		
		function bluetooth_search($real_table){
			$this->db->simple_query("
					CREATE TABLE IF NOT EXISTS `{$real_table}` (
					`bluetooth_search_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`from_id` INT( 11 ) UNSIGNED NOT NULL ,
					`to_id` INT( 11 ) UNSIGNED NOT NULL ,
					`rssi` INT( 6 ) NOT NULL ,
					`create_time` INT( 10 ) UNSIGNED NOT NULL
					) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci;
					");
		}
		
		function comment($real_table){
			$this->db->simple_query("
					CREATE TABLE IF NOT EXISTS `{$real_table}` (
				  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `user_id` int(11) unsigned NOT NULL,
				  `comment_type_id` int(11) unsigned NOT NULL,
				  `comment_type` varchar(255) NOT NULL,
				  `comment_content` varchar(255) NOT NULL,
				  `create_time` int(11) NOT NULL,
				  PRIMARY KEY (`comment_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			");
			
		}
		
		function place_meta($real_table){
			$this->db->simple_query("
					CREATE TABLE  `{$real_table}` (
						`place_meta_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
						`place_id` INT( 11 ) UNSIGNED NOT NULL ,
						`meta_key` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
						`meta_value` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
						) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;
					");
		}
	}