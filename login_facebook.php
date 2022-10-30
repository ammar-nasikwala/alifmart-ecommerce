<?php
require("inc_init.php");

global $con;
$oauth_id=$_SESSION["social_fbid"];
$phoneno=$_POST['tele'];
$email=$_POST['email'];
$act_id = get_act_id($email);
//$act_link = get_base_url()."/register.php?id=".$fld_arr;
$sms_verify_code = mt_rand(1000, 9999);	
$fname=$_SESSION["fname_social"];
	
				$sms_verify_status=0;
	$fld_arr = array();
	
	$fld_arr["memb_fname"] = $fname;
	$fld_arr["memb_email"] = $email;
	$ind_buyer=0;

			if(isset($_POST['Ind-buyer'])){
				$memb_cstn=$_POST["memb_cstn"];
				$memb_vat=$_POST["memb_vat"];
				$memb_company=$_POST["memb_company"];
				$memb_vat_doc = "";
				$memb_cst_doc = "";
				
				if(isset($_FILES["files"])){					
					$errors= array();
					$maxsize    = 2097152;
					$acceptable = array(
					'application/pdf',
					'image/jpeg',
					'image/jpg',
					'image/gif',
					'image/png'
					);
					$upload_doc_arr = array();
					for($i=0; $i<2; $i++){		
						$file_name = $_FILES['files']['name'][$i];
						if($file_name <> ""){
							$file_tmp =$_FILES['files']['tmp_name'][$i];
							$desired_dir=$_SERVER['DOCUMENT_ROOT']."/extras/user_data/id-".$memb_id;
							
							if(!in_array($_FILES['files']['type'][$i], $acceptable) && (!empty($_FILES["files"]["type"][$i]))) {
								$errors[] = 'Invalid file type. Only PDF, JPG, GIF and PNG types are accepted.';
							}
							if((filesize($file_tmp) >= $maxsize) || (filesize($file_tmp) == 0)) {
								$errors[] = 'File is too large. File size must be less than 2 megabytes.';
							}
							
							if(empty($errors)==true){
								if(is_dir($desired_dir)==false){
									mkdir("$desired_dir", 0700);		// Create directory if it does not exist
								}
								$file_path="";
								$file_path="extras/user_data/id-".$memb_id."/".$file_name;
								move_uploaded_file($file_tmp,$file_path);
								$imageFileType = strtolower(pathinfo($file_path,PATHINFO_EXTENSION));
								$upload_doc_arr[$i]=img_resize_db($file_path, 900, 520, $imageFileType);
							}else{
									$act="0";
									$msg= "Upload Failed!! ".$errors[0] ;
									break;
							}
						}else{
							$upload_doc_arr[$i]="";
						}
					}
					$memb_cst_doc = $upload_doc_arr[0];
					$memb_vat_doc = $upload_doc_arr[1];
					
					if($memb_cst_doc <> "") $memb_cst_doc_social = $memb_cst_doc;
					if($memb_vat_doc <> "") $memb_vat_doc_social = $memb_vat_doc;
					$memb_cstn_social = $memb_cstn;
					$memb_vat_social = $memb_vat;
					$memb_company_social = $memb_company;
					$ind_buyer = 1;
				}	
			}
				
