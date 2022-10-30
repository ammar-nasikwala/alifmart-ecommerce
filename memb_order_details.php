<?php

require("inc_init.php");

$cart_id = func_read_qs("id");
if ($cart_id <> ""){
	$_SESSION["cart_id"] = $cart_id;
}else{
	$cart_id = $_SESSION["cart_id"];
}
$bt_bank_name="";
$bt_bank_branch="";
$bt_ref_no="";
$bt_date="";

$act = func_read_qs("act");
$sup_id = func_read_qs("sup_id");
$item_id = func_read_qs("item_id");
$order_no = func_read_qs("order_no");
$cc=" Alif Mart <".$_SESSION["admin_email"].">";
$ord_no = "";

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

	$bt_bank_name=$row["bt_bank_name"];
	$bt_bank_branch=$row["bt_bank_branch"];
	$bt_ref_no=$row["bt_ref_no"];
	$bt_date=$row["bt_date"];	
}

if($act<>""){
	$sql = "select ord_no from ord_summary where cart_id=$cart_id";
	if(get_rst($sql,$row)){
		$ord_no = $row["ord_no"];
	}
}


if($act=="bt"){

	$fld_arr = array();
	$fld_arr["bt_bank_name"] = func_read_qs("bt_bank_name");
	$fld_arr["bt_bank_branch"] = func_read_qs("bt_bank_branch");
	$fld_arr["bt_ref_no"] = func_read_qs("bt_ref_no");
	$fld_arr["bt_date"] = func_read_qs("bt_date");

	$sql = func_update_qry("ord_details",$fld_arr," where cart_id=$cart_id");
	execute_qry($sql);	
	get_rst("select user_name,user_email from user_mast where user_type='FM'",$row_a);
	get_rst("select memb_fname from member_mast where memb_id='".$_SESSION["memb_id"]."'",$row_b);
	$mail_b = "Dear ".$row_a["user_name"]."<br> ".$row_b["memb_fname"]." has updated the payment details, please do needful.";
	send_mail($row_a['user_email'],"Buyer- ".$row_b["memb_fname"]." Payment updates",$mail_b);
	$mail_ba = "Hello, <br> ".$row_b["memb_fname"]." has updated the payment details, please do needful.";
	send_mail($info,"Buyer- ".$row_b["memb_fname"]." Payment updates",$mail_ba);
}

if($act == "oc"){
	pay_by_payu_credit();
}

if($act=="a"){
	if(get_rst("select delivery_status from ord_summary where cart_id=$cart_id and delivery_status<>'Pending'")){
		js_alert("Sorry the order has just been dispatched hence cannot be cancelled.");
	}else{
		$sql = "update ord_summary set buyer_action='Cancelled',buyer_date=now() where cart_id=$cart_id";
		execute_qry($sql);
		
		$sql = "update ord_items set item_buyer_action='Cancelled',buyer_date=now() where cart_id=$cart_id";
		execute_qry($sql);
		$reason_c = func_read_qs("reason_c");
			$comment_c = func_read_qs("comment_c");
			mysqli_query($con,"INSERT INTO ord_cancel_reasons SET ord_no='".$ord_no."',cart_id='".$cart_id."',memb_id='".$_SESSION["memb_id"]."', reason = '".$reason_c."', comment = '".$comment_c."',complete_cancel=1");
		get_rst("select sup_id,sup_email,sup_contact_no from sup_mast where sup_id in (select sup_id from ord_summary where cart_id=$cart_id)",$row_s,$rst_s);
		do{
			$sup_id = $row_s["sup_id"];
			get_rst("select cart_item_id from ord_items where cart_id=$cart_id and sup_id=$sup_id and item_buyer_action='Cancelled'",$rw);
			get_rst("select ord_no from ord_summary where cart_id=$cart_id and sup_id=$sup_id",$rw_ord);
			update_ord_summary($cart_id,$rw["cart_item_id"],$sup_id,$rw_ord["ord_no"]);

			$mail_body = "Hi,<br> Order $ord_no has been cancelled by the buyer.";	
			get_rst("select user_email from user_mast where user_type='AM'",$row_u);
			send_mail($row_u['user_email'],"Company-Name - Order Cancelled (Account Manager): $ord_no",$mail_body);
			get_rst("select user_email from user_mast where user_type='FM'",$row_u);
			send_mail($row_u['user_email'],"Company-Name - Order Cancelled (Finance Manager): $ord_no",$mail_body);
			get_rst("select user_email from user_mast where user_type='LP'",$row_u);
			send_mail($row_u['user_email'],"Company-Name - Order Cancelled (Logistics Partner): $ord_no",$mail_body);

			$mail_body = "Dear Seller,<br> Order $ord_no has been cancelled by the buyer.";
			$sms_body = "Dear Seller, Order $ord_no has been cancelled by the buyer.";
			send_mail($row_s["sup_email"],"Company-Name - Order Cancelled (Seller): $ord_no",$mail_body,"",$cc);
			send_sms($row_s["sup_contact_no"],$sms_body);
		}while($row_s = mysqli_fetch_assoc($rst_s));
		
		$mail_body = "Dear $bill_name,<br> Your order $ord_no has been cancelled successfully.";
		$sms_body = "Dear $bill_name, Your order $ord_no has been cancelled successfully.";
		send_mail($bill_email,"Company-Name - Order Cancelled: $ord_no",$mail_body,"",$cc);
		send_sms($bill_tel,$sms_body);
		
		js_alert("Your entire Order has been cancelled successfully.");

	}
}

