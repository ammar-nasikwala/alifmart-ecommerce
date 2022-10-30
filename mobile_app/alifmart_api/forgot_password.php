<?php

date_default_timezone_set('Asia/Calcutta');

ini_set('display_errors', 1); 

include("db.php");
include("functions.php");

$msg = "";
	if(isset($_GET["sup_email"])){	
//print_r($_GET)	;die;
		$sup_email = $_GET["sup_email"];
		$sup_pwd = "";
		$sup_contact_name = "";
		$sup_seller_name = "";
		$count=mysqli_query($con,"SELECT sup_activation, sup_contact_name FROM sup_mast WHERE sup_email='$sup_email'");
	
		if(mysqli_num_rows($count) <= 0)	// email check
		{
			$msg['status'] = 'This email has not been registered.'; 
		}
		else
		{
			$row = mysqli_fetch_assoc($count);
			$sup_activation = $row["sup_activation"];
			$sup_contact_name = $row["sup_contact_name"];;
			$to=$sup_email;
			$subject="Password Reset";
			$header="Company-Name<noreply@Company-Name.com>";
			$fgt_base_url="http://".$_SERVER["SERVER_NAME"]."/seller/reset_pwd.php?code=";
			$body="Hi ".$sup_contact_name.",<br>";
			$body.="<p>We have received your forgot password request. Please click the link below to reset password.</p><br>";
			$body.="<a href='".$fgt_base_url."$sup_activation'>Reset Password</a></p><br>";
			$body.="<br>Regards,<br>Team Company-Name.com";
							
			if(send_mail($to,$subject,$body)){
				$msg["site_msg"]="Password reset link has been sent to your email.";
				//echo "<meta http-equiv='refresh' content='0;url=../seller/login.php'>";
				//exit();
			}else{
				$msg['status']="Due to technical problems we are unable to process your request, please try again later.";					
			}
		
		}
		
		echo json_encode($msg);
	}
	
?>
