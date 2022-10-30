<?php
require("inc_init.php");

$msg="";

$blank_msg="";

$memb_id = 0;
if(isset($_SESSION["memb_id"]) && $_SESSION["memb_id"] <> ""){
	$memb_id = $_SESSION["memb_id"];
}else{
	$blank_msg = "Please sign-in if you are a member or register to continue with raising a support ticket.";
}
	get_rst("select memb_email from member_mast where memb_id=$memb_id",$row_e);
		$email=$row_e["memb_email"];
	
	if(func_read_qs("hdnsubmit") == "y"){
	
		if(empty($_POST['subject']) || empty($_POST['description']))
		{
			$msg="Please enter all the details.";	
		}else{
			$order_id=func_read_qs("order_id");		//getting details
			$subject=func_read_qs("subject");
			$description=func_read_qs("description");
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
								$msg="There is a problem to send email.";
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
								$msg="There is a problem to send email.";
							}
			}
			
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Company-Name - Support</title>
<link href="styles/styles.css" rel="stylesheet" type="text/css" />
<link href="styles/bannerstyle.css" rel="stylesheet" type="text/css" />
<script src="scripts/scripts.js" type="text/javascript"></script>
<script type="text/javascript" src="scripts/animatedcollapse.js"></script>
<script type="text/javascript">

function validate(){
			
		if(document.getElementById("description").value==""){
			alert("Please Enter All The Details");
			return false;
				}else{
					display_gif();
					document.frm.submit();
					return true;
				}	
	}
</script>
</head>
<body>

<? require("header.php"); ?>
<div id = "gif_show" style="display:none"></div>
<div id="content_hide">
	<div id="contentwrapper">
	<div class="center-panel">
    <div class="you-are-here">
		<p align="left"> YOU ARE HERE:<span class="you-are-normal"><a href="index.php" title="Home Page">Home</a> | Support</span> </p>
    </div>

<?if($blank_msg<>""){ ?>
		<br><br>
	<center>
		<div class="alert alert-info"><?=$blank_msg?></div>
	</center>
		<br><br><br><br><br><br>
<?}else{?>

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
				<br>
				<td colspan="2" align="left" class="table-bg"><div align="left">
				<p>Enter Details</p>
				</div></td>
			</tr>
			<tr>
				<td width="31%" align="right" class="table-bg-left"><div align="right">
				<label for="Email"></label>
				<p>Subject :</p>
				</div></td>
				<td width="69%" align="left" xclass="table-bg">
					<select name="subject" class="form-control textbox-lrg" >
						<?=create_cbo("select subject_name,subject_name from subject where type='b'")?>">										
					</select>*
				</td>
			</tr>
			<tr>
				<td align="right" class="table-bg2"><div align="right">
				<label for="First Name2"></label>
				<p>Order ID :</p>
				</div></td>
				<td align="left" class="table-bg2"><input name="order_id" id="order_id"  class="form-control textbox-lrg" type="text" title="Order_id" tabindex="6" size="20" maxlength="20" />
				</td>
			</tr>
			<tr>
				<td width="31%" align="right" class="table-bg-left"><div align="right">
				<label for="Email"></label>
				<p>Email ID:</p>
				</div></td>
				<td width="69%" align="left" xclass="table-bg"><input name="email" id="email"  class="form-control textbox-lrg" disabled type="text" id="120" title="Email" tabindex="1" size="20" maxlength="50" value="<?=$email?>"/>*
			</tr>
			<tr>
				<td align="right" class="table-bg2"><div align="right">
				<label for="Mobile No."></label>
				<p>Description:</p>
				</div></td>
				<td align="left" class="table-bg2"><textarea rows="5" class="form-control textbox-lrg" cols="50" name="description" id="description" maxlength="500"></textarea>* (Max character 500)
				</td></td>
			</tr>	  
		<table width="100%"  border="0" align="center" class="list">
			<tr>
				<td align="right" class="table-bg" colspan=10></td>
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
	<?}?>
	</div>
	</div>
	</div>
<? require("footer.php"); ?>

</body>
<script src="scripts/chat.js" type="text/javascript"></script>
</html>