$v_action="";
if($act=="c" or $act=="r" or $act=="j"){

	if($act=="c" AND get_rst("select delivery_status from ord_summary where cart_id=$cart_id and sup_id=$sup_id and delivery_status<>'Pending'")){
		js_alert("Sorry the order has just been dispatched hence cannot be cancelled.");
	}else{
		switch($act){
			case "c":
				$v_action="Cancelled";
				break;
			case "r":
				$v_action="Returned";
				break;
			case "j":
				$v_action="Rejected";
				break;
		}
		$sql = "update ord_items set item_buyer_action='$v_action',buyer_date=now() where cart_id=$cart_id and cart_item_id = $item_id";
		execute_qry($sql);
		
		update_ord_summary($cart_id,$item_id,$sup_id,$order_no);
		
		if($act=="c"){
			$reason = func_read_qs("reason");
			$comment = func_read_qs("comment");
			mysqli_query($con,"INSERT INTO ord_cancel_reasons SET ord_no='".$order_no."',cart_id='".$cart_id."',memb_id='".$_SESSION["memb_id"]."', reason = '".$reason."', comment = '".$comment."'");
		}
		else if($act=="r"){

			$reason_r =func_read_qs("reason_r");
			$comment_r = func_read_qs("comment_r");
			mysqli_query($con,"INSERT INTO ord_cancel_reasons SET ord_no='".$ord_no."',cart_id='".$cart_id."',memb_id='".$_SESSION["memb_id"]."', reason = '".$reason_r."', comment = '".$comment_r."'");
		}
		else if($act=="j"){

			$reason_j =func_read_qs("reason_j");
			$comment_j = func_read_qs("comment_j");
			mysqli_query($con,"INSERT INTO ord_cancel_reasons SET ord_no='".$order_no."',cart_id='".$cart_id."',memb_id='".$_SESSION["memb_id"]."', reason = '".$reason_j."', comment = '".$comment_j."'");
		}
		get_rst("select sup_email,sup_contact_no from sup_mast where sup_id = $sup_id",$row_s,$rst_s);
		//do{
			$mail_body = "Dear Seller,<br>A Product in Order $ord_no has been $v_action by the buyer";
			$sms_body = "Dear Seller, A Product in Order $ord_no has been $v_action by the buyer";
			send_mail($row_s["sup_email"],"Company-Name - Order $v_action (Seller): $ord_no",$mail_body,"",$cc);
			send_sms($row_s["sup_contact_no"],$sms_body);
		//}while($row_s = mysql_fetch_assoc($rst_s));
		
		$mail_body = "Dear $bill_name, <br>A product in your order $ord_no has been $v_action.";
		$sms_body = "Dear $bill_name, A product in your order $ord_no has been $v_action.";
		send_mail($bill_email,"Company-Name - Order $v_action: $ord_no",$mail_body,"",$cc);
		send_sms($bill_tel,$sms_body);	
		
		js_alert("Selected product has been $v_action.");
	}
}


$sql_where = " and user_id=".$_SESSION["memb_id"];	//." and user_type='".$_SESSION["user_type"]."'";

