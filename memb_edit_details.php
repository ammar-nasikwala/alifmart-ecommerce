<?php

require("inc_init.php");

$incomp_msg = "";
$s_flag = "";

if(func_read_qs("hdnsubmit") == "y"){	
	
	$email = func_read_qs("email");
	$pwd = func_read_qs("pwd");
	$cpwd = func_read_qs("cpwd");
	$title = func_read_qs("title");
	$fname = func_read_qs("fname");
	$memb_contact_per_name = func_read_qs("memb_contact_per_name");
	$add1 = func_read_qs("add1");
	$add2 = func_read_qs("add2");
	$city = func_read_qs("city");
	$country = func_read_qs("country");
	$pcode = func_read_qs("pcode");
	$memb_state = func_read_qs("memb_state");
	$tele = func_read_qs("tele");
	//$mob = func_read_qs("mob");
	
	$rule = "";

	$rule = $rule."fname|c|First Name^"	;
	$rule = $rule."add1|c|Address1^";
	$rule = $rule."city|c|Town / City^";
	$rule = $rule."pcode|c|Post Code^";
	$rule = $rule."memb_state|c|State^";
	$rule = $rule."country|c|Country^";
	$rule = $rule."tele|c|Telephone No.^";

    $incomp_msg = validate($rule);

	if($incomp_msg == ""){
		$memb_id = $_SESSION["memb_id"];

		$fld_arr = array();
		$fld_arr["memb_fname"] = $fname;
		$fld_arr["memb_contact_per_name"] = $memb_contact_per_name;
		$fld_arr["memb_title"] = $title;
		$fld_arr["memb_add1"] = $add1;
		$fld_arr["memb_add2"] = $add2;	
		$fld_arr["memb_city"] = $city;
		$fld_arr["memb_state"] = $memb_state;
		$fld_arr["memb_country"] = $country;
		$fld_arr["memb_postcode"] = $pcode;
		$fld_arr["memb_tel"] = $tele;
		$fld_arr["memb_newsletter"] = 0;

		$res = mysqli_query($con,"select * from memb_ext_addr where memb_id = $memb_id");
		if(mysqli_num_rows($res)==0){
		$fld_addr_arr = array();
		$fld_addr_arr["memb_id"] = $memb_id;
		$fld_addr_arr["ext_addr_default"] = 1;
		$fld_addr_arr["ext_addr_name"] = "Primary address";
		$fld_addr_arr["ext_addr1"] = $add1;
		$fld_addr_arr["ext_addr2"] = $add2;
		$fld_addr_arr["ext_addr_state"] = $memb_state;
		$fld_addr_arr["ext_addr_city"] = $city;
		$fld_addr_arr["ext_addr_pin"] = $pcode;
		$fld_addr_arr["ext_addr_contact"] = $tele;
		$addr_qry = func_insert_qry("memb_ext_addr",$fld_addr_arr);
		}else{
			$fld_addr_arrr["ext_addr_name"] = "Primary address";
			$fld_addr_arrr["ext_addr1"] = $add1;
			$fld_addr_arrr["ext_addr2"] = $add2;
			$fld_addr_arrr["ext_addr_state"] = $memb_state;
			$fld_addr_arrr["ext_addr_city"] = $city;
			$fld_addr_arrr["ext_addr_pin"] = $pcode;
			$fld_addr_arrr["ext_addr_contact"] = $tele;
			$addr_qry = func_update_qry("memb_ext_addr",$fld_addr_arrr, " where memb_id=".$_SESSION["memb_id"]." and ext_addr_default=1");
		}
		mysqli_query($con, $addr_qry);		
		if($pwd<>""){
			$fld_arr["memb_pwd"] = $pwd;
		}
		if(isset($_POST['newsletter'])){
			$fld_arr["memb_newsletter"] = 1;
		}
		
		if(isset($_POST['Ind-buyer'])){
			$memb_cstn=func_read_qs("memb_cstn");
			$memb_vat=func_read_qs("memb_vat");
			$memb_company=func_read_qs("memb_company");
			$memb_vat_doc = "";
			//$memb_cst_doc = "";
			if(isset($_FILES['files'])){	
				$errors= array();
				$maxsize    = 1048576;
				$acceptable = array(
				'application/pdf',
				'image/jpeg',
				'image/jpg',
				'image/gif',
				'image/png'
				);
	
				$file_name = $_FILES['files']['name'];
				if($file_name <> ""){
					$file_tmp =$_FILES['files']['tmp_name'];
					$desired_dir=$_SERVER['DOCUMENT_ROOT']."/extras/user_data/id-".$memb_id;
					
					if(!in_array($_FILES['files']['type'], $acceptable) && (!empty($_FILES["files"]["type"]))) {
						$errors[] = 'Invalid file type. Only PDF, JPG, GIF and PNG types are accepted.';
					}
					if((filesize($file_tmp) >= $maxsize) || (filesize($file_tmp) == 0)) {
						$errors[] = 'File is too large. File size must be less than 1 megabyte.';
					}
					
					if(empty($errors)==true){
						if(is_dir($desired_dir)==false){
							mkdir("$desired_dir", 0700);		// Create directory if it does not exist
						}
						$file_path="";
						$file_path=$_SERVER['DOCUMENT_ROOT']."/extras/user_data/id-".$memb_id."/".$file_name;
						move_uploaded_file($file_tmp,$file_path);
						$imageFileType = strtolower(pathinfo($file_path,PATHINFO_EXTENSION));
						$memb_vat_doc=img_resize_db($file_path, 1200, 980, $imageFileType);
					}else{
							$act="0";
							$msg= "Upload Failed!! ".$errors[0] ;
							goto END;
					}
				}else{
					$memb_vat_doc="";
				}
			}
			//if($memb_cst_doc <> "") $fld_arr["memb_cst_doc"] = $memb_cst_doc;
			if($memb_vat_doc <> "") $fld_arr["memb_vat_doc"] = $memb_vat_doc; //GST doc
			//$fld_arr["memb_cstn"] = $memb_cstn;
			$fld_arr["memb_vat"] = $memb_vat;	//GST
			$fld_arr["memb_company"] = $memb_company;
			$fld_arr["ind_buyer"] = 1;
		}else{
			$fld_arr["ind_buyer"] = 0;
		}
		
		$sqlIns = func_update_qry("member_mast",$fld_arr," where memb_id=".$_SESSION["memb_id"]);
		if (!mysqli_query($con,$sqlIns)) {
			if (mysqli_errno($con) == 1062) {
				$incomp_msg = "The updated email is already in use by another person. Please provide another one or keep the existing one.";
			}else{
				$incomp_msg = mysqli_error($con);
			}
		}else{
			//$_SESSION["memb_id"] = $memb_id;
			
			$mail_body = push_body("buyer_update_details.txt",$fld_arr);
			
			if(send_mail($email,"Company-Name - Details Updated",$mail_body )){
				$incomp_msg="Your details have been updated successfully.";
				$s_flag = "1";				
			}else{
				$incomp_msg="There was a problem sending mail to your email address.";
			}
			
			$fname = "";
			$sname = "";
			$add1 = "";
			$add2 = "";
			$city = "";
			$pcode = "";
			$memb_state = "";
			$tele = "";
			$email = "";
			$fax = "";	
			$pwd = "";
			$cpwd = "";
		}
	}
}elseif(func_read_qs("hdnsubmit") == "c"){
	$fname = "";
	$sname = "";
	$add1 = "";
	$add2 = "";
	$city = "";
	$pcode = "";
	$memb_state = "";
	$tele = "";
	$email = "";
	$fax = "";	
	$pwd = "";
	$cpwd = "";
}
	if(get_rst("select * from member_mast where memb_id=".$_SESSION["memb_id"],$row)){
		
		$fname = stripcslashes($row["memb_fname"]);
		$memb_contact_per_name = stripcslashes($row["memb_contact_per_name"]);
		$title = $row["memb_title"];
		$email = $row["memb_email"];
		$add1 = $row["memb_add1"];
		$add2 = $row["memb_add2"];
		$city = $row["memb_city"];
		$memb_state = $row["memb_state"];
		$country = $row["memb_country"];
		$pcode = $row["memb_postcode"];
		$tele = $row["memb_tel"];
		$memb_cst_doc = $row["memb_cst_doc"];
		$memb_vat_doc = $row["memb_vat_doc"];
		$memb_cstn = $row["memb_cstn"];
		$memb_vat = $row["memb_vat"];
		$memb_company = stripcslashes($row["memb_company"]);
		$ind_buyer = $row["ind_buyer"];
		$newsletter = $row["memb_newsletter"];
		$MrSelected = "";
		$MrsSelected = "";
		$MsSelected = "";
		$DrSelected = "";
		//Making Title selected based
		switch($title) {
			case "Mr": $MrSelected = "selected"; 
						break;
			case "Mrs": $MrsSelected = "selected"; 
						break;
			case "Ms": $MsSelected = "selected"; 
						break;
			case "Dr": $DrSelected = "selected"; 
						break;					
		}
	}
	
	if(get_rst("select * from memb_ext_addr where memb_id=".$_SESSION["memb_id"]." and ext_addr_default=1",$row_add)){
		$add1 = $row_add["ext_addr1"];
		$add2 = $row_add["ext_addr2"];
		$memb_state = $row_add["ext_addr_state"];
		$city = $row_add["ext_addr_city"];
		$pcode = $row_add["ext_addr_pin"];
		$tele = $row_add["ext_addr_contact"];
	}	
