<?php/require("inc_admin_header.php");
$id = func_read_qs("id");
$msg="";
if($id<>""){
	get_rst("select * from career_applicants where cr_applicant_id=0$id",$row);
	


	$flag=1;
	$email=$row["cr_applicant_email"];
	$name=$row["cr_applicant_name"];
	$mobile=$row["cr_applicant_mobile"];
	$j_title=$row["job_title"];
	$app_status=$row["app_status"];
	$upload_doc= array();
	for($j=0;$j<1;$j++){
		$upload_doc[0] =$_SERVER["DOCUMENT_ROOT"]."/".$row["cr_applicant_resume"];
	}
	//echo $file_path;
}	
	if(isset($_POST["submit"])){
		$app_status=func_read_qs("app_status");
		if($app_status == "Selected"){
			$to=func_read_qs("hiring_mang_email");
			$subject="Career Application"." "."(".$jtitle.")";
			$body="Hello,<br><br> The following candidate has been selected by the concern manager. Please process the joining formalities and offer letter for the same.<br><br>Job Title:&nbsp;$j_title<br> Name:&nbsp;$name<br>Email:&nbsp;$email<br>Mobile No.:&nbsp;$mobile<br><br>Please find attached resume of the candidate.<br><br>Thank You";
			$from="noreply@Company-Name.com";
			require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/PHPMailerAutoload.php');
			global $env_prod;
			if(multi_attach_mail($to,$subject,$body,$from,$upload_doc)){	
				js_alert("Application status updated successfully. Notification email has been send to Hiring Manager.");
				}else{ 
					js_alert("There is a problem sending email to Hiring Manager.");
					 }
			
		}
		elseif($app_status == "Rejected"){
			$mail_body = "Hi $name,<br><br> Thank you for showing interest in our organisation.We regret to inform that after careful review of your appliction, we find you to be unsutaible for the current job opening. Your application will reciving in our system, and will get in touch with you when any suitable opening comes up with us.<br><br>Thanks and Regards,<br>HR Company-Name";
			$from = "hr@Company-Name.com";		
			if(xsend_mail($email,"Company-Name - Careers ",$mail_body,$from )){	
				js_alert("Application status updated successfully. Rejection email has been send to applicant.");
				}else{ 
					js_alert("There was a problem sending rejection email to applicant.");
					 }
		}
		execute_qry("update career_applicants set app_status='$app_status' where cr_applicant_id=0$id");
			
	}
	
	
?>
<style>
table#trbg tr:nth-child(even) {
    background-color: #eee;
}
table#trbg tr:nth-child(odd) {
   background-color:#fff;
}
</style>
<script type="text/javascript">
function validate(){
	if(document.getElementById('app_status').value=='Selected'){
					if(document.getElementById('hiring_mang_email').value==""){
						alert("Please Enter Hiring Manager's Email Id");
						return false;
					}
					else{
					document.frm_jobapp.submit();
					return true;
					}
				}
			else if(document.getElementById('app_status').value=='Rejected'){
				if(confirm("Are you sure to Reject this application?")){
					document.frm_jobapp.submit();
					return true;
				}
				else{
					return false;
				}
			}
			else if(document.getElementById('app_status').value==""){
				alert("Please select application status");
				return false;
			}
			
			
}
function check_email(){
var email = document.getElementById('hiring_mang_email');
				var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

					if (!filter.test(email.value)) {
					alert('Please provide a valid email address');
					email.focus;
					return false;
				}


}
function Checkoption(val){
	 
 var element=document.getElementById('h_m_email');
 if(val=='Selected'){	 
 element.style.display='block';
 
 }
 else  
   element.style.display='none';
}

</script>
<form name="frm_jobapp" method="post">
<input type="hidden" name="act" value="1">
<div id="sup_profile" style="font-size: 14px" >	
<div class="solidTab panel panel-info" style="display: block" id="b2">
<div class="panel-heading">Job Application</div>
<table id="trbg" width="100%" border="0" cellspacing="1" cellpadding="5">
	
	<tr>
		<td>Name</h3></td>
		<td><?=$row["cr_applicant_name"]?></td>
		
	</tr>		
	<tr>
		<td>Email</h3></td>
		<td><?=$row["cr_applicant_email"]?></td>
		
	</tr>
	<tr>
		<td>Mobile No.</h3></td>
		<td><?=$row["cr_applicant_mobile"]?></td>
	</tr>
	<tr>
		<td>Job Title</h3></td>
		<td><?=$row["job_title"]?></td>
	</tr>
	<tr>
		<td>Resume</td>
		
		<td><?php	
		$ext="";
		$ext = pathinfo($row["cr_applicant_resume"], PATHINFO_EXTENSION);
		if($ext=="pdf"){
			$flag=0;
		?><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModal1">Show Resume</button>
		<?php }else { 
			$path= $_SERVER["SERVER_NAME"]."/".$row["cr_applicant_resume"];
			//echo $path;
		?>
		 <a href="http://<?=$path?>"> Click Here </a>&nbsp; to download resume
		<?php } ?>
		</td>
		
	</tr>
	
	<tr>
		<td>Application Status</td>
		<td>
		<?php if($app_status<>"") { ?>
		<select disabled name="app_status" id="app_status" title="Application status" onchange='Checkoption(this.value);' value=<?=$app_status?>>
		<?php } 
		else{ ?>
		<select name="app_status" id="app_status" title="Application status" onchange='Checkoption(this.value);' value=<?=$app_status?>>
		<? } ?>
		<option value="">Select</option>
			<?php func_option("Selected","Selected",func_var($app_status))?>
			<?php func_option("Rejected", "Rejected" ,func_var($app_status))?>
		</select>
		</td>
	</td>
	</tr>
</table>
<table id="h_m_email" style="display:none;">
	<tr >
		<td>Hiring  Manager's Email ID</td>
		<td>
		<input type="text" name="hiring_mang_email" id="hiring_mang_email" size="30" onblur="javascript: check_email(this);" maxlength="50" style="margin-left:200px">
		</td>
	</tr>
</table>
</div>
</div>
<table cellspacing="1" cellpadding="5" align="center">
<tr>
		<th colspan="10" id="centered">
		<input type="button" class="btn btn-warning" value="Back" name="Back" onClick="javascript: window.location.href='manage_job_application.php';">
		&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="submit" class="btn btn-warning" value="Save" name="submit" onclick="javascript: return validate();">		
		</td>
	</tr>
</table>

</form>
<?php
require_once("inc_admin_footer.php");
?>
<?php
if($flag==0)
{ ?>
<div id="myModal1" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:900px;">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body" style=" overflow=scroll;">
		<center><iframe src="http://<?php echo $_SERVER["SERVER_NAME"]."/".$row["cr_applicant_resume"];?>" width="100%" height="500px"></iframe></center> 
	  </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
	  </div>
    </div>
  </div>
</div>

<?php } ?>