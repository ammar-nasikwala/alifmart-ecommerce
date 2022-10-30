<?php
require_once("inc_admin_header.php");

$id = func_read_qs("id");
$sup_id = func_read_qs("sid");


//if($_SESSION["admin"]=""){

if(get_rst("select * from ord_items where cart_item_id=0$id and sup_id=0$sup_id",$row_o)){
	$cart_id = $row_o["cart_id"];

	get_rst("select * from ord_summary where cart_id='".$cart_id."' and sup_id=0$sup_id",$row_s,$rst_s);
		if(get_rst("select refund_status from track_refund where cart_item_id=0$id and sup_id=$sup_id",$row_re)){
		$refund_status = $row_re["refund_status"];
	
}
}else{
	header('location: edit_track_refund.php');
	js_redirect("edit_track_refund.php");
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
	
	$refund_status = func_read_qs("refund_status");
	if($refund_status."" == ""){
		$refund_status = "Pending";
	}
	execute_qry("update track_refund set refund_status='$refund_status' where cart_item_id=0$id and sup_id=0$sup_id");
	$v_cancelled = "";
	
	if($v_cancelled == ""){
		js_alert("Refund status updated successfully.");	
	}
}
if(get_rst("select * from member_mast where memb_email='".$bill_email."'",$row_m)){
	$memb_company = $row_m["memb_company"];
}
if($_POST["update_ref"]){
		$ord_no=$row_s["ord_no"];
		
		$mail_body = "Dear $bill_name,<br> Your order $ord_no has been refunded successfully.";
		$sms_body = "Dear $bill_name, Your order $ord_no has been refunded successfully.";
		send_mail($bill_email,"Company-Name - Order refund: $ord_no",$mail_body,"",$cc);
		send_sms($bill_tel,$sms_body);
		
		//js_alert("Order has been cancelled successfully.");
		header("Location:edit_track_refund.php?id=$id&sid=$sup_id");
	}
get_rst("select refund_amt,coupon_deduct from track_refund where cart_item_id=$id and sup_id=$sup_id",$row_rtn);
?>
<script>
//Save Details
function js_update(){
	document.frm_refund.submit();
}
</script>

