<?php

include("db.php");
include("functions.php");
ini_set('display_errors', 1); 

	$sup_company = func_read_qs("sup_company");
	$sup_contact_name = func_read_qs("sup_contact_name");
	$sup_business_type = func_read_qs("sup_business_type");
	$sup_email = func_read_qs("sup_email");
	$sup_contact_no = func_read_qs("sup_contact_no");
	$sup_pwd = func_read_qs("sup_pwd");
	$sup_cpwd = func_read_qs("sup_cpwd");
	$sup_contact_per_name = func_read_qs("sup_contact_per_name");
	
	
	
	$sup_activation=md5($sup_email.time()); 
		
		// 4 Digit random number code for sms verification.
		$sms_verify_code = mt_rand(1000, 9999);		
		
		$fld_arr = array();
		$fld_arr["sup_company"] = $sup_company;
		$fld_arr["sup_business_type"] = $sup_business_type;
		$fld_arr["sup_contact_name"] = $sup_contact_name;
		$fld_arr["sup_contact_per_name"] = $sup_contact_per_name;
		$fld_arr["sup_email"] = $sup_email;
		$fld_arr["sup_contact_no"] = $sup_contact_no;
		$fld_arr["sup_pwd"] = $sup_pwd;
		$fld_arr["sup_active_status"] = 0;
		$fld_arr["sup_activation"] = $sup_activation;
		$fld_arr["sup_mou_accept"] = 0;
		$fld_arr["sms_verify_code"] = $sms_verify_code;
		$fld_arr["sms_verify_status"] = 0;
		$fld_arr["sup_lmd"] = 1;
		
		$prevQuery = mysqli_query($con,"SELECT * FROM sup_mast WHERE sup_contact_no = '".$sup_contact_no."'");
		if(mysqli_num_rows($prevQuery) > 0){
			$msg = "This mobile number has already been registered.";
		}
		else
		{
		$qry = func_insert_qry("sup_mast",$fld_arr);
		$result=mysqli_query($con, $qry);
		if (mysqli_errno($con) <>0) {
			if (mysqli_errno($con) == 1062) {
				$msg = "This email has already been registered.";
			}else{
				$msg = mysqli_error($con);
			}
		}else{
		
			//Sending confirmation SMS
			$sms_msg = "Hi ".$sup_company.",This is a verification SMS for your mobile number as part of registration on Company-Name.com, your verification code is ".$sms_verify_code;			
			$output=send_sms($sup_contact_no,$sms_msg);
			if($output == "202"){
				$msg = "Invalid mobile number! Please re-enter the correct number.";
				goto END;
			}
			
			// sending verification email
			$to=$sup_email;
			$subject="Company-Name-seller email verification";
			
			$mail_fld_arr = array();
			$mail_fld_arr["sup_contact_name"] = $sup_company;
			$mail_fld_arr["sup_email"] = $sup_email;
			$mail_fld_arr["sup_pwd"] = $sup_pwd;
			$base_url=get_base_url()."/seller/activation.php?code=";
		//	http://alif.cloudapp.net
			$mail_fld_arr["act_link"] = $base_url.$sup_activation;
		//	print_r($mail_fld_arr["act_link"]);die;
			$body = push_body("seller_activation.txt",$mail_fld_arr);
			$from = "welcome@Company-Name.com";
			if(xsend_mail($to,$subject,$body,$from)){
				$msg="success";
				//$msg = "Thank You for registering on Company-Name.com. Activate your account by clicking on the activation link sent to '$sup_email'";
				$s_flag = "";
			}
			else {
				$msg = "There was a problem sending activation link to your email address";
			}
			$body="";
			$body.="<p>Congrats, a new seller has registered with the system & the email activation link is being sent to his registered email id. The seller details are as below:</p><br> ";
			$body.="<br>Estsblishment Name : $sup_company";
			$body.="<br>Contact Person Name : $sup_contact_name";
			$body.="<br>Registered Email : $sup_email";
			$body.="<br>Registered Mobile No. : $sup_contact_no";
			$body.="<br><br>Regards,<br> Company-Name Admin";
			
			xsend_mail("sell@Company-Name.com",$subject,$body,$from);
		}
		}
	END:
	$response['status']=$msg;
	echo json_encode($response);
?>