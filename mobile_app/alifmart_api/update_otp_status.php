<?php

date_default_timezone_set('Asia/Calcutta');

ini_set('display_errors', 1); 


//$db_servername = '103.21.58.112';
include("db.php");
include("functions.php");
 
$memb_id=$_GET['memb_id'];

$sms_verify_code=$_GET['sms_verify_code'];
$sms_verify_status=1;


$update = mysqli_query($con,"UPDATE member_mast SET sms_verify_code='".$sms_verify_code."',sms_verify_status='".$sms_verify_status."',memb_status=1,memb_act_status=1 WHERE  memb_id='".$memb_id."'");


 $response['status']="success";
				  
echo json_encode($response);
?>