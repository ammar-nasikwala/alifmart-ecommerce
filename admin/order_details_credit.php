<?php
require_once("inc_admin_header.php");

$id = func_read_qs("id");
$sup_id = func_read_qs("sid");

if(get_rst("select * from ord_summary where ord_id=0$id and sup_id=0$sup_id",$row_s,$rst_s)){
	$cart_id = $row_s["cart_id"];
	$delivery_status = $row_s["delivery_status"];
	$pay_status = $row_s["pay_status"];
	
}else{
	header('location: manage_bt_details.php');
	js_redirect("order_list_credit.php");
}

if(func_read_qs("act")=="bt"){
	$fld_arr = array();
	$fld_arr["bt_bank_name"] = func_read_qs("bt_bank_name");
	$fld_arr["bt_bank_branch"] = func_read_qs("bt_bank_branch");
	$fld_arr["bt_ref_no"] = func_read_qs("bt_ref_no");
	$fld_arr["bt_date"] = func_read_qs("bt_date");

	$sql = func_update_qry("ord_details",$fld_arr," where cart_id=$cart_id");
	execute_qry($sql);	
	get_rst("select user_name,user_email from user_mast where user_type='FM'",$row_a);
	get_rst("select memb_fname from member_mast where memb_id='".$_SESSION["memb_id"]."'",$row_b);
	$mail_b = "Dear ".$row_a["user_name"]."<br> ".$row_b["memb_fname"]." has updated the payment details, please do needful.";
	send_mail($row_a['user_email'],"Buyer- ".$row_b["memb_fname"]." Payment updates",$mail_b);
	$mail_ba = "Hello, <br> ".$row_b["memb_fname"]." has updated the payment details, please do needful.";
	send_mail($info,"Buyer- ".$row_b["memb_fname"]." Payment updates",$mail_ba);
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
	
	$bt_bank_name = "".$row["bt_bank_name"];
	$bt_bank_branch = "".$row["bt_bank_branch"];
	$bt_ref_no = "".$row["bt_ref_no"];
	$bt_date = "".$row["bt_date"];
	
}

