<?php

ini_set('display_errors', 1); 
include("db.php");
include("functions.php");



	if(isset($_GET['memb_email']))
	{
		$response['email'] = "Available";
		$memb_email = func_read_qs("memb_email");
	
		if(get_rst("select memb_id from member_mast where memb_email='$memb_email'",$row,$rst)){
			$response['email'] = "This email already exists. Please provide another one.";
		}
	}
		//echo("<response>$response</response>");
	

	if(isset($_GET['memb_tel']))
	{
		$response['mobile'] = "Available";
		$memb_tel = func_read_qs("memb_tel");
		if(get_rst("select memb_id from member_mast where memb_tel='$memb_tel'",$row,$rst)){
			$response['mobile'] = "This phone number already exists. Please provide another one.";
		}
		//echo("<response>$response</response>");
	
	}
	
	echo json_encode($response);
	
?>
