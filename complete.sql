-- phpMyAdmin SQL Dump
-- version 3.3.8
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 05, 2011 at 11:33 PM
-- Server version: 5.1.53
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `photagious`
--

-- --------------------------------------------------------

--
-- Table structure for table `beta_email`
--

CREATE TABLE IF NOT EXISTS `beta_email` (
  `be_email` varchar(255) NOT NULL DEFAULT '',
  `be_status` enum('requested','invited') NOT NULL DEFAULT 'requested',
  PRIMARY KEY (`be_email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `beta_invites`
--

CREATE TABLE IF NOT EXISTS `beta_invites` (
  `bi_key` varchar(32) NOT NULL DEFAULT '',
  `bi_email` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`bi_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `board`
--

CREATE TABLE IF NOT EXISTS `board` (
  `b_id` int(10) unsigned NOT NULL DEFAULT '0',
  `b_bc_id` int(10) unsigned DEFAULT NULL,
  `b_u_id` int(10) unsigned DEFAULT NULL,
  `b_title` varchar(100) NOT NULL DEFAULT '',
  `b_description` text,
  `b_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `b_bp_id` int(10) unsigned DEFAULT NULL,
  `b_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `b_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  `b_visible` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`b_id`),
  KEY `b_bc_id` (`b_bc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='An individual discussion board';

-- --------------------------------------------------------

--
-- Table structure for table `board_category`
--

CREATE TABLE IF NOT EXISTS `board_category` (
  `bc_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bc_title` varchar(100) NOT NULL DEFAULT '',
  `bc_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `bc_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `bc_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  `bc_visible` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`bc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Discussion board catagories';

-- --------------------------------------------------------

--
-- Table structure for table `board_post`
--

CREATE TABLE IF NOT EXISTS `board_post` (
  `bp_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bp_b_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bp_parentID` int(10) unsigned NOT NULL DEFAULT '0',
  `bp_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bp_title` varchar(100) NOT NULL DEFAULT '',
  `bp_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `bp_replies` smallint(5) unsigned NOT NULL DEFAULT '0',
  `bp_lastPostID` int(10) unsigned DEFAULT NULL,
  `bp_views` int(10) unsigned NOT NULL DEFAULT '0',
  `bp_lastViewID` int(10) unsigned DEFAULT NULL,
  `bp_sticky` enum('Y','N') NOT NULL DEFAULT 'N',
  `bp_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  `bp_visible` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`bp_id`),
  KEY `bp_b_id` (`bp_b_id`),
  KEY `bp_u_id` (`bp_u_id`),
  KEY `bp_parentID` (`bp_parentID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='An individual post on a discussion board';

-- --------------------------------------------------------

--
-- Table structure for table `board_postcontent`
--

CREATE TABLE IF NOT EXISTS `board_postcontent` (
  `bpc_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bpc_bp_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bpc_content` text NOT NULL,
  PRIMARY KEY (`bpc_id`),
  UNIQUE KEY `bpc_bp_id` (`bpc_bp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='The body of an individual post';

-- --------------------------------------------------------

--
-- Table structure for table `board_views`
--

CREATE TABLE IF NOT EXISTS `board_views` (
  `bv_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bv_bp_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bv_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bv_dateViewed` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`bv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Keeps track of each time a post is viewed';

-- --------------------------------------------------------

--
-- Table structure for table `board_watch`
--

CREATE TABLE IF NOT EXISTS `board_watch` (
  `bw_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bw_bp_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bw_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bw_dateSaved` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`bw_id`),
  UNIQUE KEY `u_id_bp_id` (`bw_u_id`,`bw_bp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Discussion board posts a user wishes to watch';

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `c_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `c_by_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `c_for_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `c_element_id` int(11) unsigned NOT NULL DEFAULT '0',
  `c_comment` varchar(255) NOT NULL DEFAULT '',
  `c_type` enum('blog','foto','flix') NOT NULL DEFAULT 'foto',
  `c_time` int(11) NOT NULL DEFAULT '0',
  `c_status` enum('Active','Deleted') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`c_id`),
  KEY `c_type_element_id` (`c_type`,`c_element_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ecom_carts`
--

CREATE TABLE IF NOT EXISTS `ecom_carts` (
  `ec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ec_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ec_us_hash` char(32) NOT NULL DEFAULT '',
  `ec_dateCreated` int(10) unsigned NOT NULL DEFAULT '0',
  `ec_dateModified` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ec_id`),
  KEY `ec_us_hash` (`ec_u_id`,`ec_us_hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ecom_cart_details`
--

CREATE TABLE IF NOT EXISTS `ecom_cart_details` (
  `ecd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_ecd_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ecd_ec_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ecd_ecg_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ecd_quantity` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ecd_price` double(6,2) unsigned NOT NULL DEFAULT '0.00',
  `ecd_details` text,
  PRIMARY KEY (`ecd_id`),
  KEY `ecd_ec_id` (`ecd_ec_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ecom_catalog`
--

CREATE TABLE IF NOT EXISTS `ecom_catalog` (
  `ecg_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ecg_name` varchar(50) NOT NULL DEFAULT '',
  `ecg_type` enum('','dvd','dvd-copy','diskspace','account','account_pro','promo') NOT NULL DEFAULT '',
  `ecg_description` text,
  `ecg_edit_url` varchar(50) DEFAULT NULL,
  `ecg_price` float(5,2) NOT NULL DEFAULT '0.00',
  `ecg_priceSpecial` float(5,2) DEFAULT NULL,
  `ecg_priceSpecialStart` int(10) unsigned DEFAULT NULL,
  `ecg_priceSpecialEnd` int(10) unsigned DEFAULT NULL,
  `ecg_photos` varchar(250) DEFAULT NULL,
  `ecg_userMode` enum('all','basic','premium') NOT NULL DEFAULT 'all',
  `ecg_availability` enum('in stock','backordered','out of stock') NOT NULL DEFAULT 'in stock',
  `ecg_shipping` tinyint(4) NOT NULL DEFAULT '0',
  `ecg_recurring` enum('Y','N') NOT NULL DEFAULT 'N',
  `ecg_default_quantity` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ecg_max_quantity` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ecg_group` tinyint(4) DEFAULT NULL,
  `ecg_additional` varchar(25) DEFAULT NULL,
  `ecg_children` tinytext,
  `ecg_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`ecg_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ecom_orders`
--

CREATE TABLE IF NOT EXISTS `ecom_orders` (
  `eo_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `eo_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `eo_cc_fname` varchar(30) DEFAULT NULL,
  `eo_cc_lname` varchar(30) DEFAULT NULL,
  `eo_cc_company` varchar(128) DEFAULT NULL,
  `eo_cc_street` varchar(50) DEFAULT NULL,
  `eo_cc_state` varchar(2) DEFAULT NULL,
  `eo_cc_city` varchar(30) DEFAULT NULL,
  `eo_cc_zip` varchar(12) DEFAULT NULL,
  `eo_cc_month` varchar(2) DEFAULT NULL,
  `eo_cc_year` varchar(4) DEFAULT NULL,
  `eo_cc_ccv` varchar(4) DEFAULT NULL,
  `eo_cc_num` varchar(128) DEFAULT NULL,
  `eo_dateCreated` int(10) unsigned NOT NULL DEFAULT '0',
  `eo_dateModified` int(10) unsigned NOT NULL DEFAULT '0',
  `eo_status` enum('Pending','Processing','Ready To Ship','Shipped','Cancelled') NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`eo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ecom_order_details`
--

CREATE TABLE IF NOT EXISTS `ecom_order_details` (
  `eod_id` int(10) unsigned NOT NULL DEFAULT '0',
  `parent_eod_id` int(10) unsigned NOT NULL DEFAULT '0',
  `eod_eo_id` int(10) unsigned NOT NULL DEFAULT '0',
  `eod_ecg_id` int(10) unsigned NOT NULL DEFAULT '0',
  `eod_quantity` smallint(5) unsigned NOT NULL DEFAULT '0',
  `eod_price` double(6,2) unsigned NOT NULL DEFAULT '0.00',
  `eod_details` text,
  PRIMARY KEY (`eod_id`),
  KEY `eod_eo_id` (`eod_eo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ecom_order_shipping`
--

CREATE TABLE IF NOT EXISTS `ecom_order_shipping` (
  `eos_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `eos_eo_id` int(10) unsigned NOT NULL DEFAULT '0',
  `eos_name` varchar(50) DEFAULT NULL,
  `eos_address` varchar(75) DEFAULT NULL,
  `eos_city` varchar(50) DEFAULT NULL,
  `eos_state` varchar(35) DEFAULT NULL,
  `eos_zip` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`eos_id`),
  KEY `eos_eo_id` (`eos_eo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ecom_promotions`
--

CREATE TABLE IF NOT EXISTS `ecom_promotions` (
  `ep_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `ep_ecg_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ep_code` varchar(50) NOT NULL DEFAULT '',
  `ep_timeStart` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ep_timeEnd` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ep_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`ep_id`),
  KEY `ep_code` (`ep_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ecom_recur`
--

CREATE TABLE IF NOT EXISTS `ecom_recur` (
  `er_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `er_eo_id` int(10) unsigned NOT NULL DEFAULT '0',
  `er_ecg_id` int(10) unsigned NOT NULL DEFAULT '0',
  `er_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `er_ccNum` varchar(128) NOT NULL DEFAULT '',
  `er_ccExpMonth` varchar(2) NOT NULL DEFAULT '',
  `er_ccExpYear` varchar(4) NOT NULL DEFAULT '',
  `er_ccCcv` varchar(4) NOT NULL DEFAULT '',
  `er_ccNameFirst` varchar(30) NOT NULL DEFAULT '',
  `er_ccNameLast` varchar(30) NOT NULL DEFAULT '',
  `er_ccCompany` varchar(128) DEFAULT NULL,
  `er_ccStreet` varchar(50) NOT NULL DEFAULT '',
  `er_ccCity` varchar(30) NOT NULL DEFAULT '',
  `er_ccState` varchar(2) NOT NULL DEFAULT '',
  `er_ccZip` varchar(12) NOT NULL DEFAULT '',
  `er_initialDate` date NOT NULL DEFAULT '0000-00-00',
  `er_period` enum('Monthly','Yearly') NOT NULL DEFAULT 'Yearly',
  `er_amount` double(6,2) NOT NULL DEFAULT '0.00',
  `er_status` enum('Active','Disabled') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`er_id`),
  KEY `er_eo_id` (`er_u_id`,`er_ecg_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ecom_recur_back`
--

CREATE TABLE IF NOT EXISTS `ecom_recur_back` (
  `er_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `er_eo_id` int(10) unsigned NOT NULL DEFAULT '0',
  `er_ecg_id` int(10) unsigned NOT NULL DEFAULT '0',
  `er_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `er_ccNum` varchar(128) NOT NULL DEFAULT '',
  `er_ccExpMonth` varchar(2) NOT NULL DEFAULT '',
  `er_ccExpYear` varchar(4) NOT NULL DEFAULT '',
  `er_ccCcv` varchar(4) NOT NULL DEFAULT '',
  `er_ccNameFirst` varchar(30) NOT NULL DEFAULT '',
  `er_ccNameLast` varchar(30) NOT NULL DEFAULT '',
  `er_ccCompany` varchar(128) DEFAULT NULL,
  `er_ccStreet` varchar(50) NOT NULL DEFAULT '',
  `er_ccCity` varchar(30) NOT NULL DEFAULT '',
  `er_ccState` varchar(2) NOT NULL DEFAULT '',
  `er_ccZip` varchar(12) NOT NULL DEFAULT '',
  `er_initialDate` date NOT NULL DEFAULT '0000-00-00',
  `er_period` enum('Monthly','Yearly') NOT NULL DEFAULT 'Yearly',
  `er_amount` double(6,2) NOT NULL DEFAULT '0.00',
  `er_status` enum('Active','Disabled') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`er_id`),
  KEY `er_eo_id` (`er_u_id`,`er_ecg_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ecom_recur_results`
--

CREATE TABLE IF NOT EXISTS `ecom_recur_results` (
  `err_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `err_er_id` int(10) unsigned NOT NULL DEFAULT '0',
  `err_dateTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `err_result` enum('Success','Expired','Failure') NOT NULL DEFAULT 'Success',
  PRIMARY KEY (`err_id`),
  KEY `err_er_id` (`err_er_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ecom_toolbox`
--

CREATE TABLE IF NOT EXISTS `ecom_toolbox` (
  `et_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `et_sess_hash` char(13) NOT NULL DEFAULT '',
  `et_itemId` int(10) unsigned NOT NULL DEFAULT '0',
  `et_itemType` enum('foto','flix') NOT NULL DEFAULT 'foto',
  `et_itemOrder` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`et_id`),
  UNIQUE KEY `et_sess_hash` (`et_sess_hash`,`et_itemId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email_addresses`
--

CREATE TABLE IF NOT EXISTS `email_addresses` (
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email_campaigns`
--

CREATE TABLE IF NOT EXISTS `email_campaigns` (
  `ec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ec_name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`ec_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email_campaign_tracker`
--

CREATE TABLE IF NOT EXISTS `email_campaign_tracker` (
  `ect_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ect_ec_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ect_u_id`,`ect_ec_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email_unsubscribe`
--

CREATE TABLE IF NOT EXISTS `email_unsubscribe` (
  `eu_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `eu_ec_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`eu_u_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE IF NOT EXISTS `faqs` (
  `f_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `f_category` enum('Account','General','Photos','Slideshows','Videos','Personal Page') NOT NULL DEFAULT 'Account',
  `f_question` text NOT NULL,
  `f_answer` text,
  `f_notes` text,
  `f_keywords` varchar(100) DEFAULT NULL,
  `f_link` varchar(150) DEFAULT NULL,
  `f_rating` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `f_dateCreated` date NOT NULL DEFAULT '0000-00-00',
  `f_dateModified` date NOT NULL DEFAULT '0000-00-00',
  `f_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`f_id`),
  FULLTEXT KEY `search` (`f_question`,`f_answer`,`f_keywords`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE IF NOT EXISTS `feedback` (
  `f_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `f_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `f_action` varchar(50) NOT NULL DEFAULT '',
  `f_queryString` varchar(75) DEFAULT NULL,
  `f_get` text,
  `f_post` text,
  `f_referer` varchar(100) DEFAULT NULL,
  `f_feedback` text,
  `f_assigned` enum('John','Jaisen','Cecil') NOT NULL DEFAULT 'John',
  `f_status` enum('1 - Open','2 - Pending','3 - Closed') NOT NULL DEFAULT '1 - Open',
  `f_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`f_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `flix_scheduled`
--

CREATE TABLE IF NOT EXISTS `flix_scheduled` (
  `fs_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID of the scheduled flix',
  `fs_uf_id` int(10) NOT NULL DEFAULT '0' COMMENT 'ID of the flix',
  `fs_u_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID fo the user of the flix',
  `fs_beginDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date to make the flix public',
  `fs_endDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date to make the flix private',
  `fs_initialPrivacy` char(3) NOT NULL DEFAULT '' COMMENT 'The value to change the privacy back to',
  `fs_privacy` char(3) NOT NULL DEFAULT '' COMMENT 'The privacy value to make the flix between the begin and end dates',
  PRIMARY KEY (`fs_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Schedule of Flix to become public and go back to private';

-- --------------------------------------------------------

--
-- Table structure for table `flix_templates`
--

CREATE TABLE IF NOT EXISTS `flix_templates` (
  `ft_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `ft_music` varchar(25) DEFAULT NULL,
  `ft_size` enum('750x450','420x430','400x330','475x450','450x300','750x300','400x270','400x390','680x520','515x560','85x110','190x220','500x405','500x260','800x600','550x600') NOT NULL DEFAULT '750x450',
  `ft_container` enum('ff_container.swf','ff_container_basic.swf','ff_container_no.swf','ff_container_medium.swf','ff_container_horizontal.swf','ff_container_wide.swf','ff_container_test.swf','ff_container_cc.swf','ff_container_large.swf','ff_container_home.swf','ff_container_mini.swf','ff_container_foto_pod.swf','ff_container_nt_large.swf','ff_container_500_260.swf','ff_container_800_600.swf','ff_container_550_600.swf') NOT NULL DEFAULT 'ff_container.swf',
  `ft_name` varchar(50) NOT NULL DEFAULT '',
  `ft_screenshot` varchar(50) NOT NULL DEFAULT '',
  `ft_swf` varchar(35) NOT NULL DEFAULT '',
  `ft_background` varchar(64) NOT NULL DEFAULT '',
  `ft_categories` set('Featured','Custom','Blog','Birthday','Games','Holidays','Large Fotos','Outdoors','Portfolio','Special Event','Vacation') DEFAULT NULL,
  `ft_keywords` varchar(100) DEFAULT NULL,
  `ft_partner` enum('ClearChannel') DEFAULT NULL,
  `ft_additional` varchar(255) DEFAULT NULL,
  `ft_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ft_type` enum('Free','Premium','Custom') NOT NULL DEFAULT 'Free',
  `ft_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`ft_id`),
  KEY `ft_swf` (`ft_swf`),
  FULLTEXT KEY `ff_keyword` (`ft_keywords`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `forums`
--

CREATE TABLE IF NOT EXISTS `forums` (
  `f_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `f_g_id` int(10) unsigned NOT NULL DEFAULT '0',
  `f_title` varchar(100) NOT NULL DEFAULT '',
  `f_description` text,
  `f_displayOrder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `f_lastPostTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `f_lastPoster` varchar(25) NOT NULL DEFAULT '',
  `f_lastThreadTitle` varchar(100) NOT NULL DEFAULT '',
  `f_lastThreadId` int(10) unsigned NOT NULL DEFAULT '0',
  `f_postCount` int(10) unsigned NOT NULL DEFAULT '0',
  `f_threadCount` int(10) unsigned NOT NULL DEFAULT '0',
  `f_dateModified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `f_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`f_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `forum_posts`
--

CREATE TABLE IF NOT EXISTS `forum_posts` (
  `fp_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fp_ft_id` int(10) unsigned NOT NULL DEFAULT '0',
  `fp_f_id` int(10) unsigned DEFAULT '0',
  `fp_parent_id` int(10) NOT NULL DEFAULT '0',
  `fp_username` varchar(30) NOT NULL DEFAULT '',
  `fp_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `fp_g_id` int(10) unsigned NOT NULL DEFAULT '0',
  `fp_title` varchar(100) NOT NULL DEFAULT '',
  `fp_post` text NOT NULL,
  `fp_ipAddress` varchar(15) NOT NULL DEFAULT '',
  `fp_dateModified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `fp_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`fp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `forum_threads`
--

CREATE TABLE IF NOT EXISTS `forum_threads` (
  `ft_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ft_f_id` int(10) unsigned DEFAULT '0',
  `ft_g_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ft_title` varchar(100) NOT NULL DEFAULT '',
  `ft_lastPostTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ft_lastPoster` varchar(25) NOT NULL DEFAULT '',
  `ft_replyCount` int(10) unsigned NOT NULL DEFAULT '0',
  `ft_viewCount` int(10) unsigned NOT NULL DEFAULT '0',
  `ft_open` enum('Y','N') NOT NULL DEFAULT 'Y',
  `ft_sticky` enum('Y','N') NOT NULL DEFAULT 'N',
  `ft_visible` enum('Y','N') NOT NULL DEFAULT 'Y',
  `ft_dateModified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ft_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ft_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fotoflix_slideshow_map`
--

CREATE TABLE IF NOT EXISTS `fotoflix_slideshow_map` (
  `fotoflix_id` int(10) unsigned NOT NULL DEFAULT '0',
  `slideshow_key` varchar(32) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fotoflix_id`,`slideshow_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fotos_public_select`
--

CREATE TABLE IF NOT EXISTS `fotos_public_select` (
  `up_id` int(10) unsigned NOT NULL DEFAULT '0',
  `dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `up_id` (`up_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `foto_objectionable`
--

CREATE TABLE IF NOT EXISTS `foto_objectionable` (
  `fo_us_hash` char(13) NOT NULL DEFAULT '' COMMENT 'session id of the user flagging the foto',
  `fo_up_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'id of the foto being flagged',
  `fo_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'date the foto was flagged',
  PRIMARY KEY (`fo_us_hash`,`fo_up_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Keeps track of fotos that are flagged as objectionable';

-- --------------------------------------------------------

--
-- Table structure for table `foto_quarantined`
--

CREATE TABLE IF NOT EXISTS `foto_quarantined` (
  `fq_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'foto quarantined id',
  `fq_up_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'id of the quarantined foto',
  `fq_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'date the foto was quarantined',
  PRIMARY KEY (`fq_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Keeps track of fotos that have been quarantined';

-- --------------------------------------------------------

--
-- Table structure for table `foto_slideshow_map`
--

CREATE TABLE IF NOT EXISTS `foto_slideshow_map` (
  `up_id` int(10) unsigned NOT NULL DEFAULT '0',
  `us_id` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE IF NOT EXISTS `games` (
  `gm_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `gm_type` enum('target') NOT NULL DEFAULT 'target',
  `gm_name` varchar(64) NOT NULL DEFAULT '',
  `gm_description` varchar(128) NOT NULL DEFAULT '',
  `gm_maxFotos` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `gm_premium` enum('Y','N') NOT NULL DEFAULT 'Y',
  `gm_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`gm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `game_fotorate`
--

CREATE TABLE IF NOT EXISTS `game_fotorate` (
  `fotorate_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `share_g_id` int(10) unsigned NOT NULL DEFAULT '0',
  `owner_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `photo_up_id` int(10) unsigned NOT NULL DEFAULT '0',
  `photo_url` varchar(255) NOT NULL DEFAULT '',
  `gender` char(1) NOT NULL DEFAULT 'M',
  `age` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '-1',
  `view_key` varchar(4) NOT NULL DEFAULT '',
  `vote_key` varchar(4) NOT NULL DEFAULT '',
  `votes` int(10) unsigned NOT NULL DEFAULT '0',
  `average` double unsigned NOT NULL DEFAULT '0',
  `reports` smallint(5) unsigned NOT NULL DEFAULT '0',
  `last_login` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`fotorate_id`),
  UNIQUE KEY `id_viewkey` (`fotorate_id`,`view_key`),
  UNIQUE KEY `user_id` (`owner_u_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `game_fotorate_votes`
--

CREATE TABLE IF NOT EXISTS `game_fotorate_votes` (
  `ip_long` int(10) unsigned NOT NULL DEFAULT '0',
  `fotorate_id` int(10) unsigned NOT NULL DEFAULT '0',
  `vote` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `vote_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ip_long`,`fotorate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `game_target`
--

CREATE TABLE IF NOT EXISTS `game_target` (
  `gto_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `gto_gm_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `gto_name` varchar(64) NOT NULL DEFAULT '',
  `gto_description` varchar(128) NOT NULL DEFAULT '',
  `gto_template` enum('valentine_love','valentine_hate') NOT NULL DEFAULT 'valentine_love',
  `gto_premium` enum('Y','N') NOT NULL DEFAULT 'Y',
  `gto_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`gto_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `game_target_instances`
--

CREATE TABLE IF NOT EXISTS `game_target_instances` (
  `gmt_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `gmt_gm_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `gmt_gto_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `gmt_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `gmt_up_id` int(10) NOT NULL DEFAULT '0',
  `gmt_foto_path` varchar(75) NOT NULL DEFAULT '',
  `gmt_name` varchar(64) NOT NULL DEFAULT '',
  `gmt_viewed` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`gmt_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `g_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `g_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `g_name` varchar(75) NOT NULL DEFAULT '',
  `g_description` text,
  `g_listed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `g_public` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `g_contributors` enum('Owner','Group','All') NOT NULL DEFAULT 'Owner',
  `g_delete` tinyint(1) NOT NULL DEFAULT '0',
  `g_dateModified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `g_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `g_status` enum('Active','Pending','Disabled') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`g_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Table structure for table `group_delete`
--

CREATE TABLE IF NOT EXISTS `group_delete` (
  `g_id` int(10) NOT NULL DEFAULT '0',
  `u_id` int(10) NOT NULL DEFAULT '0',
  `gd_dateToDelete` date NOT NULL DEFAULT '0000-00-00',
  UNIQUE KEY `unique` (`g_id`,`u_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `group_feed`
--

CREATE TABLE IF NOT EXISTS `group_feed` (
  `gf_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gf_g_id` int(10) unsigned NOT NULL DEFAULT '0',
  `gf_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `gf_type` enum('Photo_add','Photo_remove','Slideshow_add','Slideshow_remove','Group_join','Forum_post','Forum_reply','Group_message','Group_settings') NOT NULL DEFAULT 'Photo_add',
  `gf_type_id` int(10) unsigned NOT NULL DEFAULT '0',
  `gf_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `gf_dateId` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`gf_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `group_fotoflix_map`
--

CREATE TABLE IF NOT EXISTS `group_fotoflix_map` (
  `g_id` int(10) unsigned NOT NULL DEFAULT '0',
  `uf_id` int(10) unsigned NOT NULL DEFAULT '0',
  `u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `u_orig_id` int(10) unsigned NOT NULL DEFAULT '0',
  `uf_orig_id` int(10) unsigned NOT NULL DEFAULT '0',
  `gfm_status` enum('Active','Pending','Rejected') NOT NULL DEFAULT 'Pending',
  `dateModified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `UNIQUE` (`g_id`,`uf_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `group_fotos`
--

CREATE TABLE IF NOT EXISTS `group_fotos` (
  `gp_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gp_g_id` int(10) unsigned NOT NULL DEFAULT '0',
  `gp_up_id` int(10) unsigned NOT NULL DEFAULT '0',
  `gp_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `gp_key` varchar(32) NOT NULL DEFAULT '',
  `gp_name` varchar(75) DEFAULT NULL,
  `gp_size` int(10) unsigned NOT NULL DEFAULT '0',
  `gp_width` smallint(5) unsigned NOT NULL DEFAULT '0',
  `gp_height` smallint(5) unsigned NOT NULL DEFAULT '0',
  `gp_description` text,
  `gp_original_path` varchar(75) NOT NULL DEFAULT '',
  `gp_web_path` varchar(75) NOT NULL DEFAULT '',
  `gp_flix_path` varchar(75) NOT NULL DEFAULT '',
  `gp_thumb_path` varchar(75) NOT NULL DEFAULT '',
  `gp_l_ids` varchar(255) DEFAULT NULL,
  `gp_status` enum('active','deleted') NOT NULL DEFAULT 'active',
  `gp_created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `gp_modified_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`gp_id`),
  UNIQUE KEY `group_photo_unique` (`gp_g_id`,`gp_up_id`),
  KEY `gp_key` (`gp_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `group_foto_map`
--

CREATE TABLE IF NOT EXISTS `group_foto_map` (
  `up_id` int(10) unsigned NOT NULL DEFAULT '0',
  `g_id` int(10) unsigned NOT NULL DEFAULT '0',
  `u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `u_orig_id` int(10) unsigned NOT NULL DEFAULT '0',
  `up_orig_id` int(10) unsigned NOT NULL DEFAULT '0',
  `gfm_status` enum('Active','Pending','Rejected') NOT NULL DEFAULT 'Pending',
  `dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`g_id`,`up_id`),
  KEY `u_id` (`u_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `group_invite`
--

CREATE TABLE IF NOT EXISTS `group_invite` (
  `gi_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gi_g_id` int(10) unsigned NOT NULL DEFAULT '0',
  `gi_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `gi_reference` varchar(32) NOT NULL DEFAULT '',
  `gi_name` varchar(50) DEFAULT NULL,
  `gi_email` varchar(75) NOT NULL DEFAULT '',
  `gi_status` enum('Accepted','Declined','Pending') NOT NULL DEFAULT 'Pending',
  `gi_dateResponded` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`gi_id`),
  KEY `gi_reference` (`gi_reference`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `group_prefs`
--

CREATE TABLE IF NOT EXISTS `group_prefs` (
  `gp_g_id` int(10) unsigned NOT NULL DEFAULT '0',
  `gp_name` varchar(16) NOT NULL DEFAULT '',
  `gp_value` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY (`gp_g_id`,`gp_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `group_quick_sets`
--

CREATE TABLE IF NOT EXISTS `group_quick_sets` (
  `gqs_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gqs_p_id` int(10) unsigned NOT NULL DEFAULT '0',
  `gqs_g_id` int(10) unsigned NOT NULL DEFAULT '0',
  `gqs_name` varchar(32) NOT NULL DEFAULT '',
  `gqs_tags` varchar(255) DEFAULT NULL,
  `gqs_icon` varchar(128) DEFAULT NULL,
  `gqs_public` enum('Y','N') NOT NULL DEFAULT 'Y',
  `gqs_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`gqs_id`,`gqs_g_id`),
  KEY `parent_user` (`gqs_p_id`,`gqs_g_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `group_tags`
--

CREATE TABLE IF NOT EXISTS `group_tags` (
  `gt_g_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gt_tag` varchar(32) NOT NULL DEFAULT '',
  `gt_count` int(11) NOT NULL DEFAULT '0',
  `gt_weight` float(3,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`gt_g_id`,`gt_tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `icons`
--

CREATE TABLE IF NOT EXISTS `icons` (
  `i_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `i_name` varchar(50) DEFAULT NULL,
  `i_src` varchar(30) DEFAULT NULL,
  `i_keywords` varchar(255) DEFAULT NULL,
  `i_system` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`i_id`),
  KEY `i_keywords` (`i_keywords`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `idat`
--

CREATE TABLE IF NOT EXISTS `idat` (
  `QualifiedKey` varchar(128) NOT NULL DEFAULT '',
  `CurrentID` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`QualifiedKey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `labels`
--

CREATE TABLE IF NOT EXISTS `labels` (
  `l_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `l_p_id` int(10) unsigned NOT NULL DEFAULT '0',
  `l_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `l_g_id` int(10) unsigned NOT NULL DEFAULT '0',
  `l_name` varchar(30) NOT NULL DEFAULT '',
  `l_icon` varchar(30) DEFAULT NULL,
  `l_tags` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `l_system` enum('Y','N') NOT NULL DEFAULT 'N',
  `l_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `l_status` enum('Active','Deleted') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`l_id`),
  KEY `t_g_id` (`l_g_id`),
  KEY `l_p_id` (`l_p_id`),
  KEY `user_parent` (`l_u_id`,`l_p_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `log_flix_clicks`
--

CREATE TABLE IF NOT EXISTS `log_flix_clicks` (
  `lfc_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lfc_us_hash` varchar(13) DEFAULT NULL,
  `lfc_ipAddress` varchar(15) NOT NULL DEFAULT '',
  `lfc_fastflix` varchar(32) DEFAULT NULL,
  `lfc_urlReferrer` varchar(75) DEFAULT NULL,
  `lfc_urlDestination` varchar(50) DEFAULT NULL,
  `lfc_dateTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`lfc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `log_flix_views`
--

CREATE TABLE IF NOT EXISTS `log_flix_views` (
  `lfv_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lfv_us_hash` varchar(13) NOT NULL DEFAULT '',
  `lfv_ipAddress` varchar(15) NOT NULL DEFAULT '',
  `lfv_fastflix` varchar(32) NOT NULL DEFAULT '',
  `lfv_type` enum('P','C') NOT NULL DEFAULT 'P',
  `lfv_dateTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`lfv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `log_hits`
--

CREATE TABLE IF NOT EXISTS `log_hits` (
  `lh_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lh_referer` varchar(75) DEFAULT NULL,
  `lh_destination` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `lh_ipAddress` varchar(15) NOT NULL DEFAULT '',
  `lh_timestamp` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`lh_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `music`
--

CREATE TABLE IF NOT EXISTS `music` (
  `m_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `m_swf_src` varchar(50) NOT NULL DEFAULT '',
  `m_genre` enum('My Music','Holiday','Classical','Country','Pop/Rock','Jazz/Blues','Romantic','Drama/Suspense','Children/Cartoon','Easy Listening','World','Solo Instrument','Ambience','Drums') NOT NULL DEFAULT 'Holiday',
  `m_tempo` varchar(25) DEFAULT NULL,
  `m_name` varchar(30) NOT NULL DEFAULT '',
  `m_description` varchar(100) DEFAULT NULL,
  `m_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `m_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`m_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notepad`
--

CREATE TABLE IF NOT EXISTS `notepad` (
  `n_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `n_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `n_key` varchar(32) NOT NULL DEFAULT '',
  `n_tags` varchar(255) DEFAULT NULL,
  `n_note` text NOT NULL,
  `n_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `n_dateModified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `n_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`n_id`),
  KEY `n_u_id` (`n_u_id`,`n_active`),
  FULLTEXT KEY `n_tags` (`n_tags`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pools`
--

CREATE TABLE IF NOT EXISTS `pools` (
  `pu_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'pool id',
  `pu_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '' COMMENT 'name for the pool',
  `pu_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'date the pool was created',
  PRIMARY KEY (`pu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Tracks users in a pool';

-- --------------------------------------------------------

--
-- Table structure for table `pool_users_map`
--

CREATE TABLE IF NOT EXISTS `pool_users_map` (
  `pum_pu_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'pool id',
  `pum_u_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'user id',
  PRIMARY KEY (`pum_pu_id`,`pum_u_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Maps users to pools';

-- --------------------------------------------------------

--
-- Table structure for table `private_message`
--

CREATE TABLE IF NOT EXISTS `private_message` (
  `pm_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID of the message',
  `pm_sender_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID of the sender',
  `pm_receiver_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID of the receiver',
  `pm_subject` varchar(100) NOT NULL DEFAULT '' COMMENT 'Subject of the message',
  `pm_pmc_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID of the message content',
  `pm_status` enum('New','Read','Deleted') NOT NULL DEFAULT 'New' COMMENT 'If the message is new, has been read, or is deleted',
  `pm_type` enum('Sent','Received') NOT NULL DEFAULT 'Sent' COMMENT 'Type of message',
  `pm_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date the message was created',
  PRIMARY KEY (`pm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Tracks private messages';

-- --------------------------------------------------------

--
-- Table structure for table `private_message_ban`
--

CREATE TABLE IF NOT EXISTS `private_message_ban` (
  `pmb_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID of the ban',
  `pmb_u_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID of the person doing the banning',
  `pmb_who` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID of the person who is being banned',
  `pmb_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date the person was banned',
  PRIMARY KEY (`pmb_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Tracks those who are banned from messagine someone';

-- --------------------------------------------------------

--
-- Table structure for table `private_message_content`
--

CREATE TABLE IF NOT EXISTS `private_message_content` (
  `pmc_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID of the message content',
  `pmc_content` text NOT NULL COMMENT 'The message content',
  `pmc_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date the message content was created',
  PRIMARY KEY (`pmc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Content for a private message';

-- --------------------------------------------------------

--
-- Table structure for table `private_message_optout`
--

CREATE TABLE IF NOT EXISTS `private_message_optout` (
  `pmo_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID of the optout',
  `pmo_u_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID of the person opting out',
  `pmo_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date user opted out',
  PRIMARY KEY (`pmo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Tracks people who don''t want to receive private messages';

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE IF NOT EXISTS `promotions` (
  `p_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `p_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `p_name` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`p_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `quick_sets_map`
--

CREATE TABLE IF NOT EXISTS `quick_sets_map` (
  `old_id` int(10) unsigned NOT NULL DEFAULT '0',
  `new_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`old_id`,`new_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE IF NOT EXISTS `report` (
  `r_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID of the report',
  `r_u_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID of the user requesting the report',
  `r_rt_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID of the type of report to generate',
  `r_frequency` enum('Weekly','Monthly') NOT NULL DEFAULT 'Weekly' COMMENT 'frequency to generate the report',
  `r_email` varchar(255) DEFAULT NULL COMMENT 'who to send the report to (comma separated), null to send to no one',
  `r_active` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'if the report is active or not',
  `r_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'date the report started',
  PRIMARY KEY (`r_id`),
  UNIQUE KEY `r_u_id` (`r_u_id`,`r_rt_id`),
  KEY `r_u_id_2` (`r_u_id`,`r_rt_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Reports that need to be generated and/or sent out';

-- --------------------------------------------------------

--
-- Table structure for table `report_archive`
--

CREATE TABLE IF NOT EXISTS `report_archive` (
  `ra_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID of the archived report',
  `ra_key` varchar(32) NOT NULL DEFAULT '',
  `ra_u_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID of the user the report was generated for',
  `ra_r_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID of the generated report',
  `ra_title` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '' COMMENT 'title of the archived report',
  `ra_data` text,
  `ra_timeCreated` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ra_id`),
  UNIQUE KEY `ra_key` (`ra_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `report_data`
--

CREATE TABLE IF NOT EXISTS `report_data` (
  `rd_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID for the report_data table',
  `rd_element_key` varchar(32) NOT NULL DEFAULT '' COMMENT 'Key of the element we are logging data for',
  `rd_type` enum('Photo Viewed','Slideshow Viewed','Slideshow Viewed Complete') NOT NULL DEFAULT 'Photo Viewed',
  `rd_ipAddress` varchar(15) NOT NULL DEFAULT '' COMMENT 'IP address of person who created this record',
  `rd_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'DateTime this record was created',
  PRIMARY KEY (`rd_id`),
  KEY `rd_element_key` (`rd_element_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Tracks many different types of data for the reports';

-- --------------------------------------------------------

--
-- Table structure for table `report_type`
--

CREATE TABLE IF NOT EXISTS `report_type` (
  `rt_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID of the report type',
  `rt_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'name of the type of report',
  PRIMARY KEY (`rt_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Types of reports that can be generated';

-- --------------------------------------------------------

--
-- Table structure for table `site_news`
--

CREATE TABLE IF NOT EXISTS `site_news` (
  `sn_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `sn_date` date DEFAULT NULL,
  `sn_headline` varchar(75) NOT NULL DEFAULT '',
  `sn_body` text NOT NULL,
  `sn_archived` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`sn_id`),
  KEY `archived_date` (`sn_archived`,`sn_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tips`
--

CREATE TABLE IF NOT EXISTS `tips` (
  `t_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `t_title` varchar(128) NOT NULL,
  `t_body` text NOT NULL,
  `t_key` varchar(100) NOT NULL,
  `t_status` enum('active','deleted') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`t_id`),
  KEY `t_group` (`t_key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE IF NOT EXISTS `tokens` (
  `t_string` varchar(32) NOT NULL DEFAULT '',
  `t_dateTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`t_string`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `u_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `u_parentId` int(10) unsigned NOT NULL DEFAULT '0',
  `u_key` varchar(32) NOT NULL DEFAULT '',
  `u_username` varchar(16) NOT NULL DEFAULT '',
  `u_password` varchar(32) NOT NULL DEFAULT '',
  `u_email` varchar(75) NOT NULL DEFAULT '',
  `u_nameFirst` varchar(50) DEFAULT NULL,
  `u_nameLast` varchar(50) DEFAULT NULL,
  `u_birthDay` tinyint(2) unsigned zerofill NOT NULL DEFAULT '00',
  `u_birthMonth` tinyint(2) unsigned zerofill NOT NULL DEFAULT '00',
  `u_birthYear` smallint(4) unsigned zerofill NOT NULL DEFAULT '0000',
  `u_address` varchar(200) DEFAULT NULL,
  `u_city` varchar(50) DEFAULT NULL,
  `u_state` varchar(5) DEFAULT NULL,
  `u_zip` varchar(10) DEFAULT NULL,
  `u_country` varchar(75) DEFAULT NULL,
  `u_secret` varchar(50) DEFAULT NULL,
  `u_accountType` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `u_businessName` varchar(200) DEFAULT NULL,
  `u_interest` enum('slideshows','prints','selling','sharing','organizing','archiving','undecided') NOT NULL DEFAULT 'undecided',
  `u_spaceTotal` int(10) unsigned DEFAULT NULL,
  `u_spaceUsed` int(10) unsigned DEFAULT NULL,
  `u_isTrial` tinyint(1) NOT NULL DEFAULT '1',
  `u_dateExpires` date NOT NULL DEFAULT '0000-00-00',
  `u_dateModified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_status` enum('Active','Cancelled','Pending','Disabled','Expired','FotoFlix_Pending','Purged') NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`u_id`),
  KEY `username_password` (`u_username`,`u_password`),
  KEY `u_key` (`u_key`),
  KEY `u_parentId` (`u_parentId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_activation`
--

CREATE TABLE IF NOT EXISTS `user_activation` (
  `ua_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ua_key` varchar(32) NOT NULL DEFAULT '',
  KEY `serial` (`ua_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_activities`
--

CREATE TABLE IF NOT EXISTS `user_activities` (
  `ua_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ua_u_id` int(10) unsigned NOT NULL,
  `ua_element_id` int(10) unsigned NOT NULL,
  `ua_type` enum('newPhoto','newSlideshow','newVideo','newComment','newMessage','newBlogPost','newFriend') DEFAULT NULL,
  `ua_extra_1` varchar(100) DEFAULT NULL,
  `ua_extra_2` varchar(100) DEFAULT NULL,
  `ua_extra_3` varchar(100) DEFAULT NULL,
  `ua_extra_4` varchar(100) DEFAULT NULL,
  `ua_dateCreated` datetime NOT NULL,
  PRIMARY KEY (`ua_id`),
  UNIQUE KEY `ua_u_id_2` (`ua_u_id`,`ua_element_id`,`ua_type`),
  KEY `ua_u_id` (`ua_u_id`,`ua_type`,`ua_element_id`,`ua_dateCreated`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_audit`
--

CREATE TABLE IF NOT EXISTS `user_audit` (
  `ua_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID for audit table',
  `ua_u_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID to users table',
  `ua_reason` enum('Violation') NOT NULL DEFAULT 'Violation' COMMENT 'reason the user was audited',
  `ua_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'date the user was audited',
  PRIMARY KEY (`ua_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Keeps track of when a user is audited';

-- --------------------------------------------------------

--
-- Table structure for table `user_blogs`
--

CREATE TABLE IF NOT EXISTS `user_blogs` (
  `ub_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ub_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ub_name` varchar(64) NOT NULL DEFAULT '',
  `ub_blogId` int(10) unsigned NOT NULL DEFAULT '0',
  `ub_url` varchar(128) DEFAULT NULL,
  `ub_username` varchar(32) NOT NULL DEFAULT '',
  `ub_password` varchar(128) NOT NULL DEFAULT '',
  `ub_endPoint` varchar(75) DEFAULT NULL,
  `ub_type` enum('Blogger','LiveJournal''MovableType','TypePad','WordPress') NOT NULL DEFAULT 'Blogger',
  `ub_status` enum('Active','Deleted') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`ub_id`),
  KEY `ub_u_id` (`ub_u_id`,`ub_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_blog_entries`
--

CREATE TABLE IF NOT EXISTS `user_blog_entries` (
  `ube_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ube_u_id` int(10) unsigned NOT NULL,
  `ube_permaLink` varchar(255) NOT NULL,
  `ube_subject` varchar(255) NOT NULL,
  `ube_body` text,
  `ube_comments` smallint(5) unsigned NOT NULL,
  `ube_datePosted` datetime NOT NULL,
  `ube_dateModified` datetime NOT NULL,
  `ube_dateCreated` datetime NOT NULL,
  `ube_status` enum('Active','Deleted') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`ube_id`),
  KEY `ube_u_id` (`ube_u_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_cancellations`
--

CREATE TABLE IF NOT EXISTS `user_cancellations` (
  `uc_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uc_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `uc_confirmationId` varchar(12) NOT NULL DEFAULT '',
  `uc_email` varchar(50) NOT NULL DEFAULT '',
  `uc_dateEffective` date NOT NULL DEFAULT '0000-00-00',
  `uc_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uc_id`),
  KEY `uc_u_id` (`uc_u_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_ci_credentials`
--

CREATE TABLE IF NOT EXISTS `user_ci_credentials` (
  `ucc_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ucc_username` varchar(32) NOT NULL DEFAULT '',
  `ucc_password` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`ucc_u_id`,`ucc_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_ci_photos`
--

CREATE TABLE IF NOT EXISTS `user_ci_photos` (
  `ucp_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ucp_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ucp_up_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ucp_title` varchar(128) NOT NULL DEFAULT '',
  `ucp_description` varchar(255) NOT NULL DEFAULT '',
  `ucp_category` varchar(128) NOT NULL DEFAULT '',
  `ucp_subCategory` varchar(128) DEFAULT NULL,
  `ucp_release` varchar(32) DEFAULT NULL,
  `ucp_timestamp` bigint(20) DEFAULT NULL,
  `ucp_timezone` varchar(128) DEFAULT NULL,
  `ucp_country` varchar(128) DEFAULT NULL,
  `ucp_state` varchar(128) DEFAULT NULL,
  `ucp_city` varchar(128) DEFAULT NULL,
  `ucp_keywords` varchar(255) NOT NULL DEFAULT '',
  `ucp_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ucp_status` enum('deleted','error','pending','processed') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`ucp_id`),
  UNIQUE KEY `uci_u_id` (`ucp_u_id`,`ucp_up_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_dvds`
--

CREATE TABLE IF NOT EXISTS `user_dvds` (
  `ud_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ud_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ud_eod_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ud_name` varchar(50) DEFAULT NULL,
  `ud_dateCreated` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`ud_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_earn_space`
--

CREATE TABLE IF NOT EXISTS `user_earn_space` (
  `ues_id` int(11) NOT NULL AUTO_INCREMENT,
  `ues_u_id` int(11) NOT NULL DEFAULT '0',
  `ues_reference` varchar(32) NOT NULL DEFAULT '',
  `ues_nameFirst` varchar(50) NOT NULL DEFAULT '',
  `ues_nameLast` varchar(50) NOT NULL DEFAULT '',
  `ues_email` varchar(75) NOT NULL DEFAULT '',
  `ues_status` enum('pending','accepted','invalid') NOT NULL DEFAULT 'pending',
  `ues_dateModified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ues_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ues_id`),
  UNIQUE KEY `ues_reference` (`ues_reference`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_facebook_map`
--

CREATE TABLE IF NOT EXISTS `user_facebook_map` (
  `u_id` int(10) unsigned NOT NULL,
  `facebook_id` int(10) unsigned NOT NULL,
  `facebook_session` varchar(255) NOT NULL,
  KEY `u_id` (`u_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_fotoflix`
--

CREATE TABLE IF NOT EXISTS `user_fotoflix` (
  `uf_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uf_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `uf_tags` varchar(255) DEFAULT NULL,
  `uf_autoplay` enum('Y','N') NOT NULL DEFAULT 'N',
  `uf_fastflix` varchar(32) NOT NULL DEFAULT '',
  `uf_template` varchar(35) NOT NULL DEFAULT 'default',
  `uf_delay` smallint(5) unsigned NOT NULL DEFAULT '3000',
  `uf_music` varchar(50) DEFAULT NULL,
  `uf_name` varchar(75) NOT NULL DEFAULT '',
  `uf_createdBy` varchar(25) DEFAULT NULL,
  `uf_description` text,
  `uf_fotoCount` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `uf_length` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uf_views` int(10) unsigned NOT NULL DEFAULT '0',
  `uf_viewsComplete` int(10) unsigned NOT NULL DEFAULT '0',
  `uf_public` enum('Y','N') NOT NULL DEFAULT 'N',
  `uf_privacy` varchar(3) NOT NULL DEFAULT '',
  `uf_publicOrder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uf_dateModified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uf_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uf_status` enum('Active','Deleted','Suspended') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`uf_id`),
  KEY `uf_u_id` (`uf_u_id`,`uf_status`,`uf_privacy`),
  KEY `status_public` (`uf_status`,`uf_privacy`),
  KEY `uf_fastflix` (`uf_fastflix`,`uf_status`,`uf_privacy`),
  FULLTEXT KEY `uf_tags` (`uf_tags`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_fotoflix_data`
--

CREATE TABLE IF NOT EXISTS `user_fotoflix_data` (
  `ufd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ufd_uf_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ufd_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ufd_up_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ufd_delay` smallint(5) unsigned NOT NULL DEFAULT '3000',
  `ufd_isTitle` enum('Y','N') NOT NULL DEFAULT 'N',
  `ufd_name` varchar(50) DEFAULT NULL,
  `ufd_description` varchar(200) DEFAULT NULL,
  `ufd_link` varchar(150) DEFAULT NULL,
  `ufd_linkTarget` enum('T','B') DEFAULT NULL,
  `ufd_order` smallint(6) NOT NULL DEFAULT '0',
  `ufd_inTimeline` enum('Y','N') NOT NULL DEFAULT 'Y',
  `ufd_dateModified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ufd_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ufd_status` enum('Active','Deleted') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`ufd_id`),
  UNIQUE KEY `flix_foto_unique` (`ufd_uf_id`,`ufd_up_id`,`ufd_status`),
  KEY `ufd_uf_id_order` (`ufd_uf_id`,`ufd_order`,`ufd_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_fotos`
--

CREATE TABLE IF NOT EXISTS `user_fotos` (
  `up_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `up_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `up_key` varchar(32) NOT NULL DEFAULT '',
  `up_tags` varchar(255) DEFAULT NULL,
  `up_name` varchar(75) DEFAULT NULL,
  `up_size` int(10) unsigned NOT NULL DEFAULT '0',
  `up_width` smallint(5) unsigned NOT NULL DEFAULT '0',
  `up_height` smallint(5) unsigned NOT NULL DEFAULT '0',
  `up_description` text,
  `up_original_path` varchar(128) NOT NULL DEFAULT '',
  `up_web_path` varchar(128) NOT NULL DEFAULT '',
  `up_flix_path` varchar(128) NOT NULL DEFAULT '',
  `up_thumb_path` varchar(128) NOT NULL DEFAULT '',
  `up_l_ids` varchar(255) DEFAULT NULL,
  `up_rotation` varchar(3) NOT NULL DEFAULT '0',
  `up_camera_make` varchar(50) DEFAULT NULL,
  `up_camera_model` varchar(50) DEFAULT NULL,
  `up_views` bigint(20) unsigned NOT NULL DEFAULT '0',
  `up_status` enum('active','pending','deleted','violation') NOT NULL DEFAULT 'active',
  `up_public` enum('Y','N') NOT NULL DEFAULT 'N',
  `up_creative_commons` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `up_privacy` int(10) unsigned NOT NULL DEFAULT '1',
  `up_taken_at` int(10) unsigned DEFAULT NULL,
  `up_created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `up_modified_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `up_history` text,
  PRIMARY KEY (`up_id`),
  UNIQUE KEY `up_key` (`up_key`,`up_status`),
  KEY `up_u_id` (`up_u_id`,`up_status`),
  KEY `status_public` (`up_status`,`up_privacy`,`up_u_id`),
  FULLTEXT KEY `up_tags` (`up_tags`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_fotos_dynamic`
--

CREATE TABLE IF NOT EXISTS `user_fotos_dynamic` (
  `ufd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ufd_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ufd_up_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ufd_source` varchar(128) NOT NULL DEFAULT '',
  `ufd_width` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ufd_height` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ufd_ipAddress` varchar(15) DEFAULT NULL,
  `ufd_dateCreated` date NOT NULL DEFAULT '0000-00-00',
  `ufd_dateAccessed` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`ufd_id`),
  KEY `ufd_up_id` (`ufd_up_id`),
  KEY `ufd_u_id` (`ufd_u_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_friends`
--

CREATE TABLE IF NOT EXISTS `user_friends` (
  `uf_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `uf_friendId` int(10) unsigned NOT NULL DEFAULT '0',
  `uf_status` enum('Requested','Confirmed','Declined') NOT NULL DEFAULT 'Requested',
  PRIMARY KEY (`uf_u_id`,`uf_friendId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_group_map`
--

CREATE TABLE IF NOT EXISTS `user_group_map` (
  `u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `g_id` int(10) unsigned NOT NULL DEFAULT '0',
  `dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`u_id`,`g_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_inbox`
--

CREATE TABLE IF NOT EXISTS `user_inbox` (
  `ui_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ui_replyTo` int(10) unsigned NOT NULL DEFAULT '0',
  `ui_u_id` int(10) unsigned NOT NULL,
  `ui_senderId` int(10) unsigned NOT NULL,
  `ui_subject` varchar(100) NOT NULL,
  `ui_message` text,
  `ui_dateCreated` datetime NOT NULL,
  `ui_dateModified` datetime NOT NULL,
  `ui_status` enum('Unread','Read','Deleted') NOT NULL DEFAULT 'Unread',
  PRIMARY KEY (`ui_id`),
  KEY `ui_u_id` (`ui_u_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_incompletes`
--

CREATE TABLE IF NOT EXISTS `user_incompletes` (
  `u_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `u_key` varchar(32) NOT NULL DEFAULT '',
  `u_username` varchar(16) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `u_password` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `u_email` varchar(75) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `u_birthDay` tinyint(2) unsigned zerofill NOT NULL DEFAULT '00',
  `u_birthMonth` tinyint(2) unsigned zerofill NOT NULL DEFAULT '00',
  `u_birthYear` smallint(4) unsigned zerofill NOT NULL DEFAULT '0000',
  `u_secret` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `u_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`u_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_incomplete_responses`
--

CREATE TABLE IF NOT EXISTS `user_incomplete_responses` (
  `uir_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `uir_response` varchar(255) NOT NULL DEFAULT '',
  `uir_customResponse` text,
  `uir_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uir_u_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_mp3s`
--

CREATE TABLE IF NOT EXISTS `user_mp3s` (
  `um_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `um_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `um_name` varchar(50) NOT NULL DEFAULT '',
  `um_length` smallint(5) unsigned NOT NULL DEFAULT '0',
  `um_size` int(10) unsigned NOT NULL DEFAULT '0',
  `um_path` varchar(50) NOT NULL DEFAULT '',
  `um_status` enum('Active','Deleted') NOT NULL DEFAULT 'Active',
  `um_created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`um_id`),
  KEY `um_u_id` (`um_u_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_pages`
--

CREATE TABLE IF NOT EXISTS `user_pages` (
  `p_u_id` int(11) NOT NULL DEFAULT '0',
  `p_password` varchar(16) DEFAULT NULL,
  `p_description` varchar(255) DEFAULT NULL,
  `p_colors` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`p_u_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

CREATE TABLE IF NOT EXISTS `user_permissions` (
  `up_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `up_usa_id` int(10) unsigned NOT NULL DEFAULT '0',
  `up_action` varchar(64) NOT NULL DEFAULT '',
  `up_permission` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`up_id`),
  UNIQUE KEY `up_usa_id` (`up_usa_id`,`up_action`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_permission_types`
--

CREATE TABLE IF NOT EXISTS `user_permission_types` (
  `upt_name` varchar(16) NOT NULL DEFAULT '',
  `upt_defaultValue` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `upt_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`upt_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_prefs`
--

CREATE TABLE IF NOT EXISTS `user_prefs` (
  `up_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `up_name` varchar(16) NOT NULL DEFAULT '',
  `up_value` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY (`up_u_id`,`up_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE IF NOT EXISTS `user_profiles` (
  `p_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `p_profile` text,
  PRIMARY KEY (`p_u_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_quick_sets`
--

CREATE TABLE IF NOT EXISTS `user_quick_sets` (
  `uqs_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uqs_p_id` int(10) unsigned NOT NULL DEFAULT '0',
  `uqs_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `uqs_name` varchar(32) NOT NULL DEFAULT '',
  `uqs_tags` varchar(255) DEFAULT NULL,
  `uqs_icon` varchar(128) DEFAULT NULL,
  `uqs_public` enum('Y','N') NOT NULL DEFAULT 'Y',
  `uqs_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uqs_id`,`uqs_u_id`),
  KEY `parent_user` (`uqs_p_id`,`uqs_u_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_session`
--

CREATE TABLE IF NOT EXISTS `user_session` (
  `us_id` int(10) unsigned NOT NULL,
  `us_hash` char(13) NOT NULL DEFAULT '',
  `us_ud_id` int(10) unsigned NOT NULL DEFAULT '0',
  `us_timeAccessed` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `us_timeCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `us_hash` (`us_hash`),
  KEY `us_id` (`us_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_session_data`
--

CREATE TABLE IF NOT EXISTS `user_session_data` (
  `us_id` int(10) unsigned NOT NULL DEFAULT '0',
  `usd_name` varchar(25) NOT NULL DEFAULT '',
  `usd_value` varchar(50) DEFAULT NULL,
  UNIQUE KEY `id_name` (`us_id`,`usd_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_slideshows`
--

CREATE TABLE IF NOT EXISTS `user_slideshows` (
  `us_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `us_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `us_key` varchar(32) NOT NULL DEFAULT '',
  `us_name` varchar(128) NOT NULL DEFAULT '',
  `us_tags` varchar(255) DEFAULT NULL,
  `us_elements` longtext NOT NULL,
  `us_settings` text NOT NULL,
  `us_type` enum('site','slideshow') NOT NULL DEFAULT 'slideshow',
  `us_order` smallint(6) unsigned NOT NULL DEFAULT '0',
  `us_fotoCount` smallint(6) unsigned NOT NULL DEFAULT '0',
  `us_length` smallint(6) unsigned NOT NULL DEFAULT '0',
  `us_views` int(11) unsigned NOT NULL DEFAULT '0',
  `us_viewsComplete` int(11) unsigned NOT NULL DEFAULT '0',
  `us_privacy` int(10) unsigned NOT NULL DEFAULT '1',
  `us_dateModified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `us_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `us_status` enum('Active','Pending','Deleted') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`us_id`),
  KEY `us_key` (`us_key`),
  KEY `us_u_id` (`us_u_id`),
  FULLTEXT KEY `us_tags` (`us_tags`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_slideshow_elements`
--

CREATE TABLE IF NOT EXISTS `user_slideshow_elements` (
  `use_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `use_us_id` int(10) unsigned NOT NULL DEFAULT '0',
  `use_group_id` int(10) unsigned NOT NULL DEFAULT '0',
  `use_name` varchar(32) NOT NULL DEFAULT '',
  `use_value` varchar(255) DEFAULT NULL,
  `use_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `use_status` enum('active','deleted') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`use_id`),
  KEY `use_us_id` (`use_us_id`,`use_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_slideshow_schedule`
--

CREATE TABLE IF NOT EXISTS `user_slideshow_schedule` (
  `uss_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uss_us_id` int(10) unsigned NOT NULL DEFAULT '0',
  `uss_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `uss_startDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uss_endDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uss_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uss_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_slideshow_settings`
--

CREATE TABLE IF NOT EXISTS `user_slideshow_settings` (
  `uss_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uss_us_id` int(10) unsigned NOT NULL DEFAULT '0',
  `uss_group_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uss_name` varchar(32) NOT NULL DEFAULT '',
  `uss_value` varchar(64) DEFAULT NULL,
  `uss_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uss_status` enum('active','deleted') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`uss_id`),
  KEY `uss_us_id` (`uss_us_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_slideshow_themes`
--

CREATE TABLE IF NOT EXISTS `user_slideshow_themes` (
  `ust_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ust_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ust_name` varchar(64) NOT NULL DEFAULT '',
  `ust_settings` text NOT NULL,
  `ust_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ust_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ust_id`),
  KEY `ust_u_id` (`ust_u_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_status`
--

CREATE TABLE IF NOT EXISTS `user_status` (
  `us_id` int(10) unsigned NOT NULL DEFAULT '0',
  `us_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `us_message` varchar(100) NOT NULL DEFAULT '',
  `us_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`us_id`),
  KEY `us_u_id` (`us_u_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Logs each action of a user';

-- --------------------------------------------------------

--
-- Table structure for table `user_subscriptions`
--

CREATE TABLE IF NOT EXISTS `user_subscriptions` (
  `s_key` varchar(32) NOT NULL DEFAULT '',
  `s_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `s_userId` int(10) unsigned NOT NULL DEFAULT '0',
  `s_username` varchar(16) DEFAULT NULL,
  `s_email` varchar(255) NOT NULL DEFAULT '',
  `s_method` enum('push','pull') NOT NULL DEFAULT 'push',
  `s_status` enum('active','deleted','blacklist') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`s_key`),
  UNIQUE KEY `s_u_id` (`s_u_id`,`s_email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_subscription_data`
--

CREATE TABLE IF NOT EXISTS `user_subscription_data` (
  `sd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sd_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sd_elementType` enum('Photo_Public','Slideshow_Public') NOT NULL DEFAULT 'Photo_Public',
  `sd_element_id` varchar(32) NOT NULL DEFAULT '',
  `sd_thumbnail` varchar(64) DEFAULT NULL,
  `sd_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sd_dateId` varchar(8) NOT NULL DEFAULT '',
  `sd_status` enum('active','deleted') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`sd_id`),
  UNIQUE KEY `sd_u_id` (`sd_u_id`,`sd_elementType`,`sd_element_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_sub_accounts`
--

CREATE TABLE IF NOT EXISTS `user_sub_accounts` (
  `usa_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usa_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `usa_username` varchar(16) NOT NULL DEFAULT '',
  `usa_password` varchar(16) NOT NULL DEFAULT '',
  `usa_email` varchar(128) DEFAULT NULL,
  `usa_dateLastLogin` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `usa_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `usa_status` enum('active','deleted') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`usa_id`),
  KEY `usa_u_id` (`usa_u_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_tags`
--

CREATE TABLE IF NOT EXISTS `user_tags` (
  `ut_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ut_tag` varchar(32) NOT NULL DEFAULT '',
  `ut_count` int(11) NOT NULL DEFAULT '0',
  `ut_weight` float(3,2) unsigned NOT NULL DEFAULT '0.00',
  `ut_random` smallint(6) NOT NULL DEFAULT '0',
  `ut_status` enum('public','private') NOT NULL DEFAULT 'private',
  PRIMARY KEY (`ut_u_id`,`ut_tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_tags_geo`
--

CREATE TABLE IF NOT EXISTS `user_tags_geo` (
  `utg_u_id` int(10) unsigned NOT NULL,
  `utg_tag` varchar(255) NOT NULL,
  `utg_latitude` varchar(255) NOT NULL,
  `utg_longitude` varchar(255) NOT NULL,
  PRIMARY KEY (`utg_u_id`,`utg_tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_tag_sibling`
--

CREATE TABLE IF NOT EXISTS `user_tag_sibling` (
  `uts_u_id` int(11) NOT NULL,
  `uts_ut_tag` varchar(255) NOT NULL,
  `uts_sibling` varchar(255) NOT NULL,
  UNIQUE KEY `user_tag` (`uts_u_id`,`uts_ut_tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_template_map`
--

CREATE TABLE IF NOT EXISTS `user_template_map` (
  `u_id` int(11) NOT NULL DEFAULT '0',
  `ft_id` smallint(6) NOT NULL DEFAULT '0',
  UNIQUE KEY `UNIQUE` (`u_id`,`ft_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_tokens`
--

CREATE TABLE IF NOT EXISTS `user_tokens` (
  `ut_token` varchar(64) NOT NULL DEFAULT '',
  `ut_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ut_sess_hash` varchar(13) DEFAULT NULL,
  `ut_expires` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ut_token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_toolbox`
--

CREATE TABLE IF NOT EXISTS `user_toolbox` (
  `t_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `t_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `t_itemId` int(10) unsigned NOT NULL DEFAULT '0',
  `t_itemType` enum('foto','flix') NOT NULL DEFAULT 'foto',
  `t_itemOrder` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`t_id`),
  KEY `t_u_id` (`t_u_id`,`t_itemType`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_videos`
--

CREATE TABLE IF NOT EXISTS `user_videos` (
  `v_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `v_u_id` int(10) unsigned NOT NULL DEFAULT '0',
  `v_key` varchar(32) NOT NULL DEFAULT '',
  `v_name` varchar(64) NOT NULL DEFAULT '',
  `v_description` varchar(255) DEFAULT NULL,
  `v_tags` varchar(255) DEFAULT NULL,
  `v_path` varchar(128) NOT NULL DEFAULT '',
  `v_screen75x75` varchar(128) NOT NULL DEFAULT '',
  `v_screen115x50` varchar(128) NOT NULL DEFAULT '',
  `v_screen150x100` varchar(128) NOT NULL DEFAULT '',
  `v_screen400x300` varchar(128) NOT NULL DEFAULT '',
  `v_length` int(10) unsigned NOT NULL DEFAULT '0',
  `v_views` int(10) unsigned NOT NULL DEFAULT '0',
  `v_privacy` smallint(5) unsigned NOT NULL DEFAULT '0',
  `v_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `v_status` enum('active','deleted','voilation') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`v_id`),
  UNIQUE KEY `v_key` (`v_key`),
  KEY `v_u_id` (`v_u_id`),
  FULLTEXT KEY `v_tags` (`v_tags`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_violations`
--

CREATE TABLE IF NOT EXISTS `user_violations` (
  `uv_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'user violations id',
  `uv_u_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'id of user who commited the violation',
  `uv_up_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'id of the photo in violation',
  `uv_dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'date the violation occurred',
  PRIMARY KEY (`uv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Keeps track of the violations for a user';

INSERT INTO `idat` (`QualifiedKey`, `CurrentID`) VALUES
('fotoflix.user_session', 0),
('fotobox.image_id', 0),
('fotoflix.ecom_session', 0),
('group_invite', 0),
('fotoflix.preview', 0),
('fotobox.image_key', 0),
('fotoflix.user_id', 0);

