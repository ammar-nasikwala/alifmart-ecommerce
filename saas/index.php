<?php //Briefcase Login

require_once("inc_admin_init.php");
if(session_id() == '') {
	session_start();
}

$msg="";
$memb_id=$_SESSION["memb_id"];
if($_SESSION["memb_id"] <> ""){			
	//...if user login with buyer account and click on briefcase link then this condition will check
	get_rst("select memb_email from member_mast where memb_id=$memb_id",$row_email);
	$membemail=$row_email["memb_email"];
	if(get_rst("select memb_id, memb_fname from member_mast where memb_id=$memb_id and subscribe_saas=1",$row_chk)){		
	//if user is subscribe
		$_SESSION["saas_user"]="1";
		$_SESSION["user_name"] = $row_chk["memb_fname"];
		get_rst("select company_id, po_company_id from po_company_mast where owner_id=$memb_id",$row_comp);
		$_SESSION["user_id"]=$memb_id;
		$_SESSION["po_company_id"]=$row_comp["po_company_id"];
		$_SESSION["company_id"] = $row_comp["company_id"];
		js_redirect("mainmenu.php");
		die("");
	}else if(!get_rst("select memb_id from member_mast where ind_buyer=1 and memb_id=$memb_id")){				
	//if user is not industial buyer 
		js_alert("To use this feature you should be a Industrial Buyer.");
		js_redirect("../index.php");
		die("");
	}else if(get_rst("select memb_id from member_mast where memb_id=$memb_id and subscribe_saas=0",$row_chk)){	
	//if user is requested for subscribe but request is not approved
		js_alert("Your request for subscription is in process.");
		js_redirect("../index.php");
		die("");
	}else if(get_rst("select memb_id from member_mast where memb_id=$memb_id and subscribe_saas=2",$row_chk)){	
	//if user is requested for subscribe but request is not approved
		js_alert("Your request for subscription to Company-Name Briefcase is on hold.");
		js_redirect("../index.php");
		die("");
	}
}
if(isset($_POST["user_email"])){		
//login with id,password and company id

	$user_email = addslashes($_POST["user_email"]);
	$pwd = addslashes($_POST["pwd"]);
	$company_id= addslashes($_POST["company_id"]);
	
	if(get_rst("select memb_id from member_mast where subscribe_saas=1 and memb_email='$user_email' and memb_pwd='$pwd' and memb_id=(select owner_id from po_company_mast where company_id='$company_id')",$row,$rst)){
	// Super user login condition
		$_SESSION["saas_user"]="1";
		$membid=$row["memb_id"];
		get_rst("select company_id, po_company_id from po_company_mast where owner_id=$membid",$row_comp);
		$_SESSION["user_id"]=$membid;
		$_SESSION["po_company_id"]=$row_comp["po_company_id"];
		$_SESSION["company_id"]=$row_comp["company_id"];
		js_redirect("mainmenu.php");
		die("");
	}else{
		
		if(get_rst("select id,role_id,po_company_id, user_name from po_memb_mast where email='$user_email' and pwd='$pwd' and po_company_id=(select po_company_id from po_company_mast where company_id='$company_id')",$row,$rst)){
		//sub user login conditions
			$_SESSION["saas_user"]=$row["role_id"];
			$_SESSION["user_id"]=$row["id"];
			$_SESSION["po_company_id"]=$row["po_company_id"];
			$_SESSION["company_id"]=$company_id;
			$_SESSION["user_name"]=$row["user_name"];
			js_redirect("mainmenu.php");
			die("");
		}else{
			$msg = "Invalid User name or Password or Company ID. Please try again.";
		}
	}

}

if(isset($_POST["accept"])){
//Subscription request
	execute_qry("update member_mast set subscribe_saas=0 where memb_id=$memb_id");
	get_rst("select memb_fname,memb_email from member_mast where memb_id=0$memb_id",$row_d);
	$name = $row_d["memb_fname"];
	$mail_body= " $name,<br> Your request for subscription to Company-Name Briefcase is on hold. Our Support team will contact you to appraise you. ";
	$mail_body.="<br> <br> Regards,<br> <br>Company-Name Briefcase <br> Your Procurement Management System";
   
  if(send_mail($row_d["memb_email"],"Company-Name Briefcase",$mail_body)){
	js_alert("Your request for subscription has been received successfully."); 
  }else{
    js_alert("Due to technical problems we are unable to send mail to user.");     
  }
	js_redirect("../index.php");
}

