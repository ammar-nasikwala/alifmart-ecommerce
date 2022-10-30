<?php

//print_r("sghsgsdhgf");die;
include("db.php");
include("functions.php");

ini_set('display_errors', 1); 


$cart_id = func_read_qs('cart_id');

$act = func_read_qs("act");
$sup_id = func_read_qs("sup_id");
$item_id = func_read_qs("item_id");

$memb_id = func_read_qs("memb_id");
$user_type = func_read_qs("user_type");

$admin_email="Company-Name.test@gmail.com";

$response['order_detail']=array();

if(get_rst("select * from ord_details where cart_id='".$cart_id."'",$row)){
		$temp['bill_name'] = $row["bill_name"];
		$temp['bill_email']= $row["bill_email"];
		$temp['bill_add1']= $row["bill_add1"];
		$temp['bill_add2'] = $row["bill_add2"];
		$temp['bill_city'] = $row["bill_city"];
		$temp['bill_state']= $row["bill_state"];
		$temp['bill_country'] = $row["bill_country"];
		$temp['bill_postcode'] = $row["bill_postcode"];
		$temp['bill_tel']= $row["bill_tel"];

		$temp['ship_name'] = $row["ship_name"];
		$temp['ship_email'] = $row["ship_email"];
		$temp['ship_add1'] = $row["ship_add1"];
		$temp['ship_add2'] = $row["ship_add2"];
		$temp['ship_city']= $row["ship_city"];
		$temp['ship_state'] = $row["ship_state"];
		$temp['ship_country'] = $row["ship_country"];
		$temp['ship_postcode']= $row["ship_postcode"];
		$temp['ship_tel'] = $row["ship_tel"];
		
		$temp['ord_instruct']= $row["ord_instruct"];	

		$temp['bt_bank_name']=$row["bt_bank_name"];
		$temp['bt_bank_branch']=$row["bt_bank_branch"];
		$temp['bt_ref_no']=$row["bt_ref_no"];
		$date=date_create($row["bt_date"]);
		
		$temp['bt_date']=date_format($date,"d/m/Y");
		
		array_push($response['order_detail'],$temp);
	
}	

/*
if($act<>""){
	$sql = "select ord_no from ord_summary where cart_id=$cart_id";
	if(get_rst($sql,$row)){
		$ord_no = $row["ord_no"];
	}
}


if($act=="bt"){
	$bt_bank_name=func_read_qs("bt_bank_name");
	$bt_bank_branch=func_read_qs("bt_bank_branch");
	$bt_ref_no=func_read_qs("bt_ref_no");
	$bt_date=func_read_qs("bt_date");
	
	$fld_arr = array();
	$fld_arr["bt_bank_name"] = $bt_bank_name;
	$fld_arr["bt_bank_branch"] = $bt_bank_branch;
	$fld_arr["bt_ref_no"] = $bt_ref_no;
	$fld_arr["bt_date"] = $bt_date;

	$sql = func_update_qry("ord_details",$fld_arr," where cart_id=$cart_id");
	//echo($sql);
	execute_qry($sql);	
}

if($act=="a"){
	if(get_rst("select delivery_status from ord_summary where cart_id=$cart_id and delivery_status<>'Pending'")){
		js_alert("Sorry the order has just been dispatched hence cannot be cancelled.");
	}else{
		$sql = "update ord_summary set buyer_action='Cancelled',buyer_date=now() where cart_id=$cart_id";
		execute_qry($sql);

		$sql = "update ord_items set item_buyer_action='Cancelled',buyer_date=now() where cart_id=$cart_id";
		execute_qry($sql);
		
		get_rst("select sup_id,sup_email,sup_contact_no from sup_mast where sup_id in (select sup_id from ord_summary where cart_id=$cart_id)",$row_s,$rst_s);
		do{
			$sup_id = $row_s["sup_id"];
			update_ord_summary($cart_id,$sup_id);

			$mail_body = "Dear Seller,<br> Order $ord_no has been cancelled by the buyer";
			$sms_body = "Dear Seller, Order $ord_no has been cancelled by the buyer";
			send_mail($row_s["sup_email"],"Company-Name - Order Cancelled (Seller): $ord_no",$mail_body,"",$cc);
			send_sms($row_s["sup_contact_no"],$sms_body);
		}while($row_s = mysqli_fetch_assoc($rst_s));
		
		$mail_body = "Dear $bill_name,<br> Your order $ord_no has been cancelled successfully.";
		$sms_body = "Dear $bill_name, Your order $ord_no has been cancelled successfully.";
		send_mail($bill_email,"Company-Name - Order Cancelled: $ord_no",$mail_body,"",$cc);
		send_sms($bill_tel,$sms_body);
		
		js_alert("Your entire Order has been cancelled successfully.");

	}
}

$v_action="";
if($act=="c" or $act=="r" or $act=="j"){

	if($act=="c" AND get_rst("select delivery_status from ord_summary where cart_id=$cart_id and sup_id=$sup_id and delivery_status<>'Pending'")){
		js_alert("Sorry the order has just been dispatched hence cannot be cancelled.");
	}else{
		switch($act){
			case "c":
				$v_action="Cancelled";
				break;
			case "r":
				$v_action="Returned";
				break;
			case "j":
				$v_action="Rejected";
				break;
		}
		$sql = "update ord_items set item_buyer_action='$v_action',buyer_date=now() where cart_id=$cart_id and cart_item_id = $item_id";
		execute_qry($sql);

		update_ord_summary($cart_id,$sup_id);

		
			
		get_rst("select sup_email,sup_contact_no from sup_mast where sup_id = $sup_id",$row_s,$rst_s);
		//do{
			$mail_body = "Dear Seller,<br>A Product in Order $ord_no has been $v_action by the buyer";
			$sms_body = "Dear Seller, A Product in Order $ord_no has been $v_action by the buyer";
			send_mail($row_s["sup_email"],"Company-Name - Order $v_action (Seller): $ord_no",$mail_body,"",$cc);
			send_sms($row_s["sup_contact_no"],$sms_body);
		//}while($row_s = mysql_fetch_assoc($rst_s));
		
		$mail_body = "Dear $bill_name, <br>A product in your order $ord_no has been $v_action.";
		$sms_body = "Dear $bill_name, A product in your order $ord_no has been $v_action.";
		send_mail($bill_email,"Company-Name - Order $v_action: $ord_no",$mail_body,"",$cc);
		send_sms($bill_tel,$sms_body);	
		
		js_alert("Selected product has been $v_action.");
	}
}

*/
$sql_where = " and user_id=".$memb_id." and user_type='".$user_type."'";