$sql = "select * from ord_summary where cart_id=".$_SESSION["cart_id"]."$sql_where";
if(get_rst($sql,$row_s,$rst_s)){
	//$cart_id = $row_s["cart_id"];
}else{
	//header('location: memb_view_orders.php');
	js_alert("Sorry! Unable to display the order with id $cart_id");
	js_redirect("memb_view_orders.php");
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>Company-Name - Order Details</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="DESCRIPTION" content="<?=$cms_meta_key?>"/>
<meta name="KEYWORDS" content="<?=$cms_meta_desc?>"/>
<link href="styles/styles.css" rel="stylesheet" type="text/css" />
<script src="scripts/scripts.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="lib/jsDatePick/jsDatePick_ltr.min.css" />
<script type="text/javascript" src="lib/jsDatePick/jsDatePick.min.1.3.js"></script>

<script type="text/javascript">

function js_continue(){
	window.location.href="prod_list.php";
}

function js_checkout(){
	window.location.href="checkout.php";
}

function js_home(){
	window.location.href = "home.php"
}

function js_print(){
	//window.location.href = "payment.php"
	//document.frmCheckout.act.value="next";
	//document.frmCheckout.submit();
	window.open("ord_confirmation.php?id=<?=$cart_id?>&print=1");
}

function js_cancel(v_act,v_sup_id,v_item_id,v_ordno){
	var v_action = "Cancel";
	if(v_act=="a") v_action = "Cancel the Entire Order";
	if(v_act=="c") v_action = "Cancel this product";
	if(v_act=="r") v_action = "Return this product";
	if(v_act=="j") v_action = "Reject this product";
	
	if(confirm("Are you sure you wish to " + v_action + "?")){
		document.frm_BT.act.value=v_act
		document.frm_BT.sup_id.value=v_sup_id
		document.frm_BT.item_id.value=v_item_id
		document.frm_BT.order_no.value = v_ordno	
	}else{
		return false;
	}

}
function js_cancel_c(v_act,v_sup_id,v_item_id){
	var v_action = "Cancel";
	if(v_act=="a") v_action = "Cancel the Entire Order";
	if(confirm("Are you sure you wish to " + v_action + "?")){
		document.frm_BT.act.value=v_act
		document.frm_BT.sup_id.value=v_sup_id
		document.frm_BT.item_id.value=v_item_id
	}else{
		return false;
	}

}
function submit_cancel(){
	if(document.getElementById("comment").value==""&& document.getElementById("comment_r").value=="" && document.getElementById("comment_j").value==""&&document.getElementById("comment_c").value==""){
		alert("Please provide comment for your action");
		return false;
	}
	display_gif();
	document.frm_BT.submit();
}
window.onload = function(){
	new JsDatePick({
		useMode:2,
		target:"bt_date",
		dateFormat:"%Y-%m-%d",
		cellColorScheme:"beige"
	});
};
function upd_bt_details(){
	if(document.getElementById("bt_name").value=="" || document.getElementById("bt_branch").value=="" ||document.getElementById("bt_chq").value=="" ||document.getElementById("bt_date").value==""){
		alert("Enter complete bank details");
	} else{
		alert("Your bank transfer details are saved successfully");
	}
	
}

function pay_by_payu(){
	//document.getElementsByName("act")[0].setAttribute("value", "oc");
	//document.frm_BT.act.value='oc';
	document.frm_BT.submit();
}
</script>


</head>
<body>
<?php

require("header.php");

$incomp_msg="";
$ord_cancelled="";
?>
<div id = "gif_show" style="display:none"></div>
<div id="content_hide">
<div id="contentwrapper">
<div id="contentcolumn">
<div class="center-panel">
	<div class="you-are-here">
		<p align="left">
			YOU ARE HERE:<a href="index.php" title="">Home</a> | <span class="you-are-normal">Order Details</span>
		</p>
	</div>
	
	<table width="100%"  border="0" align="center" class="checkout" >

		<?php if($row_s["buyer_action"]=="Cancelled"){
			$ord_cancelled="1";
			?>
			<tr style="background-color:#FF0000;">
				<td style="background-color:#FF0000;" align="center" colspan="10"><font color='white'>This is a cancelled Order</font></td>
			</tr>
		<?php }?>
		
	</table>
	
		<form name="frm_BT" action="memb_order_details.php" method="post">
			<input type="hidden" name="id" value="<?=$cart_id?>">
			<?php  if($row_s["pay_method"]=="OC") { ?> 
			<input type="hidden" name="act" value="bt">
			<?php } ?>
			<input type="hidden" name="sup_id" value="">
			<input type="hidden" name="item_id" value="">
			<input type="hidden" name="order_no" value="">
			<?php if($row_s["pay_method"]=="BT" || $row_s["pay_method"]=="OC"){
				if($row_s["buyer_action"]=="Cancelled"){?>
			
			<table width="100%"  border="0" align="center" class="checkout" >
				<tr>
				<td align="left" colspan="10" class="table-bg3"><p>Bank Transfer Details</p></td>
				</tr>
				<tr>
					<td width="165" align="right">Bank Name</td>
					<td align="left"><input type="text" disabled class="" name="bt_bank_name" tabindex="1" value="<?=$bt_bank_name?>"></td>
					
					<td  align="right">Branch</td>
					<td align="left"><input type="text" disabled tabindex="2" name="bt_bank_branch" value="<?=$bt_bank_branch?>"></td>

					<td rowspan="2"><input type="submit" disabled tabindex="5" value=" Update "></td>

				</tr>
				<tr>
					<td width="165" align="right">Cheque/Ref No.</td>
					<td align="left"><input type="text" disabled tabindex="3" name="bt_ref_no" value="<?=$bt_ref_no?>"></td>
					
					<td  align="right">Date</td>
					<td align="left"><input type="text" disabled style="width:141px; height:21px;" tabindex="4" readonly id="bt_date" name="bt_date" value="<?=formatDate($bt_date)?>"></td>
				</tr>
				<tr>
				</tr>
				
			</table>
			<?php }else{?>	
			<table width="100%"  border="0" align="center" class="checkout" >
				<tr>
				<td align="left" colspan="10" class="table-bg3"><p>Bank Transfer Details</p></td>
				</tr>
				<tr>
					<td width="165" align="right">Bank Name</td>
					<td align="left"><input type="text" class="" id="bt_name" name="bt_bank_name" tabindex="1" value="<?=$bt_bank_name?>"></td>
					
					<td  align="right">Branch</td>
					<td align="left"><input type="text" tabindex="2" id="bt_branch" name="bt_bank_branch" value="<?=$bt_bank_branch?>"></td>

					<td rowspan="2"><input type="submit" id="upd" tabindex="5" class="btn btn-warning" value=" Update " onclick="javascript: upd_bt_details();"></td>

				</tr>
				<tr>
					<td width="165" align="right">Cheque/Ref No.</td>
					<td align="left"><input type="text" id="bt_chq" tabindex="3" name="bt_ref_no" value="<?=$bt_ref_no?>"></td>
					
					<td  align="right">Date</td>
					<td align="left"><input type="text" style="height:auto;" tabindex="4" id="bt_date" name="bt_date" value="<?=formatDate($bt_date)?>"></td>
				</tr>
				<tr>
				</tr>
				
			</table>
			<table width="100%"  border="0" align="center" class="checkout" >
				<tr>
				<td align="left" colspan="10" class="table-bg3"><p>Company-Name Bank Details</p></td>
				</tr>
				<tr>
					<td width="165" >Bank Name :</td>
					<td align="left">IDBI Bank</td>
					
					<td >Branch :</td>
					<td align="left">Telco Road, Bhosari, Pune</td>
				</tr>
				<tr>
					<td >Account No. :</td>
					<td align="left">1678102000005470</td>
					
					<td width="165">IFSC Code :</td>
					<td align="left">IBKL0001678</td>
				</tr>
				<tr>
				</tr>
			</table>

			<?php  if($row_s["pay_method"]=="OC") { ?>
			<input type="hidden" name="act" value="oc">
			<input type="hidden" name="id" value="<?=$cart_id?>">
				<input type="hidden" name="firstname" value="<?=$bill_name?>">
				<input type="hidden" name="email" value="<?=$bill_email?>">
				<input type="hidden" name="phone" value="<?=$bill_tel?>">
				<input type="hidden" name="address1" value="<?=$bill_add1?>">
				<input type="hidden" name="city" value="<?=$bill_city?>">
				<input type="hidden" name="state" value="<?=$bill_state?>">
				<input type="hidden" name="country" value="<?=$bill_country?>">
				<input type="hidden" name="zipcode" value="<?=$bill_postcode?>">
				<input type="hidden" name="udf1" value="<?=$_SESSION["cart_id"]?>">
			<table width="100%"  border="0" align="center" class="checkout" >
				<tr>
					<?php if($row_s["pay_status"] != "Paid") {?>
						<td colspan="2" style="color:#f0ad4e"><strong>Your payment is due on <?php echo Date('jS F, Y', strtotime("+30 days", strtotime($row_s["ord_date"]))); ?></strong></td>
					<?php } ?>
				</tr>
				<tr>
					<td><strong>Pay Using Payment Gateway</strong></td>
					<td><button class="btn btn-warning" onclick="javascript: pay_by_payu();">Pay with PayUMoney</button></td>
				</tr>
			</table>
			<?php }
			}
		} ?>
		<table width="100%"  border="0" align="center" class="checkout" >
	
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

	<?php if($ord_instruct.""<>""){?>
		<table width="100%"  border="0" align="center" class="checkout" >
		<tr>
		<td width="175" align="left" class="table-bg2"><p>Additional Instructions</p></td>
		<td align="left"  class="table-bg2"><?=$ord_instruct?>
		</td>
		</tr>
		</table>
	<?php }?>
	
	<table width="100%"  border="0" align="center" class="checkout" >
	<tr>
	<td align="left" class="table-bg3"><p>Mode of Payment</p></td>
	</tr>
	<tr>
	<td align="left" class="table-bg2"><p>
	<?=$row_s["pay_method_name"]?></p></td>
	</tr>
	</table>
	
	<?php if($row_s["po_no"] <> ""){?>
	<table width="100%"  border="0" align="center" class="checkout" >
	<tr>
	<td align="left" class="table-bg3"><p>Purchase Order Number</p></td>
	</tr>
	<tr>
	<td align="left" class="table-bg2"><p>
	<?=$row_s["po_no"]?></p></td>
	</tr>
	</table>
	<?php }?>
	
	<h1>Cart</h1>
	
	<?php if($incomp_msg <> ""){?>
			<hr color="#FF0000" size="1px" width="91%" align="center" />
			<p> <span class="red">	<?php echo $incomp_msg;?> </span> </p>		
			<hr color="#FF0000" size="1px" width="91%" align="center" />
		<?php }?>
	</div>
	<div class="center-panel">
	<?php
	$ord_pay_total = 0;
	$ord_tax_total = 0;
	$all_pending = "1";
	do{
		$sup_id = $row_s["sup_id"];
		$gst_cap = 0;
		get_rst("select sup_ext_state from sup_ext_addr where sup_id=$sup_id",$row_sadd); 
		get_rst("select sup_vat,sup_company from sup_mast where sup_id=$sup_id",$row_st);
		if($row_sadd["sup_ext_state"] == $bill_state){
			$gst_cap = 1; // 0 for inter-state GST, 1 for intra-state GST
		}
	?>

	<table width="100%"  border="0" align="center" class="checkout">
	<tr >
		<td align="center" colspan="5" class="table-bg2"><b>Order Number : &nbsp; </b><?=$row_s["ord_no"]?></td>
		<td align="center" colspan="5" class="table-bg2"><b>Order Date : &nbsp;</b><?=formatDate($row_s["ord_date"])?></td>
	</tr>
	<tr >
		<td align="center" colspan="5" class="table-bg2"><b>Supplier : &nbsp;</b> <?=$row_st["sup_company"]?></td>
		<td align="center" colspan="5" class="table-bg2"><b>GST Number : &nbsp;</b><?=$row_st["sup_vat"]?></td>
	</tr>
	<tr>
		<td  width="8%" align="center" class="table-bg"><span class="theme-blue">Image</td>
		<td  width="9%" align="center" class="table-bg"><span class="theme-blue">HSN Code</td>
		<td width="20%" align="center" class="table-bg"><span class="theme-blue">Item Description</td>
		<td width="10%" align="center" class="table-bg"><span class="theme-blue">Unit Price</td>
		<td width="7%" align="center" class="table-bg"><span class="theme-blue">GST</td>
		<td width="10%" align="center" class="table-bg"><span class="theme-blue">Price after Tax</td>
		<td width="10%" align="center" class="table-bg"><span class="theme-blue">Shipping</td>
		<td width="7%" align="center" class="table-bg"><span class="theme-blue">Qty</span></td>
		<td width="10%" align="center" class="table-bg"><span class="theme-blue">Total</td>
		<td width="12%" align="center" class="table-bg"><p><strong>Action</strong></p></td>
	</tr>
	<?php
	$Productinfo="Test Products";
	
	get_rst("select * from ord_items where cart_id=$cart_id and sup_id=$sup_id",$row,$rst);
	do{
		$cart_price = $row["cart_price"];
		$cart_qty = $row["cart_qty"];
		$prod_id = $row["prod_id"];
		$cart_price_tax = $row["cart_price_tax"];
		get_rst("select prod_name, hsn_code from prod_mast where prod_id=$prod_id",$rw_name);		
		$item_color = "";
		if($row["item_buyer_action"].""<>""){
			$item_color = "#d9534f";
		}
		?>
		<tr style="background-color:<?=$item_color?>;">
		<td rowspan="1" class="table-bg2"><a href="<?=get_prod_url($prod_id,"",$rw_name["prod_name"])?>"><img src="<?=show_img($row["item_thumb"])?>" width="51" height="43" border="0" alt="<?=$row["item_name"]?>" /></a></td>
		<td rowspan="1" align="center" valign="middle" class="table-bg2"><p><?=$rw_name["hsn_code"]?></p></td>
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
			<td  align="center"> <?=$row["ship_amt"]?>&nbsp;</td>
			<?php }?>
			
		<td valign="middle" align="center" class="table-bg2">
			<?=$cart_qty?>
		</td>
		<td valign="middle" align="center" class="table-bg2"><strong><?=formatNumber(($cart_qty * $cart_price_tax)+$row["ship_amt"],2)?></strong>&nbsp;&nbsp;</td>
		
		<td align="center">
		<?php
		//if($ord_cancelled==""){
		if($row["item_buyer_action"].""==""){
		switch($row_s["delivery_status"]){
			case "Pending":
				?>
				<input type="button" class="btn btn-warning" value=" Cancel " data-toggle="modal" data-target="#mymodal-cancel" onclick="javascript: js_cancel('c','<?=$row["sup_id"]?>','<?=$row["cart_item_id"]?>','<?=$row_s["ord_no"]?>');"/>
				<?php 
				break;
			case "Dispatched":
				?>
				<input type="button" class="btn btn-warning" value=" Return " data-toggle="modal" data-target="#mymodal-return" onclick="javascript: js_cancel('r','<?=$row["sup_id"]?>','<?=$row["cart_item_id"]?>','<?=$row_s["ord_no"]?>');"/>
				<?php
				break;
			case "Delivered":
				$cur_date = Date("m/d/Y");
				$delv_date = date_create($row_s["delivery_date"]);
				$delv_date_f = date_format($delv_date, "m/d/Y");
				$date_difference = round(abs(strtotime($cur_date) - strtotime($delv_date_f))/86400);
				if($date_difference <= 7){
				?>
				<input type="button" class="btn btn-warning" value=" Reject " data-toggle="modal" data-target="#mymodal-reject" onclick="javascript: js_cancel('j','<?=$row["sup_id"]?>','<?=$row["cart_item_id"]?>','<?=$row_s["ord_no"]?>');"/>
				<?php }
				break;
			}
		}else{
			echo("<b>".$row["item_buyer_action"]."</b>");
		}
		?>
		</td>
		
		</tr>
		<?php
	}while($row = mysqli_fetch_assoc($rst));
	
	?>	
	</table>
	<?php if($row_s["item_total"]<>0){?>
	<table width="100%"  border="0" class="checkout">
	<?php		
	$item_total = $row_s["item_total"];
	$shipping_charges = $row_s["shipping_charges"]."";
	$ord_total = $row_s["ord_total"];
	$vat_percent = $row_s["vat_percent"];
	$vat_value = $row_s["vat_value"];
	$ord_pay_total = $ord_pay_total + $ord_total;
	$ord_tax_total = $ord_tax_total + $vat_value;

	if($shipping_charges.""==""){
		$v_shipping_charges = "As per the delivery area";
	}else{
		$v_shipping_charges = formatNumber($shipping_charges,2);
	}
	?>

	<tr>
	<td align="right"><p><span class="green-basket">Sub-Total (exc. Tax):</span></p></td>
	<td align="right"> <?=FormatNumber($item_total,2)?>&nbsp;</td>
	
	</tr>	
	<tr>
	<td width="65%" align="right"><p><span class="green-basket">Shipping:</span></p></td>
	<?php if($v_shipping_charges==0){?>
	<td width="25%" align="right"> Free Delivery &nbsp;</td><?php }else{?>
	<td width="25%" align="right"> <?=$v_shipping_charges?>&nbsp;</td>
	<?php }?>
	</tr>

	<?php if($gst_cap == 0){?> <!-- Inter-State GST -->
		<tr>
		<td align="right"><p><span class="green-basket">Total IGST :</span></p></td>
		<td align="right"> <?=FormatNumber($vat_value,2)?>&nbsp;</td>
		</tr>
	<?php }else{ ?> <!-- Intra-State GST -->
		<tr>
		<td align="right"><p><span class="green-basket">Total CGST :</span></p></td>
		<td align="right"> <?=FormatNumber($vat_value/2,2)?>&nbsp;</td>
		</tr>
		<tr>
		<td align="right"><p><span class="green-basket">Total SGST :</span></p></td>
		<td align="right"> <?=FormatNumber($vat_value/2,2)?>&nbsp;</td>
		</tr>		
	<?php } ?>
	
	<tr>
	<td align="right"><p><span class="green-basket">Total Price (incl. TAX):</span></p></td>
	<td align="right"><strong> <?=FormatNumber($ord_total,2)?></strong>&nbsp;</td>
	</tr>

	<tr>
	<td align="right"><p><span class="green-basket">Total Price (Round-Off):</span></p></td>
	<td align="right"><strong> <?=round($ord_total,0,PHP_ROUND_HALF_UP)?></strong>&nbsp;</td>
	<input type="hidden" name="amount" value="<?=round($ord_total,0,PHP_ROUND_HALF_UP)?>">
	</tr>
	</table>
	<?php }?>
	<table width="100%"  border="0" align="center" xclass="checkout" >
	<tr>
	<th align="left" colspan="10" class="table-bg3"><p>Delivery Status</p></th>
	</tr>
	<?php if($ord_cancelled==""){?>
	<tr>
		<td align="center" class="del_comp"><span class="del_comp">Initiated</td>
		<?php
		$del_class = "del_pend";$to_be="to be";
		if($row_s["delivery_status"]=="Dispatched"){
			$del_class = "del_comp";$to_be="";
		}
		?>
		<td align="center" class="<?=$del_class?>"><span class="<?=$del_class?>"><?=$to_be?> Dispatched</td>
		<?php
		$del_class = "del_pend";$to_be="to be";
		if($row_s["delivery_status"]=="Delivered"){
			$del_class = "del_comp";$to_be="";
		}
		?>
		<td align="center" class="del_pend"><span class="<?=$del_class?>"><?=$to_be?> Delivered</td>
	</tr>
	<?php }else{?>
		<tr>
			<td align="center" colspan="3" class="del_pend"><span class="del_pend">Cancelled</td>		
	<?php }?>
	
	</table>

	<?php
		if(strtolower($row_s["delivery_status"])<>"pending"){
			$all_pending = "";
		}
	}while($row_s = mysqli_fetch_assoc($rst_s));
	?>
	
	<table width="100%"  border="0" class="list" >
	<?php 
	$coupon_amt = 0;
	$coupon_tax = 0;
	get_rst("select sum(cancel_coupon) as cancel_coupon,sum(cancel_amount) as cancel_amount from track_refund where cart_id=$cart_id",$ro_c);
	if(get_rst("select coupon_id,coupon_amt from ord_coupon where cart_id=$cart_id", $row_c)){
		get_rst("select coupon_code from offer_coupon where coupon_id=".$row_c["coupon_id"], $row_oc);
		if($ro_c["cancel_coupon"] <> ""){
			$coupon_amt = $row_c["coupon_amt"] - $ro_c["cancel_coupon"];
		?>
		<tr>
			<td align="right" class="table-bg"><p><span class="green-basket">Coupon Applied (<?=$row_oc["coupon_code"];?>):</span></p></td>
			<td align="right" width="100px" class="table-bg">
				<strong><div class="rupee-sign">&#8377; </div> <span><?=FormatNumber($coupon_amt,2)?></span></strong>&nbsp;
			</td>
		</tr>		
	<?php }
	else{
		$coupon_amt = $row_c["coupon_amt"];?>
		<tr>
			<td align="right" class="table-bg"><p><span class="green-basket">Coupon Applied (<?=$row_oc["coupon_code"];?>):</span></p></td>
			<td align="right" width="100px" class="table-bg">
				<strong><div class="rupee-sign">&#8377; </div> <span><?=FormatNumber($coupon_amt,2)?></span></strong>&nbsp;
			</td>
		</tr>	
	<?php }
	$coupon_tax = $coupon_amt * ($ord_tax_total/$item_total);
	}?>
	
	<?php
		get_rst("select sum((cart_price_tax * cart_qty) + ship_amt) as ord_total from ord_items where cart_id=$cart_id",$row_t);
		if($ro_c["cancel_amount"] <> ""){
			$total_amount = $row_t["ord_total"] - ($ro_c["cancel_amount"] + $ro_c["cancel_coupon"]);
			$total_amount = $total_amount - ($coupon_amt + $coupon_tax);
		?>
		<tr class="border_bottom">
		<td align="right" class="table-bg"><p><span class="green-basket">Total Tax:</span></p></td>
		<td align="right" width="100px" class="table-bg"><strong><div class="rupee-sign">&#8377; </div> <span><?=FormatNumber($ord_tax_total - $coupon_tax,2)?></span></strong>&nbsp;</td>
		</tr>

		<tr class="border_bottom">
		<td align="right" class="table-bg"><p><span class="green-basket">Total Order Amount:</span></p></td>
		<td align="right" width="100px" class="table-bg"><strong><div class="rupee-sign">&#8377; </div> <span><?=FormatNumber($total_amount,2)?></span></strong>&nbsp;</td>
		</tr>

				<tr class="border_bottom">
		<td align="right" class="table-bg"><p><span class="green-basket">Total Order Amount (Round-Off):</span></p></td>
		<td align="right" width="100px" class="table-bg"><strong><div class="rupee-sign">&#8377; </div> <span><?=round($total_amount,0,PHP_ROUND_HALF_UP)?></span></strong>&nbsp;</td>
		<input type="hidden" name="amount" value="<?=round($total_amount,0,PHP_ROUND_HALF_UP)?>">
		</tr>
		<?php }else{
			$total_amount = $row_t["ord_total"] - ($coupon_amt + $coupon_tax);
		?>
		<tr class="border_bottom">
		<td align="right" class="table-bg"><p><span class="green-basket">Total Tax:</span></p></td>
		<td align="right" width="100px" class="table-bg"><strong><div class="rupee-sign">&#8377; </div> <span><?=FormatNumber($ord_tax_total - $coupon_tax,2)?></span></strong>&nbsp;</td>
		</tr>
		<tr class="border_bottom">
		<td align="right" class="table-bg"><p><span class="green-basket">Total Order Amount:</span></p></td>
		<td align="right" width="100px" class="table-bg"><strong><div class="rupee-sign">&#8377; </div> <span><?=FormatNumber($total_amount,2)?></span></strong>&nbsp;</td>
		</tr>

		<tr class="border_bottom">
		<td align="right" class="table-bg"><p><span class="green-basket">Total Order Amount (Round-Off):</span></p></td>
		<td align="right" width="100px" class="table-bg"><strong><div class="rupee-sign">&#8377; </div> <span><?=round($total_amount,0,PHP_ROUND_HALF_UP)?></span></strong>&nbsp;</td>
		<input type="hidden" name="amount" value="<?=round($total_amount,0,PHP_ROUND_HALF_UP)?>">
		</tr>
	<?php }?>
	</tr>
	
	<tr >
	<?php
		
		if(get_rst("select * from track_refund where cart_id=$cart_id and pay_status='Paid'",$ro)){
		get_rst("select buyer_action from ord_summary where cart_id=$cart_id and sup_id=$sup_id",$rtn);
		if($rtn["buyer_action"]=='Cancelled'){
			if($ro_c["cancel_amount"] <> ""){
				$total_amount = $row_t["ord_total"] - ($ro_c["cancel_amount"] + $ro_c["cancel_coupon"]);?>
		<td align="right" class="table-bg"><p><span class="green-basket">Cancelled Product Amount:</span></p></td>
		<td align="right" width="100px" class="table-bg"><strong><div class="rupee-sign">&#8377; </div> <span><?=FormatNumber($total_amount,2)?></span></strong>&nbsp;</td>
		<?php }else{
			$total_amount = $row_t["ord_total"];
		?>
		<td align="right" class="table-bg"><p><span class="green-basket">Cancelled Product Amount:</span></p></td>
		<td align="right" width="100px" class="table-bg"><strong><div class="rupee-sign">&#8377; </div> <span><?=FormatNumber($total_amount,2)?></span></strong>&nbsp;</td>
		<?php }?>
		</tr>
		
		<tr>
		<td align="right" class="table-bg"><p><span class="green-basket">Coupon Deductables:</span></p></td>
		<td align="right" width="100px" class="table-bg"> - 
		<?php get_rst("select sum(coupon_deduct) as coupon_deduct from track_refund where cart_id=$cart_id",$r);?>		
				<strong><div class="rupee-sign">&#8377; </div> <span><?=FormatNumber($r["coupon_deduct"],2)?></span></strong>&nbsp;
		</td>

	</tr>
	<tr>
		<td align="right" class="table-bg"><p><span class="green-basket">Total Refund Amount:</span></p></td>
			<?php 
				get_rst("select sum(refund_amt) as refund_amount from track_refund where cart_id=$cart_id",$row_ref);
			?>
		<td align="right" width="100px" class="table-bg"><strong><div class="rupee-sign">&#8377; </div> <span><?=FormatNumber($row_ref["refund_amount"],2)?></span></strong>&nbsp;</td>

	</tr>
		<?php }else{?>
		<td align="right" class="table-bg"><p><span class="green-basket">Cancelled Product Amount:</span></p></td>
		<?php
			get_rst("select sum((cart_price_tax *cart_qty) + ship_amt) as total from ord_items where cart_id=$cart_id and (item_buyer_action='Cancelled' or item_buyer_action='Returned')",$row_t);
			if($ro_c["cancel_amount"] <> ""){
				$total_amt = $row_t["total"] - ($ro_c["cancel_amount"] + $ro_c["cancel_coupon"]);
		?>
		<td align="right" width="100px" class="table-bg"><strong><div class="rupee-sign">&#8377; </div> <span><?=FormatNumber($total_amt,2)?></span></strong>&nbsp;</td>
			<?php }else{
				$total_amt = $row_t["total"];
			?>
		<td align="right" width="100px" class="table-bg"><strong><div class="rupee-sign">&#8377; </div> <span><?=FormatNumber($total_amt,2)?></span></strong>&nbsp;</td>
			<?php }?>
		</tr>
		
		<tr>
		<td align="right" class="table-bg"><p><span class="green-basket">Coupon Deductables:</span></p></td>
		<td align="right" width="100px" class="table-bg"> - 
		<?php get_rst("select sum(coupon_deduct) as coupon_deduct from track_refund where cart_id=$cart_id",$r);?>
			<strong><div class="rupee-sign">&#8377; </div> <span><?=FormatNumber($r["coupon_deduct"],2)?></span></strong>&nbsp;
		</td>
	</tr>
	<tr>
		<td align="right" class="table-bg"><p><span class="green-basket">Total Refund Amount:</span></p></td>
			<?php 
				get_rst("select sum(refund_amt) as refund_amount from track_refund where cart_id=$cart_id",$row_ref);
			?>
		<td align="right" width="100px" class="table-bg"><strong><div class="rupee-sign">&#8377; </div> <span><?=FormatNumber($row_ref["refund_amount"],2)?></span></strong>&nbsp;</td>

	</tr>
	<?php }
		}?>
	</table>
	
	<center>
	<table width="90%"  border="0" align="center" xclass="list">
	<tr>
	<?php
		if($ord_cancelled==""){
		if($all_pending=="1"){
			get_rst("select item_total from ord_summary where cart_id=$cart_id",$r,$s);
				if($r["item_total"] <> 0){
			?>
			<td align="left"><input type="button" class="btn btn-warning" value=" Cancel Complete Order " data-toggle="modal" data-target="#mymodal-cancel_c" onclick="javascript: js_cancel_c('a','','');"/></a></td>
			<?php			
			}
		}
		?>

		<td colspan="10" align="center"><input type="button" class="btn btn-warning" value=" Print " xtarget="_blank" xhref="memb_order_print.php?id=<?=$cart_id?>" onclick="javascript: js_print();"/></td>
	<?php }?>
	</tr>
	</table>
	</center>
