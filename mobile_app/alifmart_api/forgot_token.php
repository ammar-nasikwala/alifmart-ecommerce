<?php

date_default_timezone_set('Asia/Calcutta');

ini_set('display_errors', 1); 

include("db.php");
include("functions.php");

$msg = "";
	if(isset($_GET["sup_email"]) && isset($_GET["sup_pwd"])){		
		$sup_email = $_GET["sup_email"];
		$sup_pwd = $_GET["sup_pwd"];
		$sup_contact_name = "";
		$sup_seller_name = "";
		$count=mysqli_query($con,"SELECT sup_seller_token, sup_contact_name FROM sup_mast WHERE sup_email='$sup_email' and sup_pwd='$sup_pwd' ");
	
		if(mysqli_num_rows($count) <= 0)	// email check
		{
			$msg['status'] = 'The email address or password is incorrect.'; 
		}
		else
		{
			$row = mysqli_fetch_assoc($count);
			$to=$sup_email;
			$subject="Seller token details";
			$header="Company-Name<noreply@Company-Name.com>";
			$sup_contact_name = $row["sup_contact_name"];
			$body="Hi ".$sup_contact_name.",<br>";
			$body.="<p>We have received your forgot token request. Following is your token:.</p><br>";
			$body.="Seller Token: ".$row["sup_seller_token"];
			$body.="<br><br>Regards,<br>Team Company-Name.com";
							
			if(send_mail($to,$subject,$body)){
				$msg["site_msg"]="Seller token has been sent to your email.";
			//	echo "<meta http-equiv='refresh' content='0;url=../seller/login.php'>";
			//	exit();
			}else{
				$msg['status']="Due to technical problems we are unable to process your request, please try again later.";					
			}
		
		}
	
		
		echo json_encode($msg);
	}
	
?>
