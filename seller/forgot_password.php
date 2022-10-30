<?php 
session_start();
include '../lib/inc_library.php';
$msg="";
//$_SESSION["user"]="1"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $company_title ?> | Forgot Password</title>
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
		$sup_pwd = "";
		$sup_contact_name = "";
		$sup_seller_name = "";
		$count=mysqli_query($con,"SELECT sup_activation, sup_contact_name FROM sup_mast WHERE sup_email='$sup_email'");
	
		if(mysqli_num_rows($count) <= 0)	// email check
		{
			$msg = 'This email has not been registered.'; 
		}
		else
		{
			$row = mysqli_fetch_assoc($count);
			$sup_activation = $row["sup_activation"];
			$sup_contact_name = $row["sup_contact_name"];;
			$to=$sup_email;
			$subject="Password Reset";
			$header="Company-Name<noreply@Company-Name.com>";
			
			$body="Hi ".$sup_contact_name.",<br>";
			$body.="<p>We have received your forgot password request. Please click the link below to reset password.</p><br>";
			$body.="<a href='".$fgt_base_url."$sup_activation'>Reset Password</a></p><br>";
			$body.="<br>Regards,<br>Team Company-Name.com";
							
			if(send_mail($to,$subject,$body)){
				$msg="Password reset link has been sent to your email.";	
			}else{
				$msg="Due to technical problems we are unable to process your request, please try again later.";					
			}
		
		}
		if($sup_email=="")
		{
			$msg="Please Enter Your Email";
		}
	}
?>
<body onload="document.frmLogin.admin_name.focus()">

<form name="frmLogin" method="post" action="" onSubmit="return validate()">
	<table align="center" border=0 width=50% cellspacing=5>
		<input type="hidden" name="action" value="validate">
		<tr><th style="padding-top:5px" height=20 colspan=2><p align="center">Seller - Forgot Password</p></th></tr>
		
		<tr><td height=25 align='center' colspan=2>&nbsp;
		    <?php
			if($msg <> "") { ?>
			<div class="alert alert-info">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    			<?=$msg?>
			</div>
    		<?php } ?>
		<br>
		</td></tr>  			
		
		<tr>
			<td bgcolor="#F5F5F5" align="right" width=40% style="padding-right:10px;">Login Email</td>
			<td bgcolor="#F5F5F5"><input type="textbox" name="sup_email" id="120" size="20" maxlength="100"></td>
		</tr>
		<tr><td height=25 colspan=2>&nbsp;</td></tr>
		
		<tr><td align="center" colspan=2><input class="btn btn-warning" type="submit" value="Submit"></td></tr>		
	</table>
</form>
</body>
</html>