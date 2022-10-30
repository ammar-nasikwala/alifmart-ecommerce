<?php

//include '../lib/inc_connection.php';

include '../lib/inc_library.php';
$msg="";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $company_title ?> | Configuration</title>

<link type="text/css" rel="stylesheet" href="css/main.css" />
<link type="text/css" rel="stylesheet" href="css/collapse_menu.css" />
<link type="text/css" rel="stylesheet" href="css/tooltip.css" />
<link type="text/css" rel="stylesheet" href="css/form-hints.css" />

<script type="text/javascript" src="js/jquery-1.2.6.pack.js"></script>
<script type="text/javascript" src="js/ddaccordion.js"></script>
<script type="text/javascript" src="js/collapse_menu.js"></script>
<script type="text/javascript" src="js/logohover.js"></script>
<script type="text/javascript" src="js/tooltip.js"></script>
<script type="text/javascript" src="js/form-hints.js"></script>

<SCRIPT language=Javascript>
      <!--
      function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
      }
      //-->
   </SCRIPT>


<script language="javascript">

function chkBlank()
{	
	var pattern=/[a-z]/
	strRemail=/^[\x09\]+[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@*.[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*$/ ;
   	strEvalue=document.frmConfig.txtEmail.value
   	strIndex=strEvalue.indexOf("@")
	
	if(document.frmConfig.txtName.value == "")
	{
		alert("The form is incomplete. Kindly enter in the login.");
		document.frmConfig.txtName.focus();
	}
	else if (document.frmConfig.txtName.value.length>10)
	{
		alert("You have exceeded the max limit for this field.");
		document.frmConfig.txtName.focus();
	}
	else if (document.frmConfig.txtPassword.value.length>54)
	{
		alert("You have exceeded the max limit for this field.");
		document.frmConfig.txtPassword.focus();
	}
	else if (document.frmConfig.txtCnfPassword.value.length>54)
	{
		alert("You have exceeded the max limit for this field.");
		document.frmConfig.txtCnfPassword.focus();
	}
	else if (document.frmConfig.txtEmail.value.length>50)
	{
		alert("You have exceeded the max limit for this field.");
		document.frmConfig.txtEmail.focus();
	}
	
	else if (document.frmConfig.txtSmpt.value.length>50)
	{
		alert("You have exceeded the max limit for this field.");
		document.frmConfig.txtSmpt.focus();
	}
	else if(document.frmConfig.txtName.value.charAt(0)==' ')	
	{	
		alert("You have not entered this field properly.\nPlease remove the leading spaces.");
		document.frmConfig.txtName.focus();
	}
	else if(document.frmConfig.txtPassword.value == "")
	{
		alert("The form is incomplete. Kindly enter in the password.");
		document.frmConfig.txtPassword.focus();
	}
	else if(document.frmConfig.txtPassword.value.charAt(0)==' ')	
	{	
		alert("You have not entered this field properly.\nPlease remove the leading spaces");
		document.frmConfig.txtPassword.focus();
	}
	else if(document.frmConfig.txtCnfPassword.value == "")
	{
		alert("The form is incomplete. Kindly enter in the confirm password.");
		document.frmConfig.txtConfPassword.focus();
	}
	else if(document.frmConfig.txtCnfPassword.value != document.frmConfig.txtPassword.value )
	{
		alert("Password and Confirm Password should match.");
		document.frmConfig.txtConfPassword.select();
	}
	else if(document.frmConfig.txtEmail.value == "")
	{
		alert("The form is incomplete. Kindly enter in the administrator's email.");
		document.frmConfig.txtEmail.focus();
	}
	else if((!strRemail.test(strEvalue)) || strIndex==-1)
	{
		alert ("Please enter email in proper format\n for e.g. yourname@xyz.com");
		document.frmConfig.txtEmail.focus();
	}
	//else if(document.frmConfig.txtSmpt.value == "")
	//{
	//	alert("The form is incomplete. Kindly enter in the SMTP server.");
	//	document.frmConfig.txtSmpt.focus();
	//}
	
	//else if (document.frmConfig.txtVat.value<=0)
	//		{
	//			alert("Vat value should be greater than 0.");
	//			document.frmConfig.txtVat.focus();
	//			return false;
	//		}
				
	else
		document.frmConfig.submit();
}

</script>

</head>

<!-- #INCLUDE file="checksession.asp" -->
<!-- #include file="qry_gen.asp"-->

<?php
$show_form="";
$update_form="";

if(isset($_GET["action"])){
	
	if($_GET["action"]=="new"){
		$show_form="1";
	}
	
	if(isset($_POST["action"])=="update"){
		$update_form="1";
	}
}

if($update_form=="1"){

	$admin_name = $_POST["txtName"];
	$admin_pwd = $_POST["txtPassword"];
	$admin_email = $_POST["txtEmail"];
	$smtp_server = $_POST["txtSmpt"];

	$sql="Update configuration set admin_name='$admin_name',admin_pwd='$admin_pwd',admin_email='$admin_email',smtp_server='$smtp_server'";
	
	execute_qry($sql);
	
	$msg = "Configuration values updated successfully.";
}

?>

<body>
<div id="maincontainer" class="table-responsive">
<table class="tbl_dash_main" width="100%">
<tr>
<td colspan="2">
<?php require("inc_top-menu.php") ?>
<!--div id="panels"-->
</td></tr>
<tr><td align="left" width="210px">
<?php require("inc_left-menu.php") ?>
</td><td>
<div id="centerpanel">

<div id="contentarea">
<h1>Configuration</h1>

<?php func_display_msg($msg)?>

<?php

$admin_name = "";
$admin_pwd = "";
$admin_email = "";
$smtp_server = "";

if($show_form=="1"){

	if(get_rst("select * from configuration",$row,$rst)){	
		$admin_name = $row["admin_user"];
		$admin_pwd = $row["admin_pwd"];
		$admin_email = $row["Admin_email"];
		$smtp_server = $row["smtp_server"];
	}
	//mysqli_free_result($rst);
}
?>
<table width="100%" border="0" cellspacing="1" cellpadding="5">

<form name="frmConfig" xaction="configuration.asp" method="post">
<input type="hidden" name="action" value="update">
<tr>
	<td bgcolor="#F5F5F5">Username</td>
	<td bgcolor="#F5F5F5"><input type="text" name="txtName" value="<?php echo $admin_name ?>" maxlength="10" size="50" class="textfield" /> <span class="red">*</span></td>
</tr>
<tr>
	<td bgcolor="#F5F5F5">Password</td>
	<td bgcolor="#F5F5F5"><input type="password" name="txtPassword" value="<?php echo $admin_pwd ?>" maxlength="10" size="50" class="textfield" /> <span class="red">*</span></td>
</tr>

<tr>
	<td bgcolor="#F5F5F5">Confirm Password</td>
	<td bgcolor="#F5F5F5"><input type="password" name="txtCnfPassword" value="<?php echo $admin_pwd ?>" maxlength="10" size="50" class="textfield" /> <span class="red">*</span></td>
</tr>

<tr>
	<td bgcolor="#F5F5F5">Administrator's Email</td>
	<td bgcolor="#F5F5F5"><input type="text" name="txtEmail" value="<?php echo $admin_email ?>" maxlength="50" size="50" class="textfield" /> <span class="red">*</span></td>
</tr>

<tr>
	<td bgcolor="#F5F5F5">SMTP Server</td>
	<td bgcolor="#F5F5F5"><input type="text" name="txtSmpt" value="<?php echo $smtp_server ?>" maxlength="50" size=50 class="textfield"> <span class="red">*</span></td>
</tr>


<tr><th id="centered" colspan="2"><input class="btn btn-warning" type="button" value="Update" onclick="javascript:chkBlank()"></th></tr>
</table>

</form>

</div>

</div>
</td>
</tr>
<tr><td colspan="2">
<?php require("inc_footer.php") ?>
</td></tr>
</table>
</body>
</html>


<?php require("../lib/inc_close_connection.php") ?>