<?php

include("db.php");
include("functions.php");

ini_set('display_errors', 1); 

//$action=addslashes($_GET['action']);
//print_r($_FILES);die;
//print_r($_GET);die;
$email = func_read_qs("email");
	$pwd = func_read_qs("pwd");
	$cpwd = func_read_qs("cpwd");
	$title = func_read_qs("title");
	$fname = func_read_qs("fname");
	$sname = func_read_qs("sname");
	$add1 = func_read_qs("add1");
	$add2 = func_read_qs("add2");
	$city = func_read_qs("city");
	$country = func_read_qs("country");
	$pcode = func_read_qs("pcode");
	$memb_state = func_read_qs("memb_state");
	$tele = func_read_qs("tele");	
	$memb_contact_per_name = func_read_qs("memb_contact_per_name");	
	$memb_newsletter = func_read_qs("memb_newsletter");	
 
	 
	$sms_verify_code = mt_rand(1000, 9999);		
	$memb_id = get_max("member_mast","memb_id");

	//print_r($memb_id);die;
	$fld_arr = array();
 
	$fld_arr["memb_id"] = $memb_id;
	$fld_arr["memb_contact_per_name"] = $memb_contact_per_name;
	$fld_arr["memb_fname"] = $fname;
	$fld_arr["memb_sname"] = $sname;
	$fld_arr["memb_title"] = $title;
	$fld_arr["memb_email"] = $email;
	$fld_arr["memb_add1"] = $add1;
	$fld_arr["memb_add2"] = $add2;	
	$fld_arr["memb_city"] = $city;
	$fld_arr["memb_state"] = $memb_state;
	$fld_arr["memb_country"] = $country;
	$fld_arr["memb_postcode"] = $pcode;
	$fld_arr["memb_tel"] = $tele;
	$fld_arr["memb_pwd"] = $pwd;
	$fld_arr["memb_act_id"] = get_act_id($fld_arr["memb_email"]);
	$fld_arr["sms_verify_code"] = $sms_verify_code;
	$fld_arr["sms_verify_status"] = 0;
	$fld_arr["ind_buyer"] = 0;
	
	
	$fld_addr_arr = array();
		$fld_addr_arr["memb_id"] = $memb_id;
		$fld_addr_arr["ext_addr_name"] = "Primary address";
		$fld_addr_arr["ext_addr_default"] = 1;
		$fld_addr_arr["ext_addr1"] = $add1;
		$fld_addr_arr["ext_addr2"] = $add2;
		$fld_addr_arr["ext_addr_state"] = $memb_state;
		$fld_addr_arr["ext_addr_city"] = $city;
		$fld_addr_arr["ext_addr_pin"] = $pcode;
		$fld_addr_arr["ext_addr_contact"] = $tele;
		$addr_qry = func_insert_qry("memb_ext_addr",$fld_addr_arr);
		mysqli_query($con, $addr_qry);
		
	
	if($memb_newsletter<>"")
	{
		$fld_arr["memb_newsletter"] = $memb_newsletter;
	}
	else{
		$fld_arr["memb_newsletter"]=0;
	}
	
	if(isset($_GET['ind_buyer'])){
	//	print_r("in if");die;
				$memb_cstn=func_read_qs("memb_cstn");
				//print_r($memb_cstn);die;
				$memb_vat=func_read_qs("memb_vat");
				$memb_company=func_read_qs("memb_company");
				$memb_vat_doc = "";
				$memb_cst_doc = "";
				if(isset($_FILES['files'])){					
					$errors= array();
					$maxsize    = 2097152;
					$acceptable = array(
					'application/pdf',
					'image/jpeg',
					'image/jpg',
					'image/gif',
					'image/png'
					);
					$upload_doc_arr = array();
					for($i=0; $i<2; $i++){		
						$file_name = $_FILES['uploaded_file']['name'][$i];
						if($file_name <> ""){
							$file_tmp =$_FILES['files']['tmp_name'][$i];
							$desired_dir=$_SERVER['DOCUMENT_ROOT']."/extras/user_data/id-".$memb_id;
							
							if(!in_array($_FILES['files']['type'][$i], $acceptable) && (!empty($_FILES["files"]["type"][$i]))) {
								$errors[] = 'Invalid file type. Only PDF, JPG, GIF and PNG types are accepted.';
							}
							if((filesize($file_tmp) >= $maxsize) || (filesize($file_tmp) == 0)) {
								$errors[] = 'File is too large. File size must be less than 2 megabytes.';
							}
							
							if(empty($errors)==true){
								if(is_dir($desired_dir)==false){
									mkdir("$desired_dir", 0700);		// Create directory if it does not exist
								}
								$file_path="";
								$file_path="extras/user_data/id-".$memb_id."/".$file_name;
								move_uploaded_file($file_tmp,$file_path);
								$imageFileType = strtolower(pathinfo($file_path,PATHINFO_EXTENSION));
								$upload_doc_arr[$i]=img_resize_db($file_path, 900, 520, $imageFileType);
							}else{
									$act="0";
									$msg= "Upload Failed!! ".$errors[0] ;
									break;
							}
						}else{
							$upload_doc_arr[$i]="";
						}
					}
					$memb_cst_doc = $upload_doc_arr[0];
					$memb_vat_doc = $upload_doc_arr[1];
					
					if($memb_cst_doc <> "") $fld_arr["memb_cst_doc"] = $memb_cst_doc;
					if($memb_vat_doc <> "") $fld_arr["memb_vat_doc"] = $memb_vat_doc;
					$fld_arr["memb_cstn"] = $memb_cstn;
					$fld_arr["memb_vat"] = $memb_vat;
					$fld_arr["memb_company"] = $memb_company;
					$fld_arr["ind_buyer"] = 1;
				}	
				$fld_arr["memb_cstn"] = $memb_cstn;
					$fld_arr["memb_vat"] = $memb_vat;
					$fld_arr["memb_company"] = $memb_company;
					$fld_arr["ind_buyer"] = 1;
			}
			
		//	print_r($fld_arr);die;
			if (mysqli_errno($con) <>0) {
				if (mysqli_errno($con) == 1062) {
					$incomp_msg = "You are already a member. Please try login or forgot password to retrieve your password.";
				}else{
					$incomp_msg = mysqli_error($con);
				}
			}else{
				
				$sqlIns = func_insert_qry("member_mast",$fld_arr);
				mysqli_query($con,$sqlIns);
			
				//Send confirmation msg to user.
				$sms_msg = "Hi ".$fname.",This is a verification SMS for your mobile number as part of registration on Company-Name.com, your verification code is ".$sms_verify_code;;
				$output = send_sms($tele,$sms_msg);
				
				if($output == "202"){
					$incomp_msg = "There was a problem verifying your Mobile number Please check the number or try again later.";
					break;
				}		
				
				$fld_arr["act_link"] = get_base_url()."/register.php?id=".$fld_arr["memb_act_id"];
				
				$mail_body = push_body("buyer_activation.txt",$fld_arr);
				$from = "welcome@Company-Name.com";		
				if(xsend_mail($fld_arr["memb_email"],"Company-Name - Buyer account verification email",$mail_body,$from )){
					$incomp_msg="success";
					$msg['register_id']=$memb_id;
					$s_flag = "1";
				}else{ 
					$incomp_msg="There was a problem sending email to your email address.";
				}
				
				$body="Congratulations!!,<br>";

				$body.="&nbsp;&nbsp;A new Buyer has been added to our system with the following details.<br>";
				$body.="<br>Name : $fname";
				$body.="<br>City : $city";
				
				$body.="<br><br>Regards,<br>Company-Name Admin";		
				xsend_mail("support@Company-Name.com","Company-Name - Buyer account verification email",$body,$from );
		
			}
		$msg['status']=$incomp_msg;
		echo json_encode($msg);
?>