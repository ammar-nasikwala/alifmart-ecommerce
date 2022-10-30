<?php

$msg="";
error_reporting(E_ERROR | E_PARSE);
$status="readonly";
if(isset($_SESSION["manage_sellers"]))
{
	$status="";
}
global $support;
?>
<style>
#sup_upd_form{
	padding-left: 5px;
	padding-right: 5px;
}

.solidTab {
  background-color: #f1f1f1;
  line-height: 1.15em;
  word-wrap: break-word;
  margin-bottom: 12px;
  margin-top: 12px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
}

</style>

<script>
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

function validate_fileupload(obj)
{
	var fileName = obj.value;
    var allowed_extensions = new Array("jpg","png","gif","tif","tiff","bmp","JPG", "PNG", "JPEG", "jpeg", "GIF", "TIF", "TIFF", "BMP");
    var file_extension = fileName.split('.').pop(); 
    for(var i = 0; i <= allowed_extensions.length; i++)
    {
        if(allowed_extensions[i]==file_extension)
        {
            if(obj.files[0].size > 1048576){
				alert("Error! File too large, file size must be less than 1 megabyte.");
				obj.value = "";
				return false;
			}
			return true;
        }
    }
	alert("Error! File type not allowed, only image files of jpg, png, gif, tif, tiff and bmp are accepted.");
	
    return false;
}

function check_pwd(obj){
	var passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,54}$/; 
	validpass=""
	if(obj.value != ""){
		var obj_lbl = document.getElementById("msg_pwd")
		
		if(obj.value.match(passw)){
			obj_lbl.innerHTML = "OK"
			validpass="Ok"
		}else{
			obj_lbl.innerHTML = "Your password must contain a minimum of 8 characters and a maximum of 54 characters. It must contain at-least 1 number, 1 capital letter and 1 lower-case letter."
			validpass="Your password must contain a minimum of 8 characters and a maximum of 54 characters. It must contain at-least 1 number, 1 capital letter and 1 lower-case letter."
		}
		if(obj_lbl.innerHTML=="Ok"){
			obj_lbl.style.color="#44FF88"
		}else{
			obj_lbl.style.color="#FF5588"
		}
	}
return validpass;
}

function js_pwd_toggle(){
	if(document.frm.pwd.readOnly==false){
		document.frm.pwd.readOnly=true
		document.frm.cpwd.readOnly=true	
		document.frm.pwd.id=""
		document.frm.cpwd.id=""
	}else{
		document.frm.pwd.readOnly=false
		document.frm.cpwd.readOnly=false
		document.frm.pwd.id="150"
		document.frm.cpwd.id="160"		
	}
}

function delete_conform(){
	if(confirm("Are you sure you want to delete this seller account?")){
	}else{
		return false;
	}
}
</script>

