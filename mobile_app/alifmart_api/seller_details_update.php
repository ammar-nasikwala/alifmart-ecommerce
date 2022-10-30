<?php

include("db.php");
include("functions.php");

ini_set('display_errors', 1); 

$sup_id=addslashes($_GET['sup_id']);

if(isset($_GET["sup_pan"]))
{
	$sup_pan =	 	$_GET["sup_pan"];
	$sup_vat = 	$_GET["sup_vat"];
	$sup_cstn = 	$_GET["sup_cstn"];
	//$sup_email = $_GET["sup_email"];

	$fld_arr["sup_pan"] = $sup_pan;
	$fld_arr["sup_vat"] = $sup_vat;
	$fld_arr["sup_cstn"] = $sup_cstn;

//$fld_arr["sup_email"] = $sup_email;

	$qry = func_update_qry("sup_mast",$fld_arr," where sup_id=".$sup_id);
	$result = mysqli_query($con,$qry);
		
		if($result){
				$msg = "The details has been updated successfully";
		}else{
			$msg = "Update failed! Please check details or try again after some time.";
		}

}

if(isset($_GET["sup_company"]))	
{
	$sup_company = $_GET["sup_company"];
	$sup_contact_name = $_GET["sup_contact_name"]; 	
	$sup_business_type = $_GET["sup_business_type"];
	$sup_contact_per_name = $_GET["sup_contact_per_name"];
	//$sup_email = $_GET["sup_email"];

	$fld_arr["sup_company"] = $sup_company;
	$fld_arr["sup_business_type"] = $sup_business_type;
	$fld_arr["sup_contact_name"] = $sup_contact_name;
	$fld_arr["sup_contact_per_name"] = $sup_contact_per_name;

	//print_r($fld_arr);die;
	//$fld_arr["sup_email"] = $sup_email;

	$qry = func_update_qry("sup_mast",$fld_arr," where sup_id=".$sup_id);
			$result = mysqli_query($con,$qry);
			
			if($result){
					$msg = "The details has been updated successfully";
			}else{
				$msg = "Update failed! Please check details or try again after some time.";
			}
			
}

if(isset($_GET["sup_bk_acc"]))	
{
	$sup_bk_acc = 	$_GET["sup_bk_acc"];
	$sup_bk_ifsc = 	$_GET["sup_bk_ifsc"];
	$sup_bk_name = 	$_GET["sup_bk_name"];
	$sup_bk_brname = 	$_GET["sup_bk_brname"];
	//$sup_email = $_GET["sup_email"];

	$fld_arr["sup_bk_acc"] = $sup_bk_acc;
	$fld_arr["sup_bk_ifsc"] = $sup_bk_ifsc;
	$fld_arr["sup_bk_name"] = $sup_bk_name;
	$fld_arr["sup_bk_brname"] = $sup_bk_brname;

	//$fld_arr["sup_email"] = $sup_email;

	$qry = func_update_qry("sup_mast",$fld_arr," where sup_id=".$sup_id);
			$result = mysqli_query($con,$qry);
			
			if($result){
					$msg = "The details has been updated successfully";
			}else{
				$msg = "Update failed! Please check details or try again after some time.";
			}
			
}

if(isset($_GET["sup_address"]))	
{
	$sup_address = 	$_GET["sup_address"];
	$state_name = 	$_GET["state_name"];
	$sup_pincode = 	$_GET["sup_pincode"];
	$sup_alt_contact_no = 	$_GET["sup_alt_contact_no"];
	$sup_ext_city = $_GET["sup_city"];
	$sup_ext_address_type = "Billing address";	

	 $sql11="select * from sup_mast where sup_id=$sup_id";
//print_r($sql);die;
	$rs_find11 = mysqli_query($con,$sql11);
	if($rs_find11){
			
			if($row11 = mysqli_fetch_assoc($rs_find11)){
					$sup_ext_name=$row11["sup_company"];
					$sup_ext_contact_no=$row11["sup_contact_no"];
			}
	} 
	$fld_addr_arr["sup_ext_name"] = $sup_ext_name;
	$fld_addr_arr["sup_ext_contact_no"] = $sup_ext_contact_no;
	$fld_addr_arr["sup_ext_address"] = $sup_address;
	$fld_addr_arr["sup_ext_state"] = $state_name;
	$fld_addr_arr["sup_ext_pincode"] = $sup_pincode;
	$fld_addr_arr["sup_ext_address_type"] = $sup_ext_address_type;
	$fld_addr_arr["sup_ext_city"] = $sup_ext_city;
	$fld_addr_arr["sup_id"] = $sup_id;

	$fld_arr["sup_alt_contact_no"] = $sup_alt_contact_no;
	

	$rst = mysqli_query($con,"select * from sup_ext_addr where sup_id=$sup_id LIMIT 1");
		$addr_row="";
		$addr_row = mysqli_fetch_assoc($rst);
		if($addr_row <> ""){
			$addr_qry = func_update_qry("sup_ext_addr",$fld_addr_arr," where addr_id=".$addr_row["addr_id"]);
		}else{
			$addr_qry = func_insert_qry("sup_ext_addr",$fld_addr_arr);
		}

		//echo $addr_qry;die;
		//$addr_qry = func_insert_qry("sup_ext_addr",$fld_addr_arr);
		$addr_result=mysqli_query($con, $addr_qry);
		
		$qry = func_update_qry("sup_mast",$fld_arr," where sup_id=".$sup_id);
		$result = mysqli_query($con,$qry);
		
		if($result){
				$msg = "The details has been updated successfully";
		}else{
			$msg = "Update failed! Please check details or try again after some time.";
		}	
}


if(isset($_GET["sup_lmd"]))	
{
	$sup_lmd = 	$_GET["sup_lmd"];
	$fld_arr["sup_lmd"] = $sup_lmd;
	

	$qry = func_update_qry("sup_mast",$fld_arr," where sup_id=".$sup_id);
	$result = mysqli_query($con,$qry);
			
			if($result){
					$msg = "The details has been updated successfully";
			}else{
				$msg = "Update failed! Please check details or try again after some time.";
			}
			
}			
		$response['status']=$msg;
		echo json_encode($response);

?>