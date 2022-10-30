<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php 
session_start();
require_once("inc_init.php"); 
$incomp_msg = "";
include '../lib/inc_library.php';
?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Company-Name - Forgot Password</title>
<link href="styles/styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="scripts/jquery-1.6.1.min.js"></script>
<script src="scripts/scripts.js" type="text/javascript"></script>
<script type="text/javascript" src="scripts/animatedcollapse.js"></script>
</head>

<?php

	if(isset($_POST["memb_email"])){		
		$memb_email = $_POST["memb_email"];
		$sup_pwd = "";
		$sup_contact_name = "";
		$sup_seller_name = "";
		$count=mysqli_query($con,"SELECT memb_act_id, memb_fname FROM member_mast WHERE memb_email='$memb_email'");
			
		if(mysqli_num_rows($count) <= 0)	// email check
		{
			$incomp_msg = 'This email has not been registered.'; 
		}
		else
		{
			$row = mysqli_fetch_assoc($count);
			$memb_act_id = $row["memb_act_id"];
			$memb_contact_name = $row["memb_fname"];;
			$to=$memb_email;
			$subject="Password Reset";
			$header="Company-Name<noreply@Company-Name.com>";
			
			$body="Hi ".$memb_contact_name.",<br>";
			$body.="<p>We have received your forgot password request. Please click the link below to reset password.</p><br>";
			$body.="<a href='http://".$_SERVER["SERVER_NAME"]."/reset_pwd.php?code=$memb_act_id'>Reset Password</a></p><br>";
			$body.="<br>Regards,<br>Team Company-Name.com";
							
			if(send_mail($to,$subject,$body)){
				$incomp_msg="Password reset link has been sent to your email.";	
			}else{
				$incomp_msg="Due to technical problems we are unable to process your request, please try again later.";					
			}
		
		}
		if($memb_email=="")
		{
			$incomp_msg="Please Enter Your Email";
		}
	}	
?>

<body>
<?php require_once("header.php"); ?>
<div id="contentwrapper">
<div id="contentcolumn">
  <div class="center-panel">
	<div class="you-are-here">
		<p align="left">
			YOU ARE HERE:<span class="you-are-normal"><a href="index.php" title="Home Page">Home</a> | Forgot Password</span>
		</p>
	</div>


<h1>Forgot Password</h1>

<?php
if($incomp_msg <> "") { ?>
<div class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <?=$incomp_msg?>
</div>
<?php } ?>

<form name="frm" action="" method="post" onSubmit="return validate()">
<input type="hidden" name="hdnsubmit" value="y">
<table width="100%" border="0" align="center" class="list">
	
	<tr><td height=25 align='center' colspan=2>&nbsp;
		<br>
		</td>
	</tr> 
	<tr>
	    <td width="19%" align="center" class="table-bg"><label for="E-mail"><p>Enter Email</p></label></td>
	    <td align="center" class="table-bg"><input name="memb_email" type="text" id="email" tabindex="1" size="30" maxlength="54" /> </td>
        </tr>
	<tr>
	    <td colspan="2" align="center"><input class="btn btn-warning" type="submit" value="Get Details" /></td>
	</tr>
</table>
</form>

</div>
</div>
</div>
<?php 
	require_once("left.php"); 
	require_once("footer.php");
?>

</body>
</html>