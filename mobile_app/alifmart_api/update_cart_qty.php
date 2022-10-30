<?php

include("db.php");
include("functions.php");

ini_set('display_errors', 1); 

//$action=addslashes($_GET['action']);
//print_r($_FILES);die;
//print_r($_GET);die;
$cart_item_id = func_read_qs("cart_item_id");
$memb_id = func_read_qs("memb_id");
$qty = func_read_qs("qty");
$pincode = func_read_qs("pincode");
$android = func_read_qs("android");

if($android == ""){
		

	//print_r($memb_id);die;
	$fld_arr = array();

	$fld_arr["cart_qty"] = $qty;
	
	$sqlIns =func_update_qry("cart_items",$fld_arr," where cart_item_id=".$cart_item_id);
	mysqli_query($con,$sqlIns);	
	
	get_rst("select cart_id from cart_items where cart_item_id=0$cart_item_id", $rowc);		//change related to shipping calculation...
	
	update_shipping_charges($pincode, $rowc["cart_id"]);
	
	update_cart_summary($memb_id);
	$msg['status']	="success";

}else{

	get_rst("select prod_id from cart_items where cart_item_id=0$cart_item_id", $rowp);
	$prodid=$rowp["prod_id"];
	
	get_rst("select min_qty from prod_mast where prod_id=0$prodid", $rowmq);
	$minqty=$rowmq["min_qty"];
	if($qty < $minqty){
		$msg['msg']	="1";
	}else{
	//print_r($memb_id);die;
	$fld_arr = array();

	$fld_arr["cart_qty"] = $qty;
	
	$sqlIns =func_update_qry("cart_items",$fld_arr," where cart_item_id=".$cart_item_id);
	mysqli_query($con,$sqlIns);	
	
	get_rst("select cart_id from cart_items where cart_item_id=0$cart_item_id", $rowc);		//change related to shipping calculation...
	
	update_shipping_charges($pincode, $rowc["cart_id"]);
	
	update_cart_summary($memb_id);
	$msg['msg']	="0";
	}
	$msg['status']	="success";
}	
	echo json_encode($msg);
?>