<?php
    	
	$act = "";
	$sup_id = 0;
	$sup_shop_act_license="";
	$sup_bk_can_chk="";
	$sup_logo="";
	$disable_upload = "";
	if(func_var($admin_login)<>"1"){
		$disable_upload = "disabled"; 
		$readonly = "readonly";
	}
	if(isset($_POST["act"])) $act=$_POST["act"];
	if(isset($_SESSION["sup_id"])) $sup_id=$_SESSION["sup_id"];	
			
	if(isset($_FILES['files'])){
		$upload_doc_arr = array();
		for($i=0; $i<9; $i++){	//Changed for GST
			$file_name = $_FILES['files']['name'][$i];
			if($file_name <> ""){
				$file_tmp =$_FILES['files']['tmp_name'][$i];
				$desired_dir=$_SERVER['DOCUMENT_ROOT']."/seller/user_data/id-".$sup_id;
				
				if(is_dir($desired_dir)==false){
					mkdir("$desired_dir", 0700);		// Create directory if it does not exist
				}
				
				$file_path="";
				$file_path=$_SERVER['DOCUMENT_ROOT']."/seller/user_data/id-".$sup_id."/".$file_name;
				move_uploaded_file($file_tmp,$file_path);
				$imageFileType = strtolower(pathinfo($file_path,PATHINFO_EXTENSION));
				$upload_doc_arr[$i]=img_resize_db($file_path, 1200, 980, $imageFileType);
			}else{
				$upload_doc_arr[$i]="";
			}
		}
		$sup_logo = $upload_doc_arr[0];
		$sup_shop_act_license = $upload_doc_arr[1];
		$sup_pan_doc = $upload_doc_arr[2];
		$sup_vat_doc = $upload_doc_arr[3]; //GST
		//$sup_cst_doc = $upload_doc_arr[4];
		
		$sup_bk_can_chk = $upload_doc_arr[5];
		$sup_imp_exp_license = $upload_doc_arr[6];
		$sup_dlr_doc = $upload_doc_arr[7];
		$sup_iso_doc = $upload_doc_arr[8];
	}
	
	if($act=="1"){
		
		//$sup_id = $_POST["sup_id"];
		$sup_company = $_POST["sup_company"];
		$sup_contact_name = $_POST["sup_contact_name"]; 	
		$sup_business_type = $_POST["sup_business_type"];
		$sup_ext_address = $_POST["sup_address"];
		$sup_ext_state = $_POST["state_name"]; 
		$sup_ext_pincode = $_POST["sup_pincode"];
		$sup_email = $_POST["sup_email"];
		$sup_ext_address_type = "Billing address";	
		$sup_contact_no = $_POST["sup_contact_no"];
		$sup_contact_per_name = $_POST["sup_contact_per_name"];
		$sup_alt_contact_no = $_POST["sup_alt_contact_no"];
		$sup_ext_city = $_POST["sup_city"];
		$sup_pan =	 	$_POST["sup_pan"];
		$sup_vat = 	$_POST["sup_vat"];	//GST number
		//$sup_cstn = 	$_POST["sup_cstn"];
		$sup_bk_acc = 	$_POST["sup_bk_acc"];
		$sup_bk_ifsc = 	$_POST["sup_bk_ifsc"];
		$sup_bk_name = 	$_POST["sup_bk_name"];
		$sup_bk_brname = 	$_POST["sup_bk_brname"];
		$sup_pwd= $_POST["pwd"];
		
		$fld_arr = array();
		//$fld_arr["sup_id"] = $sup_id;
		$fld_arr["sup_company"] = $sup_company;
		$fld_arr["sup_business_type"] = $sup_business_type;
		$fld_arr["sup_contact_name"] = $sup_contact_name;
		
		$fld_arr["sup_email"] = $sup_email;
		$fld_arr["sup_city"] = $sup_ext_city;
		$fld_arr["sup_contact_no"] = $sup_contact_no;
		$fld_arr["sup_alt_contact_no"] = $sup_alt_contact_no;
		$fld_arr["sup_contact_per_name"] = $sup_contact_per_name;
		$fld_arr["sup_pan"] = $sup_pan;
		$fld_arr["sup_vat"] = $sup_vat;	//GST number
		//$fld_arr["sup_cstn"] = $sup_cstn;
		$fld_arr["sup_bk_acc"] = $sup_bk_acc;
		$fld_arr["sup_bk_ifsc"] = $sup_bk_ifsc;
		$fld_arr["sup_bk_name"] = $sup_bk_name;
		$fld_arr["sup_bk_brname"] = $sup_bk_brname;
		if($sup_pwd<>""){
		$fld_arr["sup_pwd"] = $sup_pwd;
		}
		if (isset($_POST["sup_lmd"])) {
			$fld_arr["sup_lmd"] = 1;
		}else{
			$fld_arr["sup_lmd"] = 0;
		}
		$_SESSION["sup_lmd"] = $fld_arr["sup_lmd"];
		if($sup_logo <> "") $fld_arr["sup_logo"] = $sup_logo;
		if($sup_bk_can_chk <> "") $fld_arr["sup_bk_can_chk"] = $sup_bk_can_chk;
		if($sup_pan_doc <> "") $fld_arr["sup_pan_doc"] = $sup_pan_doc;
		if($sup_vat_doc <> "") $fld_arr["sup_vat_doc"] = $sup_vat_doc;	//GST certificate
		//if($sup_cst_doc <> "") $fld_arr["sup_cst_doc"] = $sup_cst_doc;
		if($sup_shop_act_license <> "") $fld_arr["sup_shop_act_license"] = $sup_shop_act_license;
		if($sup_imp_exp_license <> "") $fld_arr["sup_imp_exp_license"] = $sup_imp_exp_license;
		if($sup_dlr_doc <> "") $fld_arr["sup_dlr_doc"] = $sup_dlr_doc;
		if($sup_iso_doc <> "") $fld_arr["sup_iso_doc"] = $sup_iso_doc;

		$fld_addr_arr = array();
		$fld_addr_arr["sup_id"] = $sup_id;
	
		$fld_addr_arr["sup_ext_name"] = $sup_company;
		
		$fld_addr_arr["sup_ext_address"] = $sup_ext_address;
		$fld_addr_arr["sup_ext_state"] = $sup_ext_state;
		
		$fld_addr_arr["sup_ext_city"] = $sup_ext_city;
		
		$fld_addr_arr["sup_ext_address_type"] = $sup_ext_address_type;
		$fld_addr_arr["sup_ext_pincode"] = $sup_ext_pincode;
		$fld_addr_arr["sup_ext_contact_no"] = $sup_contact_no;
		
			//Query to get the values from seller address table
		$rst = mysqli_query($con,"select * from sup_ext_addr where sup_id=$sup_id LIMIT 1");
		$addr_row="";
		$addr_row = mysqli_fetch_assoc($rst);
		if($addr_row <> ""){
			$addr_qry = func_update_qry("sup_ext_addr",$fld_addr_arr," where addr_id=".$addr_row["addr_id"]);
		}else{
			$addr_qry = func_insert_qry("sup_ext_addr",$fld_addr_arr);
		}

		//$addr_qry = func_insert_qry("sup_ext_addr",$fld_addr_arr);
		$addr_result=mysqli_query($con, $addr_qry);
		
		
		$qry = func_update_qry("sup_mast",$fld_arr," where sup_id=".$sup_id);
		$result = mysqli_query($con,$qry);
		
		//mail is send to support
		$from = "noreply@Company-Name.com";		
		$body="Hi,<br>";
		$body.="The seller has updated the profile.<br>";
		$body.="Seller ID: $sup_id <br>";
		$body.="Seller Name: $sup_company";
		$body.="<br><br>Regards,<br>Team Company-Name";		
		xsend_mail($support,"Company-Name - Seller Updated Profile",$body,$from );
		
		if($result){
				$msg = "The details has been updated successfully";
		}else{
			$msg = "Update failed! Please check details or try again after some time.";
		}	
		
		//If password is change then mail send to seller with new password.
		if($sup_pwd<>""){
			$from = "support@Company-Name.com";		
			$body="Dear $sup_company,<br><br>";
			$body.="Your password has been changed successfully. If you did not take this action please contact Company-Name support.<br>";
			$body.="New Password :  $sup_pwd<br>";
			$body.="<br><br>Thanks,<br>Team Company-Name";		
			xsend_mail($sup_email,"Company-Name - Password Changed",$body,$from );
		
			if($result){
				$msg = "The details has been updated successfully";
			}else{
				$msg = "Update failed! Please check details or try again after some time.";
			}
		}
	
	}
