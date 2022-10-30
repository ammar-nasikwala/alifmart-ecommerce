<?php

include("db.php");
include("functions.php");

ini_set('display_errors', 1); 
$action=addslashes($_GET['action']);

if(strtoupper($action)=='DELETE')
{
	$cart_item_id=addslashes($_GET['cart_item_id']);
	$memb_id=addslashes($_GET['memb_id']);

	get_rst("select sup_id, cart_id from cart_items where cart_item_id=$cart_item_id",$row);
	$sup_id = $row["sup_id"];
	$cart_id = $row["cart_id"];
	
	execute_qry("delete from cart_items where cart_item_id=$cart_item_id");

	//print_r($sql);die;
	if($memb_id <> 0){
		execute_qry("update cart_summary set item_count=item_count-1 where sup_id=$sup_id and cart_id=$cart_id and user_id=$memb_id");
	}else{
		execute_qry("update cart_summary set item_count=item_count-1 where session_id='".session_id()."' and sup_id=$sup_id and cart_id=$cart_id and user_id IS NULL");
	}	

	execute_qry("delete from cart_summary where item_count=0");
	update_cart_summary($memb_id);

	echo "Deleted succsessfully";
}
else if(strtoupper($action)=='ADDTOCART')
{
	$cart_item_id=addslashes($_GET['cart_item_id']);
	$sql="update cart_items set item_wish=0 where cart_item_id=$cart_item_id and item_wish=1";
	//print_r($sql);die;
	$rs_find = mysqli_query($con,$sql);
	
	echo "Added to cart succsessfully";
}
else if(strtoupper($action)=='INSERT')
{	insertnew:

	$prod_id=addslashes($_GET['prod_id']);
	$price=addslashes($_GET['prod_price']);
	$qty=addslashes($_GET['qty']);
	$sup_id=addslashes($_GET['sup_id']);
	$prod_name=addslashes($_GET['prod_name']);
	$stock_no=addslashes($_GET['stock_no']);
	//$img_thumb=addslashes($_GET['img_thumb']);
	$sup_name=addslashes($_GET['sup_name']);
	$item_wish=addslashes($_GET['item_wish']);
	$memb_id=addslashes($_GET['memb_id']);
	$memb_pin=addslashes($_GET['memb_pin']);
	
	$qry = "select cart_id from cart_summary where user_id=$memb_id";
	//$qry = "select cart_id from cart_summary where user_id=46";

	 $cart = mysqli_query($con,$qry);
	 $count= mysqli_num_rows($cart);
	 
	 if($count)
	 {
		// print_r("in if");die;
			$row = mysqli_fetch_assoc($cart);
			$cart_id=$row["cart_id"];
	 }
	 else
	 {
		// print_r("in else");die;
		 $cart_id = get_next("seq_cart_id","cart_id");
		 //$_SESSION["cart_id"] = $cart_id;
	 }
	 
	 $qry = "select cart_id from cart_summary where user_id=$memb_id and sup_id=$sup_id";
	 //print_r($qry);die; 
	 $cart1 = mysqli_query($con,$qry);
	 $count= mysqli_num_rows($cart1);
	 if($count)
	 {
			$row = mysqli_fetch_assoc($cart1);
			$cart_id=$row["cart_id"];
	 }
	 else
	 {
		$fld_s_arr = array();
		$session_var=rand();
			$fld_s_arr["session_id"] = $session_var;
			$fld_s_arr["sup_id"] = $sup_id;
			$fld_s_arr["cart_id"] = $cart_id;
			if($memb_id <> 0){
				$fld_s_arr["user_id"] = $memb_id;
			}
			$sql = func_insert_qry("cart_summary",$fld_s_arr);
			execute_qry($sql);
		 //$_SESSION["cart_id"] = $cart_id;
	 }
	 
	$v_item_wish="";
	$qry = "";
	$qry2 = "";
	
	$qry = "select * from cart_items where prod_id=$prod_id and sup_id=$sup_id and memb_id=$memb_id" ;
	$obj_res = mysqli_query($con,$qry);
	$count= mysqli_num_rows($obj_res);
//print_r($qry);die;
	if($count){
		//print_r("in if");die;
		$qry2 = "";
		$row = mysqli_fetch_assoc($obj_res);
		//print_r(count($row));die;
		
			$cart_qty = $row["cart_qty"];
			$v_item_wish = $row["item_wish"];
			$cart_idremove= $row["cart_id"];
			//print_r($row);die;
			//$row = mysql_fetch_assoc($cart);
			
			if($item_wish == "1" and  $v_item_wish == 0){
			remove_item_from_cart_summary($sup_id, $cart_idremove, $prod_id);
			if($memb_id <> 0){
				execute_qry("delete from cart_items where memb_id=$memb_id and prod_id=$prod_id and sup_id=$sup_id");
			}else{
				execute_qry("delete from cart_items where session_id='".session_id()."' and prod_id=$prod_id and sup_id=$sup_id");
			}
		
		goto insertnew;
			//add_to_basket($prod_id,$sup_name,$img_thumb,$price,$qty,$sup_id,$item_wish,$memb_id);
		}else{
			if($memb_id <> 0){
				if($item_wish == $v_item_wish and $item_wish=="0") $cart_qty = $cart_qty + $qty;
				$qry2 = "update cart_items set cart_qty = $cart_qty, item_wish=$item_wish where memb_id=$memb_id and prod_id=$prod_id and sup_id=$sup_id";
				update_cart_summary($memb_id);
			}else{
				if($item_wish == $v_item_wish and $item_wish=="0") $cart_qty = $cart_qty + $qty;
				$qry2 = "update cart_items set cart_qty = $cart_qty, item_wish=$item_wish where session_id='".session_id()."' and prod_id=$prod_id and sup_id=$sup_id and memb_id IS NULL";
				update_cart_summary($memb_id);
			}
			execute_qry($qry2);
		}
		
			update_cart_summary($memb_id);
		
			$sql="select count(*) as wish_count from cart_items where memb_id=$memb_id and item_wish=1";
			//print_r($sql);die;
			$rs_find = mysqli_query($con,$sql);

			if($rs_find)
			{
				if($row3 = mysqli_fetch_assoc($rs_find)){
					$response['count_wishlist']=$row3["wish_count"];
				}
				else
				{
					$response['count_wishlist']=0;
				}
			}
			else
			{
				$response['count_wishlist']=0;
			}
			$inq=func_read_qs("inquiry");
			if($inq == 1){
				get_rst("select memb_fname,memb_email,memb_tel from member_mast where memb_id=$memb_id",$inq_detail);
				send_inquiry($inq_detail["memb_fname"],$inq_detail["memb_email"],$inq_detail["memb_tel"],$prod_name,$stock_no);
			}
			$response['flag']=1;
			echo json_encode($response);
		
	}
	else
	{
		//print_r("in else");die;
	 $sql="select prod_name,prod_lmd,prod_thumb1,prod_stockno,prod_tax_id,prod_tax_name,prod_tax_percent, prod_weight from prod_mast where prod_id=$prod_id";
	
	 $rs_find = mysqli_query($con,$sql);
	 $row = mysqli_fetch_assoc($rs_find);
	 
	  
	// $fld_arr["session_id"] = session_id();
			$fld_arr = array();
			$fld_arr["prod_id"] = $prod_id;
			$fld_arr["item_name"] = $row["prod_name"];
			$fld_arr["item_stock_no"] = $row["prod_stockno"];
			$fld_arr["item_thumb"] = $row["prod_thumb1"];
			$fld_arr["sup_name"] = $sup_name;
			
			$fld_arr["cart_qty"] = $qty;
			$fld_arr["cart_price"] = $price;
			$fld_arr["sup_id"] = $sup_id;
			$fld_arr["item_wish"] = $item_wish;			
			
			$fld_arr["tax_id"] = $row["prod_tax_id"];
			$fld_arr["tax_name"] = $row["prod_tax_name"]."";
			$fld_arr["tax_percent"] = $row["prod_tax_percent"];
			$fld_arr["tax_value"] = floatval($price) * floatval($row["prod_tax_percent"]."")/100;
			$fld_arr["cart_price_tax"] = floatval($price) + (floatval($price) * floatval($row["prod_tax_percent"]."")/100);
			
			//$fld_arr["cart_tot_price"] = floatval($price) + (floatval($price) * intval($qty) * floatval($row["prod_tax_percent"]."")/100);
			$fld_arr["cart_id"] = $cart_id;
			if($memb_id <> 0){
				$fld_arr["memb_id"] = $memb_id;
			}	
		/* 	$sql="select sup_ext_pincode from sup_ext_addr where sup_id=$sup_id LIMIT 1";
		//	
			
			$rs_find1 = mysqli_query($con,$sql);
			
			if($rs_find1)
			{
				$s_row = mysqli_fetch_assoc($rs_find1);
				//print_r($s_row);die;
	 
				$ship_amt = 0; 
				if(isset($s_row["sup_ext_pincode"]))
				{
					
					$ship_amt = get_shipping_charges($s_row["sup_ext_pincode"],$memb_pin,$row["prod_weight"]*$qty);	
					$fld_arr["ship_amt"] = $ship_amt;
				}
				else
				{
					$ship_amt = 0; 
					$fld_arr["ship_amt"] = $ship_amt;
				}
			} */
			
			//print_r($fld_arr);die;
			//print_r("i am here");die;
				$ship_amt = 0; 
			
			//if((get_rst("select sup_company from sup_mast where sup_id=$sup_id and sup_lmd=1")) && $row["prod_lmd"]==1){
				get_rst("select sup_ext_pincode from sup_ext_addr where sup_id=$sup_id LIMIT 1", $s_row);
			
			//check for local logistics if applicable
				if((substr($s_row["sup_ext_pincode"],0,3)==411 || substr($s_row["sup_ext_pincode"],0,3)==410 || substr($s_row["sup_ext_pincode"],0,3)==412) &&
				(substr($memb_pin,0,3) == 411 ||  substr($memb_pin,0,3) == 410 || substr($memb_pin,0,3) == 412)   || (floatval($row["prod_weight"]) * $qty)> 5){ // for local logistics
					$ship_amt = get_local_ship_charges($memb_pin, $sup_id, floatval($row["prod_weight"]) * $qty);
					execute_qry("update cart_summary set local_logistics = 1 where sup_id=$sup_id AND cart_id=$cart_id");
				}else{
					execute_qry("update cart_summary set local_logistics = 0 where sup_id=$sup_id AND cart_id=$cart_id");
					$ship_amt = get_shipping_charges($s_row["sup_ext_pincode"], $memb_pin, $row["prod_weight"] * $qty);
				}
			//}else{
			//	$ship_amt = 0;
			//}
			if($row["prod_weight"] < 1 && $price >= 2000){	//For free shipping offer
				$fld_arr["ship_amt"]= 0;
				$fld_arr["actual_ship_amt"]=$ship_amt;
			}else{
			$fld_arr["ship_amt"] = $ship_amt;
			} 
		
			
			$sql = func_insert_qry("cart_items",$fld_arr);
			
			execute_qry($sql);
			if($item_wish <> "1"){
				update_cart_summary($memb_id);
			}
			
			
			$sql="select count(*) as wish_count from cart_items where memb_id=$memb_id and item_wish=1";
			
			$rs_find = mysqli_query($con,$sql);

			if($rs_find)
			{
				if($row3 = mysqli_fetch_assoc($rs_find)){
					$response['count_wishlist']=$row3["wish_count"];
				}
				else
				{
					$response['count_wishlist']=0;
				}
			}
			else
			{
				$response['count_wishlist']=0;
			}
			$inq=func_read_qs("inquiry");
			if($inq == 1){
				get_rst("select memb_fname,memb_email,memb_tel from member_mast where memb_id=$memb_id",$inq_detail1);
				send_inquiry($inq_detail1["memb_fname"],$inq_detail1["memb_email"],$inq_detail1["memb_tel"],$prod_name,$stock_no);
			}
			$response['flag']=0;
			echo json_encode($response);
	}
	 
}




?>