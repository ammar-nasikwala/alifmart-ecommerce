<?php //saas
require("inc_admin_header.php");

$id = func_read_qs("id");
$item_no = "";
$total = 0;
$price = 0;
$disc_amt = 0;
$disc_per = 0;
$unit_price = 0;

if(isset($_POST["accept_po"])){
	
	get_rst("select price,sku,qty,discount from quotation where quotation_id=0$id",$row,$rst);
	get_rst("select memb_postcode from member_mast where memb_id=".$_SESSION["user_id"],$row_membpin);
	$_SESSION["memb_pin"] = $row_membpin["memb_postcode"];
	do{
		get_rst("select prod_tax_percent from prod_mast where prod_stockno='".$row["sku"]."'",$row_tax);    
		$total = ($row["price"] + (floatval($row["price"]) * floatval($row_tax["prod_tax_percent"])/100))*$row["qty"];
		$price = $price + $total;
		$disc_amt = $disc_amt + ((($row["price"] * $row["qty"]) * $row["discount"])/100);
		//$grand_total = $price - $disc_amt;
		$unit_price = $unit_price + ($row["price"] * $row["qty"]);        // for coupon creation
		
		get_rst("select prod_id,prod_thumb1 from prod_mast where prod_stockno='".$row['sku']."'",$rowpid);
		
		if(get_rst("select sup_id,sup_company from prod_sup where prod_id='".$rowpid['prod_id']."' and final_price='".$row['price']."'",$row_supid)){
  
			get_rst("select sup_alias from sup_mast where sup_id='".$row_supid['sup_id']."'",$alias);
			get_rst("select owner_id from po_company_mast where po_company_id='".$_SESSION["company_id"]."'",$rowmembid);
			add_to_basket($rowpid['prod_id'],$alias['sup_alias'],$rowpid['prod_thumb1'],$row['price'],$row['qty'],$row_supid['sup_id'],"0",$rowmembid['owner_id']);
		}else{
			js_alert("Something went wrong. Please contact Company-Name Team.");
			js_redirect("manage_po.php");
			die();
		}
 
	}while($row=mysqli_fetch_assoc($rst));
	
	$disc_per = $disc_amt/$unit_price * 100;
	get_rst("select po_no from po_details where quotation_id=0$id",$row_edit);
	$po_no = $row_edit["po_no"];
	if($disc_per > 0){
		$fld_arr = array();
		$coupon_id = get_max("offer_coupon","coupon_id");	
		$fld_arr["coupon_code"] = strtoupper($po_no).$coupon_id;
		$fld_arr["min_ord_value"] = $unit_price;
		$fld_arr["disc_per"] = ceil($disc_per);
		$fld_arr["active"] = 1;
		$from_date = date("d-m-Y");
		$till_date = date("d-m-Y", strtotime("$today +1 month"));
		$fld_arr["valid_from"] = $from_date;
		$fld_arr["valid_till"] = $till_date;
		$fld_arr["max_discount_value"] = $disc_amt;
			
		$fld_arr["memb_id"] = $own["owner_id"];

		$qry = func_insert_qry("offer_coupon",$fld_arr);
		execute_qry($qry);
	}
	get_rst("select cart_id from cart_items where memb_id=".$_SESSION["user_id"],$row_cart);
	execute_qry("update po_details set po_status='Approved',po_update_date=NOW(),cart_id='".$row_cart["cart_id"]."' where quotation_id=0$id");
	
	get_rst("select email,user_name,role_id from po_memb_mast where po_company_id=".$_SESSION["company_id"],$row_email,$rst_email);
	
		$url = "http://".$_SERVER["SERVER_NAME"]."/basket.php";
		do{
			if($row_email["role_id"] == 3){
				$mail_body = $row_email["user_name"].",<br>PO with ID $po_no is approved. Kindly  <a href='$url'>click here</a> to make payment/view the Cart items corresponding to the PO.";
				if(get_rst("select coupon_code from offer_coupon where coupon_code='".$fld_arr["coupon_code"]."' and max_discount_value !=0")){
					$mail_body.="<br>Please apply Coupon Code: ".$fld_arr["coupon_code"]." to avail discount.";
				}
				$mail_body.="<br><br>Regards,<br><br> Company-Name Briefcase<br>Your Procurement Management System";
				send_mail($row_email["email"],"Company-Name Briefcase - PO Approved",$mail_body,$sales);
			}else{
				$mail_body = $row_email["user_name"].",<br>PO with ID $po_no is approved.";
				$mail_body.="<br><br>Regards,<br><br> Company-Name Briefcase<br>Your Procurement Management System";
				send_mail($row_email["email"],"Company-Name Briefcase - PO Approved",$mail_body,$sales);
			}
		}while($row_email = mysqli_fetch_assoc($rst_email));
			
		get_rst("select memb_fname,memb_email from member_mast where memb_id=".$_SESSION["user_id"],$own_comp);
			
		$mail_body1 = $own_comp["memb_fname"].",<br>PO with ID $po_no is approved. Kindly  <a href='$url'>click here</a> to make payment/view the Cart items corresponding to the PO.";
		$mail_body1.="<br><br>Regards,<br><br> Company-Name Briefcase<br>Your Procurement Management System";
		send_mail($own_comp["memb_email"],"Company-Name Briefcase - PO Approved",$mail_body1,$sales);
		
		$mail_body2 = "Sales Team,<br>PO with ID $po_no is approved. Kindly  <a href='$url'>click here</a> to make payment/view the Cart items corresponding to the PO.";
		$mail_body2.="<br><br>Regards,<br><br> Company-Name Briefcase<br>Your Procurement Management System";
		send_mail($sales,"Company-Name Briefcase - PO Approved",$mail_body2,"noreply@Company-Name.com");
	
	js_alert("PO approved successfully and items added to the cart");
	js_redirect("manage_po.php");
}
if(isset($_POST["reject_po"])){
	get_rst("select po_no from po_details where quotation_id=0$id",$row_edit);
	$po_no = $row_edit["po_no"];
	$reason_for_reject = $_POST["reason_for_reject"];
	execute_qry("update po_details set po_status='Rejected',po_update_date=NOW(), reason_for_reject ='".$reason_for_reject."' where quotation_id=0$id");
	
	get_rst("select email,user_name from po_memb_mast where po_company_id=".$_SESSION["company_id"],$row_email,$rst_email);
	
	$url = "http://".$_SERVER["SERVER_NAME"]."/saas/edit_po.php?id=$id";
	do{
		$mail_body = $row_email["user_name"].",<br>PO with ID $po_no is rejected by the authority. Kindly  <a href='$url'>click here</a> to view the reason for the rejection.";
		$mail_body.="<br><br>Regards,<br><br> Company-Name Briefcase<br>Your Procurement Management System";
		send_mail($row_email["email"],"Company-Name Briefcase - PO Rejected",$mail_body,$sales);
	}while($row_email = mysqli_fetch_assoc($rst_email));
			
	get_rst("select memb_fname,memb_email from member_mast where memb_id=".$_SESSION["user_id"],$own_comp);
			
	$mail_body1 = $own_comp["memb_fname"].",<br>PO with ID $po_no is approved. Kindly  <a href='$url'>click here</a> to make payment/view the Cart items corresponding to the PO.";
	$mail_body1.="<br><br>Regards,<br><br> Company-Name Briefcase<br>Your Procurement Management System";
	
	send_mail($own_comp["memb_email"],"Company-Name Briefcase - PO Rejected",$mail_body1,$sales);
	send_mail($sales,"Company-Name Briefcase - PO Rejected",$mail_body1,"noreply@Company-Name.com");
	
	js_alert("PO rejected successfully.");
	js_redirect("manage_po.php");
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
	$page_head = "Purchase Order Details";
}else{
	$page_head = "Create New Purchase Order";
}
?>
<h2><?=$page_head?></h2>
<div id = "gif_show" style="display:none"></div>
<div id="content_hide">
<form name="frm_po" id="frm_po" method="post" enctype="multipart/form-data" >
<?php if(get_rst("select reason_for_reject from po_details where quotation_id=0$id and reason_for_reject IS NOT NULL",$row_reject)){?>
		<table style="padding-top: 10px; padding-bottom: 10px;"><tr><td style="width: 120px"><strong> Reason For Reject: </strong></td> <td><?=$row_reject["reason_for_reject"]?></td></tr> </table>
<?php  }
		get_rst("select po_path from po_details where quotation_id=0$id",$row1)?>
		<iframe src="<?php echo $row1["po_path"];?>" width="100%" height="500px"></iframe>
		
		<table id="reason_block" style="display: none">
		<tr><td>Reason for reject: </td>
		<td><textarea type="text" name="reason_for_reject" id="reason_for_reject" class="form-control textbox-lrg" style="height:50px"></textarea></td>
		</tr>
		</table>
		
<?php if($_SESSION["saas_user"] == 1 && get_rst("select quotation_id from po_details where quotation_id=0$id and po_status='Pending'",$row_po)){?>
	
	<center>
		<input type="submit" class="btn btn-warning" value="Approve Purchase Order" name="accept_po" onclick="javascript: display_gif();">
		<input type="submit" class="btn btn-warning" value="Reject Purchase Order" name="reject_po" onclick="javascript: return display_div();">
	</center>
<?php }?>
</form>
</div>	
<?php 
require_once("inc_admin_footer.php");
?>
<script>
function display_div(){
	var reason_block = document.getElementById("reason_block").style.display;
	if(reason_block=='block'){
		if(document.getElementById("reason_for_reject").value==""){
			alert("Please provide reason");
			return false;
		}
	}else{ 
		document.getElementById("reason_block").style.display ="block";
		return false;
	}
	display_gif();
}
</script>