<div id="mymodal-cancel" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width:700px;">
    <!-- Modal content-->
		<div class="modal-content" style="height:auto">
			 <div class="modal-body" style=" overflow=scroll;">
				<h1 class="modal-heading" style="color:#f0ad4e !important;">Request Cancellation<img src="images/close_header.gif" width="12px" height="12px" alt="close" align="right" data-dismiss="modal"></h1>
				<table width="100%"  border="0" align="center">
				<tr>
				<td><div align="left">
				<p>Reason for cancellation :</p>
				</div></td>
				<td align="left">
					<select name="reason">									
						<option value="Order placed by mistake">Order placed by mistake</option>		
						<option value="Expected delivery time too long">Expected delivery time too long</option>	
						<option value="Delivery delayed">Delivery delayed</option>
						<option value="Bought from somewhere else">Bought from somewhere else</option>
						<option value="My reason not listed">My reason not listed</option>
					</select>*
				</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td align="left"><div align="left">
					<p>Comment:</p>
					</div>
					</td>
					<td align="left"><textarea rows="10" class="textboxwidth" cols="50" name="comment" id="comment" style="border: 1px solid;" maxlength="200"></textarea> (Max character 200)
					</td>
				</tr>
				<tr>
				<td></td>
				<td align="right"><input type="submit" class="open-cancel btn btn-warning" value=" Confirm Cancellation" name="cnfrm_cancel" id="cnfrm_cancel" onclick="javascript: return submit_cancel();"/>
			</tr>	
			</table>
			</div>
		</div>
	</div>
