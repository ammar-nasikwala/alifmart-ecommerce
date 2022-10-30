CREATE TABLE seq_cart_id (cart_id BIGINT NOT NULL);
INSERT INTO seq_cart_id VALUES (0);

ALTER TABLE  `cart_summary` DROP PRIMARY KEY;
ALTER TABLE  `ord_summary` DROP PRIMARY KEY;


create view vw_ord_summary
as
select ord_id
,max(os.cart_id) as cart_id
,max(ord_no) as 'Order No.'
,max(ord_date) as 'Date'
,bill_name as 'Placed by'
,sum(ord_total) as 'Order Value'
,sum(item_count) as 'No. of Products'
,user_type as 'User type'
,max(pay_method) as 'Payment Type'
,max(pay_status) as 'Payment Status'
,max(delivery_status) as 'delivery_status'
,max(user_id) as user_id
,max(user_type) as user_type
 from ord_summary os inner join ord_details od on os.cart_id=od.cart_id
 group by ord_id;
 

ALTER TABLE `cart_items` ADD  `item_buyer_action` VARCHAR( 10 ) NULL;
ALTER TABLE `ord_items` ADD  `item_buyer_action` VARCHAR( 10 ) NULL;

ALTER TABLE `cart_summary` ADD  `buyer_action` VARCHAR( 10 ) NULL;
ALTER TABLE `ord_summary` ADD  `buyer_action` VARCHAR( 10 ) NULL;

ALTER TABLE  `cart_summary` ADD  `buyer_date` DATETIME NULL;
ALTER TABLE  `ord_summary` ADD  `buyer_date` DATETIME NULL;
ALTER TABLE  `cart_items` ADD  `buyer_date` DATETIME NULL;
ALTER TABLE  `ord_items` ADD  `buyer_date` DATETIME NULL;

ALTER TABLE  `prod_sup` ADD  `out_of_stock` TINYINT NULL;

ALTER TABLE `sup_mast` ADD `sms_verify_code` INT(4) NULL COMMENT 'SMS verification code' , ADD `sms_verify_status` INT(1) NULL COMMENT 'SMS verification status' ;

ALTER TABLE `member_mast` ADD `sms_verify_code` INT(4) NULL COMMENT 'SMS verification code' , ADD `sms_verify_status` INT(1) NULL COMMENT 'SMS verification status' ;

--12 dec 15 --
ALTER TABLE `member_mast` ADD `memb_cstn` VARCHAR(15) NULL COMMENT 'Buyers CST number' , ADD `memb_vat` VARCHAR(15) NULL COMMENT 'Buyer''s vat number' , ADD `memb_cst_doc` VARCHAR(100) NULL COMMENT 'Buyer''s cst certificate' , ADD `memb_vat_doc` VARCHAR(100) NULL COMMENT 'Buyer''s vat certificate' ;
ALTER TABLE `member_mast` ADD `ind_buyer` INT(1) NULL COMMENT 'Is industrial buyer' ;
--------------

----16 dec 15----
ALTER TABLE `cart_summary` ADD `sup_id` INT(11) NULL COMMENT 'Selller ID' ; 
-----------------

----19 dec 15 ----
ALTER TABLE `ord_summary` ADD `sup_id` INT(11) NULL ;
------------------

----20 dec 15----
ALTER TABLE `ord_items` ADD `item_wish` TINYINT(4) NULL COMMENT 'Wish list status' AFTER `cart_id`;
-----------------

----14 feb 16----// to run this follow these insructions
--- goto our database -> views-> vw_user -> structure -> edit view
---- replace existing query with the one provided below.
select 'M' AS `user_type`,`Company-Name`.`member_mast`.`memb_id` AS `memb_id`,`Company-Name`.`member_mast`.`memb_email` AS `memb_email`,`Company-Name`.`member_mast`.`memb_pwd` AS `memb_pwd`,`Company-Name`.`member_mast`.`memb_fname` AS `memb_fname`,`Company-Name`.`member_mast`.`memb_act_status` AS `memb_act_status` from `Company-Name`.`member_mast` where (`Company-Name`.`member_mast`.`memb_status` = 1) union select 'S' AS `S`,`Company-Name`.`sup_mast`.`sup_id` AS `sup_id`,`Company-Name`.`sup_mast`.`sup_email` AS `sup_email`,`Company-Name`.`sup_mast`.`sup_pwd` AS `sup_pwd`,`Company-Name`.`sup_mast`.`sup_contact_name` AS `sup_contact_name`,`Company-Name`.`sup_mast`.`sup_active_status` AS `sup_active_status` from `Company-Name`.`sup_mast`
-----------------