<?php

include("db.php");
include("functions.php");

ini_set('display_errors', 1); 



		if(isset($_GET['sup_id']))
		{
			$sup_id=$_GET['sup_id'];
			$sql="select * from sup_ext_addr where sup_id=$sup_id";
			$rst = mysqli_query($con, $sql);
			
		}
		
		$response['address']=array();
		while($row = mysqli_fetch_assoc($rst))
		{
			$temp['addr_id']=$row['addr_id'];
			$temp['sup_id']=$row['sup_id'];
			$temp['sup_ext_name']=$row['sup_ext_name'];
			$temp['sup_ext_address']=$row['sup_ext_address'];
			$temp['sup_ext_state']=$row['sup_ext_state'];
			$temp['sup_ext_city']=$row['sup_ext_city'];
			$temp['sup_ext_address_type']=$row['sup_ext_address_type'];
			$temp['sup_ext_contact_no']=$row['sup_ext_contact_no'];
			$temp['sup_ext_pincode']=$row['sup_ext_pincode'];
			
			array_push($response['address'],$temp);
		}
		
		//print_r($row);die;
					
		echo json_encode($response);
					
					
?>