</div>

<div id="mymodal-return" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width:700px;">
    <!-- Modal content-->
		<div class="modal-content" style="height:auto">
			 <div class="modal-body" style=" overflow=scroll;">
				<h1 class="modal-heading" style="color:#f0ad4e !important;">Request Return<img src="images/close_header.gif" width="12px" height="12px" alt="close" align="right" data-dismiss="modal"></h1>
				<table width="100%"  border="0" align="center">
				<tr>
				<td><div align="left">
				<p>Reason for return :</p>
				</div></td>
				<td align="left">
					<select name="reason_r">									
						<option value="Incorrect product or size ordered">Incorrect product or size ordered</option>		
						<option value="Product is no longer needed">Product is no longer needed</option>	
						<option value="Product did not match its description on website or in catalog">Product did not match its description on website or in catalog</option>
						<option value="Company shipped wrrong product/size">Company shipped wrrong product/size</option>
						<option value="My reason not listed">My reason not listed</option>
					</select>*
				</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td align="left"><div align="left">
					<p>Comment:</p>
					</div>
					</td>
					<td align="left"><textarea rows="10" class="textboxwidth" cols="50" name="comment_r" id="comment_r" style="border: 1px solid;" maxlength="200"></textarea> (Max character 200)
					</td>
				</tr>
				<tr>
				<td></td>
				<td align="right"><input type="submit" class="open-cancel btn btn-warning" value=" Confirm Return" name="cnfrm_return" id="cnfrm_return" onclick="javascript: return submit_cancel();"/>
			</tr>	
			</table>
			</div>
		</div>
	</div>
