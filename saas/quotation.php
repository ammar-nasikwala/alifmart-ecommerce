<?php //saas
ob_start();
require("inc_admin_header.php");
require("../admin/fpdf/fpdf.php");
global $header_image;
$id = func_read_qs("id");
$item_no = "";
if(isset($_POST['review_quotation'])){
	
	$customer_name = addslashes($_POST["customer_name"]);
	$item_desc = $_POST["item_desc"]; 
	$brand_name = $_POST["brand_name"];
	$item_qty = $_POST["item_qty"];
	$item_price = $_POST["item_price"];
	$item_disc = $_POST["item_disc"];
	$comment = $_POST["comment"];
	$currentDate = date("j/n/Y");
	$timestamp = date("Ymd");
	
	get_rst("select email,user_name from po_memb_mast where po_company_id=".$_SESSION["po_company_id"],$row_email,$rst_email);
	$quotation_no = $r["quotation_no"];
	
	$item_no = 1;
	$desc = array(); 
	$br_name = array();
	$qty = array();
	$price = array();
	$disc = array();
	for($i=0;$i<count($item_desc);$i++){
		mysqli_query($con,"update quotation set comment='".$comment[$i]."' where quotation_id=0$id and item_no='".$item_no."'");
		$msg = "Quotation updated successfully";
		
		$url = "http://".$_SERVER["SERVER_NAME"]."/admin/quotation.php?id=$id";
		
		$mail_body = "Sales Team,<br>PO against quotation ID $quotation_no is sent for review. Kindly <a href='$url'>click here</a> to view the comments.";
		$mail_body.="<br><br>Regards,<br><br> Company-Name Briefcase<br>Your Procurement Management System";
		send_mail($sales,"Company-Name Briefcase - PO Genertaed",$mail_body,"noreply@Company-Name.com");
		$item_no++;
	}?>
	<script>
		alert("<?=$msg?>");
	</script>
	<?php 	
}
if(isset($_POST["generate"])){
	$customer_name = addslashes($_POST["customer_name"]);
	$item_desc = $_POST["item_desc"]; 
	$brand_name = $_POST["brand_name"];
	$item_qty = $_POST["item_qty"];
	$item_price = $_POST["item_price"];
	$item_disc = $_POST["item_disc"];
	$comment = $_POST["comment"];
	$po_no = str_replace(' ', '',$_POST["po_number"]);
	$po_no1 = str_replace(' ', '',$_POST["po_number1"]);
	$currentDate = date("Y-m-d");
	$timestamp = date("Ymd");
	if(isset($_FILES['upload_head'])){
		$errors= array();
		$maxsize    = 204800; //200kb = 1024*200
		$acceptable = array(
			'image/jpeg',
			'image/jpg',
			'image/gif',
			'image/png'
			);
		$file_name = $_FILES['upload_head']['name'];
		if($file_name <> ""){
			$file_tmp =$_FILES['upload_head']['tmp_name'];
			$enq_dir = $_SERVER['DOCUMENT_ROOT']."/saas/po_document";
			if(is_dir($enq_dir)==false){
				mkdir($enq_dir,0700);
			}
			$desired_dir=$_SERVER['DOCUMENT_ROOT']."/saas/po_document/letterhead";
			if(is_dir($desired_dir)==false){
				mkdir($desired_dir,0700);
			}
			$req_dir = $_SERVER['DOCUMENT_ROOT']."/saas/po_document/letterhead/id-".$_SESSION["po_company_id"];
			if(is_dir($req_dir)==false){
				mkdir($req_dir, 0700);		// Create directory if it does not exist
			}	
			$file_path="";
			$file_path=$req_dir."/".$file_name;
			move_uploaded_file($file_tmp,$file_path);
			$upload_image = "http://".$_SERVER['SERVER_NAME']."/saas/po_document/letterhead/id-".$_SESSION["po_company_id"]."/".$file_name;
			execute_qry("update po_company_mast set letterhead_image='".$file_path."' where po_company_id='".$_SESSION["po_company_id"]."'");
			$act = 1;
		}else{
			$act = 0;
		}
	}
	$tax = array();
	$net_price = array();
	$total = 0;
	$price = 0;
	$i = 0;
	$disc_amt = 0;
	$disc_per = 0;
	$unit_price = 0;
	if($po_no <> ""){
		$po_number = $po_no;
	}else{
		$po_number = $po_no1;
	}
	get_rst("select owner_id from po_company_mast where po_company_id=".$_SESSION["po_company_id"],$own);
	get_rst("select quotation_no from quotation_mast where quotation_id=0$id",$r);
	get_rst("select price,sku,qty,discount from quotation where quotation_id=0$id",$row,$rst);
	do{
		get_rst("select prod_tax_percent from prod_mast where prod_stockno='".$row["sku"]."'",$row_tax);
		$tax[$i] = $row_tax["prod_tax_percent"];
		$net_price[$i] = (($row["price"] + (floatval($row["price"]) * floatval($row_tax["prod_tax_percent"])/100))*$row["qty"]) - ((($row["price"] * $row["qty"]) * $row["discount"])/100);
		$total = ($row["price"] + (floatval($row["price"]) * floatval($row_tax["prod_tax_percent"])/100))*$row["qty"];
		$price = $price + $total;
		$disc_amt = $disc_amt + ((($row["price"] * $row["qty"]) * $row["discount"])/100);
		$grand_total = $price - $disc_amt;
		$unit_price = $unit_price + ($row["price"] * $row["qty"]);
		$i++;
	}while($row = mysqli_fetch_assoc($rst));
	
	$pdf=new PDF_MC_Table();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',12);
	$pdf->SetLeftMargin(14);
	$pdf->Cell(40,5,'PO No: '.strtoupper($po_number));
	$pdf->Cell(140,5,'Date '.date("j/n/Y"),0,'','R');
	$pdf->ln();
	$pdf->Cell(40,5,'Quotation no: '.$r["quotation_no"]);
	$pdf->ln();
	$pdf->SetWidths(array(10,68,25,12,20,12,15,23));
	$pdf->ln(8);
	$pdf->SetFont('Arial','B',12);
	$pdf->Row(array('No.','Item Description/Specs','Brand','Qty','Rs','Tax','Disc%','Net price'));
	$item_no = 1;
	for($i=0;$i<count($item_desc);$i++){
		$pdf->SetFont('Arial','',12);
			$pdf->Row(array($item_no,$item_desc[$i],$brand_name[$i],$item_qty[$i],$item_price[$i],$tax[$i],$item_disc[$i],$net_price[$i]));
			$item_no++;
	}
	$pdf->Cell(132,10,'Grand Total',1,0,'R');
	$pdf->Cell(53,10,formatNumber($grand_total,2),1,0,'R');
	$pdf->ln(12);

	get_rst("select memb_company,memb_fname,memb_email from member_mast where memb_id=".$own["owner_id"],$own_comp);
	$pdf->ln(6);
	$pdf->Cell(180,10,$own_comp["memb_company"],0,0,'L');
	$pdf->ln(10);
	$pdf->Cell(180,10,'Authorised Signatory',0,0,'L');
	
	
	$quote_dir = $_SERVER['DOCUMENT_ROOT']."/saas/po_document/po";
	if(is_dir($quote_dir)==false){
		mkdir($quote_dir, 0700);
	}
	$dir = $quote_dir."/company_id-".$_SESSION["po_company_id"];
	if(is_dir($dir)==false){
		mkdir($dir, 0700);
	}
	$req_dir = $dir."/id-".$id;
	if(is_dir($req_dir)==false){
		mkdir($req_dir, 0700);
	}
	$file_name= "Company-Name_PO_".stripslashes($customer_name).".pdf";
	$file_path="";
	$file_path1 = "/saas/po_document/po/company_id-".$_SESSION["po_company_id"]."/id-".$id."/".$file_name;
	
	$file_path=$req_dir."/".$file_name;
	
	$pdf->Output($file_path,'F');
	
	execute_qry("insert into po_details SET quotation_id='".$id."',po_no='".strtoupper($po_number)."',po_update_date=NOW(),po_path='".$file_path1."',po_company_id='".$_SESSION["po_company_id"]."'");
	get_rst("select email,user_name from po_memb_mast where po_company_id=".$_SESSION["po_company_id"],$row_email,$rst_email);
	$quotation_no = $r["quotation_no"];
	$url = "http://".$_SERVER["SERVER_NAME"].$file_path1;
	do{
		$mail_body = $row_email["user_name"].",<br>PO against quotation ID $quotation_no is created. Kindly <a href='$url'>click here</a> to approve/view the PO with ID $po_number.";
		$mail_body.="<br><br>Regards,<br><br> Company-Name Briefcase<br>Your Procurement Management System";
		send_mail($row_email["email"],"Company-Name Briefcase - PO Genertaed",$mail_body,$sales);
	}while($row_email = mysqli_fetch_assoc($rst_email));
	
	$mail_body = $own_comp["memb_fname"].",<br>PO against quotation ID $quotation_no is created. Kindly <a href='$url'>click here</a> to approve/view the PO with ID $po_number.";
	$mail_body.="<br><br>Regards,<br><br> Company-Name Briefcase<br>Your Procurement Management System";
	send_mail($own_comp["memb_email"],"Company-Name Briefcase - PO Genertaed",$mail_body,$sales);
	
	$mail_body1 = "Sales Team,<br>PO against quotation ID $quotation_no is created. Kindly <a href='$url'>click here</a> to approve/view the PO with ID $po_number.";
	$mail_body1.="<br><br>Regards,<br><br> Company-Name Briefcase<br>Your Procurement Management System";
	send_mail($sales,"Company-Name Briefcase - PO Genertaed",$mail_body1,"noreply@Company-Name.com");
	js_alert("PO request generated successfully");
}

