<?php session_start();

require("../lib/inc_library.php");
require("../lib/inc_connection.php");

$msg="";

	if(func_read_qs("hdnsubmit") == "y"){
	
		if(empty($_POST['subject']) || empty($_POST['description']))
		{
			$msg="Please enter all the details.";	
		}else{						
			$sup_id=$_SESSION["sup_id"];		//getting details
			$subject_data=func_read_qs("subject");
			$description=func_read_qs("description");
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
								$msg="There is a problem to send email.";
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
			}
			
	}
?>

	

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Support</title>
<link type="text/css" rel="stylesheet" href="css/main.css" />
<link type="text/css" rel="stylesheet" href="css/collapse_menu.css" />
<link type="text/css" rel="stylesheet" href="css/tooltip.css" />
<!--<script type="text/javascript" src="js/jquery-1.2.6.pack.js"></script>-->
<script type="text/javascript" src="js/ddaccordion.js"></script>
<script type="text/javascript" src="js/collapse_menu.js"></script>
<script type="text/javascript" src="js/logohover.js"></script>
<script type="text/javascript" src="js/tooltip.js"></script>
<script type="text/javascript">

function validate(){
			
		if(document.getElementById("description").value==""){
			alert("Please Enter All The Details");
			return false;
				}else{
					document.frm.submit();
					return true;
				}	
	}

</script>
<style>
.textboxwidth{
	width:350px !important;
	height:80px !important;
}
</style>
</head>
<body>
<?php
require("inc_chk_session.php");
?>
<div id="maincontainer" class="table-responsive">
<table class="tbl_dash_main">

<tr>
<td colspan="2">
<?php require("inc_top-menu.php") ?>


<!--div id="panels"!-->
<tr>
<td align="left" width="210px">
<?php require("inc_left-menu.php") ?>
</td>
<td align="left">
<div id="centerpanel">
<div id="contentarea">
<h2>Company-Name Seller Support </h2>
<!--<span style="float:right">Registration status: Pending Approval</span> -->
<div id="buttons">
</div>
<?if($msg<>""){?>
<div class="alert alert-info">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <?=$msg?>
</div>
<?}?>
<form name="frm" method="post" action=""  xonsubmit="javascript: return validate();">
	<input type="hidden" name="hdnsubmit" value="y">
		<table width="100%"  border="0" align="center" class="list">
			
			<tr>
				<td  class="table-bg-left"><div align="left">
				<p>Subject :</p>
				</div></td>
				<td align="left" xclass="table-bg">
					<select name="subject"  >									
						<?=create_cbo("select subject_name,subject_name from subject  where type='s'")?>">																
					</select>*
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="left" class="table-bg2"><div align="left">
				<p>Description:</p>
				</div></td>
				<td align="left" class="table-bg2"><textarea rows="10" class="textboxwidth" cols="50" name="description" id="description" maxlength="500"></textarea>* (Max character 500)
				</td></td>
			</tr>	  
			<table width="100%"  border="0" align="center" class="list">
				<tr>
					<td align="right" class="table-bg" colspan=10>&nbsp;</td>
				</tr>
				<tr>
					<td align="center">
						<input type="submit" class="btn btn-warning" onclick="javascript: return validate();" value=" Submit " name="submit" tabindex="15" >
					</td>
				</tr>
			</table>
		</table>
<br>
<br>	
</form>

</div>
</div>
</td>
</tr>
<tr>
<td colspan="2">
<?php require("inc_footer.php"); ?>
</td>
</table>

</body>
</html>