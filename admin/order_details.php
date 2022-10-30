<?php
require_once("inc_admin_header.php");
require_once("../seller/logistics_push.php");
$id = func_read_qs("id");
$sup_id = func_read_qs("sid");

//if($_SESSION["admin"]=""){

if(get_rst("select * from ord_summary where ord_id=0$id and sup_id=0$sup_id",$row_s,$rst_s)){
	$cart_id = $row_s["cart_id"];
	$delivery_status = $row_s["delivery_status"];
	$pay_status = $row_s["pay_status"];
	
}else{
	header('location: order_list.php');
	js_redirect("order_list.php");
}

if(get_rst("select * from ord_details where cart_id='".$cart_id."'",$row)){
	$bill_name = $row["bill_name"];
	$bill_email = $row["bill_email"];
	$bill_add1 = $row["bill_add1"];
	$bill_add2 = $row["bill_add2"];
	$bill_city = $row["bill_city"];
	$bill_state = $row["bill_state"];
	$bill_country = $row["bill_country"];
	$bill_postcode = $row["bill_postcode"];
	$bill_tel = $row["bill_tel"];

	$ship_name = $row["ship_name"];
	$ship_email = $row["ship_email"];
	$ship_add1 = $row["ship_add1"];
	$ship_add2 = $row["ship_add2"];
	$ship_city = $row["ship_city"];
	$ship_state = $row["ship_state"];
	$ship_country = $row["ship_country"];
	$ship_postcode = $row["ship_postcode"];
	$ship_tel = $row["ship_tel"];
	
	$ord_instruct = $row["ord_instruct"];
	
}

$act = func_read_qs("act");

