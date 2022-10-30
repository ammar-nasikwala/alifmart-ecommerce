<?php //saas
$id = func_read_qs("id");
$rfq_no = "";
$flag="";
if(isset($_POST['save_req_quotation'])){
	$item_no = $_POST["item_no"];
	$customer_email = $_POST["customer_email"];
	$customer_name = addslashes($_POST["customer_name"]);
	$contact_no = $_POST["contact_no"];
	$item_desc = $_POST["item_desc"]; 
	$brand_name = $_POST["brand_name"];
	$item_qty = $_POST["item_qty"];
	$item_price = $_POST["item_price"];
	$rfq_status = $_POST["rfq_status"];
	$timestamp = date("Ymd");
	$owner_name = $_POST["owner"];
	$rfq_description = addslashes($_POST["rfq_description"]);
	$ima = $_POST["ima_path"];
	$vendor_name = $_POST["vendor"];
	$quot_id1 = get_max("request_for_quotation","quotation_id"); 
	get_rst("select company_id from po_company_mast where po_company_id=".$_SESSION["po_company_id"],$r1);
	$rfq_no = substr($r1["company_id"],0,3);
	$rfq_no = $rfq_no.str_pad($quot_id1,5,'0',STR_PAD_LEFT);
	if($id == ""){
		if($_SESSION["saas_user"] == 1){
			$user_id = "";
			get_rst("select memb_fname, memb_email from member_mast where memb_id=".$_SESSION["user_id"],$row_cust);
			mysqli_query($con,"insert into request_for_quotation SET rfq_no='".$rfq_no."',customer_email='".$row_cust["memb_email"]."',customer_name='".$row_cust["memb_fname"]."',contact_no='".$contact_no."',creation_date=NOW(),owner_name='".$owner_name."',rfq_description='".$rfq_description."',po_company_id='".$_SESSION["po_company_id"]."',subuser_id='".$user_id."'");
			$flag = 1;
		}else{
			$user_id = $_SESSION["user_id"];
			get_rst("select memb_fname, memb_email from member_mast where memb_id=(select owner_id from po_company_mast where po_company_id='".$_SESSION["po_company_id"]."')",$row_cust);
			mysqli_query($con,"insert into request_for_quotation SET rfq_no='".$rfq_no."',customer_email='".$row_cust["memb_email"]."',customer_name='".$row_cust["memb_fname"]."',contact_no='".$contact_no."',creation_date=NOW(),owner_name='".$owner_name."',rfq_description='".$rfq_description."',po_company_id='".$_SESSION["po_company_id"]."',subuser_id='".$user_id."'");
			$flag = 1;
		}
	}else{
		if($_SESSION["saas_user"] == 1){
			$user_id = "";
			get_rst("select memb_fname, memb_email from member_mast where memb_id=".$_SESSION["user_id"],$row_cust);
			mysqli_query($con,"update request_for_quotation SET customer_email='".$row_cust["memb_email"]."',customer_name='".$row_cust["memb_fname"]."',contact_no='".$contact_no."',owner_name='".$owner_name."',rfq_description='".$rfq_description."',po_company_id='".$_SESSION["po_company_id"]."',subuser_id='".$user_id."' where quotation_id=0$id");
		}else{
			$user_id = $_SESSION["user_id"];
			get_rst("select memb_fname, memb_email from member_mast where memb_id=(select owner_id from po_company_mast where po_company_id='".$_SESSION["po_company_id"]."')",$row_cust);
			mysqli_query($con,"update request_for_quotation SET customer_email='".$row_cust["memb_email"]."',customer_name='".$row_cust["memb_fname"]."',contact_no='".$contact_no."',owner_name='".$owner_name."',rfq_description='".$rfq_description."',po_company_id='".$_SESSION["po_company_id"]."',subuser_id='".$user_id."' where quotation_id=0$id");
		}
	}	
	if($id == ""){
		$quotation_id = mysqli_insert_id($con);
	}else{
		$quotation_id = $id;
	}
if($flag == 1){
	$url=	"http://".$_SERVER["SERVER_NAME"]."/saas/inc_rfq.php?id=$quotation_id";
	if($_SESSION["saas_user"] !=1){
		get_rst("select email from po_memb_mast where id=".$_SESSION["user_id"],$row_subuser);
		$mail_body = $owner_name.",<br>A new request for quotation with ID $rfq_no is created. Kindly <a href='$url'>click here</a> to edit/view the RfQ.";
		$mail_body.="<br><br>Regards,<br>Company-Name Briefcase<br>Your Procurement Management System";
		send_mail($row_subuser["email"],"Company-Name Briefcase-RFQ",$mail_body,$sales);
	}
	$comp_id=$_SESSION["po_company_id"];
	get_rst("select memb_email,memb_fname from member_mast where memb_id=(select owner_id from po_company_mast where po_company_id=$comp_id)",$row_user);
	
	$mail_body1 = $row_user["memb_fname"].",<br>A new request for quotation with ID $rfq_no is created. Kindly <a href='$url'>click here</a> to edit/view the RfQ.";
	$mail_body1.="<br><br>Regards,<br>Company-Name Briefcase<br>Your Procurement Management System";
	send_mail($row_user["memb_email"],"Company-Name Briefcase-RFQ",$mail_body1,$sales);
	$url=	"http://".$_SERVER["SERVER_NAME"]."/admin/inc_rfq.php?id=$quotation_id";
	$mail_body2 = "Sales team,<br>A new request for quotation with ID $rfq_no is created. Kindly <a href='$url'>click here</a> to edit/view the RfQ.";
	$mail_body2.="<br><br>Regards,<br>Company-Name Briefcase<br>Your Procurement Management System";
	send_mail($sales,"Company-Name Briefcase-RFQ",$mail_body2,"noreply@Company-Name.com");
}
function image_upload($enq_no,$quotation_id,$k){	
	if(isset($_FILES['uploadfile'])){
		$errors= array();
		$maxsize    = 204800; //200kb = 1024*200
		$acceptable = array(
			'image/jpeg',
			'image/jpg',
			'image/gif',
			'image/png'
			);
		$pqr = $enq_no-1;
		$file_name = $_FILES['uploadfile']['name'][$k];
		if($file_name <> ""){
			$file_tmp =$_FILES['uploadfile']['tmp_name'][$k];
			$enq_dir = $_SERVER['DOCUMENT_ROOT']."/extras/request_quotation";
			if(is_dir($enq_dir)==false){
				mkdir($enq_dir,0700);
			}
			$desired_dir=$_SERVER['DOCUMENT_ROOT']."/extras/request_quotation/id-".$quotation_id."-".$enq_no;
			if(!in_array($_FILES['uploadfile']['type'][$k], $acceptable) && (!empty($_FILES['uploadfile']['type'][$k]))) {
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
				$file_path="../extras/request_quotation/id-".$quotation_id."-".$enq_no."/".$file_name;
				move_uploaded_file($file_tmp,$file_path);
				$upload_image[$enq_no] = "http://".$_SERVER['SERVER_NAME']."/extras/request_quotation/id-".$quotation_id."-".$enq_no."/".$file_name;
				return $upload_image[$enq_no];
				$act = 1;
			}else{
				$act = 0;
				$msg= "Image upload failed!! ".$errors[0] ;
			}
		}
	}
}
	mysqli_query($con,"delete from prod_enquiry where quotation_id=0$id"); // if record exists then delete 
	$k=0;
	for($i=0;$i<count($item_desc);$i++){
		$enq_no=$item_no[$i];
		if($ima[$i] <> ""){
			mysqli_query($con,"insert into prod_enquiry SET quotation_id='".$quotation_id."',item_no='".$item_no[$i]."',prod_name='".addslashes($item_desc[$i])."',brand_name='".addslashes($brand_name[$i])."',item_qty='".$item_qty[$i]."',item_price='".$item_price[$i]."',image_path1='".$ima[$i]."',insertdate=NOW(),rfq_status='".$rfq_status[$i]."'");
		}else{
			$upload_image[$i] = image_upload($enq_no,$quotation_id,$k);
			mysqli_query($con,"insert into prod_enquiry SET quotation_id='".$quotation_id."',item_no='".$item_no[$i]."',prod_name='".addslashes($item_desc[$i])."',brand_name='".addslashes($brand_name[$i])."',item_qty='".$item_qty[$i]."',item_price='".$item_price[$i]."',image_path1='".$upload_image[$i]."',insertdate=NOW(),rfq_status='".$rfq_status[$i]."'");
			$k++;
		}
		$msg = "Record Saved Successfully.";
		?>
<?php	
	}?>
	<script>
		alert("<?=$msg?>");
		window.location="manage_rfq.php"
	</script>
<?php }	
if($id<> ""){
	get_rst("select * from request_for_quotation where quotation_id=0$id",$row_quot);
	$customer_name = $row_quot["customer_name"];
	$customer_email = $row_quot["customer_email"];
	$quotation_no = $row_quot["quotation_no"];
	$contact_no = $row_quot["contact_no"];
	$owner_name = $row_quot["owner_name"];
	$rfq_description = $row_quot["rfq_description"];
}
?>
<style>
	.quotation{
		border-top: solid 1px #FFFFFF !important; 
		font-size: initial;
	}
	.edit_rfq{
		background-color: #eee;
	}
	#gif_show{
		position:fixed;
		left:0;
		top:0;
		width:100%;
		height:100%;
		z-index:9999;
		background:url(../images/spinner.gif) center no-repeat;
}
</style>
<?php
if($id){
	$page_head = "Edit RFQ Details";
	$access = "readonly";
	$disabled = "disabled";
	$edit_rfq = "class=edit_rfq";
}else{
	$page_head = "Create New RFQ";
	$access = "";
	$disabled = "";
	$edit_rfq = "";
}

