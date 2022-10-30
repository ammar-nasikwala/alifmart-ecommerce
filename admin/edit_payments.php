<?php
require_once("inc_admin_header.php");

$id = func_read_qs("id");
$sup_id = 0;
if(get_rst("select * from invoice_mast where invoice_id=0$id",$row)){
	$pay_total = $row["pay_total"];
	$pay_status = $row["pay_status"];
	$ord_no = $row["ord_no"];
	$pay_comission = $row["pay_comission"];
	$service_tax = $row["service_tax"];
	get_rst("select ord_total,ord_date, item_total, vat_value, shipping_charges, cart_id, sup_id from ord_summary where ord_no='".$ord_no."' ", $row_s);
	$ord_total = $row_s["ord_total"];
	$vat_value = $row_s["vat_value"];
	$shipping_charges = $row_s["shipping_charges"];
	$item_total = $row_s["item_total"];
	$cart_id = $row_s["cart_id"];
        $sup_id = $row_s["sup_id"];
	$gst_cap_1 = 0;
	get_rst("select sup_ext_state from sup_ext_addr where sup_id=$sup_id", $st_rw);
	if($st_rw["sup_ext_state"] == "Maharashtra"){
		$gst_cap_1 = 1; // 0 for inter-state GST, 1 for intra-state GST
	}
	get_rst("select sup_company, sup_vat from sup_mast where sup_id=$sup_id", $row_sup);
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

//Print Form
function print_page(divID){
	document.getElementsByClassName("noprint")[0].style.display = "none";
    var printContents = document.getElementById(divID).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
	document.getElementsByClassName("noprint")[0].style.display = "table-row";
}

//show-hide invoice details
function toggle_details(divID){
	var inv = document.getElementById(divID);
	if(inv.style.display == 'none'){
		inv.style.display = 'block';
		document.getElementById("inv-btn").value = 'Hide Details';
		document.getElementById("print_detail").style.display = "block";
	}else{
		inv.style.display = 'none';
		document.getElementById("inv-btn").value = 'Show Details';
		document.getElementById("print_detail").style.display = "none";
	}
}
</script>

<script type="text/javascript" src="../lib/frmCheck.js"></script>


	<center>
		<div id="ord_invoice">		
			<center>
			<table width="80%"  border="0" align="center" class="checkout" style="margin: 0 auto;">
				<tr>
					<td rowspan="2" style="vertical-align: bottom">
						<a href="/" title="Home"><img width="150" height="55" src="<?=$url_root?>/images/logo-black.gif" /></a>
					</td>
					<td align="center"><h3 style="padding: 0;">Company-Name Service Invoice Number: <?php echo $id ?></h3></td>
					<td align="center"><h3 style="padding: 0;">GST: 27ABDFA8513E1ZP</h3> </td>
				</tr>
				<tr>
					<td align="center"><strong>Order Number: </strong><?=$row["ord_no"]?></td>

					<td align="center"><strong>Order Date: </strong><?=$row_s["ord_date"]?></td>
				</tr>
			</table>
			<br>
			<table width="80%"  border="1" align="center" class="list" style="margin: 0 auto;">
				<tr>
					<td colspan=4 class="table-bg" align="center"><strong>Seller Name: <?php echo $row_sup["sup_company"]; ?></strong></td>
					<td colspan=4 class="table-bg" align="center"><strong>GST: <?php echo $row_sup["sup_vat"]; ?></strong></td>
				</tr>
				<tr>
					<td width="10%" align="center" class="table-bg"><p><strong>Product Image</strong></p></td>
					<td width="28%" align="center" class="table-bg"><p><strong>Product Name</strong></p></td>
					<td width="12%" align="center" class="table-bg"><p><strong>Price</strong></p></td>
					<td width="8%" align="center" class="table-bg"><p><strong>GST</strong></p></td>
					<td width="12%" align="center" class="table-bg"><p><strong>Price with TAX</strong></p></td>
					<td width="7%" align="center" class="table-bg"><p><strong>Qty</strong></p></td>
					<td width="12%" align="center" class="table-bg"><p><strong>Total</strong></p></td>
					<td width="11%" align="center" class="table-bg"><p><strong>Status</strong></p></td>
				</tr>
				<?php	
				$Productinfo="Test Products";
				get_rst("select * from ord_items where cart_id='".$cart_id."' and sup_id=$sup_id",$row,$rst);
				get_rst("select bill_state from ord_details where cart_id=".$cart_id,$row_bs);
				$item_total = 0;
				$vat_value = 0;
				$gst_cap_2 = 0;
				if($st_rw["sup_ext_state"] == $row_bs["bill_state"]){
					$gst_cap_2 = 1; // 0 for inter-state GST, 1 for intra-state GST
				}
				do{ 
					$cart_price = 0;
					$item_color = "";
					$cart_qty = 0;
					$cart_price_tax = 0;
					$tax_percent = 0;
					if($row["item_buyer_action"].""<>""){
						$item_color = "#d9534f";
						if($row["new_seller_price"] <> ""){
							$cart_price = $row["new_seller_price"];
						}else{
							$cart_price = $row["cart_price"];
						}
						$cart_qty = $row["cart_qty"];
						$net_price = $cart_price * $cart_qty;
						$tax_percent = $row["tax_percent"];
						$tax_value = $net_price * ($tax_percent/100);
						$cart_price_tax = $cart_price + $tax_value;	
					}else{	
						if($row["new_seller_price"] <> ""){
							$cart_price = $row["new_seller_price"];
						}else{
							$cart_price = $row["cart_price"];
						}
						$cart_qty = $row["cart_qty"];
						$tax_percent = $row["tax_percent"];
						$net_price = $cart_price * $cart_qty;
						$tax_value = $cart_price * ($tax_percent/100);
						$cart_price_tax = $cart_price + $tax_value;	
						$item_total = $item_total + ($cart_price * $cart_qty);
						$vat_value = $vat_value + ($tax_value * $cart_qty);						
					}
					$prod_id = $row["prod_id"];
					get_rst("select prod_name from prod_mast where prod_id=$prod_id",$rw);
					?>
					<tr style="background-color:<?=$item_color?>;">
					<td rowspan="1" align="center" class="img-padding"><img src="<?=show_img($row["item_thumb"])?>" width="51" height="33" border="0" alt="<?=$row["item_name"]?>"/></td>
					<td rowspan="1" align="center" valign="middle" class="table-bg2"><p><?=$row["item_stock_no"]." - ".$row["item_name"]?></a></p></td>
					<td rowspan="1" align="center" valign="middle" class="table-bg2"><?=formatNumber($cart_price,2)?>&nbsp;</td>
					<td rowspan="1" align="center" valign="middle" class="table-bg2"><?=formatNumber($tax_percent,2)?>&nbsp;</td>
					<td valign="middle" class="table-bg2">
					<?=formatNumber($cart_price_tax,2)?>&nbsp;
					</td>
					
					
					<td valign="middle" align="center" class="table-bg2">
						<?=$cart_qty?>
					</td>
					<td valign="middle" align="center" class="table-bg2"><strong><p><?=formatNumber($cart_qty * $cart_price_tax,2)?></p></strong>&nbsp;&nbsp;
					<?php if($row["item_buyer_action"]."" == ""){ ?> <td valign="middle" align="center" class="table-bg2"><strong><p>Processed</p></strong>&nbsp;&nbsp;
					<?php }else{ ?> <td valign="middle" align="center" class="table-bg2"><strong><p>Cancelled</p></strong>&nbsp;&nbsp; <?php } ?>

					</tr>
				<?php
				}while($row = mysqli_fetch_assoc($rst));?>		
			</table>

			<table width="80%"  border="1" class="list" style="margin: 0 auto;">
			<?php	
			$actual_ship_amt=0;		//for displaying free shipping...
			get_rst("select actual_ship_amt from ord_items where cart_id='".$cart_id."' and sup_id=$sup_id",$row_ship,$rst_ship);
				do{
					$actual_ship_amt = $row_ship["actual_ship_amt"]+$actual_ship_amt;
				}while($row_ship = mysqli_fetch_assoc($rst_ship));
						
			$shipping_charges = $row_s["shipping_charges"]."";
			$coupon_amt = 0;
			$coupon_tax = 0;
			if(get_rst("select coupon_id,coupon_amt from ord_coupon where cart_id=$cart_id", $row_c)){
				$coupon_amt = $row_c["coupon_amt"];
				$coupon_tax = $coupon_amt * ($vat_value/$item_total);
				$vat_value = $vat_value - $coupon_tax;
			}
			$ord_total = $item_total + $vat_value + $shipping_charges - $coupon_amt;
						
			if($shipping_charges.""==""){
				$v_shipping_charges = "As per the delivery area";
			}else{
				$v_shipping_charges = formatNumber($shipping_charges,2);
			}
			?>			
			<tr>
			<td align="right"><strong><p><span class="green-basket">Total Order Price:</span></p></strong></td>
			<td align="right"><strong><?=FormatNumber($ord_total,2)?></strong>&nbsp;</td>
			</tr>
			
			</tr>	
			<tr>
			<td width="65%" align="right"><strong><p><span class="green-basket">Shipping:</span></p></strong></td>
			<td width="25%" align="right"><?=$v_shipping_charges?>&nbsp;</td>
			</tr>

			<?php if($gst_cap_2 == 0){?> <!-- Inter-State GST -->
				<tr>
				<td align="right"><strong><p><span class="green-basket">Total IGST :</span></p></strong></td>
				<td align="right"><?=FormatNumber($vat_value,2)?>&nbsp;</td>
				</tr>
			<?php }else{ ?> <!-- Intra-State GST -->
				<tr>
				<td align="right"><strong><p><span class="green-basket">Total CGST :</span></p></strong></td>
				<td align="right"><?=FormatNumber($vat_value/2,2)?>&nbsp;</td>
				</tr>
				<tr>
				<td align="right"><strong><p><span class="green-basket">Total SGST :</span></p></strong></td>
				<td align="right"><?=FormatNumber($vat_value/2,2)?>&nbsp;</td>
				</tr>
			<?php } ?>

			<tr style="background-color: #ffd699">
			<td align="right"><strong><p><span class="green-basket">Net Item Price:</span></p></strong></td>
			<td align="right"><?=FormatNumber($pay_total - $vat_value,2)?>&nbsp;</td>
			</tr>
			
			<tr>
			<td align="right"><strong><p><span class="green-basket">Company-Name Commission :</span></p></strong></td>
			<td align="right"><?=FormatNumber($pay_comission,2)?>&nbsp;</td>
			</tr>		
			
			<tr>
			<td align="right"><strong><p><span class="green-basket">GST on Commission (@ 18%):</span></p></strong></td>
			<td align="right"><?=FormatNumber($service_tax,2)?>&nbsp;</td>
			</tr>		
			<?php if($gst_cap_1 == 0){?> <!-- Inter-State GST -->
				<tr>
				<td align="right"><strong><p><span class="green-basket">IGST (@ 18%):</span></p></strong></td>
				<td align="right"><?=FormatNumber($service_tax,2)?>&nbsp;</td>
				</tr>
			<?php }else{ ?> <!-- Intra-State GST -->
				<tr>
				<td align="right"><strong><p><span class="green-basket">CGST(@ 9%):</span></p></strong></td>
				<td align="right"><?=FormatNumber($service_tax/2,2)?>&nbsp;</td>
				</tr>	
				<tr>
				<td align="right"><strong><p><span class="green-basket">SGST (@ 9%):</span></p></strong></td>
				<td align="right"><?=FormatNumber($service_tax/2,2)?>&nbsp;</td>
				</tr>				
			<?php } ?>			
			<tr>
			<td align="right"><strong><p><span class="green-basket">Free Shipping Amount </span></span><a href="#" data-toggle="popover" data-trigger="hover" data-content="These charges are in accordance with the offer of giving free shipping to buyers if they buy products worth Rs 2000 or more."><span class="glyphicon glyphicon-question-sign help"></span></p></strong></td>
			<td align="right"><?=FormatNumber($actual_ship_amt,2)?>&nbsp;</td>
			</tr>
			
			<tr>
			<td align="right"><strong><p><span class="green-basket">Seller Price:</span></p></strong></td>
			<td align="right"><?=FormatNumber($pay_total - $vat_value,2)?>&nbsp;</td>
			</tr>

			<tr>
			<td align="right"><strong><p><span class="green-basket">Total Amount to be Paid(Seller Price + GST):</span></p></strong></td>
			<td align="right"><?=FormatNumber($pay_total,2)?>&nbsp;</td>
			</tr>
	
			<tr>
			<td align="right"><strong><p><span class="green-basket">Total Amount to be Paid(Round-Off):</span></p></strong></td>
			<td align="right">Rs.<?=round($pay_total,0,PHP_ROUND_HALF_UP)?>&nbsp;</td>
			</tr>
			<tr class="noprint">
			<td align="right"><strong><p><span class="green-basket">Payment Status:</span></p></strong></td>
			<td align="right"><?=$pay_status?>&nbsp;</td>
			</tr>
			</table>
		</div>
		<br>
		<table>
			<tr>
				<td align="left"><input id="inv-btn" type="button" class="btn btn-warning" value=" Show details " onclick="javascript: toggle_details('detailed_invoice');"/></td>
				<td align="right"><input type="button" class="btn btn-warning" value=" Print Invoice " onclick="javascript: print_page('ord_invoice');"/></td>
			</tr>
		</table>
		<br>
		<!--Detailed invoice -->
		<div id="detailed_invoice" style="display:none">					
			<center><h3>Company-Name Order Details</h3>
			<?php
			$Productinfo="Test Products";
			get_rst("select * from ord_items where cart_id='".$cart_id."' and sup_id=$sup_id",$row,$rst);
			$total_tax = 0;
			do{ ?> 
			<table width="80%"  border="1" align="center" class="list" style="margin: 0 auto;">
				<tr>
				<td xwidth="20%" align="left" class="table-bg"><p><strong>Product Image</strong></p></td>
				<td xwidth="32%" align="left" class="table-bg"><p><strong>Product Name</strong></p></td>
				<?php IF(func_var($row["user_type"])=="S"){?>
					<td xwidth="32%" align="left" class="table-bg"><p><strong>Seller</strong></p></td>
				<?php }?>
				<td xwidth="14%" align="left" class="table-bg"><p><strong>Price</strong></p></td>
				<td xwidth="14%" align="left" class="table-bg"><p><strong>GST</strong></p></td>
				<td xwidth="14%" align="left" class="table-bg"><p><strong>Price after TAX</strong></p></td>
				<td xwidth="14%" align="left" class="table-bg"><p><strong>Qty</strong></p></td>
				<?php if($row["item_buyer_action"]."" == ""){ ?> <td width="12%" align="left" class="table-bg"><p><strong>Total</strong></p></td> 
				<?php }else{ ?> <td width="12%" align="left" class="table-bg"><p><strong>Status</strong></p></td> <?php } ?>
				
				</tr>
					<?php		
						$cart_price = 0;
						$item_color = "";
						$cart_qty = 0;
						$cart_price_tax = 0;
						$tax_percent = 0;
						$net_price = 0;
						$actual_ship_amt=0;
						if($row["item_buyer_action"].""<>""){
							$item_color = "#d9534f";
							if($row["new_seller_price"] <> ""){
								$cart_price = $row["new_seller_price"];
							}else{
								$cart_price = $row["cart_price"];
							}
							$cart_qty = $row["cart_qty"];
							$net_price = $cart_price * $cart_qty;
							$tax_percent = $row["tax_percent"];
							$tax_value = $net_price * ($tax_percent/100);
							$cart_price_tax = $cart_price + $tax_value;													
							
						}else{	
							if($row["new_seller_price"] <> ""){
								$cart_price = $row["new_seller_price"];
							}else{
								$cart_price = $row["cart_price"];
							}
							$cart_qty = $row["cart_qty"];
							$net_price = $cart_price * $cart_qty;
							$tax_percent = $row["tax_percent"];
							$actual_ship_amt= $row["actual_ship_amt"];
							$tax_value = $cart_price * ($tax_percent/100);
							$total_tax = $tax_value *$cart_qty;
							$cart_price_tax = $cart_price + $tax_value;													
						}
						$prod_id = $row["prod_id"];
						//get per item commission
						get_rst("select sup_commission from prod_sup where sup_id=0$sup_id and prod_id=0$prod_id", $rw_com);
						$unit_commission = $net_price * intval($rw_com["sup_commission"]) / 100;
						$tax_unit_commission = ($unit_commission * 18) / 100; // 18% GST	
						$net_payable = $net_price - $unit_commission - $tax_unit_commission - $actual_ship_amt;		
					?>
					<tr style="background-color:<?=$item_color?>;">
					<td class="img-padding"><a href="<?=get_prod_url($prod_id,"")?>"><img src="<?=show_img($row["item_thumb"])?>" width="51" height="33" border="0" alt="<?=$row["item_name"]?>" /></a></td>
					<td align="left" valign="middle" class="table-bg2"><p><?=$row["item_stock_no"]." - ".$row["item_name"]?></a></p></td>
					<td align="center" valign="middle" class="table-bg2"><p><?=formatNumber($cart_price,2)?></p></td>
					<td align="center" valign="middle" class="table-bg2">
					<?php
					IF(floatval($tax_percent)>0){
						echo($tax_percent."% ");
					}?>
					</td>
					<td valign="middle" class="table-bg2"><p>
					<?=formatNumber($cart_price_tax,2)?></p>
					</td>
					
					
					<td valign="middle" align="center" class="table-bg2">
						<?=$cart_qty?>
					</td>
					<?php if($row["item_buyer_action"]."" == ""){ ?> <td valign="middle" align="center" class="table-bg2"><strong><p><?=formatNumber($cart_qty * $cart_price_tax,2)?></p></strong>&nbsp;&nbsp;
					<?php }else{ ?> <td valign="middle" align="center" class="table-bg2"><strong><p>Cancelled</p></strong>&nbsp;&nbsp; <?php } ?>
					</td>
				</tr>
			</table>
			<?php if($row["item_buyer_action"]."" == ""){ ?>
				<table width="80%"  border="1" class="list" style="margin: 0 auto;">

					<tr style="background-color: #ffd699">
					<td align="right"><strong><p><span class="green-basket">Net Item Price:</span></p></strong></td>
					<td align="right"><?=FormatNumber($net_price,2)?>&nbsp;</td>
					</tr>
					
					<tr>
					<td align="right"><strong><p><span class="green-basket">Company-Name Commission (@ <?=$rw_com["sup_commission"]?>%):</span></p></strong></td>
					<td align="right"><?=FormatNumber($unit_commission,2)?>&nbsp;</td>
					</tr>		
					
					<tr>
					<td align="right"><strong><p><span class="green-basket">GST on commission (@ 18%):</span></p></strong></td>
					<td align="right"><?=FormatNumber($tax_unit_commission,2)?>&nbsp;</td>
					</tr>		
					<?php if($gst_cap_1 == 0){?> <!-- Inter-State GST -->
						<tr>
						<td align="right"><strong><p><span class="green-basket">IGST (@ 18%):</span></p></strong></td>
						<td align="right"><?=FormatNumber($tax_unit_commission,2)?>&nbsp;</td>
						</tr>
					<?php }else{ ?> <!-- Intra-State GST -->
						<tr>
						<td align="right"><strong><p><span class="green-basket">CGST (@ 9%):</span></p></strong></td>
						<td align="right"><?=FormatNumber($tax_unit_commission/2,2)?>&nbsp;</td>
						</tr>
						<tr>
						<td align="right"><strong><p><span class="green-basket">SGST (@ 9%):</span></p></strong></td>
						<td align="right"><?=FormatNumber($tax_unit_commission/2,2)?>&nbsp;</td>
						</tr>
					<?php } ?>					
					<tr>
					<td align="right"><strong><p><span class="green-basket">Free Shipping Amount </span><a href="#" data-toggle="popover" data-trigger="hover" data-content="These charges are in accordance with the offer of giving free shipping to buyers if they buy products worth Rs 2000 or more."><span class="glyphicon glyphicon-question-sign help"></span></a></p></strong></td>
					<td align="right"><?=FormatNumber($actual_ship_amt,2)?>&nbsp;</td>
					</tr>
					
					<tr>
					<td align="right"><strong><p><span class="green-basket">Seller Price:</span></p></strong></td>
					<td align="right"><?=FormatNumber($net_payable,2)?>&nbsp;</td>
					</tr>

					<tr>
					<td align="right"><strong><p><span class="green-basket">Total Amount to be Paid(Seller Price + GST):</span></p></strong></td>
					<td align="right"><?=FormatNumber($net_payable + $total_tax,2)?>&nbsp;</td>
					</tr>

					<tr>
					<td align="right"><strong><p><span class="green-basket">Total Amount to be Paid(Round-Off):</span></p></strong></td>
					<td align="right">Rs.<?=round($net_payable + $total_tax,0,PHP_ROUND_HALF_UP)?>&nbsp;</td>
					</tr>
				</table>
				<?php } ?>
				<br>
			<?php }while($row = mysqli_fetch_assoc($rst));?>		
		</div>
		<center><input id="print_detail" type="button" class="btn btn-warning" value=" Print Detailed Invoice " onclick="javascript: print_page('detailed_invoice');"/></center>
	</center>
<?php
require_once("inc_admin_footer.php");
?>