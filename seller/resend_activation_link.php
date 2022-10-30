<?php session_start();
require("../lib/inc_library.php");
$msg="";
//$_SESSION["user"]="1"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $company_title ?> | Resend Activation Link</title>
<link type="text/css" rel="stylesheet" href="main.css" />
<link type="text/css" rel="stylesheet" href="css/collapse_menu.css" />
<link type="text/css" rel="stylesheet" href="css/tooltip.css" />
<script type="text/javascript" src="js/jquery-1.2.6.pack.js"></script>
<script type="text/javascript" src="js/ddaccordion.js"></script>
<script type="text/javascript" src="js/collapse_menu.js"></script>
<script type="text/javascript" src="js/logohover.js"></script>
<script type="text/javascript" src="js/tooltip.js"></script>
<script type="text/javascript" src="js/form-hints.js"></script>
<script language="javascript" src="frmCheck.js"></script>
	<script language="javascript">
		function DoCal(objDOI,DOIVal){	
			var sRtn;
			var err=0
			sRtn = showModalDialog("calendar.htm",DOIVal,"center=yes;dialogWidth=200pt;dialogHeight=200pt;status=no");
			if(String(sRtn)!="undefined"){
					objDOI.value=sRtn;	
			}
		}
		function Submit_form()
		{	
			if(chkForm(document.frm)==false)
				return false;
			else
				document.frm.submit();
		}
	</script>
<script type="text/javascript" language="javascript"> 
<!--
function limitChars(textid, limit, infodiv)
{
	var text = $('#'+textid).val();	
	var textlength = text.length;
	if(textlength > limit)
	{
		$('#' + infodiv).html('You cannot write more then '+limit+' characters!');
		$('#'+textid).val(text.substr(0,limit));
		return false;
	}
	else
	{
		$('#' + infodiv).html('You have <strong>'+ (limit - textlength) +'</strong> characters left');
		return true;
	}
}
 
$(function(){
 	$('#event_details').keyup(function(){
 		limitChars('event_details', 1000, 'charlimitinfo');
 	})
});
-->
</script>
</head>
<?php
$msg = "";
	if(isset($_POST["sup_email"])){		
		$sup_email = $_POST["sup_email"];
		$sup_activation = "";
		$sup_contact_name = "";
		$sup_pwd = "";
		$sup_seller_token = "";
		$count=mysqli_query($con,"SELECT sup_contact_name, sup_pwd, sup_seller_token, sup_activation FROM sup_mast WHERE sup_email='$sup_email'");
	
		if(mysqli_num_rows($count) <= 0)	// email check
	
		{
			$msg = 'This email has not been registered.'; 
	
		}
		else
		{
			$row = mysqli_fetch_assoc($count);
		$sup_pwd = $row["sup_pwd"];
			$sup_contact_name = $row["sup_contact_name"];
			$sup_seller_token = $row["sup_seller_token"];
			$sup_activation = $row["sup_activation"];
			// sending verification email
			$to=$sup_email;
			$subject="Email verification";
			$body="Hi ".$sup_contact_name.",<br>";
			$body.="<p>Please verify your email and get started using your Website account by clicking on the activation link.<br>";
			$body.="<a href='$base_url.$sup_activation.'>Click to Activate</a></p>";
			$body.="<br>Username : $sup_email";
			$body.="<br>Password : $sup_pwd";
			$body.="<br>Seller Token : $sup_seller_token";
			$body.="<br><br>Regards,<br>For Company-Name.com";
			
			$header="Company-Name<noreply@Company-Name.com>";
		
				//$sentmail = mail($to,$subject,$body,$header);
			try{
				$sentmail = true;
				$sentmail = send_mail2($to,$subject,$body,$header);
			}
			catch(Exception $e){
				$sentmail = false;
			}
			//	$sentmail = mail($to,$subject,$body,$header);
				if($sentmail){
					$_SESSION["site_msg"] = "The activation link has been sent to your email.";
					echo "<meta http-equiv='refresh' content='0;url=https://www.Company-Name.com/seller/login.php'>";
					exit();
				}
				else {
					$msg = "There was a problem sending login details to your e-mail address";
				}
			
		}
	}
?>
<body onload="document.frmLogin.admin_name.focus()">
<form name="frmLogin" method="post" action="" onSubmit="return validate()">
	<table align="center" border=0 width=50% cellspacing=5>
		<input type="hidden" name="action" value="validate">
		<tr><th height=20 colspan=2>Seller - Resend Activation Link</th></tr>
		
		<tr><td height=25 align='center' colspan=2>&nbsp;
		<center>
		<?php func_display_msg($msg)?>
		</center>
		<br>
		</td></tr>  			
		
		<tr>
			<td bgcolor="#F5F5F5" align="right" width=40%>Login Email</td>
			<td bgcolor="#F5F5F5"><input type="textbox" name="sup_email" id="120" size="20" maxlength="100"></td>
		</tr>
		<tr><td height=25 colspan=2>&nbsp;</td></tr>
		
		<tr><th align="center" colspan=2><input type="submit" value="Send Activation Link"></th></tr>
		
	</table>
</form>
</body>
</html>