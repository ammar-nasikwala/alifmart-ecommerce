<?php

require("inc_init.php");

$msg="";

	if(func_read_qs("hdnsubmit") == "y")
	{
		if($_POST['captcha'] != $_SESSION['digit']){
			$msg = "Please enter valid CAPTCHA code";
		}else{
			$id = get_max("prod_enquiry","id");
			$fld_arr = array();
			$fld_arr["email"] = func_read_qs("email");
			$prod_des_name = stripslashes(func_read_qs("prod_name"));
			$fld_arr["prod_name"] = nl2br($prod_des_name);
			//Check before creating a new one
			
		if(isset($_FILES['uploadfile'])){
			$errors= array();
			$maxsize    = 204800; //200kb = 1024*200
			$acceptable = array(
				'image/jpeg',
				'image/jpg',
				'image/gif',
				'image/png'
				);
				$upload_image = array();
				for($i=0;$i<2;$i++){
			$file_name = $_FILES['uploadfile']['name'][$i];
			if($file_name <> ""){
				$file_tmp =$_FILES['uploadfile']['tmp_name'][$i];
				
				$enq_dir = $_SERVER['DOCUMENT_ROOT']."/extras/product-enquiry";
				if(is_dir($enq_dir)==false){
					mkdir($enq_dir,0700);
				}
				$desired_dir=$_SERVER['DOCUMENT_ROOT']."/extras/product-enquiry/id-".$id;
				
				if(!in_array($_FILES['uploadfile']['type'][$i], $acceptable) && (!empty($_FILES["uploadfile"]["type"][$i]))) {
							$errors[] = 'Invalid file type. Only JPEG, JPG, GIF and PNG types are accepted.';
						}
				
				if((filesize($file_tmp) >= $maxsize) || (filesize($file_tmp) == 0)) {
					$errors[] = 'File is too large. File size must be less than 200 Kb.';
				}
				if(empty($errors)==true){
					if(is_dir($desired_dir)==false){
						mkdir($desired_dir, 0700);		// Create directory if it does not exist
					}
					
					$file_path="";
					$file_path="extras/product-enquiry/id-".$id."/".$file_name;
					move_uploaded_file($file_tmp,$file_path);
					$upload_image[$i] = $desired_dir."/".$file_name;
					$upload_docs[$i] = $file_path;
					$act = 1;
				}else{
					$act = 0;
					$msg= "Image upload failed!! ".$errors[0] ;
				}
			}
				}
				$fld_arr["image_path1"] = $upload_docs[0];
				$fld_arr["image_path2"] = $upload_docs[1];
		}
		if($msg == "") {
			$qry = func_insert_qry("prod_enquiry",$fld_arr);
			execute_qry($qry);
			//this is for sending enquiry mail to sales dept
			
			$body="Hello,<br><br> An enquiry has been received, with following details:<br><br> Product Name:&nbsp;".$fld_arr["prod_name"]."<br>Email:&nbsp;".$fld_arr["email"]."<br><br>Please find attached images of the product.<br><br>Thank You<br> Regards Company-Name";
			if(multi_attach_mail($sales,"Product Enquiry",$body,"sales@Company-Name.com",$upload_image,"")){
				$msg="Your enquiry has been placed successfully";
			}else{
					$msg="There is some problem in placing your enquiry. Please try again later or contact our customer support.";
			 }
				
			//mail sent to customer
			$mail_body = "Dear Customer, <br> Thank you for placing an inquiry for your requirement on our portal. Your request is registered with following details:";
			$mail_body = $mail_body."<br><br> <b>Product Description:</b><br>".nl2br($fld_arr["prod_name"])."<br><br>";
			$mail_body = $mail_body."We are working on your request and will get back to you soon.<br><br> Thanks & Regards <br>Sales Team - Company-Name<br> IVR:     +91 77 9849 5353 <br>Direct: +91 20 4003 5353";
			$from = "sales@Company-Name.com";
			xsend_mail($fld_arr["email"],"Company-Name - Product Enquiry",$mail_body,$from );
		}
	}
}
?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Company-Name - Enquiry </title>
<link href="styles/styles.css" rel="stylesheet" type="text/css" />
<link href="styles/bannerstyle.css" rel="stylesheet" type="text/css" />
<script src="scripts/scripts.js" type="text/javascript"></script>
<script type="text/javascript" src="scripts/animatedcollapse.js"></script>
<script type="text/javascript">
function validate(){
	if(document.getElementById("prod_name").value==""|| document.getElementById("email").value==""){
			alert("Please enter mandatory details.");
			
			document.getElementById("prod_name").style.borderColor = "red";
			document.getElementById("email").style.borderColor = "red";
			return false;
			}
		if(document.getElementById("uploadfile1").value=="" && document.getElementById("uploadfile").value==""){
			alert("please provide atleast one image");
			document.getElementById("uploadfile1").focus();
			return false;
		}
		var email = document.getElementById('email');
		var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

			if (!filter.test(email.value)) {
				alert("Please enter valid email.")
				document.getElementById("email").style.borderColor = "red";
				email.focus;
				return false;
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

</script>
</head>
<body>

<?php require("header.php"); ?>

<div id = "gif_show" style="display:none"></div>
<div id="content_hide">
	<div id="contentwrapper">
	<div class="center-panel">
    <div class="you-are-here">
		<p align="left"> YOU ARE HERE:<span class="you-are-normal"><a href="index.php" title="Home Page">Home</a> | Product Enquiry</span> </p>
    </div>
	<?php if($msg<>""){?>
<div class="alert alert-info">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <?=$msg?>
</div>
<?php }?>
<form name="frm" method="post" action="" enctype="multipart/form-data">
	<input type="hidden" name="hdnsubmit" value="y">
	<div class="enquiry">
		<table style="border-spacing: 10px; border-collapse: separate; margin-left:50px" class="list">
			<tr>
				<td colspan="2" class="table-bg"><center>Couldn't find what you want. Leave us an enquiry!</center>
				</td>
			</tr>
			<tr>
				<!--td align="right" class="table-bg" colspan=10></td-->
			</tr>
			<tr>
				<td width="31%" align="right" class="table-bg2"><div align="right">
				<label for="Email"></label><br>
				<p>Enter email :</p>
				</div></td>
				<td width="69%" align="left" xclass="table-bg"><input name="email" id="email"  class="form-control textbox-lrg" type="text" title="Email" tabindex="1" size="20" maxlength="50"/>
				*<span id="msg_email"></span></td>
			</tr>
			
			<tr>
				<td align="right" class="table-bg2"><div align="right">
				<label for="First Name2"></label>
				<p>Enter product details:</p>
				</div></td>
				<td align="left"  class="table-bg2"><textarea name="prod_name" id="prod_name" class="form-control textbox-lrg" title="Product Name" tabindex="2"></textarea>
				*</td>
			</tr>
			
			<tr>
				<td align="right" class="table-bg2" ><div align="right"><p>Upload Image1 :</p></div></td>
				<td align="left" class="table-bg2"><input name="uploadfile[]" type="file" id="uploadfile1" size="40" tabindex="3" style="width: 250px;" accept=".png, .jpg, .jpeg, .gif" /></td>			
			</tr>
			<tr>
				<td align="right" class="table-bg2" ><div align="right"><p>Upload Image2 :(optional)</p></div></td>
				<td align="left" class="table-bg2"><input name="uploadfile[]" type="file" id="uploadfile" size="40" tabindex="3" style="width: 250px;" accept=".png, .jpg, .jpeg, .gif" /></td>			
			</tr>
			<tr>
				<td align="right" class="table-bg2"><div align="right">
				<p>Validation code:</p>
				</div></td>
				<td><img src="/captcha.php" width="120" height="30" border="1" alt="CAPTCHA" id="captchaimg"><br>
				<label for='message'>Enter the code above here : </label><input type="text" style="margin-left:5px" id="captcha_code" size="8" class="form-control textbox-auto" tabindex="4" maxlength="5" name="captcha" value="">
				
				<br>
				Can't read the image? click <a class="color-amber" href="javascript: refreshCaptcha();">here</a> to refresh.</td>
			</tr>
		<table width="100%"  border="0" align="center" class="list">
			<tr>
				<td align="right" class="table-bg" colspan=10></td>
			</tr>
			<tr>
				<td align="center">
				<input type="submit" class="btn btn-warning" onclick="javascript: return validate();" value=" Submit " name="submit" tabindex="5" >
				</td>
			</tr>
		</table>
	</table>
</div>
<br>
<br>
<br>
</form>
	
	</div>
	</div>
</div>
<?php require("footer.php"); ?>
</body>
<script src="scripts/chat.js" type="text/javascript"></script>
</html>
	