if(isset($_POST['print_quotation'])){
	$_SESSION["header_image"] = 1;
	if($id == ""){
		$quotation_id = get_max("quotation_mast","quotation_id");
	}else{
		$quotation_id=$id;
	}
	$customer_name = addslashes($_POST["customer_name"]);
	$item_desc = preg_replace("/'/", '', $_POST["item_desc"]); 
	$brand_name = preg_replace("/'/", '', $_POST["brand_name"]);
	$item_qty = $_POST["item_qty"];
	$item_price = $_POST["item_price"];
	$item_disc = $_POST["item_disc"];
	$currentDate = date("j/n/Y");
	$timestamp = date("Ymd");
	if($id == ""){
		$quot_no = "AM/PNQ/".$timestamp."/";
		get_rst("select count(0)+1 as next_code from quotation_mast where quotation_no LIKE '$quot_no%' and quotation_no is NOT null",$row);
		$quotation_no = "AM/PNQ/".$timestamp."/".str_pad($row["next_code"], 4-strlen($row["next_code"]), "0", STR_PAD_LEFT);
	}else{
		get_rst("select quotation_no from quotation_mast where quotation_id=0$id",$row_edit);
		$quotation_no = $row_edit["quotation_no"];
	}
	$pdf=new PDF_MC_Table();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',12);
	$pdf->SetLeftMargin(14);
	$pdf->Cell(40,3,'Quotation No: '.$quotation_no);
	$pdf->Cell(140,3,'Date: '.$currentDate,0,'','R');
	$pdf->SetFont('Arial','B',18);
	$pdf->ln(15);
	//Creating a table in pdf
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(40,9,'Dear'." ".stripslashes($customer_name).',');
	$pdf->ln();
	$pdf->Cell(40,5,'Thank you for your enquiry');
	$pdf->ln();
	$pdf->Cell(40,6,'We are pleased to quote you the following rates for your precious enquiry:');
	
	$pdf->SetWidths(array(12,68,35,20,27,25));
	$pdf->ln(8);
	$pdf->SetFont('Arial','B',12);
	$pdf->Row(array('Item','Item Description/Specs','Brand','Qty','Unit Price','Discount%'));
	$item_no = 1;
	for($i=0;$i<count($item_desc);$i++){
		$pdf->SetFont('Arial','',12);
			$pdf->Row(array($item_no,$item_desc[$i],$brand_name[$i],$item_qty[$i],$item_price[$i],$item_disc[$i]));
			$item_no++;
	}
	$pdf->ln();
	$pdf->MultiCell(0,5,'We will be happy to supply any further information you may need and you can trust us to get best Products at best Prices.');
	$pdf->ln();
	$pdf->Cell(40,10,'Thanking You.');
	$pdf->ln(8);
	$pdf->Cell(40,10,'Yours Truly,');
	$pdf->SetFont('Arial','B',12);
	$pdf->ln();
	$pdf->Cell(40,10,'Sales Team, Company-Name');
	$pdf->ln();
	$pdf->Cell(24,10,'Disclaimer:');
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(40,10,'The above rates are exclusive of Taxes, logistics charges may apply.');
	$pdf->ln(16);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(0,5,'This is Computer generated document & hence do not need any signature.');
	
	ob_end_clean();
	$content = $pdf->Output();
}

