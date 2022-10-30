<?php
require("../lib/inc_library.php");
$msg="";
$_SESSION["user"]="1"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $company_title ?> | Register Seller</title>
<link type="text/css" rel="stylesheet" href="css/main.css" />
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
	if(isset($_POST["act"])){		
		$sup_company = $_POST["sup_company"];
		//$sup_contact_name = $_POST["sup_contact_name"];
		$sup_email = $_POST["sup_email"];
		$sup_contact_no = $_POST["sup_contact_no"];
		$sup_pwd = $_POST["sup_pwd"];
		
		$sup_cpwd = $_POST["sup_cpwd"];
		
		$sup_activation=md5($sup_email.time()); // encrypted email+timestamp
		$rule = "";
		$rule = $rule."sup_pwd|c|Password^";
		$rule = $rule."sup_pwd|p|Password^";
		$msg = validate($rule);
		
		//Random string generator, for seller code
		
		$characters = �0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ�;
		$sup_seller_token.= "";
		for ($i = 0; $i < 6; $i++) {
			$sup_seller_token .= $characters[rand(0, strlen($characters) - 1)];
		}	
		
		if($msg == "")	
		{
			$fld_arr = array();
			$fld_arr["sup_company"] = $sup_company;
			
			$fld_arr["sup_seller_token"] = $sup_seller_token;
			$fld_arr["sup_email"] = $sup_email;
			$fld_arr["sup_contact_no"] = $sup_contact_no;
			$fld_arr["sup_pwd"] = $sup_pwd;
			$fld_arr["sup_active_status"] = "0";
			$fld_arr["sup_activation"] = $sup_activation;
			
			
			$count=mysqli_query($con,"SELECT sup_id FROM sup_mast WHERE sup_email='$sup_email'");
			if($sup_pwd != $sup_cpwd)		//Password check
			{
				$msg = 'Passwords mismatch!';	
			}
			
			else if(mysqli_num_rows($count) > 0)	// email check
			{
				$msg = 'The email is already registered, please try another email.'; 
			}
			
			else
			{
				$qry = func_insert_qry("sup_mast",$fld_arr);
				$result=mysqli_query($con, $qry);
		
				if($result){
					// sending verification email
					$to=$sup_email;
					$subject="Email verification";
					//$fld_arry = array();
					//$fld_arry["memb_fname"] = $sup_contact_name;
					//		 $fld_arry["memb_email"] = $sup_email;
					//		 $fld_arry["memb_pwd"] = $sup_pwd;
					//$fld_arry["act_link"] = $base_url.$sup_activation;
					
					//$body = push_body("confirmation_email.txt",$fld_arry);
					
					
					$header="from: Company-Name<noreply@Company-Name.com>";
					$body="Hi ".$sup_contact_name.",\r\n";
					$body.="Please verify your email and get started using your Website account by clicking on the activation link below.\r\n";
					$body.=$base_url.$sup_activation."\r\n\n";
					$body.="Username : $sup_email\r\n";
					$body.="Password : $sup_pwd\r\n";
					$body.="Seller Token : $sup_seller_token";		
					//$body.="<a href='.$base_url.$sup_activation.'>Click to Activate</a>";
					$body.="\n\n\nRegards,\nFor Company-Name.com";
					$sentmail = mail($to,$subject,$body,$header);
					if($sentmail){
						$msg = "Thank You for registering on Company-Name.com. Activate your account by clicking on the activation link sent to '$sup_email'";
					}
					else {
						$msg = "Cannot send activation link to your e-mail address";
					}
				}
				else{
					$msg = "Registration failed! Cannot add record to the database";
				}		
			}
		}
	}
?>
<body onload="document.frm.sup_company.focus();">
<?php require("inc_top-menu.php") ?>
<div id="centerpanel">
<div id="contentarea">
<h1>Seller Registration</h1>
<center>
<?php func_display_msg($msg)?>
</center>
<table width="100%" border="0" cellspacing="1" cellpadding="5">
	<form name="frm" method="post" >
	<input type="hidden" name="act" value="1" >
	<tr>
		<td bgcolor="#F5F5F5">Company Name</td>
		<td bgcolor="#F5F5F5"><input type="textbox" title="Company Name" name="sup_company" value="" maxlength="100" id="100" size="50" class="textfield" /> <span class="red">*</span></td>
	</tr>
	<tr>
		<td bgcolor="#F5F5F5">Email</td>
		<td bgcolor="#F5F5F5"><input type="textbox" title="Email" name="sup_email" value="" maxlength="50" id="120" size="50" class="textfield" /> <span class="red">*</span></td>
	</tr>
	<tr>
		<td bgcolor="#F5F5F5">Contact Number</td>
		<td bgcolor="#F5F5F5"><input type="textbox" title="Contact Number" name="sup_contact_no" value="" maxlength="10" id="100" size="50" class="textfield" /> <span class="red">*</span></td>
	</tr>
	<tr>
		<td bgcolor="#F5F5F5">Password</td>
		<td bgcolor="#F5F5F5"><input type="password" title="Password" name="sup_pwd" value="" maxlength="10" id="150" size="20" class="textfield" /> <span class="red">*</span></td>
	</tr>
	
	<tr>
		<td bgcolor="#F5F5F5">Confirm Password</td>
		<td bgcolor="#F5F5F5"><input type="password" title="Confirm Password" name="sup_cpwd" value="" maxlength="10" id="160" size="20" class="textfield" /> <span class="red">*</span></td>
	</tr>
	
	<tr><th align="center" colspan="2"><input type="button" class="btnclass" onClick="javascript:return Submit_form();" value="Register" id=button1 name=button1></th></tr>
	</form>	
</table>
</div>
</div>
<?php require("inc_footer.php") ?>
</body>
</html>
<?php require("../lib/inc_close_connection.php") ?>