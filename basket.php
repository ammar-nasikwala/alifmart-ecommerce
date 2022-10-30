<?php

require("inc_init.php");

$memb_id = 0;
if(isset($_SESSION["memb_id"]) && $_SESSION["memb_id"] <> ""){
	$memb_id = $_SESSION["memb_id"];
}
if(func_read_qs("act")=="add"){
	$prod_id = func_read_qs("prod_id");
	$price = func_read_qs("prod_price");
	$qty = func_read_qs("qty");
	$sup_id = func_read_qs("sup_id");
	$prod_name = func_read_qs("prod_name");
	$stock_no = func_read_qs("stock_no");
	$img_thumb = func_read_qs("img_thumb");
	$sup_name = func_read_qs("sup_name");
	$item_wish = func_read_qs("item_wish");
	$_SESSION["memb_pin"] = func_read_qs("memb_pin");
	add_to_basket($prod_id,$sup_name,$img_thumb,$price,$qty,$sup_id,$item_wish,$memb_id);

	//header("location: basket.php");
	if($item_wish=="1"){
		js_redirect("wish_list.php");
	}else{
		js_redirect("basket.php");
	}
}

if(func_read_qs("act")=="upd"){
	$qry = "";
	if($memb_id <> 0){
		$qry = "select cart_item_id,cart_id from cart_items where memb_id=$memb_id";
	}else{
		$qry = "select cart_item_id,cart_id from cart_items where session_id='".session_id()."' and memb_id IS NULL";
	}
	
	if(get_rst($qry,$row,$rst)){
		do{
			$cart_id = $row["cart_id"];
			if(func_read_qs("qty_".$row["cart_item_id"]).""<>""){
				if($memb_id <> 0){
					$sql = "update cart_items set cart_qty=".func_read_qs("qty_".$row["cart_item_id"])." where memb_id=$memb_id and cart_item_id=".$row["cart_item_id"];
				}else{
					$sql = "update cart_items set cart_qty=".func_read_qs("qty_".$row["cart_item_id"])." where session_id='".session_id()."' and cart_item_id=".$row["cart_item_id"]." and memb_id IS NULL";
				}
				execute_qry($sql);
			}
		}while($row = mysqli_fetch_assoc($rst));
	}
	update_shipping_charges($_SESSION["memb_pin"], $cart_id);
	update_cart_summary($memb_id);
}

if(func_read_qs("act")=="del"){
	$cart_item_id = func_read_qs("cart_item_id");
	get_rst("select sup_id, cart_id from cart_items where cart_item_id=$cart_item_id",$row);
	$sup_id = $row["sup_id"];
	$cart_id = $row["cart_id"];
	execute_qry("delete from cart_items where cart_item_id=$cart_item_id");
	
	if($memb_id <> 0){
		execute_qry("update cart_summary set item_count=item_count-1 where sup_id=$sup_id and cart_id=$cart_id and user_id=$memb_id");
	}else{
		execute_qry("update cart_summary set item_count=item_count-1 where session_id='".session_id()."' and sup_id=$sup_id and cart_id=$cart_id and user_id IS NULL");
	}		
	execute_qry("delete from cart_summary where item_count=0");
	update_cart_summary($memb_id);
}

?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>Buyer's Shopping Cart</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="DESCRIPTION" content="Company-Name.com Buyer's Shopping Cart, holds shortlisted products ready to go to checkout."/>
<meta name="KEYWORDS" content="Company-Name Shopping Cart, Buyer's shopping cart"/>
<link href="styles/styles.css" rel="stylesheet" type="text/css" />
<link href="styles/bannerstyle.css" rel="stylesheet" type="text/css" />
<script src="scripts/scripts.js" type="text/javascript"></script>

<script type="text/javascript">
function js_continue(){
	window.location.href="prod_list.php";
}

function js_update(){
	document.frmBasket.act.value="upd";
	document.frmBasket.submit();
}

function js_checkout(){
	window.location.href="checkout.php";

}
function js_change(ref,bttnID, min_qty)
{
  document.getElementById(bttnID).disabled= ((ref.value !== ref.defaultValue) ? false : true);
  if(ref.value==0 || parseInt(ref.value) < parseInt(min_qty)){
	   ref.value = min_qty;
	   var result = alert("Can not set quantity to Zero or less than minimum set quantity. If you want to remove the product from the cart Click on Remove");
  }
}


</script>
</head>
<body>
<?php

require("header.php");

