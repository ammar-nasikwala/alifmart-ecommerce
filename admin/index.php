	
	<?php

require_once("inc_admin_init.php");

//if(!isset($_POST["admin_user"])){
	if(session_id() == '') {
		session_start();
	}
//}

$msg="";

if(isset($_POST["admin_user"])){

	$admin_user = addslashes($_POST["admin_user"]);
	$admin_pwd = addslashes($_POST["admin_pwd"]);

	if(get_rst("select * from configuration where admin_user='$admin_user' and admin_pwd='$admin_pwd'",$row,$rst)){
		$_SESSION["admin"]="1";
		//header("location: mainmenu.php");
		js_redirect("mainmenu.php");
		die("");
	}else{
		if(get_rst("select * from user_mast where user_email='$admin_user' and user_pwd='$admin_pwd'",$row,$rst)){
			$_SESSION["admin"]=$row["user_type"];
			$_SESSION["user_name"]=$row["user_name"];
			//header("location: mainmenu.php");
			js_redirect("mainmenu.php");
			die("");
		}else{
			$msg = "Invalid User name or password. Please try again.";
		}
	}

}


?>

<html>

<head>
<title><?php echo $company_title;?> - System Web Administrator Panel</title>
<link rel="stylesheet" href="main.css" type="text/css">

<script src="../lib/lib.js"></script>
<script src="../lib/frmCheck.js"></script>

</head>

<script language="javaScript">
function validate(){
	if(chkForm(document.frmLogin)==false)
		return false;
	else
		document.frm.submit();	
}
</script>
<body onload="document.frmLogin.admin_user.focus()">
<div id="login-panel"> 
	<form name="frmLogin" method="post" xaction="login.asp" onSubmit="return validate()">
		<table align="center" border=0 width=40%>
			<input type="hidden" name="action" value="validate">
			<tr><th height=40 colspan=2><img src="../images/logo.gif" border="0" height="50">  System Web Administrator - Sign in</th></tr>
			<tr><td height=45 align='center' colspan=2> 
			<?php if($msg <> "") { ?>
			<div class="alert alert-info">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<?=$msg?>
			</div>
			<?php } ?>	
			<br>
			</td></tr>      
			<tr>
				<td align="right" width=30% style="padding-right:15px">Username</td>
				<td><input type="text" class="narrow form-control" name="admin_user" title="User Name" id="100" placeholder="User Name" size="20" maxlength="100"></td>
			</tr>
			<tr>
				<td align="right" style="padding-right:15px">Password</td>
				<td><input type="password" class="narrow form-control" name="admin_pwd" title="Password" id="100" placeholder="Password" size="20" maxlength="50"></td>
			</tr>
			<tr><td height="50" align="center" colspan=2><input class="btn btn-warning" type="submit" value="Login"></td></tr>

		</table>
	</form>
</div>
</body>
</html>

<?php require("../lib/inc_close_connection.php"); ?>
	
	