if($act<>""){
	$delivery_status_old = func_read_qs("delivery_status_old");
	$delivery_status = func_read_qs("delivery_status");
	if($delivery_status."" == ""){
		$delivery_status = "Pending";
	}
	
	$pay_status = func_read_qs("pay_status");
	$pay_status_old= func_read_qs("pay_status_old");
	if($row_s["pay_method"] == "BT"){
		$credit_amt=0;
		if($pay_status_old <> 'Paid' && $pay_status == 'Paid'){
			get_rst("select ord_total from ord_summary where ord_id=0$id and sup_id=0$sup_id",$ordtotal);
			$coupon_amt = 0;
			if(get_rst("select coupon_id, coupon_amt from ord_coupon where cart_id=$cart_id", $row_c)){
				$coupon_amt = $row_c["coupon_amt"];
			}
			$credit_amt	= ($ordtotal['ord_total'] - $coupon_amt) * 0.01;
			
			if(get_rst("select credit_amt from credit_details where memb_id=".$row_s['user_id'],$cr)){
				
				$credit_amt= $cr['credit_amt'] + $credit_amt;
				
				if($credit_amt >= 100){
					//$new_creditamt = $credit_amt % 100;
					$couponamt = (intval($credit_amt/100))*100;
					
					if(get_rst("select coupon_id,coupon_code,min_ord_value,max_discount_value from offer_coupon where credit_flag=1 and memb_id=".$row_s['user_id'],$coupon)){
						$fld_arr = array();
			
						$fld_arr["min_ord_value"] = $couponamt;
						$fld_arr["max_discount_value"] = $couponamt;
						$from_date = date("d-m-Y");
						$till_date = date("d-m-Y", strtotime("$today +240 month"));
						$fld_arr["valid_from"] = $from_date;
						$fld_arr["valid_till"] = $till_date;

						$qry = func_update_qry("offer_coupon",$fld_arr,"where credit_flag=1 and memb_id='".$row_s['user_id']."'");
						execute_qry($qry);
						
						execute_qry("update credit_details set credit_amt=$credit_amt,coupon_id='".$coupon['coupon_id']."' where memb_id=".$row_s['user_id']);
						
					}else{
						$fld_arr = array();
			
						$fld_arr["coupon_code"] = $row_s['ord_no'];
						$fld_arr["min_ord_value"] = $couponamt;
						$fld_arr["disc_per"] = 100;
						$fld_arr["active"] = 1;
						$from_date = date("d-m-Y");
						$till_date = date("d-m-Y", strtotime("$today +240 month"));
						$fld_arr["valid_from"] = $from_date;
						$fld_arr["valid_till"] = $till_date;
						$fld_arr["max_discount_value"] = $couponamt;
						$fld_arr["credit_flag"] = 1;
						
						$fld_arr["memb_id"] = $row_s['user_id'];

						$qry = func_insert_qry("offer_coupon",$fld_arr);
						execute_qry($qry);
						$coupon_id = mysqli_insert_id($con);
						execute_qry("update credit_details set credit_amt=$credit_amt,coupon_id=$coupon_id where memb_id=".$row_s['user_id']);
						
						get_rst("select memb_email,memb_fname from member_mast where memb_id=".$row_s['user_id'],$userdetail);
						
						$mail_body = "Dear ".$userdetail["memb_fname"].",<br>We've converted your Credit Points accumulated on your account to Discount Coupon owing to use of Bank Transfer Payment mode in your previous transaction(s). Kindly use coupon code '".$row_s['ord_no']."' to avail Rs.$couponamt Discount on your next purchase.";
						$mail_body.="Redefine your Industrial Purchasing using our simplified eCommerce Marketplace portal. Find genuine discounts & offers throughout various product ranges.";
						$mail_body.="<br><br>Thank you for being our Patron.<br><br> Team Company-Name";
						send_mail($userdetail["memb_email"],"Company-Name - Credit Coupon",$mail_body,$sales);
					}
				}else{
					execute_qry("update credit_details set credit_amt=$credit_amt where memb_id=".$row_s['user_id']);
				}
			}else{
				execute_qry("insert into credit_details set memb_id='".$row_s['user_id']."',credit_amt=$credit_amt");
				
				if($credit_amt >= 100){
					//$new_creditamt = $credit_amt % 100;
					$couponamt = (intval($credit_amt/100))*100;
					
					if(get_rst("select coupon_id,coupon_code,min_ord_value,max_discount_value from offer_coupon where credit_flag=1 and memb_id=".$row_s['user_id'],$coupon)){
						$fld_arr = array();
			
						$fld_arr["min_ord_value"] = $couponamt;
						$fld_arr["max_discount_value"] = $couponamt;
						$from_date = date("d-m-Y");
						$till_date = date("d-m-Y", strtotime("$today +240 month"));
						$fld_arr["valid_from"] = $from_date;
						$fld_arr["valid_till"] = $till_date;
			

						$qry = func_update_qry("offer_coupon",$fld_arr,"where credit_flag=1 and memb_id='".$row_s['user_id']."'");
						execute_qry($qry);
						
						execute_qry("update credit_details set credit_amt=$credit_amt,coupon_id='".$coupon['coupon_id']."' where memb_id=".$row_s['user_id']);
						
					}else{
						$fld_arr = array();
			
						$fld_arr["coupon_code"] = $row_s['ord_no'];
						$fld_arr["min_ord_value"] = $couponamt;
						$fld_arr["disc_per"] = 100;
						$fld_arr["active"] = 1;
						$from_date = date("d-m-Y");
						$till_date = date("d-m-Y", strtotime("$today +360 month"));
						$fld_arr["valid_from"] = $from_date;
						$fld_arr["valid_till"] = $till_date;
						$fld_arr["max_discount_value"] = $couponamt;
						$fld_arr["credit_flag"] = 1;
			
						$fld_arr["memb_id"] = $row_s['user_id'];

						$qry = func_insert_qry("offer_coupon",$fld_arr);
						execute_qry($qry);
						$coupon_id = mysqli_insert_id($con);
						execute_qry("update credit_details set credit_amt=$credit_amt,coupon_id=$coupon_id where memb_id=".$row_s['user_id']);
						
						get_rst("select memb_email,memb_fname from member_mast where memb_id=".$row_s['user_id'],$userdetail);
						
						$mail_body = "Dear ".$userdetail["memb_fname"].",<br>We've converted your Credit Points accumulated on your account to Discount Coupon owing to use of Bank Transfer Payment mode in your previous transaction(s). Kindly use coupon code '".$row_s['ord_no']."' to avail Rs.$couponamt Discount on your next purchase.";
						$mail_body.="Redefine your Industrial Purchasing using our simplified eCommerce Marketplace portal. Find genuine discounts & offers throughout various product ranges.";
						$mail_body.="<br><br>Thank you for being our Patron.<br><br> Team Company-Name";
						send_mail($userdetail["memb_email"],"Company-Name - Credit Coupon",$mail_body,$sales);
					}
				}
			}
			
		}
	}
	execute_qry("update ord_summary set delivery_status='$delivery_status',pay_status='$pay_status' where ord_id=0$id and sup_id=0$sup_id");
	$v_cancelled = "";
	if($delivery_status<>"Pending" and $delivery_status_old <> $delivery_status){
		if(get_rst("select buyer_action from ord_summary where cart_id=$cart_id and sup_id=$sup_id and buyer_action is not null")){
			$v_cancelled = "1";
			js_alert("Sorry the order has just been cancelled by the Buyer hence cannot be Dispatched.");
		}	
	}	
	if($v_cancelled == ""){
		if($delivery_status_old <> "Dispatched" and $delivery_status == "Dispatched"){
			//Process comission when the order is dispatched
			$actual_ship_amt=0;
			get_rst("select actual_ship_amt from ord_items where cart_id='".$cart_id."' and sup_id=$sup_id",$row_ship,$rst_ship);
			do{
				$actual_ship_amt = $row_ship["actual_ship_amt"]+$actual_ship_amt;
			}while($row_ship = mysqli_fetch_assoc($rst_ship));
			
			if($row_s["new_seller_price"] <> ""){
				get_rst("select new_seller_price,cart_price_tax,tax_percent,cart_qty from ord_items where cart_id='".$cart_id."' and sup_id=$sup_id",$row_in,$rst_in);
				$fld_inv_arr = array();
				$fld_inv_arr["ord_no"] = $row_s["ord_no"];
				$fld_inv_arr["sup_id"] = $sup_id;
				$fld_inv_arr["pay_status"] = "Pending";
				$fld_inv_arr["pay_comission"] = func_read_qs("sup_total_commission");
				$fld_inv_arr["service_tax"] = ($fld_inv_arr["pay_comission"] *18) / 100;	//18% GST
				do{
					if($row_in["new_seller_price"] <> ""){
						$new_price = ($row_in["new_seller_price"] * $row_in["tax_percent"])/100;
						$new_price = $new_price * $row_in["cart_qty"];
						$total_price = $total_price + ($row_in["new_seller_price"] * $row_in["cart_qty"]) + $new_price;
					}else{
						$total_price = $total_price + ($row_in["cart_price_tax"]*$row_in["cart_qty"]);
					}
				}while($row_in = mysqli_fetch_assoc($rst_in));
				$fld_inv_arr["pay_total"] = floatval($total_price) - $fld_inv_arr["pay_comission"] - $fld_inv_arr["service_tax"] - $actual_ship_amt;
				$qry = func_insert_qry("invoice_mast",$fld_inv_arr);
				execute_qry($qry);
			}else{
				$fld_inv_arr = array();
				$fld_inv_arr["ord_no"] = $row_s["ord_no"];
				$fld_inv_arr["sup_id"] = $sup_id;
				$fld_inv_arr["pay_status"] = "Pending";
				$fld_inv_arr["pay_comission"] = func_read_qs("sup_total_commission");
				$fld_inv_arr["service_tax"] = ($fld_inv_arr["pay_comission"] *18) / 100;	//18% GST
				$fld_inv_arr["pay_total"] = floatval($row_s["item_total"] + $row_s["vat_value"]) - $fld_inv_arr["pay_comission"] - $fld_inv_arr["service_tax"] - $actual_ship_amt;
				$qry = func_insert_qry("invoice_mast",$fld_inv_arr);
				execute_qry($qry);
			}
			
			$fld_arr = array();
			$fld_arr["ord_no"] = $row_s["ord_no"];
			$fld_arr["bill_name"] = $bill_name;

			$mail_body = push_body("ord_dispatched.html",$fld_arr);
			send_mail($bill_email,"Company-Name - Order dispatched : ".$row_s["ord_no"],$mail_body);

		}
		
		if($delivery_status=="Ready-to-Ship"){ //creates a logistics push request.
			get_rst("select addr_id from sup_ext_addr where sup_id=$sup_id LIMIT 1",$row_add);
				$address_id=$row_add["addr_id"];
				get_rst("select local_logistics from ord_summary where sup_id=$sup_id and ord_id=0$id",$r);
					if($r["local_logistics"] == 1){
					get_rst("select Admin_email from configuration",$row_em);
					$super_admin_email = $row_em["Admin_email"];
					$from = "orders@Company-Name.com";
					$body="Hi,<br>";
					$body.="An order with orderID=$ord_no has been placed, and needs to be shipped from local logistics vendor.<br>";
					$body.="<br><br>Regards,<br>Team Company-Name";		
					xsend_mail($logistics_email,"Company-Name - Local Logistics",$body,$from );//mail send to local logistics
				
					$from = "orders@Company-Name.com";
					$body="Hi,<br>";
					$body.="An order with orderID=$ord_no has been placed, and needs to be shipped from local logistics vendor.<br>";
					$body.="<br><br>Regards,<br>Team Company-Name";		
					xsend_mail($super_admin_email,"Company-Name - Local Logistics",$body,$from );// mail send to super admin
					$msg="1";
				}else{
				lgs_create_order($id,$msg,$address_id);
			}
			if($msg <> "1"){
				js_alert("Record cannot be updated, please contact Company-Name support.");	
			}
		}elseif($delivery_status_old == "Dispatched" && $delivery_status=="Delivered"){
			// Send mail on order delivery.
			$fld_arr = array();
			$fld_arr["ord_no"] = $row_s["ord_no"];
			execute_qry("update ord_summary set delivery_date=NOW() where ord_id=0$id and sup_id=0$sup_id");
			$mail_body = push_body("ord_delivered.txt",$fld_arr);
			xsend_mail($bill_email,"Company-Name - Order ".$row_s["ord_no"]." Delivered",$mail_body,"orders@Company-Name.com");
		}
		
		js_alert("Order status updated successfully.");	
	}
}
if(get_rst("select ind_buyer, memb_company, memb_vat from member_mast where memb_email='".$bill_email."'",$row_m)){
	$memb_company = $row_m["memb_company"];
	$memb_vat = $row_m["memb_vat"];
}
if($_POST["cancel"]){
		$ord_no=$row_s["ord_no"];
		$sql = "update ord_summary set buyer_action='Cancelled',buyer_date=now() where cart_id=$cart_id";
		execute_qry($sql);

		$sql = "update ord_items set item_buyer_action='Cancelled',buyer_date=now() where cart_id=$cart_id";
		execute_qry($sql);
		
		get_rst("select sup_id,sup_email,sup_contact_no from sup_mast where sup_id in (select sup_id from ord_summary where cart_id=$cart_id)",$row_c,$rst_c);
		do{
			$sup_id = $row_c["sup_id"];
			update_ord_summary($cart_id,0,$sup_id,"");

			$mail_body = "Dear Seller,<br> Order $ord_no has been cancelled by the buyer";
			$sms_body = "Dear Seller, Order $ord_no has been cancelled by the buyer";
			send_mail($row_s["sup_email"],"Company-Name - Order Cancelled (Seller): $ord_no",$mail_body,"",$cc);
			send_sms($row_s["sup_contact_no"],$sms_body);
		}while($row_s = mysqli_fetch_assoc($rst_s));
		
		$mail_body = "Dear $bill_name,<br> Your order $ord_no has been cancelled successfully.";
		$sms_body = "Dear $bill_name, Your order $ord_no has been cancelled successfully.";
		send_mail($bill_email,"Company-Name - Order Cancelled: $ord_no",$mail_body,"",$cc);
		send_sms($bill_tel,$sms_body);
		
		//js_alert("Order has been cancelled successfully.");
		$url = "order_details.php?id=$id&sid=$sup_id"; ?>
		<script>
		window.location.href="<?=$url?>"
		</script>
	<?php	}
