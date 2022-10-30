<?php
include '../lib/inc_library.php';

$msg='';
if(!empty($_GET['code']) && isset($_GET['code']))
{
	$code=mysqli_real_escape_string($con,$_GET['code']);
	$c=mysqli_query($con,"SELECT sup_id FROM sup_mast WHERE sup_activation='$code'");
	if(mysqli_num_rows($c) == 1)
	{
		$row = mysqli_fetch_assoc($c);
		$sup_id=$row["sup_id"];
		
		if(isset($_POST["sup_pwd"])){
			$sup_pwd = $_POST["sup_pwd"];
			$sup_cpwd = $_POST["sup_cpwd"];
			
			$rule="";
			$rule = $rule."sup_pwd|c|Password^";
			$rule = $rule."sup_cpwd|c|Confirm Password^";
			$msg = validate($rule);
			if($sup_pwd <>" "&& $sup_cpwd <>""){
			if($sup_pwd <> $sup_cpwd){
				$msg = "Password and Confirm Password does not match.";
			}
			elseif (strlen($sup_pwd) < '8') {
				$msg = "Your password must contain at least 8 characters";
			}
			elseif (strlen($sup_pwd) > '54') {
				$msg = "Your password can contain maximum 54 characters";
			}
			elseif(!preg_match("#[0-9]+#",$sup_pwd)) {
				$msg = "Your password must contain at Least 1 number";
			}
			elseif(!preg_match("#[A-Z]+#",$sup_pwd)) {
				$msg = "Your password must contain at least 1 capital letter";
			}
			elseif(!preg_match("#[a-z]+#",$sup_pwd)) {
				$msg = "Your password must contain At least 1 lowercase letter";
			}
			}
			if($msg == ""){
				$qry = "update sup_mast set sup_pwd='$sup_pwd' where sup_id='$sup_id'";
				$result=mysqli_query($con, $qry);
				if($result){
						$msg = "Your password has been updated successfully.";
						$url=	"http://".$_SERVER["SERVER_NAME"]."/seller/index.php";
						$msg.="<br> Click <a class=\"alif-link\" href='$url'>here</a> to login to your account.";
				}else{
					$msg = "Our system has encountered a problem, please try again after some time.";
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $company_title ?> | Password Reset</title>
<link type="text/css" rel="stylesheet" href="main.css" />
<link type="text/css" rel="stylesheet" href="css/tooltip.css" />
<script type="text/javascript" src="js/ddaccordion.js"></script>
<script type="text/javascript" src="js/logohover.js"></script>
<script type="text/javascript" src="js/tooltip.js"></script>
<script type="text/javascript" src="js/form-hints.js"></script>
 
</head>
<body>
<form name="frmLogin" method="post" action="" >
	<table align="center" border=0 width=50% cellspacing=5>
		<input type="hidden" name="action" value="validate">
		<tr><th style="padding-top:5px" height=20 colspan=2><p align="center">Seller - Reset Password</p></th></tr>
		
		<tr><td height=25 align='center' colspan=2>&nbsp;
    		<?php
			if($msg <> "") { ?>
			<div class="alert alert-info">
    			<?=$msg?>
			</div>
    		<?php } ?>
		<br>
		</td></tr>  			
		
		<tr>
			<td bgcolor="#F5F5F5" align="right" width=40% style="padding-right:10px;">New Password</td>
			<td bgcolor="#F5F5F5"><input type="password" name="sup_pwd" id="sup_pwd" size="20" data-toggle="tooltip" data-placement="left" title="Password should contain atleast 8 character one number one capital letter, one lowercase letter." maxlength="54">
			<span id="msg_pwd"></span></td>

		</tr>
		<tr><td height=20 align='center' colspan=2>&nbsp;
		<tr>
			<td bgcolor="#F5F5F5" align="right" width=40% style="padding-right:10px;">Confirm Password</td>
			<td bgcolor="#F5F5F5"><input type="password" name="sup_cpwd" id="120" size="20" maxlength="54"></td>
		</tr>
		<tr><td height=25 colspan=2>&nbsp;</td></tr>
		
		<tr><td align="center" colspan=2><input class="btn btn-warning" type="submit" value="Submit"></td></tr>		
	</table>
</form>
</body>
</html>