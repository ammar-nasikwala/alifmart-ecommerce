<?php 
//print_r("njnjdsn");die;

include("db.php");


	//print_r($_GET);die; 
	//getting the request values 
	$device_token = $_GET['device_token'];
	$user_id  = $_GET['user_id'];
	$type  = $_GET['type'];
	$status  = $_GET['login_status'];
	$device  = $_GET['device'];
	
	
	//connecting to database 
	//$con = mysqli_connect('localhost','leomehti_taxi','dbg9096064619','leomehti_firebase');
	
	$sql="select * from notification where type='".$type."' and device='".$device."' and user_id='".$user_id."'";

	$response['success']="";
	$rs_find = mysqli_query($con,$sql);
	if($rs_find){
		
		if($row = mysqli_fetch_assoc($rs_find)){
			$sql = "update notification set device_token='$device_token',type='$type',login_status=$status where type='".$type."' and device='".$device."' and user_id='".$user_id."'";
			if(mysqli_query($con,$sql)){
				$response['success']='success';
			}else{
				$response['success']='failure';
			}
		}
		else{
			
			$sql = "INSERT INTO notification (device_token,user_id,type,login_status,device) VALUES ('$device_token',$user_id,'$type',$status,'$device')";
						
			if(mysqli_query($con,$sql)){
				$response['success']='success';
			}else{
				$response['success']='failure';
			}
			
		}
	}
	
	

	//creating an sql query 
	
	
	
	echo json_encode($response);
?>