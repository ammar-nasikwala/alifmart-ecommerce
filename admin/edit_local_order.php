<?php
require_once("inc_admin_header.php");
require_once("../seller/logistics_push.php");
$id = func_read_qs("id");
$sup_id = func_read_qs("sid");

//if($_SESSION["admin"]="")
if(isset($_POST["submit"])){
	$vendor_id=$_POST["vendor"];
	get_rst("select vendor_name from local_logistics_vendor where id=$vendor_id",$row_v);
	$vendor_name = $row_v["vendor_name"];
	$qry_upd="update ord_summary set local_logistics_vendor_id='$vendor_id' where ord_id=0$id and sup_id=0$sup_id";
	execute_qry($qry_upd);
}
if(get_rst("select * from ord_summary where ord_id=0$id and sup_id=0$sup_id",$row_s,$rst_s)){
	$cart_id = $row_s["cart_id"];
	$delivery_status = $row_s["delivery_status"];
	$pay_status = $row_s["pay_status"];
	
}else{
	header('location: edit_local_order.php');
	js_redirect("edit_local_order.php");
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
<script>
//Save Details

//Print Form
function print_page(divID){
    var printContents = document.getElementById(divID).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}

$(document).ready(function()
{
$(".city").change(function()
	{
		var v_id=$(this).val();
		var dataString = 'v_id='+ v_id;
		$.ajax
		({
			type: "POST",
			url: "ajax_admin.php",
			data: dataString,
			cache: false,
			success: function(html)
			{	
				$(".vendor").html(html);
			} 
		});
	});
	
});
</script>

<form name="frm_order" action="edit_local_order.php" method="post">
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
			<td valign="middle" align="right" class="table-bg2"><strong>Rs.<?=formatNumber(($cart_qty * $cart_price_tax)+ $row["ship_amt"],2)?></strong>&nbsp;&nbsp;</td>

			</tr>
			<?php
		}while($row = mysqli_fetch_assoc($rst));
		
		?>
		
		
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
		<?php
				//}
		?>
		</table>
		
	<table width="80%"  border="1" class="list"  align="center">
		<tr>
        <td align="right"> 
			<p>City :</p>
		</td>
        <td align="left">
			<select id="100" title="City" name="city" class="city" tabindex="12">
			<option value="">Select</option>
			<?=create_cbo("select DISTINCT city,city from local_logistics_vendor",func_var($city))?>">	
			</select>*
		</td>
		
		<td align="right"> 
			<p>Vendor :</p>
		</td>
        <td align="left">
			<select id="100" title="Vendor" name="vendor" class="vendor" tabindex="13">
					<option selected="selected">--Select Vendor--</option>
			</select>
		</td>
		<td>
			<input type="submit" name="submit" class="btn btn-warning" value=" Save "  tabindex="14" >
		</td>
		</tr>
	</table>
		
	<div id="ord_info">
	<?php for($i=1;$i<=2;$i++){?>
		<div class="order_info">
			<table width="100%"  border="1" align="center" class="checkout invoice">
				<tr>
					<td><strong>WayBill No.</strong></td><td><?=$row_s["way_billl_no"]?></td>
					<?php $barcode_url = "../lib/barcode.php?codetype=Code39&size=40&text=".$row_s["way_billl_no"]; ?>
					<td><strong>Barcode</strong></td><td class="img-padding"><img src="<?=$barcode_url?>" height="30"/></td>
				</tr>
				</tr>
				
				<tr>
					<td><strong>Order No.</strong></td><td><?=$row_s["ord_no"]?></td>

					<td><strong>Order Date.</strong></td><td><?=$row_s["ord_date"]?></td>
				</tr>
			</table>
			
			<center><h3 class="h3">Shipping Details</h3></center>
			<?php if($row_m["ind_buyer"]==1){?>
			<p class="p"><strong>Name:</strong> <span align="left" class="table-bg2" style="padding-left:5px;"><?=$memb_company?></span><span style="padding-left:50px;">
			<strong>Contact No.:</strong><span align="left" class="table-bg2" style="padding-left:5px;"><?=$ship_tel?></span></p></span></span></p>
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
				
			<table width="100%"  border="1" align="center" class="list">
			<tr>
			<!--td xwidth="20%" align="left" class="table-bg"><p><strong>Product Image</strong></p></td-->
			<td xwidth="32%" align="left" class="table-bg"><p><strong>Product Name</strong></p></td>
			<td xwidth="14%" align="left" class="table-bg"><p><strong>Price</strong></p></td>
			<td xwidth="14%" align="left" class="table-bg"><p><strong>Tax</strong></p></td>
			<td xwidth="14%" align="left" class="table-bg"><p><strong>Price after TAX</strong></p></td>
			<td align="left" class="table-bg"><p><strong>Shipping Charges</strong></p></td>
			<td xwidth="14%" align="left" class="table-bg"><p><strong>Qty</strong></p></td>
			<td width="12%" align="left" class="table-bg"><p><strong>Total</strong></p></td>
			</tr>
			<?php	
			$Productinfo="Test Products";
			get_rst("select * from ord_items where cart_id='".$cart_id."' and sup_id=$sup_id",$row,$rst);

			do{
				$cart_price = $row["cart_price"];
				$cart_qty = $row["cart_qty"];
				$prod_id = $row["prod_id"];
				$cart_price_tax = $row["cart_price_tax"];
				
				
				?>
				<tr>
				<!--td rowspan="1" class="img-padding"><img src="<?//=show_img($row["item_thumb"])?>" width="50" height="40" border="0" /></td-->
				<td rowspan="1" align="left" valign="middle" class="table-bg2"><p><?=$row["item_stock_no"]." - ".$row["item_name"]?></a></p></td>
				<?php IF(func_var($row["user_type"])=="S"){?>
					<td rowspan="1" align="left" valign="middle" class="table-bg2"><?=$row["sup_name"]?></td>
				<?php }?>
				<td rowspan="1" align="right" valign="middle" class="table-bg2">Rs.<?=formatNumber($row["cart_price"],2)?>&nbsp;</td>
				<td rowspan="1" align="center" valign="middle" class="table-bg2">
				<?php
				IF(floatval($row["tax_percent"])>0){
					echo($row["tax_percent"]."% ");
				}?>
				</td>
				<td valign="middle" class="table-bg2">
				Rs.<?=formatNumber($cart_price_tax,2)?>&nbsp;
				</td>
				<?php if($row["ship_amt"]==0){?>
					<td  align="center" style="color: #006633;"><b>Free</b></td><?php }else{?>
					<td  align="center">Rs.<?=$row["ship_amt"]?>&nbsp;</td>
				<?php }?>
				
				<td valign="middle" align="center" class="table-bg2">
					<?=$cart_qty?>
				</td>
				<td valign="middle" align="right" class="table-bg2"><strong>Rs.<?=formatNumber($cart_qty * $cart_price_tax,2)?></strong>&nbsp;&nbsp;</td>

				</tr>
				<?php
			}while($row = mysqli_fetch_assoc($rst));
			
			?>
			
			
			</table>

			<table width="100%"  border="1" class="list">
			<?php		
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
			<td align="right"><strong><p><span class="green-basket">Sub-Total (exc. Tax):</span></p></strong></td>
			<td align="right">Rs.<?=FormatNumber($item_total,2)?>&nbsp;</td>
			
			</tr>	
			<tr>
			<td width="65%" align="right"><strong><p><span class="green-basket">Shipping:</span></p></strong></td>
			<?php if($shipping_charges == 0){?>
			<td width="25%" align="right"> Free Delivery &nbsp;</td><?php }else{?>
			<td width="25%" align="right"><?=$v_shipping_charges?>&nbsp;</td>
			<?php }?>
			</tr>

			<tr>
			<td align="right"><strong><p><span class="green-basket">Total Tax :</span></strong></p></td>
			<td align="right">Rs.<?=FormatNumber($vat_value,2)?>&nbsp;</td>
			</tr>

			<tr>
			<td align="right"><strong><p><span class="green-basket">Total Price (incl. TAX):</span></strong></p></td>
			<td align="right"><strong>Rs.<?=FormatNumber($ord_total,2)?></strong>&nbsp;</td>
			</tr>
			</table>
		</div>
			<?php }?>
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
				<?=$cms_middle_panel?>
			</div>
	</div>

	</center>
</form>

<?php
require_once("inc_admin_footer.php");
?>

