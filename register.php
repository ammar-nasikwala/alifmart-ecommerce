<?php
require("inc_init.php");

//filer access from russian ips
if ($_SERVER['HTTP_X_FORWARDED_FOR']){
	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}else{
	$ip   = $_SERVER['REMOTE_ADDR'];
}
$country_code=iptocountry($ip);
if($country_code == "RU" || $country_code == "CN" || $country_code == "AF" || $country_code == "KZ" || $country_code == "UZ" || $country_code == "UA"){
	die("Page not available.");
}

if($_SESSION["memb_id"]<>""){
	js_redirect("index.php");
}
$incomp_msg = "";
$img_display = "";
$s_flag = "";
$sms_verify_flag = false;
$memb_act_id = func_read_qs("id");
$hdn_act_submit = func_read_qs("hdn_act_submit");
if($memb_act_id<>""){

	get_rst("select memb_tel, memb_fname, sms_verify_code from member_mast where memb_act_id='$memb_act_id'", $rec);
	if($hdn_act_submit =="y"){
		$sms_verify_code = func_read_qs("sms_verify_code");
		if($sms_verify_code <> $rec["sms_verify_code"]){
				$incomp_msg = "Sorry! Not a valid sms verification code. ";
		}else{
		$rst = mysqli_query($con, "update member_mast set memb_status=1, memb_act_status=1, sms_verify_status=1 where memb_act_id='$memb_act_id' AND sms_verify_code=$sms_verify_code ");
		if($rst){
			$incomp_msg = "Congratulations! Your account has been activated. Please sign in from above panel.";
			$s_flag = "1";
			$img_display = "1";
		}
		}
	}else{
		if(get_rst("select memb_id from member_mast where memb_act_status=1 and memb_act_id='$memb_act_id'")){
			$incomp_msg = "Your account has already been activated.";
			$s_flag = "1";
			$img_display = "1";
		}
	}
}

