<?php
require_once("inc_admin_header.php");
require_once("../seller/logistics_push.php");
$id = func_read_qs("id");
$sup_id = func_read_qs("sid");

//if($_SESSION["admin"]=""){

if(get_rst("select * from ord_summary where ord_id=0$id and sup_id=0$sup_id and delivery_status='Pending'",$row_s,$rst_s)){
	$cart_id = $row_s["cart_id"];
	$pay_status = $row_s["pay_status"];
	
}else{
	header('location: manage_order_change.php');
	js_redirect("manage_order_change.php");
}

$act = func_read_qs("act");

if($act<>""){
		$fld_arr = array();
		
		$fld_arr["prod_name"] = func_read_qs("prod_name");
		$fld_arr["sup_id"] = intval(func_read_qs("sup_id"));
		
		$prod_size = func_read_qs("prod_size");
		$prod_unit = func_read_qs("prod_unit");
}
if(isset($_POST["btn_update"])){
	get_rst("select * from ord_items where cart_id='".$cart_id."' and sup_id=0$sup_id",$row_re,$rst);
	do{
		$prod_id = $row_re["prod_id"];
		$new_sup_id = func_read_qs($row_re["cart_item_id"]);
		$old_sup_id = $sup_id;
		$prod_id = $row_re["prod_id"];
		$memb_id = $row_re["memb_id"];
		$item_count = $row_s["item_count"];
		$ord_no = $row_s["ord_no"];
		if(isset($_POST["chk_seller_".$row_re["cart_item_id"]])){
			if($row_re["new_seller_price"] <> ""){
				$msg="Cannot re-assigne this order. Please cancel the order";
			}elseif($new_sup_id == $old_sup_id){
				$msg = "Record updated successfully.";
			}else{
				re_assign_seller($cart_id, $prod_id, $new_sup_id, $old_sup_id, $memb_id, $ord_no, $item_count,$row_re["cart_price"],$row_re["tax_value"],$row_re["ship_amt"],$row_re["cart_qty"],$row_re["tax_percent"]);
				$msg="Record updated successfully.";
			}
		}
	}while($row_re = mysqli_fetch_assoc($rst));?>
	
	<script>
		alert("<?=$msg?>");
	    window.location.href="manage_order_change.php";
	</script>	
<?php }?>

<script>
//Print Form
function select_seller(id, value){
	document.getElementById(id).value = value;
	//alert(document.getElementById(id).value);
}
function change_seller(obj){
	document.getElementById("cart_item_id").value = obj;
	var id = document.getElementById("cart_item_id").value;
	
	var chk = document.getElementById("chk_seller_"+obj);
	
	if(!chk.checked)
		document.getElementById("sel_seller_"+obj).disabled = true;
	else
		document.getElementById("sel_seller_"+obj).disabled = false;
	
}
window.onload = function(){
	$('select').attr('disabled', 'disabled');
}
</script>
<form name="frm_order" action="edit_order_change.php" method="post">
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
		<td xwidth="14%" align="left" class="table-bg"><p><strong>Price</strong></p></td>
		<td xwidth="14%" align="left" class="table-bg"><p><strong>Tax</strong></p></td>
		<td xwidth="14%" align="left" class="table-bg"><p><strong>Price after TAX</strong></p></td>
		<td align="left" class="table-bg"><p><strong>Shipping Charges</strong></p></td>
		<td xwidth="14%" align="left" class="table-bg"><p><strong>Qty</strong></p></td>
		<td align="Left" width="12%" align="left" class="table-bg"><p><strong>Total</strong></p></td>
		<td align="Left" width="12%" align="left" class="table-bg"><p><strong>Seller</strong></p></td>
		</tr>
		<?php 	
		$Productinfo="Test Products";
		get_rst("select * from ord_items where cart_id='".$cart_id."' and sup_id=0$sup_id",$row,$rst);
		$item_total = 0;
		$vat_value = 0;
		$sup_commission = 0;
		get_rst("select sup_company from sup_mast where sup_id=$sup_id", $row_sp);
		$sup_company = $row_sp["sup_company"];
		do{
			$cart_price = 0;
			$item_color = "";
			$cart_item_id = $row["cart_item_id"];
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
			<td valign="middle" align="right" class="table-bg2"><strong>Rs.<?=formatNumber(($cart_qty * $cart_price_tax)+($row["ship_amt"]),2)?></strong>&nbsp;&nbsp;</td>
			<td align="left">
			<input id="chk_seller_<?=$cart_item_id?>" type="checkbox" name="chk_seller_<?=$cart_item_id?>" value="1" <?=sel_("1",$chk_seller."")?> onchange="javascript: change_seller('<?=$cart_item_id?>');" /><select id="sel_seller_<?=$cart_item_id?>" title="Select Seller" name="<?=$cart_item_id?>" onchange="javascript: select_seller(<?=$cart_item_id?>,this.value)" tabindex="12">
			<?=create_cbo("select sup_id,sup_company from prod_sup where sup_status=1 and prod_id=$prod_id and out_of_stock is NULL",$sup_id)?>	
			</select>
			<input type="hidden" id="<?=$cart_item_id?>" name="sup_<?=$cart_item_id?>" value="">
			</td>
			</tr>
		<?php }while($row = mysqli_fetch_assoc($rst)); ?>
	</table>
	
	<input type="hidden" name="sup_total_commission" value="<?=$sup_commission?>">
	<input type="hidden" name="cart_item_id" id="cart_item_id" value="">
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
	<table width="80%"  border="0" class="list"  align="center">
	<tr>
		<td align="right" class="table-bg"><strong><span class="green-basket">Total Amount Payable:</span></strong></td>
		<td align="right" width="100px" class="table-bg"><strong>Rs.<?=FormatNumber($ord_pay_total,2)?></strong>&nbsp;</td>
	</tr>
	</table>
	<br>
	<center>
	
	<?php 
	$status_disabled="";
	if($row_s["buyer_action"]=="Cancelled" || $_SESSION["admin"]=="FM" || $_SESSION["admin"]=="AM" || $delivery_status == "Dispatched"){
		$status_disabled="disabled";
	}
	?>
	<table width="80%"  border="0" align="center">
	<tr>
	<td>
		<p>Delivery Status: <?=$row_s["delivery_status"]?></p>
	</td>
	<td>
		<p>Payment Status: <?=$pay_status?></p>
	</td>
	</tr>
	<tr>
	<td colspan="2" align="center"><input type="submit" class="btn btn-warning" name="btn_update" value=" Update "/></a></td>
	</tr>
	</table>
	
	</center>
</form>

<?php
require_once("inc_admin_footer.php");
?>

