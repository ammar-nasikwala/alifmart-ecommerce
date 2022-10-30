<?php

include("db.php");
include("functions.php");

ini_set('display_errors', 1); 


	$coupon_id = func_read_qs("coupon_id");
	$cart_id = func_read_qs("cart_id");
	
		execute_qry("delete from ord_coupon where coupon_id=$coupon_id and cart_id=$cart_id");
		
	$msg['status']	="success";
	
	echo json_encode($msg);
?>