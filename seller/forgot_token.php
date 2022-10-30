<?php session_start();
include '../lib/inc_library.php';
$msg="";
//$_SESSION["user"]="1"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $company_title ?> | Forgot Token</title>
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
	if(isset($_POST["sup_email"]) && isset($_POST["sup_pwd"])){		
		$sup_email = $_POST["sup_email"];
		$sup_pwd = $_POST["sup_pwd"];
		$sup_contact_name = "";
		$sup_seller_name = "";
		$count=mysqli_query($con,"SELECT sup_seller_token, sup_contact_name FROM sup_mast WHERE sup_email='$sup_email' and sup_pwd='$sup_pwd' ");
	
		if(mysqli_num_rows($count) <= 0)	// email check
		{
			$msg = 'The email address or password is incorrect.'; 
		}
		else
		{
			if(get_rst("select sup_seller_token from sup_mast where sup_email='$sup_email' and sup_seller_token is not null")){
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
				$_SESSION["site_msg"]="Seller token has been sent to your email.";
				echo "<meta http-equiv='refresh' content='0;url=../seller/login.php'>";
				exit();
			}else{
				$msg="Due to technical problems we are unable to process your request, please try again later.";					
			}
			}else{
				$msg = "Your seller token is not yet generated. Please contact our sales team at sales@Company-Name.com to help you with the registration process.";
			}
		
		}
	}
?>
<body onload="document.frmLogin.admin_name.focus()">
<form name="frmLogin" method="post" action="" onSubmit="return validate()">
	<table align="center" border=0 width=50% cellspacing=5>
		<input type="hidden" name="action" value="validate">
		<tr><th style="padding-top:5px" height=20 colspan=2><p align="center">Seller - Forgot Token</p></th></tr>
		
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
		<tr>
			<td bgcolor="#F5F5F5" align="right" width=40% style="padding-right:10px;">Password</td>
			<td bgcolor="#F5F5F5"><input type="password" name="sup_pwd" id="120" size="20" maxlength="100"></td>
		</tr>
		<tr><td height=25 colspan=2>&nbsp;</td></tr>
		
		<tr><td align="center" colspan=2><input class="btn btn-warning" type="submit" value="Submit"></td></tr>		
	</table>
</form>
</body>
</html>