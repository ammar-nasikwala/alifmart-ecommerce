<?php
require_once("inc_admin_header.php");

$memb_img="";

$img_path = "../images/users/";
$id = func_read_qs("id");
if(isset($_POST["act"])) $act=$_POST["act"];
if(isset($_POST["save"])){
	
		$fld_arr = array();
		
		$fld_arr["memb_fname"]=func_read_qs("memb_fname");
		$fld_arr["memb_title"]=func_read_qs("memb_title");
		$fld_arr["memb_email"]=func_read_qs("memb_email");
		$fld_arr["memb_add1"]=func_read_qs("memb_add1");
		$fld_arr["memb_add2"]=func_read_qs("memb_add2");
		$fld_arr["memb_city"]=func_read_qs("memb_city");
		$fld_arr["memb_state"]=func_read_qs("memb_state");
		$fld_arr["memb_country"]=func_read_qs("memb_country");
		$fld_arr["memb_postcode"]=func_read_qs("memb_postcode");
		$fld_arr["memb_tel"]=func_read_qs("memb_tel");
		$fld_arr["memb_pwd"]=func_read_qs("memb_pwd");
		$fld_arr["memb_act_status"]=func_read_qs("memb_act_status");
		$fld_arr["sms_verify_status"]=func_read_qs("sms_verify_status");
		$fld_arr["memb_company"]=func_read_qs("memb_company");
		
		$fld_addr_arr = array();
	
		$fld_addr_arrr["ext_addr_name"] = "Primary address";
		$fld_addr_arrr["ext_addr1"] =func_read_qs("memb_add1");
		$fld_addr_arrr["ext_addr2"] = func_read_qs("memb_add2");
		$fld_addr_arrr["ext_addr_state"] =func_read_qs("memb_state");
		$fld_addr_arrr["ext_addr_city"] = func_read_qs("memb_city");
		$fld_addr_arrr["ext_addr_pin"] =func_read_qs("memb_postcode");
		$fld_addr_arrr["ext_addr_contact"] = func_read_qs("memb_tel");
			
		$addr_qry = func_update_qry("memb_ext_addr",$fld_addr_arrr, " where memb_id=".$_SESSION["memb_id"]." and ext_addr_default=1");

		mysqli_query($con, $addr_qry);
		
		
		//if($img_name<>"") $fld_arr["user_img"] = $img_name;
			//$memb_cstn=func_read_qs("memb_cstn");
			$memb_vat=func_read_qs("memb_vat");
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
				//$upload_doc_arr = array();
				//for($i=0; $i<2; $i++){		
					$file_name = $_FILES['files']['name'];
					if($file_name <> ""){
						$file_tmp =$_FILES['files']['tmp_name'];
						$dir = $_SERVER['DOCUMENT_ROOT']."/extras/user_data";
						if(is_dir($dir)==false){
							mkdir($dir,0700);
						}
						$desired_dir=$_SERVER['DOCUMENT_ROOT']."/extras/user_data/id-".$id;
							if(is_dir($desired_dir)==false){
								mkdir($desired_dir, 0700);		// Create directory if it does not exist
							}
							$file_path="";
							$file_path=$_SERVER['DOCUMENT_ROOT']."/extras/user_data/id-".$id."/".$file_name;
							move_uploaded_file($file_tmp,$file_path);
							$imageFileType = strtolower(pathinfo($file_path,PATHINFO_EXTENSION));
							$memb_vat_doc=img_resize_db($file_path, 1200, 980, $imageFileType);
					}else{
							
							$memb_vat_doc="";							

					}
				//}
					//$memb_cst_doc = $upload_doc_arr[0];
					//$memb_vat_doc = $upload_doc_arr[1];
					//$fld_arr["memb_cstn"] = $memb_cstn;
					$fld_arr["memb_vat"] = $memb_vat;
			}
				//if($memb_cst_doc <> "") $fld_arr["memb_cst_doc"] = $memb_cst_doc;
				if($memb_vat_doc <> "") $fld_arr["memb_vat_doc"] = $memb_vat_doc;
				$fld_arr["memb_company"] = $memb_company;
				$qry = func_update_qry("member_mast",$fld_arr," where memb_id=".$id);
				$result = mysqli_query($con,$qry);
		if($result){
				$msg = "The details has been updated successfully";
		}else{
			$msg = "Update failed! Please check details or try again after some time.";
		}	
}

