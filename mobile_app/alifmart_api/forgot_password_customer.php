<?php

date_default_timezone_set('Asia/Calcutta');

ini_set('display_errors', 1); 

include("db.php");
include("functions.php");

		$email = addslashes($_GET["email"]);
		if($email <> ""){
			
			$rsMemb= mysqli_query($con,"select * from member_mast where memb_email='".$email."'");
			if(mysqli_num_rows($rsMemb)>0){
				$row=mysqli_fetch_assoc($rsMemb);
				$member_name = $row["memb_title"]." ".$row["memb_fname"]." ".$row["memb_sname"];
				$memb_pwd = $row["memb_pwd"];
					
				//$mail_body=push_body("forgot-passwordmail",$fld_arr);
				$subject = "Login details";
				$header="Company-Name<noreply@Company-Name.com>";
			
				$body="Hi ".$member_name.",<br>";
				$body.="<p>Your login details are as follows:<br></p>";
				$body.="<br>Username : $email";
				$body.="<br>Password : $memb_pwd";
				$body.="<br><br>Regards,<br>For Company-Name.com";
						
				if(send_mail($email,$subject,$body,$header)){
					$incomp_msg="success";
				}else{
					$incomp_msg="Due to technical problems we are unable to accept your Enquiry Form, please try again later.";					
				}
			}else{
				$incomp_msg = "This email has not been registered."; 
			}
		}else{
			$incomp_msg = "Please enter your Email.";
		}
		
		$msg['status']=$incomp_msg;
		echo json_encode($msg);
	
?>
