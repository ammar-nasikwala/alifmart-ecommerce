<?php
require_once("inc_admin_header.php");
$id = func_read_qs("id");

if(isset($_POST["approved"])){

	$fld_arr = array();
	$fld_arr["owner_id"] = $id;
	get_rst("select memb_company,memb_email,memb_pwd from member_mast where memb_id=0$id",$row_comp);
	$company_name = $row_comp["memb_company"];
	$comp=strtoupper(substr($company_name,0,3));
	$comptoken=str_pad($id, 5, '0', STR_PAD_LEFT);
	$comptoken = $comp.$comptoken;
	$fld_arr["company_id"]=$comptoken;
	
	$sqlIns = func_insert_qry("po_company_mast",$fld_arr);
	mysqli_query($con,$sqlIns);

	execute_qry("update member_mast set subscribe_saas=1 where memb_id=0$id");
	$mail_body= " Congratulations! You are successfully subscribed as the Owner of Company-Name Briefcase. Your login credentials are:";
	$mail_body.="<br> Email ID -".  $row_comp["memb_email"];
	$mail_body.="<br> Password -".  $row_comp["memb_pwd"];
	$mail_body.="<br> Token -".  $comptoken;
	$mail_body.="<br> <br> Thanks & Regards <br> Team Company-Name";
   
	if(send_mail($row_comp["memb_email"],"Company-Name - Briefcase Owner ",$mail_body)){
	js_alert("Mail is sent successfully to user."); 
	}else{
	js_alert("Due to technical problems we are unable to send mail to user.");     
	}
}

if(isset($_POST["reject"])){
	
	get_rst("select memb_fname,memb_email from member_mast where memb_id=0$id",$row_comp);
	$name = $row_comp["memb_fname"];
	
	execute_qry("update member_mast set subscribe_saas=2 where memb_id=0$id");
	
	$mail_body= " $name,<br> We're sorry to inform you that your request for Briefcase - Online Procurement Management System Feature subscription is rejected by our moderator based on your Business model. We look forward for your continued support & patronage. ";
	$mail_body.="<br> <br> Regards,<br> <br>Company-Name Briefcase <br> Your Procurement Management System";
   
  if(send_mail($row_comp["memb_email"],"Company-Name Briefcase",$mail_body)){
	js_alert("Request is rejected successfully."); 
  }else{
    js_alert("Due to technical problems we are unable to send mail to user.");     
  }
}

if($id){
	$page_head = "Edit User Details";
}else{
	$page_head = "Create New User";
}
get_rst("select memb_company,memb_email,subscribe_saas from member_mast where memb_id=0$id",$row);
?>
<script type="text/javascript">
function reject_conform(){
	if(confirm("Are you sure to Reject this application?")){
		return true;
	}else{
		return false;
		}
}
</script>
<h2><?=$page_head?></h2>

<form name="frm" id="frm" method="post" enctype="multipart/form-data">
	<input type="hidden" id="id" name="id" value="<?=func_var($id)?>">
	<input type="hidden" id="act" name="act" value="">
	<table border="1" class="table_form">

		<tr>
		<td>User Email: </td>
		<td><input type="text" size="71" maxlength="100" id="user_email" readonly title="Email Address" name="user_email"  value="<?=$row["memb_email"]?>"></td>
		</tr>
		<tr>
		<td>Establishment Name: </td>
		<td><input type="text" size="71" maxlength="100" id="user_company" readonly title="Establishment Name" name="user_company" value="<?=$row["memb_company"]?>"></td>
		</tr>
		
		<tr>
		<th colspan="2" id="centered">
		<?php if($row["subscribe_saas"] == 1 || $row["subscribe_saas"] == 2){?>
			<input type="submit" class="btn btn-warning" value="Approve" disabled name="approved">
			&nbsp;&nbsp;
			<input type="submit" class="btn btn-warning" value="Reject" disabled name="reject">
		<?php }else{?>
			<input type="submit" class="btn btn-warning" value="Approve" name="approved">
			&nbsp;&nbsp;
			<input type="submit" class="btn btn-warning" value="Reject" name="reject" onclick="javascript: return reject_conform();">
		<?php }?>
		
		&nbsp;&nbsp;
		<input type="button" class="btn btn-warning" value="Back" name="btn_back" onclick="window.location = 'manage_briefcase_users.php'">

		</td>
		</tr>
	</table>
</form>

<?php
require_once("inc_admin_footer.php");

?>