if(get_rst("select ind_buyer, memb_vat, memb_company from member_mast where memb_email='".$bill_email."'",$row_m)){
	$memb_company = $row_m["memb_company"];
	$memb_vat = $row_m["memb_vat"];
}
?>
<link rel="stylesheet" type="text/css" href="../lib/jsDatePick/jsDatePick_ltr.min.css" />
<script type="text/javascript" src="../lib/jsDatePick/jsDatePick.min.1.3.js"></script>
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
window.onload = function(){
	new JsDatePick({
		useMode:2,
		target:"bt_date",
		dateFormat:"%Y-%m-%d",
		cellColorScheme:"beige"
	});
};
function upd_bt_details(){
	if(document.getElementById("bt_name").value=="" || document.getElementById("bt_branch").value=="" ||document.getElementById("bt_chq").value=="" ||document.getElementById("bt_date").value==""){
		alert("Enter complete bank details");
	} else{
		alert("Your bank transfer details are saved successfully");
	}
	
}
</script>

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
			<tr>
				<?php if($row_s["pay_status"] != "Paid") {?>
					<td colspan="4" style="color:#f0ad4e" align="center"><strong>Order payment is due on <?php echo Date('jS F, Y', strtotime("+30 days", strtotime($row_s["ord_date"]))); ?></strong></td>
				<?php } ?>
			</tr>
		</table>
		<?php if($row_s["pay_method"]=="BT"){?>
		<br>
		<form name="frm_BT" action="edit_bt_details.php" method="post">
			<input type="hidden" name="id" value="<?=$cart_id?>">
			<input type="hidden" name="act" value="bt">
			<input type="hidden" name="sup_id" value="">
			<input type="hidden" name="item_id" value="">
			<input type="hidden" name="order_no" value="">
			<?php if($row_s["pay_method"]=="BT"){
				if($row_s["buyer_action"]=="Cancelled" || ($bt_bank_name != "" && $bt_bank_branch != "" && $bt_ref_no != "" && $bt_date != "")){?>
			
			<table width="80%"  border="1" align="center" class="checkout" >
				<tr>
				<td align="left" colspan="10" class="table-bg3"><p>Bank Transfer Details</p></td>
				</tr>
				<tr>
					<td width="165" align="right">Bank Name</td>
					<td align="left"><input type="text" disabled class="" name="bt_bank_name" tabindex="1" value="<?=$bt_bank_name?>"></td>
					
					<td  align="right">Branch</td>
					<td align="left"><input type="text" disabled tabindex="2" name="bt_bank_branch" value="<?=$bt_bank_branch?>"></td>

					<td rowspan="2" style="vertical-align: middle;"><input type="submit" disabled tabindex="5" class="btn btn-warning" value=" Update "></td>

				</tr>
				<tr>
					<td width="165" align="right">Cheque/Ref No.</td>
					<td align="left"><input type="text" disabled tabindex="3" name="bt_ref_no" value="<?=$bt_ref_no?>"></td>
					
					<td  align="right">Date</td>
					<td align="left"><input type="text" disabled style="width:141px; height:21px;" tabindex="4" readonly id="bt_date" name="bt_date" value="<?=formatDate($bt_date)?>"></td>
				</tr>
				<tr>
				</tr>
				
			</table>
			<?php }else{?>	
			<table width="80%"  border="1" align="center" class="checkout" >
				<tr>
				<td align="left" colspan="10" class="table-bg3"><p>Bank Transfer Details</p></td>
				</tr>
				<tr>
					<td width="165" align="right">Bank Name</td>
					<td align="left"><input type="text" class="" id="bt_name" name="bt_bank_name" tabindex="1" value="<?=$bt_bank_name?>"></td>
					
					<td  align="right">Branch</td>
					<td align="left"><input type="text" tabindex="2" id="bt_branch" name="bt_bank_branch" value="<?=$bt_bank_branch?>"></td>

					<td rowspan="2" style="vertical-align: middle;" ><input type="submit" id="upd" tabindex="5" class="btn btn-warning" value=" Update " onclick="javascript: upd_bt_details();"></td>

				</tr>
				<tr>
					<td width="165" align="right">Cheque/Ref No.</td>
					<td align="left"><input type="text" id="bt_chq" tabindex="3" name="bt_ref_no" value="<?=$bt_ref_no?>"></td>
					
					<td  align="right">Date</td>
					<td align="left"><input type="text" style="height:auto;" tabindex="4" id="bt_date" name="bt_date" value="<?=formatDate($bt_date)?>"></td>
				</tr>
				<tr>
				</tr>
				
			</table>
			</form>
			<?php }
			}
		}?>			
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
	do{
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
		<td xwidth="20%" align="left" class="table-bg"><p><strong>HSN Code</strong></p></td>
		<td xwidth="32%" align="left" class="table-bg"><p><strong>Product Name</strong></p></td>
		<td xwidth="14%" align="left" class="table-bg"><p><strong>Price</strong></p></td>
		<td xwidth="14%" align="left" class="table-bg"><p><strong>Tax</strong></p></td>
		<td xwidth="14%" align="left" class="table-bg"><p><strong>Price after TAX</strong></p></td>
		<td align="left" class="table-bg"><p><strong>Shipping Charges</strong></p></td>
		<td xwidth="14%" align="left" class="table-bg"><p><strong>Qty</strong></p></td>
		<td align="center" width="12%" align="left" class="table-bg"><p><strong>Total</strong></p></td>
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
			get_rst("select sup_commission from prod_sup where sup_id=0$sup_id and prod_id=0$prod_id", $rw_com);
			$sup_commission = $sup_commission + (($cart_price * $cart_qty) * intval($rw_com["sup_commission"]) / 100);
			get_rst("select hsn_code from prod_mast where prod_id=0$prod_id", $prow);
			?>
			<tr style="background-color:<?=$item_color?>;">
			<td rowspan="1" class="table-bg2"><a href="<?=get_prod_url($prod_id,"")?>"><img src="<?=show_img($row["item_thumb"])?>" width="71" xheight="73" border="0" alt="<?=$row["item_name"]?>" /></a></td>
			<td rowspan="1" align="center" valign="middle" class="table-bg2"><p><?=$prow["hsn_code"]?></p></td>
			<td rowspan="1" align="left" valign="middle" class="table-bg2"><p><?=$row["item_stock_no"]." - ".$row["item_name"]?></a></p></td>
			<td rowspan="1" align="right" valign="middle" class="table-bg2">Rs.<?=formatNumber($cart_price,2)?>&nbsp;</td>
			<td rowspan="1" align="center" valign="middle" class="table-bg2">
			<?php echo($tax_percent."% ");?>
			</td>
			<td valign="middle" class="table-bg2">
			Rs.<?php echo formatNumber($cart_price_tax,2) ?>&nbsp;
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
		<?php }while($row = mysqli_fetch_assoc($rst)); ?>
		
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
		}
		?>

		<tr>
		<td align="right"><p><span class="green-basket">Sub-Total (exc. Tax):</span></p></td>
		<td align="right">Rs.<?=FormatNumber($item_total,2)?>&nbsp;</td>
		
		</tr>	
		<tr>
		<td width="65%" align="right"><p><span class="green-basket">Shipping:</span></p></td>
		<?php if($shipping_charges == 0){?>
		<td width="25%" align="right"> Free Delivery &nbsp;</td><?php }else{?>
		<td width="25%" align="right"><?=$v_shipping_charges?>&nbsp;</td>
		<?php }?>
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
		<br>
	<?php
	}while($row_s = mysqli_fetch_assoc($rst_s));
	?>
	<table width="80%"  border="0" class="list"  align="center">
		<?php 
		$coupon_amt = 0;
		if(get_rst("select coupon_id, coupon_amt,coupon_deduct from ord_coupon where cart_id=$cart_id", $row_c)){
			get_rst("select coupon_code from offer_coupon where coupon_id=".$row_c["coupon_id"], $row_oc);
			$coupon_amt = $row_c["coupon_amt"]-$row_c["coupon_deduct"];
			?>
			<tr class="border_bottom">
				<td align="right" class="table-bg"><strong><span class="green-basket">Coupon Applied (<?=$row_oc["coupon_code"];?>):</span></strong></td>
				<td align="right" width="100px" class="table-bg"><strong><div style="display: inline-block;" class="rupee-sign">&#8377; </div> <span><?=FormatNumber($coupon_amt,2)?></span></strong>&nbsp;</td>
			</tr>		
		<?php }?>
	<tr>
	<?php
		if($row_c["coupon_amt"] == $row_c["coupon_deduct"]){?>
			<td align="right" class="table-bg"><strong><span class="green-basket">Total Amount Payable:</span></strong></td>
		<td align="right" width="100px" class="table-bg"><strong><div style="display: inline-block;" class="rupee-sign">&#8377; </div> <?=FormatNumber($ord_pay_total,2)?></strong>&nbsp;</td>
			
		<?php }else{?>
		<td align="right" class="table-bg"><strong><span class="green-basket">Total Amount Payable:</span></strong></td>
		<td align="right" width="100px" class="table-bg"><strong><div style="display: inline-block;" class="rupee-sign">&#8377; </div> <?=FormatNumber($ord_pay_total - $coupon_amt,2)?></strong>&nbsp;</td>
		<?php }?>
	</tr>
	</table>
	<br>
	<center>
	
	<?php
	$status_disabled="";
	if($row_s["buyer_action"]=="Cancelled" || $_SESSION["admin"]=="FM" || $_SESSION["admin"]=="AM"){
		$status_disabled="disabled";
	}
	?>
	<table width="80%"  border="0" align="center">
	<tr>
	<td>
		Delivery Status: <?=$delivery_status;?>
	</td>
	<td>
		<?php if($_SESSION["admin"]=="FM"){
			$status_disabled="";
		}?>
		Payment Status: <?=$pay_status;?>
	</td>
	</tr>
	<tr>
	<td align="center" colspan="2"><input type="button" class="btn btn-warning" value=" Print Invoice " onclick="javascript: print_page('ord_info');"/></td>
	</tr>
	
	</table>
	<div id="ord_info">
		<div class="order_info">
		<?php get_rst("select * from ord_summary where cart_id=0$cart_id",$row_s,$rst_s); ?>
		<table width="100%"  border="1" align="center" class="checkout" >
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
				<td>Order No.</td><td><?php echo $row_s["ord_no"]?></td>
				<td>Order Date.</td><td><?php echo $row_s["ord_date"]?></td>
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
				<?=formatNumber($cart_price_tax,2)?>&nbsp;
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
				<td align="right"><strong><p><span class="green-basket">Sub-Total (exc. Tax):</span></p></strong></td>
				<td align="right">Rs.<?=formatNumber($item_total,2)?>&nbsp;</td>
				
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
				<td align="right">Rs.<?=formatNumber($vat_value,2)?>&nbsp;</td>
				</tr>

				<tr>
				<td align="right"><strong><p><span class="green-basket">Total Price :</span></strong></p></td>
				<td align="right"><strong>Rs.<?=formatNumber($ord_total,2)?></strong>&nbsp;</td>
				</tr>
			</table>
		
			<table width="100%"  border="0" class="list"  align="center">
			<?php 
			$coupon_amt = 0;
			if(get_rst("select coupon_id, coupon_amt,coupon_deduct from ord_coupon where cart_id=$cart_id", $row_c)){
				get_rst("select coupon_code from offer_coupon where coupon_id=".$row_c["coupon_id"], $row_oc);
				$coupon_amt = $row_c["coupon_amt"]-$row_c["coupon_deduct"];
				?>
				<tr class="border_bottom">
					<td align="right" class="table-bg"><strong><span class="green-basket">Coupon Applied (<?=$row_oc["coupon_code"];?>):</span></strong></td>
					<td align="right" width="100px" class="table-bg"><strong><div style="display: inline-block;" class="rupee-sign">&#8377; </div> <span><?=FormatNumber($coupon_amt,2)?></span></strong>&nbsp;</td>
				</tr>		
			<?php }?>
			<tr>
			<?php
				if($row_c["coupon_amt"] == $row_c["coupon_deduct"]){ ?>
					<td align="right" class="table-bg"><strong><span class="green-basket">Total Amount Payable:</span></strong></td>
				<td align="right" width="100px" class="table-bg"><strong><div style="display: inline-block;" class="rupee-sign">&#8377; </div> <?=FormatNumber($ord_pay_total,2)?></strong>&nbsp;</td>
					
				<?php }else{ ?>
				<td align="right" class="table-bg"><strong><span class="green-basket">Total Amount Payable:</span></strong></td>
				<td align="right" width="100px" class="table-bg"><strong><div style="display: inline-block;" class="rupee-sign">&#8377; </div> <?=FormatNumber($ord_pay_total - $coupon_amt,2)?></strong>&nbsp;</td>
				<?php } ?>
			</tr>
			</table>
		</div>
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

<?php
require_once("inc_admin_footer.php");
?>