<?php

include("db.php");

include("functions.php");

ini_set('display_errors', 1); 

			$order_id=$_GET["order_id"];		//getting details
			$email=$_GET["email"];
			$subject=$_GET["subject"];
			$description=$_GET["description"];	
			$ticket_id= get_max("support","ticket_id");
			
			get_rst("select subject_id from subject where subject_name='".$subject."' and type='b'",$row);		//generating ticket
			$subject_id=$row["subject_id"];
			$subject_year=date("Y");
			$ticket="AM";
			$ticket.=str_pad($subject_id, 2, '0', STR_PAD_LEFT);
			$ticket.="B";
			$ticket.=str_pad($subject_year, 5, '-', STR_PAD_LEFT);
			$ticket.="-";
			$ticket.=str_pad($ticket_id, 6, '0', STR_PAD_LEFT);	
			
			mysqli_query($con,"INSERT INTO support SET ticket_id='".$ticket_id."',order_id='".$order_id."',email='".$email."', subject = '".$subject."', description = '".$description."'");
		
					//mail is send to user...
				$user_body = "Thank you for contacting us. We have got your problem ,we will contact you as soon as possible.";
				$user_body.="<br>Use Ticket ID for further Reference.";
				$user_body.="<br><br>Thanks and Regards,<br>Support Company-Name";
				$from = "support@Company-Name.com";		
					if(xsend_mail($email,"$subject, Ticket ID:$ticket",$user_body,$from )){	
							$msg="Your record has been saved successfully. ";
							}else{ 
								$msg="There was a problem sending email.";
							}
			
					//mail is send to support....
				$support_body = "Hi ,<br>";
				$support_body.= "We have new ticket raised with following details :<br>";
				$support_body.= "Ticket ID: $ticket<br>Order ID: $order_id<br>Subject : $subject<br>Description : $description";
				$support_body.= "<br><br>Thanks";
				$from = "noreply@Company-Name.com";		
					if(xsend_mail("support@Company-Name.com","Ticket ID: $ticket, Subject : $subject, Order ID: $order_id",$support_body,$from )){	
							$msg="Your record has been saved successfully. ";
							}else{ 
								$msg="There was a problem sending email.";
							}

			
			$response['status']=$msg;
			echo json_encode($response);
	
?>