if(func_read_qs("hdnsubmit") == "y"){	
	
	$email = func_read_qs("email");
	$pwd = func_read_qs("pwd");
	$cpwd = func_read_qs("cpwd");
	$title = func_read_qs("title");
	$fname = func_read_qs("fname");
	$add1 = func_read_qs("add1");
	$add2 = func_read_qs("add2");
	$city = func_read_qs("city");
	$country = func_read_qs("country");
	$pcode = func_read_qs("pcode");
	$memb_state = func_read_qs("memb_state");
	$tele = func_read_qs("tele");
	$memb_contact_per_name = func_read_qs("memb_contact_per_name");
	
	// 4 Digit random number code for sms verification.
	$sms_verify_code = mt_rand(1000, 9999);		
	
	$rule = "";
	$rule = "email|c|Email^";
    $rule = "email|e|Email^";
	$rule = $rule."pwd|c|Password^";
	$rule = $rule."pwd|p|Password^";
	$rule = $rule."cpwd|c|Confirm Password^";
	$rule = $rule."cpwd|m|Confirm Password^";
	$rule = $rule."fname|c|First Name^"	;
	$rule = $rule."add1|c|Address1^";
	$rule = $rule."city|c|Town / City^";
	$rule = $rule."pcode|c|Post Code^";
	$rule = $rule."memb_state|c|State^";
	$rule = $rule."country|c|Country^";
	$rule = $rule."tele|c|Telephone No.^";

    $incomp_msg = validate($rule);

	if($incomp_msg == ""){
		$memb_id = get_max("member_mast","memb_id");
		$fld_arr = array();
		$fld_arr["memb_id"] = $memb_id;
		$fld_arr["memb_fname"] = $fname;
		$fld_arr["memb_contact_per_name"] = $memb_contact_per_name;
		$fld_arr["memb_title"] = $title;
		$fld_arr["memb_email"] = $email;
		$fld_arr["memb_add1"] = $add1;
		$fld_arr["memb_add2"] = $add2;	
		$fld_arr["memb_city"] = $city;
		$fld_arr["memb_state"] = $memb_state;
		$fld_arr["memb_country"] = $country;
		$fld_arr["memb_postcode"] = $pcode;
		$fld_arr["memb_tel"] = $tele;
		$fld_arr["memb_pwd"] = $pwd;
		$fld_arr["memb_act_id"] = get_act_id($fld_arr["memb_email"]);
		$fld_arr["sms_verify_code"] = $sms_verify_code;
		$fld_arr["sms_verify_status"] = 0;
		$fld_arr["ind_buyer"] = 0;
		$fld_arr["memb_newsletter"] = 0;
		
		$fld_addr_arr = array();
		$fld_addr_arr["memb_id"] = $memb_id;
		$fld_addr_arr["ext_addr_name"] = "Primary address";
		$fld_addr_arr["ext_addr_default"] = 1;
		$fld_addr_arr["ext_addr1"] = $add1;
		$fld_addr_arr["ext_addr2"] = $add2;
		$fld_addr_arr["ext_addr_state"] = $memb_state;
		$fld_addr_arr["ext_addr_city"] = $city;
		$fld_addr_arr["ext_addr_pin"] = $pcode;
		$fld_addr_arr["ext_addr_contact"] = $tele;
		$addr_qry = func_insert_qry("memb_ext_addr",$fld_addr_arr);
		mysqli_query($con, $addr_qry);
		
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
				$upload_doc_arr = array();
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
						$file_path="extras/user_data/id-".$memb_id."/".$file_name;
						move_uploaded_file($file_tmp,$file_path);
						$imageFileType = strtolower(pathinfo($file_path,PATHINFO_EXTENSION));
						$memb_vat_doc=img_resize_db($file_path, 1200, 980, $imageFileType);
					}else{
						$act="0";
						$incomp_msg = "Upload Failed!! ".$errors[0] ;
						goto END;
					}
				}else{
					$memb_vat_doc="";
				}
				if($memb_vat_doc <> "") $fld_arr["memb_vat_doc"] = $memb_vat_doc; //GST doc
				//$fld_arr["memb_cstn"] = $memb_cstn;
				$fld_arr["memb_vat"] = $memb_vat; //GST
				$fld_arr["memb_company"] = $memb_company;
				$fld_arr["ind_buyer"] = 1;
			}	
		}
		if(isset($_POST['newsletter'])){
			$fld_arr["memb_newsletter"] = 1;
		}
		 
		if (mysqli_errno($con) <>0) {
			if (mysqli_errno($con) == 1062) {
				$incomp_msg = "You are already a member. Please try login or forgot password to retrieve your password.";
			}else{
				$incomp_msg = mysqli_error($con);
			}
		}else{
			if($_POST['captcha'] != $_SESSION['digit']){
			 $incomp_msg = "Please enter valid CAPTCHA code";
			}else{
			$sqlIns = func_insert_qry("member_mast",$fld_arr);
			mysqli_query($con,$sqlIns);
			
			//Send confirmation msg to user.
			$sms_msg = "Hi ".$fname.",Your mobile verification code is ".$sms_verify_code.". Kindly enter this code in the email activation page to enable your account for transactions.";
			$output = send_sms($tele,$sms_msg);
			
			if($output == "202"){
				$incomp_msg = "There is a problem to verify your Mobile number. Please check the number or try again later.";
				goto END;
			}		
			
			$fld_arr["act_link"] = get_base_url()."/register.php?id=".$fld_arr["memb_act_id"];
			
			$mail_body = push_body("buyer_activation.txt",$fld_arr);
			$from = "welcome@Company-Name.com";		
			if(xsend_mail($fld_arr["memb_email"],"Company-Name - Buyer account verification email",$mail_body,$from )){
				$incomp_msg="Thank you for registering with us. You will shortly receive an activation link via email. Please follow the link to activate your account.";
				$s_flag = "1";
				$img_display = "1";
			}else{ 
				$incomp_msg="There is a problem to send email to your email address.";
				$img_display = "1";
			}
			
			$body="Congratulations!!,<br>";
			$body.="&nbsp;&nbsp;A new Buyer has been added to our system with the following details.<br>";
			$body.="<br>Name : $fname";
			$body.="<br>City : $city";
			$body.="<br><br>Regards,<br>Company-Name Admin";		
			xsend_mail("support@Company-Name.com","Company-Name - Buyer account verification email",$body,$from );
			
			
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
END:
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Company-Name - Register Buyer</title>
<meta property="og:url" content="http://www.Company-Name.com/register.php" />
<meta property="og:image" content="http://www.Company-Name.com/images/buyer.png"/>  
<meta property="og:title" content="Company-Name - Buyer Registration"/>  
<meta property="og:description" content="Buy Industrial Hardware, Tools, Mchinery and Office Supplis at Company-Name, Industrila Shopping Redefined."/>

<link href="styles/styles.css" rel="stylesheet" type="text/css" />
<script src="scripts/scripts.js" type="text/javascript"></script>

<script type="text/javascript">
clearForm();
function clearForm()
{		
	document.frm.email.value="";
	document.frm.pwd.value="";		
	document.frm.cpwd.value="";	
	document.frm.fname.value="";	
	document.frm.memb_contact_per_name.value="";	
	document.frm.city.value="";		
	document.frm.add1.value="";		
	document.frm.add2.value="";		
	document.frm.memb_state.value="";		
	document.frm.pcode.value="";		
	document.frm.tele.value="";		
	document.frm.memb_company.value="";		
	document.frm.memb_cstn.value="";		
	document.frm.memb_vat.value="";
	document.frm.captcha_code.value="";
	document.getElementById("msg_email").innerHTML="";
	document.getElementById("msg_phone").innerHTML="";
	document.getElementById("msg_pwd").innerHTML="";
}

var sleep_count;
function validate(){
	
	p_valid = check_phno(document.frm.tele);
	v_valid = check_email(document.frm.email);
	
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
			if(document.getElementById("es_name").value==""||document.getElementById("vat_n").value==""){
			alert("Please Enter All The Details");
			return false;
			}else{
				display_gif();
				document.frm.submit();
				return true;
			}	
	   }
	   if(document.getElementById("captcha_code").value=="") {
		   
		alert('Please enter the CAPTCHA code');
		frm.captcha.focus();
		return false;
		}
		display_gif();
		document.frm.submit();
		return true;
	}
}

