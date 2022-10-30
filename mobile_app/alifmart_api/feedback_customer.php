<?php

include("db.php");
include("functions.php");

ini_set('display_errors', 1); 

$fld_s_arr = array();
					
	$fld_s_arr["q1_ratting"] = func_read_qs("q1");
	$fld_s_arr["q2_ratting"] = func_read_qs("q2");
	$fld_s_arr["q3_ratting"] = func_read_qs("q3");
	$fld_s_arr["q4_ratting"] = func_read_qs("q4");
	$fld_s_arr["q5_ratting"] = func_read_qs("q5");
	$fld_s_arr["q6_ratting"] = func_read_qs("q6");
	$fld_s_arr["q7_ratting"] = func_read_qs("q7");
	$fld_s_arr["q8_ratting"] = func_read_qs("q8");
	$fld_s_arr["q9_ratting"] = func_read_qs("q9");
	$fld_s_arr["q10_ratting"] = func_read_qs("q10");
	$fld_s_arr["suggestion"] = func_read_qs("suggestion");
	
		$qry = func_insert_qry("feedback_buyer",$fld_s_arr);
		execute_qry($qry);

	$msg="Thank you for providing your valuable inputs.";
	$response['msg']=$msg;				
	echo json_encode($response);				
?>