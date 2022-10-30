<?php

include("db.php");
include("functions.php");

ini_set('display_errors', 1); 

	if(strtoupper($_GET['action'])=="DELETE")
	{
		if(isset($_GET['addr_id']))
		{
			$addr_id=$_GET['addr_id'];
			
			$qry = "delete from sup_ext_addr where addr_id=$addr_id";
			$result=mysqli_query($con, $qry);
			if($result){
				$msg = "delete_success";
			}else{
				$msg = "Record delete failed. Please try after sometime";	
			}
			
			//print_r($row);die;
			$response['status']=$msg;
			echo json_encode($response);
		}	
	}
	else if(strtoupper($_GET['action'])=="UPDATE"||strtoupper($_GET['action'])=="INSERT")
	{
		if(isset($_GET['addr_id']))
		{
			$addr_id=$_GET['addr_id'];
		}
		
		
		$sup_id = $_GET["sup_id"];	
		$sup_company = $_GET["sup_company"];	
		$sup_ext_address_type = $_GET["sup_ext_address_type"];
		$sup_ext_address = $_GET["sup_ext_address"];
		$sup_ext_state = $_GET["state_name"];	
		$sup_ext_city = $_GET["sup_ext_city"];
		$sup_ext_pincode = $_GET["sup_ext_pincode"];
		$sup_ext_contact_no = $_GET["sup_ext_contact_no"];
		
		$fld_arr = array();
		$fld_arr["sup_id"] = $sup_id;
		
		$fld_arr["sup_ext_name"] = $sup_company;
		
		$fld_arr["sup_ext_address"] = $sup_ext_address;
		
		$fld_arr["sup_ext_state"] = $sup_ext_state;
		
		$fld_arr["sup_ext_city"] = $sup_ext_city;
		
		$fld_arr["sup_ext_address_type"] = $sup_ext_address_type;
		$fld_arr["sup_ext_pincode"] = $sup_ext_pincode;
		$fld_arr["sup_ext_contact_no"] = $sup_ext_contact_no;
		
		$qry = "";
		
		if(isset($_GET["addr_id"]))
		{
			$qry = func_update_qry("sup_ext_addr",$fld_arr," where addr_id=".$addr_id);
		}
		else
		{
			$qry = func_insert_qry("sup_ext_addr",$fld_arr);
		}
		$result=mysqli_query($con, $qry);
	
		if($result){
			$msg = "insert_success";
		}
		else{
			$msg = "update_success";
		}	
		
			//print_r($row);die;
			$response['status']=$msg;
			echo json_encode($response);
	}
		
					
?>