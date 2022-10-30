<?php

require("inc_init.php");

$msg="";
$act=1;
$j="";

	if(func_read_qs("hdnsubmit") == "y"){
	
	if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['tele']))
	{
	$msg="Please enter all the details.";	
	}
	if($_POST['captcha'] != $_SESSION['digit']){
		$msg = "Please enter valid CAPTCHA code";
	}else{
		$name=addslashes($_POST["name"]);		//getting applicants details
		$email=$_POST["email"];
		$tele=$_POST["tele"];
		$jtitle=$_POST["job_title"];
		
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
		switch($jtitle) {
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
		

	if(isset($_FILES['files'])){		//for taken resume files		
			$errors= array();
			$maxsize    = 1268777;
			$acceptable = array(
			'application/pdf',
			'application/msword',
			"application/vnd.openxmlformats-officedocument.wordprocessingml.document"
			);
			$upload_doc="";
							
			$file_name = $_FILES['files']['name'];
				if($file_name <> ""){
					$file_tmp =$_FILES['files']['tmp_name'];
					$desired_dir=$_SERVER['DOCUMENT_ROOT']."/extras/careers/id-".$name;
							
						if(!in_array($_FILES['files']['type'], $acceptable) && (!empty($_FILES["files"]["type"]))) {
							$errors[] = 'Invalid file type. Only PDF, doc and docx types are accepted.';
							}
						if((filesize($file_tmp) >= $maxsize) || (filesize($file_tmp) == 0)) {
							$errors[] = 'File is too large. File size must be less than 2 megabytes.';
							}
							
						if(empty($errors)==true){
							if(is_dir($desired_dir)==false){
								mkdir("$desired_dir", 0700);		// Create directory if it does not exist
								}
							$file_path="";
							$file_path="extras/careers/id-".$name."/".$file_name;	//path where resume will be saved
							move_uploaded_file($file_tmp,$file_path);
								
							}else{
								$act=0;
								$msg= "Upload Failed!! ".$errors[0] ;	

							}
					}else{
						$upload_doc="";
						}
						$upload_doc=$file_path;	
						
			}	
		
				
		//inserting data in database
		if($act<>0){
		$insert = mysqli_query($con,"INSERT INTO career_applicants SET cr_applicant_name='".$name."', cr_applicant_mobile = '".$tele."', cr_applicant_email = '".$email."',cr_applicant_resume = '".$upload_doc."' ,job_title = '".$jtitle."'");
		
		//this is for sending resume to hr
		$u_doc= array();
				for($j=0;$j<1;$j++){
				$u_doc[0] =$_SERVER["DOCUMENT_ROOT"]."/".$file_path;
				}
		$to="hr@Company-Name.com";
		$subject="Career Application"." "."(".$jtitle.")";
		$body="Hello,<br><br> An application has been received, with following details:<br><br> Name:&nbsp;$name<br>Email:&nbsp;$email<br><br>Please find attached resume of the candidate.<br><br>Thank You";
		$from="noreply@Company-Name.com";	
		
			if(multi_attach_mail($to,$subject,$body,$from,$u_doc,"")){
				$msg="Thank you for showing interest in our organisation. Your details are saved and you will we be contacted if we find suitable position for you. ";
				}else{
					$msg="There was a problem getting your data please try again later.";
					 }
		
		//sending mail to applicant
		$mail_body = "Hi $name,<br><br> Thank you for showing interest in our organisation. <br>Your details are saved and you will we be contacted if we find a suitable position for you.<br><br>Thanks and Regards,<br>HR Company-Name";
		$from = "hr@Company-Name.com";		
			if(xsend_mail($email,"Company-Name - Careers ",$mail_body,$from )){	
				$msg="Thank you for showing interest in our organisation. Your details are saved and you will we be contacted if we find suitable position for you. ";
				}else{ 
					$msg="There was a problem sending email to your email address.";
					 }
			$name="";		
			$email="";
			$tele="";
			$jtitle="";
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
		}
	}
	}
