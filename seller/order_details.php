<?php
require_once("inc_admin_header.php");
require_once("logistics_push.php");

$id = func_read_qs("id");
$act = func_read_qs("act");
$sup_id = $_SESSION["sup_id"];

if(get_rst("select * from ord_summary where ord_id=0$id and sup_id=$sup_id",$row_s,$rst_s)){
	$cart_id = $row_s["cart_id"];
	$user_id= $row_s["user_id"];
	$ord_no = $row_s["ord_no"];
}

if($act<>""){
	$delivery_status_old = func_read_qs("delivery_status_old");
	$delivery_status = func_read_qs("delivery_status");
	$v_cancelled = "";
	
	if($delivery_status<>"Pending" and $delivery_status_old <> $delivery_status){
		if(get_rst("select buyer_action from ord_summary where cart_id=$cart_id and sup_id=$sup_id and buyer_action is not null")){
			$v_cancelled = "1";
			js_alert("Sorry the order has just been cancelled by the Buyer hence cannot be Dispatched.");
		}	
	}
	
	if($v_cancelled == ""){
		
		get_rst("select sup_company from sup_mast where sup_id=$sup_id",$row_e);	//to get Seller name
		$sup_company=$row_e["sup_company"];
		
		$sql = "select memb_email from member_mast where memb_id='".$user_id."'";		//to get buyers email id
		get_rst($sql,$row_u);
		$user_email=$row_u["memb_email"];
		$logistics_m="LP";															//to get logistics managers email id 
		get_rst("select user_email from user_mast where user_type='".$logistics_m."'",$row_lm);
		$logistics_email=$row_lm["user_email"];
		
		$fld_arr = array();
		$fld_arr["pkg_weight_kgs"] = func_read_qs("pkg_weight_kgs");
		$fld_arr["pkg_weight"] = func_read_qs("pkg_weight");
		$fld_arr["pkg_height"] = func_read_qs("pkg_height");
		$fld_arr["pkg_width"] = func_read_qs("pkg_width");
		$fld_arr["pkg_depth"] = func_read_qs("pkg_depth");
		$fld_arr["pkg_count"] = func_read_qs("pkg_count");
		$fld_arr["pickup_datetime"] = func_read_qs("pickup_datetime");
		$fld_arr["delivery_status"] = $delivery_status;
		$msg = "";
		
		if($delivery_status_old <> "Dispatched" and $delivery_status == "Dispatched"){
			$actual_ship_amt=0;
			get_rst("select actual_ship_amt from ord_items where cart_id='".$cart_id."' and sup_id=$sup_id",$row_ship,$rst_ship);
			do{
				$actual_ship_amt = $row_ship["actual_ship_amt"]+$actual_ship_amt;
			}while($row_ship = mysqli_fetch_assoc($rst_ship));
			
			if($row_s["new_seller_price"] <> ""){
				get_rst("select new_seller_price,cart_price_tax,tax_percent,cart_qty from ord_items where cart_id='".$cart_id."' and sup_id=$sup_id",$row_in,$rst_in);
				$fld_inv_arr = array();
				$fld_inv_arr["ord_no"] = $row_s["ord_no"];
				$fld_inv_arr["sup_id"] = $sup_id;
				$fld_inv_arr["pay_status"] = "Pending";
				$fld_inv_arr["pay_comission"] = func_read_qs("sup_total_commission");
				$fld_inv_arr["service_tax"] = ($fld_inv_arr["pay_comission"] *18) / 100;	//18% GST
				do{
					if($row_in["new_seller_price"] <> ""){
						$new_price = ($row_in["new_seller_price"] * $row_in["tax_percent"])/100;
						$new_price = $new_price * $row_in["cart_qty"];
						$total_price = $total_price + ($row_in["new_seller_price"] * $row_in["cart_qty"]) + $new_price;
					}else{
						$total_price = $total_price + ($row_in["cart_price_tax"]*$row_in["cart_qty"]);
					}
				}while($row_in = mysqli_fetch_assoc($rst_in));
				$fld_inv_arr["pay_total"] = floatval($total_price) - $fld_inv_arr["pay_comission"] - $fld_inv_arr["service_tax"] - $actual_ship_amt;
				$qry = func_insert_qry("invoice_mast",$fld_inv_arr);
				execute_qry($qry);
			}else{
				$fld_inv_arr = array();
				$fld_inv_arr["ord_no"] = $row_s["ord_no"];
				$fld_inv_arr["sup_id"] = $sup_id;
				$fld_inv_arr["pay_status"] = "Pending";
				$fld_inv_arr["pay_comission"] = func_read_qs("sup_total_commission");
				$fld_inv_arr["service_tax"] = ($fld_inv_arr["pay_comission"] *18) / 100;	//18% GST
				$fld_inv_arr["pay_total"] = floatval($row_s["item_total"] + $row_s["vat_value"]) - $fld_inv_arr["pay_comission"] - $fld_inv_arr["service_tax"] - $actual_ship_amt;
				$qry = func_insert_qry("invoice_mast",$fld_inv_arr);
				execute_qry($qry);
			}
		}
		
		if($delivery_status=="Ready-to-Ship"){ //creates a logistics push request.
			$address_id = func_read_qs("address_id");
			if($address_id==""){
				get_rst("select addr_id from sup_ext_addr where sup_id=$sup_id LIMIT 1",$row_add);
				$address_id=$row_add["addr_id"];
			}
			get_rst("select local_logistics from ord_summary where sup_id=$sup_id and ord_id=0$id",$r);
					if($r["local_logistics"] == 1){
						get_rst("select Admin_email from configuration",$row_em);
						$super_admin_email = $row_em["Admin_email"];
						$from = "orders@Company-Name.com";
						$body="Hi,<br>";
						$body.="An order with orderID=$ord_no has been placed, and needs to be shipped from local logistics vendor.<br>";
						$body.="<br><br>Regards,<br>Team Company-Name";		
						xsend_mail($logistics_email,"Company-Name - Local Logistics",$body,$from );//mail send to local logistics
				
						$from = "orders@Company-Name.com";
						$body="Hi,<br>";
						$body.="An order with orderID=$ord_no has been placed, and needs to be shipped from local logistics vendor.<br>";
						$body.="<br><br>Regards,<br>Team Company-Name";		
						xsend_mail($super_admin_email,"Company-Name - Local Logistics",$body,$from );// mail send to super admin
						$msg="1";
					}else{
						lgs_create_order($id,$msg,$address_id);
					}
			if($msg <> "1"){
				js_alert("Record cannot be updated, please contact Company-Name support.");	
			}
		}
		
		if($msg <> "0"){
			$qry = func_update_qry("ord_summary",$fld_arr," where ord_id=0$id and sup_id=$sup_id");
			execute_qry($qry);
			
			$from = "orders@Company-Name.com";		//mail is send to buyer about order status
			$body="Hi,<br>";
			$body.="The status of your order with order no.: $ord_no has been changed by the seller to $delivery_status.<br>";
			$body.="<br><br>Regards,<br>Team Company-Name";		
			xsend_mail($user_email,"Company-Name - Order Tracking",$body,$from );
			
			$body1="Hi,<br>";					//mail is send to logistics manager about order status
			$body1.="&nbsp;&nbsp;The status of the order with order no.: $ord_no has been changed by the seller to $delivery_status.<br>";
			$body1.="<br>Seller Id : $sup_id";
			$body1.="<br>Seller Name : $sup_company";
			$body1.="<br><br>Regards,<br>Team Company-Name";		
			xsend_mail($logistics_email,"Company-Name - Order Tracking",$body1,$from );
			
			sendnotifiction_deliverystatus($cart_id,$user_id,$delivery_status,$ord_no);		// for sending notification to mobile...
			
			js_alert("Order details updated successfully.");
		}
	}
}

