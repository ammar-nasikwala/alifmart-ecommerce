<?php

include("db.php");
include("functions.php");

ini_set('display_errors', 1); 

$id = func_read_qs("order_id");
//$act = func_read_qs("act");
$sup_id = func_read_qs("sup_id");

$order_products['ord_details']=array();
$sql="select * from ord_summary where ord_id=0$id and sup_id=$sup_id";
//print_r($sql);die;
if(get_rst("select * from ord_summary where ord_id=0$id and sup_id=$sup_id",$row_s,$rst_s)){
	$temp['cart_id'] = $row_s["cart_id"];
	$cart_id = $row_s["cart_id"];
	$temp['delivery_status'] = $row_s["delivery_status"];
	$temp['pay_status'] = $row_s["pay_status"];
	$temp['pay_method'] = $row_s["pay_method"];
	$temp['pay_method_name'] = $row_s["pay_method_name"];
	$temp['buyer_action'] = $row_s["buyer_action"];
	$temp['way_billl_no'] = $row_s["way_billl_no"];
	//$temp['buyer_action'] = $row_s["buyer_action"];
	$temp['ord_no'] = $row_s["ord_no"];
	$temp['ord_date'] = $row_s["ord_date"];
	$temp['pkg_weight_kgs'] = $row_s["pkg_weight_kgs"];
	$temp['pkg_weight'] = $row_s["pkg_weight"];
	$temp['pkg_height'] = $row_s["pkg_height"];
	$temp['pkg_width'] = $row_s["pkg_width"];
	$temp['pkg_depth'] = $row_s["pkg_depth"];
	$temp['pkg_count'] = $row_s["pkg_count"];
	$temp['pickup_datetime'] = $row_s["pickup_datetime"];
	//$date=date_create($row_s["pickup_datetime"]);

	//$temp['pickup_datetime']=date_format($date,"d/m/Y");
	
	$temp['item_total'] = $row_s["item_total"];
	$temp['vat_value'] = $row_s["vat_value"];
	$temp['vat_percent'] = $row_s["vat_percent"];
	$temp['ord_total'] = $row_s["ord_total"];
	$shipping_charges = $row_s["shipping_charges"]."";
	if($shipping_charges.""==""){
			$temp['v_shipping_charges'] = "As per the delivery area";
		}else{
			$temp['v_shipping_charges'] = formatNumber($shipping_charges,2);
		}
	
}else{
	//header('location: order_list.php');
	//js_redirect("order_list.php");
}

if(get_rst("select * from ord_details where cart_id='".$cart_id."'",$row)){
	$temp['bill_name'] = $row["bill_name"];
	$temp['bill_email'] = $row["bill_email"];
	$bill_email= $row["bill_email"];
	$temp['bill_add1'] = $row["bill_add1"];
	$temp['bill_add2'] = $row["bill_add2"];
	$temp['bill_city'] = $row["bill_city"];
	$temp['bill_state'] = $row["bill_state"];
	$temp['bill_country'] = $row["bill_country"];
	$temp['bill_postcode'] = $row["bill_postcode"];
	$temp['bill_tel'] = $row["bill_tel"];

	$temp['ship_name'] = $row["ship_name"];
	$temp['ship_email'] = $row["ship_email"];
	$temp['ship_add1'] = $row["ship_add1"];
	$temp['ship_add2'] = $row["ship_add2"];
	$temp['ship_city'] = $row["ship_city"];
	$temp['ship_state'] = $row["ship_state"];
	$temp['ship_country'] = $row["ship_country"];
	$temp['ship_postcode'] = $row["ship_postcode"];
	$temp['ship_tel'] = $row["ship_tel"];
	
	$temp['ord_instruct'] = $row["ord_instruct"];
}

		if(get_rst("select * from member_mast where memb_email='".$bill_email."'",$row_m)){
			$temp['memb_company'] = $row_m["memb_company"];
			$temp['ind_buyer'] = $row_m["ind_buyer"];
		}

		/* $qry = "Select middle_panel from cms_pages where page_name='returns'";
				if(get_rst($qry, $row)){
					$temp['return_policy'] = $row["middle_panel"];
				} */
				
	array_push($order_products['ord_details'],$temp);
	
	$order_products['item_details']=array();
	
	$ord_pay_total = 0;
		//do{
	$sup_id = $row_s["sup_id"];
	get_rst("select * from ord_items where cart_id='".$cart_id."' and sup_id=$sup_id",$row,$rst);
	
	do{
		//print_r($row);die;
			$temp1['cart_price'] = 0;
			$temp1['item_color'] = "";
			if($row["item_buyer_action"].""<>""){
				$temp1['item_color'] = "#d9534f";
			}else{
				if($row["new_seller_price"] <> ""){
					$temp1['cart_price'] = $row["new_seller_price"];
				}else{
					$temp1['cart_price'] = $row["cart_price"];
				}				
			}
			$temp1['cart_qty'] = $row["cart_qty"];
			$temp1['prod_id']  = $row["prod_id"];
			$temp1['cart_price_tax'] = $row["cart_price_tax"];
			$temp1['tax_percent'] = $row["tax_percent"];
			
			$temp1['user_type'] = func_var($row["user_type"]);
			$temp1['sup_name'] = $row["sup_name"];
			$temp1['item_name'] = $row["item_name"];
			$temp1['item_stock_no'] = $row["item_stock_no"];
			$temp1['item_thumb'] = base64_encode($row["item_thumb"]);
			
			array_push($order_products['item_details'],$temp1);
			
	}while($row = mysqli_fetch_assoc($rst));
		
	
	array_walk_recursive($order_products,"convert_to_utf8");
	echo json_encode($order_products);
?>