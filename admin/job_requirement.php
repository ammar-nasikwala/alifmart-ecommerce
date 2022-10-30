<?php
require("inc_admin_header.php");
$id = func_read_qs("id");

$j_label="";
$j_des="";
$h_email="";
$btn="Save";
$flag="";

?>


<?php
if($id){
	$page_head = "Edit Job Requirement";
}else{
	$page_head = "Create New Job Requirement";
}
if(func_read_qs("hdnsubmit") == "y") {
		$j_des=addslashes($_POST["job_des"]);
		$h_email=$_POST["h_email"];
		$j_status=$_POST["job_status"];
		$j_title=$_POST["job_title"];
		
		$j=$_POST["job_openings"];
		if($j=='other'){
			$j_opening=$_POST["openings"];
		}
		else{
			$j_opening=$_POST["job_openings"];
		}
		
		if($id==""){
			if(get_rst("select * from job_requirement where job_title='$j_title' and job_status='Open'",$row)){
				$msg="Job requirement for $j_title already present.Please select other requirement.";
			
			}
			else{
			$result= mysqli_query($con,"INSERT INTO job_requirement SET job_title='".$j_title."', job_des= '".$j_des."', hiring_mng_email= '".$h_email."',job_status = '".$j_status."' ,job_opening = '".$j_opening."',posting_date = now(); ");
			
			if($result){
				$msg = "The Requirement has been created successfully";
				
				$subject="Career Application"." "."(".$jtitle.")";
				$body="Hello,<br><br>New job requirement has been created, with following details:<br><br> Job Title:&nbsp;&nbsp;&nbsp;$j_title<br>No of opening:&nbsp;$j_opening<br>Job Discription:&nbsp;$j_des<br><br>Thank You";
				$from="noreply@Company-Name.com";	
				xsend_mail($h_email,"Company-Name - Careers ",$body,$from );
				
				$j_des="";
				$h_email="";
				$j_status="";
				$j_opening="";
				$j_title="";
						
			}else{
				
			$msg = "Creation failed! Please check details or try again after some time.";
			}	
		}
		}else{
			
			$fld_arr = array();
		
			$fld_arr["job_title"]=func_read_qs("job_title");
			$fld_arr["job_des"]=func_read_qs("job_des");
			$fld_arr["hiring_mng_email"]=func_read_qs("h_email");
			$fld_arr["job_status"]=func_read_qs("job_status");
			$fld_arr["job_opening"]=func_read_qs("job_openings");
			$fld_arr["posting_date"]=date('Y-m-d');
			echo $fld_arr["posting_date"];
			$qry = func_update_qry("job_requirement",$fld_arr," where job_id=".$id);
			$result = mysqli_query($con,$qry);
			if($result){
				$msg = "The details has been updated successfully";
			}else{
				
			$msg = "Update failed! Please check details or try again after some time.";
			}	
}
}		
if($id<> ""){
	$btn="Update";
	get_rst("select * from job_requirement where job_id=0$id",$row);
	$j_title = $row["job_title"];
	$j_des = $row["job_des"];
	$h_email = $row["hiring_mng_email"];
	$j_status = $row["job_status"];
	$j_opening = $row["job_opening"];

		$PWDSelected = "";
		$PWDISelected= "";
		$AnIDSelected= "";
		$AnIDISelected= "";
		$DEOSelected= "";
		$MkMSelected= "";
		$MkISelected= "";
		$DMMSelected= "";
		$DMISelected= "";
		$MTSelected = "";
		$MTISelected= "";
		$ATSelected= "";
		$ATISelected= "";
		$OASelected= "";
		//Making Title selected based
		switch($j_title) {
			case "PHP/Web Developer": $PWDSelected = "selected"; 
						break;
			case "PHP/Web Development Intern": $PWDISelected = "selected"; 
						break;
			case "Android/IOS Developer": $AnIDSelected = "selected"; 
						break;
			case "Android/IOS Development Intern": $AnIDISelected = "selected"; 
						break;
			case "Data Entry Operator": $DEOSelected = "selected"; 
						break;
			case "Marketing Manager": $MkMSelected = "selected"; 
						break;
			case "Marketing Intern": $MkISelected = "selected"; 
						break;
			case "Digital Marketing Manager": $DMMSelected = "selected"; 
						break;	
			case "Digital Marketing Intern": $DMISelected = "selected"; 
						break;
			case "Manual Tester": $MTSelected = "selected"; 
						break;
			case "Manual Testing Intern": $MTISelected = "selected"; 
						break;
			case "Automated Tester": $ATSelected = "selected"; 
						break;
			case "Automated Testing Intern": $ATISelected = "selected"; 
						break;
			case "Office Administrator": $OASelected = "selected"; 
						break;
		}
}		
		
