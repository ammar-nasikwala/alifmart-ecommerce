-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2015 at 07:19 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `Company-Name`
--

-- --------------------------------------------------------

--
-- Table structure for table `banner_mast`
--

CREATE TABLE IF NOT EXISTS `banner_mast` (
  `banner_id` int(11) NOT NULL AUTO_INCREMENT,
  `banner_alt_text` varchar(50) DEFAULT NULL,
  `banner_img` longblob,
  `banner_sort` float DEFAULT NULL,
  `banner_status` tinyint(4) DEFAULT NULL,
  `banner_target_url` varchar(100) DEFAULT NULL,
  `banner_target_window` varchar(10) DEFAULT NULL,
  `banner_position` varchar(2) DEFAULT NULL,
  `banner_img_cp` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`banner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `brand_mast`
--

CREATE TABLE IF NOT EXISTS `brand_mast` (
  `brand_id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(100) DEFAULT NULL,
  `brand_heading` varchar(100) DEFAULT NULL,
  `page_title` varchar(100) DEFAULT NULL,
  `meta_key` varchar(500) DEFAULT NULL,
  `meta_desc` varchar(2000) DEFAULT NULL,
  `brand_desc` varchar(4000) DEFAULT NULL,
  `brand_img` varchar(100) DEFAULT NULL,
  `brand_status` tinyint(4) DEFAULT NULL,
  `brand_sort` float DEFAULT NULL,
  PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `cart_details`
--

CREATE TABLE IF NOT EXISTS `cart_details` (
  `session_id` varchar(50) DEFAULT NULL,
  `bill_name` varchar(30) DEFAULT NULL,
  `bill_state` varchar(30) DEFAULT NULL,
  `bill_email` varchar(100) DEFAULT NULL,
  `bill_add1` varchar(50) DEFAULT NULL,
  `bill_add2` varchar(50) DEFAULT NULL,
  `bill_city` varchar(30) DEFAULT NULL,
  `bill_postcode` varchar(10) DEFAULT NULL,
  `bill_country` varchar(30) DEFAULT NULL,
  `bill_tel` varchar(30) DEFAULT NULL,
  `bill_mob` varchar(30) DEFAULT NULL,
  `ship_name` varchar(30) DEFAULT NULL,
  `ship_state` varchar(30) DEFAULT NULL,
  `ship_email` varchar(100) DEFAULT NULL,
  `ship_add1` varchar(50) DEFAULT NULL,
  `ship_add2` varchar(50) DEFAULT NULL,
  `ship_city` varchar(30) DEFAULT NULL,
  `ship_postcode` varchar(10) DEFAULT NULL,
  `ship_country` varchar(30) DEFAULT NULL,
  `ship_tel` varchar(30) DEFAULT NULL,
  `ship_mob` varchar(30) DEFAULT NULL,
  `ord_instruct` varchar(1000) DEFAULT NULL,
  `cart_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE IF NOT EXISTS `cart_items` (
  `cart_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) DEFAULT NULL,
  `session_id` varchar(50) DEFAULT NULL,
  `cart_datetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `item_name` varchar(200) DEFAULT NULL,
  `item_stock_no` varchar(100) DEFAULT NULL,
  `item_thumb` longblob,
  `cart_qty` int(11) DEFAULT NULL,
  `cart_price` decimal(6,2) DEFAULT NULL,
  `sup_id` int(11) DEFAULT NULL,
  `sup_name` varchar(50) DEFAULT NULL,
  `tax_id` int(11) NOT NULL,
  `tax_name` varchar(30) DEFAULT NULL,
  `tax_percent` decimal(6,2) NOT NULL,
  `tax_value` decimal(10,2) NOT NULL,
  `cart_price_tax` decimal(10,2) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `item_wish` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`cart_item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=89 ;

-- --------------------------------------------------------

--
-- Table structure for table `cart_summary`
--

CREATE TABLE IF NOT EXISTS `cart_summary` (
  `session_id` varchar(50) DEFAULT NULL,
  `way_billl_no` varchar(50) DEFAULT NULL,
  `cart_datetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `item_total` decimal(10,2) DEFAULT NULL,
  `shipping_charges` decimal(6,2) DEFAULT NULL,
  `ord_total` decimal(10,2) DEFAULT NULL,
  `vat_percent` decimal(6,2) DEFAULT NULL,
  `vat_value` decimal(6,2) DEFAULT NULL,
  `item_count` int(11) DEFAULT NULL,
  `pay_method` varchar(5) DEFAULT NULL,
  `pay_method_name` varchar(100) DEFAULT NULL,
  `ord_id` int(11) DEFAULT NULL,
  `ord_date` datetime DEFAULT NULL,
  `pay_status` varchar(20) DEFAULT NULL,
  `delivery_status` varchar(20) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` varchar(2) DEFAULT NULL,
  `ord_no` varchar(15) DEFAULT NULL,
  `pg_status` varchar(30) DEFAULT NULL,
  `pg_txnid` varchar(30) DEFAULT NULL,
  `pkg_weight` float DEFAULT NULL,
  `pkg_height` float DEFAULT NULL,
  `pkg_width` float DEFAULT NULL,
  `pkg_depth` float DEFAULT NULL,
  PRIMARY KEY (`cart_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=66 ;

-- --------------------------------------------------------

--
-- Table structure for table `city_mast`
--

CREATE TABLE IF NOT EXISTS `city_mast` (
  `city_id` int(11) NOT NULL,
  `city_name` varchar(50) NOT NULL,
  `state_name` varchar(30) NOT NULL,
  `state_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='List of all Indian Cities';

-- --------------------------------------------------------

--
-- Table structure for table `cms_pages`
--

CREATE TABLE IF NOT EXISTS `cms_pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_type` varchar(3) DEFAULT NULL,
  `page_name` varchar(45) DEFAULT NULL,
  `page_heading` varchar(200) DEFAULT NULL,
  `page_title` varchar(200) DEFAULT NULL,
  `meta_key` varchar(1000) DEFAULT NULL,
  `meta_desc` varchar(2000) DEFAULT NULL,
  `middle_panel` text,
  `page_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `configuration`
--

CREATE TABLE IF NOT EXISTS `configuration` (
  `c_id` decimal(10,0) NOT NULL DEFAULT '0',
  `admin_user` varchar(20) DEFAULT NULL,
  `admin_pwd` varchar(20) DEFAULT NULL,
  `smtp_server` varchar(100) DEFAULT NULL,
  `vat` decimal(5,2) NOT NULL,
  `Admin_email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`c_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE IF NOT EXISTS `levels` (
  `level_id` int(11) NOT NULL AUTO_INCREMENT,
  `level_parent` int(11) DEFAULT NULL,
  `level_name` varchar(50) DEFAULT NULL,
  `page_title` varchar(100) DEFAULT NULL,
  `meta_key` text,
  `meta_desc` text,
  `level_desc` tinytext,
  `level_sort` float DEFAULT NULL,
  `level_status` tinyint(4) DEFAULT NULL,
  `level_image` longblob,
  PRIMARY KEY (`level_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=65 ;

-- --------------------------------------------------------

--
-- Table structure for table `levels_props`
--

CREATE TABLE IF NOT EXISTS `levels_props` (
  `level_id` int(11) NOT NULL,
  `prop_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Category-wise property';

-- --------------------------------------------------------

--
-- Table structure for table `logging`
--

CREATE TABLE IF NOT EXISTS `logging` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `log_page` varchar(50) DEFAULT NULL,
  `session_id` varchar(30) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `log_activity` varchar(500) DEFAULT NULL,
  `log_event` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `member_mast`
--

CREATE TABLE IF NOT EXISTS `member_mast` (
  `memb_id` int(11) NOT NULL,
  `memb_email` varchar(50) NOT NULL,
  `memb_pwd` varchar(10) NOT NULL,
  `memb_title` varchar(5) DEFAULT NULL,
  `memb_fname` varchar(20) NOT NULL,
  `memb_sname` varchar(20) NOT NULL,
  `memb_tel` varchar(50) DEFAULT NULL,
  `memb_fax` varchar(15) DEFAULT NULL,
  `memb_add1` varchar(45) NOT NULL,
  `memb_add2` varchar(45) DEFAULT NULL,
  `memb_city` varchar(45) DEFAULT NULL,
  `memb_county` varchar(45) DEFAULT NULL,
  `memb_postcode` varchar(10) NOT NULL,
  `memb_state` varchar(50) NOT NULL,
  `memb_country` varchar(30) DEFAULT NULL,
  `memb_status` tinyint(4) DEFAULT NULL,
  `memb_contact` varchar(30) DEFAULT NULL,
  `memb_allowonaccpymt` tinyint(4) DEFAULT NULL,
  `memb_act_id` varchar(100) DEFAULT NULL,
  `memb_act_status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`memb_id`),
  UNIQUE KEY `memb_email_UNIQUE` (`memb_email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ord_details`
--

CREATE TABLE IF NOT EXISTS `ord_details` (
  `session_id` varchar(50) DEFAULT NULL,
  `bill_name` varchar(30) DEFAULT NULL,
  `bill_state` varchar(30) DEFAULT NULL,
  `bill_email` varchar(100) DEFAULT NULL,
  `bill_add1` varchar(50) DEFAULT NULL,
  `bill_add2` varchar(50) DEFAULT NULL,
  `bill_city` varchar(30) DEFAULT NULL,
  `bill_postcode` varchar(10) DEFAULT NULL,
  `bill_country` varchar(30) DEFAULT NULL,
  `bill_tel` varchar(30) DEFAULT NULL,
  `bill_mob` varchar(30) DEFAULT NULL,
  `ship_name` varchar(30) DEFAULT NULL,
  `ship_state` varchar(30) DEFAULT NULL,
  `ship_email` varchar(100) DEFAULT NULL,
  `ship_add1` varchar(50) DEFAULT NULL,
  `ship_add2` varchar(50) DEFAULT NULL,
  `ship_city` varchar(30) DEFAULT NULL,
  `ship_postcode` varchar(10) DEFAULT NULL,
  `ship_country` varchar(30) DEFAULT NULL,
  `ship_tel` varchar(30) DEFAULT NULL,
  `ship_mob` varchar(30) DEFAULT NULL,
  `ord_instruct` varchar(1000) DEFAULT NULL,
  `cart_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ord_items`
--

CREATE TABLE IF NOT EXISTS `ord_items` (
  `cart_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) DEFAULT NULL,
  `session_id` varchar(50) DEFAULT NULL,
  `cart_datetime` datetime DEFAULT NULL,
  `item_name` varchar(200) DEFAULT NULL,
  `item_stock_no` varchar(100) DEFAULT NULL,
  `item_thumb` longblob,
  `cart_qty` int(11) DEFAULT NULL,
  `cart_price` decimal(6,2) DEFAULT NULL,
  `sup_id` int(11) DEFAULT NULL,
  `sup_name` varchar(50) DEFAULT NULL,
  `tax_id` int(11) NOT NULL,
  `tax_name` varchar(30) DEFAULT NULL,
  `tax_percent` decimal(6,2) NOT NULL,
  `tax_value` decimal(10,2) NOT NULL,
  `cart_price_tax` decimal(10,2) NOT NULL,
  `cart_id` int(11) NOT NULL,
  PRIMARY KEY (`cart_item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=88 ;

-- --------------------------------------------------------

--
-- Table structure for table `ord_summary`
--

CREATE TABLE IF NOT EXISTS `ord_summary` (
  `session_id` varchar(50) DEFAULT NULL,
  `way_billl_no` varchar(50) DEFAULT NULL COMMENT 'Way bill number of shipment',
  `cart_datetime` datetime DEFAULT NULL,
  `item_total` decimal(10,2) DEFAULT NULL,
  `shipping_charges` decimal(6,2) DEFAULT NULL,
  `ord_total` decimal(10,2) DEFAULT NULL,
  `vat_percent` decimal(6,2) DEFAULT NULL,
  `vat_value` decimal(6,2) DEFAULT NULL,
  `item_count` int(11) DEFAULT NULL,
  `pay_method` varchar(5) DEFAULT NULL,
  `pay_method_name` varchar(100) DEFAULT NULL,
  `ord_id` int(11) DEFAULT NULL,
  `ord_date` datetime DEFAULT NULL,
  `pay_status` varchar(20) DEFAULT NULL,
  `delivery_status` varchar(20) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `cart_id` int(11) NOT NULL,
  `user_type` varchar(2) DEFAULT NULL,
  `ord_no` varchar(15) DEFAULT NULL,
  `pg_status` varchar(30) DEFAULT NULL,
  `pg_txnid` varchar(30) DEFAULT NULL,
  `pkg_weight` float DEFAULT NULL,
  `pkg_height` float DEFAULT NULL,
  `pkg_width` float DEFAULT NULL,
  `pkg_depth` float DEFAULT NULL,
  PRIMARY KEY (`cart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ord_waybill`
--

CREATE TABLE IF NOT EXISTS `ord_waybill` (
  `way_bill_no` int(11) NOT NULL,
  `ord_id` int(11) DEFAULT NULL,
  `in_use` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pay_method`
--

CREATE TABLE IF NOT EXISTS `pay_method` (
  `pay_id` int(11) NOT NULL AUTO_INCREMENT,
  `pay_code` varchar(5) NOT NULL,
  `pay_name` varchar(100) NOT NULL,
  `pay_sort` float NOT NULL,
  `pay_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`pay_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `prod_cats`
--

CREATE TABLE IF NOT EXISTS `prod_cats` (
  `prod_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  UNIQUE KEY `unq_prod_level` (`prod_id`,`level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `prod_mast`
--

CREATE TABLE IF NOT EXISTS `prod_mast` (
  `prod_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_title` varchar(200) DEFAULT NULL,
  `meta_key` text,
  `meta_desc` text,
  `prod_stockno` varchar(25) DEFAULT NULL,
  `prod_name` varchar(200) NOT NULL,
  `prod_briefdesc` text,
  `prod_detaildesc` text,
  `prod_ourprice` float NOT NULL,
  `prod_offerprice` float DEFAULT NULL,
  `prod_effectiveprice` float DEFAULT NULL,
  `prod_pricestatus` char(10) DEFAULT NULL,
  `prod_thumb1` longblob,
  `prod_large1` longblob,
  `prod_thumb2` longblob,
  `prod_large2` longblob,
  `prod_thumb3` longblob,
  `prod_large3` longblob,
  `prod_thumb4` longblob,
  `prod_large4` longblob,
  `prod_sort` float DEFAULT NULL,
  `prod_status` tinyint(4) DEFAULT NULL,
  `prod_bestseller` tinyint(4) DEFAULT NULL,
  `prod_bsort` float DEFAULT NULL,
  `prod_new` tinyint(4) DEFAULT NULL,
  `prod_nsort` float DEFAULT NULL,
  `level1_id` int(11) DEFAULT '0',
  `level2_id` int(11) DEFAULT '0',
  `level3_id` int(11) DEFAULT '0',
  `is_vat` tinyint(4) DEFAULT '0',
  `cross_cell_item1` varchar(30) DEFAULT NULL,
  `cross_cell_item2` varchar(30) DEFAULT NULL,
  `cross_cell_item3` varchar(30) DEFAULT NULL,
  `prod_free_text` text,
  `prod_osort` int(11) DEFAULT NULL,
  `prod_weight` float DEFAULT NULL,
  `sell_online` tinyint(4) DEFAULT '0',
  `prod_date` datetime DEFAULT NULL,
  `prod_thumb_display` tinyint(4) DEFAULT NULL,
  `prod_brand_id` int(11) DEFAULT NULL,
  `prod_url` varchar(100) DEFAULT NULL,
  `level_parent` int(11) DEFAULT NULL,
  `prod_sup_id` int(11) DEFAULT NULL,
  `prod_tax_id` int(11) NOT NULL,
  `prod_tax_name` varchar(30) NOT NULL,
  `prod_tax_percent` decimal(5,2) NOT NULL,
  `inserted_date` datetime NOT NULL,
  `prod_effective_price` decimal(10,2) NOT NULL,
  `prod_viewed` bigint(20) NOT NULL,
  `prod_purchased` bigint(20) NOT NULL,
  `prod_outofstock` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`prod_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

-- --------------------------------------------------------

--
-- Table structure for table `prod_props`
--

CREATE TABLE IF NOT EXISTS `prod_props` (
  `prod_id` int(11) NOT NULL,
  `prop_id` int(11) NOT NULL,
  `opt_value` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `prod_sup`
--

CREATE TABLE IF NOT EXISTS `prod_sup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) DEFAULT NULL,
  `sup_id` int(11) DEFAULT NULL,
  `price` decimal(6,2) DEFAULT NULL,
  `offer_price` decimal(6,2) DEFAULT NULL,
  `offer_disc` decimal(6,2) DEFAULT NULL,
  `sup_status` int(11) DEFAULT NULL,
  `final_price` decimal(6,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq` (`prod_id`,`sup_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=203 ;

-- --------------------------------------------------------

--
-- Table structure for table `prod_viewed`
--

CREATE TABLE IF NOT EXISTS `prod_viewed` (
  `view_id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(30) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `prod_id` int(11) DEFAULT NULL,
  `view_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `view_count` int(11) DEFAULT NULL,
  PRIMARY KEY (`view_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=757 ;

-- --------------------------------------------------------

--
-- Table structure for table `prop_mast`
--

CREATE TABLE IF NOT EXISTS `prop_mast` (
  `prop_id` int(11) NOT NULL AUTO_INCREMENT,
  `prop_name` varchar(40) NOT NULL,
  `prop_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`prop_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `prop_options`
--

CREATE TABLE IF NOT EXISTS `prop_options` (
  `prop_id` int(11) NOT NULL,
  `opt_value` varchar(50) NOT NULL,
  `opt_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`opt_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `rotating_img_mast`
--

CREATE TABLE IF NOT EXISTS `rotating_img_mast` (
  `rotating_img_id` int(11) NOT NULL,
  `rotating_img_alt_text` varchar(50) DEFAULT NULL,
  `rotating_img` varchar(100) DEFAULT NULL,
  `rotating_img_sort` decimal(4,0) DEFAULT NULL,
  `rotating_img_status` tinyint(4) DEFAULT NULL,
  `rotating_img_target_url` varchar(100) DEFAULT NULL,
  `rotating_img_target_window` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`rotating_img_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ship_zone`
--

CREATE TABLE IF NOT EXISTS `ship_zone` (
  `ship_id` int(11) NOT NULL AUTO_INCREMENT,
  `zone_name` varchar(50) NOT NULL,
  `ord_upto` decimal(10,0) NOT NULL,
  `ship_charge` decimal(10,0) NOT NULL,
  PRIMARY KEY (`ship_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `state_mast`
--

CREATE TABLE IF NOT EXISTS `state_mast` (
  `state_id` int(11) NOT NULL AUTO_INCREMENT,
  `state_name` varchar(50) NOT NULL,
  `state_zone` varchar(50) NOT NULL,
  PRIMARY KEY (`state_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

-- --------------------------------------------------------

--
-- Table structure for table `sup_ext_addr`
--

CREATE TABLE IF NOT EXISTS `sup_ext_addr` (
  `addr_id` int(11) NOT NULL AUTO_INCREMENT,
  `sup_id` int(11) NOT NULL COMMENT 'Seller ID',
  `sup_ext_name` varchar(50) DEFAULT NULL COMMENT 'Establishment Name',
  `sup_ext_address` varchar(100) DEFAULT NULL COMMENT 'Seller''s additional  address',
  `sup_ext_state` varchar(50) DEFAULT NULL COMMENT 'State',
  `sup_ext_city` varchar(50) DEFAULT NULL COMMENT 'City',
  `sup_ext_address_type` varchar(50) DEFAULT NULL COMMENT 'Address type',
  `sup_ext_contact_no` varchar(50) DEFAULT NULL COMMENT 'contact number',
  `sup_ext_pincode` varchar(50) DEFAULT NULL COMMENT 'Pincode',
  PRIMARY KEY (`addr_id`),
  KEY `sup_id` (`sup_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `sup_mast`
--

CREATE TABLE IF NOT EXISTS `sup_mast` (
  `sup_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Seller ID',
  `sup_seller_token` varchar(7) DEFAULT NULL COMMENT 'An alpha numeric code of length 6, unique to every seller',
  `sup_company` varchar(50) DEFAULT NULL COMMENT 'Company Name',
  `sup_business_type` varchar(50) DEFAULT NULL COMMENT 'Type of Business',
  `sup_email` varchar(50) NOT NULL COMMENT 'Email address',
  `sup_contact_no` varchar(50) NOT NULL COMMENT 'seller conntact',
  `sup_alt_contact_no` int(11) DEFAULT NULL COMMENT 'Alternate contact number',
  `sup_logo` varchar(100) DEFAULT NULL COMMENT 'Seller Logo',
  `sup_pwd` varchar(50) NOT NULL COMMENT 'Seller password',
  `sup_active_status` int(11) NOT NULL DEFAULT '0' COMMENT 'Activation status',
  `sup_activation` varchar(50) DEFAULT NULL COMMENT 'Activtion code',
  `sup_admin_approval` int(1) NOT NULL DEFAULT '0' COMMENT 'Admin''s approval after verification of seller''s documents.',
  `sup_shop_act_license` varchar(50) DEFAULT NULL COMMENT 'Shop act license document',
  `sup_pan` varchar(20) DEFAULT NULL COMMENT 'Establishment PAN',
  `sup_vat` varchar(20) DEFAULT NULL COMMENT 'Central Excise & Service Tax number',
  `sup_cstn` varchar(20) DEFAULT NULL COMMENT 'Central Sales Tax Account number',
  `sup_bk_acc` varchar(20) DEFAULT NULL COMMENT 'Bank account number',
  `sup_bk_ifsc` varchar(20) DEFAULT NULL COMMENT 'IFSC Code',
  `sup_bk_name` varchar(50) DEFAULT NULL COMMENT 'Bank Name',
  `sup_bk_brname` varchar(50) DEFAULT NULL COMMENT 'Branch Name',
  `sup_bk_can_chk` varchar(50) DEFAULT NULL COMMENT 'Cancelled check for the bank account',
  `sup_contact_name` varchar(50) DEFAULT NULL COMMENT 'Seller Name',
  `sup_reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sup_vat_doc` varchar(100) DEFAULT NULL COMMENT 'VAT ceertificate',
  `sup_pan_doc` varchar(100) DEFAULT NULL COMMENT 'PAN document',
  `sup_cst_doc` varchar(100) DEFAULT NULL,
  `sup_mou_accept` int(1) DEFAULT NULL COMMENT 'Seller MOU acceptance',
  PRIMARY KEY (`sup_id`),
  UNIQUE KEY `sup_id_2` (`sup_id`),
  UNIQUE KEY `sup_email` (`sup_email`),
  KEY `sup_id` (`sup_id`),
  KEY `sup_id_3` (`sup_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50 ;

-- --------------------------------------------------------

--
-- Table structure for table `sup_type_mast`
--

CREATE TABLE IF NOT EXISTS `sup_type_mast` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(30) NOT NULL,
  `type_sort` int(11) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `tax_mast`
--

CREATE TABLE IF NOT EXISTS `tax_mast` (
  `tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_name` varchar(30) NOT NULL,
  `tax_percent` decimal(6,2) NOT NULL,
  `tax_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`tax_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='To store Tax types based on product types' AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_mast`
--

CREATE TABLE IF NOT EXISTS `user_mast` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(100) NOT NULL,
  `user_pwd` varchar(20) NOT NULL,
  `user_type` varchar(10) NOT NULL,
  `user_status` int(11) NOT NULL DEFAULT '0',
  `user_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_name` varchar(120) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_user`
--
CREATE TABLE IF NOT EXISTS `vw_user` (
`user_type` varchar(1)
,`memb_id` int(11)
,`memb_email` varchar(50)
,`memb_pwd` varchar(50)
,`memb_fname` varchar(50)
);
-- --------------------------------------------------------

--
-- Structure for view `vw_user`
--
DROP TABLE IF EXISTS `vw_user`;

CREATE ALGORITHM=UNDEFINED DEFINER=`Company-Namedba`@`localhost` SQL SECURITY DEFINER VIEW `vw_user` AS select 'M' AS `user_type`,`member_mast`.`memb_id` AS `memb_id`,`member_mast`.`memb_email` AS `memb_email`,`member_mast`.`memb_pwd` AS `memb_pwd`,`member_mast`.`memb_fname` AS `memb_fname` from `member_mast` where ((`member_mast`.`memb_act_status` = 1) and (`member_mast`.`memb_status` = 1)) union select 'S' AS `S`,`sup_mast`.`sup_id` AS `sup_id`,`sup_mast`.`sup_email` AS `sup_email`,`sup_mast`.`sup_pwd` AS `sup_pwd`,`sup_mast`.`sup_contact_name` AS `sup_contact_name` from `sup_mast`;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sup_ext_addr`
--
ALTER TABLE `sup_ext_addr`
  ADD CONSTRAINT `Supplier id foriegn key` FOREIGN KEY (`sup_id`) REFERENCES `sup_mast` (`sup_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
