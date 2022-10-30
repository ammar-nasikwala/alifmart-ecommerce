<?php

require("inc_init.php");

$act = func_read_qs("act");
$cc=" Company-Name <".$_SESSION["admin_email"].">";
$print = func_read_qs("print");


if($_SESSION["ord_id"]==""){
		$cart_id = func_read_qs("id");
		$sql_where = " where cart_id='".$cart_id."'";
	}else{
	$sql_where = " where ord_id='" .$_SESSION["ord_id"]."'";
}
$sql_where = $sql_where. " and item_count>0 and user_id=".$_SESSION["memb_id"];

if(get_rst("select * from ord_summary $sql_where",$row_s,$rst_s)){
	$cart_id = $row_s["cart_id"];
}else{
	js_redirect("basket.php");
}

if($act=="a"){
	if(get_rst("select delivery_status from ord_summary where cart_id=$cart_id and delivery_status<>'Pending'")){
		js_alert("Sorry the order has just been dispatched hence cannot be cancelled.");
	}else{
		$sql = "update ord_summary set buyer_action='Cancelled',buyer_date=now() where cart_id=$cart_id";
		execute_qry($sql);

		$sql = "update ord_items set item_buyer_action='Cancelled',buyer_date=now() where cart_id=$cart_id";
		execute_qry($sql);
		
		get_rst("select sup_id,sup_email,sup_contact_no from sup_mast where sup_id in (select sup_id from ord_summary where cart_id=$cart_id)",$row_sup,$rst_sup);
		do{
			$sup_id = $row_sup["sup_id"];
			update_ord_summary($cart_id,$sup_id);

			$mail_body = "Dear Seller,<br> Order $ord_no has been cancelled by the buyer";
			$sms_body = "Dear Seller, Order $ord_no has been cancelled by the buyer";
			send_mail($row_sup["sup_email"],"Company-Name - Order Cancelled (Seller): $ord_no",$mail_body,"",$cc);
			send_sms($row_sup["sup_contact_no"],$sms_body);
		}while($row_sup = mysqli_fetch_assoc($rst_sup));
		
		$mail_body = "Dear $bill_name,<br> Your order $ord_no has been cancelled successfully.";
		$sms_body = "Dear $bill_name, Your order $ord_no has been cancelled successfully.";
		send_mail($bill_email,"Company-Name - Order Cancelled: $ord_no",$mail_body,"",$cc);
		send_sms($bill_tel,$sms_body);
		
		js_alert("Your entire Order has been cancelled successfully.");

	}
}

$memb_id=$_SESSION["memb_id"];

if(get_rst("select * from ord_details where cart_id='".$cart_id."' and memb_id=$memb_id",$row)){
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
	$m_id = $_SESSION["memb_id"];
	$ord_instruct = $row["ord_instruct"];
	$vat_n = "";
	$cst_n = "";
	
	if(get_rst("select memb_cstn, memb_vat, memb_company from member_mast where memb_id=$m_id and ind_buyer=1",$row_n)){
		$vat_n = $row_n["memb_vat"] ;
		$cst_n = $row_n["memb_cstn"];
		$memb_company = $row_n["memb_company"];
	}
}

