<?php
require_once("inc_admin_header.php");


$id = func_read_qs("id");
$cid=$_SESSION["company_id"];
if($id<>""){
	$qry = "select * from po_memb_mast where id=".$id;
	if(get_rst($qry, $row)){
		$user_email = $row["email"];
		$role_id = $row["role_id"];
		$contact_no = $row["contact_no"];
	}
}

if(isset($_POST["submit"])){
	$msg = "1";
	$email = func_read_qs("user_email");
	
	if(get_rst("select id from po_memb_mast where email='".$email."' and po_company_id=$cid", $r) && $id==""){
		$msg="The user with this email has already been registered. Please use another email id.";
	}
	if($msg=="1"){
		$fld_arr = array();
		
		$fld_arr["email"] = func_read_qs("user_email");
		$useremail=func_read_qs("user_email");
		$fld_arr["role_id"] = func_read_qs("role_id");
		$fld_arr["po_company_id"] = $cid;
		$fld_arr["contact_no"] = func_read_qs("contact_no");
		$contact = func_read_qs("contact_no");
		
		if($id==""){
			$sms_verify_code = mt_rand(1000, 9999);
			$fld_arr["sms_code"] = $sms_verify_code;
			$fld_arr["act_id"] = get_act_id($fld_arr["email"]);
			$actlink= get_base_url()."/saas/pwdCreation.php?code=".$fld_arr["act_id"];
				
			get_rst("select member_count from po_company_mast where po_company_id=$cid",$row_mcount);
			$memb_count =  $row_mcount['member_count'] + 1;
			if($memb_count <=3){																		//check for max 3 users
				$qry = func_insert_qry("po_memb_mast",$fld_arr);
				execute_qry("update po_company_mast set member_count=$memb_count where po_company_id=$cid");
					
				if(!mysqli_query($con, $qry)){
					$incomp_msg="Something went wrong try again later.";
				}else{
					$msg="Record saved Successfully.";	//when insert query is successfully then sending mail and sms
					
					get_rst("select memb_email,memb_tel from member_mast where memb_id=".$_SESSION["user_id"],$r);
					get_rst("select company_id from po_company_mast where po_company_id=".$_SESSION["company_id"],$rowcid);
					
					$mail_body.="<br>Company-Name Briefcase account is successfully created for your use.  Kindly <a href='$actlink'>click here </a> to set a new password for your account.";
					$mail_body.="<br><br>Your login ID: $email ";
					$mail_body.="<br>Company ID: ".$rowcid['company_id'] ;
					
		
					$role=$_POST["role_id"];
					if($role==2){
						$role="Purchase Manager";
					}else{
						$role ="Finance Manager";
					}
		
					$mail_body.="<br>Role: $role";
					$mail_body.="<br><br>Admin Details: ".$r['memb_email'].",". $r['memb_tel'] ;
		
					$mail_body.="<br><br>Confirm your Mobile No. $contact on the page using the OTP send to your registered mobile no.";
					$mail_body.="<br> <br> Welcome to Company-Name Briefcase - your Procurement Management System.";
					$mail_body.="<br> <br> Regards,<br>Company-Name Briefcase";
			
					if(send_mail($email,"Company-Name Briefcase- Password Creation Link ",$mail_body)){
						js_alert("Record saved Successfully").
						js_redirect("manage_users.php");
					}else{
						js_alert("Due to technical problems we are unable to send mail to user.");					
					}
					
					$sms_msg = "Hi, Your mobile verification code is ".$sms_verify_code.". Kindly enter this code on set new password page to enable your account.";
					$output = send_sms($contact,$sms_msg);
			
						if($output == "202"){
							js_alert("There is a problem to verify Mobile number. Please check the number or try again later.");
							goto END;
						}
				}
			}else{
		
				$msg="You can create max 3 user account including yourself. For cretaing more user, Kindly contact Company-Name";
			}
		}else{																//update query
			$qry = func_update_qry("po_memb_mast",$fld_arr," where id=$id");
			if(!mysqli_query($con, $qry)){
				$incomp_msg="Something went wrong try again later.";
			}else{
				$msg="Record updated Successfully.";
			}
		}	
		?>
		<script>
			alert("<?=$msg?>");
			//window.location.href="manage_users.php";
		</script>
		<?php
	
	}else{
		?>
		<script>
			alert("<?=$msg?>");
			//window.location.href="manage_users.php";
		</script>
		<?php
	}
}

