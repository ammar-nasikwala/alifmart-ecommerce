<?php

include("db.php");
include("functions.php");

ini_set('display_errors', 1); 

//$action=addslashes($_GET['action']);
//print_r($_FILES);die;
//print_r($_GET);die;

	//print_r($memb_id);die;
	
	
		$msg['status'] = "";
		$memb_id = func_read_qs("memb_id");
		$coupon_code = func_read_qs("coupon");
		$ord_amt = func_read_qs("ord_total");
		$cart_id = func_read_qs("cart_id");
		get_rst("select sum(shipping_charges+vat_value) as total from cart_summary where cart_id=$cart_id", $row_extra);
		$ord_amt=$ord_amt - $row_extra["total"];
		$result = verify_offer_coupon($memb_id, $cart_id, $coupon_code, $ord_amt);
		get_rst("select coupon_id, max_discount_value from offer_coupon where coupon_code='$coupon_code'", $row);
		$fld_arr = array();
		$max_discount_value = $row["max_discount_value"];
		if($result."" <> ""){
			if(intval($result) == 0){
				$msg['status'] = $result;
			}else{
				$disc_per = intval($result);
				$coupon_amt = (floatval($ord_amt) * floatval($disc_per)/100);
				if($coupon_amt > $max_discount_value){
					
					$coupon_amt = $max_discount_value;
					
					$msg['status'] = "success";
				
				}else{
					$msg['status'] = "success";
					
				}
				
				$fld_arr["cart_id"] = $cart_id;
				$fld_arr["coupon_amt"] = $coupon_amt;
				$fld_arr["coupon_id"] = $row["coupon_id"];
				$qry = func_insert_qry("ord_coupon",$fld_arr);
				execute_qry($qry);
			}
		}else{
			$msg['status'] = "Invalid Coupon";
		}
		
	
	//update_cart_summary($memb_id,$cart_id,$coupon_code);
	
	echo json_encode($msg);
?>