$incomp_msg="";
?>
<div id="contentwrapper">
<div id="contentcolumn">
<div class="center-panel">
	<div class="you-are-here">
		<p align="left">
			YOU ARE HERE:<a href="index.php" title="">Home</a> | <span class="you-are-normal">Shopping Cart <a href="returns.php" title="Cancellation & Returns Policy" style="float:right">Cancellation & Returns Policy</a></span>
		</p>
	</div>
	
	<h1>Cart</h1>
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

	<form name="frmBasket" method="post" action="basket.php">
		<input type="hidden" name="act" value="upd">
		
	<?php
	$ord_pay_total = 0;
	$ord_disc_total = 0;
	$qry = "";
	if($memb_id <> 0){
		$qry = "select * from cart_summary where user_id=0$memb_id and item_count IS NOT NULL order by sup_id";
	}else{
		$qry = "select * from cart_summary where session_id='" .session_id(). "' and item_count IS NOT NULL and user_id IS NULL order by sup_id";
	}
	if(get_rst($qry,$row_sum,$rst_sum)){
		$_SESSION["cart_id"] = $row_sum["cart_id"];
		do{
		$sup_id = $row_sum["sup_id"];
		?>
		<table width="100%"  border="0" align="center" class="list">
		<tr>
		<td width="10%" align="center" class="table-bg"><span class="theme-blue">Product Image</span></td>
		<td width="23%" align="center" class="table-bg"><span class="theme-blue">Product Name</span></td>
		<td width="9%" align="center" class="table-bg"><span class="theme-blue">Unit Price</span></td>
		<td width="7%" align="center" class="table-bg"><span class="theme-blue">Discount</span></td>
		<td width="6%" align="center" class="table-bg"><span class="theme-blue">Tax</span></td>
		<td width="10%" align="center" class="table-bg"><span class="theme-blue">Price(incl. TAX)</span></td>
		<td width="10%" align="center" class="table-bg"><span class="theme-blue">Shipping Charges</span></td>
		<td width="7%" align="center" class="table-bg"><span class="theme-blue">Qty</span></td>
		<td width="10" align="center" class="table-bg"><span class="theme-blue">Total</span></td>
		
		<td width="8%" align="center" class="table-bg"><span class="theme-blue">Action</span></td>
		</tr>
		<?php
	
		$qry = "";
		if($memb_id <> 0){	
			$qry = "select * from cart_items where memb_id=0$memb_id and item_wish<>1 and sup_id=0$sup_id";
		}else{
			$qry = "select * from cart_items where session_id='".session_id()."' and item_wish<>1 and sup_id=0$sup_id and memb_id IS NULL";
		}
		if(get_rst($qry,$row,$rst)){
			do{
				$cart_price = $row["cart_price"];
				$cart_qty = $row["cart_qty"];
				$prod_id = $row["prod_id"];
				$cart_price_tax = $row["cart_price_tax"];
				get_rst("select min_qty, prod_name from prod_mast where prod_id=0$prod_id", $rw_disc);
				//get_price($rw_disc, $off_pr, $pr_orp, $disc_per);
				get_rst("select price as prod_ourprice, final_price as prod_offerprice from prod_sup where prod_id=0$prod_id and sup_id=0$sup_id", $rw_prod_price);
				get_price($rw_prod_price, $off_pr, $pr_orp, $disc_per);
				$disc_per = $disc_per * 1 ;
				$ord_disc_total = $ord_disc_total + (($pr_orp - $off_pr) * $cart_qty);
				$min_qty = $rw_disc["min_qty"];
				if($min_qty == ""){ $min_qty = 1;}
				?>
				<tr>
				<td rowspan="1" class="table-bg2"><a href="<?=get_prod_url($prod_id,"",$rw_disc["prod_name"])?>"><img src="<?=show_img($row["item_thumb"])?>" width="71" border="0" alt="<?=$row["item_name"]?>" /></a></td>
				<td rowspan="1" align="left" valign="middle" class="table-bg2"><p><?=$row["item_stock_no"]." - ".$row["item_name"]?></a></p></td>
				<td rowspan="1" align="center" valign="middle" class="table-bg2"> <?=formatNumber($row["cart_price"],2)?>&nbsp;</td>
				
				<td rowspan="1" align="center" valign="middle" class="table-bg2"><?=$disc_per?>%</td>
				<td rowspan="1" align="center" valign="middle" class="table-bg2">
				<?php
				IF(floatval($row["tax_percent"])>0){
					echo($row["tax_percent"]."% ");
				}?>
				</td>
				<td align="center" class="table-bg2">
				 <?=formatNumber($cart_price_tax,2)?>&nbsp;
				</td>
				<?php if($row["ship_amt"]==0){?>
				<td align="center" style="color: #006633;"><b>Free</b></td><?php }else{?>
				<td align="center"> <?=$row["ship_amt"]?>&nbsp;</td>
				<?php }?>
				<td>
				<div align="center">
					<input name="qty_<?=$row["cart_item_id"]?>" type="text" size="2" maxlength="4" value="<?=$cart_qty?>" onchange="js_change(this,'bttnupdate', '<?=$min_qty?>');"/>
				</div>
				</td>
				<td valign="middle" align="center" class="table-bg2"><strong> <?=formatNumber(($cart_qty * $cart_price_tax)+$row["ship_amt"],2)?></strong>&nbsp;&nbsp;</td>
				<td valign="middle" align="center" class="table-bg2"><a href="basket.php?act=del&cart_item_id=<?=$row["cart_item_id"]?>"><font color="red">Remove</a>&nbsp;</td>
				</tr>
				<?php

			}while($row = mysqli_fetch_assoc($rst));
		}
	?>
	</table>

	<table width="100%"  border="0" class="list">
	<?php
		$item_total = $row_sum["item_total"];
		$shipping_charges = $row_sum["shipping_charges"]."";
		$ord_total = $row_sum["ord_total"];
		$vat_percent = $row_sum["vat_percent"];
		$vat_value = $row_sum["vat_value"];
		$ord_pay_total = $ord_pay_total + $ord_total;
		
		if($shipping_charges.""==""){
			$v_shipping_charges = "As per the delivery area";
		}else{
			
			$v_shipping_charges = formatNumber($shipping_charges,2);
		}
	?>

	<tr>
	<td align="right"><p><span class="theme-blue">Sub-Total (exc. Tax):</span></p></td>
	<td align="right" > <?=FormatNumber($item_total,2)?>&nbsp;</td>
	
	</tr>	
	<tr>
	<td width="65%" align="right"><p><span class="theme-blue">Shipping:</span></p></td>
	<?php if($v_shipping_charges==0){?>
	<td width="25%" align="right"> Free Delivery &nbsp;</td><?php }else{?>
	<td width="25%" align="right"> <?=$v_shipping_charges?>&nbsp;</td>
	<?php }?>
	</tr>

	<tr>
	<td align="right"><p><span class="theme-blue">Total Tax :</span></p></td>
	<td align="right"> <?=FormatNumber($vat_value,2)?>&nbsp;</td>
	</tr>

	<tr>
	<td align="right"><p><span class="theme-blue">Total Price (incl. TAX):</span></p></td>
	<td align="right"><strong> <?=FormatNumber($ord_total,2)?></strong>&nbsp;</td>
	</tr>
	</table>
	<?php
	}while($row_sum = mysqli_fetch_assoc($rst_sum));
	?>
	<table width="100%"  border="0" class="list" >
	<tr>
		<td align="right" class="table-bg"><p><span class="theme-blue">Total Amount Payable:</span></p></td>
		<td align="right" width="100px" class="table-bg"><strong><div class="rupee-sign">&#8377; </div> <?=FormatNumber($ord_pay_total,2)?></strong>&nbsp;</td>
	</tr>
	<?php if($ord_disc_total > 0){?>
	<tr>
		<td align="right" class="table-bg"><p><span class="theme-blue">Total Savings:</span></p></td>
		<td align="right" width="100px" class="table-bg"><strong><div class="rupee-sign">&#8377; </div> <?=FormatNumber($ord_disc_total,2)?></strong>&nbsp;</td>
	</tr>
	<?php } ?>
	</table>
	
	<table width="100%"  border="0" class="list">
	<tr>
	<td align="left"><input type="button" class="btn btn-warning" value="Continue Shopping" onclick="javascript:js_continue();" border="0" /></td>
	<td align="center"><input type="button" id="bttnupdate" disabled class="btn btn-warning" value="Update Cart"  onclick="javascript:js_update();" /></td>
	<td align="right"><input type="button" class="btn btn-warning" value=" Checkout"  onclick="javascript:js_checkout();" /></a></td>
	</tr>
	</table>
	</form>
	<?php
	}else{
	?>
		<hr color="#FF0000" size="1px" width="91%" align="center" />
		<p>
			<span class="red">
				Shopping Cart is Empty
			</span>
		</p>		
		<hr color="#FF0000" size="1px" width="91%" align="center" />
	<?php
	}
	?>	
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