?>
<script>
//Save Details
function js_save(){
	document.frm_order.submit();
}

//Print Form
function print_page(divID){
    var printContents = document.getElementById(divID).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script>

<form name="frm_order" action="order_details.php" method="post">
	<input type="hidden" name="act" value="1">
	<input type="hidden" name="id" value="<?=$id?>">
	<input type="hidden" name="sid" value="<?=$sup_id?>">
	<div id="ord_detail">
		<table width="80%"  border="1" align="center" class="checkout" >
			<tr>
				<td>WayBill No.</td><td><?=$row_s["way_billl_no"]?></td>
				<?php if($row_s["way_billl_no"] <> ""){
				$barcode_url = "../lib/barcode.php?codetype=Code39&size=40&text=".$row_s["way_billl_no"]; ?>
				<td><strong>Barcode</strong></td><td class="img-padding"><img src="<?=$barcode_url?>" height="30"/></td>
				<?php }else { ?>
					<td><strong>Barcode</strong></td><td class="img-padding"></td>
				<?php } ?>

			</tr>
				<?php if($row_s["buyer_action"]=="Cancelled"){?>
			<tr style="background-color:#FF0000;">
				<td align="center" style="background-color:#FF0000;" colspan="10"><font color='white'>This order has been cancelled by the Buyer</font></td>
			</tr>		
			<?php }?>
			<tr>
				<td>Order No.</td><td><?=$row_s["ord_no"]?></td>

				<td>Order Date.</td><td><?=$row_s["ord_date"]?></td>
			</tr>
		</table>
		<?php if($row_s["pay_method"]=="BT"){?>
		<br>
		<table width="80%"  border="1" align="center" class="checkout" >
			<tr>
			<td align="left" colspan="10" class="table-bg3"><p>Bank Transfer Details</p></td>
			</tr>
			<tr>
				<td width="165" align="right">Bank Name</td>
				<td align="left"><?=$row["bt_bank_name"]?></td>
				
				<td  align="right">Branch</td>
				<td align="left"><?=$row["bt_bank_branch"]?></td>

			</tr>
			<tr>
				<td width="165" align="right">Cheque/Ref No.</td>
				<td align="left"><?=$row["bt_ref_no"]?></td>
				
				<td  align="right">Date</td>
				<td align="left"><?=$row["bt_date"]?></td>
			</tr>
			<tr>
			</tr>
			
		</table>
		<?php }?>			
		<br>
		
		<table width="80%"  border="1" align="center" class="checkout" >		
			<tr>
			<td align="right"></td>
			<td align="left" class="table-bg2"><p><b>Billing Details</p></td>
			<td align="left" class="table-bg2"><p><b>Shipping Details</p></td>
			</tr>
			
			<?php if($row_m["ind_buyer"]==1){?>
				<tr>
				<td align="right" class="table-bg2"><label for="Name"></label>
				<p>Name :</p></td>
				<td align="left" class="table-bg2"><?=$memb_company?></td>
				<td align="left" class="table-bg2"><?=$memb_company?></td>
				</tr>
				
				<tr>
				<td align="right" class="table-bg2"><label for="GST"></label>
				<p>GST :</p></td>
				<td align="left" class="table-bg2"><?=$memb_vat?></td>
				<td align="left" class="table-bg2"><?=$memb_vat?></td>
				</tr>
			<?php }else{?>
				<tr>
				<td align="right" class="table-bg2"><label for="Name"></label>
				<td align="left" class="table-bg2"><?=$bill_name?></td>
				<td align="left" class="table-bg2"><?=$ship_name?></td>
				</tr>
			<?php }?>
			
			<tr>
			<td align="right" class="table-bg2"><label for="Email"></label>
			<p>Email :</p></td>
			<td align="left" class="table-bg2"><?=$bill_email?></td>
			<td align="left" class="table-bg2"><?=$ship_email?></td>	
			</tr>


			<tr>
			<td align="right" class="table-bg2"><label for="Email"></label>
			<p>Address 1 :</p></td>
			<td align="left" class="table-bg2"><?=$bill_add1?></td>
			<td align="left" class="table-bg2"><?=$ship_add1?></td>	
			</tr>

			<tr>
			<td align="right" class="table-bg2"><label for="Email"></label>
			<p>Address 2 :</p></td>
			<td align="left" class="table-bg2"><?=$bill_add2?></td>
			<td align="left" class="table-bg2"><?=$ship_add2?></td>	
			</tr>

			<tr>
			<td align="right" class="table-bg2"><label for="Email"></label>
			<p>Town / City :</p></td>
			<td align="left" class="table-bg2"><?=$bill_city?></td>
			<td align="left" class="table-bg2"><?=$ship_city?></td>	
			</tr>

			<tr>
			<td align="right" class="table-bg2"><label for="Email"></label>
			<p>Post code :</p></td>
			<td align="left" class="table-bg2"><?=$bill_postcode?></td>
			<td align="left" class="table-bg2"><?=$ship_postcode?></td>	
			</tr>
			
			<tr>
			<td align="right" class="table-bg2"><label for="Email"></label>
			<p>State :</p></td>
			<td align="left" class="table-bg2"><?=$bill_state?></td>
			<td align="left" class="table-bg2"><?=$ship_state?></td>	
			</tr>
			
			<tr>
			<td align="right" class="table-bg2"><label for="Email"></label>
			<p>Country :</p></td>
			<td align="left" class="table-bg2"><?=$bill_country?></td>
			<td align="left" class="table-bg2"><?=$ship_country?></td>	
			</tr>

			<tr>
			<td align="right" class="table-bg2"><label for="Email"></label>
			<p>Contact No. :</p></td>
			<td align="left" class="table-bg2"><?=$bill_tel?></td>
			<td align="left" class="table-bg2"><?=$ship_tel?></td>	
			</tr>
			</table>
			
			<hr color="#96b809" width="100%" size="1px" align="center" />
			
			<table width="80%"  border="1" align="center" class="checkout" >
			<tr>
			<th width="175" align="left" ><p>Additional Instructions:</p></th>
			<td align="left" ><?=$ord_instruct?>
			</td>
			</tr>

			<tr>
			<th align="left" xwidth="150px"><p>Mode of Payment: </p></th>
			<td align="left" class="table-bg2"><p>
				<?=$row_s["pay_method_name"]?></p></td>
			</tr>	
		</table>
	</div>
	<br>
	<center><h3>Item details</h3>
	<?php
	$ord_pay_total = 0;
	//do{
		$sup_id = $row_s["sup_id"];
		$sup_name="";
		$sup_gst="";
		$gst_cap = 0;
		if(get_rst("select sup_company,sup_vat from sup_mast where sup_id=0$sup_id",$row_sup)){
			$sup_name=$row_sup["sup_company"]; 
			$sup_gst=$row_sup["sup_vat"];
			get_rst("select sup_ext_state from sup_ext_addr where sup_id=$sup_id", $st_rw);
			if($st_rw["sup_ext_state"] == $bill_state){
				$gst_cap = 1; // 0 for inter-state GST, 1 for intra-state GST
			}
		}
		?>
		<table width="80%"  border="1" class="list"  align="center">
			<tr><th>Supplier :&nbsp; <?=$sup_name?></th>
			<th><span>GST Number :&nbsp; <?=$sup_gst?><span></th></tr>
		</table>
		
		<table width="80%"  border="1" align="center" class="list">
		<tr style="font-size:1em; background-color:#D5D5D5">
			<td width="10%" align="center" class="table-bg">Image</td>
			<td width="10%" align="center" class="table-bg">HSN Code</td>
			<td width="24%" align="center" class="table-bg">Item Description</td>
			<td width="10%" align="center" class="table-bg">Price &nbsp;<span><div class="" style="display:inline-block;">&#8377; </div></span></td>
			<td width="8%" align="center" class="table-bg">GST</td>
			<td width="10%" align="center" class="table-bg">Price with TAX &nbsp;<span><div class="" style="display:inline-block;">&#8377; </div></span></td>
			<td width="10%" align="center" class="table-bg">Shipping Charges &nbsp;<span><div class="" style="display:inline-block;">&#8377; </div></span></td>
			<td width="6%" align="center" class="table-bg">Qty</strong></td>
			<td align="center" width="12%" class="table-bg">Total&nbsp;<span><div class="" style="display:inline-block;">&#8377; </div></span></td>
		</tr>
		<?php 	
		$Productinfo="Test Products";
		get_rst("select * from ord_items where cart_id='".$cart_id."' and sup_id=0$sup_id",$row,$rst);
		$item_total = 0;
		$vat_value = 0;
		$sup_commission = 0;
		do{
			$cart_price = 0;
			$item_color = "";
			if($row["item_buyer_action"].""<>""){
				$item_color = "#d9534f";
			}else{	
				$cart_price = $row["cart_price"];
			}
			
			$cart_qty = $row["cart_qty"];
			$prod_id = $row["prod_id"];
			$tax_percent = $row["tax_percent"];
			$tax_value = $cart_price * ($tax_percent/100);
			$cart_price_tax = $cart_price + $tax_value;
			
			$item_total = $item_total + ($cart_price * $cart_qty);
			$vat_value = $vat_value + ($tax_value * $cart_qty);
			
			//calculate seller commision
			get_rst("select sup_commission, price, offer_disc from prod_sup where sup_id=0$sup_id and prod_id=0$prod_id", $rw_com);
			get_rst("select hsn_code from prod_mast where prod_id=0$prod_id", $prow);
			$sup_commission = $sup_commission + ((($rw_com["price"] - ($rw_com["price"] * $rw_com["offer_disc"]/100)) * $cart_qty) * floatval($rw_com["sup_commission"]) / 100); ?>
			
			<tr style="background-color:<?=$item_color?>;">
			<td rowspan="1" class="table-bg2"><a href="<?=get_prod_url($prod_id,"")?>"><img src="<?=show_img($row["item_thumb"])?>" width="50" height="40" border="0" alt="<?=$row["item_name"]?>" /></a></td>
			<td rowspan="1" align="center" valign="middle" class="table-bg2"><p><?=$prow["hsn_code"]?></p></td>
			<td rowspan="1" align="center" valign="middle" class="table-bg2"><p><?=$row["item_stock_no"]." - ".$row["item_name"]?></p></td>
			<td rowspan="1" align="center" valign="middle" class="table-bg2"><?=formatNumber($cart_price,2)?>&nbsp;</td>
			<td rowspan="1" align="center" valign="middle" class="table-bg2">
			<?php echo($tax_percent."% ");?>
			</td>
			<td valign="middle" align="center" class="table-bg2">
			<?=formatNumber($cart_price_tax,2)?>&nbsp;
			</td>
			<?php if($row["ship_amt"]==0){?>
			<td  align="center" style="color: #006633;"><b>Free</b></td><?php }else{?>
			<td  align="center"><?=$row["ship_amt"]?>&nbsp;</td>
			<?php }?>
				
			<td valign="middle" align="center" class="table-bg2">
				<?=$cart_qty?>
			</td>
			<td valign="middle" align="center" class="table-bg2"><strong><?=formatNumber(($cart_qty * $cart_price_tax) + $row["ship_amt"],2)?></strong>&nbsp;&nbsp;</td>

			</tr>
			<?php
		}while($row = mysqli_fetch_assoc($rst)); ?>
		
		</table>
		<input type="hidden" name="sup_total_commission" value="<?=$sup_commission?>">
		<table width="80%"  border="1" class="list"  align="center">
		<?php	
		//$item_total = $row_s["item_total"];
		$shipping_charges = $row_s["shipping_charges"]."";
		$ord_total = $item_total + $vat_value + $shipping_charges;
		$vat_percent = $row_s["vat_percent"];
		//$vat_value = $row_s["vat_value"];
		$ord_pay_total = $ord_pay_total + $ord_total;
		
		if($shipping_charges.""==""){
			$v_shipping_charges = "As per the delivery area";
		}else{
			$v_shipping_charges = "Rs.".formatNumber($shipping_charges,2);
		} ?>

		<tr>
		<td align="right"><p><span class="green-basket">Sub-Total (exc. Tax)</span></p></td>
		<td align="right">Rs.<?=FormatNumber($item_total,2)?>&nbsp;</td>
		
		</tr>	
		<tr>
		<td width="65%" align="right"><p><span class="green-basket">Shipping Charges </span></p></td>
		<?php if($shipping_charges == 0){?>
		<td width="25%" align="right"> Free Delivery &nbsp;</td><?php }else{?>
		<td width="25%" align="right"><?=$v_shipping_charges?>&nbsp;</td>
		<?php }?>
		</tr>
		
		<?php if($gst_cap == 0){?> <!-- Inter-State GST -->
			<tr>
			<td align="right"><p><span class="green-basket">Total IGST </span></p></td>
			<td align="right">Rs.<?=FormatNumber($vat_value,2)?>&nbsp;</td>
			</tr>
		<?php }else{ ?> <!-- Intra-State GST -->
			<tr>
			<td align="right"><p><span class="green-basket">Total CGST </span></p></td>
			<td align="right">Rs.<?=FormatNumber($vat_value/2,2)?>&nbsp;</td>
			</tr>
			<tr>
			<td align="right"><p><span class="green-basket">Total SGST </span></p></td>
			<td align="right">Rs.<?=FormatNumber($vat_value/2,2)?>&nbsp;</td>
			</tr>
		<?php } ?>
		
		<tr>
		<td align="right"><p><span class="green-basket">Total Price </span></p></td>
		<td align="right"><strong>Rs.<?=FormatNumber($ord_total,2)?></strong>&nbsp;</td>
		</tr>
		
		<tr>
		<td align="right"><p><span class="green-basket">Total Price (Round-Off)</span></p></td>
		<td align="right"><strong>Rs.<?=round($ord_total,0,PHP_ROUND_HALF_UP)?></strong>&nbsp;</td>
		</tr>
		</table>
		
		<?php $pkg_disabled="disabled"; ?>
		<table width="80%"  border="1" align="center" class="table_form" style="width:80%;">
		<tr>
			<th colspan="2" align="left" >Packaging Details</th>
		</tr>
		<tr>
			<td>Weight:</td>
			<td>
			<input type="text" name="pkg_weight_kgs" value="<?=$row_s["pkg_weight_kgs"]?>" <?=$pkg_disabled?> size="5" id="110" title="Weight in Kgs"> Kgs + 
			<input type="text" name="pkg_weight" value="<?=$row_s["pkg_weight"]?>" <?=$pkg_disabled?> size="5" id="110" title="Weight in gms"> gms
			</td>
		</tr>
		
		<tr>
			<td>Dimension (H x W x D):</td><td>
			<input type="text" name="pkg_height" value="<?=$row_s["pkg_height"]?>" <?=$pkg_disabled?> size="5" id="110" title="Height in cms"> cms x 
			<input type="text" name="pkg_width" value="<?=$row_s["pkg_width"]?>" <?=$pkg_disabled?> size="5" id="110" title="Width in cms"> cms x 
			<input type="text" name="pkg_depth" value="<?=$row_s["pkg_depth"]?>" <?=$pkg_disabled?> size="5" id="110" title="Depth in cms"> cms
			</td>
		</tr>
		<tr>
			<td>Volumetric weight:</td><td>
			<?php if($pkg_disabled==""){?>
				<input type="button" class="btn btn-warning" value="Calculate" onclick="javascript: js_cal_vw();"> 
			<?php }?>
			&nbsp; &nbsp;<span id="span_vol_wt"><?=calc_vol_wt($row_s["pkg_height"],$row_s["pkg_width"],$row_s["pkg_depth"])?></span>
			</td>
		</tr>
		</table>
		<br>

	<table width="80%"  border="0" class="list"  align="center">
	<tr>
		<td align="right" class="table-bg"><strong><span class="green-basket">Total Amount Payable:</span></strong></td>
		<td align="right" width="100px" class="table-bg"><strong>Rs.<?=FormatNumber($ord_pay_total,2)?></strong>&nbsp;</td>
	</tr>
	</table>
	<br>
	<center>
	
	<?php $status_disabled="";
	if($row_s["buyer_action"]=="Cancelled" || $_SESSION["admin"]=="FM" || $_SESSION["admin"]=="AM" || $delivery_status == "Delivered"){
		$status_disabled="disabled";
	} ?>
	<table width="80%"  border="0" align="center">
	<tr>
	<td>
		Delivery Status:
		<input type="hidden" name="delivery_status_old" value="<?=$delivery_status?>">
		<?php if ($row_s["pay_status"]=="Pending"){
					echo $row_s["pay_status"];
			  }else{ ?>
				<select name="delivery_status" <?=$status_disabled?>>
					<?php func_option("Pending","Pending",func_var($delivery_status))?>
					<?php func_option("Ready-to-Ship","Ready-to-Ship",func_var($delivery_status))?>
					<?php func_option("Dispatched","Dispatched",func_var($delivery_status))?>
					<?php func_option("Delivered","Delivered",func_var($delivery_status))?>
				</select>
		<?php }?>		
	</td>
	<td>
		<?php if($_SESSION["admin"]=="FM"){
			$status_disabled="";
		}?>
		Payment Status:
		<input type="hidden" name="pay_status_old" value="<?=$row_s["pay_status"]?>">
		<select name="pay_status" <?=$status_disabled?>>
			<?php func_option("OnCredit","OnCredit",func_var($pay_status))?>
			<?php func_option("Pending","Pending",func_var($pay_status))?>
			<?php func_option("Paid","Paid",func_var($pay_status))?>
		</select>
	</td>
	</tr>
	<tr height="25px"></tr>
	<tr>
	<td align="right"><input type="submit" name="cancel" class="btn btn-warning" value=" Cancel Order " <?=$status_disabled?>/></td>
	<td align="center"><input type="button" class="btn btn-warning" value=" Update " onclick="javascript: js_save();" <?=$status_disabled?>/></a></td>
	<td align="left"><input type="button" class="btn btn-warning" value=" Print Invoice " onclick="javascript: print_page('ord_info');"/></td>
	</tr>
	
	</table>
	<div id="ord_info">
	<?php for($i=1;$i<=2;$i++){?>
		<div class="order_info">
			<table width="100%"  border="1" align="center" class="checkout invoice">
				<tr>
					<td><strong>WayBill No.</strong></td><td><?=$row_s["way_billl_no"]?></td>
					<?php if($row_s["way_billl_no"] <> ""){
					$barcode_url = "../lib/barcode.php?codetype=Code39&size=40&text=".$row_s["way_billl_no"]; ?>
					<td><strong>Barcode</strong></td><td class="img-padding"><img src="<?=$barcode_url?>" height="30"/></td>
					<?php }else { ?>
						<td><strong>Barcode</strong></td><td class="img-padding"></td>
					<?php } ?>
				</tr>
				</tr>
				
				<tr>
					<td><strong>Order No.</strong></td><td><?=$row_s["ord_no"]?></td>
					<td><strong>Order Date.</strong></td><td><?=$row_s["ord_date"]?></td>
				</tr>
			</table>
			
			<center><h3 class="h3">Shipping Details</h3></center>
			<?php if($row_m["ind_buyer"]==1){?>
			<p class="p"><strong>Name:</strong> <span align="left" class="table-bg2" style="padding-left:5px;"><?=$memb_company?></span><span style="padding-left:50px;">
			<strong>Contact No.:</strong><span align="left" class="table-bg2" style="padding-left:5px;"><?=$ship_tel?></span></p></span></span></p>
			<p class="p"><strong>GST:</strong> <span align="left" class="table-bg2" style="padding-left:5px;"><?=$memb_vat?></span></p>
			<p class="p"><strong>Address 1:</strong><span align="left" class="table-bg2" style="padding-left:5px;"><?=$ship_add1?></span>
			<span align="left" class="table-bg2"><?=$ship_city?></span>
			<span align="left" class="table-bg2"><?=$ship_state?></span>,
			<span align="left" class="table-bg2"><?=$ship_country?></span> 
			<span align="left" class="table-bg2"><?=$ship_postcode?></span></p><?php }else{?>
			
			<p class="p"><strong>Name:</strong> <span align="left" class="table-bg2" style="padding-left:5px;"><?=$ship_name?></span><span style="padding-left:50px;">
			<strong>Contact No.:</strong><span align="left" class="table-bg2" style="padding-left:5px;"><?=$ship_tel?></span></p></span></span></p>
			<p class="p"><strong>Address 1:</strong><span align="left" class="table-bg2" style="padding-left:5px;"><?=$ship_add1?></span>
			<span align="left" class="table-bg2"><?=$ship_city?></span>
			<span align="left" class="table-bg2"><?=$ship_state?></span>,
			<span align="left" class="table-bg2"><?=$ship_country?></span> 
			<span align="left" class="table-bg2"><?=$ship_postcode?></span></p><?php }?>
			
			<center><h3 class="h3">Item details</h3>
			<table width="100%"  border="1" class="list"  align="center">
				<tr><th>Supplier :&nbsp; <?=$sup_name?></th>
				<th><span>GST Number :&nbsp; <?=$sup_gst?><span></th></tr>
			</table>	
			<table width="100%"  border="1" align="center" class="list">
			<tr style="font-size:1em; background-color:#D5D5D5">
				<td width="10%" align="center" class="table-bg">HSN Code</td>
				<td width="28%" align="center" class="table-bg">Item Description</td>
				<td width="12%" align="center" class="table-bg">Price &nbsp;<span><div class="" style="display:inline-block;">&#8377; </div></span></td>
				<td width="8%" align="center" class="table-bg">Tax</td>
				<td width="11%" align="center" class="table-bg">Price after TAX &nbsp;<span><div class="" style="display:inline-block;">&#8377; </div></span></td>
				<td width="11%" align="center" class="table-bg">Shipping Charges &nbsp;<span><div class="" style="display:inline-block;">&#8377; </div></span></td>
				<td width="8%" align="center" class="table-bg">Qty</strong></td>
				<td align="center" width="12%" class="table-bg">Total&nbsp;<span><div class="" style="display:inline-block;">&#8377; </div></span></td>
			</tr>
			<?php 	
			$Productinfo="Test Products";
			get_rst("select * from ord_items where cart_id='".$cart_id."' and sup_id=$sup_id",$row,$rst);

			do{
				$cart_price = $row["cart_price"];
				$cart_qty = $row["cart_qty"];
				$prod_id = $row["prod_id"];
				$cart_price_tax = $row["cart_price_tax"];
				get_rst("select hsn_code from prod_mast where prod_id=0$prod_id", $prow);
				?>
				<tr>
				<td rowspan="1" align="center" valign="middle" class="table-bg2"><p><?=$prow["hsn_code"]?></p></td>
				<td rowspan="1" align="center" valign="middle" class="table-bg2"><p><?=$row["item_stock_no"]." - ".$row["item_name"]?></a></p></td>
				<td rowspan="1" align="center" valign="middle" class="table-bg2"><?=formatNumber($row["cart_price"],2)?>&nbsp;</td>
				<td rowspan="1" align="center" valign="middle" class="table-bg2">
				<?php 
				IF(floatval($row["tax_percent"])>0){
					echo($row["tax_percent"]."% ");
				}?>
				</td>
				<td valign="middle" align="center" class="table-bg2">
				<?php echo formatNumber($cart_price_tax,2)?>&nbsp;
				</td>
				<?php if($row["ship_amt"]==0){?>
					<td  align="center" style="color: #006633;"><b>Free</b></td><?php }else{?>
					<td  align="center"><?=$row["ship_amt"]?>&nbsp;</td>
				<?php }?>
				
				<td valign="middle" align="center" class="table-bg2">
					<?=$cart_qty?>
				</td>
				<td valign="middle" align="right" class="table-bg2"><strong><?=formatNumber(($cart_qty * $cart_price_tax)+$row["ship_amt"],2)?></strong>&nbsp;&nbsp;</td>

				</tr>
			<?php }while($row = mysqli_fetch_assoc($rst)); ?>
			
			
			</table>

			<table width="100%"  border="1" class="list">
			<?php		
			$item_total = $row_s["item_total"];
			$shipping_charges = $row_s["shipping_charges"]."";
			$ord_total = $row_s["ord_total"];
			$vat_percent = $row_s["vat_percent"];
			$vat_value = $row_s["vat_value"];
			
			if($shipping_charges.""==""){
				$v_shipping_charges = "As per the delivery area";
			}else{
				$v_shipping_charges = "Rs.".formatNumber($shipping_charges,2);
			} ?>

			<tr>
			<td align="right"><strong><p><span class="green-basket">Sub-Total (exc. Tax):</span></p></strong></td>
			<td align="right">Rs.<?=FormatNumber($item_total,2)?>&nbsp;</td>
			
			</tr>	
			<tr>
			<td width="65%" align="right"><strong><p><span class="green-basket">Shipping Charges:</span></p></strong></td>
			<?php if($shipping_charges == 0){?>
			<td width="25%" align="right"> Free Delivery &nbsp;</td><?php }else{?>
			<td width="25%" align="right"><?=$v_shipping_charges?>&nbsp;</td>
			<?php }?>
			</tr>

			<tr>
			<td align="right"><strong><p><span class="green-basket">Total Tax :</span></strong></p></td>
			<td align="right">Rs.<?=FormatNumber($vat_value,2)?>&nbsp;</td>
			</tr>

			<tr>
			<td align="right"><strong><p><span class="green-basket">Total Price :</span></strong></p></td>
			<td align="right"><strong>Rs.<?=FormatNumber($ord_total,2)?></strong>&nbsp;</td>
			</tr>

			<tr>
			<td align="right"><strong><p><span class="green-basket">Total Price (Round-Off)</span></p></strong></td>
			<td align="right"><strong>Rs.<?=round($ord_total,0,PHP_ROUND_HALF_UP)?></strong>&nbsp;</td>
			</tr>
			</table>
		</div>
			<?php }?>
		<style>
			div.order_info{
				page-break-after:always;
			}
		</style>
			<?php
				$qry = "Select middle_panel from cms_pages where page_name='returns'";
				if(get_rst($qry, $row)){
					$cms_middle_panel = $row["middle_panel"];
				}
			?>
			<div class="order_info">
				<?php echo $cms_middle_panel; ?>
			</div>
	</div>
	</center>
</form>

<?php require_once("inc_admin_footer.php"); ?>