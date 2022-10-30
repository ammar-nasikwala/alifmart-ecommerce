<?php

date_default_timezone_set('Asia/Calcutta');

ini_set('display_errors', 1); 


//$db_servername = '103.21.58.112';
include("db.php");
include("functions.php");
 
$phoneno=$_GET['tele'];
$sms_verify_code = mt_rand(1000, 9999);	
$email=$_GET["email"];
$fld_arr = get_act_id($_GET["email"]);
$sms_verify_status=0;
$memb_cstn_social=$_GET["memb_cstn"];
$memb_vat_social=$_GET["memb_vat"];
$memb_company_social=$_GET["memb_company"];
$ind_buyer=$_GET["ind_buyer"];
$fname=$_GET["fname"];

$update = mysqli_query($con,"UPDATE member_mast SET memb_tel='".$phoneno."',sms_verify_code='".$sms_verify_code."',memb_act_id='".$fld_arr."',sms_verify_status='".$sms_verify_status."',memb_cstn='".$memb_cstn_social."',memb_vat='".$memb_vat_social."',memb_company='".$memb_company_social."',ind_buyer='".$ind_buyer."' WHERE  memb_email='".$email."'");


    $sms_msg = "Hi ".$fname.",This is a verification SMS for your mobile number as part of registration on Company-Name.com, your verification code is ".$sms_verify_code;
    $output = send_sms($phoneno,$sms_msg);
	$incomp_msg="success";
	if($output == "202"){
		$incomp_msg = "There was a problem verifying your Mobile number Please check the number or try again later.";
		goto END;
	}
	$response['sms_verify_code']=$sms_verify_code;
	END: 
    $response['status']=$incomp_msg;	  
	echo json_encode($response);
?>