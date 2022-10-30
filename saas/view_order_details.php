
<?php
require_once("inc_admin_header.php");
$cart_id = func_read_qs("id");

$bt_bank_name="";
$bt_bank_branch="";
$bt_ref_no="";
$bt_date="";

$act = func_read_qs("act");
$sup_id = func_read_qs("sup_id");
$item_id = func_read_qs("item_id");
$order_no = func_read_qs("order_no");
$cc=" Alif Mart <".$_SESSION["admin_email"].">";
$ord_no = "";

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

if($act<>""){
	$sql = "select ord_no from ord_summary where cart_id=$cart_id";
	if(get_rst($sql,$row)){
		$ord_no = $row["ord_no"];
	}
}

//$sql_where = " and user_id=".$_SESSION["memb_id"];	." and user_type='".$_SESSION["user_type"]."'";

$sql = "select * from ord_summary where cart_id=$cart_id";
//die($sql);
if(get_rst($sql,$row_s,$rst_s)){
	//$cart_id = $row_s["cart_id"];
}else{
	//header('location: memb_view_orders.php');
	js_alert("Sorry! Unable to display the order with id $cart_id");
	js_redirect("view_order.php");
}
?>

</head>
<body>
<?php

$incomp_msg="";
$ord_cancelled="";
?>

<style>
.rupee-sign{font-size:14px;display:inline-block}
</style>
<div id = "gif_show" style="display:none"></div>

	<table width="100%"  border="0" align="center" class="checkout" >

		<?php if($row_s["buyer_action"]=="Cancelled"){
			$ord_cancelled="1";
			?>
			<tr style="background-color:#FF0000;">
				<td style="background-color:#FF0000;" align="center" colspan="10"><font color='white'>This is a cancelled Order</font></td>
			</tr>
		<?php }?>
		
	</table>
	
<form name="frm_BT" action="memb_order_details.php" method="post">
	<input type="hidden" name="id" value="<?=$cart_id?>">
	<input type="hidden" name="act" value="bt">
	<input type="hidden" name="sup_id" value="">
	<input type="hidden" name="item_id" value="">
	<input type="hidden" name="order_no" value="">
<div id="ord_detail">
	
