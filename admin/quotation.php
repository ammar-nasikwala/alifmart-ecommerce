<?php
ob_start();
require("inc_admin_header.php");
require("fpdf/fpdf.php");

$id = func_read_qs("id");
$item_no = "";
if(isset($_POST['save_quotation'])){
	
	$customer_email = $_POST["customer_email"];
	$customer_name = addslashes($_POST["customer_name"]);
	//$cust_city = $_POST["customer_city"];
	$item_desc = $_POST["item_desc"]; 
	$brand_name = $_POST["brand_name"];
	$item_qty = $_POST["item_qty"];
	$item_price = $_POST["item_price"];
	$item_disc = $_POST["item_disc"];
	$customer_contact = $_POST["customer_contact"];
	$currentDate = date("j/n/Y");
	$timestamp = date("d-m-Y");
	$comment = $_POST["comment"];
	$sku = $_POST["sku"];
	if($id == ""){
		$quot_no = "AM/PNQ/".$timestamp."/";
		get_rst("select count(0)+1 as next_code from quotation_mast where quotation_no LIKE '$quot_no%' and quotation_no is NOT null",$row);
		$quotation_no = "AM/PNQ/".$timestamp."/".str_pad($row["next_code"], 4-strlen($row["next_code"]), "0", STR_PAD_LEFT);
	}else{
		get_rst("select quotation_no from quotation_mast where quotation_id=0$id",$row_edit);
		$quotation_no = $row_edit["quotation_no"];
	}
	if($id == ""){
		mysqli_query($con,"insert into quotation_mast SET quotation_no='".$quotation_no."',customer_email='".$customer_email."',customer_name='".$customer_name."', customer_contact='".$customer_contact."', quotation_date='".$timestamp."'");
	}else{
		mysqli_query($con,"update quotation_mast SET quotation_no='".$quotation_no."',customer_email='".$customer_email."',customer_name='".$customer_name."', customer_contact='".$customer_contact."', quotation_date='".$timestamp."' where quotation_id=0$id");
	}	
	if($id == ""){
		$quotation_id = mysqli_insert_id($con);
		$_GET["id"] = $quotation_id;
		$id = $_GET["id"];
	}else{
		$quotation_id = $id;
	}
	mysqli_query($con,"delete from quotation where quotation_id=0$id"); // if record exists then delete 

	$item_no = 1;
	$desc = array(); 
	$br_name = array();
	$qty = array();
	$price = array();
	$disc = array();
	$prod_sku = array();
	for($i=0;$i<count($item_desc);$i++){
		if($sku[$i] == ""){
			if(mysqli_query($con,"insert into quotation SET quotation_id='".$quotation_id."',sku=null,item_no='".$item_no."',item_desc='".addslashes($item_desc[$i])."',brand_name='".addslashes($brand_name[$i])."',qty='".$item_qty[$i]."',price='".$item_price[$i]."',discount='".$item_disc[$i]."',comment=null")){
				$msg = "Quotation saved successfully";
			}
		}else{
			if(mysqli_query($con,"insert into quotation SET quotation_id='".$quotation_id."',sku='".$sku[$i]."',item_no='".$item_no."',item_desc='".addslashes($item_desc[$i])."',brand_name='".addslashes($brand_name[$i])."',qty='".$item_qty[$i]."',price='".$item_price[$i]."',discount='".$item_disc[$i]."',comment='".$comment[$i]."'")){
				$msg = "Quotation saved successfully";
			}
		}
		$desc[$i] = $item_desc[$i];
		$br_name[$i] = $brand_name[$i];
		$qty[$i] = $item_qty[$i];
		$price[$i] = $item_price[$i];
		$disc[$i] = $item_disc[$i];
		$prod_sku[$i] = $sku[$i];
		$item_no++;
	}?>
	<script>
		alert("<?php echo $msg; ?>");
	</script>
	<?php 
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
	
	$pdf->SetWidths(array(12,68,35,20,20,25));
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
	
	$quote_dir = "../extras/quotation/";
	if(is_dir($quote_dir)==false){
		mkdir($quote_dir, 0700);
	}
	$dir = $_SERVER['DOCUMENT_ROOT']."/extras/quotation/id-".$quotation_id."/";
	if(is_dir($dir)==false){
		mkdir($dir, 0700);
	}
	$file_name= "Company-Name_Quotation_".stripslashes($customer_name).".pdf";
	$file_path="";
	$file_path="/extras/quotation/id-".$quotation_id."/".$file_name;
	$pdf->Output($dir.$file_name,'F');
	mysqli_query($con,"update quotation_mast set quotation_path='".$file_path."' where quotation_id=$quotation_id");
}	

