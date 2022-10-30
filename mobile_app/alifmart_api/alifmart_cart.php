<?php

include("db.php");
include("functions.php");

ini_set('display_errors', 1); 

$memb_id=addslashes($_GET['memb_id']);
$sql="select * from cart_items where memb_id=$memb_id and item_wish=0";
//print_r($sql);die;
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 
$wish_list['cart_list']=array();

	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			
			$temp['cart_item_id'] = $row["cart_item_id"];
			$cart_id = $row["cart_id"];		//change related to coupon feature
			$temp['cart_id'] = $row["cart_id"];
			$temp['cart_price'] = $row["cart_price"];
			$temp['cart_qty'] = $row["cart_qty"];
			$temp['prod_id'] = $row["prod_id"];
			$sup_id=$row["sup_id"];
			if(get_rst("select sup_company from sup_mast where sup_id=$sup_id and sup_lmd=1"))
			{
				$temp['cart_price_tax'] = ($row["cart_price_tax"]*$row["cart_qty"])+$row["ship_amt"];
				$temp['ship_amt'] = $row["ship_amt"];
			}
			else
			{
				$temp['cart_price_tax'] = ($row["cart_price_tax"]*$row["cart_qty"]);
				$temp['ship_amt'] = 0;
			}
			$temp['tax_percent'] = $row["tax_percent"];
			$temp['item_name'] = $row["item_name"];
			$temp['item_thumb'] = base64_encode($row["item_thumb"]);
		//	$temp['ship_amt'] = $row["ship_amt"];
			
			$sql="select sup_status,out_of_stock from prod_sup where prod_id=".$row["prod_id"]." and sup_id=".$row["sup_id"];
			$rs_find1 = mysqli_query($con,$sql);
			
			if($rs_find1)
			{
				$rowcount=mysqli_num_rows($rs_find1);
				//print_r($rowcount);die;
				if($rowcount>0)
				{
					$row_ps = mysqli_fetch_assoc($rs_find1);
					$temp['sup_status'] = $row_ps["sup_status"];
					$temp['out_of_stock'] = $row_ps["out_of_stock"];
				}
				else
				{
					$temp['sup_status'] =0;
					$temp['out_of_stock'] = 0;
				}
			}

			array_push($wish_list['cart_list'],$temp);
		}
		
		/* $qry = "select * from cart_items where memb_id=$memb_id order by sup_id";
		$ord_pay_total = 0;
		
			if(get_rst($qry,$row_sum,$rst_sum)){
				do{
						$ord_total = $row_sum["cart_price_tax"];
						$ord_pay_total = $ord_pay_total + $ord_total;
						
				}while($row_sum = mysqli_fetch_assoc($rst_sum));			
			}
			 */
			
		$qry = "select * from cart_summary where user_id=$memb_id and item_count IS NOT NULL order by sup_id";
		$ord_pay_total = 0;
		
			if(get_rst($qry,$row_sum,$rst_sum)){
				do{
						$ord_total = $row_sum["ord_total"];
						$ord_pay_total = $ord_pay_total + $ord_total;
						
				}while($row_sum = mysqli_fetch_assoc($rst_sum));
				
				
			}
		$wish_list['ord_pay_total']=$ord_pay_total;
	}
	
	//change code start 

		$sql="select count(*) as count_wish from cart_items where memb_id=$memb_id and item_wish=1";

		$rs_find = mysqli_query($con,$sql);

		$wish_list['wish_list_count']=0;
		
		if($rs_find){
			
			if($row = mysqli_fetch_assoc($rs_find)){
				$wish_list['wish_list_count']=$row['count_wish'];
			}
		}
		
	//change code end
	//changes is related to coupon feature
	$wish_list['coupon_amount'] = "0";
	$wish_list['coupon_text'] = "0";
	$wish_list['coupon_id'] = 0;
	$coupon_amt = 0;
	if(get_rst("select coupon_id, coupon_amt from ord_coupon where cart_id=0$cart_id", $row_c)){
		get_rst("select coupon_code, max_discount_value from offer_coupon where coupon_id=0".$row_c["coupon_id"], $row_oc);
		
		if($row_c["coupon_amt"] > $row_oc["max_discount_value"])
		{
			$coupon_amt = $row_oc["max_discount_value"];
			$wish_list['coupon_amount'] = $coupon_amt;
		}else{
			$coupon_amt = $row_c["coupon_amt"];
			$wish_list['coupon_amount'] = $coupon_amt;
		}
	$wish_list['coupon_id'] = $row_c["coupon_id"];
	$wish_list['coupon_text'] = $row_oc["coupon_code"];
	$total_with_coupon = $ord_pay_total - $coupon_amt;
	$wish_list['ord_pay_total'] = $total_with_coupon;
	}
	echo json_encode($wish_list);
?>