if(func_var($admin_login)=="1")	{
	if(isset($_POST["btn_resend"])){
		get_rst("select sup_active_status,sup_activation,sup_email,sup_company,sup_pwd from sup_mast where sup_id=0$sup_id",$row_send);
		if($row_send["sup_active_status"] <> 1){
			$fld_arr_resend = array();
			$fld_arr_resend["sup_contact_name"] = $row_send["sup_company"];
			$fld_arr_resend["sup_email"] = $row_send["sup_email"];
			$fld_arr_resend["sup_pwd"] = $row_send["sup_pwd"];
			$fld_arr_resend["act_link"] = $base_url.$row_send["sup_activation"];
			$body = push_body("seller_activation.txt",$fld_arr_resend);
			$from = "welcome@Company-Name.com";
		
			if(xsend_mail($row_send["sup_email"],"Company-Name-seller email verification",$body,$from)){
				$msg = "Activation link has been sent successfully";
			}else{
				$msg = "There is some problem to send email to your address";
			}
		}
	}
}
?>
<div id="sup_upd_form">

    <?php
	if($msg <> "") { ?>
	<div class="alert alert-info">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
   		<?=$msg?>
	</div>
    <?php } ?>
	
<form name="frm" method="post" enctype="multipart/form-data" >
<input type="hidden" name="act" value="1" >