?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Company-Name - Careers</title>
<link href="styles/styles.css" rel="stylesheet" type="text/css" />
<link href="styles/bannerstyle.css" rel="stylesheet" type="text/css" />
<script src="scripts/scripts.js" type="text/javascript"></script>
<script type="text/javascript" src="scripts/animatedcollapse.js"></script>
<script type="text/javascript">
	
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

function validate(){
	
		if(document.getElementById("name").value==""||document.getElementById("email").value==""||document.getElementById("tele").value==""||document.getElementById("job_title").value==""||document.getElementById("files").value==""){
			alert("Please Enter All The Details");
			return false;
			}
		var email = document.getElementById('email');
		var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

			if (!filter.test(email.value)) {
				alert('Please provide a valid email address');
				email.focus;
				return false;
			}
			
		var mob = /^[1-9]{1}[0-9]{9}$/;
		var txtMobile = document.getElementById('tele');
			if (mob.test(txtMobile.value) == false) {
				alert("Please enter valid mobile number.");
				txtMobile.focus();
				return false;
			}
		if(document.getElementById("captcha_code").value=="") {
			alert('Please enter the CAPTCHA code');
			frm.captcha.focus();
			return false;
		}
		else{
				display_gif();
				document.frm.submit();
				return true;
				}	
		
}
function check(val) {
	
	if(val!=""){
			document.getElementById("job_title").value= val;
	}	
	else{
		document.getElementById("div_desc").style.display='none';
	}
	
	
}	
function display_div(){
	var obj=document.getElementById("searchjob").value;
	if(obj!=""){
		var ans = call_ajax("ajax.php","process=search_job&searchjob=" + obj);
		var v_arr= ans.split('|');
		document.getElementById("desc").innerHTML =v_arr[0];
		document.getElementById("openings").innerHTML =v_arr[1];
		document.getElementById("postingdate").innerHTML =v_arr[2];	
		document.getElementById("div_desc").style.display='block';
	}else{
		alert("Please select your job.");
		return false;
	}
	
}

</script>
<style>
.div_desc{
    margin-left: 30px;
    border: 1px solid #ccc;
    width: 520px;
    padding:5px;
    border-radius: 5px;
	font-size:14px;


}
</style>
</head>
<body>

<? require("header.php"); ?>

<?if($msg<>""){?>
<div class="alert alert-info">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <?=$msg?>
</div>
<?}?>
<div id = "gif_show" style="display:none"></div>
<div id="content_hide">
	<div id="contentwrapper">
	<div class="center-panel">
    <div class="you-are-here">
		<p align="left"> YOU ARE HERE:<span class="you-are-normal"><a href="index.php" title="Home Page">Home</a> | Career Application</span> </p>
    </div>
			
	<table width="100%"  border="0" align="center" class="list">
		<tr>						
			<td style="width: 245px;">
				<label id="search">Search Current Job Openings </label>
			</td>
			<td style="width:20px;">
				<select name="searchjob" id="searchjob" type="text" class="form-control search" style="height: 30px; width: auto;"  placeholder="Search Your Job" maxlength="50" onchange='check(this.value);' class="search-area">
					<option value="">Select Your Job</option>
						 <?=create_cbo("select job_title,job_title from job_requirement where job_status='open' ",func_var($j))?>
				</select> 
						<?php $jtitle=$j; ?>
			</td>
			<td>	
				<button class="btn btn-warning" type="submit" style="border-top-left-radius: 0px; border-bottom-left-radius: 0px; height: 30px;" onclick="display_div();"> <span class="color-white glyphicon glyphicon-search glyf"></span></button>
			</td>
		</tr>
	</table>
	<div id="div_desc" class="div_desc" style="display:none">
		<div id="div_jd"  style="display:inline-flex;margin: 2px;"><label id="l1">Job Description:</label>&nbsp;<textarea readonly id="desc" style="width:344px;font-size:13px;margin-left: 5px;"></textarea></div>
		<div id="div_jop" style="display:inline-flex;margin: 2px;"><label id="l1">No.of openings:</label>&nbsp;<div id="openings"  style="margin-left:5px;"></div></div><br>
		<div id="div_jp"  style="display:inline-flex;margin: 2px;"><label id="l1">Posted on:</label>&nbsp;<div id="postingdate" style="margin-left:38px;"></div></div>
	</div>
