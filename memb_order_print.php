<?php

require("inc_init.php");

$cart_id = func_read_qs("id");

$sql_where = " where user_id=".$_SESSION["memb_id"]." and user_type='".$_SESSION["user_type"]."'";

if(get_rst("select * from ord_summary where cart_id=$cart_id $sql_where",$row_s,$rst)){
	//$cart_id = $row_s["cart_id"];
}else{
	js_alert("Sorry! Unable to display the order with id $cart_id");
	?>
	<script>
		window.close()
	</script>
	<?php
	js_redirect("memb_view_orders.php");
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
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title><?=$cms_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="DESCRIPTION" content="<?=$cms_meta_key?>"/>
<meta name="KEYWORDS" content="<?=$cms_meta_desc?>"/>
<link href="styles/styles.css" rel="stylesheet" type="text/css" />

</script>
</head>

<body style="width:980px;background-color:#ffffff;">

<?php
$incomp_msg="";
?>

<div class="center-panel" >

	<h2 style="font-size:20px;font-family:verdana;">Company-Name - Order Details</h2>
	
	<table width="100%" border="0" align="center" class="checkout">
		<tr>
			<td colspan="10"></td>
		</tr>

		<tr>
			<td align="center"><b>Order No. </b><?=$row_s["ord_no"]?></td>

			<td align="center"><b>Order Date. </b><?=formatDate($row_s["ord_date"])?></td>
		</tr>
	</table>
	
	<table width="100%"  border="0" align="center" class="checkout" style="width:980px;background-color:#ffffff;">
	
	<tr>
	<td align="right"></td>
	<td align="left" class="table-bg2"><p><b>Your Billing Details</p></td>
	<td align="left" class="table-bg2"><p><b>Your Shipping Details</p></td>
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
	<td align="left" class="table-bg2"><?=$bill_name?></td>
	<td align="left" class="table-bg2"><?=$ship_name?></td>
	</tr>
		
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

	<?if($ord_instruct.""<>""){?>
		<table width="100%"  border="0" align="center" class="checkout" style="width:980px;background-color:#ffffff;">
		<tr>
		<td width="175" align="left" class="table-bg2"><p>Additional Instructions</p></td>
		<td align="left"  class="table-bg2"><?=$ord_instruct?>
		</td>
		</tr>
		</table>
	<?}?>
	
	<table width="100%"  border="0" align="center" class="checkout" >
	<tr>
	<td align="left" class="table-bg3"><p>Mode of Payment</p></td>
	</tr>
	<tr>
	<td align="left" class="table-bg2"><p>
	<?=$row_s["pay_method_name"]?></p></td>
	</tr>
	
	</table>
	
	<h1>Cart</h1>
	<?if($incomp_msg <> ""){?>
			<hr color="#FF0000" size="1px" width="91%" align="center" />
			<p>
				<span class="red">
				<?=$incomp_msg?>
				</span>
			</p>		
			<hr color="#FF0000" size="1px" width="91%" align="center" />
		<?}?>
	</div>
	<div class="center-panel">
	<?
	if(get_rst("select * from ord_items where cart_id='".$cart_id."'",$row,$rst)){
	?>

		<table width="100%"  border="0" align="center" class="list">
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

	do{
		$cart_price = $row["cart_price"];
		$cart_qty = $row["cart_qty"];
		$prod_id = $row["prod_id"];
		$cart_price_tax = $row["cart_price_tax"];
		
		
		?>
		<tr>
		<td rowspan="1" class="table-bg2"><a href="<?=get_prod_url($prod_id,"")?>"><img src="images/products/thumb/<?=$row["item_thumb"]?>" width="71" xheight="73" border="0" alt="<?=$row["item_name"]?>" /></a></td>
		<td rowspan="1" align="left" valign="middle" class="table-bg2"><p><?=$row["item_stock_no"]." - ".$row["item_name"]?></a></p></td>
		<?IF(func_var($row["user_type"])=="S"){?>
			<td rowspan="1" align="left" valign="middle" class="table-bg2"><?=$row["sup_name"]?></td>
		<?}?>
		<td rowspan="1" align="right" valign="middle" class="table-bg2">Rs.<?=formatNumber($row["cart_price"],2)?>&nbsp;</td>
		<td rowspan="1" align="center" valign="middle" class="table-bg2">
		<?
		IF(floatval($row["tax_percent"])>0){
			echo($row["tax_percent"]."% ");
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

	<table width="100%"  border="0" class="list">
	<?		
	$item_total = $row_s["item_total"];
	$shipping_charges = $row_s["shipping_charges"]."";
	$ord_total = $row_s["ord_total"];
	$vat_percent = $row_s["vat_percent"];
	$vat_value = $row_s["vat_value"];
	
	if($shipping_charges.""==""){
		$v_shipping_charges = "As per the delivery area";
	}else{
		$v_shipping_charges = "Rs.".formatNumber($shipping_charges,2);
	}
	?>
	<tr>
	<td colspan="2" align="center"><hr color="#96b809" width="100%" size="1px" align="center" />
	</td>
	</tr>
	<tr>
	<td align="right"><p><span class="green-basket">Sub-Total (exc. Tax):</span></p></td>
	<td align="right">Rs.<?=FormatNumber($item_total,2)?>&nbsp;</td>
	
	</tr>	
	<tr>
	<td width="65%" align="right"><p><span class="green-basket">Shipping:</span></p></td>
	<td width="25%" align="right"><?=$v_shipping_charges?>&nbsp;</td>
	</tr>

	<tr>
	<td align="right"><p><span class="green-basket">Total Tax :</span></p></td>
	<td align="right">Rs.<?=FormatNumber($vat_value,2)?>&nbsp;</td>
	</tr>
	<tr>
	<td colspan="2" align="center"><hr color="#96b809" width="100%" size="1px" align="center" />&nbsp;
	</td>
	</tr>
	<tr>
	<td align="right"><p><span class="green-basket">Total Price (incl. TAX):</span></p></td>
	<td align="right"><strong>Rs.<?=FormatNumber($ord_total,0)?></strong>&nbsp;</td>
	</tr>
	</table>

	</form>
	<?php
	}else{
	?>
		<hr color="#FF0000" size="1px" width="91%" align="center" />
		<p>
			<span class="red">
				Order details not found.
			</span>
		</p>
		<hr color="#FF0000" size="1px" width="91%" align="center" />
	<?php
	}
	?>

</div>

<script>
	window.print();
</script>

</body>
</html>
