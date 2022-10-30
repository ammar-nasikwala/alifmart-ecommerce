<?php
$id = func_read_qs("id");
$rfq_no = "";

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
	$rfq_no = "AM";
	$rfq_no = $rfq_no.str_pad($quot_id1,5,'0',STR_PAD_LEFT);
	if($id == ""){
		mysqli_query($con,"insert into request_for_quotation SET rfq_no='".$rfq_no."',customer_email='".$customer_email."',customer_name='".$customer_name."',contact_no='".$contact_no."',creation_date=NOW(),owner_name='".$owner_name."',rfq_description='".$rfq_description."'");
	}else{
		mysqli_query($con,"update request_for_quotation SET customer_email='".$customer_email."',customer_name='".$customer_name."',contact_no='".$contact_no."',owner_name='".$owner_name."',rfq_description='".$rfq_description."' where quotation_id=0$id");
	}	
	if($id == ""){
		$quotation_id = mysqli_insert_id($con);
	}else{
		$quotation_id = $id;
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
			mysqli_query($con,"insert into prod_enquiry SET quotation_id='".$quotation_id."',item_no='".$item_no[$i]."',prod_name='".addslashes($item_desc[$i])."',brand_name='".addslashes($brand_name[$i])."',item_qty='".$item_qty[$i]."',item_price='".$item_price[$i]."',image_path1='".$ima[$i]."',insertdate=NOW(),vendorname='".$vendor_name[$i]."',rfq_status='".$rfq_status[$i]."'");
		}else{
			$upload_image[$i] = image_upload($enq_no,$quotation_id,$k);
			mysqli_query($con,"insert into prod_enquiry SET quotation_id='".$quotation_id."',item_no='".$item_no[$i]."',prod_name='".addslashes($item_desc[$i])."',brand_name='".addslashes($brand_name[$i])."',item_qty='".$item_qty[$i]."',item_price='".$item_price[$i]."',image_path1='".$upload_image[$i]."',insertdate=NOW(),vendorname='".$vendor_name[$i]."',rfq_status='".$rfq_status[$i]."'");
			$k++;
		}
		if($rfq_status[$i]=='Closed'){
			if(get_rst("select quotation_id from quotation_mast where rfq_id=$id")){
				$msg = "Record Saved Successfully.";
			}else{
				$quot_no = "AM/PNQ/".$timestamp."/";
				get_rst("select count(0)+1 as next_code from quotation_mast where quotation_no LIKE '$quot_no%' and quotation_no is NOT null",$row);
				$quotation_no = "AM/PNQ/".$timestamp."/".str_pad($row["next_code"], 4-strlen($row["next_code"]), "0", STR_PAD_LEFT);
				get_rst("select customer_name, customer_email,po_company_id from request_for_quotation where quotation_id=0$id",$row_quotation);
				mysqli_query($con, "insert into quotation_mast set customer_name='".$row_quotation["customer_name"]."',customer_email='".$row_quotation["customer_email"]."',quotation_no='".$quotation_no."',rfq_id='".$id."',po_company_id='".$row_quotation["po_company_id"]."'");
				$quot_id = mysqli_insert_id($con);
				mysqli_query($con,"INSERT INTO quotation(quotation_id, item_no, item_desc, brand_name, qty, price, discount) VALUES ('$quot_id', '1', NULL, NULL, NULL, NULL, NULL)");
			}
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
</style>
<?php
if($id){
	$page_head = "Edit RFQ Details";
}else{
	$page_head = "Create New RFQ";
}
?>
<h2><?=$page_head?></h2>

<form name="frm_req_quotation" id="frm_req_quotation" method="post" enctype="multipart/form-data">
<table border="1" class="table_form">
	<tr>
		<td>Enquiry Description: </td>
		<td><textarea type="text" name="rfq_description" id="rfq_description" class="form-control textbox-lrg" style="height:80px"><?=$rfq_description?></textarea></td>
	</tr>
	<tr>
		<td>Customer Name: </td>
		<td><input type="text" name="customer_name" id="customer_name" class="form-control textbox-lrg" value="<?=$customer_name?>"></td>
	</tr>
	<tr>
		<td>Customer email: </td>
		<td><input type="text" name="customer_email" id="customer_email" class="form-control textbox-lrg" value="<?=$customer_email?>"></td>
	</tr>
	<tr>
		<td>Contact No: </td>
		<td><input type="text" name="contact_no" id="contact_no" class="form-control textbox-lrg" maxlength="10" onkeypress='validate_key(event)' value="<?=$contact_no?>"></td>
	</tr>
	<tr>
		<td>Owner: </td>
		<td><input type="text" name="owner" id="owner" class="form-control textbox-lrg" value="<?=$owner_name?>"></td>
	</tr>
</table>
<table align="center" width="80%"  border="1" class="list" id="req_quotation" style="margin-top: 15px">
		<th align="center" class="table-bg" style="width:10px"><p><strong>Item No.</strong></p></td>
		<th align="center" class="table-bg"><p><strong>Item Description/Specs</strong></p></td>
		<th align="center" class="table-bg"><p><strong>Brand Name</strong></p></td>
		<th align="center" class="table-bg"><p><strong>Quantity</strong></p></td>
		<th align="center" class="table-bg"><p><strong>Asking for Price</strong></p></td>
		<th align="center" class="table-bg"><p><strong>Upload Image</strong></p></td>
		<th align="center" class="table-bg"><p><strong>Status</strong></p></td>
		<th align="center" class="table-bg"><p><strong>Vendor</strong></p></td>
		<td><input type='button' class='check_delivery btn btn-warning' id='add_row' value='+'></td>
		<?php  
			if($id <> ""){
			$num = 0;
			get_rst("select * from prod_enquiry where quotation_id=0$id",$row,$rst);
			do{
				$num++;
				$NewSelected = "";
				$AssignedSelected = "";
				$ProcessingSelected = "";
				$ClosedSelected = "";
		
				switch($row["rfq_status"]) {
					case "New": $NewSelected = "selected"; 
						break;
					case "Assigned": $AssignedSelected = "selected"; 
						break;
					case "Processing": $ProcessingSelected = "selected"; 
						break;
					case "Closed": $ClosedSelected = "selected"; 
						break;					
				}?>
				<tr id="rowCount_<?=$num?>">
					<td><input type='text' name='item_no[]' style="width: 15px;" readonly value="<?=$row["item_no"]?>"></td>
					<td><textarea type="text" name="item_desc[]" id="item_desc" style="width: 150px; height:25px;"><?=$row["prod_name"]?></textarea></td>
					<td><input type="text" name="brand_name[]" id="brand_name" value="<?=$row["brand_name"]?>"></td>
					<td><input type="text" name="item_qty[]" id="item_qty" style="width: 50px " value="<?=$row["item_qty"]?>"></td>
					<td><input type="text" name="item_price[]" id="item_price" style="width: 90px" onkeypress='validate_key(event)' value="<?=$row["item_price"]?>"></td>
					<?php if($row["image_path1"] == ""){?>
						<td><input name="uploadfile[]" type="file" id="uploadfile" size="40" style="width: 170px;" accept=".png, .jpg, .jpeg, .gif" /></td>
					<?php }else{?>
						<td><a href="<?=$row["image_path1"]?>" target="_blank">Show Image</a></td>
					<?php }?>
					<input type="hidden" name="ima_path[]" value="<?=$row["image_path1"]?>">
					<td>
						<select id="rfq_status" name="rfq_status[]">
							<option value="New" <?=$NewSelected?>>New</option>
							<option value="Assigned" <?=$AssignedSelected?>>Assigned</option>
							<option value="Processing" <?=$ProcessingSelected?>>Processing</option>
							<option value="Closed" <?=$ClosedSelected?>>Closed</option>
						<select>
					</td>
					<td><textarea type="text" name="vendor[]" id="vendor" style="width: 120px; height: 25px; "><?=$row["vendorname"]?></textarea></td>
					<td><input type='button' class='check_delivery btn btn-warning' id='del' value='--'></td>
			<?php }while($row = mysqli_fetch_assoc($rst));?>
				</tr>
		<?php }else{$num = 1;?>
				<tr id="rowCount_<?=$num?>">
					<td><input type='text' name='item_no[]' style="width: 15px;" readonly 	value="<?=$num?>"></td>
					<td><textarea type="text" name="item_desc[]" id="item_desc" style="width: 150px; height:25px;"></textarea></td>
					<td><input type="text" name="brand_name[]" id="brand_name"></td>
					<td><input type="text" name="item_qty[]" id="item_qty" style="width: 50px"></td>
					<td><input type="text" name="item_price[]" id="item_price" style="width: 90px" onkeypress='validate_key(event)'></td>
					<td><input name="uploadfile[]" type="file" id="uploadfile" size="40" style="width: 170px;" accept=".png, .jpg, .jpeg, .gif" /></td>
					<td>
						<select id="rfq_status" name="rfq_status[]">
							<option value="New" <?=$NewSelected?>>New</option>
							<option value="Assigned" <?=$AssignedSelected?>>Assigned</option>
							<option value="Processing" <?=$ProcessingSelected?>>Processing</option>
							<option value="Closed" <?=$ClosedSelected?>>Closed</option>
						<select>
					</td>
					<td><textarea type="text" name="vendor[]" id="vendor" style="width: 120px; height: 25px; "></textarea></td>
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