get_rst("select sup_id from ord_summary where cart_id=$cart_id and item_count>0",$row_sup);
$sup_id=$row_sup["sup_id"];
$due_date = "";
if($row_s["pay_method"] = "OC"){
	$due_date = Date('jS F, Y', strtotime("+30 days"));
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>Company-Name Order Confirmation - Thank You for Shopping with Us.</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="DESCRIPTION" content="Company-Name Order Confirmation - Thank You for shopping with Us"/>
<meta name="KEYWORDS" content="Order confirmation - Thank You for Shopping with Us"/>
<link href="<?=$url_root?>/styles/styles.css" rel="stylesheet" type="text/css" />
<!--<script type="text/javascript" src="scripts/jquery-1.6.1.min.js"></script>-->
<script src="<?=$url_root?>/scripts/scripts.js" type="text/javascript"></script>

<script type="text/javascript">

function js_continue(){
	window.location.href="prod_list.php";
}

function js_checkout(){
	window.location.href="checkout.php";
}

function js_home(){
	window.location.href = "index.php"
}

function js_cancel(v_act,v_sup_id,v_item_id){
	var v_action = "Cancel";
	if(v_act=="a") v_action = "Cancel the Entire Order";
	if(v_act=="c") v_action = "Cancel this product";
	if(v_act=="r") v_action = "Return this product";
	if(v_act=="j") v_action = "Reject this product";
	
	if(confirm("Are you sure you wish to " + v_action + "?")){
		document.frm_BT.act.value=v_act
		document.frm_BT.sup_id.value=v_sup_id
		document.frm_BT.item_id.value=v_item_id
		
		document.frm_BT.submit();
	}

}

function js_print(){
	window.open("ord_confirmation.php?print=1");
}

</script>
</head>
<body>

<?php

if($print==""){
	require("header.php");
}
$incomp_msg="";

if($print==""){?>
<div id="contentwrapper">

<?php }?>
<div id="contentcolumn" <?php if($print<>""){?>style="width:800px;" <?php }?>>
<div class="center-panel" >

	<?php if($print==""){?>
	<div class="you-are-here">
		<p align="left">
			YOU ARE HERE:<a href="index.php" title="">Home</a> | <span class="you-are-normal">Order Confirmation</span>
		</p>
	</div>
	<br>	
	<center>
	<table border="1" width="90%" class="table_checkout boxed-shadow">
		<tr>
			<th>1. Your Details</th>
			<th>2. Order Summary</th>
			<th>3. Payment</th>
			<th>4. Order Confirmation</th>
		</tr>
	</table>
	</center>
	<br>

	<?php if($act==""){?>
		<div class="alert alert-info">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			Congratulations! Your order has been generated with following details. Thank You for shopping with us. <br><?php if($due_date <> ""){ echo "Your payment is due on ".$due_date;} ?>
		</div>	
	<?php }else{?>
		<div class="alert alert-info">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				The order has been cancelled successfully.
		</div>
	<?php }
	}else{?>
	<div align="right">
	<p style="padding-left:10px; text-align:right; padding-bottom: 5px;">Powered By :-</p>
			<img src="images/logo-black.gif" alt="Company-Name" width="120px" height="40px" align="right" style="padding-bottom: 5px;"/>
			</div>
			<center><b style="font-size:18px;"><?php if($row_s["pay_status"]=="OnCredit"){ echo "Challan No: $cart_id"; }else{echo "Tax Invoice/Retail Invoice";}?> </b></center>
		</div>	
	<?php }?>

	<?php
	$ord_cancelled="";
	if($row_s["buyer_action"]=="Cancelled"){
		$ord_cancelled="1";
	?>
		<tr style="background-color:#FF0000;">
			<td style="background-color:#FF0000;" align="center" colspan="10"><font color='white'>This is a cancelled Order</font></td>
		</tr>
	<?php }?>	
	
	<table width="100%"  border="0" align="center" class="checkout">
		<tr>
			<td width="50%" align="center" class="table-bg2"><b>Order No. </b><?=$row_s["ord_no"]?></td>

			<td width="50%" align="center" class="table-bg2"><b>Order Date. </b><?=formatDate($row_s["ord_date"])?></td>
		</tr>
	</table>
		<form name="frm_BT" action="ord_confirmation.php" method="post">
			<input type="hidden" name="id" value="<?=$cart_id?>">
			<input type="hidden" name="act" value="">
			<input type="hidden" name="sup_id" value="">
			<input type="hidden" name="item_id" value="">
		</form>
	
	<table width="100%"  border="2" align="center" class="list" >
	<tr>
	<td><center><h4 class="h4">Shipping Details</h4></center></td>
	</tr>
	<tr>
	<td style="padding: 10px;">
	<p><?php if($memb_company <> ""){
	echo $memb_company;}else{ echo $ship_name; }?>,</p>
	<p><strong>Address :</strong> <?=$ship_add1?>, <?=$ship_add2?>, <?=$ship_city?>, <?=$ship_state?>,</p>
	<p><?=$ship_country?> - <?=$ship_postcode?>.</p>
	<p>Contact No.- <?=$ship_tel?></p>
	<?php if($vat_n <> "" || $cst_n <> ""){ ?>
	<p><strong>GST NO.:</strong> <?php echo substr($vat_n,0,15);?></p>
	<?php }?>
	</td>
	</tr>
	
	<?php if($ord_instruct.""<>""){?>
		<table width="100%"  border="2" align="center" class="checkout" >
		<tr>
		<td width="175" align="left" class="table-bg2"><p>Additional Instructions</p></td>
		<td align="left"  class="table-bg2"><?=$ord_instruct?>
		</td>
		</tr>
		</table>
	<?php }?>
	
	<table width="100%"  border="0" align="center" class="checkout" >
	<tr>
	<td width="50%" align="left" class="table-bg3"><p>Mode of Payment</p></td>
	<span>
	<td width="50%" align="left" class="table-bg2"><p>
	<?=$row_s["pay_method_name"]?></p></td><span>
	</tr>
	
	</table>
	
	<?php if($row_s["po_no"] <> ""){?>
	<table width="100%"  border="0" align="center" class="checkout" >
	<tr>
	<td width="50%" align="left" class="table-bg3"><p>Purchase Order Number</p></td>
	<span>
	<td width="50%" align="left" class="table-bg2"><p>
	<?=$row_s["po_no"]?></p></td><span>
	</tr>
	
	</table>
	<?php }?>
	
	<h1 style="text-transform:none;">Product Details</h1>
	<?php if($incomp_msg <> ""){?>
			<hr color="#FF0000" size="1px" width="91%" align="center" />
			<p>
				<span class="red">
				<?=$incomp_msg?>
				</span>
			</p>		
			<hr color="#FF0000" size="1px" width="91%" align="center" />
		<?php }?>
	
	<div class="center-panel">
	<?php
	$ord_pay_total = 0;
	$ord_tax_total = 0;
	$ord_disc_total = 0;
	do{
	$sup_id = $row_s["sup_id"];
	$gst_cap = 0;
	get_rst("select sup_ext_name,sup_ext_address,sup_ext_city,sup_ext_state,sup_ext_pincode from sup_ext_addr where sup_id=$sup_id",$row_sadd); 
	get_rst("select sup_vat from sup_mast where sup_id=$sup_id",$row_st);
	if($row_sadd["sup_ext_state"] == $bill_state){
		$gst_cap = 1; // 0 for inter-state GST, 1 for intra-state GST
	}

	if($row_s["pay_status"] !="OnCredit"){ ?>
	
	<table width="100%"  border="0" align="center" class="checkout">
	<tr>
	<td colspan="10">
	<table width="100%"  border="0" align="center" class="list">
		<tr>
			<td style="padding: 10px; ">
				<p>Sold By-</p>
				<p class="p"><strong><?=$row_sadd["sup_ext_name"]?></strong></p> 
				<p class="p"><strong>Address :</strong> <?=$row_sadd["sup_ext_address"]?>, <?=$row_sadd["sup_ext_city"]?>, <?=$row_sadd["sup_ext_state"]?>,</p>
				<p>India - <?=$row_sadd["sup_ext_pincode"]?>.</p>
				<p class="p"><strong>GST NO.:</strong> <?php echo substr($row_st["sup_vat"],0,15);?></p>	
			</td>
		</tr>
	</table>
	<?php } ?>
	</td>
	</tr>
	<tr>
		<td  width="8%" align="center" class="table-bg"><span class="theme-blue">Image</td>
		<td  width="11%" align="center" class="table-bg"><span class="theme-blue">HSN Code</td>
		<td width="20%" align="center" class="table-bg"><span class="theme-blue">Item Description</td>
		<td width="10%" align="center" class="table-bg"><span class="theme-blue">Unit Price&nbsp;<span><div class="" style="display:inline-block;">&#8377; </div></span></td>
		<td width="7%" align="center" class="table-bg"><span class="theme-blue">Discount</td>
		<td width="7%" align="center" class="table-bg"><span class="theme-blue">GST</td>
		<td width="10%" align="center" class="table-bg"><span class="theme-blue">Price after Tax&nbsp;<span><div class="" style="display:inline-block;">&#8377; </div></span></td>
		<td width="10%" align="center" class="table-bg"><span class="theme-blue">Shipping Charges&nbsp;<span><div class="" style="display:inline-block;">&#8377; </div></span></td>
		<td width="7%" align="center" class="table-bg"><span class="theme-blue">Qty</span></td>
		<td width="10%" align="center" class="table-bg"><span class="theme-blue">Total&nbsp;<span><div class="" style="display:inline-block;">&#8377; </div></span></td>
	</tr>
	<?php	
	$Productinfo="Test Products";
	get_rst("select * from ord_items where cart_id='".$cart_id."' and sup_id=$sup_id",$row,$rst);
	do{
		$cart_price = $row["cart_price"];
		$cart_qty = $row["cart_qty"];
		$prod_id = $row["prod_id"];
		$cart_price_tax = $row["cart_price_tax"];
		
		get_rst("select price as prod_ourprice, final_price as prod_offerprice from prod_sup where prod_id=0$prod_id and sup_id=0$sup_id", $rw_disc);
		get_price($rw_disc, $off_pr, $pr_orp, $disc_per);
		$disc_per = $disc_per * 1 ;
		$ord_disc_total = $ord_disc_total + (($pr_orp - $off_pr) * $cart_qty);
		get_rst("select hsn_code from prod_mast where prod_id=0$prod_id", $prow);
		$item_color = "";
		if($row["item_buyer_action"].""<>""){
			$item_color = "#d9534f";
		}		
		?>
		<tr style="background-color:<?=$item_color?>;">
		<td rowspan="1" class="table-bg2"><img src="<?=show_img($row["item_thumb"])?>" width="51" height="43" border="0" alt="<?=$row["item_name"]?>" /></td>
		<td rowspan="1" align="center" valign="middle" class="table-bg2"><p><?=$prow["hsn_code"]?></p></td>
		<td rowspan="1" align="center" valign="middle" class="table-bg2"><p><?=$row["item_stock_no"]." - ".$row["item_name"]?></p></td>
		<td rowspan="1" align="center" valign="middle" class="table-bg2"><?=formatNumber($row["cart_price"],2)?></td>
		<td rowspan="1" align="center" valign="middle" class="table-bg2"><?=$disc_per?>%</td>
		<td rowspan="1" align="center" valign="middle" class="table-bg2">
		<?php
		IF(floatval($row["tax_percent"])>0){
			echo($row["tax_percent"]."% ");
		}?>
		</td>
		<td align="center" valign="middle" class="table-bg2">
		<?=formatNumber($cart_price_tax,2)?>
		</td>
		<?php if($row["ship_amt"]==0){?>
			<td  align="center" style="color: #006633;"><b>Free</b></td><?php }else{?>
			<td  align="center"><?=$row["ship_amt"]?></td>
		<?php }?>
		
		<td valign="middle" align="center" class="table-bg2">
			<?=$cart_qty?>
		</td>
		<td valign="middle" align="center" class="table-bg2"><strong><?=formatNumber(($cart_qty * $cart_price_tax)+$row["ship_amt"],2)?></strong></td>

		</tr>
		<?php
	}while($row = mysqli_fetch_assoc($rst));
		
	$item_total = $row_s["item_total"];
	$shipping_charges = $row_s["shipping_charges"]."";
	$ord_total = $row_s["ord_total"];
	$vat_percent = $row_s["vat_percent"];
	$vat_value = $row_s["vat_value"];
	$ord_pay_total = $ord_pay_total + $ord_total;
	$pay_method = $row_s["pay_method"];
	$ord_tax_total = $ord_tax_total + $vat_value;

	if($shipping_charges.""==""){
		$v_shipping_charges = "As per the delivery area";
	}else{
		$v_shipping_charges = formatNumber($shipping_charges,2);
	}
	?>
	<tr>
	<td align="right" colspan="8"><p><span class="theme-blue">Sub-Total (exc. Tax):</span></p></td>
	<td align="right" colspan="2"> <?=FormatNumber($item_total,2)?>&nbsp;</td>
	</tr>	
	<tr>
	<td  align="right" colspan="8"><p><span class="theme-blue">Shipping Charges:</span></p></td>
	<?php if($v_shipping_charges==0){?>
	<td colspan="2" align="right"> Free Delivery &nbsp;</td><?}else{?>
	<td colspan="2" align="right"> <?=$v_shipping_charges?>&nbsp;</td>
	<?php }?>
	</tr>
	
	<?php if($gst_cap == 0){?> <!-- Inter-State GST -->
		<tr>
		<td align="right" colspan="8"><p><span class="theme-blue">Total IGST :</span></p></td>
		<td align="right" colspan="2" > <?=FormatNumber($vat_value,2)?>&nbsp;</td>
		</tr>
	<?php }else{ ?> <!-- Intra-State GST -->
		<tr>
		<td align="right" colspan="8"><p><span class="theme-blue">Total CGST :</span></p></td>
		<td align="right" colspan="2" > <?=FormatNumber($vat_value/2,2)?>&nbsp;</td>
		</tr>
		<tr>
		<td align="right" colspan="8"><p><span class="theme-blue">Total SGST :</span></p></td>
		<td align="right" colspan="2" > <?=FormatNumber($vat_value/2,2)?>&nbsp;</td>
		</tr>
	<?php } ?>
	
	<tr>
	<td align="right" colspan="8"><p><span class="theme-blue">Total Price :</span></p></td>
	<td align="right" colspan="2" ><strong> <?=FormatNumber($ord_total,2)?></strong>&nbsp;</td>
	</tr>

	<tr>
	<td align="right" colspan="8"><p><span class="theme-blue">Total Price (Round-Off):</span></p></td>
	<td align="right" colspan="2" ><strong><div class="rupee-sign">&#8377; </div> <?=round($ord_total,0,PHP_ROUND_HALF_UP)?></strong>&nbsp;</td>
	</tr>

	<?php
	}while($row_s = mysqli_fetch_assoc($rst_s));

	$coupon_amt = 0;
	$coupon_tax = 0;
	if(get_rst("select coupon_id, coupon_amt from ord_coupon where cart_id=$cart_id", $row_c)){
		get_rst("select coupon_code from offer_coupon where coupon_id=".$row_c["coupon_id"], $row_oc);
		$coupon_amt = $row_c["coupon_amt"];
		$coupon_tax = $coupon_amt * ($ord_tax_total/$item_total);
		?>
		<tr class="border_bottom">
			<td align="right" class="table-bg"  colspan="8"><p><span class="theme-blue">Coupon Applied (<?=$row_oc["coupon_code"];?>):</span></p></td>
			<td align="right" colspan="2" class="table-bg">
				<strong> <span><?=FormatNumber($coupon_amt,2)?></span></strong>
			</td>
		</tr>
		<tr>
			<td align="right" class="table-bg" colspan="8"><p><span class="theme-blue">Total Tax:</span></p></td>
			<td align="right" colspan="9" class="table-bg"><strong> <?=FormatNumber($ord_tax_total - $coupon_tax,2)?></strong>&nbsp;</td>
		</tr>		
	<?php }?>
	<tr>
		<td align="right" class="table-bg" colspan="8"><p><span class="theme-blue">Total Amount Payable:</span></p></td>
		<td align="right" colspan="9" class="table-bg"><strong> <?=FormatNumber($ord_pay_total - ($coupon_amt + $coupon_tax),2)?></strong>&nbsp;</td>
	</tr>
	<tr>
		<td align="right" class="table-bg" colspan="8"><p><span class="theme-blue">Total Amount Payable (Round-Off):</span></p></td>
		<td align="right" colspan="9" class="table-bg"><strong><div class="rupee-sign">&#8377; </div> <?=round($ord_pay_total - ($coupon_amt + $coupon_tax),0,PHP_ROUND_HALF_UP)?></strong>&nbsp;</td>
	</tr>
	<?php if(($ord_disc_total + $coupon_amt) > 0){?>
	<tr>
		<td align="right" class="table-bg" colspan="8"><p><span class="theme-blue">Total Savings:</span></p></td>
		<td align="right" colspan="2" class="table-bg"><strong><div class="rupee-sign">&#8377; </div> <?=FormatNumber($ord_disc_total + $coupon_amt,2)?></strong>&nbsp;</td>
	</tr>
	<?php }
	if($print<>""){?>
	<tr>
		<td width="100px" class="table-bg color-amber" colspan="10" >I/We hereby certify that my/our registration certificate under the Name of the Tax Authority is in force on the date on which the specified sale of goods specified in the tax invoice is made by me/us and that the transaction of sale coverd by this tax invoice has been effected by me/us and it shall be accounted for in the turnover of sales while filing of return and due tax, if any, payable on the sale has been paid or shall be paid. <br><br><br>
	<p>(This is a computer generated Invoice, hence no signature is required)</p></td>
	</tr>
	<?php }
	if($print==""){
		if($pay_method == "BT"){
		?>
		<tr>
			<td width="100px" class="table-bg color-amber" colspan="10" >&nbsp;&nbsp;&nbsp;<strong>You have selected Bank transfer mode of payment, please <a href="../memb_view_orders.php">click here</a> to enter your transfer details.</strong>&nbsp;</td>
		</tr>
		<?php
		}
	}
	?>
	</table>
	<?php if($print<>""){?>
	<center>Please call us at Mobile or Email us at Email for any issues/information.<center><br>
	<center><b>Want to place a Bulk Order? Call us at Mobile</b></center>
	<?php } if($print==""){?>
	<center>
	<table width="90%"  border="0" align="center" xclass="list">
	<tr>
		<td align="left"><input type="button" class="btn btn-warning" value=" Go back to Home Page " onclick="javascript: js_home();"/></a></td>
		<?php if($row_s["buyer_action"]=="Cancelled"){?>
			<td align="left"><input type="button" class="btn btn-danger" value=" Cancel Order " onclick="javascript: js_cancel('a','','');"/></a></td>
		<?php }?>
		<td align="right"><input type="button" class="btn btn-warning" value=" Print " onclick="javascript: js_print();"/></td>
	</tr>
	</table>
	</center>
	<?php }?>
</div>
</div>
</div>
</div>

<?php

if($print==""){
	require("left.php");
	require("footer.php");
}
?>

</div>
</div>

<?php if($print=="1"){?>
	<script>window.print()</script>
<?php }?>
</body>
<script src="<?=$url_root?>/scripts/chat.js" type="text/javascript"></script>
</html>