if($_SESSION["saas_user"] == 1 || get_rst("select owner_name from request_for_quotation where quotation_id=0$id and owner_name=(select user_name from po_memb_mast where id='".$_SESSION["user_id"]."')")){
	$access = "";
	$disabled = "";
	$edit_rfq = "";
}
?>
<h2><?=$page_head?></h2>
<div id = "gif_show" style="display:none"></div>
<div id="content_hide">
<form name="frm_saas_rfq" id="frm_saas_rfq" method="post" enctype="multipart/form-data">
<input type="hidden" id="req_id" value="<?=$id?>">
<table border="1" class="table_form">
	<tr>
		<td>Enquiry Description: </td>
		<td><textarea type="text" name="rfq_description" id="rfq_description" <?=$access?> class="form-control textbox-lrg" style="height:80px"><?=$rfq_description?></textarea></td>
	</tr>
	<?php if($id == ""){
		if($_SESSION["saas_user"] == 1){
			get_rst("select memb_fname from member_mast where memb_id='".$_SESSION["user_id"]."'",$row_owner)?>
		<tr>
			<td>Owner: </td>
			<td><input type="text" name="owner" readonly id="owner" class="form-control textbox-lrg" value="<?=$row_owner["memb_fname"]?>"></td>
		</tr>
	<?php }else{
			get_rst("select user_name from po_memb_mast where id='".$_SESSION["user_id"]."'",$row_owner);?>
		<tr>
			<td>Owner: </td>
			<td><input type="text" name="owner" readonly id="owner" class="form-control textbox-lrg" value="<?=$row_owner["user_name"]?>"></td>
		</tr>
	<?php }
		}else{ get_rst("select owner_name from request_for_quotation where quotation_id=0$id",$row_owner);?>
		<tr>
			<td>Owner: </td>
			<td><input type="text" name="owner" readonly id="owner" class="form-control textbox-lrg" value="<?=$row_owner["owner_name"]?>"></td>
		</tr>
		<?php }?>