<div class="solidTab panel panel-info">
<div class="panel-heading">Seller Details</div>
	<table width="100%" border="0" cellspacing="1" cellpadding="5" >
		<?php if(true){ 
		$qry = "select * from sup_mast where sup_id=$sup_id";
		
		get_rst($qry, $row);
		?>
		<tr>
			<td>Establishment Name</td>
			<td><input type="textbox" title="Establishment Name" name="sup_company" value="<?php echo $row["sup_company"]; ?>" maxlength="100" id="100" size="25" class="form-control" style="width:auto;"/> </td>
			<td>Type of Business</td>
			
			<td><select name="sup_business_type" title="Type of Business" class="form-control" style="width:auto">
				<?php if($row["sup_business_type"] == ""){ ?>
				<option value="">Please Select</option>
				<?php }else{ ?>
				<option value="<?php echo $row["sup_business_type"]; ?>"><?php echo $row["sup_business_type"]; ?></option>
				<?php } ?>
				<option value="Reseller">Reseller</option>
				<option value="Dealer">Dealer</option>
				<option value="Distributor">Distributor</option>
				<option value="Manufacturer">Manufacturer</option>
			</select></td>
		</tr>
		<tr>
			<td>Owner Full Name</td>
			<td><input type="textbox" title="Owner Name" name="sup_contact_name" value="<?php echo $row["sup_contact_name"]; ?>" maxlength="100" id="100" size="25" class="form-control" style="width:auto;" /> </td>
			<td>Authorised/Contact Person Full Name :
			</div></td>
			<td align="left" xclass="table-bg"><input name="sup_contact_per_name" type="text" class="form-control" title="Contact Person Name" style="width:auto;" tabindex="5" size="25" maxlength="50"  value="<?php echo $row["sup_contact_per_name"]; ?>"/>
			</td>
		</tr>
		
		<tr>
			<td>Upload Logo</h3></td>
			<?php if($row["sup_logo"] <> ""){ ?>
			<td><input name="files[]" type="file" size="40" onchange="validate_fileupload(this);" style="width: 250px; display: inline-block;" class="transparent" accept=".png, .jpg, .jpeg, .gif, .tif, .tiff, .bmp"/>
			<img src="../images/checkmark_green.gif" title="File Uploaded" style="display: inline-block;"></td>		
			<?php } else { ?>
			<td><input name="files[]" onchange="validate_fileupload(this);" type="file" size="40" style="width: 250px;" accept=".png, .jpg, .jpeg, .gif, .tif, .tiff, .bmp"/></td>
			<?php } ?>
			<td>Email</td>
			<td><input type="textbox" title="Please contact support@Company-Name.com to change Email" <?=$status?> name="sup_email" value="<?php echo $row["sup_email"]; ?>" maxlength="100" id="100" size="25" class="form-control" style="width:auto;" /> </td>
		</tr>
			
		<tr>
			<td>Password</td>
			<td><input class="form-control" name="pwd" type="password" id="" readonly title="Your password must contain a minimum of 8 characters and a maximum of 54 characters. It must contain at-least 1 number, 1 capital letter and 1 lower-case letter.	" style="width:auto;" tabindex="3" size="25" maxlength="54"  onblur="javascript: check_pwd(this);" value="<?=func_var($pwd)?>"/> 
			<a href="#" class="pwd-chng" onclick="js_pwd_toggle();">Change</a>
			<span id="msg_pwd"></span>
			</td>
			<td>Confirm Password
			</td>
			<td align="left" xclass="table-bg"><input class="form-control" name="cpwd" type="password" id="" readonly title="Confirm Password" tabindex="4" size="25" maxlength="54" style="width:auto;"/>
			</td>
		</tr>
		<?php } ?>
	</table>