if(get_rst("select * from ord_summary where ord_id=0$id and sup_id=$sup_id",$row_s,$rst_s)){
	$cart_id = $row_s["cart_id"];
	$delivery_status = $row_s["delivery_status"];
	$pay_status = $row_s["pay_status"];
	
}else{
	header('location: order_list.php');
	js_redirect("order_list.php");
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
if(get_rst("select * from member_mast where memb_email='".$bill_email."'",$row_m)){
	$memb_company = $row_m["memb_company"];
	$memb_vat = $row_m["memb_vat"];
}
$gst_cap = 0;
if(get_rst("select sup_vat,sup_company from sup_mast where sup_id=$sup_id",$row_tax)){
	$sub_vat = $row_tax["sup_vat"];
	$sub_company = $row_tax["sup_company"];
	get_rst("select sup_ext_state from sup_ext_addr where sup_id=$sup_id", $st_rw);
	if($st_rw["sup_ext_state"] == $bill_state){
		$gst_cap = 1; // 0 for inter-state GST, 1 for intra-state GST
	}
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

$( function() {
	$( "#datepicker" ).datetimepicker({
		dateFormat: "yy/mm/dd",
		timeFormat: "hh:mm:ss",
		minDate: 0,
		controlType: 'select'
	});
} );

function js_save(){
	if(chkForm(document.frm_order)==false){
		return false
	}else{
		(function(){
			var gif_show_s = document.getElementById("gif_show_s")
			var content_hide_s = document.getElementById("content_hide_s"),
		show = function(){
			gif_show_s.style.display = "block";
		},
		hide = function(){
			gif_show_s.style.display = "none";
		};

	show();
  })();
		document.frm_order.submit();
	}
}

function js_cal_vw(){
	document.getElementById("span_vol_wt").innerHTML = parseFloat(frm_order.pkg_height.value) * parseFloat(frm_order.pkg_width.value) * parseFloat(frm_order.pkg_depth.value)/5000
}

//Print Form
function print_page(divID){
    var printContents = document.getElementById(divID).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
function addr_print(obj){
	v_add = obj.value
	v_add_arr = v_add.split('|');
	var address = v_add_arr[0].concat(","+" "+v_add_arr[1]);
	address = address.concat(","+" "+v_add_arr[2]);
	address = address.concat(","+" "+v_add_arr[3]);
	address = address.concat(","+" "+v_add_arr[4]);
	document.getElementById("pickup_address").innerHTML = address;
	document.getElementById("pickup_address").style.display = "block";
	document.getElementById("address_id").value = v_add_arr[5];
}
</script>
<style>
	#pickup_address{
		display:none;
		border:none;
		margin-top:5px;
	}
</style>
<script type="text/javascript" src="../lib/frmCheck.js"></script>
<form name="frm_order" action="order_details.php" method="post">
	<input type="hidden" name="act" value="1">
	<input type="hidden" name="id" value="<?=$id?>">
	<input type="hidden" name="address_id" id="address_id" value="">
	<center>
		<table width="80%"  border="1" align="center" class="checkout" style="margin: 0 auto;">

			<tr>
				<td><strong>WayBill No.</strong></td><td><?=$row_s["way_billl_no"]?></td>
				<?php if($row_s["way_billl_no"] <> ""){
				$barcode_url = "../lib/barcode.php?codetype=Code39&size=40&text=".$row_s["way_billl_no"]; ?>
				<td><strong>Barcode</strong></td><td class="img-padding"><img src="<?=$barcode_url?>" height="30"/></td>
				<?php }else { ?>
					<td><strong>Barcode</strong></td><td class="img-padding"></td>
				<?php } ?>
			</tr>
			</tr>
			
			<?php if($row_s["buyer_action"]=="Cancelled"){?>
			<tr style="background-color:#FF0000;">
				<td align="center" style="background-color:#FF0000;" colspan="10"><font color='white'>This order has been cancelled by the Buyer</font></td>
			</tr>		
			<?php }?>
			
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
		<?php if($row_m["ind_buyer"]==1){?>
		<td align="left" class="table-bg2"><?=$memb_company?></td>
		<td align="left" class="table-bg2"><?=$memb_company?></td><?php }else{?>
		<td align="left" class="table-bg2"><?=$bill_name?></td>
		<td align="left" class="table-bg2"><?=$ship_name?></td>
		<?php }?>
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
		
		<center><h3>Licensing Details</h3>
		
		<table width="80%"  border="1" align="center" class="checkout" style="margin: 0 auto;">
		<th>User</th>
		<th>GST Number</th>
		<tr>
		<td><p>Seller :&nbsp; <?=$sub_company?></p></td>
		<td><?=$sub_vat?></td>
		</tr>
		<?php if($memb_vat<>""){?>
		<tr>
		<td><p>Buyer :&nbsp; <?=$memb_company?></p></td>
		<td><?=$memb_vat?></td>
		</tr>
		<?php }?>
		</table>
		
		<center><h3>Item details</h3>

		<table width="80%"  border="1" align="center" class="list" style="margin: 0 auto;">
		<tr style="font-size:1em; background-color:#D5D5D5">
			<td width="10%" align="center" class="table-bg">Image</td>
			<td width="10%" align="center" class="table-bg">HSN Code</td>
			<td width="24%" align="center" class="table-bg">Item Description</td>
			<td width="10%" align="center" class="table-bg">Price &nbsp;<span><div class="" style="display:inline-block;">&#8377; </div></span></td>
			<td width="8%" align="center" class="table-bg">GST</td>
			<td width="10%" align="center" class="table-bg">Price after TAX &nbsp;<span><div class="" style="display:inline-block;">&#8377; </div></span></td>
			<td width="10%" align="center" class="table-bg">Shipping Charges &nbsp;<span><div class="" style="display:inline-block;">&#8377; </div></span></td>
			<td width="6%" align="center" class="table-bg">Qty</strong></td>
			<td align="center" width="12%" class="table-bg">Total&nbsp;<span><div class="" style="display:inline-block;">&#8377; </div></span></td>
		</tr>
		<?php 	
		$Productinfo="Test Products";
		get_rst("select * from ord_items where cart_id='".$cart_id."' and sup_id=$sup_id",$row,$rst);
		$sup_commission = 0;
		$item_total = 0;
		$vat_value = 0;
		do{
		
			$cart_price = 0;
			$item_color = "";
			if($row["item_buyer_action"].""<>""){
				$item_color = "#d9534f";
			}else{	
				if($row["new_seller_price"] <> ""){
					$cart_price = $row["new_seller_price"];
				}else{
					$cart_price = $row["cart_price"];
				}
			}
			$cart_qty = $row["cart_qty"];
			$prod_id = $row["prod_id"];
			$tax_percent = $row["tax_percent"];
			$tax_value = $cart_price * ($tax_percent/100);
			$cart_price_tax = $cart_price + $tax_value;
			$item_total = $item_total + ($cart_price * $cart_qty);
			$vat_value = $vat_value + ($tax_value * $cart_qty);
			//calculate seller commision
			get_rst("select sup_commission, price, offer_disc from prod_sup where sup_id=0$sup_id and prod_id=0$prod_id", $rw_com);
			get_rst("select hsn_code from prod_mast where prod_id=0$prod_id", $prow);
			$sup_commission = $sup_commission + ((($rw_com["price"] - ($rw_com["price"] * $rw_com["offer_disc"]/100)) * $cart_qty) * floatval($rw_com["sup_commission"]) / 100); ?>
			<tr style="background-color:<?=$item_color?>;">
			<td rowspan="1" class="img-padding"><a href="<?=get_prod_url($prod_id,"")?>"><img src="<?=show_img($row["item_thumb"])?>" width="51" height="38" border="0" alt="<?=$row["item_name"]?>" /></a></td>
			<td rowspan="1" align="center" valign="middle" class="table-bg2"><p><?=$prow["hsn_code"]?></p></td>
			<td rowspan="1" align="center" valign="middle" class="table-bg2"><p><?=$row["item_stock_no"]." - ".$row["item_name"]?></a></p></td>
			<td rowspan="1" align="center" valign="middle" class="table-bg2"><?=formatNumber($cart_price,2)?>&nbsp;</td>
			<td rowspan="1" align="center" valign="middle" class="table-bg2">
			<?php
			IF(floatval($tax_percent)>0){
				echo($tax_percent."% ");
			}?>
			</td>
			<td valign="middle" align="center" class="table-bg2">
			<?=formatNumber($cart_price_tax,2)?>&nbsp;
			</td>
			<?php if($row["ship_amt"]==0){?>
			<td  align="center" style="color: #006633;"><b>Free</b></td><?php }else{?>
			<td  align="center"><?=$row["ship_amt"]?>&nbsp;</td>
			<?php }?>
			
			<td valign="middle" align="center" class="table-bg2">
				<?=$cart_qty?>
			</td>
			<td valign="middle" align="center" class="table-bg2"><strong><?=formatNumber(($cart_qty * $cart_price_tax) + $row["ship_amt"],2)?></strong>&nbsp;&nbsp;</td>

			</tr>
			<?php	}while($row = mysqli_fetch_assoc($rst));?>
		</table>
		<input type="hidden" name="sup_total_commission" value="<?=$sup_commission?>">
		<table width="80%"  border="1" class="list" style="margin: 0 auto;">
		<?php		

		$shipping_charges = $row_s["shipping_charges"]."";
		$ord_total = $item_total + $vat_value + $shipping_charges;
		$vat_percent = $row_s["vat_percent"];
		
		if($shipping_charges.""==""){
			$v_shipping_charges = "As per the delivery area";
		}else{
			$v_shipping_charges = "Rs.".formatNumber($shipping_charges,2);
		}
		?>

		<tr>
		<td align="right"><strong><p><span class="green-basket">Sub-Total (exc. Tax) &nbsp;</span></p></strong></td>
		<td align="right">Rs.<?=FormatNumber($item_total,2)?>&nbsp;</td>
		</tr>	
		<tr>
		<td width="65%" align="right"><strong><p><span class="green-basket">Shipping Charges &nbsp;</span></p></strong></td>
		<?php if($shipping_charges==0){?>
		<td width="25%" align="right"> Free Delivery &nbsp;</td><?php }else{?>
		<td width="25%" align="right"><?=$v_shipping_charges?>&nbsp;</td>
		<?php }?>
		</tr>

		<?php if($gst_cap == 0){?> <!-- Inter-State GST -->
			<tr>
			<td align="right"><strong><p><span class="green-basket">Total IGST &nbsp;</span></p></strong></td>
			<td align="right">Rs.<?=FormatNumber($vat_value,2)?>&nbsp;</td>
			</tr>
		<?php }else{ ?> <!-- Intra-State GST -->
			<tr>
			<td align="right"><strong><p><span class="green-basket">Total CGST &nbsp;</span></p></strong></td>
			<td align="right">Rs.<?=FormatNumber($vat_value/2,2)?>&nbsp;</td>
			</tr>
			<tr>
			<td align="right"><strong><p><span class="green-basket">Total SGST &nbsp;</span></p></strong></td>
			<td align="right">Rs.<?=FormatNumber($vat_value/2,2)?>&nbsp;</td>
			</tr>
		<?php } ?>

		<tr>
		<td align="right"><strong><p><span class="green-basket">Total Price &nbsp;</span></p></strong></td>
		<td align="right"><strong>Rs.<?=FormatNumber($ord_total,2)?></strong>&nbsp;</td>
		</tr>

		<tr>
		<td align="right"><strong><p><span class="green-basket">Total Price (Round-Off) &nbsp;</span></p></strong></td>
		<td align="right"><strong>Rs.<?=round($ord_total,0,PHP_ROUND_HALF_UP)?></strong>&nbsp;</td>
		</tr>
		</table>
	<?php
	$pkg_disabled="";	//"disabled";
	if($row_s["delivery_status"]=="Pending" and $row_s["buyer_action"].""==""){
		$pkg_disabled="";
	}
	//}while($row_s = mysql_fetch_assoc($rst_s));
	?>
	<br>
	
	<table width="80%"  border="1" align="center" class="table_form" style="width:80%;">
		<tr>
			<th colspan="2" align="left" >Payment Status: <?=$row_s["pay_status"]?></th>
		</tr>
	</table>
	
	<?php if($row_s["buyer_action"]<>"Cancelled" and $row_s["pay_status"]<>"Pending"){?>
		<br>
		<table width="80%"  border="1" align="center" class="table_form" style="width:80%;">
		<tr>
			<th colspan="3" align="left" >Packaging Details</th>
		</tr>
		<tr>
			<td>Weight:</td>
			<td>
			<input type="text" name="pkg_weight_kgs" onkeypress="validate_key(event);" value="<?=$row_s["pkg_weight_kgs"]?>" <?=$pkg_disabled?> size="5" id="110" title="Weight in Kgs"> Kgs + 
			<input type="text" name="pkg_weight" onkeypress="validate_key(event);" value="<?=$row_s["pkg_weight"]?>" <?=$pkg_disabled?> size="5" id="110" title="Weight in gms"> gms
			</td>
			<td></td>
		</tr>
		
		<tr>
			<td>Dimension (H x W x D):</td><td>
			<input type="text" name="pkg_height" onkeypress="validate_key(event);" value="<?=$row_s["pkg_height"]?>" <?=$pkg_disabled?> size="5" id="110" title="Height in cms"> cms x 
			<input type="text" name="pkg_width" onkeypress="validate_key(event);" value="<?=$row_s["pkg_width"]?>" <?=$pkg_disabled?> size="5" id="110" title="Width in cms"> cms x 
			<input type="text" name="pkg_depth" onkeypress="validate_key(event);" value="<?=$row_s["pkg_depth"]?>" <?=$pkg_disabled?> size="5" id="110" title="Depth in cms"> cms
			</td>
			<td></td>
		</tr>
		<tr>
			<td>Volumetric weight:</td>
			<td>
			<?php if($pkg_disabled==""){?>
				<input type="button" class="btn btn-warning" value="Calculate" onclick="javascript: js_cal_vw();"> 
			<?php }?>
			&nbsp; &nbsp;<span id="span_vol_wt"></span>
			</td>
			<td>Package Count: <input type="text" name="pkg_count" onkeypress="validate_key(event);" value="<?=$row_s["pkg_count"]?>" <?=$pkg_disabled?> size="5" id="110" title="Number of packages"></td>
		</tr>
		<tr>
		<td>Pick-up Date-Time:</td><td>
			<input type="text" id="datepicker" name="pickup_datetime" value="<?=$row_s["pickup_datetime"]?>">
		</td>
		<?php
			$add_flds = "concat(sup_ext_address,'|',sup_ext_city,'|',sup_ext_state,'|',sup_ext_pincode,'|',sup_ext_contact_no,'|',addr_id)";
		?>
		<td>Pick-up Location</td><td>
			<select name="addr_id" onchange="addr_print(this);">
				<?php
					get_rst("select local_logistics from ord_summary where sup_id=$sup_id and ord_id=0$id",$r);
					if($r["local_logistics"] == 1){
						create_cbo("select $add_flds,sup_ext_address_type from sup_ext_addr where sup_id=$sup_id and sup_ext_pincode LIKE '411%' OR sup_ext_pincode LIKE '410%' OR sup_ext_pincode LIKE '412%'")?>
					<?php }else{
						create_cbo("select $add_flds,sup_ext_address_type from sup_ext_addr where sup_id=$sup_id")?>
				<?php }?>
			</select>
		<textarea rows="3" cols="10" id="pickup_address" disabled></textarea>
		</td>
		<td></td>
		
		</tr>
		<?php if($pkg_disabled==""){
			$status_disabled="";
			if($row_s["buyer_action"]=="Cancelled" || $delivery_status == "Dispatched" || $delivery_status == "Delivered"){
				$status_disabled="disabled";
			}	
		?>
		<tr>
		<td>
		Delivery Status <a href="#" data-toggle="popover" data-trigger="hover" data-html="true" data-content="When to change the status:<ul>
		<li>Ready To Ship: Select this status from the drop down when you have packed the ordered item and filled in the necessary details for the pickup of item.</li>
		<li>Dispatch: Select this option when the delivery boy pick-up the item from you for the delivery.</li>
		<li>If you provide you own logistics, then select the Dispatch option when your delivery boy is out for delivery.</li>
		</ul>"><span class="glyphicon glyphicon-question-sign help"></span></a>
		<input type="hidden" name="delivery_status_old" value="<?=$delivery_status?>">
		<td><select name="delivery_status" <?=$status_disabled?>>
			<?php if($delivery_status == "Delivered"){func_option("Delivered","Delivered",func_var($delivery_status));}else{func_option("Pending","Pending",func_var($delivery_status));}?>
			<?php func_option("Ready-to-Ship","Ready-to-Ship",func_var($delivery_status))?>
			<?php func_option("Dispatched","Dispatched",func_var($delivery_status))?>
		</select>
		</td></td>
		<td><input type="button" class="btn btn-warning" value=" Update " onclick="javascript: js_save();" <?=$status_disabled?>/></td>
		<td align="right"><input type="button" class="btn btn-warning" value=" Print Invoice " onclick="javascript: print_page('ord_info');"/></td>
		</tr>
	<?php }?>
	</table>
	
	<?php }?>

	<div id="ord_info">
	<?php for($i=1;$i<=3;$i++){?>
		<div class="order_info">
			<div align="right">
				<p style="padding-left:10px; text-align:right; padding-bottom: 5px;">Powered By :-</p>
					<img src="../images/logo-black.gif" alt="Company-Name" width="120px" height="40px" align="right" style="padding-bottom: 5px;"/>
			</div><br>
			<center><b style="font-size:18px;">Tax Invoice/Retail Invoice</b></center>
			<table width="100%"  border="1" align="center" class="checkout invoice">
				<tr>
					<td><strong>WayBill No.</strong></td><td><?=$row_s["way_billl_no"]?></td>
					<?php if($row_s["way_billl_no"] <> ""){
					$barcode_url = "../lib/barcode.php?codetype=Code39&size=40&text=".$row_s["way_billl_no"]; ?>
					<td><strong>Barcode</strong></td><td class="img-padding"><img src="<?=$barcode_url?>" height="30"/></td>
					<?php }else { ?>
						<td><strong>Barcode</strong></td><td class="img-padding"></td>
					<?php } ?>
				</tr>
				</tr>
				
				<tr>
					<td><strong>Order No.</strong></td><td><?=$row_s["ord_no"]?></td>

					<td><strong>Order Date.</strong></td><td><?=$row_s["ord_date"]?></td>
				</tr>
			</table><br>
			
			<table width="100%"  border="1" align="center" class="list">
			<tr>
			<td>
				<?php get_rst("select sup_ext_name,sup_ext_address,sup_ext_city,sup_ext_state,sup_ext_pincode from sup_ext_addr where sup_id=$sup_id",$row_sadd); ?>
				<p>Sold By-</p>
				<p class="p"><strong><?=$row_sadd["sup_ext_name"]?></strong></p> 
				<p class="p"><strong>Address :</strong><span align="left" class="table-bg2" style="padding-left:5px;"><?=$row_sadd["sup_ext_address"]?></span>
				<span align="left" class="table-bg2"><?=$row_sadd["sup_ext_city"]?></span>
				<span align="left" class="table-bg2"><?=$row_sadd["sup_ext_state"]?></span>,
				<span align="left" class="table-bg2">India </span> 
				<span align="left" class="table-bg2"><?=$row_sadd["sup_ext_pincode"]?></span></p>
				<p class="p"><strong>GST NO.:</strong><span align="left" class="table-bg2" style="padding-left:5px;"><?php echo substr($sub_vat,0,11);?></span></p>
				
			</td>
			</tr>
			</table><br>
			
			<table width="100%"  border="1" align="center" class="list">
			<tr>
			<td>
				<center><h4 class="h4">Shipping Details</h4></center>
			</td>
			</tr>
			<tr>
			<td>
			<?php if($row_m["ind_buyer"]==1){?>
			<p class="p"><strong>Name:</strong> <span align="left" class="table-bg2" style="padding-left:5px;"><?=$memb_company?></span><span style="padding-left:50px;">
			<strong>Contact No.:</strong><span align="left" class="table-bg2" style="padding-left:5px;"><?=$ship_tel?></span></p></span></span></p>
			<p class="p"><strong>Address :</strong><span align="left" class="table-bg2" style="padding-left:5px;"><?=$ship_add1?></span>
			<span align="left" class="table-bg2"><?=$ship_city?></span>
			<span align="left" class="table-bg2"><?=$ship_state?></span>,
			<span align="left" class="table-bg2"><?=$ship_country?></span> 
			<span align="left" class="table-bg2"><?=$ship_postcode?></span></p>
			<p class="p"><strong>GST NO.:</strong><span align="left" class="table-bg2" style="padding-left:5px;"><?php echo substr($memb_vat,0,11);?></span></p><?php }else{?>
			
			<p class="p"><strong>Name:</strong> <span align="left" class="table-bg2" style="padding-left:5px;"><?=$ship_name?></span><span style="padding-left:50px;">
			<strong>Contact No.:</strong><span align="left" class="table-bg2" style="padding-left:5px;"><?=$ship_tel?></span></p></span></span></p>
			<p class="p"><strong>Address :</strong><span align="left" class="table-bg2" style="padding-left:5px;"><?=$ship_add1?></span>
			<span align="left" class="table-bg2"><?=$ship_city?></span>
			<span align="left" class="table-bg2"><?=$ship_state?></span>,
			<span align="left" class="table-bg2"><?=$ship_country?></span> 
			<span align="left" class="table-bg2"><?=$ship_postcode?></span></p><?php }?>
			<br>
			</td>
			</tr>
			</table>
			
			<center><h3 class="h3">Item details</h3>
				
			<table width="100%"  border="1" align="center" class="list">
			<tr>
				<td width="10%" align="center" class="table-bg">HSN Code</td>
				<td width="28%" align="center" class="table-bg">Item Description</td>
				<td width="12%" align="center" class="table-bg">Price &nbsp;<span><div class="" style="display:inline-block;">&#8377; </div></span></td>
				<td width="8%" align="center" class="table-bg">Tax</td>
				<td width="11%" align="center" class="table-bg">Price after TAX &nbsp;<span><div class="" style="display:inline-block;">&#8377; </div></span></td>
				<td width="11%" align="center" class="table-bg">Shipping Charges &nbsp;<span><div class="" style="display:inline-block;">&#8377; </div></span></td>
				<td width="8%" align="center" class="table-bg">Qty</strong></td>
				<td width="12%" align="center" class="table-bg">Total&nbsp;<span><div class="" style="display:inline-block;">&#8377; </div></span></td>
			</tr>
			<?php	
			$Productinfo="Test Products";
			get_rst("select * from ord_items where cart_id='".$cart_id."' and sup_id=$sup_id",$row,$rst);

			do{
				$cart_price = $row["cart_price"];
				$cart_qty = $row["cart_qty"];
				$prod_id = $row["prod_id"];
				$cart_price_tax = $row["cart_price_tax"];
				get_rst("select hsn_code from prod_mast where prod_id=0$prod_id", $prow);
				?>
				<tr>
				<td rowspan="1" align="center" valign="middle" class="table-bg2"><p><?=$prow["hsn_code"]?></p></td>
				<td rowspan="1" align="center" valign="middle" class="table-bg2"><p><?=$row["item_stock_no"]." - ".$row["item_name"]?></p></td>
				<td rowspan="1" align="center" valign="middle" class="table-bg2"><?=formatNumber($row["cart_price"],2)?>&nbsp;</td>
				<td rowspan="1" align="center" valign="middle" class="table-bg2">
				<?php
				IF(floatval($row["tax_percent"])>0){
					echo($row["tax_percent"]."% ");
				}?>
				</td>
				<td valign="middle" align="center" class="table-bg2">
				<?=formatNumber($cart_price_tax,2)?>&nbsp;
				</td>
				<?php if($row["ship_amt"]==0){?>
					<td  align="center" style="color: #006633;"><b>Free</b></td><?php }else{?>
					<td  align="center"><?=$row["ship_amt"]?>&nbsp;</td>
				<?php }?>
				
				<td valign="middle" align="center" class="table-bg2">
					<?=$cart_qty?>
				</td>
				<td valign="middle" align="right" class="table-bg2"><strong><?=formatNumber(($cart_qty * $cart_price_tax)+ $row["ship_amt"],2)?></strong>&nbsp;&nbsp;</td>
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
			<td width="65%" align="right"><strong><p><span class="green-basket">Shipping Charges:</span></p></strong></td>
			<?php if($shipping_charges == 0){?>
			<td width="25%" align="right"> Free Delivery &nbsp;</td><?php }else{?>
			<td width="25%" align="right"><?=$v_shipping_charges?>&nbsp;</td>
			<?php }?>
			</tr>

			<tr>
			<td align="right"><strong><p><span class="green-basket">Tax :</span></strong></p></td>
			<td align="right">Rs.<?=FormatNumber($vat_value,2)?>&nbsp;</td>
			</tr>

			<tr>
			<td align="right"><strong><p><span class="green-basket">Total Price :</span></strong></p></td>
			<td align="right"><strong>Rs.<?=FormatNumber($ord_total,2)?></strong>&nbsp;</td>
			</tr>

			<tr>
			<td align="right"><strong><p><span class="green-basket">Total Price (Round-Off):</span></strong></p></td>
			<td align="right"><strong>Rs.<?=round($ord_total,0,PHP_ROUND_HALF_UP)?></strong>&nbsp;</td>
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
<script>
function validate_key(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /[0-9]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}  
</script>
</form>
<?php
require_once("inc_admin_footer.php");
?>