END:	
?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="DESCRIPTION" content="Company-Name.com Buyer's profile edit page, profile display"/>
<meta name="KEYWORDS" content="Buyer edit details"/>
<title>Company-Name - Buyer Edit Details</title>
<link href="styles/styles.css" rel="stylesheet" type="text/css" />
<script src="scripts/scripts.js" type="text/javascript"></script>
<script type="text/javascript">

	function clearForm()
	{		
		document.frm.pwd.value="";		
		document.frm.cpwd.value="";	
		document.frm.fname.value="";	
		document.frm.sname.value="";	
		document.frm.city.value="";		
		document.frm.add1.value="";		
		document.frm.add2.value="";		
		document.frm.city.value="";		
		document.frm.pcode.value="";		
		document.frm.tele.value="";		
		//document.frm.mob.value="";		
	}
	
	var sleep_count;
function validate(){
	//check_email(document.frm.email);
	
	if(chkForm(document.frm)==false){
		return false;
	}else{
		
		if(document.getElementById("chk").checked){
			if(document.getElementById("es_name").value==""||document.getElementById("vat_n").value==""){
				alert("Please Enter All The Details");
				return false;
			}else{
				display_gif();
				document.frm.submit();
				return true;
			}	
		}
		display_gif();
		document.frm.submit();
	}
}

