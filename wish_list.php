<?php

require("inc_init.php");
$memb_id = 0;
if(isset($_SESSION["memb_id"]) && $_SESSION["memb_id"] <> ""){
	$memb_id = $_SESSION["memb_id"];
}
if(func_read_qs("act")=="del"){
	$cart_item_id = func_read_qs("cart_item_id");
	execute_qry("delete from cart_items where cart_item_id=$cart_item_id and item_wish=1");
	
	//update_cart_summary();
}

if(func_read_qs("act")=="add"){
	$cart_item_id = func_read_qs("cart_item_id");
	execute_qry("update cart_items set item_wish=0 where cart_item_id=$cart_item_id and item_wish=1");
	
	update_cart_summary($memb_id);
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title><?=$cms_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="DESCRIPTION" content="<?=$cms_meta_key?>"/>
<meta name="KEYWORDS" content="<?=$cms_meta_desc?>"/>
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
function js_add(v_item_id){
	window.location.href="wish_list.php?act=add&cart_item_id=" + v_item_id
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
			YOU ARE HERE:<a href="index.php" title="">Home</a> | <span class="you-are-normal">Your Wish List <a href="returns.php" title="Cancellation & Returns Policy" style="float:right">Cancellation & Returns Policy</a></span>
		</p>
	</div>
	<h1>Wish List</h1>
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
		$qry = "";
		if($memb_id <> 0){
			$qry = "select * from cart_items where memb_id=$memb_id and item_wish=1";
		}else{
			$qry = "select * from cart_items where session_id='".session_id()."' and item_wish=1 and memb_id IS NULL";
		}
		
		if(get_rst($qry,$row,$rst)){
	?>
	<form name="frmBasket" method="post" action="wish_list.php">
		<input type="hidden" name="act" value="upd">
		<table width="100%"  border="0" align="center" class="list">
	<tr>
		<td width="20%" align="center" class="table-bg"><span class="theme-blue">Product Image</span></td>
		<td width="32%" align="center" class="table-bg"><span class="theme-blue">Product Name</span></td>
		<td width="14%" align="center" class="table-bg"><span class="theme-blue">Price</span></td>
		<td width="14%" align="center" class="table-bg"><span class="theme-blue">Tax</span></td>
		<td width="14%" align="center" class="table-bg"><span class="theme-blue">Price after TAX</span></td>
		<td width="20%" align="center" class="table-bg" colspan="2"><span class="theme-blue">Action</span></td>
	</tr>
	<?php
	do{
		$cart_price = $row["cart_price"];
		$cart_qty = $row["cart_qty"];
		$prod_id = $row["prod_id"];
		$cart_price_tax = $row["cart_price_tax"];
		get_rst("select prod_name from prod_mast where prod_id=$prod_id",$rw_name);	?>
		<tr>
		<td rowspan="1" class="table-bg2"><a href="<?=get_prod_url($prod_id,"",$rw_name["prod_name"])?>"><img src="<?=show_img($row["item_thumb"])?>" width="71" xheight="73" border="0" alt="<?=$row["item_name"]?>" /></a></td>
		<td rowspan="1" align="center" valign="middle" class="table-bg2"><p><?=$row["item_stock_no"]." - ".$row["item_name"]?></a></p></td>
		<?php if(get_rst("select prod_advrt from prod_mast where prod_id=0".$row["prod_id"]." and prod_advrt<>1")){ ?>
			<td rowspan="1" align="center" valign="middle" class="table-bg2"><div class="rupee-sign">&#8377; </div> <?=formatNumber($row["cart_price"],2)?>&nbsp;</td>
			<td rowspan="1" align="center" valign="middle" class="table-bg2">
			<?php
			IF(floatval($row["tax_percent"])>0){
				echo($row["tax_percent"]."% ");
			}?>
			</td>
			<td align="center" valign="middle" class="table-bg2">
			<div class="rupee-sign">&#8377; </div> <?=formatNumber($cart_price_tax,2)?>&nbsp;
			</td>
			
			<?php
			$msg="Not available";
			if(get_rst("select sup_status,out_of_stock from prod_sup where prod_id=".$row["prod_id"]." and sup_id=".$row["sup_id"],$row_ps)){
				if($row_ps["sup_status"].""=="1"){
					$msg = "";
				}
				if($row_ps["out_of_stock"].""=="1"){
					$msg = "Out of Stock";
				}
			}
			$msg = "Out of Stock"; // change to supress add to cart
			if($msg==""){?>
				<td valign="middle" align="center" class="table-bg2">
					<input type="button" value="Add to Cart" class="btn btn-warning" onclick="javascript: js_add('<?=$row["cart_item_id"]?>');">
				</td>		
			<?php }else{?>
				<td align="center" class="back_red"><span class="back_red"><?=$msg?></span></td>
			<?php }
		}else{ ?>
			<td rowspan="1" align="center" valign="middle" class="table-bg2">-</td>
			<td rowspan="1" align="center" valign="middle" class="table-bg2">-</td>
			<td align="center" valign="middle" class="table-bg2">-</td>
			<td align="center" valign="middle" class="table-bg2">-</td>
		<?php }?>	
		<td valign="middle" align="center" class="table-bg2"><a href="wish_list.php?act=del&cart_item_id=<?=$row["cart_item_id"]?>"><font color="red">Remove</a>&nbsp;</td>

		</tr>
	<?php }while($row = mysqli_fetch_assoc($rst)); ?>
	</table>
	<?php
		$qry= "";
		if($memb_id <> 0){
			$qry = "select item_total, shipping_charges, ord_total, vat_percent, vat_value from cart_summary where user_id=$memb_id";
		}else{
			$qry = "select item_total, shipping_charges, ord_total, vat_percent, vat_value from cart_summary where session_id='" .session_id(). "' and user_id IS NULL";
		}
		if(get_rst($qry,$row,$rst)){
			$item_total = $row["item_total"];
			$shipping_charges = $row["shipping_charges"]."";
			$ord_total = $row["ord_total"];
			$vat_percent = $row["vat_percent"];
			$vat_value = $row["vat_value"];
			
			if($shipping_charges.""==""){
				$v_shipping_charges = "As per the delivery area";
			}else{
				$v_shipping_charges = formatNumber($shipping_charges,2);
			}
		}
	?>
	
	<table width="100%"  border="0" class="list">
	<tr>
	<td align="left"><input type="button" class="btn btn-warning" value="Continue Shopping" onclick="javascript:js_continue();" border="0" /></td>
	</tr>
	</table>
	</form>
	<?php
	}else{
	?>
			<hr color="#FF0000" size="1px" width="91%" align="center" />
			<p>
				<span class="red">
					Your Wish List is Empty
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