<form name="frm" method="post" action="" enctype="multipart/form-data">
	<input type="hidden" name="hdnsubmit" value="y">
		<table width="100%"  border="0" align="center" class="list">
			<tr>
				<td colspan="2" align="left" class="table-bg"><div align="left">
				<p>Enter Details</p>
				</div></td>
			</tr>
			<tr>
				<td align="right" class="table-bg2"><div align="right">
				<label for="First Name2"></label>
				<p>Name :</p>
				</div></td>
				<td align="left" class="table-bg2"><input name="name" id="name" class="form-control textbox-lrg" type="text" id="100" title="First Name" tabindex="1" size="20" maxlength="20" value="<?=func_var($name)?>"/>
				*</td>
			</tr>
			<tr>
				<td width="31%" align="right" class="table-bg-left"><div align="right">
				<label for="Email"></label>
				<p>Email :</p>
				</div></td>
				<td width="69%" align="left" xclass="table-bg"><input name="email" id="email"  class="form-control textbox-lrg" type="text" id="120" title="Email" tabindex="2" size="20" maxlength="50" value="<?=func_var($email)?>"/>
				*<span id="msg_email"></span></td>
			</tr>
			<tr>
				<td align="right" class="table-bg2"><div align="right">
				<label for="Mobile No."></label>
				<p>Mobile No.:</p>
				</div></td>
				<td align="left" class="table-bg2"><input name="tele" id="tele" class="form-control textbox-lrg" type="text" onkeypress='validate_key(event)' title="Mobile No." id="110" tabindex="3" size="20" maxlength="10" value="<?=func_var($tele)?>"/>
				*<span id="msg_phone"></span></td></td>
			</tr>	
			<tr>
				<td align="right" class="table-bg2"><div align="right">
				<label for="Job Title"></label>
				<p>Job Title.:</p>
				</div></td>
				<td align="left" class="table-bg2">
				<select id="job_title" class="form-control textbox-auto" title="job_title" name="job_title" onclick="" tabindex="4" value="<?=$jtitle?>" >
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
				</select>
				*<span id="job_title"></span></td></td>
			</tr>	
			<tr>
				<td align="right" class="table-bg2" ><div align="right"><p>Resume :</p></div></td>
				<td align="left" class="table-bg2"><input name="files" type="file" id="files" size="40" tabindex="5" style="width: 250px;" accept=".pdf,.doc, .docx" /></td>			
			</tr>
			  
			<tr>
			<tr>
				<td align="right" class="table-bg2"><div align="right">
				<p>Validation code:</p>
				</div></td>
				<td><img src="/captcha.php" width="120" height="30" border="1" alt="CAPTCHA" id="captchaimg"><br>
				<label for='message'>Enter the above code here : </label><input type="text" style="margin-left:5px" id="captcha_code" size="8" class="form-control textbox-auto"  tabindex="6" maxlength="5" name="captcha" value="">
				
				<br>
				Can't read the image? click <a class="color-amber" href="javascript: refreshCaptcha();">here</a> to refresh.</td>
			</tr>
		<table width="100%"  border="0" align="center" class="list">
			<tr>
				<td align="right" class="table-bg" colspan=10></td>
			</tr>
			<tr>
				<td align="center">
				<input type="submit" class="btn btn-warning" onclick="javascript: return validate();" value=" Submit " name="submit" tabindex="7" >
				</td>
			</tr>
		</table>
	</table>
	<br>
	<br>
	<br>
</form>
	
	</div>
	</div>
</div>
<? require("footer.php"); ?>
</body>
<script src="scripts/chat.js" type="text/javascript"></script>
</html>
	