if(isset($_POST["btn_resend"])){			//Re-sending mail and sms on click on resend button 
	
	$email=func_read_qs("user_email");
	$fld_arr["po_company_id"] = $cid;
	$contact = func_read_qs("contact_no");	
	get_rst("select sms_code,act_id from po_memb_mast where id=0$id",$row);
	$sms_verify_code = $row['sms_code'];
	$act_code = $row['act_id'];
	$actlink= get_base_url()."/saas/pwdCreation.php?code=".$act_code;
				
	get_rst("select memb_email,memb_tel from member_mast where memb_id=".$_SESSION["user_id"],$r);
	get_rst("select company_id from po_company_mast where po_company_id=".$_SESSION["company_id"],$rowcid);
					
	$mail_body.="<br>Company-Name Briefcase account is successfully created for your use.  Kindly <a href='$actlink'>click here </a> to set a new password for your account.";
	$mail_body.="<br><br>Your login ID: $email ";
	$mail_body.="<br>Company ID: ".$rowcid['company_id'] ;
							
	$role=$_POST["role_id"];
		if($role==2){
			$role="Purchase Manager";
		}else{
			$role ="Finance Manager";
		}
		
	$mail_body.="<br>Role: $role";
	$mail_body.="<br><br>Admin Details: ".$r['memb_email'].",". $r['memb_tel'] ;
		
	$mail_body.="<br><br>Confirm your Mobile No. $contact on the page using the OTP send to your registered mobile no.";
	$mail_body.="<br> <br> Welcome to Company-Name Briefcase - your Procurement Management System.";
	$mail_body.="<br> <br> Regards,<br>Company-Name Briefcase";
			
		if(send_mail($email,"Company-Name Briefcase - User Creation ",$mail_body)){
			js_alert("Record saved Successfully").
			js_redirect("manage_users.php");
		}else{
			js_alert("Due to technical problems we are unable to send mail to user.");					
		}
					
	$sms_msg = "Hi, Your mobile verification code is ".$sms_verify_code.". Kindly enter this code on set new password page to enable your account.";
	$output = send_sms($contact,$sms_msg);
			
		if($output == "202"){
			js_alert("There is a problem to verify Mobile number. Please check the number or try again later.");
			goto END;
		}
	
}

$act = func_read_qs("act");
if($act=="d"){								//delete sub-user
	if(!mysqli_query($con, "delete from po_memb_mast where id=".func_read_qs("id"))){
		echo("Problem updating database... $qry");
	}else{
		
		get_rst("select member_count from po_company_mast where po_company_id=$cid",$row_mcount);
			$memb_count =  $row_mcount['member_count'] - 1;
			execute_qry("update po_company_mast set member_count=$memb_count where po_company_id=$cid");
		?>
		<script>
			alert("Record Deleted successfully.");
			window.location.href="manage_users.php";
		</script>
		<?php
		die("");
	}	
}
END:
?>

<script>


	function js_delete(){
		if(confirm("Are you sure you want to delete this record?")){		
			document.frm.act.value="d";
		}else{
			return false;
			
		}
	}

	function Submit_form(){
		if(chkForm(document.frm)==false)
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

<style>

.div_tree{
  position:absolute;
  top:0px;
  left:0px;
  width:100%;
  height:100%;
  background:rgba(200,200,255,0.9);
  
}

.div_inner
{
    position:relative; 
    top:100px;
    height:500px;
    width:800px;
    border: solid 1px #000000;
    background-color:#FFFFFF;
	xoverflow: scroll;
}

.div_inner_tree{
	overflow: scroll;
	background-color:#FFFFFF;
	border:none;
	padding:10px;
}
	
</style>


<?php
if($id){
	$page_head = "Edit User Details";
}else{
	$page_head = "Create New User";
}
?>

<h2><?=$page_head?></h2>

<form name="frm" id="frm" method="post" enctype="multipart/form-data">
	<input type="hidden" id="id" name="id" value="<?=func_var($id)?>">
	<input type="hidden" id="act" name="act" value="">
	<table border="1" class="table_form">

		<tr>
		<td>User Role</td>
		<td>
		<select name="role_id" id="100" title="User Role">
			<?php func_option("--Select--","",func_var($role_id))?>
			<?php func_option("Purchase Manager",2,func_var($role_id))?>
			<?php func_option("Finance Manager",3,func_var($role_id))?>
		</select>*
		</td>
		</tr>
		
		<tr>
		<td>Email Address</td>
		<td><input type="text" size="71" maxlength="100" id="120" title="Email Address" name="user_email" value="<?=func_var($user_email)?>">*</td>
		</tr>
		
		<tr>
		<td>Mobile No.</td>
		<td><input type="text" size="71" maxlength="10" id="100" title="Mobile Number" name="contact_no" onkeypress='validate_key(event)' value="<?=func_var($contact_no)?>">*</td>
		</tr>
			
		<tr>
		<th colspan="2" id="centered">
		<input type="submit" class="btn btn-warning" value="Save" name="submit" onClick="javascript:return Submit_form();">
		<?php if($id<>""){?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" class="btn btn-warning" value="Resend" name="btn_resend">
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" class="btn btn-warning" value="Delete" name="btn_delete" onclick="javascript: return js_delete();">
		<?php }?>
		
		</td>
		</tr>
		
	</table>
</form>

<?php 
require_once("inc_admin_footer.php");

?>
