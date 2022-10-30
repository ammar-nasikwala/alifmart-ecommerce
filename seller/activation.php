<?php
require_once '../lib/inc_library.php';
//require_once '../ajax.php';
$msg='';
$s_flag = "";
if(!empty($_GET['code']) && isset($_GET['code']))
{
	$hdn_act_submit = func_read_qs("hdn_act_submit");
	$code=mysqli_real_escape_string($con,$_GET['code']);
	$qry="SELECT sup_contact_name, sup_id, sms_verify_code, sup_contact_no, sup_active_status FROM sup_mast WHERE sup_activation='$code'";
	if(get_rst($qry, $rec))
	{	
		if($hdn_act_submit =="y"){
			//$count=mysqli_query($con,"SELECT sup_id FROM sup_mast WHERE sup_activation='$code' and sup_active_status=0");
			$sms_verify_code = func_read_qs("sms_verify_code");
			
			if($rec["sup_active_status"] == 0)
			{			
				if($sms_verify_code <> $rec["sms_verify_code"]){
					$msg = "Sorry! Not a valid sms verification code. ";
				}else{
					$result = mysqli_query($con,"UPDATE sup_mast SET sup_active_status=1, sms_verify_status=1 WHERE sup_activation='$code' AND sms_verify_code=$sms_verify_code");
					if($result){
						$msg="Your account has been successfully activated. ";
						$msg.="<br> Click <a href='../seller/index.php'><u>here</u></a> to login to your account."; 
						$s_flag = "1";
					}else{
						$msg = "Sorry! There is some problem updating your record, please try after some time. ";
					}
				}
			}
			else
			{
				$msg ="Your account is has already been activated.";
			}
		}
	}
	else
	{
		$msg ="Sorry! Not a valid activation link.";
	}
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $company_title ?>Company-Name | Account Activation</title>
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
<script type="text/javascript" src="../lib/ajax.js"></script> 
<script type="text/javascript">
function send_sms(){
	var obj_fname = document.getElementById("fname").value;
	var obj_tele = document.getElementById("tele_no").value;
	var obj_sms_code = document.getElementById("sms_code").value;
	if(obj_tele=="")
	{
		alert("Invalid activation link");
		return;
	}
	var output_msg = call_ajax("../ajax.php","process=send_sms&fname=" +obj_fname + "&sms_code=" + obj_sms_code + "&tele_no=" + obj_tele);
	alert(output_msg)
}

</script> 
 
 
</head>
<body>
<?php if($s_flag <> "") { ?> 
	<table align="center" border=0 width=50% cellspacing=5>
		<input type="hidden" name="action" value="validate">
		<tr><th height=20 colspan=2>Account Activation</th></tr>
		
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
			<td bgcolor="#F5F5F5"></td>
		</tr>
		<tr><td height=25 colspan=2>&nbsp;</td></tr>
		<tr><th height=20 align="center" colspan=2>  </th></tr>		
	</table>
<?php } else {?>
	<form name="frm" method="post" action="activation.php?code=<?php echo $code; ?>" xonsubmit="javascript: return validate();">
		<input type="hidden" name="hdn_act_submit" value="y">
	 
		<table width="50%" style="margin-top:10%"  border="0" align="center">
		<tr><td height=25 align='center' colspan=3>&nbsp;
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
		  <td>Enter SMS verification code</td>
		  <td><input name="sms_verify_code" class="form-control" style="width:150px; display:inline-block;" type="text" title="SMS verification code" id="110" tabindex="14" size="10" maxlength="10" value=""/></td>
		  <td><input id="fname" type="hidden" name="fname" value="<?php echo $rec["sup_contact_name"];?>"><input id="tele_no" type="hidden" name="tele_no" value="<?php echo $rec["sup_contact_no"]; ?>"><input id="sms_code" type="hidden" name="sms_code" value="<?php echo $rec["sms_verify_code"]; ?>"><a href="#" onclick="javascript: send_sms()" >Resend verification code</a></td>
		  </tr>
		<tr>
		<td align="center" colspan="3">
			<input type="submit" class="btn btn-warning" value=" Submit "  tabindex="15" border="0">
		</td>
	  </tr>
	  </table>
	</form>

<?php } ?>
</body>
</html>