$act = func_read_qs("act");
if($act=="d"){
	if(!mysqli_query($con, "delete from member_mast where memb_id=".func_read_qs("id"))){
		echo("Problem updating database... $qry");
	}else{
		?>
		<script>
			alert("Record Deleted successfully.");
			window.location.href="manage_buyers.php";
		</script>
		<?php
		die("");
	}	
}

if($id<>""){
	$qry = "select * from member_mast where memb_id=".$id;
	if(get_rst($qry, $row)){
		$memb_id = $row["memb_id"];
		$memb_fname = stripcslashes($row["memb_fname"]);
		$memb_title = $row["memb_title"];
		$memb_email = $row["memb_email"];
		$memb_add1 = $row["memb_add1"];
		$memb_add2 = $row["memb_add2"];
		$memb_city = $row["memb_city"];
		$memb_country = $row["memb_country"];
		$memb_postcode = $row["memb_postcode"];
		$memb_state = $row["memb_state"];
		$memb_tel = $row["memb_tel"];
		$memb_pwd = $row["memb_pwd"];
		$memb_act_status = $row["memb_act_status"];
		$sms_verify_status = $row["sms_verify_status"];
		$memb_cstn = $row["memb_cstn"];
		$memb_vat = $row["memb_vat"];
		$memb_cst_doc = $row["memb_cst_doc"];
		$memb_vat_doc = $row["memb_vat_doc"];
		$memb_company = stripcslashes($row["memb_company"]);
	}
}
if(isset($_POST["btn_resend"])){
	if($memb_act_status <> 1){
		$fld_arr = array();
		$fld_arr["act_link"] = get_base_url()."/register.php?id=".$row["memb_act_id"];
		$fld_arr["memb_fname"] = $memb_fname;
		$fld_arr["memb_email"] = $memb_email;
		$mail_body = push_body("buyer_activation.txt",$fld_arr);
		$from = "welcome@Company-Name.com";		
		if(xsend_mail($memb_email,"Company-Name - Buyer account verification email",$mail_body,$from )){
			$msg="Activation link has been sent successfully";
		}else{ 
			$msg="There is a problem to send email to your email address.";
		}
	}
}
if(isset($_POST["btn_porder"])){
	$_SESSION["memb_id"] = $id;
	$_SESSION["memb_fname"] = $memb_fname;
	js_redirect("../index.php");
	die("");
}
?>

<script type="text/javascript" src="frmCheck.js"></script>
<script>
function js_delete(){
	if(confirm("Are you sure you want to delete this record?")){		
		document.frm.act.value="d";
	}else{
		return false;
		
	}
}