function validate_terms(){

	if(chkForm(document.frm)==false){
		return false;
	}else{
		document.frm.submit();
	}
}


function js_sms_response(v_code){
	
	if(v_code == "200" || v_code == "201"){
		document.frm.submit();
	}else{
		alert("Error Code: " + sms_status + " | There is a problem to verify your Mobile no. Please check the no. or try again later.")
		return false;
	}
}

function do_sleep(){
	sleep_count++;
}

function send_sms(){
	var obj_fname = document.getElementById("fname_s").value;
	var obj_tele = document.getElementById("tele_no").value;
	var obj_sms_code = document.getElementById("sms_code").value;
	var output_msg = call_ajax("ajax.php","process=send_sms&sms_code=" + obj_sms_code + "&tele_no=" + obj_tele + "&fname=" + obj_fname)
	alert(output_msg)
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

function remove_msg(obj_id){
	document.getElementById(obj_id).innerHTML = "";
}
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
      <p align="left"> YOU ARE HERE:<span class="you-are-normal"><a href="index.php" title="Home Page">Home</a> | Buyer Registration</span> </p>
    </div>
   <!-- <h1>Membership Registration</h1> -->
	<?php
	if($incomp_msg <> "") { ?>
	<div class="alert alert-info">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<?=$incomp_msg?>
	</div>
	<?php } 
	if($img_display <> ""){?>
		<center><img src="images/logo-app.gif" alt="" style="opacity: 0.2; padding-top: 70px;" /></center>
	<?php }
	
	if($memb_act_id=="" and $s_flag==""){?>
    <form name="frm" method="post" action="register.php" enctype="multipart/form-data" xonsubmit="javascript: return validate();">
	<input type="hidden" name="hdnsubmit" value="y">
	<input type="hidden" name="sms_msg" value="">
    <table width="100%"  border="0" align="center" class="list">
      <tr>
        <td colspan="2" align="left" class="table-bg"><div align="left">
            <p>My Signin Details</p>
        </div></td>
      </tr>
	  <tr><td></td>
	  <td><a class="btn btn-danger color-white" href="google_login/">
	  &nbsp;&nbsp;<span class="entypo-gplus color-white">&nbsp;  &nbsp;Signup with Google</span>&nbsp;&nbsp;&nbsp;
	  </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-primary color-white" href="facebook_login/fbconfig.php">
	  &nbsp;&nbsp;<span class="entypo-facebook color-white">&nbsp;  &nbsp;Signup with Facebook</span> &nbsp;&nbsp;</a>
	  </a></td></tr>
      <tr>
        <td width="33%" align="right" class="table-bg-left"><div align="right">
            <label for="Email"></label>
            <p>Email :</p>
        </div></td>
        <td width="67%" align="left" xclass="table-bg"><input name="email" class="form-control textbox-lrg" type="text" id="120" title="Email" tabindex="1" size="20" maxlength="50" onkeypress="javascript: remove_msg('msg_email');" onblur="javascript: check_email(this);" value="<?=func_var($email)?>"/>
        *<span id="msg_email"></span></td>
      </tr>
      <tr>
        <td align="right" class="table-bg2"><div align="right">
            <label for="Mobile No."></label>
            <p>Mobile :</p>
        </div></td>
        <td align="left" class="table-bg2"><input name="tele" class="form-control textbox-lrg" type="text" onkeypress="validate_key(event); remove_msg('msg_phone');" title="Mobile No." id="110" tabindex="2" size="20" maxlength="10" onblur="javascript: check_phno(this);" value="<?=func_var($tele)?>"/>
        *<span id="msg_phone"></span></td></td>
      </tr>	  
      <tr>
        <td align="right" class="table-bg-left"><div align="right">
            <label for="Password"></label>
            <p>Password :</p>
        </div></td>
        <td align="left" xclass="table-bg"><input name="pwd" onkeypress="remove_msg('msg_pwd');" class="form-control textbox-lrg" type="password" data-toggle="tooltip" data-placement="left" title="Your password must contain a minimum of 8 characters and a maximum of 54 characters. It must contain at-least 1 number, 1 capital letter and 1 lower-case letter.	" tabindex="3" size="20" maxlength="54"  onblur="javascript: check_pwd(this)" value="<?=func_var($pwd)?>"/>
        * <span id="msg_pwd"></span></td>
      </tr>
      <tr>
        <td align="right" class="table-bg-left"><div align="right">
            <label for="Confirm-Password"></label>
            <p>Confirm Password :</p>
        </div></td>
        <td align="left" xclass="table-bg"><input name="cpwd" class="form-control textbox-lrg" type="password" tabindex="4" size="20" maxlength="54"  value="<?=func_var($cpwd)?>"/>
        * </td>
      </tr>
    </table>
    <table width="100%"  border="0" align="center" class="list">
      <tr>
        <td colspan="2" align="left" class="table-bg"><div align="left">
            <p>My Address Details</p>
        </div></td>
      </tr>
      <tr>
        <td width="33%" align="right" class="table-bg2"><div align="right">
            <label for="Title"></label>
            <p>Title :</p>
        </div></td>
        <td width="67%" align="left" class="table-bg2"><select class="form-control textbox-sml" id="title" name="title"  tabindex="5">            
            <option value="Mr">Mr</option>
            <option value="Mrs">Mrs</option>
            <option value="Ms">Ms</option>
            <option value="Dr">Dr</option>
        </select></td>
      </tr>
      <tr>
        <td align="right" class="table-bg2"><div align="right">
            <label for="First Name2"></label>
            <p>Buyer's Full name :</p>
        </div></td>
        <td align="left" class="table-bg2"><input name="fname" class="form-control textbox-lrg" type="text" id="100" title="Buyer Name" tabindex="6" size="20" maxlength="50"  value="<?=func_var($fname)?>"/>
        *<p class="color-amber">Note: If Industrial buyer, name should be same as viewed on GST certificate.</p></td>
      </tr>
      <tr>
        <td align="right" class="table-bg2"><div align="right">
            <label for="Authorised/Contact Person Name"></label>
            <p>Authorised/Contact Person Full Name:</p>
        </div></td>
        <td align="left" class="table-bg2"><input name="memb_contact_per_name" placeholder="Optional, if the Owner is same as contact person." class="form-control textbox-lrg" type="text" title="Optional, if the Owner is same as contact person." tabindex="7" size="20" maxlength="50"  value=""/>
        </td>
      </tr>
      <tr>
        <td align="right" class="table-bg2"><div align="right">
            <label for="Address1"></label>
            <p>Address 1 :</p>
        </div></td>
        <td align="left" class="table-bg2"><input name="add1" class="form-control textbox-lrg" type="text" id="100" title="Address1" tabindex="8" size="35" maxlength="50"  value="<?=func_var($add1)?>"/>
        *</td>
      </tr>
      <tr>
        <td align="right" class="table-bg2"><div align="right">
            <label for="Address2"></label>
            <p>Address 2 :</p>
        </div></td>
        <td align="left" class="table-bg2"><input name="add2" class="form-control textbox-lrg" type="text" id="Address2" tabindex="9" size="35" maxlength="50"  value="<?=func_var($add2)?>"/></td>
      </tr>
      <tr>
        <td align="right" class="table-bg2"><div align="right">
            <label for="City"></label>
            <p>City :</p>
        </div></td>
        <td align="left" class="table-bg2"><input name="city" class="form-control textbox-lrg" type="text" id="100" title="Town / City" tabindex="10" size="20" maxlength="50"  value="<?=func_var($city)?>"/>
        *</td>
      </tr>
      <tr>
        <td align="right" class="table-bg2"><div align="right">
            <label for="Post Code"></label>
            <p>Pincode :</p>
        </div></td>
        <td align="left" class="table-bg2"><input name="pcode" class="form-control textbox-sml" type="text" onkeypress='validate_key(event)' id="100" title="Pin code" tabindex="11" size="8" maxlength="6" value="<?=func_var($pcode)?>"/>
        *</td>
      </tr>

	<tr>
        <td align="right" class="table-bg2"><div align="right">
            <label for="Post Code"></label>
            <p>State :</p>
			</div>
		</td>
        <td align="left" class="table-bg2">
			<select id="100" class="form-control textbox-auto" title="State" name="memb_state" tabindex="12">
			<option value="">Select</option>
			<?=create_cbo("select state_name,state_name from state_mast",func_var($memb_state))?>
			</select>*
		</td>
	</tr>
	
      <tr>
        <td align="right" class="table-bg2" ><div align="right">
            <label for="Country"></label>
            <p>Country :</p>
        </div></td>
        <td align="left" class="table-bg2"><select class="form-control textbox-auto" id="country" name="country"  tabindex="13">
            <option value="India">India</option>
          </select>
        </td>
      </tr>

	  <tr>
        <td align="right" class="table-bg2"><div align="right">
            <label for="Post Code"></label>
            <p>I am Industrial Buyer <a href="#" data-toggle="popover" data-trigger="hover" data-content="Corporate/SMEs who would like to procure items for their purchase orders, and regular operational needs should select this option."><span class="glyphicon glyphicon-question-sign glyf" style="color: #00aced;"></span></a></p> </p>
        </div></td>
        <td align="left" class="table-bg2"><input type="checkbox" name="Ind-buyer" checked id="chk" tabindex="14" value="Ind-buyer" onclick="OnChangeCheckbox(this,'ind-buyer')">
        </td>
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
			<td align="right" class="table-bg2" ><div align="right"><p>Establishment Name</p></div></td>
			<td align="left" class="table-bg2"><input type="textbox" id="es_name" title="Establishment Name" class="form-control textbox-mid" name="memb_company" maxlength="50" id="100" size="20" class="form-control" style="width:auto;" /> </td>
		</tr>
		  <!--<tr>
			<td align="right" class="table-bg2" ><div align="right"><p>Central Sales Tax Account Number (CST)</p></div></td>
			<td align="left" class="table-bg2"><input type="textbox" id="cst_n" title="Central Sales Tax Account number (CSTN)" class="form-control textbox-mid" name="memb_cstn" maxlength="12" id="100" size="20" class="form-control" style="width:auto;" /> </td>	
			<td align="left" class="table-bg2"><input name="files[]" type="file" size="40" style="width: 250px;"  accept=".pdf,.png, .gif, .jpg, .jpeg"/></td>
		  </tr>-->
	
		  <tr>
			<td align="right" class="table-bg2" ><div align="right"><p>Goods and Service Tax Number (GST)</p></div></td>
			<td align="left" class="table-bg2"><input type="textbox" id="vat_n" title="Goods and Service Tax number (GSTN)" class="form-control textbox-mid" name="memb_vat" maxlength="15" id="100" size="20" class="form-control" style="width:auto;" /> </td>	
			<td align="left" class="table-bg2"><input name="files" type="file" size="40" style="width: 250px;" accept=".pdf,.png, .gif, .jpg, .jpeg" /></td>			
		  </tr>
		</table>
	</div>
	
	<table width="100%"  border="0" align="center" class="list">
		<tr>
			<td align="right" width="265px" class="table-bg2"><div align="right">
				<p>Subscribe Company-Name Newsletter</p>
			</div></td>
			<td align="left"  class="table-bg2"><input type="checkbox" name="newsletter" checked id="newsletter" tabindex="14" value="1" >
			</td>
		</tr>
		<tr>
			<td align="right" class="table-bg2"><div align="right">
			<p>Validation code:</p>
			</div></td>
			<td><img src="/captcha.php" width="120" height="30" border="1" alt="CAPTCHA" id="captchaimg"><br>
			<label for='message'>Enter the code above here : </label><input type="text" style="margin-left:5px" id="captcha_code" size="8" class="form-control textbox-auto" maxlength="5" name="captcha" value="">
			
			<br>
			Can't read the image? click <a class="color-amber" href="javascript: refreshCaptcha();">here</a> to refresh.</td>
		</tr>
	</table>
	
	<table width="100%"  border="0" align="center" class="list">
		<tr>
			<td align="right" class="table-bg" colspan=10></td>
		</tr>
		<tr>
			<td align="right">
			<!--<a href="#" onclick="javascript:clearForm()"><img src="images/clear.gif" alt="CLEAR"  border="0" /></a>-->
				<input type="button" class="btn btn-warning" value=" Clear " onclick="javascript:clearForm()">
			</td>
			<td align="left">
				<input type="button" class="btn btn-warning" onclick="javascript: return validate();" value=" Register "  tabindex="15" >
			</td>
		</tr>
	</table>

</form>
<?php }
    if($memb_act_id<>"" and $s_flag==""){ ?>
	
	<form name="frm" method="post" action="register.php" xonsubmit="javascript: return validate();">
		<input type="hidden" name="hdn_act_submit" value="y">
		<input type="hidden" name="memb_act_id" value="<?= $memb_act_id ?>">
		<input type="hidden" name="id" value="<?= $memb_act_id ?>">
	 
		<table width="100%"  border="0" align="center" class="list">
		  <tr>
			<td colspan="2" align="left" class="table-bg"><div align="left">
				<p>Terms and Condition</p>
			</div></td>
		  </tr>
		  
		  
		  <tr>
		  <td>
				
				<input type="checkbox" name="chk_accept" value="1" id="100" title="Accepting Terms and Condition">
				 I have read and accept <a href='terms.php' target="_blank" style="color:#FF9933; font-weight: bold">terms and conditions.</a>
		  </td>
		  </tr>
		  <tr>
		  <td>Enter SMS verification code</td>
		  <td><input name="sms_verify_code" class="form-control textbox-sml" type="text" title="SMS verification code" id="110" tabindex="14" size="10" maxlength="10" value=""/></td>
		  <td><input id="fname_s" type="hidden" name="fname_s" value="<?php echo $rec["memb_fname"]; ?>"><input id="tele_no" type="hidden" name="tele_no" value="<?php echo $rec["memb_tel"]; ?>"><input id="sms_code" type="hidden" name="sms_code" value="<?php echo $rec["sms_verify_code"]; ?>"><a href="#" onclick="javascript: send_sms();" >Resend verification code</a></td>
		  </tr>
		  <tr>
	  
		  <td align="center" colspan="2">
		  <input type="button" class="btn btn-warning" xsrc="images/submit.gif" onclick="javascript: return validate_terms();" value=" Submit "  tabindex="15" border="0" / id=image1 name=image1>
		  </td>
	      </tr>
	  </table>
	</form>
    <?php } ?>
    
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