<?php

print_r("in bank");die;
include("db.php");
include("functions.php");

ini_set('display_errors', 1); 

$response=array();

$bt_bank_name=func_read_qs("bank_name");
$bt_bank_branch=func_read_qs("bank_branch");
$bt_ref_no=func_read_qs("ref_no");
$bt_date=func_read_qs("date");
$cart_id=func_read_qs("cart_id");
	
$fld_arr = array();
$fld_arr["bt_bank_name"] = $bt_bank_name;
$fld_arr["bt_bank_branch"] = $bt_bank_branch;
$fld_arr["bt_ref_no"] = $bt_ref_no;
$fld_arr["bt_date"] = $bt_date;

	$sql = func_update_qry("ord_details",$fld_arr," where cart_id=$cart_id");
	//echo($sql);
	execute_qry($sql);	
	
$response['status']="success";

	echo json_encode($response);
?>