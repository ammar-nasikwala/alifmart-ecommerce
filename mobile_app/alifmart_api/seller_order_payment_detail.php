<?php

//print_r("ksndjhs");die;
date_default_timezone_set('Asia/Calcutta');

include("db.php");
include("functions.php");
//print_r($_GET);

ini_set('display_errors', 1); 

$sup_id=addslashes($_GET['sup_id']);
$id=addslashes($_GET['order_id']);

$response['invoice_info']=array();

if(get_rst("select * from invoice_mast where invoice_id=0$id",$row)){
	$temp['pay_total'] = $row["pay_total"];
	$temp['pay_status'] = $row["pay_status"];
	$temp['ord_no'] = $row["ord_no"];
	$ord_no = $row["ord_no"];
	$temp['pay_comission'] = $row["pay_comission"];
	$temp['service_tax'] = $row["service_tax"];
	
	get_rst("select ord_total,ord_date, item_total, vat_value, shipping_charges, cart_id from ord_summary where sup_id=0$sup_id and ord_no='".$ord_no."' ", $row_s);
	$temp['ord_total'] = $row_s["ord_total"];
	$temp['vat_value'] = $row_s["vat_value"];
	$temp['shipping_charges'] = $row_s["shipping_charges"];
	$temp['item_total'] = $row_s["item_total"];
	$temp['cart_id'] = $row_s["cart_id"];
	$temp['ord_date'] = $row_s["ord_date"];
	$cart_id = $row_s["cart_id"];
	
		$actual_ship_amt=0;
		get_rst("select actual_ship_amt from ord_items where cart_id='".$cart_id."' and sup_id=$sup_id",$row_ship,$rst_ship);
			do{
				$actual_ship_amt = $row_ship["actual_ship_amt"]+$actual_ship_amt;
			}while($row_ship = mysqli_fetch_assoc($rst_ship));
	$temp['free_shipping_charges'] = $actual_ship_amt;
	
	array_push($response['invoice_info'],$temp);
}

$item_total=0;
$vat_value=0;

$response['order_items']=array();
if(get_rst("select * from ord_items where cart_id='".$cart_id."' and sup_id=$sup_id",$row,$rst))
{
	do{
					$cart_price = 0;
					$item_color = "";
					$cart_qty = 0;
					$cart_price_tax = 0;
					$tax_percent = 0;
					if($row["item_buyer_action"].""<>""){
						$item_color = "#d9534f";
						if($row["new_seller_price"] <> ""){
							$cart_price = $row["new_seller_price"];
						}else{
							$cart_price = $row["cart_price"];
						}
						$cart_qty = $row["cart_qty"];
						$net_price = $cart_price * $cart_qty;
						$tax_percent = $row["tax_percent"];
						$tax_value = $net_price * ($tax_percent/100);
						$cart_price_tax = $cart_price + $tax_value;	
					}else{	
						if($row["new_seller_price"] <> ""){
							$cart_price = $row["new_seller_price"];
						}else{
							$cart_price = $row["cart_price"];
						}
						$cart_qty = $row["cart_qty"];
						$tax_percent = $row["tax_percent"];
						$net_price = $cart_price * $cart_qty;
						$tax_value = $cart_price * ($tax_percent/100);
						$cart_price_tax = $cart_price + $tax_value;	
						$item_total = $item_total + ($cart_price * $cart_qty);
						$vat_value = $vat_value + ($tax_value * $cart_qty);						
					}
					$prod_id = $row["prod_id"];
					
					$temp1['prod_id']=$prod_id;
					$temp1['cart_price']=$cart_price;
					$temp1['cart_qty']=$cart_qty;
					$temp1['tax_percent']=$tax_percent;
					$temp1['tax_value']=$tax_value;
					$temp1['cart_price_tax']=$cart_price_tax;
					//$temp1['item_total']=$item_total;
					//$temp1['vat_value']=$vat_value;
					$temp1['item_name']=$row["item_name"];
					$temp1['item_stock_no']=$row["item_stock_no"];
					$temp1['item_buyer_action']=$row["item_buyer_action"];
					$temp1['item_thumb']=base64_encode($row["item_thumb"]);
					
					
				array_push($response['order_items'],$temp1);
		
	}while($row = mysqli_fetch_assoc($rst));
	
					$shipping_charges = $row_s["shipping_charges"];
					$ord_total = $item_total + $vat_value + $shipping_charges;
					$response['ord_total']=FormatNumber($ord_total,2);
					$response['item_totals']=FormatNumber($item_total,2);
					$response['vat_value']=FormatNumber($vat_value,2);
}
		
	echo json_encode($response);
?>