if($id<> ""){
	get_rst("select * from quotation_mast where quotation_id=0$id",$row_quot);
	$customer_name = $row_quot["customer_name"];
	$customer_email = $row_quot["customer_email"];
	$quotation_no = $row_quot["quotation_no"];
	$cust_city = $row_quot["city"];
}
?>
<style>
	.quotation{
		//border-top: solid 1px #FFFFFF !important; 
		font-size: initial;
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
	$page_head = "Edit Quotation Details";
}else{
	$page_head = "Create New Quotation";
}
?>
<h2><?=$page_head?></h2>
<div id = "gif_show" style="display:none"></div>
<div id="content_hide">
<?php if($_SESSION["saas_user"] == 1 || $_SESSION["saas_user"] == 2){?>
<form name="frm_quotation" id="frm_quotation" method="post" enctype="multipart/form-data" action="quotation.php?id=<?=$id?>">
	
<table border="1" class="table_form">
	<tr>
		<td style="width: 170px" class="quotation">Customer Name: </td>
		<td><input type="text" name="customer_name" id="customer_name" class="form-control textbox-lrg" readonly value="<?=$customer_name?>"></td>
	</tr>
	<tr>
		<!--td>Customer email: </td>
		<td><input type="text" name="customer_email" id="customer_email" class="form-control textbox-lrg" readonly value="<?//=$customer_email?>"></td-->
	<?php if($id <> ""){?>
		<td class="quotation">Quotation No : </td><td class="quotation"><?=$quotation_no?></td>
	<?php }else{ 
				if($quotation_no <> ""){?>
					<td class="quotation">Quotation No :</td><td class="quotation"><?=$quotation_no?></td>
	<?php 		}
			}?>
	</tr>
</table>
<table align="center" width="80%"  border="1" class="list" id="quotation" style="margin-top: 15px">
		<th align="center" class="table-bg" style="width:10px"><p><strong>Item No.</strong></p></td>
		<th align="center" class="table-bg"><p><strong>Item Description/Specs</strong></p></td>
		<th align="center" class="table-bg"><p><strong>Brand Name</strong></p></td>
		<th align="center" class="table-bg"><p><strong>Quantity</strong></p></td>
		<th align="center" class="table-bg"><p><strong>Unit Price</strong></p></td>
		<th align="center" class="table-bg"><p><strong>Discount%</strong></p></td>
		<th align="center" class="table-bg"><p><strong>Comment</strong></p></td>
		<?php  
			$num = 0;
			get_rst("select * from quotation where quotation_id=0$id",$row,$rst);
			do{
				$item_desc = $row["item_desc"];
				$num++;?>
				<tr id="rowCount_<?=$num?>">
					<td><?=$num?></td>
					<td><textarea type="text" name="item_desc[]" readonly id="item_desc" style="width: 150px; height:25px;"><?=$item_desc?></textarea></td>
					<td><input type="text" name="brand_name[]" readonly id="brand_name" value="<?=$row["brand_name"]?>"></td>
					<td><input type="text" name="item_qty[]" readonly id="item_qty" style="width: 50px " value="<?=$row["qty"]?>"></td>
					<td><input type="text" name="item_price[]" readonly id="item_price" style="width: 90px" onkeypress='validate_key(event)' value="<?=$row["price"]?>"></td>
					<td><input type="text" name="item_disc[]" readonly id="item_disc" style="width: 30px " onkeypress='validate_key(event)' value="<?=$row["discount"]?>"></td>
					<?php if(get_rst("select quotation_id from po_details where quotation_id=0$id and quotation_id is not null")){?>
						<td><textarea type="text" name="comment[]" id="comment" readonly style="width: 180px; height:25px;"><?=$row["comment"]?></textarea></td>
					<?php }else{?>
						<td><textarea type="text" name="comment[]" id="comment" style="width: 180px; height:25px;"><?=$row["comment"]?></textarea></td>
					<?php }?>
			<?php }while($row = mysqli_fetch_assoc($rst));?>
				</tr>
</table>
<table cellspacing="1" cellpadding="5" align="center">
<tr>
		<th colspan="10" id="centered">
		<?php if(!get_rst("select quotation_id from po_details where quotation_id=0$id")){?>
		<input type="submit" class="btn btn-warning" value="Review" name ="review_quotation" onclick="javascript: return review_quot();">
		<?php }?>
		<input type="submit" class="btn btn-warning" value="Print" name="print_quotation" formtarget="_blank">
	<?php if($_SESSION["saas_user"] == 2 || $_SESSION["saas_user"] == 1 ){
			if(!get_rst("select quotation_id from po_details where po_company_id='".$_SESSION["po_company_id"]."' and quotation_id='$id'")){
				if(!get_rst("select letterhead_image from po_company_mast where po_company_id='".$_SESSION["po_company_id"]."' and letterhead_image is not null")){?>
					<input type="button" class="btn btn-warning" value="Generate PO" name="generate_po" data-toggle="modal" data-target="#mymodal">
		<?php	}else{?>
					<input type="button" class="btn btn-warning" value="Generate PO" name="generate_po1" data-toggle="modal" data-target="#mymodal1">
		<?php 		}
			}
		}
			if($id <> ""){?>
				<input type="button" class="btn btn-warning" value="Back" name="Back" onClick="javascript: window.location.href='manage_quotation.php';">
	<?php 	}?>
		</td>
</tr>
</table>
<div id="mymodal" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width:500px;">
    <!-- Modal content-->
		<div class="modal-content" style="height:180px;">
			 <div class="modal-body" style=" overflow=scroll;">
				<h1 class="modal-heading" style="color:#f0ad4e !important;">Enter Details<img src="../../images/close_header.gif" width="12px" height="12px" alt="close" align="right" data-dismiss="modal"></h1>
				<center>
				<br>
				<table class="modeltd" width="100%">
				<tr>
				<td align="center">Upload Lettterhead Image</td>
				<td><input type="file" name="upload_head" id="upload_head" accept=".png,.jpeg,.jpg,.gif"/>
				</td>
				</tr>
				<tr>
				<td align="center">Enter Purchase Order No</td>
				<td><input type="text" name="po_number" id="po_number"  style="text-transform: uppercase">
				</tr>
				<tr>
					<td align="center">
					<input name="generate" type="submit" class="btn btn-warning"  value="Generate" onclick="javascript: return validate_img();"/>&nbsp;</td>
				</tr>	
				</table>
			</div>
		</div>
	</div>	
</div>	
<div id="mymodal1" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width:500px;">
    <!-- Modal content-->
		<div class="modal-content" style="height:150px;">
			 <div class="modal-body" style=" overflow=scroll;">
				<h1 class="modal-heading" style="color:#f0ad4e !important;">Enter Details<img src="../../images/close_header.gif" width="12px" height="12px" alt="close" align="right" data-dismiss="modal"></h1>
				<center>
				<br>
				<table class="modeltd" width="100%">
				<tr>
				<td align="center">Enter Purchase Order No</td>
				<td><input type="text" name="po_number1" id="po_number1" style="text-transform: uppercase">
				</td>
				</tr>
				<tr>
					<td align="center">
					<input name="generate" type="submit" class="btn btn-warning"  value="Generate" onclick="javascript: return validate_po();"/>&nbsp;</td>
				</tr>	
				</table>
			</div>
		</div>
	</div>	
</div>	
</form>
</div>
<?php }else{ get_rst("select quotation_path from quotation_mast where quotation_id=0$id",$row1)?>
		<iframe src="http://<?php echo $_SERVER["SERVER_NAME"]."/".$row1["quotation_path"];?>" width="100%" height="500px"></iframe>
<?php }
require_once("inc_admin_footer.php");
?>
<script type="text/javascript" src="../lib/ajax.js"></script>
<script type="text/javascript">