<form name="frm_refund" method="post">
	<input type="hidden" name="act" value="1">
	<input type="hidden" name="id" value="<?=$id?>">
	<input type="hidden" name="sid" value="<?=$sup_id?>">
	
	<div id="ord_detail">
		<table width="80%"  border="1" align="center" class="checkout" >
			<tr>
				<td>WayBill No.</td><td><?=$row_s["way_billl_no"]?></td>
				<?php $barcode_url = "../lib/barcode.php?codetype=Code39&size=40&text=".$row_s["way_billl_no"]; ?>
				<td>Barcode</td><td><img alt="Barcode" src="<?=$barcode_url?>" /></td>
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
			<tr>
			<td align="left" class="table-bg2"></td>
			
			<td align="left" class="table-bg2">

			</td>
			
			<td align="left" valign="middle" class="table-bg2">

			</td>
			</tr>

			<tr>
			<td align="right" class="table-bg2"><label for="Name"></label>
			<p>Name :</p></td>
			<?php if($row_m["ind_buyer"]==1){?>
			<td align="left" class="table-bg2"><?=$memb_company?></td>
			<td align="left" class="table-bg2"><?=$memb_company?></td><?php }else{?>
			<td align="left" class="table-bg2"><?=$bill_name?></td>
			<td align="left" class="table-bg2"><?=$ship_name?></td><?php }?>
			</tr>
				.
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
		$sup_alias="";
		if(get_rst("select sup_company,sup_alias from sup_mast where sup_id=0$sup_id",$row_sup)){
			$sup_name=$row_sup["sup_company"]; 
			$sup_alias=$row_sup["sup_alias"];
		}
		?>
		<table width="80%"  border="1" class="list"  align="center">
			<tr><th>Supplier: <?=$sup_name?></th>
			<th><span>Alias: <?=$sup_alias?><span></th></tr>
		</table>
		
		<table width="80%"  border="1" align="center" class="list">
		<tr>
		<td xwidth="20%" align="left" class="table-bg"><p><strong>Product Image</strong></p></td>
		<td xwidth="32%" align="left" class="table-bg"><p><strong>Product Name</strong></p></td>
		<?php IF(func_var($row["user_type"])=="S"){?>
			<td xwidth="32%" align="left" class="table-bg"><p><strong>Seller</strong></p></td>
		<?php }?>
		<td xwidth="14%" align="left" class="table-bg"><p><strong>Price</strong></p></td>
		<td xwidth="14%" align="left" class="table-bg"><p><strong>Tax</strong></p></td>
		<td xwidth="14%" align="left" class="table-bg"><p><strong>Price after TAX</strong></p></td>
		<td align="left" class="table-bg"><p><strong>Shipping Charges</strong></p></td>
		<td xwidth="14%" align="left" class="table-bg"><p><strong>Qty</strong></p></td>
		<td align="center" width="12%" align="left" class="table-bg"><p><strong>Total</strong></p></td>
		</tr>
		<?php 	
		$Productinfo="Test Products";
	if($row_s["buyer_action"]==""){
		get_rst("select * from ord_items where cart_item_id=$id and sup_id=0$sup_id",$row,$rst);
		$sup_commission = 0;
		
			$item_color = "";
			if($row["item_buyer_action"].""<>""){
				$item_color = "#d9534f";
				$cart_price = $row["cart_price"];
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
			get_rst("select sup_commission from prod_sup where sup_id=0$sup_id and prod_id=0$prod_id", $rw_com);
			$sup_commission = $sup_commission + (($cart_price * $cart_qty) * intval($rw_com["sup_commission"]) / 100);
			get_rst("select prod_name from prod_mast where prod_id=$prod_id",$rw);
			?>
			<tr style="background-color:<?=$item_color?>;">
			<td rowspan="1" class="table-bg2"><a href="<?=get_prod_url($prod_id,"",$rw["prod_name"])?>"><img src="<?=show_img($row["item_thumb"])?>" width="71" xheight="73" border="0" alt="<?=$row["item_name"]?>" /></a></td>
			<td rowspan="1" align="left" valign="middle" class="table-bg2"><p><?=$row["item_stock_no"]." - ".$row["item_name"]?></a></p></td>
			<td rowspan="1" align="right" valign="middle" class="table-bg2">Rs.<?=formatNumber($cart_price,2)?>&nbsp;</td>
			<td rowspan="1" align="center" valign="middle" class="table-bg2">
			<?php echo($tax_percent."% ");?>
			</td>
			<td valign="middle" class="table-bg2">
			Rs.<?=formatNumber($cart_price_tax,2)?>&nbsp;
			</td>
			<?php if($row["ship_amt"]==0){?>
			<td  align="center" style="color: #006633;"><b>Free</b></td><?php }else{?>
			<td  align="center">Rs.<?=$row["ship_amt"]?>&nbsp;</td>
			<?php }?>
				
			<td valign="middle" align="right" class="table-bg2">
				<?=$cart_qty?>
			</td>
			<td valign="middle" align="right" class="table-bg2"><strong>Rs.<?=formatNumber(($cart_qty * $cart_price_tax)+($cart_qty*$row["ship_amt"]),2)?></strong>&nbsp;&nbsp;</td>

			</tr>
			<?php
	}else{
		get_rst("select * from ord_items where cart_id='".$cart_id."' and sup_id=0$sup_id",$row,$rst);
		$item_total = 0;
		$vat_value = 0;
		$sup_commission = 0;
		do{
			$cart_price = 0;
			$item_color = "";
			if($row["item_buyer_action"].""<>""){
				$item_color = "#d9534f";
				$cart_price = $row["cart_price"];
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
			get_rst("select sup_commission from prod_sup where sup_id=0$sup_id and prod_id=0$prod_id", $rw_com);
			$sup_commission = $sup_commission + (($cart_price * $cart_qty) * intval($rw_com["sup_commission"]) / 100);
		
			?>
			<tr style="background-color:<?=$item_color?>;">
			<td rowspan="1" class="table-bg2"><a href="<?=get_prod_url($prod_id,"")?>"><img src="<?=show_img($row["item_thumb"])?>" width="71" xheight="73" border="0" alt="<?=$row["item_name"]?>" /></a></td>
			<td rowspan="1" align="left" valign="middle" class="table-bg2"><p><?=$row["item_stock_no"]." - ".$row["item_name"]?></a></p></td>
			<td rowspan="1" align="right" valign="middle" class="table-bg2">Rs.<?=formatNumber($cart_price,2)?>&nbsp;</td>
			<td rowspan="1" align="center" valign="middle" class="table-bg2">
			<?php echo($tax_percent."% ");?>
			</td>
			<td valign="middle" class="table-bg2">
			Rs.<?=formatNumber($cart_price_tax,2)?>&nbsp;
			</td>
			<?php if($row["ship_amt"]==0){?>
			<td  align="center" style="color: #006633;"><b>Free</b></td><?php }else{?>
			<td  align="center">Rs.<?=$row["ship_amt"]?>&nbsp;</td>
			<?php }?>
				
			<td valign="middle" align="right" class="table-bg2">
				<?=$cart_qty?>
			</td>
			<td valign="middle" align="right" class="table-bg2"><strong>Rs.<?=formatNumber(($cart_qty * $cart_price_tax)+($cart_qty*$row["ship_amt"]),2)?></strong>&nbsp;&nbsp;</td>

			</tr>
			<?php
		}while($row = mysqli_fetch_assoc($rst));
	}
		
		?>
		
		
		</table>
		<input type="hidden" name="sup_total_commission" value="<?=$sup_commission?>">
		<table width="80%"  border="1" class="list"  align="center">
		<?php
		if($row_s["buyer_action"]=='Cancelled'){
			get_rst("select sum(ship_amt) as shipping_charges from ord_items where cart_id='".$cart_id."' and sup_id=$sup_id",$rw);
		}else{
			get_rst("select ship_amt as shipping_charges from ord_items where cart_item_id=$id and sup_id=$sup_id",$rw);
		}
		
		$ord_total = $item_total + $vat_value + $rw["shipping_charges"];
		$vat_percent = $row_s["vat_percent"];
		$ord_pay_total = $ord_pay_total + $ord_total;
		
		?>

		<tr>
		<td align="right"><p><span class="green-basket">Sub-Total (exc. Tax):</span></p></td>
		<td align="right">Rs.<?=FormatNumber($item_total,2)?>&nbsp;</td>
		
		</tr>	
		<tr>
		<td width="65%" align="right"><p><span class="green-basket">Shipping:</span></p></td>
		<td width="25%" align="right">Rs.<?=formatNumber($rw["shipping_charges"],2)?>&nbsp;</td>
		</tr>

		<tr>
		<td align="right"><p><span class="green-basket">Total Tax :</span></p></td>
		<td align="right">Rs.<?=FormatNumber($vat_value,2)?>&nbsp;</td>
		</tr>

		<tr>
		<td align="right"><p><span class="green-basket">Total Price (incl. TAX):</span></p></td>
		<td align="right"><strong>Rs.<?=FormatNumber($ord_total,2)?></strong>&nbsp;</td>
		</tr>
		</table>
	<table width="80%"  border="0" class="list"  align="center">
	<?php 
		$coupon_amt = 0;
		if(get_rst("select coupon_id, coupon_amt from ord_coupon where cart_id=$cart_id", $row_c)){
			get_rst("select coupon_code from offer_coupon where coupon_id='".$row_c["coupon_id"]."'",$row_oc);
			?>
			<tr class="border_bottom">
				<td align="right" class="table-bg"><strong><span class="green-basket">Coupon Deductables (<?=$row_oc["coupon_code"];?>):</span></strong></td>
				<td align="right" width="100px" class="table-bg"><strong><div style="display: inline-block;" class="rupee-sign">&#8377; </div> <span>-<?=FormatNumber($row_rtn["coupon_deduct"],2)?></span></strong>&nbsp;</td>
			</tr>		
		<?php }?>
	<tr>
		<td align="right" class="table-bg"><strong><span class="green-basket">Total Amount to be refund:</span></strong></td>
		<td align="right" width="100px" class="table-bg"><strong>Rs.<?=FormatNumber($row_rtn["refund_amt"],2)?></strong>&nbsp;</td>
	</tr>
	
	<tr>
	<td>
		Refund Status:
		<select name="refund_status">
			<?php func_option("Pending","Pending",func_var($refund_status))?>
			<?php func_option("Paid","Paid",func_var($refund_status))?>
		</select>
	</td>
	</tr>
	<tr>
	<td align="left"><input type="submit" class="btn btn-warning" name="update_ref" value=" Update " onclick="javascript: js_update();"/></a></td>
	</tr>
	</table>
	</center>
</form>

<?php
require_once("inc_admin_footer.php");
?>

