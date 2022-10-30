<?
require_once("inc_admin_header.php");
require_once("logistics_push.php");

$id = func_read_qs("id");
$act = func_read_qs("act");
$sup_id = $_SESSION["sup_id"];

if(get_rst("select * from ord_summary where ord_id=0$id and sup_id=$sup_id",$row_s,$rst_s)){
	$cart_id = $row_s["cart_id"];
}

if($act<>""){
	$delivery_status_old = func_read_qs("delivery_status_old");
	$delivery_status = func_read_qs("delivery_status");
	$delivery_date = func_read_qs("delivery_date");
	$v_cancelled = "";
	
	if($delivery_status<>"Pending" and $delivery_status_old <> $delivery_status){
		if(get_rst("select item_buyer_action from ord_items where cart_id=$cart_id and sup_id=$sup_id and item_buyer_action is not null")){
			$v_cancelled = "1";
			js_alert("Sorry the order has just been cancelled by the Buyer hence cannot be Dispatched.");
		}	
	}
	
	if($v_cancelled == ""){
		
		$fld_arr = array();

		$fld_arr["delivery_status"] = $delivery_status;
		$fld_arr["delivery_date"] = $delivery_date;
		
		$qry = func_update_qry("ord_summary",$fld_arr," where ord_id=0$id and sup_id=$sup_id");
		execute_qry($qry);
		
		js_alert("Order details updated successfully.");
	}
	
	/*if($delivery_status=="Dispatched"){
		lgs_create_order($id,$msg);
		js_alert($msg);
	}*/
}