</div>

<div class="solidTab panel panel-info">
<div class="panel-heading">Licensing Details</div>
	<table width="100%" border="0" cellspacing="1" cellpadding="5">
		
		<tr>
			<td>Shop Act License</h3></td>
			<?php if($row["sup_admin_approval"] == 1){
			if($row["sup_shop_act_license"] <> ""){ ?>
			<td><input name="files[]" type="file" size="40" style="width: 250px;" onchange="validate_fileupload(this);" class="transparent" accept=".png, .jpg, .jpeg, .gif,.tif, .tiff, .bmp" <?=$disable_upload?> /></td>
			<td><img src="../images/checkmark_green.gif" title="File Uploaded"></td>
			<?php } 
			}else{
			if($row["sup_shop_act_license"] <> ""){ ?>
			<td><input name="files[]" type="file" size="40" style="width: 250px;" onchange="validate_fileupload(this);" class="transparent" accept=".png, .jpg, .jpeg, .gif,.tif, .tiff, .bmp" /></td>
			<td><img src="../images/checkmark_green.gif" title="File Uploaded"></td>
			<?php } else { ?>
			<td><input name="files[]" onchange="validate_fileupload(this);" type="file" size="40" style="width: 250px;" accept=".png, .jpg, .jpeg, .gif, .tif, .tiff, .bmp"/></td>
			<?php }
			} ?>
		</tr>	
		
		<tr>
			<td>Companies or Firm Income Tax number (PAN)</td>
			<?php if($row["sup_admin_approval"] == "1"){ ?>
			<td><input type="textbox" title="Companies or Firm Income Tax number (PAN)" name="sup_pan" value="<?php echo $row["sup_pan"]; ?>" maxlength="10" id="100" size="30" class="form-control" style="width:auto;" <?=$readonly?>/> </td>
			<?php if($row["sup_pan_doc"] <> ""){ ?>
			<td><input name="files[]" type="file" onchange="validate_fileupload(this);" size="40" style="width: 250px;" class="transparent" accept=".png, .jpg, .jpeg, .gif, .tif, .tiff, .bmp" <?=$disable_upload?>/></td>
			<td><img src="../images/checkmark_green.gif" title="File Uploaded"></td>
			<?php } 
			}else{?>
			<td><input type="textbox" title="Companies or Firm Income Tax number (PAN)" name="sup_pan" value="<?php echo $row["sup_pan"]; ?>" maxlength="10" id="100" size="30" class="form-control" style="width:auto;" /> </td>
			<?php if($row["sup_pan_doc"] <> ""){ ?>
			<td><input name="files[]" type="file" onchange="validate_fileupload(this);" size="40" style="width: 250px;" class="transparent" accept=".png, .jpg, .jpeg, .gif, .tif, .tiff, .bmp" /></td>
			<td><img src="../images/checkmark_green.gif" title="File Uploaded"></td>
			<?php } else { ?>
			<td><input name="files[]" onchange="validate_fileupload(this);" type="file" size="40" style="width: 250px;" accept=".png, .jpg, .jpeg, .gif, .tif, .tiff, .bmp"/></td>
			<?php }
			} ?>
			
		</tr>
		<tr>
			<td>Goods & Service Tax number (GST)</td>
			<?php// if($row["sup_admin_approval"] == "1"){ ?>
			<td><input type="textbox" title="Goods & Service Tax number (GST)" name="sup_vat" value="<?php echo $row["sup_vat"]; ?>" maxlength="15" id="100" size="30" class="form-control" style="width:auto;"/> </td>
			<?php if($row["sup_vat_doc"] <> ""){ ?>
			<td><input name="files[3]" type="file" onchange="validate_fileupload(this);" size="40" style="width: 250px;" class="transparent" accept=".png, .jpg, .jpeg, .gif, .tif, .tiff, .bmp" /></td>
			<td><img src="../images/checkmark_green.gif" title="File Uploaded"></td>
			<?php }else { ?>
			<td><input name="files[]" onchange="validate_fileupload(this);" type="file" size="40" style="width: 250px;" accept=".png, .jpg, .jpeg, .gif, .tif, .tiff, .bmp" /></td>
			<?php }
			/*}  else{ ?>
			<td><input type="textbox" title="Goods & Service Tax number (GST)" name="sup_vat" value="<?php echo $row["sup_vat"]; ?>" maxlength="15" id="100" size="30" class="form-control" style="width:auto;" /> </td>
			<?php if($row["sup_vat_doc"] <> ""){ ?>
			<td><input name="files[]" type="file" onchange="validate_fileupload(this);" size="40" style="width: 250px;" class="transparent" accept=".png, .jpg, .jpeg, .gif, .tif, .tiff, .bmp"/></td>
			<td><img src="../images/checkmark_green.gif" title="File Uploaded"></td>
			<?php } else { ?>
			<td><input name="files[]" type="file" onchange="validate_fileupload(this);" size="40" style="width: 250px;" accept=".png, .jpg, .jpeg, .gif, .tif, .tiff, .bmp"/></td>
			<?php } 
			}*/ ?>		
		</tr>
		<tr style="display:none">
			<td>Central Sales Tax Account number (CST)</td>
			<?php if($row["sup_admin_approval"] == "1"){ ?>
			<td><input type="textbox" title="Central Sales Tax Account number (CST)" name="sup_cstn" value="<?php echo $row["sup_cstn"]; ?>" maxlength="15"  size="30" class="form-control" style="width:auto;" <?=$readonly?>/> </td>	
			<?php if($row["sup_cst_doc"] <> ""){ ?>
			<td><input name="files[]" type="file" onchange="validate_fileupload(this);" size="40" style="width: 250px;" class="transparent" accept=".png, .jpg, .jpeg, .gif, .tif, .tiff, .bmp" <?=$disable_upload?>/></td>
			<td><img src="../images/checkmark_green.gif" title="File Uploaded"></td>
			<?php }
			}else{ ?>	
			<td><input type="textbox" title="Central Sales Tax Account number (CST)" name="sup_cstn" value="<?php echo $row["sup_cstn"]; ?>" maxlength="15" size="30" class="form-control" style="width:auto;" /> </td>	
			<?php if($row["sup_cst_doc"] <> ""){ ?>
			<td><input name="files[]" type="file" onchange="validate_fileupload(this);" size="40" style="width: 250px;" class="transparent" accept=".png, .jpg, .jpeg, .gif, .tif, .tiff, .bmp" /></td>
			<td><img src="../images/checkmark_green.gif" title="File Uploaded"></td>
			<?php } else { ?>
			<td><input name="files[]" onchange="validate_fileupload(this);" type="file" size="40" style="width: 250px;" accept=".png, .jpg, .jpeg, .gif, .tif, .tiff, .bmp" /></td>
			<?php }
			} ?>
		</tr>
	</table>
