<?php

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
	//echo($sql);die;
	execute_qry($sql);	
	
	get_rst("select user_name,user_email from user_mast where user_type='FM'",$row_a);
	get_rst("select memb_fname from member_mast where memb_id=(select memb_id from ord_details where cart_id=0$cart_id)",$row_b);
	$mail_b = "Dear ".$row_a["user_name"]."<br> ".$row_b["memb_fname"]." has updated the payment details, please do needful.";
	send_mail($row_a['user_email'],"Buyer- ".$row_b["memb_fname"]." Payment updates",$mail_b);
	$mail_ba = "Hello, <br> ".$row_b["memb_fname"]." has updated the payment details, please do needful.";
	send_mail("info@Company-Name.com","Buyer- ".$row_b["memb_fname"]." Payment updates",$mail_ba);
	
$response['status']="success";

	echo json_encode($response);
?>