$sql = "select * from ord_summary where cart_id=$cart_id $sql_where and item_count IS NOT NULL";
//die($sql);
if(get_rst($sql,$row_s,$rst_s)){
	//$cart_id = $row_s["cart_id"];
}else{
	//header('location: memb_view_orders.php');
	//js_alert("Sorry! Unable to display the order with id $cart_id");
	//js_redirect("memb_view_orders.php");
	$response['status']="Sorry! Unable to display the order with id $cart_id";
}

$response['cart_detail']=array();
$total_amount_payable=0;
	do{
		$sup_id = $row_s["sup_id"];
		$ord_no = $row_s["ord_no"];
		$ord_date = $row_s["ord_date"];
		
		//$temp1['sup_id'] = $row_s["sup_id"];
		$temp1['ord_no'] = $row_s["ord_no"];
		$temp1['ord_date'] = $row_s["ord_date"];

		$temp1['delivery_status'] = $row_s["delivery_status"];
		$temp1['item_total'] = $row_s["item_total"];
		$shipping_charges = $row_s["shipping_charges"]."";
		if($shipping_charges.""==""){
				$temp1['v_shipping_charges'] = "As per the delivery area";
			}else{
				$temp1['v_shipping_charges'] = "Rs.".formatNumber($shipping_charges,2);
			}
		$temp1['ord_total'] = $row_s["ord_total"];
		$total_amount_payable=$total_amount_payable+$row_s["ord_total"];
		$temp1['vat_percent'] = $row_s["vat_percent"];
		$temp1['vat_value'] = $row_s["vat_value"];
		$temp1['pay_method'] = $row_s["pay_method"];
		$temp1['buyer_action'] = $row_s["buyer_action"];
		$temp1['pay_method_name'] = $row_s["pay_method_name"];
		$temp1['item_details'] = array();

		
		$sql="select * from ord_items where cart_id=$cart_id and sup_id=$sup_id";
		//echo $sql;die;
		get_rst("select * from ord_items where cart_id=$cart_id and sup_id=$sup_id",$row,$rst);
		do{
				$temp2['cart_price'] = $row["cart_price"];
				$temp2['cart_qty'] = $row["cart_qty"];
				$temp2['prod_id'] = $row["prod_id"];
				$temp2['cart_price_tax'] = $row["cart_price_tax"];
				$temp2['user_type']=func_var($row["user_type"]);
				$temp2['sup_name']=$row["sup_name"];
				$temp2['item_name']=$row["item_name"];
				$temp2['cart_price']=$row["cart_price"];
				$temp2['tax_percent']=$row["tax_percent"];
				$temp2['sup_id']=$row["sup_id"];
				$temp2['cart_item_id']=$row["cart_item_id"];
				$temp2['item_buyer_action']=$row["item_buyer_action"];
				$temp2['ship_amt']=$row["ship_amt"];
		
				$temp2['item_color'] = "";
					if($row["item_buyer_action"].""<>""){
						$temp2['item_color'] = "#d9534f";
					}
				$temp2['item_thumb'] = base64_encode($row["item_thumb"]);	
				array_push($temp1['item_details'],$temp2);
				
			}while($row = mysqli_fetch_assoc($rst));
			
			array_push($response['cart_detail'],$temp1);
		
	}while($row_s = mysqli_fetch_assoc($rst_s));
	
	//change related to coupon code
	$coupon_amt = 0;
	$response['coupon_text']="0";
	$response['coupon_amt']="0";
	get_rst("select sum(cancel_coupon) as cancel_coupon,sum(cancel_amount) as cancel_amount from track_refund where cart_id=$cart_id",$ro_c);

	if(get_rst("select coupon_id,coupon_amt from ord_coupon where cart_id=$cart_id", $row_c)){
		get_rst("select coupon_code from offer_coupon where coupon_id=".$row_c["coupon_id"], $row_oc);
		
		if($ro_c["cancel_coupon"] <> ""){
			$coupon_amt = $row_c["coupon_amt"] - $ro_c["cancel_coupon"];
		}else{	
			$coupon_amt = $row_c["coupon_amt"];
		}
		$response['coupon_text'] = $row_oc["coupon_code"];
		$response['coupon_amt'] = $coupon_amt;
		$total_amount_payable = $total_amount_payable - $coupon_amt;
	}	
	
	get_rst("select sum((cart_price_tax * cart_qty) + ship_amt) as ord_total from ord_items where cart_id=$cart_id",$row_t);
	if($ro_c["cancel_amount"] <> ""){
			
			$total_amount = $row_t["ord_total"] - ($ro_c["cancel_amount"] + $ro_c["cancel_coupon"]);
			$total_amount_payable = $total_amount - ($row_c["coupon_amt"] - $ro_c["cancel_coupon"]);
			$response['total_amount_payable']=FormatNumber($total_amount_payable,2);
		}else{
			$response['total_amount_payable']=$row_t["ord_total"] - $row_c["coupon_amt"];
		}
		
	$response['refund_details']= "0";
	$response['total_deductable']= 0;
	$response['coupon_deductable']= 0;
	$response['total_refund']= 0;

	if(get_rst("select * from track_refund where cart_id=$cart_id and pay_status='Paid'",$ro)){
		
		$response['refund_details']= "1";
		
		get_rst("select sum((cart_price_tax *cart_qty) + ship_amt) as total from ord_items where cart_id=$cart_id and (item_buyer_action='Cancelled' or item_buyer_action='Returned')",$row_t);
		
			if($ro_c["cancel_amount"] <> ""){
				$total_amt = $row_t["total"] - ($ro_c["cancel_amount"] + $ro_c["cancel_coupon"]);
				$total_deductable = FormatNumber($total_amt,2);
				$response['total_deductable']= $total_deductable;
			}else{
					$total_amt = $row_t["total"];
					$total_deductable = FormatNumber($total_amt,2);
					$response['total_deductable']= $total_deductable;
			 }
			
			get_rst("select sum(coupon_deduct) as coupon_deduct from track_refund where cart_id=$cart_id",$row_tot);
			$coupon_deductable=FormatNumber($row_tot["coupon_deduct"],2);
			$response['coupon_deductable']= $coupon_deductable;
			
			get_rst("select sum(refund_amt) as refund_amount from track_refund where cart_id=$cart_id",$row_ref);
			$total_refund=FormatNumber($row_ref["refund_amount"],2);
			$response['total_refund']= $total_refund;
			
	}
 

 array_walk_recursive($response,"convert_to_utf8");
	echo json_encode($response);
?>