</div>
	
<div class="solidTab panel panel-info">
<div class="panel-heading">Bank Details</div>
	<table width="100%" border="0" cellspacing="1" cellpadding="5">
		
		<tr>
			<td>Account Number</td>
			<td><input type="textbox" title="Bank account number" name="sup_bk_acc" value="<?php echo $row["sup_bk_acc"]; ?>" maxlength="100" id="100" size="25" class="form-control" style="width:auto;" /> </td>
			<td>IFSC Code</td>
			<td><input type="textbox" title="IFSC Code" name="sup_bk_ifsc" value="<?php echo $row["sup_bk_ifsc"]; ?>" maxlength="11" id="100" size="25" class="form-control" style="width:auto;" /> </td>
		</tr>
		<tr>
			<td>Bank Name</td>
			<td><input type="textbox" title="Bank Name" name="sup_bk_name" value="<?php echo $row["sup_bk_name"]; ?>" maxlength="100" id="100" size="25" class="form-control" style="width:auto;" /> </td>
			<td>Branch Name</td>
			<td><input type="textbox" title="Branch Name" name="sup_bk_brname" value="<?php echo $row["sup_bk_brname"]; ?>" maxlength="100" id="100" size="25" class="form-control" style="width:auto;" /> </td>	
		</tr>
		
		<tr>
			<td>Cancelled Cheque</h3></td>
			<?php if($row["sup_bk_can_chk"] <> ""){ ?>
			<td><input name="files[]" type="file" onchange="validate_fileupload(this);" size="40" style="width: 250px;" class="transparent" accept=".png, .jpg, .jpeg, .gif, .tif, .tiff, .bmp" <?=$disable_upload?>/></td>		
			<td><img src="../images/checkmark_green.gif" title="File Uploaded"></td>
			<?php } else { ?>
			<td><input name="files[]" type="file"  onchange="validate_fileupload(this);" size="40" style="width: 250px;" accept=".png, .jpg, .jpeg, .gif, .tif, .tiff, .bmp"/></td>
			<?php } ?>
		</tr>
	</table>
