<?php

include("db.php");

include("functions.php");

include("logistics_push.php");
//print_r($_GET);die;
ini_set('display_errors', 1); 


$id = func_read_qs("order_id");
$act = func_read_qs("act");
$sup_id = func_read_qs("sup_id");


if(get_rst("select * from ord_summary where ord_id=0$id and sup_id=$sup_id",$row_s,$rst_s)){
	$cart_id = $row_s["cart_id"];
}
if($act<>""){
	//print_r("in here");die;
	$delivery_status_old = func_read_qs("delivery_status_old");
	$delivery_status = func_read_qs("delivery_status");
	$delivery_date = func_read_qs("delivery_date");
	$v_cancelled = "";
	
	if($delivery_status<>"Pending" and $delivery_status_old <> $delivery_status){
		if(get_rst("select item_buyer_action from ord_items where cart_id=$cart_id and sup_id=$sup_id and item_buyer_action is not null")){
			$v_cancelled = "1";
			$msg_response="Sorry the order has just been cancelled by the Buyer hence cannot be Dispatched.";
		}	
	}
	
	if($v_cancelled == ""){
		
		$fld_arr = array();

		$fld_arr["delivery_status"] = $delivery_status;
		$fld_arr["delivery_date"] = $delivery_date;
		
		$qry = func_update_qry1("ord_summary",$fld_arr," where ord_id=0$id and sup_id=$sup_id");
		execute_qry($qry);
		
		$msg_response="Order details updated successfully.";
	}
	
	/*if($delivery_status=="Dispatched"){
		lgs_create_order($id,$msg);
		js_alert($msg);
	}*/
}
	
		$response['status']=$msg_response;
			echo json_encode($response);
	
?>