<?php

require("inc_init.php");

$memb_id = $_SESSION["memb_id"];

if(get_rst("select * from cart_details where memb_id=$memb_id",$row)){
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

if(!get_rst("select * from cart_summary where user_id=$memb_id and item_count IS NOT NULL order by sup_id",$row_s,$rst_s)){
	header('location: basket.php');
	js_redirect("basket.php");
}

if(get_rst("select * from member_mast where memb_email='".$bill_email."'",$row_m)){
	$memb_company = $row_m["memb_company"];
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title><?=$cms_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="DESCRIPTION" content="<?=$cms_meta_key?>"/>
<meta name="KEYWORDS" content="<?=$cms_meta_desc?>"/>
<link href="styles/styles.css" rel="stylesheet" type="text/css" />
<!--<link href="styles/snackbar.css" rel="stylesheet" type="text/css" />-->
<script src="scripts/scripts.js" type="text/javascript"></script>

<script type="text/javascript">

function js_continue(){
	window.location.href="prod_list.php";
}

function js_checkout(){
	window.location.href="checkout.php";
}

function js_prev(){
	window.location.href = "checkout.php"
}

function js_next(){
	var po_no = document.getElementById("po_number").value;
	var response = call_ajax("ajax.php","process=add_po_number&po_no=" + po_no);
	display_gif();
	document.frmCheckout.submit();
}

function js_coupon_remove(coupon_id, cart_id)
{
	var response = call_ajax("ajax.php","process=remove_coupon&coupon_id=" + coupon_id + "&cart_id=" + cart_id);
	location.reload();
}

function apply_coupon(memb_id, cart_id, ord_amt) 
{
	var coupon_code = document.getElementById("coupon_code").value;
	if(coupon_code != ""){
	coupon_code = coupon_code.toUpperCase();	
	var result = call_ajax("ajax.php","process=apply_coupon_code&memb_id=" + memb_id + "&cart_id=" + cart_id + "&coupon_code=" + coupon_code + "&ord_amt=" + ord_amt);
	var coupon_amt = parseFloat(result);
	//coupon_amt = Math.round(coupon_amt * 100) / 100;
	var obj_lbl = document.getElementById("snackbar");
	if(isNaN(coupon_amt)){
		obj_lbl.innerHTML = result;
		//obj_lbl.style.color="#FF5588";
		show_snackbar_msg('snackbar');
	}else{
		var ord_total = ord_amt - coupon_amt;
		document.getElementById("ord_total_value").innerHTML = ord_total;
		document.getElementById("coupon_applied").value = coupon_code;
		obj_lbl.innerHTML = "Coupon applied! You saved an extra Rs. " + coupon_amt;
		//obj_lbl.style.color="#44FF88";
		show_snackbar_msg('snackbar');
		setTimeout(function(){location.reload();},700);
	}
	}else{
		var obj_lbl = document.getElementById("snackbar");
		obj_lbl.innerHTML = "Please enter valid coupon code.";
		//obj_lbl.style.color="#FF5588";
		show_snackbar_msg('snackbar');
	}
}
</script>
</head>

<body>

<?php
require("header.php");
$incomp_msg="";
?>

<div id = "gif_show" style="display:none"></div>
<div id="content_hide">
<div id="contentwrapper">
<div id="contentcolumn">
<div class="center-panel">
	<div class="you-are-here">
		<p align="left">
			YOU ARE HERE:<a href="index.php" title="">Home</a> | <span class="you-are-normal">Order Summary<a href="returns.php" title="Cancellation & Returns Policy" style="float:right">Cancellation & Returns Policy</a></span>
		</p>
	</div>
	
	<br>
	<center>
	<table border="1" width="90%" class="table_checkout boxed-shadow">
		<tr>
			<th>1. Your Details</th>
			<th>2. Order Summary</th>
			<td>3. Payment</td>
			<td>4. Order Confirmation</td>			
		</tr>
	</table>
	</center>
	<br>
	
	<form name="frmCheckout" method="post" action="payment.php">
		<input type="hidden" name="act" value="upd">
		
		<table width="100%"  border="0" align="center" class="checkout" >
	<tr>
	<td align="right"></td>
	<td align="left" class="table-bg2"><p>Your Billing Details</p></td>
	<td align="left" class="table-bg2"><p>Your Shipping Details</p></td>
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
	<?php if($row_m["ind_buyer"]==1){ ?>
		<td align="left" class="table-bg2"><?=$memb_company?></td>
		<td align="left" class="table-bg2"><?=$memb_company?></td><?php }else{?>
		<td align="left" class="table-bg2"><?=$bill_name?></td>
		<td align="left" class="table-bg2"><?=$ship_name?></td><?php } ?>
	</tr>
	
	<?php
	$name_arr=explode(" ",$bill_name);
	
	$bill_fname = "";
	$bill_sname = $name_arr[count($name_arr)-1];
	for($i=0;$i<count($name_arr)-1;$i++){
		if($name_arr[$i]<>"Mr" AND $name_arr[$i]<>"Mrs" AND $name_arr[$i]<>"Ms" AND $name_arr[$i]<>"Dr"){
			$bill_fname = $bill_fname." ".$name_arr[$i];
		}
	}
	$bill_fname = trim($bill_fname);
	?>
	
	<input type="hidden" name="firstname" value="<?=$bill_fname?>">
	<input type="hidden" name="lastname" value="<?=$bill_sname?>">
	<input type="hidden" name="email" value="<?=$bill_email?>">
	<input type="hidden" name="phone" value="<?=$bill_tel?>">
	<input type="hidden" name="address1" value="<?=$bill_add1?>">
	<input type="hidden" name="city" value="<?=$bill_city?>">
	<input type="hidden" name="state" value="<?=$bill_state?>">
	<input type="hidden" name="country" value="<?=$bill_country?>">
	<input type="hidden" name="zipcode" value="<?=$bill_postcode?>">
	<input type="hidden" name="udf1" value="<?=$_SESSION["cart_id"]?>">
		
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

	
	<table width="100%"  border="0" align="center" class="checkout" >
	<tr>
	<td width="25%" align="left" class="table-bg2"><p>Additional Instructions</p></td>
	<td align="left"  class="table-bg2"><?=$ord_instruct?>
	</td>
	</tr>

	
	</table>

	<table width="100%"  border="0" align="center" class="checkout" >
	<tr>
	<td align="left" class="table-bg3"><p>Mode of Payment</p></td>
	<span>
	<td align="left" class="table-bg2"><p>
	<?=$row_s["pay_method_name"]?></p></td></span>
	</tr>
	
	</table>
	
	<table width="100%"  border="0" align="center" class="checkout" >
	<tr>
	<td align="left" class="table-bg3"><p>Purchase Order No</p></td>
	<span>
	<td align="left" class="table-bg2">
	<?php if(get_rst("select po_no from po_details where cart_id=".$_SESSION["cart_id"],$row_po)){?>
		<input type="text" maxlength="50" id="po_number" readonly class="form-control textbox-mid" title="Enter Purchase Order No" name="po_no" value="<?=$row_po["po_no"]?>"></td></span>
	<?php }else{ ?>
		<input type="text" maxlength="50" id="po_number" class="form-control textbox-mid" title="Enter Purchase Order No" name="po_no">
	<?php } ?>
	</tr>
	
	</table>
	<h1 style="text-transform:none;">Product Details</h1>
	<?php if($incomp_msg <> ""){?>
			<hr color="#FF0000" size="1px" width="91%" align="center" />
			<p>
				<span class="red">
				<?=$incomp_msg?>
				</span>
			</p>		
			<hr color="#FF0000" size="1px" width="91%" align="center" />
		<?php } ?>
	</div>
	<div class="center-panel">
	<?php
	$ord_pay_total = 0;
	$ord_item_total = 0;
	$ord_tax_total = 0;
	do{
	$sup_id = $row_s["sup_id"];
	?>

	<table width="100%"  border="0" align="center" class="list">
	<tr>
	<td width="12%" align="center" class="table-bg"><span class="theme-blue">Product Image</span></td>
	<td width="27%" align="center" class="table-bg"><span class="theme-blue">Product Name</span></td>
	<td width="10%" align="center" class="table-bg"><span class="theme-blue">Price</span></td>
	<td width="7%" align="center" class="table-bg"><span class="theme-blue">Tax %</span></td>
	<td width="12%" align="center" class="table-bg"><span class="theme-blue">Price Inc. TAX</span></td>
	<td width="11%" align="center" class="table-bg"><span class="theme-blue">Shipping Charge</span></td>
	<td width="5%" align="center" class="table-bg"><span class="theme-blue">Qty</span></td>
	<td width="12%" align="center" class="table-bg"><span class="theme-blue">Total</span></td>
	</tr>
	<?php
	$cart_id = "";
	$Productinfo="Test Products";
	get_rst("select * from cart_items where memb_id=$memb_id and item_wish<>1 and sup_id=$sup_id",$row,$rst);
	do{
		$cart_price = $row["cart_price"];
		$cart_qty = $row["cart_qty"];
		$prod_id = $row["prod_id"];
		$cart_price_tax = $row["cart_price_tax"];
		$cart_id = $row["cart_id"];
		get_rst("select prod_name from prod_mast where prod_id=$prod_id",$row_name);
		?>
		<tr>
		<td rowspan="1" align="center" class="table-bg2"><a href="<?=get_prod_url($prod_id,"",$row_name["prod_name"])?>"><img src="<?=show_img($row["item_thumb"])?>" width="71" xheight="73" border="0" alt="<?=$row["item_name"]?>" /></a></td>
		<td rowspan="1" align="center" valign="middle" class="table-bg2"><p><?=$row["item_stock_no"]." - ".$row["item_name"]?></a></p></td>
		<td rowspan="1" align="center" valign="middle" class="table-bg2"><div class="rupee-sign">&#8377; </div> <?=formatNumber($row["cart_price"],2)?>&nbsp;</td>
		<td rowspan="1" align="center" valign="middle" class="table-bg2">
		<?php
		IF(floatval($row["tax_percent"])>0){
			echo($row["tax_percent"]);
		} ?>
		</td>
		<td align="center" valign="middle" class="table-bg2">
		<div class="rupee-sign">&#8377; </div> <?=formatNumber($cart_price_tax,2)?>&nbsp;
		</td>
		<?php if($row["ship_amt"]==0){ ?>
			<td width="5%" align="center" style="color: #006633;"><b>Free</b></td><?php
			}else{ ?>
			<td width="5%" align="center"><div class="rupee-sign">&#8377; </div> <?=$row["ship_amt"]?>&nbsp;</td>
			<?php } ?>
		
		<td valign="middle" align="center" class="table-bg2">
			<?=$cart_qty?>
		</td>
		<td valign="middle" align="center" class="table-bg2"><div class="rupee-sign">&#8377; </div> <?=formatNumber(($cart_qty * $cart_price_tax)+$row["ship_amt"],2)?>&nbsp;&nbsp;</td>

		</tr>
		<?php
	}while($row = mysqli_fetch_assoc($rst));
	?>
	
	</table>

	<table width="100%"  border="0" class="list">
	<?php		
	$item_total = $row_s["item_total"];
	$shipping_charges = $row_s["shipping_charges"]."";
	$ord_total = $row_s["ord_total"];
	$vat_percent = $row_s["vat_percent"];
	$vat_value = $row_s["vat_value"];
	$ord_pay_total = $ord_pay_total + $ord_total;
	$ord_item_total = $ord_item_total + $item_total;
	$ord_tax_total = $ord_tax_total + $vat_value;
	
	if($shipping_charges.""==""){
		$v_shipping_charges = "As per the delivery area";
	}else{
		$v_shipping_charges = formatNumber($shipping_charges,2);
	}
	?>
	<tr>
	<td align="right"><p><span class="theme-blue">Sub-Total (exc. Tax):</span></p></td>
	<td align="right"><div class="rupee-sign">&#8377; </div> <?=FormatNumber($item_total,2)?>&nbsp;</td>
	
	</tr>	
	<tr>
	<td width="65%" align="right"><p><span class="theme-blue">Shipping:</span></p></td>
	<?php if($v_shipping_charges==0){?>
	<td width="25%" align="right"><div > </div> Free Delivery &nbsp;</td><?php }else{?>
	<td width="25%" align="right"><div class="rupee-sign">&#8377;</div> <?=$v_shipping_charges?>&nbsp;</td>
	<?php } ?>
	</tr>

	<tr>
	<td align="right"><p><span class="theme-blue">Total Tax :</span></p></td>
	<td align="right"><div class="rupee-sign">&#8377; </div> <?=FormatNumber($vat_value,2)?>&nbsp;</td>
	</tr>
	<tr>
	<td align="right"><p><span class="theme-blue">Total Price (incl. TAX):</span></p></td>
	<td align="right"><strong><div class="rupee-sign">&#8377; </div> <?=FormatNumber($ord_total,2)?></strong>&nbsp;</td>
	<input type="hidden" name="amount" value="<?=$ord_total?>">
	</tr>

	<tr>
	<td align="right"><p><span class="theme-blue">Total Price (Round-Off):</span></p></td>
	<td align="right"><strong><div class="rupee-sign">&#8377; </div> <?=round($ord_total,0,PHP_ROUND_HALF_UP)?></strong>&nbsp;</td>
	<input type="hidden" name="amount" value="<?=round($ord_total,0,PHP_ROUND_HALF_UP)?>">
	</tr>
	</table>
	<?php
	}while($row_s = mysqli_fetch_assoc($rst_s));
	?>
	<br>
		<table width="100%"  border="0" class="list">
	<?php 
	$coupon_amt = 0;
	$coupon_tax = 0;
	if(get_rst("select coupon_id, coupon_amt from ord_coupon where cart_id=$cart_id", $row_c)){
		get_rst("select coupon_code, max_discount_value from offer_coupon where coupon_id=".$row_c["coupon_id"], $row_oc);
		
		if($row_c["coupon_amt"] > $row_oc["max_discount_value"])
		{
			$coupon_amt = $row_oc["max_discount_value"];
		}else{
			$coupon_amt = $row_c["coupon_amt"];
		}
		$coupon_tax = $coupon_amt * ($ord_tax_total/$item_total);
		?>
		<tr class="border_bottom">
			<td align="right" class="table-bg"><p><span class="theme-blue">Coupon Applied (<?=$row_oc["coupon_code"];?>):</span></p></td>
			<td align="right" width="100px" class="table-bg">
				<strong><div class="rupee-sign">&#8377; </div> <span><?=FormatNumber($coupon_amt,2)?></span></strong>
				<a style="diaplay: inline-block;" href="javascript: js_coupon_remove('<?=$row_c["coupon_id"]?>','<?=$cart_id;?>')" title="Remove Coupon" name="btn_delete"> <span class="color-amber glyf glyphicon glyphicon-remove"></span></a>&nbsp;
			</td>
		</tr>		
	<?php }
	if ($coupon_amt == 0) {
	?>
	
	<tr>
		<td align="right" class="table-bg"><p><span class="theme-blue">Total Amount Payable:</span></p></td>
		<td align="right" width="100px" class="table-bg"><strong><div class="rupee-sign">&#8377; </div> <span id="ord_total_value"><?=FormatNumber($ord_pay_total - $coupon_amt,2)?></span></strong>&nbsp;</td>
	</tr>
	<tr>
		<td align="right" class="table-bg"><p><span class="theme-blue">Total Amount Payable (Round-Off):</span></p></td>
		<td align="right" width="100px" class="table-bg"><strong><div class="rupee-sign">&#8377; </div> <span id="ord_total_value"><?=round($ord_pay_total - $coupon_amt,0,PHP_ROUND_HALF_UP)?></span></strong>&nbsp;</td>
	</tr>
	<?php } else { ?>
	<tr>
		<td align="right" class="table-bg"><p><span class="theme-blue">Total Item  Price:</span></p></td>
		<td align="right" width="100px" class="table-bg"><strong><div class="rupee-sign">&#8377; </div> <span id="ord_total_value"><?=FormatNumber($ord_item_total - $coupon_amt,2)?></span></strong>&nbsp;</td>
	</tr>
	<tr>
		<td align="right" class="table-bg"><p><span class="theme-blue">Total TAX:</span></p></td>
		<td align="right" width="100px" class="table-bg"><strong><div class="rupee-sign">&#8377; </div> <span id="ord_total_value"><?=FormatNumber($ord_tax_total - $coupon_tax,2)?></span></strong>&nbsp;</td>
	</tr>
	<tr>
		<td align="right" class="table-bg"><p><span class="theme-blue">Total Amount Payable:</span></p></td>
		<td align="right" width="100px" class="table-bg"><strong><div class="rupee-sign">&#8377; </div> <span id="ord_total_value"><?=FormatNumber($ord_pay_total - ($coupon_amt + $coupon_tax),2)?></span></strong>&nbsp;</td>
	</tr>
	<tr>
		<td align="right" class="table-bg"><p><span class="theme-blue">Total Amount Payable (Round-Off):</span></p></td>
		<td align="right" width="100px" class="table-bg"><strong><div class="rupee-sign">&#8377; </div> <span id="ord_total_value"><?=round($ord_pay_total - ($coupon_amt + $coupon_tax),0,PHP_ROUND_HALF_UP)?></span></strong>&nbsp;</td>
	</tr>
	<?php } ?>
	</table>
	
	<center>
	<table width="90%"  border="0" align="center" xclass="list">	
	<tr>
	<td align="right">
	Enter Promo Code : <input type="text" style="text-transform:uppercase" maxlength="50" id="coupon_code" class="form-control textbox-mid" title="Enter Promo Code" name="coupon_code" value="">
	<!--<br><p style="float: right;" id="offer_coupon_msg"></p>-->
	<div id="snackbar"></div>
	<input type="hidden" name="coupon_applied" id="coupon_applied" value="">
	</td>
	<td align="right"><input type="button" id="coupon_apply" class="btn btn-warning" value="Apply" onclick="javascript: apply_coupon('<?=$memb_id?>','<?=$cart_id;?>', '<?=$ord_item_total;?>');"/></td>
	</tr>

	<tr>
	<td align="left"><input type="button" class="btn btn-warning" value="Previous" onclick="javascript: js_prev();"/></a></td>
	<td align="right"><input type="button" class="btn btn-warning" value="Next" onclick="javascript: js_next();"/></td>
	</tr>
	</table>
	</center>

	</form>
</div>

</div>
</div>
</div>
<?php
	require("left.php");
	require("footer.php");
?>

</div>
</div>

</body>
<script src="scripts/chat.js" type="text/javascript"></script>
</html>