function js_sms_response(v_code){
	//alert(v_code);
	//sms_status = v_code;
		
	if(v_code == "200" || v_code == "201"){
		document.frm.submit();
	}else{
		alert("Error Code: " + sms_status + " | There was a problem verifying your Mobile no. Please check the no. or try again later.")
		return false;
	}
}

function do_sleep(){
	sleep_count++;
}



function check_email(obj){
	return;

	if(obj.value != ""){
		var obj_lbl = document.getElementById("msg_email")
		
		obj_lbl.innerHTML = call_ajax("ajax.php","process=check_memb_email&memb_email=" + obj.value)

		if(obj_lbl.innerHTML=="Available"){
			obj_lbl.style.color="#44FF88"
		}else{
			obj_lbl.style.color="#FF5588"
		}
	}
}

function check_pwd(obj){
	if(obj.value != ""){
		var obj_lbl = document.getElementById("msg_pwd")

		obj_lbl.innerHTML = call_ajax("ajax.php","process=check_pwd&pwd=" + obj.value)

		if(obj_lbl.innerHTML=="Ok"){
			obj_lbl.style.color="#44FF88"
		}else{
			obj_lbl.style.color="#FF5588"
		}
	}
}

function js_pwd_toggle(){
	if(document.frm.pwd.readOnly==false){
		document.frm.pwd.readOnly=true
		document.frm.cpwd.readOnly=true
		document.frm.pwd.className="table-bg form-control textbox-lrg"
		document.frm.cpwd.className="table-bg form-control textbox-lrg"		
		document.frm.pwd.id=""
		document.frm.cpwd.id=""
	}else{
		document.frm.pwd.readOnly=false
		document.frm.cpwd.readOnly=false
		document.frm.pwd.className="form-control textbox-lrg"
		document.frm.cpwd.className="form-control textbox-lrg"
		document.frm.pwd.id="150"
		document.frm.cpwd.id="160"		
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

//-->
</script>

</head>
<body>
<?php require("header.php"); ?>
<div id = "gif_show" style="display:none"></div>
<div id="content_hide">
<div id="contentwrapper">
<div id="contentcolumn">
  <div class="center-panel">
    <div class="you-are-here">
      <p align="left"> YOU ARE HERE:<span class="you-are-normal"><a href="index.php" title="Home Page">Home</a> | Edit your details</span> </p>
    </div>
    <h1>Edit Profile</h1>
    <?php
	if($incomp_msg <> "") { ?>
	<div class="alert alert-info">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    		<?=$incomp_msg?>
	</div>
    <?php } ?>
    
    <form name="frm" method="post" action="memb_edit_details.php" enctype="multipart/form-data" xonsubmit="javascript: return validate();">
	<input type="hidden" name="hdnsubmit" value="y">
    <table width="100%"  border="0" align="center" class="list">
      <tr>
        <td colspan="2" align="left" class="table-bg"><div align="left">
            <p>Your Sign In Details</p>
        </div></td>
      </tr>
      <tr>
        <td width="33%" align="right" class="table-bg-left"><div align="right">
            <label for="Email"></label>
            <p>Email :</p>
        </div></td>
        <td width="67%" align="left" xclass="table-bg">
		
		<input name="email" type="text" title="Email" class="form-control textbox-lrg" tabindex="1" size="20" maxlength="50" xonblur="javascript: check_email(this);" value="<?php echo $email;?>"/>
        * <span id="msg_email"></span></td>
      </tr>
	  <tr>
        <td align="right" class="table-bg2"><div align="right">
            <label for="Mobile No."></label>
            <p>Mobile No.:</p>
        </div></td>
        <td align="left" class="table-bg2"><input name="tele" class="form-control textbox-lrg" type="text" title="Mobile No." onkeypress='validate_key(event)' id="110" tabindex="2" size="20" maxlength="10"  value="<?=func_var($tele)?>"/>
        *</td>
      </tr>	
      <tr>
        <td align="right" class="table-bg-left"><div align="right">
            <label for="Password"></label>
            <p>Password :</p>
        </div></td>
        <td align="left" xclass="table-bg"><input class="table-bg form-control textbox-lrg" name="pwd" type="password" id="" readonly title="Your password must contain a minimum of 8 characters and a maximum of 54 characters. It must contain at-least 1 number, 1 capital letter and 1 lower-case letter.	" tabindex="3" size="20" maxlength="54"  onblur="javascript: check_pwd(this);" value="<?=func_var($pwd)?>"/>
        * <span id="msg_pwd"></span>
		<a href="#" class="pwd-chng" onclick="js_pwd_toggle();">Change</a>
		</td>
      </tr>
      <tr>
        <td align="right" class="table-bg-left"><div align="right">
            <label for="Confirm-Password"></label>
            <p>Confirm Password :</p>
        </div></td>
        <td align="left" xclass="table-bg"><input name="cpwd" class="table-bg form-control textbox-lrg" type="password" id="" readonly title="Confirm Password" tabindex="4" size="20" maxlength="54"  value="<?=func_var($cpwd)?>"/>
        * </td>
      </tr>
    </table>
    <table width="100%"  border="0" align="center" class="list">
      <tr>
        <td colspan="2" align="left" class="table-bg"><div align="left">
            <p>Your Address Details</p>
        </div></td>
      </tr>
      <tr>
        <td width="33%" align="right" class="table-bg2"><div align="right">
            <label for="Title"></label>
            <p>Title :</p>
        </div></td>
        <td width="67%" align="left" class="table-bg2"><select id="title" class="form-control textbox-sml" name="title"  tabindex="5">            
            <option value="Mr" <?=$MrSelected?>>Mr</option>
            <option value="Mrs" <?=$MrsSelected?>>Mrs</option>
            <option value="Ms" <?=$MsSelected?>>Ms</option>
            <option value="Dr" <?=$DrSelected?>>Dr</option>
        </select></td>
      </tr>
      <tr>
        <td align="right" class="table-bg2"><div align="right">
            <label for="First Name2"></label>
            <p>Buyer's Full Name :</p>
		</div></td>
        <td align="left" class="table-bg2"><input name="fname" type="text" id="100" class="form-control textbox-lrg" title="Buyer Name" tabindex="6" size="20" maxlength="50"  value="<?=func_var($fname)?>"/>
        *<p class="color-amber">Note: If Industrial buyer, name should be same as viewed on GST certificate.</p></td>
      </tr>
      <tr>
        <td align="right" class="table-bg2"><div align="right">
            <label for="Authorised/Contact Person Name"></label>
            <p>Authorised/Contact Person Full Name:</p>
        </div></td>
        <td align="left" class="table-bg2"><input name="memb_contact_per_name" type="text" placeholder="Optional, if the Owner is same as contact person." class="form-control textbox-lrg" title="Optional, if the Owner is same as contact person." tabindex="7" size="20" maxlength="50"  value="<?=func_var($memb_contact_per_name)?>"/>
        </td>
      </tr>
      <tr>
        <td align="right" class="table-bg2"><div align="right">
            <label for="Address1"></label>
            <p>Address 1 :</p>
        </div></td>
        <td align="left" class="table-bg2"><input name="add1" type="text" id="100" class="form-control textbox-lrg" title="Address1" tabindex="8" size="35" maxlength="50"  value="<?=func_var($add1)?>"/>
        *</td>
      </tr>
      <tr>
        <td align="right" class="table-bg2"><div align="right">
            <label for="Address2"></label>
            <p>Address 2 :</p>
        </div></td>
        <td align="left" class="table-bg2"><input name="add2" type="text" id="Address2" class="form-control textbox-lrg" tabindex="9" size="35" maxlength="50"  value="<?=func_var($add2)?>"/></td>
      </tr>
      <tr>
        <td align="right" class="table-bg2"><div align="right">
            <label for="City"></label>
            <p>Town / City :</p>
        </div></td>
        <td align="left" class="table-bg2"><input name="city" type="text" id="100" class="form-control textbox-lrg" title="Town / City" tabindex="10" size="20" maxlength="50"  value="<?=func_var($city)?>"/>
        *</td>
      </tr>
      <tr>
        <td align="right" class="table-bg2"><div align="right">
            <label for="Post Code"></label>
            <p>Post code :</p>
        </div></td>
        <td align="left" class="table-bg2"><input name="pcode" type="text" id="100" class="form-control textbox-sml" title="Post code" tabindex="11" size="8" maxlength="6"  value="<?=func_var($pcode)?>" onkeypress='validate_key(event)'/>
        *</td>
      </tr>

	<tr>
        <td align="right" class="table-bg2"><div align="right">
            <label for="Post Code"></label>
            <p>State :</p>
			</div>
		</td>
        <td align="left" class="table-bg2">
			<select id="100" title="State"  class="form-control textbox-auto" name="memb_state" tabindex="12">
			<option value="">Select</option>
			<?php create_cbo("select state_name,state_name from state_mast",func_var($memb_state))?>
			</select>*
		</td>
	</tr>
	
      <tr>
        <td align="right" class="table-bg2" ><div align="right">
            <label for="Country"></label>
            <p>Country :</p>
        </div></td>
        <td align="left" class="table-bg2"><select id="country"  class="form-control textbox-auto" name="country"  tabindex="13">
            <option value="India">India</option>
          </select>
        </td>
      </tr>
	  
	  <tr>
        <td align="right" class="table-bg2"><div align="right">
            <label for="Post Code"></label>
            <p>I am a Business Buyer  <a href="#" data-toggle="popover" data-trigger="hover" data-content="Corporate/SMEs who would like to procure items for their purchase orders, and regular operational needs should select this option."><span class="glyphicon glyphicon-question-sign glyf" style="color: #00aced;"></span></a></p>
			</div>
		</td>
		<?php if($ind_buyer == 1){ ?>
			<td align="left" class="table-bg2"><input type="checkbox" id="chk" name="Ind-buyer" tabindex="14" value="Ind-buyer" onclick="OnChangeCheckbox(this,'ind-buyer')" checked></td>
		<?php }else{ ?> 
			<td align="left" class="table-bg2"><input type="checkbox" id="chk" name="Ind-buyer" tabindex="14" value="Ind-buyer" onclick="OnChangeCheckbox(this,'ind-buyer')"></td>
		<?php } ?>
      </tr>  

    </table>

	<?php if($ind_buyer == 1){ ?>
		<div id="ind-buyer" style="display: block">
			<table width="100%"  border="0" align="center" class="list">
				<tr>
					<td colspan="3" align="left" class="table-bg"><div align="left">
						<p>My Tax Details</p>
					</div></td>
				</tr>
				<tr>
					<td align="right" class="table-bg2" ><div align="right"><p>Establishment name</p></div></td>
					<td align="left" class="table-bg2"><input type="textbox" id="es_name" title="Establishments Name" class="form-control textbox-mid" name="memb_company" maxlength="50" size="20" style="width:auto;" value="<?=func_var($memb_company)?>"/>* </td>
				</tr>
				<!--<tr>
					<td align="right" class="table-bg2" ><div align="right"><p>Central Sales Tax Account number (CST)</p></div></td>
					<td align="left" class="table-bg2"><input type="textbox" id="cst_n" title="Central Sales Tax Account number (CST)" class="form-control textbox-mid" name="memb_cstn" maxlength="12" size="20" style="width:auto;" value="<?=func_var($memb_cstn)?>"/>* </td>	
					<?php if($row["memb_cst_doc"] <> ""){ ?>
						<td align="left" class="table-bg2"><input name="files[]" class="transparent display-inline" type="file" size="40" style="width: 250px;" accept=".pdf,.png, .gif, .jpg, .jpeg" /><span><img class="display-inline" src="../images/checkmark_green.gif" title="File Uploaded"></span></td>
					<?php } else { ?>
						<td align="left" class="table-bg2"><input name="files[]" type="file" size="40" style="width: 250px;" accept=".pdf,.png, .gif, .jpg, .jpeg" /></td>
					<?php } ?>
				</tr>-->
			
				<tr>
					<td align="right" class="table-bg2" ><div align="right"><p>Goods and Service Tax number (GST)</p></div></td>
					<td align="left" class="table-bg2"><input type="textbox" id="vat_n" title="Value Added Tax number (GSTN)" class="form-control textbox-mid" name="memb_vat" maxlength="15" size="20" style="width:auto;" value="<?=func_var($memb_vat)?>"/>* </td>	
					<?php if($row["memb_vat_doc"] <> ""){ ?>
						<td align="left" class="table-bg2"><input name="files" class="transparent display-inline" type="file" size="40" style="width: 250px;" accept=".pdf,.png, .gif, .jpg, .jpeg" /><img class="display-inline" src="../images/checkmark_green.gif" title="File Uploaded"></td>							
					<?php } else { ?>
						<td align="left" class="table-bg2"><input name="files" type="file" size="40" style="width: 250px;" accept=".pdf,.png, .gif, .jpg, .jpeg" /></td>
					<?php } ?>	
				</tr>
			</table>
		</div>
	<?php }else{ ?>
		<div id="ind-buyer">
			<table width="100%"  border="0" align="center" class="list">
			  <tr>
				<td colspan="3" align="left" class="table-bg"><div align="left">
					<p>My Tax Details</p>
				</div></td>
			  </tr>
			  <tr>
				<td align="right" class="table-bg2" ><div align="right"><p>Establishment name</p></div></td>
				<td align="left" class="table-bg2"><input type="textbox" id="es_name" title="Establishment Name" class="form-control textbox-mid" name="memb_company" maxlength="50" size="20" class="form-control" style="width:auto;" /> *</td>
			  </tr>
				<!--	  <tr>
				<td align="right" class="table-bg2" ><div align="right"><p>Central Sales Tax Account number (CST)</p></div></td>
				<td align="left" class="table-bg2"><input type="textbox" id="cst_n" title="Central Sales Tax Account number (CSTN)" class="form-control textbox-mid" name="memb_cstn" maxlength="12" size="20" class="form-control" style="width:auto;" /> *</td>	
				<td align="left" class="table-bg2"><input name="files[]" type="file" size="40" style="width: 250px;"  accept=".pdf,.png, .gif, .jpg, .jpeg"/></td>
			  </tr> -->
	
			  <tr>
				<td align="right" class="table-bg2" ><div align="right"><p>Goods and Service Tax number (GST)</p></div></td>
				<td align="left" class="table-bg2"><input type="textbox" id="vat_n" title="Goods and Service Tax number (GSTN)" class="form-control textbox-mid" name="memb_vat" maxlength="15" size="20" class="form-control" style="width:auto;" /> *</td>	
				<td align="left" class="table-bg2"><input name="files" type="file" size="40" style="width: 250px;" accept=".pdf,.png, .gif, .jpg, .jpeg" /></td>			
			  </tr>
			</table>
		</div>
	<?php } ?>
	
	<table width="100%"  border="0" align="center" class="list">
		<tr>
			<td align="right" width="265px" class="table-bg2"><div align="right">
				<p>Subscribe Company-Name Newsletter</p>
			</div></td>
			<td align="left"  class="table-bg2">
			<?php if($newsletter == 1){ ?><input type="checkbox" name="newsletter" checked id="newsletter" tabindex="14" value="1" >
			<?php }else{?><input type="checkbox" name="newsletter" id="newsletter" tabindex="14" value="" > <?php } ?>
			</td>
		</tr>
	</table>

	<table width="100%"  border="0" align="center" class="list">
		<tr>
			<td align="right" class="table-bg" colspan=10></td>
		</tr>
		<tr>
			<td align="right">
				<input type="button" class="btn btn-warning" onclick="javascript: validate();" value=" Update"  tabindex="15" border="0" >
			</td>		
			<td align="left">
				<input type="button" class="btn btn-warning" onclick="window.location.href='memb_addr_upd.php'" value=" Add more addresses"  tabindex="15" border="0" >
			</td>
		</tr>
	</table>
</form>

  </div>
</div>
</div>
</div>
<?php 
require("left.php"); 
require("footer.php"); 
?>
</body>
<script src="scripts/chat.js" type="text/javascript"></script>
</html>
	