if(get_rst("select * from ord_summary where ord_id=0$id and sup_id=$sup_id",$row_s,$rst_s)){
	$cart_id = $row_s["cart_id"];
	$delivery_status = $row_s["delivery_status"];
	$pay_status = $row_s["pay_status"];
	$delivery_date = $row_s["delivery_date"];
}else{
	header('location: order_list.php?dls=1');
	js_redirect("order_list.php?dls=1");
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
?>
<style>
.h3{
	
	margin-top:0px;
	margin-bottom:0px;
	padding-bottom:0px;
	padding-top:0px;
	font-size: 1.3em;
	color:#1E9BDD;
}
.p{
	padding-top:0px;
	padding-bottom:0px;
}
</style>
<script>
function js_save(){
	if(chkForm(document.frm_order)==false){
		return false
	}else{
		document.frm_order.submit();
	}
}

function js_cal_vw(){
	document.getElementById("span_vol_wt").innerHTML = parseFloat(frm_order.pkg_height.value) * parseFloat(frm_order.pkg_width.value) * parseFloat(frm_order.pkg_depth.value)/5000
}

</script>

<script type="text/javascript" src="../lib/frmCheck.js"></script>

<form name="frm_order" action="order_details.php" method="post">
	<input type="hidden" name="act" value="1">
	<input type="hidden" name="id" value="<?=$id?>">
	<center>
		<table width="80%"  border="1" align="center" class="checkout" style="margin: 0 auto;">

			<tr>
				<td><strong>WayBill No.</strong></td><td><?=$row_s["way_billl_no"]?></td>
				<?php $barcode_url = "../lib/barcode.php?codetype=Code39&size=40&text=".$row_s["way_billl_no"]; ?>
				<td><strong>Barcode</strong></td><td class="img-padding"><img alt="Barcode" src="<?=$barcode_url?>" /></td>
			</tr>
			</tr>
			
			<?if($row_s["buyer_action"]=="Cancelled"){?>
			<tr style="background-color:#FF0000;">
				<td align="center" style="background-color:#FF0000;" colspan="10"><font color='white'>This order has been cancelled by the Buyer</font></td>
			</tr>		
			<?}?>
			
			<tr>
				<td><strong>Order No.</strong></td><td><?=$row_s["ord_no"]?></td>

				<td><strong>Order Date.</strong></td><td><?=$row_s["ord_date"]?></td>
			</tr>
		</table>
		<br>
		
		<table width="80%"  border="1" align="center" style="margin: 0 auto;">
		
		<tr>
		<td align="right"></td>
		<td align="left" class=""><p><b>Billing Details</p></td>
		<td align="left" class=""><p><b>Shipping Details</p></td>
		</tr>
		

		<tr>
		<td class="table-bg2"><label for="Name"></label>
		<p><strong>Name:</strong> </p></td>
		<td align="left" class="table-bg2"><?=$bill_name?></td>
		<td align="left" class="table-bg2"><?=$ship_name?></td>
		</tr>
			
		<tr>
		<td class="table-bg2"><label for="Email"></label>
		<p><strong>Email:</strong></p></td>
		<td align="left" class="table-bg2"><?=$bill_email?></td>
		<td align="left" class="table-bg2"><?=$ship_email?></td>	
		</tr>


		<tr>
		<td class="table-bg2"><label for="Email"></label>
		<p><strong>Address 1:</strong></p></td>
		<td align="left" class="table-bg2"><?=$bill_add1?></td>
		<td align="left" class="table-bg2"><?=$ship_add1?></td>	
		</tr>

		<tr>
		<td class="table-bg2"><label for="Email"></label>
		<p><strong>Address 2:</strong></p></td>
		<td align="left" class="table-bg2"><?=$bill_add2?></td>
		<td align="left" class="table-bg2"><?=$ship_add2?></td>	
		</tr>

		<tr>
		<td class="table-bg2"><label for="Email"></label>
		<p><strong>Town / City:</strong></p></td>
		<td align="left" class="table-bg2"><?=$bill_city?></td>
		<td align="left" class="table-bg2"><?=$ship_city?></td>	
		</tr>

		<tr>
		<td class="table-bg2"><label for="Email"></label>
		<p><strong>Post code:</strong></p></td>
		<td align="left" class="table-bg2"><?=$bill_postcode?></td>
		<td align="left" class="table-bg2"><?=$ship_postcode?></td>	
		</tr>
		
		<tr>
		<td class="table-bg2"><label for="Email"></label>
		<p><strong>State:</strong></p></td>
		<td align="left" class="table-bg2"><?=$bill_state?></td>
		<td align="left" class="table-bg2"><?=$ship_state?></td>	
		</tr>
		
		<tr>
		<td class="table-bg2"><label for="Email"></label>
		<p><strong>Country:</strong></p></td>
		<td align="left" class="table-bg2"><?=$bill_country?></td>
		<td align="left" class="table-bg2"><?=$ship_country?></td>	
		</tr>

		<tr>
		<td class="table-bg2"><label for="Email"></label>
		<p><strong>Contact No.:</strong></p></td>
		<td align="left" class="table-bg2"><?=$bill_tel?></td>
		<td align="left" class="table-bg2"><?=$ship_tel?></td>	
		</tr>
		</table>
		
		<hr color="#96b809" width="100%" size="1px" align="center" />
		
		<table width="80%"  border="1" align="center" class="checkout" style="margin: 0 auto;">
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
		
		<center><h3>Item details</h3>

		<?
		$ord_pay_total = 0;
		//do{
			$sup_id = $row_s["sup_id"];
			?>
			
		<table width="80%"  border="1" align="center" class="list" style="margin: 0 auto;">
		<tr>
		<td xwidth="20%" align="left" class="table-bg"><p><strong>Product Image</strong></p></td>
		<td xwidth="32%" align="left" class="table-bg"><p><strong>Product Name</strong></p></td>
		<?IF(func_var($row["user_type"])=="S"){?>
			<td xwidth="32%" align="left" class="table-bg"><p><strong>Seller</strong></p></td>
		<?}?>
		<td xwidth="14%" align="left" class="table-bg"><p><strong>Price</strong></p></td>
		<td xwidth="14%" align="left" class="table-bg"><p><strong>Tax</strong></p></td>
		<td xwidth="14%" align="left" class="table-bg"><p><strong>Price after TAX</strong></p></td>
		<td xwidth="14%" align="left" class="table-bg"><p><strong>Qty</strong></p></td>
		<td width="12%" align="left" class="table-bg"><p><strong>Total</strong></p></td>
		</tr>
		<?	
		$Productinfo="Test Products";
		get_rst("select * from ord_items where cart_id='".$cart_id."' and sup_id=$sup_id",$row,$rst);

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
			$cart_price_tax = $row["cart_price_tax"];
			$tax_percent = $row["tax_percent"];

			?>
			<tr style="background-color:<?=$item_color?>;">
			<td rowspan="1" class="img-padding"><a href="<?=get_prod_url($prod_id,"")?>"><img src="<?=show_img($row["item_thumb"])?>" width="51" height="33" border="0" alt="<?=$row["item_name"]?>" /></a></td>
			<td rowspan="1" align="left" valign="middle" class="table-bg2"><p><?=$row["item_stock_no"]." - ".$row["item_name"]?></a></p></td>
			<?IF(func_var($row["user_type"])=="S"){?>
				<td rowspan="1" align="left" valign="middle" class="table-bg2"><?=$row["sup_name"]?></td>
			<?}?>
			<td rowspan="1" align="right" valign="middle" class="table-bg2">Rs.<?=formatNumber($cart_price,2)?>&nbsp;</td>
			<td rowspan="1" align="center" valign="middle" class="table-bg2">
			<?
			IF(floatval($tax_percent)>0){
				echo($tax_percent."% ");
			}?>
			</td>
			<td valign="middle" class="table-bg2">
			Rs.<?=formatNumber($cart_price_tax,2)?>&nbsp;
			</td>
			
			
			<td valign="middle" align="right" class="table-bg2">
				<?=$cart_qty?>
			</td>
			<td valign="middle" align="right" class="table-bg2"><strong>Rs.<?=formatNumber($cart_qty * $cart_price_tax,2)?></strong>&nbsp;&nbsp;</td>

			</tr>
			<?
		}while($row = mysqli_fetch_assoc($rst));
		
		?>
		
		
		</table>

		<table width="80%"  border="1" class="list" style="margin: 0 auto;">
		<?		
		$item_total = $row_s["item_total"];
		$shipping_charges = $row_s["shipping_charges"]."";
		$ord_total = $row_s["ord_total"];
		$vat_percent = $row_s["vat_percent"];
		$vat_value = $row_s["vat_value"];
		$ord_pay_total = $ord_pay_total + $ord_total;
		
		if($shipping_charges.""==""){
			$v_shipping_charges = "As per the delivery area";
		}else{
			$v_shipping_charges = "Rs.".formatNumber($shipping_charges,2);
		}
		?>

		<tr>
		<td align="right"><strong><p><span class="green-basket">Sub-Total (exc. Tax):</span></p></strong></td>
		<td align="right">Rs.<?=FormatNumber($item_total,2)?>&nbsp;</td>
		
		</tr>	
		<tr>
		<td width="65%" align="right"><strong><p><span class="green-basket">Shipping:</span></p></strong></td>
		<td width="25%" align="right"><?=$v_shipping_charges?>&nbsp;</td>
		</tr>

		<tr>
		<td align="right"><strong><p><span class="green-basket">Total Tax :</span></p></strong></td>
		<td align="right">Rs.<?=FormatNumber($vat_value,2)?>&nbsp;</td>
		</tr>

		<tr>
		<td align="right"><strong><p><span class="green-basket">Total Price (incl. TAX):</span></p></strong></td>
		<td align="right"><strong>Rs.<?=FormatNumber($ord_total,2)?></strong>&nbsp;</td>
		</tr>
		</table>
	<?
		$pkg_disabled="disabled";
	?>
	<br>
	
	<table width="80%"  border="1" align="center" class="table_form" style="width:80%;">
		<tr>
			<th colspan="2" align="left" >Payment Status: <?=$row_s["pay_status"]?></th>
		</tr>
	</table>
	
	<?if($row_s["buyer_action"]<>"Cancelled" and $row_s["pay_status"]<>"Pending"){?>
		<br>
		<table width="80%"  border="1" align="center" class="table_form" style="width:80%;">
		<tr>
			<th colspan="3" align="left" >Packaging Details</th>
		</tr>
		<tr>
			<td>Weight:</td>
			<td>
			<input type="text" name="pkg_weight_kgs" value="<?=$row_s["pkg_weight_kgs"]?>" <?=$pkg_disabled?> size="5" id="110" title="Weight in Kgs"> Kgs + 
			<input type="text" name="pkg_weight" value="<?=$row_s["pkg_weight"]?>" <?=$pkg_disabled?> size="5" id="110" title="Weight in gms"> gms
			</td>
			<td></td>
		</tr>
		
		<tr>
			<td>Dimension (H x W x D):</td><td>
			<input type="text" name="pkg_height" value="<?=$row_s["pkg_height"]?>" <?=$pkg_disabled?> size="5" id="110" title="Height in cms"> cms x 
			<input type="text" name="pkg_width" value="<?=$row_s["pkg_width"]?>" <?=$pkg_disabled?> size="5" id="110" title="Width in cms"> cms x 
			<input type="text" name="pkg_depth" value="<?=$row_s["pkg_depth"]?>" <?=$pkg_disabled?> size="5" id="110" title="Depth in cms"> cms
			</td>
			<td></td>
		</tr>
		<tr>
			<td>Volumetric weight:</td>
			<td>
			<?if($pkg_disabled==""){?>
				<input type="button" class="btn btn-warning" value="Calculate" onclick="javascript: js_cal_vw();"> 
			<?}?>
			&nbsp; &nbsp;<span id="span_vol_wt"></span>
			</td><td></td>
		</tr>
	
		<tr>
		<td>
		Delivery Status:
		<input type="hidden" name="delivery_status_old" value="<?=$delivery_status?>">
		<select name="delivery_status">
			<?func_option("Dispatched","Dispatched",func_var($delivery_status))?>
			<?func_option("Delivered","Delivered",func_var($delivery_status))?>
		</select>
		</td>
		<td>Delivery Date : <input type="text" class="" name="delivery_date" value="<?=$delivery_date?>" placeholder="dd/mm/yyyy"/></td>
		<td><input type="button" class="btn btn-warning" value=" Update " onclick="javascript: js_save();"/></td>
		</tr>
	</table>
	
	<?}?>

</center>
</form>
<?
require_once("inc_admin_footer.php");
?>