</div>

<div id="mymodal-reject" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width:700px;">
    <!-- Modal content-->
		<div class="modal-content" style="height:auto">
			 <div class="modal-body" style=" overflow=scroll;">
				<h1 class="modal-heading" style="color:#f0ad4e !important;">Request Reject<img src="images/close_header.gif" width="12px" height="12px" alt="close" align="right" data-dismiss="modal"></h1>
				<table width="100%"  border="0" align="center">
				<tr>
				<td><div align="left">
				<p>Reason for reject :</p>
				</div></td>
				<td align="left">
					<select name="reason_j">									
						<option value="Incorrect product or size ordered">Incorrect product or size ordered</option>		
						<option value="Product is no longer needed">Product is no longer needed</option>	
						<option value="Product did not match its description on website or in catalog">Product did not match its description on website or in catalog</option>
						<option value="Company shipped wrrong product/size">Company shipped wrrong product/size</option>
						<option value="My reason not listed">My reason not listed</option>
					</select>*
				</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td align="left"><div align="left">
					<p>Comment:</p>
					</div>
					</td>
					<td align="left"><textarea rows="10" class="textboxwidth" cols="50" name="comment_j" id="comment_j" style="border: 1px solid;" maxlength="200"></textarea> (Max character 200)
					</td>
				</tr>
				<tr>
				<td></td>
				<td align="right"><input type="submit" class="open-cancel btn btn-warning" value=" Confirm Reject" name="cnfrm_reject" id="cnfrm_reject" onclick="javascript: return submit_cancel();"/>
			</tr>	
			</table>
			</div>
		</div>
	</div>
