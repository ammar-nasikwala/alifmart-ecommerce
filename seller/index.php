<?php session_start();
require("../lib/inc_library.php");
$msg="";

if(isset($_SESSION["site_msg"])){
$msg = $_SESSION["site_msg"];
}

if(isset($_SESSION["sup_id"])){
	if($_SESSION["sup_id"] <> ""){
		if(isset($_SESSION["seller_vfd"])){
			echo "<meta http-equiv='refresh' content='0;url=mainmenu.php'>";
			exit();
		}else{
			echo "<meta http-equiv='refresh' content='0;url=../seller_update.php'>";
			exit();
		}
	}
}

if(isset($_POST["sup_email"])){
	
	if($_POST["sup_seller_token"] == ""){
		$sup_email = addslashes($_POST["sup_email"]) ;
		$sup_pwd = addslashes($_POST["sup_pwd"]);
		
		//$sup_id = "";
		mysqli_select_db( $con, $db_name);
		

		$rst = mysqli_query($con,"select sup_id, sup_delete_account, sup_seller_token from sup_mast where sup_email='$sup_email' and sup_pwd='$sup_pwd'");
		if ($rst == 0) {
			$msg  = 'Invalid query: ' . mysqli_error() . '\n';
		}
		if(mysqli_num_rows($rst)>0){
		
			$act_check = mysqli_query($con,"select sup_id from sup_mast where sup_email='$sup_email' and sup_active_status=1");
			if(mysqli_num_rows($act_check)<=0){
				$msg = "Your account is not activated. Please activate your account by clicking the activation link send to your email. If you haven't received any activation email, please click <a href='resend_activation_link.php'>here</a> to resend the activation link.  ";	
			}
			else{
				$row = mysqli_fetch_assoc($rst);
				$sub_delete_account=$row["sup_delete_account"];
				if(!$sub_delete_account){	
					if($row["sup_seller_token"] <> ""){
						$msg = "Your seller token has been generated. Please enter your token no. to login to your dashboard";
					}
					else{
						$_SESSION["user"]="1";
						$_SESSION["sup_id"] = $row["sup_id"];
						echo "<meta http-equiv='refresh' content='0;url=../seller_update.php'>";
						exit();
					}
				}else{
					$msg = "Account does not Exist. Please register as a seller or login with a different account.";
				}	
			}
		}else{
			$msg = "Authentication failed! Please enter valid details.";
		}
	}else{
	
		$sup_email = addslashes($_POST["sup_email"]) ;
		$sup_pwd = addslashes($_POST["sup_pwd"]);
		$sup_seller_token = addslashes($_POST["sup_seller_token"]);
		//$sup_id = "";
		mysqli_select_db( $con, $db_name);
		

		$rst = mysqli_query($con,"select sup_id, sup_lmd, sup_delete_account from sup_mast where sup_email='$sup_email' and sup_pwd='$sup_pwd' and sup_seller_token='$sup_seller_token'");
		if ($rst == 0) {
			$msg  = 'Invalid query: ' . mysqli_error() . '\n';
			//die($msg);
		}
		if(mysqli_num_rows($rst)>0){
			$act_check = mysqli_query($con,"select sup_id from sup_mast where sup_email='$sup_email' and sup_active_status=1");
			if(mysqli_num_rows($act_check)<=0){
				$msg = "Your account is not activated. Please activate your account by clicking the activation link send to your email. If you haven't received any activation email, please click <a href='resend_activation_link.php'>here</a> to resend the activation link.  ";	
			}
			else{
				$row = mysqli_fetch_assoc($rst);
				$sub_delete_account=$row["sup_delete_account"];
				if(!$sub_delete_account){
					$_SESSION["user"]="1";
					$_SESSION["sup_id"] = $row["sup_id"];
					$_SESSION["seller_vfd"] = "1";
					$_SESSION["sup_lmd"] = $row["sup_lmd"];
				echo "<meta http-equiv='refresh' content='0;url=mainmenu.php'>";
				exit();
				}else{
					$msg = "Account does not Exist. Please register as a seller or login with a different account.";
				}
			}
		}else{
			$msg = "Authentication failed! Please enter valid details.";
		}
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $company_title ?> | Seller Sign in</title>
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
<script language="javaScript">
function validate()
{	if(document.frmLogin.sup_email.value=="")	
	{
		alert("Please enter both Login Email and Password");
		document.frmLogin.user_name.focus();
		return false;
	}
	else if(document.frmLogin.sup_pwd.value=="")
	{
		alert("Please enter both Login Email and Password");
		document.frmLogin.user_pwd.focus();
		return false;
	}
	else{
		//document.frmLogin.submit();
		return true;
	}
}
</script>
<body onload="document.frmLogin.sup_email.focus()">


<div id="login-panel">      
	<form name="frmLogin" method="post" action="" onSubmit="return validate()">
		<table align="center" border=0 width=50%>
			<input type="hidden" name="action" value="validate">
			<tr>
				<th height=40 colspan=2><img src="../images/logo.gif" border="0" height="50">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;      Seller - Sign in</th>
			</tr>
			
			<tr><td height=25 align='center' colspan=2>
			<?php
			if(true || $msg <> "") { ?>
				<div class="alert alert-info">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					This site is temporarily unavailable.
				</div>
			<?php } ?>
			<br>
			</td></tr>  			
			<!--
			<tr>
				<td bgcolor="#F5F5F5" align="right" width=40%>Login Email*</td>
				<td bgcolor="#F5F5F5"><input type="textbox" class="narrow form-control" Placeholder="Enter Email" name="sup_email" size="20" maxlength="100"></td>
			</tr>

			<tr>
				<td bgcolor="#F5F5F5" align="right">Password*</td>
				<td bgcolor="#F5F5F5"><input type="password" class="narrow form-control" Placeholder="Enter Password" name="sup_pwd" size="20" maxlength="100"></td>
			</tr>
			</tr>
			
			<tr><td style="text-align: center" align="center" colspan=2><h4 style="color:#eb9316">Don't have a seller token yet? Press Login!<h4></td></tr>
			
			<tr>
				<tr>
				<td bgcolor="#F5F5F5" align="right">Seller Token*</td>
				<td bgcolor="#F5F5F5"><input type="password" class="narrow form-control" Placeholder="Enter Seller Token" name="sup_seller_token" size="10" maxlength="100"></td>
			</tr>
			
			<tr>
				<td align="right"><input type="submit" value="Login" class="btn btn-warning"></td>
				<td align="left"><a href="../seller.php" title="Not a member? Register" class="btn btn-warning">Not a member? Register</a></td>
			</tr>
			
			<tr>
				<td></td>
				<td bgcolor="#F5F5F5" align="left"><a href="forgot_password.php">Forgot Password?</a> | <a href="forgot_token.php">Forgot Token?</a></td>
			</tr>
			<tr><td>* Indicates compulsory field</td></tr>-->
		</table>
	</form>

</div>

</body>
</html>
<?php require("../lib/inc_close_connection.php"); ?>