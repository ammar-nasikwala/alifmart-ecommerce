<?php

include("db.php");

include("functions.php");

include("logistics_push.php");
//print_r($_GET);die;
ini_set('display_errors', 1); 


$id = func_read_qs("order_id");
//$act = func_read_qs("act");
$sup_id = func_read_qs("sup_id");


	$delivery_status_old = func_read_qs("delivery_status_old");
	$delivery_status = func_read_qs("delivery_status");
	$v_cancelled = "";
	if(get_rst("select * from ord_summary where ord_id=0$id and sup_id=$sup_id",$row_s,$rst_s)){
			$cart_id = $row_s["cart_id"];
			$user_id= $row_s["user_id"];
			$ord_no = $row_s["ord_no"];

	}
	if($delivery_status<>"Pending" and $delivery_status_old <> $delivery_status){
		if(get_rst("select buyer_action from ord_summary where cart_id=$cart_id and sup_id=$sup_id and buyer_action is not null")){
			$v_cancelled = "1";
			$msg_response="Sorry the order has just been cancelled by the Buyer hence cannot be Dispatched.";
		}	
	}
	
	if($v_cancelled == ""){
		
		get_rst("select sup_company from sup_mast where sup_id=$sup_id",$row_e);	//to get Seller name
		$sup_company=$row_e["sup_company"];
		
		$sql = "select memb_email from member_mast where memb_id='".$user_id."'";		//to get buyers email id
		get_rst($sql,$row_u);
		$user_email=$row_u["memb_email"];
		$logistics_m="LP";															//to get logistics managers email id 
		get_rst("select user_email from user_mast where user_type='".$logistics_m."'",$row_lm);
		$logistics_email=$row_lm["user_email"];
		
		$fld_arr = array();
		$fld_arr["pkg_weight_kgs"] = func_read_qs("pkg_weight_kgs");
		$fld_arr["pkg_weight"] = func_read_qs("pkg_weight");
		$fld_arr["pkg_height"] = func_read_qs("pkg_height");
		$fld_arr["pkg_width"] = func_read_qs("pkg_width");
		$fld_arr["pkg_depth"] = func_read_qs("pkg_depth");
		$fld_arr["pkg_count"] = func_read_qs("pkg_count");
		$fld_arr["pickup_datetime"] = func_read_qs("pickup_datetime");
		$fld_arr["delivery_status"] = $delivery_status;
		$msg = "";
		
		if($delivery_status_old <> "Dispatched" and $delivery_status == "Dispatched"){
			$actual_ship_amt=0;
			get_rst("select actual_ship_amt from ord_items where cart_id='".$cart_id."' and sup_id=$sup_id",$row_ship,$rst_ship);
			do{
				$actual_ship_amt = $row_ship["actual_ship_amt"]+$actual_ship_amt;
			}while($row_ship = mysqli_fetch_assoc($rst_ship));
			
			if($row_s["new_seller_price"] <> ""){
				get_rst("select new_seller_price,cart_price_tax,tax_percent,cart_qty from ord_items where cart_id='".$cart_id."' and sup_id=$sup_id",$row_in,$rst_in);
				$fld_inv_arr = array();
				$fld_inv_arr["ord_no"] = $row_s["ord_no"];
				$fld_inv_arr["sup_id"] = $sup_id;
				$fld_inv_arr["pay_status"] = "Pending";
				$fld_inv_arr["pay_comission"] = func_read_qs("sup_total_commission");
				$fld_inv_arr["service_tax"] = ($fld_inv_arr["pay_comission"] *15) / 100;
				do{
					if($row_in["new_seller_price"] <> ""){
						$new_price = ($row_in["new_seller_price"] * $row_in["tax_percent"])/100;
						$new_price = $new_price * $row_in["cart_qty"];
						$total_price = $total_price + ($row_in["new_seller_price"] * $row_in["cart_qty"]) + $new_price;
					}else{
						$total_price = $total_price + ($row_in["cart_price_tax"]*$row_in["cart_qty"]);
					}
				}while($row_in = mysqli_fetch_assoc($rst_in));
				$fld_inv_arr["pay_total"] = floatval($total_price) - $fld_inv_arr["pay_comission"] - $fld_inv_arr["service_tax"] - $actual_ship_amt;
				$qry = func_insert_qry("invoice_mast",$fld_inv_arr);
				execute_qry($qry);
			}else{
				$fld_inv_arr = array();
				$fld_inv_arr["ord_no"] = $row_s["ord_no"];
				$fld_inv_arr["sup_id"] = $sup_id;
				$fld_inv_arr["pay_status"] = "Pending";
				$fld_inv_arr["pay_comission"] = func_read_qs("sup_total_commission");
				$fld_inv_arr["service_tax"] = ($fld_inv_arr["pay_comission"] *15) / 100;
				$fld_inv_arr["pay_total"] = floatval($row_s["item_total"] + $row_s["vat_value"]) - $fld_inv_arr["pay_comission"] - $fld_inv_arr["service_tax"] - $actual_ship_amt;
				$qry = func_insert_qry("invoice_mast",$fld_inv_arr);
				execute_qry($qry);
			}
		}
		
		if($delivery_status=="Ready-to-Ship"){ //creates a logistics push request.
			get_rst("select local_logistics from ord_summary where sup_id=$sup_id and ord_id=0$id",$r);
					if($r["local_logistics"] == 1){
						get_rst("select Admin_email from configuration",$row_em);
						$super_admin_email = $row_em["Admin_email"];
						$from = "orders@Company-Name.com";
						$body="Hi,<br>";
						$body.="An order with orderID=$ord_no has been placed, and needs to be shipped from local logistics vendor.<br>";
						$body.="<br><br>Regards,<br>Team Company-Name";		
						xsend_mail($logistics_email,"Company-Name - Local Logistics",$body,$from );//mail send to local logistics
				
						$from = "orders@Company-Name.com";
						$body="Hi,<br>";
						$body.="An order with orderID=$ord_no has been placed, and needs to be shipped from local logistics vendor.<br>";
						$body.="<br><br>Regards,<br>Team Company-Name";		
						xsend_mail($super_admin_email,"Company-Name - Local Logistics",$body,$from );// mail send to super admin
						$msg="1";
					}else{
						lgs_create_order($id,$msg,$sup_id);
					}
			//lgs_create_order($id,$msg,$sup_id);
			if($msg <> "1"){
				$msg_response="Record cannot be updated, please contact Company-Name support.";	
			}
		}
		
		if($msg <> "0"){
			$qry = func_update_qry1("ord_summary",$fld_arr," where ord_id=0$id and sup_id=$sup_id");
			execute_qry($qry);
			
			$from = "orders@Company-Name.com";		//mail is send to buyer about order status
			$body="Hi,<br>";
			$body.="The status of your order with order no.: $ord_no has been changed by the seller to $delivery_status.<br>";
			$body.="<br><br>Regards,<br>Team Company-Name";		
			xsend_mail($user_email,"Company-Name - Order Tracking",$body,$from);
			
			$body1="Hi,<br>";					//mail is send to logistics manager about order status
			$body1.="&nbsp;&nbsp;The status of the order with order no.: $ord_no has been changed by the seller to $delivery_status.<br>";
			$body1.="<br>Seller Id : $sup_id";
			$body1.="<br>Seller Name : $sup_company";
			$body1.="<br><br>Regards,<br>Team Company-Name";		
			xsend_mail($logistics_email,"Company-Name - Order Tracking",$body1,$from );
			$msg_response="Order details updated successfully.";
		}
	}
		$response['status']=$msg_response;
			echo json_encode($response);
	
?>