if(isset($_POST['submit']))
{

        if(!empty($phoneno) && preg_match('/^[0-9]{10}+$/', $phoneno))
        {
			$update = mysqli_query($con,"UPDATE member_mast SET memb_email='".$email."',memb_tel='".$phoneno."',sms_verify_code='".$sms_verify_code."',memb_act_id='".$act_id."',sms_verify_status='".$sms_verify_status."',memb_cstn='".$memb_cstn_social."',memb_vat='".$memb_vat_social."',memb_company='".$memb_company_social."',ind_buyer='".$ind_buyer."',memb_cst_doc='".$memb_cst_doc_social."',memb_vat_doc='".$memb_vat_doc_social."' WHERE  oauth_uid='".$oauth_id."'");
            //$update = mysqli_query($con,"UPDATE member_mast SET memb_email='".$email."',memb_tel='".$phoneno."',sms_verify_code='".$sms_verify_code."',memb_act_id='".$act_id."',sms_verify_status='".$sms_verify_status."' WHERE oauth_uid='".$oauth_id."' ");
            $sms_msg = "Hi ".$fname.",This is a verification SMS for your mobile number as part of registration on Company-Name.com, your verification code is ".$sms_verify_code;
            $output = send_sms($phoneno,$sms_msg);
				
				if($output == "202"){
					$incomp_msg = "There was a problem verifying your Mobile number Please check the number or try again later.";
					break;
				}	
		$fld_arr["act_link"] = get_base_url()."/register.php?id=".$act_id;
				
				$mail_body = push_body("buyer_facebook_activation.txt",$fld_arr);
				$from = "welcome@Company-Name.com";		
				if(xsend_mail($fld_arr["memb_email"],"Company-Name - Buyer account verification email",$mail_body,$from )){
					$incomp_msg="Thank you for registering with us. You will shortly receive an activation link via email. Please follow the link to activate your account.";
					$s_flag = "1";
				}else{ 
					$incomp_msg="There was a problem sending email to your email address.";
				}
        }
      
    else
      {
     ?>
      <script type="text/javascript">
        alert("Enter Mobile Number Properly");
      </script>
    <?php
      }
}
?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Company-Name - Membership Registration</title>
<link href="styles/styles.css" rel="stylesheet" type="text/css" />
<link href="styles/styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function send_sms(){
	var obj_fname = document.getElementById("fname_s").value;
	var obj_tele = document.getElementById("tele_no").value;
	var obj_sms_code = document.getElementById("sms_code").value;
	var output_msg = call_ajax("ajax.php","process=send_sms&sms_code=" + obj_sms_code + "&tele_no=" + obj_tele + "&fname=" + obj_fname)
	alert(output_msg)
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
  
function validate(){
	v_valid = check_email(document.frm.email);
	p_valid = check_phno(document.frm.tele);
	//if(v_valid != "Available"){
		//alert("This email already exists. Please provide another one.");
	//}
	
	if(chkForm(document.frm)==false){
		return false;
	}else if(v_valid != "Available"){
		alert(v_valid)
		return false;	
	}else if(p_valid != "Available"){
		alert(p_valid)
		return false;	
	}else{
			if(document.getElementById("chk").checked){
				if(document.getElementById("es_name").value==""||document.getElementById("cst_n").value==""||document.getElementById("vat_n").value==""){
				alert("Please Enter All The Details");
				return false;
				}else{
					document.frm.submit();
					return true;
				}	
		   }
		//alert("here")
		//sms_msg = call_ajax("ajax.php","process=get_sms_msg&memb_email=" + document.frm.email.value + "&memb_fname=" + document.frm.fname.value);
		//document.frm.sms_msg = sms_msg;
		document.frm.submit();
		return true;
	}
}

function check_email(obj){
	v_valid = ""
	if(obj.value != ""){
		var obj_lbl = document.getElementById("msg_email")
		
		v_valid = call_ajax("ajax.php","process=check_memb_email&memb_email=" + obj.value)
		obj_lbl.innerHTML = v_valid
		if(obj_lbl.innerHTML=="Available"){
			obj_lbl.style.color="#44FF88"
			//v_valid = "1"
		}else{
			obj_lbl.style.color="#FF5588"
		}
	}
	return v_valid;
}

function check_phno(obj){
	v_valid = ""
	if(obj.value != ""){
		var obj_lbl = document.getElementById("msg_phone")
		
		v_valid = call_ajax("ajax.php","process=check_memb_tel&memb_tel=" + obj.value)
		obj_lbl.innerHTML = v_valid
		if(obj_lbl.innerHTML=="Available"){
			obj_lbl.style.color="#44FF88"
			//v_valid = "1"
		}else{
			obj_lbl.style.color="#FF5588"
		}
	}
	return v_valid;
}

 window.onload = function() {
     if ($('#chk').is(':checked')) {
        $("#ind-buyer").show();
     } else {
        $("#ind-buyer").hide();
     }
    };
$(function () {
        $("#chk").click(function () {

            if ($(this).is(":checked")) {
                $("#ind-buyer").show();
				$("#not-indbuyer").hide();
            } else {
				$("#ind-buyer").hide();
                $("#not-indbuyer").show();
            }
        });
    });

</script>
</head>
<body>
<? require("header.php"); ?>
<div id="contentwrapper">
<div id="contentcolumn">
  <div class="center-panel">
    <div class="you-are-here">
      <p align="left"> YOU ARE HERE:<span class="you-are-normal"><a href="index.php" title="Home Page">Home</a> | Membership Registration</span> </p>
    </div>
   <!-- <h1>Membership Registration</h1> -->
<?php
if($incomp_msg <> "") { ?>
<div class="alert alert-info">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <?=$incomp_msg?>
</div>
<?php } ?>