<table width="90%"  border="1" align="center" class="checkout" >		
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
			
		<table width="90%"  border="1" align="center" class="checkout" >
			
			<tr>
			<th align="left" xwidth="150px"><p>Mode of Payment: </p></th>
			<td align="left" class="table-bg2"><p>
				<?=$row_s["pay_method_name"]?></p></td>
			</tr>
			
		</table>
		
	<hr color="#96b809" width="100%" size="1px" align="center" />
	
	<?php if(get_rst("select po_no from po_details where cart_id=".$cart_id,$row_po)){?>
		<table width="90%"  border="1" align="center" class="checkout" >
			<tr>
				<th align="left" width="30%"><p>Purchase Order No:</p></th>
				<td align="left" class="table-bg2"><p>
				<?=$row_po["po_no"]?></p></td>
			</tr>
		</table>
 <?php }?>
	</div>
	<br>
	<center><h3>Item details</h3></center>
	
	<?php if($incomp_msg <> ""){?>
			<hr color="#FF0000" size="1px" width="91%" align="center" />
			<p>
				<span class="red">
				<?=$incomp_msg?>
				</span>
			</p>		
			<hr color="#FF0000" size="1px" width="91%" align="center" />
		<?php }?>
	</div>
	<div class="center-panel">
	<?php
	$ord_pay_total = 0;
	$all_pending = "1";
	do{
		$sup_id = $row_s["sup_id"];
	?>
	
	<table width="90%"  border="1" align="center" class="list">
	<tr>
		<td align="center" colspan="4" class="table-bg"><b>Order No. </b><?=$row_s["ord_no"]?></td>
		<td align="center" colspan="5" class="table-bg"><b>Order Date. </b><?=formatDate($row_s["ord_date"])?></td>
	</tr>
		<tr>
		<td xwidth="20%" align="left" class="table-bg"><p><strong>Product Image</strong></p></td>
		<td xwidth="32%" align="left" class="table-bg"><p><strong>Product Name</strong></p></td>
		<?php IF(func_var($row["user_type"])=="S"){?>
			<td xwidth="32%" align="left" class="table-bg"><p><strong>Seller</strong></p></td>
		<?php }?>
		<td width="12%" align="left" class="table-bg"><p><strong>Price</strong></p></td>
		<td xwidth="14%" align="left" class="table-bg"><p><strong>Tax</strong></p></td>
		<td xwidth="14%" align="left" class="table-bg"><p><strong>Price after TAX</strong></p></td>
		<td align="left" class="table-bg"><p><strong>Shipping Charges</strong></p></td>
		<td xwidth="14%" align="left" class="table-bg"><p><strong>Qty</strong></p></td>
		<td align="center" width="12%" align="left" class="table-bg"><p><strong>Total</strong></p></td>
		</tr>
	<?php
	$Productinfo="Test Products";
	
	get_rst("select * from ord_items where cart_id=$cart_id and sup_id=$sup_id",$row,$rst);
	do{
		$cart_price = $row["cart_price"];
		$cart_qty = $row["cart_qty"];
		$prod_id = $row["prod_id"];
		$cart_price_tax = $row["cart_price_tax"];
		get_rst("select prod_name from prod_mast where prod_id=$prod_id",$rw_name);		
		$item_color = "";
		if($row["item_buyer_action"].""<>""){
			$item_color = "#d9534f";
		}
		?>
		<tr style="background-color:<?=$item_color?>;">
		<td rowspan="1" class="table-bg2"><a href="<?=get_prod_url($prod_id,"",$rw_name["prod_name"])?>"><img src="<?=show_img($row["item_thumb"])?>" width="71" xheight="73" border="0" alt="<?=$row["item_name"]?>" /></a></td>
		<td rowspan="1" align="center" valign="middle" class="table-bg2"><p><?=$row["item_stock_no"]." - ".$row["item_name"]?></a></p></td>
		<td rowspan="1" align="center" valign="middle" class="table-bg2"><div class="rupee-sign">&#8377; </div> <?=formatNumber($row["cart_price"],2)?>&nbsp;</td>
			<td rowspan="1" align="center" valign="middle" class="table-bg2">
		<?php
		IF(floatval($row["tax_percent"])>0){
			echo($row["tax_percent"]."% ");
		}?>
		</td>
		<td valign="middle" align="center" class="table-bg2">
		<div class="rupee-sign">&#8377; </div> <?=formatNumber($cart_price_tax,2)?>&nbsp;
		</td>
		<?php if($row["ship_amt"]==0){?>
			<td  align="center" style="color: #006633;"><b>Free</b></td><?php }else{?>
			<td  align="center"><div class="rupee-sign">&#8377; </div> <?=$row["ship_amt"]?>&nbsp;</td>
			<?php }?>
			
		<td valign="middle" align="center" class="table-bg2">
			<?=$cart_qty?>
		</td>
		<td valign="middle" align="center" class="table-bg2"><strong><div class="rupee-sign">&#8377; </div> <?=formatNumber(($cart_qty * $cart_price_tax)+$row["ship_amt"],2)?></strong>&nbsp;&nbsp;</td>
		
		</tr>
		<?php
	}while($row = mysqli_fetch_assoc($rst));
	
	?>	
	</table>
	<?php if($row_s["item_total"]<>0){?>
	<table width="90%"  border="1" class="list"  align="center">
	<?php		
	$item_total = $row_s["item_total"];
	$shipping_charges = $row_s["shipping_charges"]."";
	$ord_total = $row_s["ord_total"];
	$vat_percent = $row_s["vat_percent"];
	$vat_value = $row_s["vat_value"];
	$ord_pay_total = $ord_pay_total + $ord_total;
	
	if($shipping_charges.""==""){
		$v_shipping_charges = "As per the delivery area";
	}else{
		$v_shipping_charges = formatNumber($shipping_charges,2);
	}
	?>

		<tr>
		<td align="right"><p><span class="green-basket">Sub-Total (exc. Tax):</span></p></td>
		<td align="right"><div class="rupee-sign">&#8377; </div> <?=FormatNumber($item_total,2)?>&nbsp;</td>
		
		</tr>	
		<tr>
		<td width="65%" align="right"><p><span class="green-basket">Shipping Charges :</span></p></td>
		<?php if($shipping_charges == 0){?>
		<td width="25%" align="right"> Free Delivery &nbsp;</td><?php }else{?>
		<td width="25%" align="right"><div class="rupee-sign">&#8377; </div> <?=$v_shipping_charges?>&nbsp;</td>
		<?php }?>
		</tr>

		<tr>
		<td align="right"><p><span class="green-basket">Total Tax :</span></p></td>
		<td align="right"><div class="rupee-sign">&#8377; </div> <?=FormatNumber($vat_value,2)?>&nbsp;</td>
		</tr>

		<tr>
		<td align="right"><p><span class="green-basket">Total Price :</span></p></td>
		<td align="right"><strong><div class="rupee-sign">&#8377; </div> <?=FormatNumber($ord_total,2)?></strong>&nbsp;</td>
		</tr>
		
	</table>
	<?php }
	}while($row_s = mysqli_fetch_assoc($rst_s));
	?>
	
	<center>
	<table width="90%"  border="1" class="list" >
	<?php 
	$coupon_amt = 0;
	get_rst("select sum(cancel_coupon) as cancel_coupon,sum(cancel_amount) as cancel_amount from track_refund where cart_id=$cart_id",$ro_c);
	if(get_rst("select coupon_id,coupon_amt from ord_coupon where cart_id=$cart_id", $row_c)){
		get_rst("select coupon_code from offer_coupon where coupon_id=".$row_c["coupon_id"], $row_oc);
		if($ro_c["cancel_coupon"] <> ""){
			$coupon_amt = $row_c["coupon_amt"] - $ro_c["cancel_coupon"];
		?>
		<tr>
			<td align="right"><p><span class="green-basket">Coupon Applied (<?=$row_oc["coupon_code"];?>):</span></p></td>
			<td align="right" width="100px" >
				<strong><div class="rupee-sign">&#8377; </div> <span><?=FormatNumber($coupon_amt,2)?></span></strong>&nbsp;
			</td>
		</tr>		
	<?php }
	else{
		$coupon_amt = $row_c["coupon_amt"];?>
		<tr>
			<td align="right" ><p><span class="green-basket">Coupon Applied (<?=$row_oc["coupon_code"];?>):</span></p></td>
			<td align="right" width="100px" >
				<strong><div class="rupee-sign">&#8377; </div> <span><?=FormatNumber($coupon_amt,2)?></span></strong>&nbsp;
			</td>
		</tr>	
	<?php }
	}?>
	<tr class="border_bottom">
	<?php
		get_rst("select sum((cart_price_tax * cart_qty) + ship_amt) as ord_total from ord_items where cart_id=$cart_id",$row_t);
		if($ro_c["cancel_amount"] <> ""){
			
			$total_amount = $row_t["ord_total"] - ($ro_c["cancel_amount"] + $ro_c["cancel_coupon"]);
			$total_amount = $total_amount - ($row_c["coupon_amt"] - $ro_c["cancel_coupon"]);
	?>
		<td align="right" ><p><span class="green-basket">Total Order Amount:</span></p></td>
		<td align="right" width="100px" ><strong><div class="rupee-sign">&#8377; </div> <span><?=FormatNumber($total_amount,2)?></span></strong>&nbsp;</td>
		<?php }else{
			$total_amount = $row_t["ord_total"] - $row_c["coupon_amt"];
		?>
		<td align="right" ><p><span class="green-basket">Total Order Amount:</span></p></td>
		<td align="right" width="100px" ><strong><div class="rupee-sign">&#8377; </div> <span><?=FormatNumber($total_amount,2)?></span></strong>&nbsp;</td>
	<?php }?>
	</tr>
	</table>
	</center>
	</form>


<?php require_once("inc_admin_footer.php");?>