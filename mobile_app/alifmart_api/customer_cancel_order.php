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
//$user_type = func_read_qs("user_type");

$admin_email="Company-Name.test@gmail.com";
$cc=$admin_email;
$response['status']="success";

if(get_rst("select * from ord_details where cart_id='".$cart_id."'",$row)){
	$bill_name = $row["bill_name"];
	$bill_email = $row["bill_email"];
	$bill_add1 = $row["bill_add1"];
	$bill_add2 = $row["bill_add2"];
	$bill_city = $row["bill_city"];
	$bill_state = $row["bill_state"];
	$bill_country = $row["bill_country"];
	$bill_postcode = $row["bill_postcode"];
	$bill_tel = $row["bill_tel"];

	$ship_name = $row["ship_name"];
	$ship_email = $row["ship_email"];
	$ship_add1 = $row["ship_add1"];
	$ship_add2 = $row["ship_add2"];
	$ship_city = $row["ship_city"];
	$ship_state = $row["ship_state"];
	$ship_country = $row["ship_country"];
	$ship_postcode = $row["ship_postcode"];
	$ship_tel = $row["ship_tel"];
	
	$ord_instruct = $row["ord_instruct"];	

	$bt_bank_name=$row["bt_bank_name"];
	$bt_bank_branch=$row["bt_bank_branch"];
	$bt_ref_no=$row["bt_ref_no"];
	$bt_date=$row["bt_date"];	
}

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
		$response['status']="Sorry the order has just been dispatched hence cannot be cancelled.";
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
		
		$response['status']="Your entire Order has been cancelled successfully.";

	}
}

$v_action="";
if($act=="c" or $act=="r" or $act=="j"){

	if($act=="c" AND get_rst("select delivery_status from ord_summary where cart_id=$cart_id and sup_id=$sup_id and delivery_status<>'Pending'")){
		$response['status']="Sorry the order has just been dispatched hence cannot be cancelled.";
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
		get_rst("select ord_no from ord_summary where cart_id=$cart_id and sup_id=$sup_id",$ord);
		$order_no = $ord["ord_no"];
		update_ord_summary($cart_id,$sup_id,$item_id,$order_no);

		if($act=="c"){
			$reason = func_read_qs("reason");
			$comment = func_read_qs("comment");
			mysqli_query($con,"INSERT INTO ord_cancel_reasons SET ord_no='".$order_no."',cart_id='".$cart_id."',memb_id='".$memb_id."', reason = '".$reason."', comment = '".$comment."'");
		}
		else if($act=="r"){

			$reason_r =func_read_qs("reason");
			$comment_r = func_read_qs("comment");
			mysqli_query($con,"INSERT INTO ord_cancel_reasons SET ord_no='".$ord_no."',cart_id='".$cart_id."',memb_id='".$memb_id."', reason = '".$reason_r."', comment = '".$comment_r."'");
		}
		else if($act=="j"){

			$reason_j =func_read_qs("reason");
			$comment_j = func_read_qs("comment");
			mysqli_query($con,"INSERT INTO ord_cancel_reasons SET ord_no='".$order_no."',cart_id='".$cart_id."',memb_id='".$memb_id."', reason = '".$reason_j."', comment = '".$comment_j."'");
		}
		
			
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
		
		$response['status']="$v_action";
	}
}
	echo json_encode($response);
?>