<?if($s_flag==""){?>
    <form name="frm" method="post" action="" xonsubmit="javascript: return validate();" enctype="multipart/form-data">
	
    <table width="100%"  border="0" align="center" class="list">
      <tr>
        <td colspan="2" align="left" class="table-bg"><div align="left">
            <p>My Sign In Details</p>
        </div></td>
      </tr>
      <tr>
        <td width="31%" align="right" class="table-bg-left"><div align="right">
            <label for="Email"></label>
            <p>Email :</p>
        </div></td>
        <td width="69%" align="left" xclass="table-bg"><input name="email" class="form-control textbox-lrg" onblur="javascript: check_email(this);" type="text" id="120" title="Email" tabindex="1" size="20" maxlength="50" value="<?echo $_SESSION["email_social"];?>"/>
        *<span id="msg_email"></span></td>
      </tr>
      <tr>
        <td align="right" class="table-bg2"><div align="right">
            <label for="Mobile No."></label>
            <p>Mobile No.:</p>
        </div></td>
        <td align="left" class="table-bg2"><input name="tele" class="form-control textbox-lrg" onkeypress='validate_key(event)' onblur="javascript: check_phno(this);" type="text"  title="Mobile No." id="110" tabindex="2" size="20" maxlength="10" value="<?echo $_SESSION["tel_social"];?>"/>
        *<span id="msg_phone"></span></td></td>
      </tr>
	  </table>
	  
	  <table width="100%"  border="0" align="center" class="list">
	
    <tr>
        <td align="right" width="31%" class="table-bg2"><div align="right">
            <label for="Post Code"></label>
            <p>I am a Business Buyer: </p>
        </div></td>
        <td align="left" class="table-bg2"><input type="checkbox" name="Ind-buyer"  id="chk" tabindex="14" value="Ind-buyer" >
        </td>
      </tr> 
	  <tr>
        <td align="right" class="table-bg" colspan=10></td>
    </tr>
	   </table>
	  <div id="ind-buyer" style="display: block">
		<table width="100%"  border="0" align="center" class="list">
		
		  <tr>
			<td colspan="3" align="left" class="table-bg"><div align="left">
				<p>My Tax Details</p>
			</div></td>
		  </tr>
		<tr>
			<td align="right" class="table-bg2" ><div align="right"><p>Establishment name</p></div></td>
			<td align="left" class="table-bg2"><input type="textbox" id="es_name" title="Establishment Name" class="form-control textbox-mid" name="memb_company" maxlength="50" id="100" size="20" class="form-control" style="width:auto;" /> </td>
		</tr>
		  <tr>
			<td align="right" class="table-bg2" ><div align="right"><p>Central Sales Tax Account number (CST)</p></div></td>
			<td align="left" class="table-bg2"><input type="textbox" id="cst_n" title="Central Sales Tax Account number (CSTN)" class="form-control textbox-mid" name="memb_cstn" maxlength="12" id="100" size="20" class="form-control" style="width:auto;" /> </td>	
			<td align="left" class="table-bg2"><input name="files[]" type="file" size="40" style="width: 250px;"  accept=".pdf,.png, .gif, .jpg, .jpeg"/></td>
		  </tr>
	
		  <tr>
			<td align="right" class="table-bg2" ><div align="right"><p>Value Added Tax number (VAT)</p></div></td>
			<td align="left" class="table-bg2"><input type="textbox" id="vat_n" title="Value Added Tax number (VATN)" class="form-control textbox-mid" name="memb_vat" maxlength="12" id="100" size="20" class="form-control" style="width:auto;" /> </td>	
			<td align="left" class="table-bg2"><input name="files[]" type="file" size="40" style="width: 250px;" accept=".pdf,.png, .gif, .jpg, .jpeg" /></td>			
		  </tr>
		</table>
	</div>
	<div id="not-indbuyer" style="display: block">
	<table width="100%"  border="0" align="center" class="checkout" >
			<tr>
			<td><p class="color-amber">Note : </p></td>
			<td align="left" colspan="10" ><p class="color-amber">You can update Business Buyer details after registeration in profile edit section.</p></td>
			</tr></table></div>
	  <table width="100%"  border="0" align="center" class="list">
	<tr>
        <td align="right" class="table-bg" colspan=10></td>
    </tr>
    <tr>
	
		<td align="center">
			<input type="submit" class="btn btn-warning" onclick="javascript: return validate();" value=" Submit " name="submit" tabindex="15" >
		</td>
    </tr>
	</table>
	
</form>
<?}?>
</div>
</div>
</div>

<? require("left.php"); ?>

<? require("right.php"); ?>

<? require("footer.php"); ?>
</body>
</html>