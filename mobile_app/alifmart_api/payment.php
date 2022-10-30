<?php

include("db.php");
include("functions.php");

ini_set('display_errors', 1); 

$memb_id = func_read_qs("memb_id");
$session_cart_id = func_read_qs("cart_id");
$session_user_type = func_read_qs("user_type");

$response['status']="fail";
if(get_rst("select pay_method from cart_summary where user_id=$memb_id",$row)){
	
	$pay_method=$row["pay_method"];

	switch($pay_method){
				/* case "CC":
					pay_by_cc();
					break;
					
				case "CCZ":
					pay_by_ccZ();
					break; */

				default:
				if($pay_method=="CC"){
						$status=func_read_qs("status");
						$txnid=func_read_qs("txnid");
						$cart_id=func_read_qs("cart_id");
						save_order($pay_method,$memb_id,$session_cart_id,$session_user_type,$session_cart_id);
						$sql = "update ord_summary set pg_status='$status', pg_txnid='$txnid',pay_status='Paid' where cart_id=$cart_id";
						execute_qry($sql);
						
						

						//print_r(json_encode($message));die;
						//sendcustomernotification_order($cart_id,$memb_id);
						sendcustomernotification_order($cart_id,$memb_id,$pay_method);
						sendsellernotification_order($cart_id); 
						//sendnotification(json_encode($message));

					}
					else
					{
						save_order($pay_method,$memb_id,$session_cart_id,$session_user_type);
						$message['title']="Order Details";
						
						//print_r(json_encode($message));die;
						//sendcustomernotification_order($session_cart_id,$memb_id);
						sendcustomernotification_order($session_cart_id,$memb_id,$pay_method);
						sendsellernotification_order($session_cart_id); 
					}
					//save_order($pay_method,$memb_id,$session_cart_id,$session_user_type);
				//	header('location: ord_confirmation.php');
				//	js_redirect("ord_confirmation.php");
					$response['status']="success";
					break;

			}
}
			
	echo json_encode($response);
?>