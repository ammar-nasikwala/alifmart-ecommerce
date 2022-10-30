<?php
require_once("../lib/inc_library.php");
header("Content-Type: text/html; charset=ISO-8859-1");

$msg='';

if(!empty($_GET['code']) && isset($_GET['code']))
{
	$code=mysqli_real_escape_string($con,$_GET['code']);
	$c=mysqli_query($con,"SELECT id,sms_code,email FROM po_memb_mast WHERE act_id='$code'");
	
	if(mysqli_num_rows($c) == 1)
	{
		$row = mysqli_fetch_assoc($c);
		$id=$row["id"];
		$email=$row["email"];
		$sms_code=$row["sms_code"];
		
		if(isset($_POST["memb_pwd"])){
			$memb_pwd = $_POST["memb_pwd"];
			$memb_cpwd = $_POST["memb_cpwd"];
			$otp = $_POST["sms_code"];
			$name = $_POST["memb_name"];
			
			$rule="";
			$rule = $rule."memb_pwd|c|Password^";
			$rule = $rule."memb_cpwd|c|Confirm Password^";
			$msg = validate($rule);
			if($memb_pwd <>" "&& $memb_cpwd <>""){	
			
			if($memb_pwd <> $memb_cpwd){
				$msg ="Password and Confirm Password does not match.";
			}
			elseif (strlen($memb_pwd) < '8') {
				$msg = "Your password must contain at least 8 characters";
			}
			elseif (strlen($memb_pwd) > '54') {
				$msg = "Your password can contain maximum 54 characters";
			}
			elseif(!preg_match("#[0-9]+#",$memb_pwd)) {
				$msg = "Your password must contain at Least 1 number";
			}
			elseif(!preg_match("#[A-Z]+#",$memb_pwd)) {
				$msg = "Your password must contain at least 1 capital letter";
			}
			elseif(!preg_match("#[a-z]+#",$memb_pwd)) {
				$msg = "Your password must contain At least 1 lowercase letter";
			}
			}
			if($msg == ""){			//if password is ok then updating details
				if(get_rst("select id from po_memb_mast where sms_code=$otp and act_id='$code'")){		//checking sms code is correct or not
					$qry = "update po_memb_mast set pwd='$memb_pwd', user_name='$name' where id='$id'";
					$result=mysqli_query($con, $qry);
					if($result){
							$msg = "Your password has been updated successfully.";
							$url=	"http://".$_SERVER["SERVER_NAME"]."/saas/index.php";
							$msg.="<br> Click <a class=\"alif-link\" href='$url' style='color: #eb9316 !important'>here</a> to login to your account.";
					}else{
						$msg = "Our system has encountered a problem, please try again after some time.";
					}
				}else{
					$msg= "Incorrect OTP.";
				}	
			}
		}
		
	}
else
	{
		$msg ="Incorrect reset link.";
	}
}


?>

<html>

<head>
<title>Company-Name - Company-Name Briefcase Password Creation</title>
<link rel="stylesheet" href="main.css" type="text/css">

<script src="../lib/lib.js"></script>
<script src="../lib/frmCheck.js"></script>

</head>

<script language="javaScript">
function validate(){
	if(chkForm(document.frmpwd)==false)
		return false;
	else
		document.frm.submit();	
}

function validate_key(evt) {
		var theEvent = evt || window.event;
		var key = theEvent.keyCode || theEvent.which;
		key = String.fromCharCode( key );
		var regex = /[0-9]|\./;
			if( !regex.test(key) ) {
				theEvent.returnValue = false;
					if(theEvent.preventDefault) theEvent.preventDefault();
				}
	} 
</script>
<body onload="document.frmpwd.memb_pwd.focus()">
<div id="login-panel"> 
	<form name="frmpwd" method="post" xaction="login.asp" onSubmit="return validate()">
		<table align="center" border=0 width="40%">
			<input type="hidden" name="action" value="validate">
			<tr><th height=40 colspan=2><img src="../images/logo.gif" border="0" height="50" style="margin-right:20px">Password Creation</th></tr>
			 
			<?php if($msg <> "") { ?>
			<tr><td height=45 align='center' colspan=2>
			<div class="alert alert-info">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<?=$msg?>
			</div>
			</td></tr> 
			<?php } ?>	
		
			<tr style="padding-top:15px">
				<td align="right" width=30% style="padding-right:15px">Login ID</td>
				<td><?=$email?></td>
			</tr>
			
			<tr style="padding-top:15px">
				<td align="right" width=30% style="padding-right:15px">User Name</td>
				<td><input type="text" class="narrow form-control" name="memb_name" id="100" placeholder="Enter your name" size="20" title="Name" tabindex="1" maxlength="54"></td>
			</tr>
			
			<tr style="padding-top:15px">
				<td align="right" width=30% style="padding-right:15px">Password</td>
				<td><input type="password" class="narrow form-control" name="memb_pwd" id="100" placeholder="Password" size="20" data-toggle="tooltip" data-placement="right" title="Your password must contain a minimum of 8 characters and a maximum of 54 characters. It must contain at-least 1 number, 1 capital letter and 1 lower-case letter."  maxlength="54"></td>
			</tr>
			<tr>
				<td align="right" style="padding-right:15px">Confirm Password</td>
				<td><input type="password" class="narrow form-control" name="memb_cpwd" title="Confirm Password" id="100" placeholder="Confirm Password" size="20" maxlength="54"></td>
			</tr>
			
			<tr>
				<td align="right" style="padding-right:15px">OTP</td>
				<td><input type="text" class="narrow form-control" name="sms_code" title="OTP" id="100" onkeypress='validate_key(event)' placeholder="OTP" size="20" maxlength="4"></td>
			</tr>
			
			<tr><td height="50" align="center" colspan=2><input class="btn btn-warning" type="submit" value="Submit"></td></tr>

		</table>
	</form>
</div>
</body>
</html>

<?php require("../lib/inc_close_connection.php"); ?>