if(isset($_POST["membid"])){		
//login from list of user account display when user login with buyer account and click on breifcase link
	$mid = addslashes($_POST["membid"]);
	$roleid = addslashes($_POST["roleid"]);
	$cid= addslashes($_POST["po_company_id"]);
	if(get_rst("select id from po_memb_mast where id=$mid and pwd is not NULL")){
		$_SESSION["saas_user"]=$roleid;
		$_SESSION["user_id"]=$mid;
		$_SESSION["po_company_id"]=$cid;
		js_redirect("mainmenu.php");
		die("");
	}else{
		js_alert("Your account is not activated yet. Please activate your account.");
	}
}
?>

<html>

<head>
<title><?php echo $company_title;?> - Company-Name Briefcase</title>
<link rel="stylesheet" href="main.css" type="text/css">

<script src="../lib/lib.js"></script>
<script src="../lib/frmCheck.js"></script>

</head>

<script language="javaScript">
function validate(){
	if(chkForm(document.frmLogin)==false)
		return false;
	else
		document.frmLogin.submit();	
}

function loginjava(id,roleid,cid){
	
	document.getElementById("membid").value=id;
	document.getElementById("roleid").value=roleid;
	document.getElementById("po_company_id").value=cid;
	
	document.frmLogin.submit();
	}
</script>
<body>
<div id="login-panel"> 
	<form name="frmLogin" method="post" xaction="login.asp" onSubmit="return validate()">
	<?php if($_SESSION["memb_id"] == ""){ ?>
		<table align="center" border=0 width=40%>
			<input type="hidden" name="action" value="validate">
			<tr><th height=40 colspan=2><img src="../images/logo.gif" border="0" height="50">� Briefcase - Sign in</th></tr>
			<tr><td height=45 align='center' colspan=2>�
			<?php if($msg <> "") { ?>
			<div class="alert alert-info">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<?=$msg?>
			</div>
			<?php } ?>	
			<br>
			</td></tr>      
			<tr>
				<td align="right" width=30% style="padding-right:15px">Login ID</td>
				<td><input type="text" class="narrow form-control" name="user_email" title="Login ID" id="100" placeholder="Login ID" size="20" maxlength="100"></td>
			</tr>
			<tr>
				<td align="right" style="padding-right:15px">Password</td>
				<td><input type="password" class="narrow form-control" name="pwd" title="Password" id="100" placeholder="Password" size="20" maxlength="50"></td>
			</tr>
			<tr>
				<td align="right" style="padding-right:15px">Company ID</td>
				<td><input type="text" class="narrow form-control" name="company_id" id="100" title="Company ID"  placeholder="Company ID" size="20" maxlength="100"></td>
			</tr>
			<tr><td height="50" align="center" colspan=2><input class="btn btn-warning" name="login" type="submit" value="Login"></td></tr>

		</table>
	<?php }else if(get_rst("select pm.id,pm.role_id,pm.po_company_id,pc.company_id from po_memb_mast pm join po_company_mast pc on pm.po_company_id=pc.po_company_id where email='$membemail'",$row_user,$rst)){?>
		<table align="center" border=0 width=40%>
			<input type="hidden" name="action" value="validate">
			<tr><th height=40 colspan=2><img src="../images/logo.gif" border="0" height="50">� Briefcase -Sign in</th></tr>
			<tr><td height="50" align="center" colspan=2>Select From Which Account Want to Login.</td></tr>
			<input type="hidden" name="membid" id="membid" >
			<input type="hidden" name="roleid" id="roleid">
			<input type="hidden" name="po_company_id" id="po_company_id">
		<?php do{?>
			<tr onclick="javascript: loginjava('<?=$row_user["id"]?>','<?=$row_user["role_id"]?>','<?=$row_user["po_company_id"]?>');">
				<td height="50" align="center" colspan=2 style="padding-top:15px; padding-bottom:15px; background-color: #d4e5f7;"><span style="padding-right:25px;"><?=$membemail?></span><span> <?=$row_user["company_id"]?></span></td>
			</tr>
		<?php }while($row_user = mysqli_fetch_assoc($rst));?>
		</table>
			
	<?php }else{?>
		<table align="center" border=0 width=60%>
			<input type="hidden" name="action" value="validate">
			<tr><th height=40 colspan=2><img src="../images/logo.gif" border="0" height="50"> Briefcase - Subscription</th></tr>
		  
			<tr>
				<td align="right" colspan=2><iframe src="po_document/briefcase_primer.pdf" width="100%" height="400px"></iframe></td>
			</tr>
			
			<tr><td height="50" align="center" colspan=2><input class="btn btn-warning" type="submit" name="accept" value="Subscribe"></td></tr>
		</table>
	<?php }?>
	</form>
</div>
</body>
</html>

<?php require("../lib/inc_close_connection.php"); ?>