</div>

<div id="mymodal-cancel_c" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width:700px;">
    <!-- Modal content-->
		<div class="modal-content" style="height:auto">
			 <div class="modal-body" style=" overflow=scroll;">
				<h1 class="modal-heading" style="color:#f0ad4e !important;">Request Cancellation<img src="images/close_header.gif" width="12px" height="12px" alt="close" align="right" data-dismiss="modal"></h1>
				<table width="100%"  border="0" align="center">
				<tr>
				<td><div align="left">
				<p>Reason for cancellation :</p>
				</div></td>
				<td align="left">
					<select name="reason_c">									
						<option value="Order placed by mistake">Order placed by mistake</option>		
						<option value="Expected delivery time too long">Expected delivery time too long</option>	
						<option value="Delivery delayed">Delivery delayed</option>
						<option value="Bought from somewhere else">Bought from somewhere else</option>
						<option value="My reason not listed">My reason not listed</option>
					</select>*
				</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td align="left"><div align="left">
					<p>Comment:</p>
					</div>
					</td>
					<td align="left"><textarea rows="10" class="textboxwidth" cols="50" name="comment_c" id="comment_c" style="border: 1px solid;" maxlength="200"></textarea> (Max character 200)
					</td>
				</tr>
				<tr>
				<td></td>
				<td align="right"><input type="submit" class="open-cancel btn btn-warning" value=" Confirm Cancellation" name="cnfrm_cancel_c" id="cnfrm_cancel_c" onclick="javascript: return submit_cancel();"/>
			</tr>	
			</table>
			</div>
		</div>
	</div>
</div>

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