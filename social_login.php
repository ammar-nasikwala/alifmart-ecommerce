<?php
require("inc_init.php");

global $con;
$fld_arr = get_act_id($_SESSION["email_social"]);
//$act_link = get_base_url()."/register.php?id=".$fld_arr;
$sms_verify_code = mt_rand(1000, 9999);	
$fname=$_SESSION["fname_social"];
	$sms_verify_status=0;
	$ind_buyer=0;

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
	if(isset($_POST['submit']))
	{
		$phoneno=func_read_qs("tele");		
        if(!empty($phoneno) && preg_match('/^[0-9]{10}+$/', $phoneno))
        {
            $update = mysqli_query($con,"UPDATE member_mast SET memb_tel='".$phoneno."',sms_verify_code='".$sms_verify_code."',memb_act_id='".$fld_arr."',sms_verify_status='".$sms_verify_status."',memb_cstn='".$memb_cstn_social."',memb_vat='".$memb_vat_social."',memb_company='".$memb_company_social."',ind_buyer='".$ind_buyer."',memb_cst_doc='".$memb_cst_doc_social."',memb_vat_doc='".$memb_vat_doc_social."' WHERE  memb_email='".$_SESSION["email_social"]."'");
            $sms_msg = "Hi ".$fname.",This is a verification SMS for your mobile number as part of registration on Company-Name.com, your verification code is ".$sms_verify_code;
            $output = send_sms($phoneno,$sms_msg);
			if($output == "202"){
				$incomp_msg = "There was a problem verifying your Mobile number Please check the number or try again later.";
				goto END;
			}	
			header("location: register.php?id=$fld_arr");
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
	END:
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
	
	
	if(chkForm(document.frm)==false){
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
		
		document.frm.submit();
		return true;
	}
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
<?php require("header.php"); ?>
<div id="contentwrapper">
<div id="contentcolumn">
  <div class="center-panel">
    <div class="you-are-here">
      <p align="left"> YOU ARE HERE:<span class="you-are-normal"><a href="index.php" title="Home Page">Home</a> | Membership Registration</span> </p>
    </div>
   <!-- <h1>Membership Registration</h1> -->



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
        <td width="69%" align="left" xclass="table-bg"><input name="email" class="form-control textbox-lrg" type="text" id="120" title="Email" tabindex="1" size="20" maxlength="50" readonly  value="<?php echo $_SESSION["email_social"];?>"/>
        *<span id="msg_email"></span></td>
      </tr>
      <tr>
        <td align="right" class="table-bg2"><div align="right">
            <label for="Mobile No."></label>
            <p>Mobile No.:</p>
        </div></td>
        <td align="left" class="table-bg2"><input name="tele" class="form-control textbox-lrg" onkeypress='validate_key(event)' type="text"  title="Mobile No." id="110" tabindex="2" size="20" maxlength="10" value="<?php echo $_SESSION["tel_social"];?>"/>
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
			<td align="right" class="table-bg2" ><div align="right"><p>Goods and Service Tax Number (GST)</p></div></td>
			<td align="left" class="table-bg2"><input type="textbox" id="vat_n" title="Goods and Service Tax number (GSTN)" class="form-control textbox-mid" name="memb_vat" maxlength="12" id="100" size="20" class="form-control" style="width:auto;" /> </td>	
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

</div>
</div>
</div>
<?php require("left.php"); 
require("footer.php"); ?>
</body>
</html>