if(isset($_POST['print_quotation'])){
	if($id == ""){
		$quotation_id = get_max("quotation_mast","quotation_id");
	}else{
		$quotation_id=$id;
	}
	$customer_email = $_POST["customer_email"];
	$customer_name = addslashes($_POST["customer_name"]);
	$item_desc = preg_replace("/'/", '', $_POST["item_desc"]); 
	$brand_name = preg_replace("/'/", '', $_POST["brand_name"]);
	$item_qty = $_POST["item_qty"];
	$item_price = $_POST["item_price"];
	$item_disc = $_POST["item_disc"];
	$currentDate = date("j/n/Y");
	$timestamp = date("d-m-Y");
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
	
	$pdf->SetWidths(array(12,68,35,20,20,25));
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

if(isset($_POST["send_quotation"])){
	
	$customer_email = $_POST["customer_email"];
	$customer_name = addslashes($_POST["customer_name"]);
	$item_desc = preg_replace("/'/", '', $_POST["item_desc"]); 
	$brand_name = preg_replace("/'/", '', $_POST["brand_name"]);
	$item_qty = $_POST["item_qty"];
	$item_price = $_POST["item_price"];
	$item_disc = $_POST["item_disc"];
	$quot_status = 'Sent';
	$currentDate = date("j/n/Y");
	$comment = $_POST["comment"];
	$sku = $_POST["sku"];
	$timestamp = date("d-m-Y");
	if($id == ""){
		$quot_no = "AM/PNQ/".$timestamp."/";
		get_rst("select count(0)+1 as next_code from quotation_mast where quotation_no LIKE '$quot_no%' and quotation_no is NOT null",$row);
		$quotation_no = "AM/PNQ/".$timestamp."/".str_pad($row["next_code"], 4-strlen($row["next_code"]), "0", STR_PAD_LEFT);
	}else{
		get_rst("select quotation_no from quotation_mast where quotation_id=0$id",$row_edit);
		$quotation_no = $row_edit["quotation_no"];
	}
	
	if($customer_email!=""){
		if($id == ""){
			mysqli_query($con,"insert into quotation_mast SET quotation_no='".$quotation_no."',customer_email='".$customer_email."',customer_name='".$customer_name."'");
		}else{
			mysqli_query($con,"update quotation_mast SET quotation_no='".$quotation_no."',customer_email='".$customer_email."',customer_name='".$customer_name."' where quotation_id=0$id");
		}
	}
	if($id == ""){
		$quotation_id = mysqli_insert_id($con);
	}else{
		$quotation_id = $id;
	}
	mysqli_query($con,"delete from quotation where quotation_id=0$id"); // if record exists then delete 
	$item_no = 1;
	for($i=0;$i<count($item_desc);$i++){
		if($sku[$i] == ""){
			mysqli_query($con,"insert into quotation SET quotation_id='".$quotation_id."', sku=null, item_no='".$item_no."',item_desc='".addslashes($item_desc[$i])."',brand_name='".addslashes($brand_name[$i])."',qty='".$item_qty[$i]."',price='".$item_price[$i]."',discount='".$item_disc[$i]."',comment=null");
		}else{
			mysqli_query($con,"insert into quotation SET quotation_id='".$quotation_id."', sku='".$sku[$i]."', item_no='".$item_no."',item_desc='".addslashes($item_desc[$i])."',brand_name='".addslashes($brand_name[$i])."',qty='".$item_qty[$i]."',price='".$item_price[$i]."',discount='".$item_disc[$i]."',comment='".$comment[$i]."'");
		}
		$item_no++;
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
	
	$pdf->SetWidths(array(12,68,35,20,20,25));
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

	$quote_dir = "../extras/quotation/";
	if(is_dir($quote_dir)==false){
		mkdir($quote_dir, 0700);
	}
	$dir = $_SERVER['DOCUMENT_ROOT']."/extras/quotation/id-".$quotation_id."/";
	if(is_dir($dir)==false){
		mkdir("$dir", 0700);
	}
	$file_name= "Company-Name_Quotation_".stripslashes($customer_name).".pdf";
	$file_path="";
	$file_path="/extras/quotation/id-".$quotation_id."/".$file_name;
	$pdf->Output($dir.$file_name,'F');
	$upload_doc = array();
	for($j=0;$j<1;$j++){
		$upload_doc[0] = $dir.$file_name;
	}
	if(get_rst("select po_company_id from quotation_mast where quotation_no='$quotation_no' and po_company_id IS NOT NULL")){
		get_rst("select quotation_id from quotation_mast where quotation_no='".$quotation_no."'",$row);
		get_rst("select price,sku,qty from quotation where quotation_id='".$row["quotation_id"]."'",$row1,$rst1);
		do{
			$flag = 0;
			get_rst("select prod_id from prod_mast where prod_stockno='".$row1['sku']."'",$rowpid);
		
			if(get_rst("select sup_id,sup_company from prod_sup where prod_id='".$rowpid['prod_id']."' and final_price='".$row1['price']."'",$row_supid)){
				$flag = 1;
			}else{
			
				$flag = 0;
				break;
			}
		}while($row1 = mysqli_fetch_assoc($rst1));
	
		if($flag == 1){
			mysqli_query($con,"update quotation_mast set quotation_path='".$file_path."',quot_status='Sent' where quotation_id=$quotation_id");

			$to = $customer_email; 
			$cc = $sales;
			$from = "sales@Company-Name.com"; 
			$subject = "Company-Name Quotation ".$quotation_no; 
			$body = "Dear Sir,<br> Thank you for your enquiry. We are glad to enclose the quotation in the attached file. <br><br>If you need any further details to meet your requirements , please feel free to write or call us on mentioned numbers.";
			$body.= "<br><br> Thanks & Regards, <br> Sales Team- Company-Name.com <br><br> IVR: +91 77 9849 5353  <br>Direct: +91 20 4003 5353";
		
			get_rst("select email,user_name from po_memb_mast where po_company_id=(select po_company_id from quotation_mast where quotation_id=0$id)",$row_email,$rst_email);
			get_rst("select rfq_no from request_for_quotation where quotation_id=(select rfq_id from quotation_mast where quotation_id=0$id)",$row_rfq);
			$url = "http://".$_SERVER["SERVER_NAME"].$file_path;
			do{
				$mail_body = $row_email["user_name"].",<br>Response to your RfQ ".$row_rfq["rfq_no"]." is received. Kindly  <a href='$url'>click here</a> to view the Quotation $quotation_no.";
				$mail_body.="<br><br>Regards,<br><br> Company-Name Briefcase<br>Your Procurement Management System";
				send_mail($row_email["email"],"Company-Name Briefcase - Quotation Generated",$mail_body,$sales);
			}while($row_email = mysqli_fetch_assoc($rst_email));
		
			get_rst("select po_company_id from quotation_mast where quotation_id=0$id",$row_em);
			$po_company_id = $row_em["po_company_id"];
		
			get_rst("select memb_fname,memb_email from member_mast where memb_id=(select owner_id from po_company_mast where po_company_id=$po_company_id)",$row_memb);
		
			$mail_body1 = $row_memb["memb_fname"].",<br>Response to your RfQ ".$row_rfq["rfq_no"]." is received. Kindly  <a href='$url'>click here</a> to view the Quotation $quotation_no.";
			$mail_body1.="<br><br>Regards,<br><br> Company-Name Briefcase<br>Your Procurement Management System";
		
			send_mail($row_memb["memb_email"],"Company-Name Briefcase - Quotation Generated",$mail_body1,$sales);
		
			$mail_body1 = "Sales team,<br>Response to your RfQ ".$row_rfq["rfq_no"]." is received. Kindly  <a href='$url'>click here</a> to view the Quotation $quotation_no.";
			$mail_body1.="<br><br>Regards,<br><br> Company-Name Briefcase<br>Your Procurement Management System";
			send_mail($sales,"Company-Name Briefcase - Quotation Generated",$mail_body1,"noreply@Company-Name.com");
		
			if(multi_attach_mail($to,$subject,$body,$from,$upload_doc,$cc)){
				$msg="Quotation has been sent successfully";
			}else{ 
				$msg="There is some problem to send email.";
			}
		}else{
			$msg = "The product doesnot exists in the system";
		}
	}else{
		mysqli_query($con,"update quotation_mast set quotation_path='".$file_path."',quot_status='Sent' where quotation_id=$quotation_id");

		$to = $customer_email; 
		$cc = $sales;
		$from = "sales@Company-Name.com"; 
		$subject = "Company-Name Quotation ".$quotation_no; 
		$body = "Dear Sir,<br> Thank you for your enquiry. We are glad to enclose the quotation in the attached file. <br><br>If you need any further details to meet your requirements , please feel free to write or call us on mentioned numbers.";
		$body.= "<br><br> Thanks & Regards, <br> Sales Team- Company-Name.com <br><br> IVR: +91 77 9849 5353  <br>Direct: +91 20 4003 5353";
		if(multi_attach_mail($to,$subject,$body,$from,$upload_doc,$cc)){
			$msg="Quotation has been sent successfully";
		}else{ 
			$msg="There is some problem to send email.";
		}
	}?>
<script>
	alert("<?=$msg?>");
	window.location.href="manage_quotation.php";
</script>
<?php }

if($id<> ""){
	get_rst("select * from quotation_mast where quotation_id=0$id",$row_quot);
	$customer_name = $row_quot["customer_name"];
	$customer_email = $row_quot["customer_email"];
	$quotation_no = $row_quot["quotation_no"];
	$cust_city = $row_quot["city"];
	$quotation_date = $row_quot["quotation_date"];
	$customer_contact = $row_quot["customer_contact"];
}
?>
<style>
	.quotation{
		border-top: solid 1px #FFFFFF !important; 
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
<form name="frm_quotation" id="frm_quotation" method="post" enctype="multipart/form-data" action="quotation.php?id=<?=$id?>">
<input type="hidden" value="<?=$id?>" id="quot_id">
<?php if(get_rst("select po_company_id from quotation_mast where quotation_no='$quotation_no' and po_company_id IS NOT NULL",$rb)){?>
<input type="hidden" value="<?=$rb["po_company_id"]?>" id="po_company_id">
<?php }else{?>
<input type="hidden" value="" id="po_company_id">
<?php }?>
<table border="1" class="table_form">
	<tr>
		<td>Customer Name: </td>
		<td><input type="text" name="customer_name" id="customer_name" class="form-control textbox-lrg" value="<?=$customer_name?>"></td>
		<td>Quotaion Date: </td>
		<td><?=$quotation_date;?></td>
	</tr>
	<tr>
		<td>Customer Email: </td>
		<td><input type="text" name="customer_email" id="customer_email" class="form-control textbox-lrg" value="<?=$customer_email?>"></td>
		<td>Customer Contact: </td>
		<td><input type="text" name="customer_contact" id="customer_contact" class="form-control textbox-lrg" value="<?=$customer_contact?>"></td>
	</tr>
	<tr>
	<?php if($id <> ""){ ?>
		<td colspan="4" class="quotation">Quotation No : <?=$quotation_no?></td>
	<?php }else{ 
			if($quotation_no <> ""){?>
				<td colspan="4" class="quotation">Quotation No :<?=$quotation_no?></td>
	<?php 	}
	}?>
	</tr>
</table>
	<table align="center" width="80%"  border="1" class="list" id="quotation" style="margin-top: 15px">
		<th align="center" class="table-bg" style="width:10px"><p><strong>Item No.</strong></p></td>
		<th align="center" class="table-bg"><p><strong>Item Description/Specs</strong></p></td>
		<th align="center" class="table-bg"><p><strong>Brand Name</strong></p></td>
		<!--td align="center" class="table-bg"><p><strong>Item Specification</strong></p></td-->
		<th align="center" class="table-bg"><p><strong>Quantity</strong></p></td>
		<th align="center" class="table-bg"><p><strong>Unit Price</strong></p></td>
		<th align="center" class="table-bg"><p><strong>Discount%</strong></p></td>
		<?php if(get_rst("select po_company_id from quotation_mast where quotation_no='$quotation_no' and po_company_id IS NOT NULL")){?>
		<th align="center" class="table-bg"><p><strong>SKU</strong></p></td>
		<?php if(get_rst("select comment from quotation where quotation_id=0$id",$r)){?>
		<th align="center" class="table-bg"><p><strong>Comment</strong></p></td>
		<?php  }
		}		
		if($id <> ""){
		$num = 0;
		get_rst("select * from quotation where quotation_id=0$id",$row,$rst);
		do{
			$item_desc = $row["item_desc"];
			$num++;?>
			<tr id="rowCount_<?=$num?>">
				<td><?=$num?></td>
				<td><textarea type="text" name="item_desc[]" id="item_desc" style="width: 150px; height:25px;"><?=$item_desc?></textarea></td>
				<td><input type="text" name="brand_name[]" id="brand_name" value="<?=$row["brand_name"]?>"></td>
				<td><input type="text" name="item_qty[]" id="item_qty" style="width: 50px " value="<?=$row["qty"]?>"></td>
				<td><input type="text" name="item_price[]" id="item_price" style="width: 90px" onkeypress='validate_key(event)' value="<?=$row["price"]?>"></td>
				<td><input type="text" name="item_disc[]" id="item_disc" style="width: 30px " onkeypress='validate_key(event)' value="<?=$row["discount"]?>"></td>
				<?php if(get_rst("select po_company_id from quotation_mast where quotation_no='$quotation_no' and po_company_id IS NOT NULL")){?>
				<td><input type="text" name="sku[]" id="sku" style='width: 110px;' value="<?=$row["sku"]?>"></td>
				<td><textarea type="text" name="comment[]" id="comment" style="width: 180px; height:25px;"><?=$row["comment"]?></textarea></td>
				<?php  } ?>
				<td><button class='check_delivery btn btn-warning' onclick="delete_row('rowCount_<?=$num?>')">--</button></td>
		<?php }while($row = mysqli_fetch_assoc($rst));?>
		</tr>
		<?php
		}else{
			$num = 1;
			if($item_no == ""){?>
				<tr id="rowCount_<?=$num?>">
					<td><?=$num?></td>
					<td><textarea type="text" name="item_desc[]" id="item_desc" style="width: 150px; height:25px;"></textarea></td>
					<td><input type="text" name="brand_name[]" id="brand_name"></td>
					<td><input type="text" name="item_qty[]" id="item_qty" style="width: 50px " onkeypress='validate_key(event)'></td>
					<td><input type="text" name="item_price[]" id="item_price" style="width: 90px" onkeypress='validate_key(event)'></td>
					<td><input type="text" name="item_disc[]" id="item_disc" style="width: 30px " onkeypress='validate_key(event)'></td>
					<?php if(get_rst("select po_company_id from quotation_mast where quotation_no='$quotation_no' and po_company_id IS NOT NULL")){?>
					<td><input type="text" name="sku[]" id="sku" style='width: 110px;'></td>
					<?php  } ?>
					<td><button class='check_delivery btn btn-warning' onclick="delete_row('rowCount_<?=$num?>')">--</button></td>
				</tr>
			<?php
			}else{
				for($i=0;$i<$item_no-1;$i++){ ?>
					<tr id="rowCount_<?=$num?>">
						<td><?=$num?></td>
						<td><textarea type="text" name="item_desc[]" id="item_desc" style="width: 150px; height:25px;"><?=func_var($desc[$i])?></textarea></td>
						<td><input type="text" name="brand_name[]" id="brand_name" value="<?=func_var($br_name[$i])?>"></td>
						<td><input type="text" name="item_qty[]" id="item_qty" style="width: 50px " value="<?=func_var($qty[$i])?>"></td>
						<td><input type="text" name="item_price[]" id="item_price" style="width: 90px" onkeypress='validate_key(event)' value="<?=func_var($price[$i])?>"></td>
						<td><input type="text" name="item_disc[]" id="item_disc" style="width: 30px " onkeypress='validate_key(event)' value="<?=func_var($disc[$i])?>"></td>
						<?php if(get_rst("select po_company_id from quotation_mast where quotation_no='$quotation_no' and po_company_id IS NOT NULL")){?>
						<td><input type="text" name="sku[]" id="sku" style='width: 110px;' value="<?=func_var($sku[$i])?>"></td>
						<?php  } ?>
						<td><button class='check_delivery btn btn-warning' onclick="delete_row('rowCount_<?=$num?>')">--</button></td>
					</tr>
					<?php
					$num++;
				}
			}
		} ?>	
	</table>
	<table cellspacing="1" cellpadding="5" align="center">
	<tr>
		<th colspan="10" id="centered">
			<button class="check_delivery btn btn-warning" id="add_row" type="button" onclick="add_new_row();">Add Row</button>
			<input type="submit" class="btn btn-warning" value="Save" name ="save_quotation" onclick="javascript: return validate_fields();">
			<input type="submit" class="btn btn-warning" value="Print" name="print_quotation" formtarget="_blank">
			<input type="submit" class="btn btn-warning" value="Send" name="send_quotation" onclick="javascript: return validate();">
		<?php
			if($id <> ""){?>
				<input type="button" class="btn btn-warning" value="Back" name="Back" onClick="javascript: window.location.href='manage_quotation.php';">
		<?php } ?>
		</th>
	</tr>
	</table>
</form>
</div>
<?php
require_once("inc_admin_footer.php");
?>
<script type="text/javascript">

function add_new_row(){
	var id = document.getElementById("quot_id").value
	$rowno=$("#quotation tr").length;
	if(id == ""){
		if(document.getElementById("po_company_id").value == ""){
			$("#quotation tr:last").after("<tr id='rowCount_"+$rowno+"'><td>"+$rowno+"</td><td><textarea type='text' style='width:150px; height: 25px' name='item_desc[]'/></td><td><input type='text' name='brand_name[]'/></td><td><input type='text' style='width:50px' name='item_qty[]'/></td><td><input type='text' style='width:90px' name='item_price[]' onkeypress='validate_key(event)'/></td><td><input type='text' style='width:30px' name='item_disc[]' onkeypress='validate_key(event)'/></td><td><button class='check_delivery btn btn-warning' onclick=delete_row('rowCount_"+$rowno+"')>--</td></tr>");
			$rowno=$rowno+1;
		}else{
			$("#quotation tr:last").after("<tr id='rowCount_"+$rowno+"'><td>"+$rowno+"</td><td><textarea type='text' style='width:150px; height: 25px' name='item_desc[]'/></td><td><input type='text' name='brand_name[]'/></td><td><input type='text' style='width:50px' name='item_qty[]'/></td><td><input type='text' style='width:90px' name='item_price[]' onkeypress='validate_key(event)'/></td><td><input type='text' style='width:30px' name='item_disc[]' onkeypress='validate_key(event)'/></td><td><input type='text' name='sku[]' id='sku' style='width: 110px;'></td><td><button class='check_delivery btn btn-warning' onclick=delete_row('rowCount_"+$rowno+"')>--</td></tr>");
			$rowno=$rowno+1;
		}
	}else{
		if(document.getElementById("po_company_id").value == ""){
			$("#quotation tr:last").after("<tr id='rowCount_"+$rowno+"'><td>"+$rowno+"</td><td><textarea type='text' style='width:150px; height: 25px' name='item_desc[]'/></td><td><input type='text' name='brand_name[]'/></td><td><input type='text' style='width:50px' name='item_qty[]'/></td><td><input type='text' style='width:90px' name='item_price[]' onkeypress='validate_key(event)'/></td><td><input type='text' style='width:30px' name='item_disc[]' onkeypress='validate_key(event)'/></td><td><button class='check_delivery btn btn-warning' onclick=delete_row('rowCount_"+$rowno+"')>--</td></tr>");
			$rowno=$rowno+1;
		}else{
			$("#quotation tr:last").after("<tr id='rowCount_"+$rowno+"'><td>"+$rowno+"</td><td><textarea type='text' style='width:150px; height: 25px' name='item_desc[]'/></td><td><input type='text' name='brand_name[]'/></td><td><input type='text' style='width:50px' name='item_qty[]'/></td><td><input type='text' style='width:90px' name='item_price[]' onkeypress='validate_key(event)'/></td><td><input type='text' style='width:30px' name='item_disc[]' onkeypress='validate_key(event)'/></td><td><input type='text' name='sku[]' style='width: 110px;'></td><td><textarea type='text' name='comment[]' style='width: 180px; height:25px;'></textarea></td><td><button class='check_delivery btn btn-warning' onclick=delete_row('rowCount_"+$rowno+"')>--</td></tr>");
			$rowno=$rowno+1;
		}
	}
}
var newId;
function delete_row(rowno){
	$rowno=$("#quotation tr").length;
	if($rowno == 2){
		alert("Can not delete a single row");
		return false;
	}else{
		$('#'+rowno).remove();
	}
	$('#quotation tr').each(function() {
        newId =  $(this).index() + 1;
		newId = newId-1;
        $(this).children('#quotation td').first().html(newId);   // Change value in first td
		$(this).attr('rowCount_'+newId, newId);      
    });
}

function validate(){
	var email = document.getElementById('customer_email');
		var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		
			if (!filter.test(email.value)) {
				alert("Please enter valid email.")
				document.getElementById("customer_email").style.borderColor = "red";
				email.focus;
				return false;
			}
			if(document.getElementById("customer_name").value==""){
				alert("Please enter customer name");
				return false;
			}
				display_gif();
				document.frm_quotation.submit();
				return true;
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

function validate_fields(){
	fCode = document.getElementsByName("item_desc[]");

	for ( var i = 0; i < fCode.length; i++ ){
		if ( fCode[i].value == "" ) {
			alert("Please enter product details.");
			fCode[i].focus();
			return false;
		}
	}
	qty = document.getElementsByName("item_qty[]");
	for ( var j = 0; j < qty.length; j++ ){
		if ( qty[j].value == "" || qty[j].value == 0) {
			alert("Please enter quantity.");
			qty[j].focus();
			return false;
		}
	}
	price = document.getElementsByName("item_price[]");
	for ( var k = 0; k < price.length; k++ ){
		if ( price[k].value == "" || price[k].value == 0) {
			alert("Please enter price.");
			price[k].focus();
			return false;
		}
	} 
}
function display_gif(){
				(function(){
			var gif_show = document.getElementById("gif_show")
			var content_hide = document.getElementById("content_hide"),
		show = function(){
			gif_show.style.display = "block";
		},
		hide = function(){
			gif_show.style.display = "none";
		};

	show();
  })();
}
</script>