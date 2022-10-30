<?php

ini_set('display_errors', 1); 
include("db.php");
include("functions.php");



	
		$response['status'] = "Available";
		$pincode = func_read_qs("pincode");
		if(get_rst("select city_id from city_mast where pincode='$pincode'",$row,$rst)){
			$response['status'] = "Available";
		}else{
			$response['status'] = "Not Available";
		}
		
		echo json_encode($response);
	
?>