</div>

<div class="solidTab panel panel-info">
<div class="panel-heading">Billing Details</div>
<?php
		$rst = mysqli_query($con,"select * from sup_ext_addr where sup_id=$sup_id LIMIT 1");
		$row2 = mysqli_fetch_assoc($rst);
?>
	<table width="100%" border="0" cellspacing="1" cellpadding="5">
		
		<tr>
			<td>Address</td>
			<td><textarea title="Address" rows=4 cols=25 name="sup_address" id="101500" class="form-control" style="width:auto;"><?php echo $row2["sup_ext_address"]; ?></textarea></td>		
			
			<td>State</td>
						
			<td> <select id="100" title="State" name="state_name" class="form-control" style="width:auto">
				<?php if($row2["sup_ext_state"] == ""){ ?>
					<option value="">Select</option>
				<?php }else{ ?>
					<option value="<?php echo $row2["sup_ext_state"]; ?>"><?php echo $row2["sup_ext_state"]; ?></option>
				<?php } ?>
				<?=create_cbo("select state_name,state_name from state_mast",func_var($state_name))?>
			</select>	</td>
		</tr>
		<tr>
			<td>City</td>
			<td><input type="textbox" title="City" name="sup_city" value="<?php echo $row2["sup_ext_city"]; ?>" maxlength="100" id="100" size="25" class="form-control" style="width:auto;" /> </td>	
			<td>Pincode</td>
			<td><input type="textbox" onkeypress='validate_key(event)'  title="Pincode" name="sup_pincode" value="<?php echo $row2["sup_ext_pincode"]; ?>" maxlength="6" id="100" size="25" class="form-control" style="width:auto;" /> </td>
		</tr>
		
		<tr>
			<td>Mobile</td>	
			<td><input type="textbox" title="Please contact support@Company-Name.com to change Mobile number" onkeypress='validate_key(event)' <?=$status?> title="Mobile Number" name="sup_contact_no" value="<?php echo $row["sup_contact_no"]; ?>" maxlength="10" id="100" size="25" class="form-control" style="width:auto;" /> </td>
			
			<td>Alternate Contact Number</td>	
			<td><input type="textbox" onkeypress='validate_key(event)' title="Alternate Contact Number" name="sup_alt_contact_no" value="<?php echo $row["sup_alt_contact_no"]; ?>" maxlength="11" id="100" size="25" class="form-control" style="width:auto;" /> </td>
			
		</tr>
	</table>
</div>