</table>
<table align="center" width="80%"  border="1" class="list" id="req_quotation" style="margin-top: 15px">
		<th align="center" class="table-bg" style="width:10px"><p><strong>Item No.</strong></p></td>
		<th align="center" class="table-bg"><p><strong>Item Description/Specs</strong></p></td>
		<th align="center" class="table-bg"><p><strong>Brand Name</strong></p></td>
		<th align="center" class="table-bg"><p><strong>Quantity</strong></p></td>
		<th align="center" class="table-bg"><p><strong>Asking for Price</strong></p></td>
		<th align="center" class="table-bg"><p><strong>Upload Image</strong></p></td>
		<?php if($id <> ""){?>
		<th align="center" class="table-bg"><p><strong>Status</strong></p></td>
		<?php }?>
		<!--th align="center" class="table-bg"><p><strong>Vendor</strong></p></td-->
		<td><input type='button' class='check_delivery btn btn-warning' id='add_row' value='+' <?=$disabled?> ></td>
		<?php  
			if($id <> ""){
				$num = 0;
				get_rst("select * from prod_enquiry where quotation_id=0$id",$row,$rst);
				do{
					$num++;?>
					<tr id="rowCount_<?=$num?>" <?=$edit_rfq?>>
						<td><input type='text' name='item_no[]' style="width: 15px;" readonly value="<?=$row["item_no"]?>"></td>
						<td><textarea type="text" name="item_desc[]" <?=$access?> id="item_desc" style="width: 150px; height:25px;"><?=$row["prod_name"]?></textarea></td>
						<td><input type="text" name="brand_name[]" <?=$access?> id="brand_name" value="<?=$row["brand_name"]?>"></td>
						<td><input type="text" name="item_qty[]" <?=$access?> id="item_qty" style="width: 50px " value="<?=$row["item_qty"]?>"></td>
						<td><input type="text" name="item_price[]" <?=$access?> id="item_price" style="width: 90px" onkeypress='validate_key(event)' value="<?=$row["item_price"]?>"></td>
						<?php if($row["image_path1"] == ""){?>
							<td><input name="uploadfile[]" type="file" <?=$disabled?> id="uploadfile" size="40" style="width: 170px;" accept=".png, .jpg, .jpeg, .gif" /></td>
						<?php }else{?>
							<td><a href="<?=$row["image_path1"]?>" target="_blank" <?=$access?>>Show Image</a></td>
						<?php }?>
						<input type="hidden" name="ima_path[]" value="<?=$row["image_path1"]?>">
						<td><input type="text" name="rfq_status[]" id="rfq_status" style="width: 90px" value="<?=$row["rfq_status"]?>" readonly></td>
						<td><input type='button' <?=$disabled?> class='check_delivery btn btn-warning' id='del' value='--'></td>
			<?php }while($row = mysqli_fetch_assoc($rst));?>
					</tr>
				<?php
			}else{$num = 1;?>
				<tr id="rowCount_<?=$num?>">
					<td><input type='text' name='item_no[]' style="width: 15px;" readonly 	value="<?=$num?>"></td>
					<td><textarea type="text" name="item_desc[]" id="item_desc" style="width: 150px; height:25px;"></textarea></td>
					<td><input type="text" name="brand_name[]" id="brand_name"></td>
					<td><input type="text" name="item_qty[]" id="item_qty" style="width: 50px "></td>
					<td><input type="text" name="item_price[]" id="item_price" style="width: 90px" onkeypress='validate_key(event)'></td>
					<td><input name="uploadfile[]" type="file" id="uploadfile" size="40" style="width: 170px;" accept=".png, .jpg, .jpeg, .gif" /></td>
					<!--td><input type="text" name="rfq_status[]" id="rfq_status" style="width: 90px" value="New" readonly></td-->
					<td><input type='button' class='check_delivery btn btn-warning' id='del' value='--'></td>
			</tr>
<?php	}?>	
</table>
<table cellspacing="1" cellpadding="5" align="center">
<tr>
		<th colspan="10" id="centered">
		<input type="submit" class="btn btn-warning" value="Save" name ="save_req_quotation" onclick="javascript: return validate();">
	<?php
			if($id <> ""){?>
				<input type="button" class="btn btn-warning" value="Back" name="Back" onClick="javascript: window.location.href='manage_rfq.php';">
	<?php 	}?>
		</td>
</tr>
</table>
</form>
</div>