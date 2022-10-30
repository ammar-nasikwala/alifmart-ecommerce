<?php

include("db.php");
include("functions.php");

ini_set('display_errors', 1); 

//$action=addslashes($_GET['action']);

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
	
	//$sms_verify_code = mt_rand(1000, 9999);		
	$memb_id =func_read_qs("memb_id");

	//print_r($memb_id);die;
	$fld_arr = array();

	//$fld_arr["memb_id"] = $memb_id; 
	$fld_arr["memb_contact_per_name"] = $memb_contact_per_name;
	$fld_arr["memb_fname"] = $fname;
	$fld_arr["memb_sname"] = $sname;
	$fld_arr["memb_title"] = $title;
	//$fld_arr["memb_email"] = $email;
	$fld_arr["memb_add1"] = $add1;
	$fld_arr["memb_add2"] = $add2;	
	$fld_arr["memb_city"] = $city;
	$fld_arr["memb_state"] = $memb_state;
	$fld_arr["memb_country"] = $country;
	$fld_arr["memb_postcode"] = $pcode;
	$fld_arr["memb_tel"] = $tele;
	
	
	$fld_addr_arr = array();
	$fld_addr_arr["ext_addr_name"] = "Primary address";
	$fld_addr_arr["ext_addr1"] = $add1;
	$fld_addr_arr["ext_addr2"] = $add2;
	$fld_addr_arr["ext_addr_state"] = $memb_state;
	$fld_addr_arr["ext_addr_city"] = $city;
	$fld_addr_arr["ext_addr_pin"] = $pcode;
	$fld_addr_arr["ext_addr_contact"] = $tele;
	$addr_qry = func_update_qry("memb_ext_addr",$fld_addr_arr, " where memb_id=".$memb_id." and ext_addr_default=1");
	mysqli_query($con, $addr_qry);		
	
	
	if($memb_newsletter<>"")
	{
		$fld_arr["memb_newsletter"] = $memb_newsletter;
	}
	else{
		$fld_arr["memb_newsletter"]=0;
	}
	
	
	
	if($pwd<>""){
				$fld_arr["memb_pwd"] = $pwd;
			}
	//$fld_arr["memb_act_id"] = get_act_id($fld_arr["memb_email"]);
	//$fld_arr["sms_verify_code"] = $sms_verify_code;
//	$fld_arr["sms_verify_status"] = 0;
	//$fld_arr["ind_buyer"] = 0;
	
	
	
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
						$file_name = $_FILES['files']['name'][$i];
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
								$file_path=$_SERVER['DOCUMENT_ROOT']."/extras/user_data/id-".$memb_id."/".$file_name;
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
					 $fld_arr["memb_cst_doc"] = $upload_doc_arr[0];
					 $fld_arr["memb_vat_doc"] = $upload_doc_arr[1];
				}	
				$fld_arr["memb_cstn"] = $memb_cstn;
				$fld_arr["memb_vat"] = $memb_vat;
				$fld_arr["memb_company"] = $memb_company;
				$fld_arr["ind_buyer"] = 1;
			}
			else{
				$fld_arr["ind_buyer"] = 0;
			}
			
			$sqlIns = func_update_qry("member_mast",$fld_arr," where memb_id=".$memb_id);
			
		//	print_r($fld_arr);die;
			if (!mysqli_query($con,$sqlIns)) {
				if (mysqli_errno($con) == 1062) {
					$incomp_msg = "The updated email is already in use by another person. Please provide another one or keep the existing one.";
				}else{
					$incomp_msg = mysqli_error($con);
				}
			}else{
				
				$mail_body = push_body("buyer_update_details.txt",$fld_arr);
				
				if(send_mail($email,"Company-Name - Details Updated",$mail_body )){
					$incomp_msg="success";
					$s_flag = "1";				
				}else{
					$incomp_msg="There was a problem sending mail to your email address.";
				}
		
			}
		$msg['status']=$incomp_msg;
		echo json_encode($msg);
?>