<div class="solidTab panel panel-info">
<div class="panel-heading">Logistics Details</div>
	<table width="100%" border="0" cellspacing="1" cellpadding="5">
		<tr><td></td></tr>
		<tr >
			<td width="41%">Last Mile Delivery <a href="#" data-toggle="popover" data-trigger="hover" data-html="true" data-content="<ul><li>Last mile delivery is a service provided by Company-Name to take care of the shipping of your items sold on Company-Name.com.</li> <li>Shipping charges applicable will be added to the order amount and it will be billed to the buyer.</li><li> Do not opt for this service if you wish to use your own logistics.</li> <li> The shipping charges applicable in this case will have to be included in the product price itself to take care of shipping.</li> <li> Shipping charges will not be shown to the buyer.</li></ul>"><span class="glyphicon glyphicon-question-sign help"></span></a></td>
			<td align="left"><input type="checkbox" title="Last Mile Delivery" name="sup_lmd" <?php echo ($row["sup_lmd"]==1 ? 'checked' : '');?>/> </td>
		</tr>		
		<tr><td></td></tr>
	</table>
</div>

<div class="solidTab panel panel-info">
<div class="panel-heading">Additional Documents (Optional)</div>
	<table width="100%" border="0" cellspacing="1" cellpadding="5">
		
		<tr>
			<td>Import/Export License</h3></td>
			<?php if($row["sup_imp_exp_license"] <> ""){ ?>
			<td><input name="files[]" type="file" size="40" onchange="validate_fileupload(this);" style="width: 250px;" class="transparent" accept=".png, .jpg, .jpeg, .gif, .tif, .tiff, .bmp" <?=$disable_upload?>/></td>
			<td><img src="../images/checkmark_green.gif" title="File Uploaded"></td>
			<?php } else { ?>
			<td><input name="files[]" type="file" onchange="validate_fileupload(this);" size="40" style="width: 250px;" accept=".png, .jpg, .jpeg, .gif, .tif, .tiff, .bmp"/></td>
			<?php } ?>
		</tr>	
		
		<tr>
			<td>Dealer/Distributor Certificate</td>
			<?php if($row["sup_dlr_doc"] <> ""){ ?>
			<td><input name="files[]" type="file" size="40" onchange="validate_fileupload(this);" style="width: 250px;" class="transparent" accept=".png, .jpg, .jpeg, .gif, .tif, .tiff, .bmp" <?=$disable_upload?>/></td>
			<td><img src="../images/checkmark_green.gif" title="File Uploaded"></td>
			<?php } else { ?>
			<td><input name="files[]" type="file" onchange="validate_fileupload(this);" size="40" style="width: 250px;" accept=".png, .jpg, .jpeg, .gif, .tif, .tiff, .bmp"/></td>
			<?php } ?>
		</tr>
		
		<tr>
			<td>ISO Certificate</td>
			<?php if($row["sup_iso_doc"] <> ""){ ?>
			<td><input name="files[]" type="file" onchange="validate_fileupload(this);" size="40" style="width: 250px;" class="transparent" accept=".png, .jpg, .jpeg, .gif, .tif, .tiff, .bmp" <?=$disable_upload?>/></td>
			<td><img src="../images/checkmark_green.gif" title="File Uploaded"></td>
			<?php } else { ?>
			<td><input name="files[]" type="file" onchange="validate_fileupload(this);" size="40" style="width: 250px;" accept=".png, .jpg, .jpeg, .gif, .tif, .tiff, .bmp"/></td>
			<?php } ?>
		</tr>
			
	</table>
</div>
	<br><center> <input type="button" class="btn btn-warning" onClick="javascript:return Submit_form();" value="Update Details" id=button1 name=button1>
	</center><br>
	</form>
	<form name="delete" method="post">
		<center><?php if(func_var($admin_login)=="1"){?>&nbsp; &nbsp; &nbsp; &nbsp;<input type="hidden" name="act" value="B" > 

		<input type="submit" class="btn btn-warning"  value="Delete Seller" onClick="javascript: return delete_conform();" id=button2 name=button2 style="margin-right:10px;">
		<?php if($row["sup_active_status"] <> 1){?>
			<input type="submit" class="btn btn-warning"  value="Resend activation link" name = "btn_resend">
		<?php }
		}?>
	</center>
	</form>
</div>