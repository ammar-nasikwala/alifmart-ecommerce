<?php

include("db.php");
include("functions.php");

ini_set('display_errors', 1); 
$memb_id=addslashes($_GET['memb_id']);
$user_type=addslashes($_GET['user_type']);

$sql="select os.cart_id";
$sql = $sql.",ord_no";
$sql = $sql.",ord_date";
$sql = $sql.",sum(ord_total) as 'order_value'";
$sql = $sql.",sum(item_count) as 'no_of_products'";
$sql = $sql.",pay_method as 'payment_type'";
$sql = $sql.",pay_status as 'payment_status'";
$sql = $sql.",delivery_status";
$sql = $sql.",logistics_status";
$sql = $sql." from ord_summary os inner join ord_details od on os.cart_id=od.cart_id and os.user_id=od.memb_id";
$sql_where = " where user_id=".$memb_id;// and user_type='".$user_type."'";
$sql = $sql.$sql_where;
$sql = $sql." group by ord_id order by ord_id desc ";

//print_r($sql);die;
$rs_find = mysqli_query($con,$sql);
//$cart_id = func_read_qs("id");

//print_r($rs_find);die; 
$order_products['order_products']=array();

	 if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			
			$temp['cart_id'] = $row["cart_id"];
			$temp['ord_no'] = $row["ord_no"];
			$temp['ord_date'] = $row["ord_date"];
			$order_value = $row["order_value"];				//change related to coupon feature
			$temp['no_of_products'] = $row["no_of_products"];
			$temp['payment_type'] = $row["payment_type"];
			$temp['payment_status'] = $row["payment_status"];
			$temp['delivery_status'] = $row["delivery_status"];
			$temp['logistics_status'] = $row["logistics_status"];
			//$temp['item_thumb'] = base64_encode($row["item_thumb"]);
			
			//change related to coupon feature...
			$cart_id = $row["cart_id"];
			$coupon_amt = 0;
			
			$rs_find_coupon = mysqli_query($con, "select coupon_amt from ord_coupon where cart_id = $cart_id" );
			
			$row_c = mysqli_fetch_assoc($rs_find_coupon);
			get_rst("select sum(cancel_coupon) as cancel_coupon,sum(cancel_amount) as cancel_amount from track_refund where cart_id=$cart_id",$ro_c);
			get_rst("select sum((cart_price_tax * cart_qty) + ship_amt) as ord_total from ord_items where cart_id=$cart_id",$row_t);
				if($ro_c["cancel_amount"] <> ""){
			
					$total_amount = $row_t["ord_total"] - ($ro_c["cancel_amount"] + $ro_c["cancel_coupon"]);
					$total_amount_payable = $total_amount - ($row_c["coupon_amt"] - $ro_c["cancel_coupon"]);
					$temp['order_value']=FormatNumber($total_amount_payable,2);
				}else{
					$temp['order_value']=$row_t["ord_total"] - $row_c["coupon_amt"];
				}
			
			array_push($order_products['order_products'],$temp);
		} 
		
	}
		
	echo json_encode($order_products);
?>