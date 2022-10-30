ALTER TABLE  `cart_details` ADD  `bt_bank_name` VARCHAR( 50 ) NULL ,
ADD  `bt_bank_branch` VARCHAR( 50 ) NULL ,
ADD  `bt_ref_no` VARCHAR( 15 ) NULL ,
ADD  `bt_date` DATE NULL;


ALTER TABLE  `ord_details` ADD  `bt_bank_name` VARCHAR( 50 ) NULL ,
ADD  `bt_bank_branch` VARCHAR( 50 ) NULL ,
ADD  `bt_ref_no` VARCHAR( 15 ) NULL ,
ADD  `bt_date` DATE NULL;


ALTER TABLE  `cart_summary` ADD  `pkg_weight_kgs` float NULL;
ALTER TABLE  `ord_summary` ADD  `pkg_weight_kgs` float NULL;
