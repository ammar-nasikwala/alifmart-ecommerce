<?php

include("db.php");

include("functions.php");

ini_set('display_errors', 1); 

$sup_id=$_GET["sup_id"];		//getting details
			$subject_data=$_GET["subject"];
			$description=$_GET["description"];	
			$ticket_id= get_max("seller_support","ticket_id");		
			
			get_rst("select sup_email,sup_company from sup_mast where sup_id=$sup_id",$row_s);
			$sup_email=$row_s["sup_email"];
			$sup_company=$row_s["sup_company"];
			get_rst("select subject_id from subject where subject_name='".$subject_data."'",$row);
			$subject_id=$row["subject_id"];
			$subject_year=date("Y");
			$ticket="AM";
			$ticket.=str_pad($subject_id, 2, '0', STR_PAD_LEFT);
			$ticket.=str_pad($subject_year, 5, '-', STR_PAD_LEFT);
			$ticket.="-";
			$ticket.=str_pad($ticket_id, 6, '0', STR_PAD_LEFT);
			mysqli_query($con,"INSERT INTO seller_support SET ticket_id='".$ticket_id."',sup_id='".$sup_id."', subject='".$subject_data."', description = '".$description."'");
		
						//mail is send to user...
				$user_body="Dear $sup_company,<br>"	;
				$user_body.= "<br>We thank you for writing to us related to the Subject :&#34;$subject_data&#34;." ;
				$user_body.="Our seller support team is investigating your query & will get back to you for further understanding of your concern or a solution to your concern.<br>";
				$user_body.="<br>Use Ticket ID for further Reference.<br>";
				$user_body.="We value your concern & will try our best to resolve it. For any further communication, please reply with the same subject line for faster resolution.<br>";
				$user_body.="<br><br>Thanks You <br>Company-Name.com <br>Seller Support Team";
				
				$from = "support@Company-Name.com";		
					if(xsend_mail($sup_email,"$subject_data, Ticket ID:$ticket",$user_body,$from )){	
							$msg="Your record has been saved successfully. ";
							}else{ 
								$msg="There was a problem sending email.";
							}
			
					//mail is send to support....
				$support_body = "Hi ,<br>";
				$support_body.= "<br>We have new ticket raised at Seller Support with following details :<br>";
				$support_body.= "Ticket ID: $ticket<br>Seller ID: $sup_id<br>Seller Name: $sup_company<br>Subject : $subject_data<br>Description : $description";
				$support_body.= "<br><br>From<br>Company-Name Support Team ";
				
				$from = "noreply@Company-Name.com";		
					if(xsend_mail("support@Company-Name.com","Ticket ID:$ticket,Subject: $subject_data, Seller Name: $sup_company",$support_body,$from )){	
							$msg="Your record has been saved successfully. ";
							}else{ 
								$msg="There was a problem sending email.";
							}
			
			
			$response['status']=$msg;
			echo json_encode($response);
	
?>