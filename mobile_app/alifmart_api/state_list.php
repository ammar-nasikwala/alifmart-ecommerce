<?php

include("db.php");
include("functions.php");

ini_set('display_errors', 1); 


			$sql="select * from state_mast";
			$rst = mysqli_query($con, $sql);
	
		$response['state_list']=array();
		while($row = mysqli_fetch_assoc($rst))
		{
			$temp['state_name']=$row['state_name'];
			$temp['state_zone']=$row['state_zone'];
			$temp['state_id']=$row['state_id'];
			
			
			array_push($response['state_list'],$temp);
		}
		
		//print_r($row);die;
					
		echo json_encode($response);
					
					
?>