function validate_img(){
	
	var fuData = document.getElementById('upload_head');
    var FileUploadPath = fuData.value;
    if (FileUploadPath == '') {
        alert("Please upload an image");
		return false;
    }else{
        var Extension = FileUploadPath.substring(
        FileUploadPath.lastIndexOf('.') + 1).toLowerCase();
		if (Extension == "gif" || Extension == "png" || Extension == "bmp"|| Extension == "jpeg" || Extension == "jpg") {
			if (fuData.files && fuData.files[0]) {
                var size = (fuData.files[0].size)/1024;
                if(size > 200){
                    alert("File size must be less than 200Kb");
                    return false;
                }
			}
		}else{
			alert("Photo only allows file types of GIF, PNG, JPG, JPEG and BMP. ");
			return false;
		}
    }
	
	
	if(document.getElementById("po_number").value == ""){
		alert("Please enter Purchase Order Number.");
		return false;
	}
	display_gif();
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
function validate_po(){
	if(document.getElementById("po_number1").value == ""){
		alert("Please enter Purchase Order Number.");
		return false;
	}
}
function review_quot(){
	comment = document.getElementsByName("comment[]");
	var flag = 0;
	for ( var l = 0; l < comment.length; l++ ){
		if ( comment[l].value != "") {
			flag = 1;
			break;
		}
	}
	if(flag != 1){
		alert("Please enter comments for review");
		return false;
	}
}

</script>