function Submit_form(){
	if(chkForm(document.frm)==false)
		return false;
	else
		document.frm.act.value="1";
		document.frm.submit();
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
</script>

<style>

.div_tree{
  position:absolute;
  top:0px;
  left:0px;
  width:100%;
  height:100%;
  background:rgba(200,200,255,0.9);
  
}

.div_inner
{
    position:relative; 
    top:100px;
    height:500px;
    width:800px;
    border: solid 1px #000000;
    background-color:#FFFFFF;
	xoverflow: scroll;
}

.div_inner_tree{
	overflow: scroll;
	background-color:#FFFFFF;
	border:none;
	padding:10px;
}
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

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />



<?php
	if($id){
		$page_head = "Edit Buyer Details";
	}else{
		$page_head = "Create New Buyer";
	}
?>

<h2><?=$page_head?></h2>
<div id="sup_upd_form">
<?php
	if($msg <> "") { ?>
	<div class="alert alert-info">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<?=$msg?>
	</div>
	<?php } ?>
<form name="frm" id="frm" method="post" enctype="multipart/form-data">
	<input type="hidden" id="act" name="act" value="1">
<div class="solidTab panel panel-info">
<div class="panel-heading">Personal Details</div>

	<table width="100%" border="0" cellspacing="1" cellpadding="5">	
		<tr>
		<td>Title</td>
		<td style="width:auto;">
		<select name="memb_title" id="100" title="Title" class="form-control" style="width:auto;">
			<?php func_option("Mr","Mr",func_var($memb_title))?>
			<?php func_option("Mrs","Mrs",func_var($memb_title))?>
			<?php func_option("Ms","Ms",func_var($memb_title))?>
			<?php func_option("Dr","Dr",func_var($memb_title))?>
		</select>
		</td>
		<td>Full Name</td>
		<td><input type="text" size="25" maxlength="50" id="100" title="First Name" name="memb_fname" value="<?=func_var($memb_fname)?>" class="form-control" style="width:auto;"></td>
		</tr>
		<tr>
		<td>Email Address</td>
		<td><input type="text" maxlength="100" id="120" title="Email Address" name="memb_email" value="<?=func_var($memb_email)?>"  size="25" class="form-control" style="width:auto;"></td>
		<td>Contact No.</td>
		<td><input type="text" size="25" maxlength="50" id="100" title="Contact No." name="memb_tel" value="<?=func_var($memb_tel)?>" class="form-control" style="width:auto;"></td>
		
		</tr>
		<tr>
		<td>Password</td>
		<td><input type="password" maxlength="20" id="150" title="Password" name="memb_pwd" value="<?=func_var($memb_pwd)?>" size="25" class="form-control" style="width:auto;"></td>
		<td>Confirm Password</td>
		<td><input type="password" size="25" maxlength="20" id="160" title="Confirm Password" name="memb_cpwd" value="<?=func_var($memb_pwd)?>" class="form-control" style="width:auto;"></td>
		</tr>
	</table>
</div>
<div class="solidTab panel panel-info">
<div class="panel-heading">Address Details</div>
	<table width="100%" border="0" cellspacing="1" cellpadding="5">	
		<tr>
		<td style="padding-right: 25px;">Address 1</td>
		<td><textarea  rows=4 cols=25 maxlength="45" id="100" title="Address 1" name="memb_add1"  class="form-control" style="width:auto;"><?php echo $row["memb_add1"]; ?></textarea></td>
		<td style="padding-left:0px;">Address 2</td>
		<td><textarea  rows=4 cols=25 maxlength="45" id="add2" title="Address 2" name="memb_add2"  class="form-control" style="width:auto;"><?php echo $row["memb_add2"]; ?></textarea></td>
		</tr>
		<td >City</td>
		<td><input type="text" size="25" maxlength="45" id="100" title="First Name" name="memb_city" value="<?=func_var($memb_city)?>" class="form-control" style="width:auto;"></td>
		<td  style="padding-left:0px;">Postcode</td>
		<td><input type="text" size="25" maxlength="10" id="100" title="postcode" name="memb_postcode" value="<?=func_var($memb_postcode)?>"  class="form-control" style="width:auto;"></td>
		</tr>
		<tr>
		<td>State</td>
			<td >
				<select  title="State" name="memb_state"  class="form-control" style="width:150px;">
				<option value="">Select</option>
				<?=create_cbo("select state_name,state_name from state_mast",func_var($memb_state))?>
				</select>
			</td>
			<td  style="padding-left:0px;">Country</td>
			<td>
			<select name="memb_country" class="form-control" style="width:auto;">
				<?php func_option("India","India",func_var($memb_country))?>
			</select>
			</td>
		</tr>
		</table>
</div>
<div class="solidTab panel panel-info">
<div class="panel-heading">Account Details</div>
	
	<table width="100%" border="0" cellspacing="1" cellpadding="5">	
		<tr>
		<td style="width:200px;">Account Activation Status</td>
		<td>
		<select name="memb_act_status" class="form-control" style="width:auto;">
			<?php func_option("Yes","1",func_var($memb_act_status))?>
			<?php func_option("No","0",func_var($memb_act_status))?>
		</select>
		</td>
		
		<td style="width:150px;">SMS Activation Status</td>
		<td>
		<select name="sms_verify_status" class="form-control" style="width:auto;">
			<?php func_option("Active","1",func_var($sms_verify_status))?>
			<?php func_option("Inactive","0",func_var($sms_verify_status))?>
		</select>
		</td>
		</tr>
</table>
</div>	
<div class="solidTab panel panel-info">
<div class="panel-heading">Additional Documents (Optional)</div>
	<table width="100%" border="0" cellspacing="1" cellpadding="5">
		<tr>
			<td>Establishment Name</td>
		<td><input type="text" size="20" maxlength="50" id="100" title="Establishment Name" name="memb_company" value="<?=func_var($memb_company)?>" class="form-control" style="width:auto;"></td>
		
		</tr>
		
		<tr>
			<td>Goods & Service Tax number (GST):</td>
			<td ><input type="textbox" id="vat_n" title="Goods & Service Tax number (GST)" class="form-control textbox-mid" name="memb_vat" maxlength="15" id="100" size="20" class="form-control" style="width:auto;" value="<?=func_var($memb_vat)?>" />
			<?php if($row["memb_vat_doc"] <> ""){ ?>
			<td><img src="../images/checkmark_green.gif" title="File Uploaded"></td>
			<?php } else { ?>
			<?php } ?>
			<td><input name="files[]" onchange="validate_fileupload(this);" type="file" size="40" style="width: 250px;" accept=".png, .jpg, .jpeg, .gif, .tif, .tiff, .bmp"/></td>

			<?php //echo $row["memb_vat_doc"]; ?></b></td>
			<?php if($row["memb_vat_doc"] <> ""){ ?>
			<td><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModal2">Show Document</button></td>
			<?php } ?>
		</tr>
			
	</table>
</div>
		<table width="100%" border="0" cellspacing="1" cellpadding="5">	
		<tr>
		<th colspan="2" id="centered" >
		<input type="submit" class="btn btn-warning" value="Save" name="save" onClick="javascript:return Submit_form();">
		<?php if($id<>""){?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" class="btn btn-warning" value="Delete" name="btn_delete" onclick="javascript: return js_delete();">
		<?php }
			if($memb_act_status <> 1){?>
				<input type="submit" class="btn btn-warning" value="Re-send activation link" name="btn_resend" style="margin-left: 15px">
		<?php }else{ ?>
				<input type="submit" class="btn btn-warning" value="Place Order" name="btn_porder" style="margin-left: 15px">
		<?php } ?>
		</td>
		</tr>
		
	</table>

</form>


<div id="myModal3" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:900px;">
    <!-- Modal content-->
    <div class="modal-content" >
      <div class="modal-body" style=" overflow=scroll;">
	  <center><h1 class="modal-heading">Central Sales Tax Account number (CST):<?=$row["memb_cstn"];?></h1></center>
	  <button class="btn btn-info" id="counterclockwise" onclick="javascript: rotate_counter_clockwise('canvas2');">
		<span class="color-white glyphicon glyphicon-share-alt gly-flip-horizontal"></span> Rotate left</button>
		<button class="btn btn-info" style="float:right;" id="clockwise" onclick="javascript: rotate_clockwise('canvas2');">Rotate right 
		<span class="color-white glyphicon glyphicon-share-alt"></span></button>
		<?php if($row["memb_cst_doc"] == ""){ ?>
		<center><h1>No document image to display</h1></center>
		<?php }else{ ?>			
		<img id="canvas2" src="<?=show_img($row["memb_cst_doc"]);?>" width="100%">
		<?php } ?>
	  </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
	  </div>
    </div>
  </div>
</div>

<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:900px;">
    <!-- Modal content-->
    <div class="modal-content" >
      <div class="modal-body" style=" overflow=scroll;">
	  <center><h1 class="modal-heading">Value Added Tax number (VAT):<?=$row["memb_vat"];?></h1></center>
	  <button class="btn btn-info" id="counterclockwise" onclick="javascript: rotate_counter_clockwise('canvas1');">
		<span class="color-white glyphicon glyphicon-share-alt gly-flip-horizontal"></span> Rotate left</button>
		<button class="btn btn-info" style="float:right;" id="clockwise" onclick="javascript: rotate_clockwise('canvas1');">Rotate right 
		<span class="color-white glyphicon glyphicon-share-alt"></span></button>
		<?php if($row["memb_vat_doc"] == ""){ ?>
		<center><h1>No document image to display</h1></center>
		<?php }else{ ?>			
		<img id="canvas1" src="<?=show_img($row["memb_vat_doc"]);?>" width="100%">
		<?php } ?>
	  </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
	  </div>
    </div>
  </div>
</div>

<?php
require_once("inc_admin_footer.php");

?>
