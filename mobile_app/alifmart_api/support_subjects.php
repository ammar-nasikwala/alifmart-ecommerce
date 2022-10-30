<?php

include("db.php");
include("functions.php");

ini_set('display_errors', 1); 

//$memb_id=addslashes($_GET['memb_id']);
$sql="select * from subject  where type='s'";
//print_r($sql);die;
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 
$subject['seller_subjects']=array();

	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			
			//$row["sup_company"];
			$temp['subject_id'] = $row["subject_id"];
			$temp['subject_name'] = $row["subject_name"];
			$temp['type'] = $row["type"];
			
			array_push($subject['seller_subjects'],$temp);
		}
		
	}
	
$sql="select * from subject  where type='b'";
//print_r($sql);die;
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 
$subject['customer_subjects']=array();

	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			
			//$row["sup_company"];
			$temp1['subject_id'] = $row["subject_id"];
			$temp1['subject_name'] = $row["subject_name"];
			$temp1['type'] = $row["type"];
			
			array_push($subject['customer_subjects'],$temp1);
		}
		
	}
	
	
	echo json_encode($subject);
?>