?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<script type="text/javascript">
function validate(){

			
if(document.getElementById("job_title").value==""||document.getElementById("job_des").value==""||document.getElementById("h_email").value==""||document.getElementById("job_status").value==""||document.getElementById("j_o").value==""){
			alert("Please Enter All The Details");
			return false;
			}else{
				
				var email = document.getElementById('h_email');
				var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

					if (!filter.test(email.value)) {
					alert('Please provide a valid email address');
					email.focus;
					return false;
				}
				if(document.getElementById('j_o').value=='other'){
					if(document.getElementById('job_openings').value==""){
						alert("Please Enter All The Details");
						return false;
					}

				}
				display_gif();
				document.frm_jobreq.submit();
				return true;
				}
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
	
function Checkoption(val){
	 
 var element=document.getElementById('job_openings');
 if(val=='other'){	 
 element.style.display='block';
 
 }
 else  
   element.style.display='none';
}

function maxlimit(){
	var num=document.getElementById('job_openings').value;
	if(num <=10){
		alert("Please select the value from Drop-down");
		return false;
	}
	 
}
</script>
</head>
<h2><?=$page_head?></h2>
<?php
	if($msg <> "") { ?>
	<div class="alert alert-info">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<?=$msg?>
	</div>
	<?php } ?>
<form name="frm_jobreq" id="frm_jobreq" method="post" enctype="multipart/form-data" action="">
	<input type="hidden" name="hdnsubmit" value="y">
	<table border="1" class="table_form">
		<tr><td>Job Title: </td>
			<td><select id="job_title" class="form-control textbox-lrg" title="job_title" name="job_title"  tabindex="1" value="<?=$j_title?>">
					<option value="">Select</option>
					<option value="PHP/Web Developer" <?=$PWDSelected?>>PHP/Web Developer</option>
					<option value="PHP/Web Development Intern" <?=$PWDISelected?>>PHP/Web Development Intern</option>
					<option value="Android/IOS Developer" <?=$AnIDSelected?>>Android/IOS Developer</option>
					<option value="Android/IOS Development Intern" <?=$AnIDISelected?>>Android/IOS Development Intern</option>
					<option value="Data Entry Operator" <?=$DEOSelected?>>Data Entry Operator</option>
					<option value="Marketing Manager" <?=$MkMSelected?>>Marketing Manager</option>
					<option value="Marketing Intern" <?=$MkISelected?>>Marketing Intern</option>
					<option value="Digital Marketing Manager" <?=$DMMSelected?>>Digital Marketing Manager</option>
					<option value="Digital Marketing Intern" <?=$DMISelected?>>Digital Marketing Intern</option>
					<option value="Manual Tester" <?=$MTSelected?>>Manual Tester</option>
					<option value="Manual Testing Intern" <?=$MTISelected?>>Manual Testing Intern</option>
					<option value="Automated Tester" <?=$ATSelected?>>Automated Tester</option>
					<option value="Automated Testing Intern" <?=$ATISelected?>>Automated Testing Intern</option>
					<option value="Office Administrator" <?=$OASelected?>>Office Administrator</option>
				</select></td>
		</tr>
		<tr><td>Job Description: </td>
			<td><textarea id="job_des" name="job_des" class="form-control textbox-lrg" rows="5" col="7" tabindex="2" maxlength="5000"><?=$j_des?></textarea></td>
		</tr>
		<tr><td>Hiring Manager's Email Id: </td>
			<td><input type="text" name="h_email" id="h_email" class="form-control textbox-lrg" tabindex="3" onblur="javascript: check_email(this);" value="<?=$h_email?>">
			</td>
		</tr>
		<tr><td>Job Status: </td>
			<td><select name="job_status" id="job_status" title="Job Status" tabindex="4" class="form-control textbox-lrg" value="<?=$j_status?>">
					<option value="">Select</option>
					<?php func_option("Open","Open",func_var($j_status))?>
					<?php func_option("Close", "Close" ,func_var($j_status))?>
				</select></td>
		</tr>
		<tr><td>No of openings: </td>
			<td><select type="text" name="job_openings" id="j_o" class="form-control textbox-lrg" tabindex="5" onchange='Checkoption(this.value);' value="<?=$j_opening?>">
					<option value="">Select</option>
					<?php func_option("1","1",func_var($j_opening))?>
					<?php func_option("2","2",func_var($j_opening))?>
					<?php func_option("3","3",func_var($j_opening))?>
					<?php func_option("4","4",func_var($j_opening))?>
					<?php func_option("5","5",func_var($j_opening))?>
					<?php func_option("6","6",func_var($j_opening))?>
					<?php func_option("7","7",func_var($j_opening))?>
					<?php func_option("8","8",func_var($j_opening))?>
					<?php func_option("9","9",func_var($j_opening))?>
					<?php func_option("10","10",func_var($j_opening))?>
					<option value="other">other</option>
				</select>
			</td>
		</tr>
		<tr>
			<td></td>	
			<td><input type="text" name="openings" id="job_openings" class="form-control textbox-lrg" tabindex="6" style="display:none;" onchange="maxlimit();" onkeypress="validate_key(event);" value="<?=$j_opening?>">
			</td>
		</tr>
	</table>
	
	<table cellspacing="1" cellpadding="5" align="center">
		<tr>
			<th colspan="10" id="centered">
			<input type="button" class="btn btn-warning" value="Back" name="Back" tabindex="7" onClick="javascript: window.location.href='manage_job_requirement.php';">
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" class="btn btn-warning" onclick="javascript: return validate();" tabindex="8" value="<?=$btn?>" name="submit">		
			</td>
		</tr>
	</table>

<?php
require_once("inc_admin_footer.php");
?>