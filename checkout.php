<?php
require("inc_init.php");
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title><?=$cms_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="DESCRIPTION" content="<?=$cms_meta_key?>"/>
<meta name="KEYWORDS" content="<?=$cms_meta_desc?>"/>
<link href="styles/styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript"> var city_arr = []; </script>
</head>

<body>
<script src='lib/completely.js'></script>

<?php
require("header.php");
$blank_msg="";

$memb_id = 0;
if(isset($_SESSION["memb_id"]) && $_SESSION["memb_id"] <> ""){
	$memb_id = $_SESSION["memb_id"];
}else{
	$blank_msg = "Please sign-in if you are a member or register to continue with the checkout process.";
}

$qry="";
if($memb_id <> 0){
	$qry = "select cart_item_id from cart_items where memb_id=$memb_id";
}else{
	$qry = "select cart_item_id from cart_items where session_id='".session_id()."' and memb_id IS NULL";
}

if(!get_rst($qry,$row,$rst)){
	$blank_msg = "Sorry! there are no items in your cart";
}

$chk="";
$ro="";
$chk2="";

$bill_name="";
$bill_email=func_var($_SESSION["memb_email"]);
$bill_add1="";
$bill_add2="";
$bill_city="";
$bill_state="";
$bill_postcode="";
$bill_country="";
$bill_tel="";
$bill_mob="";

$ship_title="";
$ship_name="";
$ship_sname="";
$ship_email=func_var($_SESSION["memb_email"]);
$ship_add1="";
$ship_add2="";
$ship_city="";
$ship_state="";
$ship_postcode="";
$ship_country="";
$ship_tel="";
$ship_mob="";

$ord_instruct="";
$pay_method="";

$act = func_read_qs("act");

if($act<>""){
	$fld_arr = array();
	
	$fld_arr["bill_name"] = func_read_qs("bill_name");
	$fld_arr["bill_email"] = func_read_qs("bill_email");
	$fld_arr["bill_add1"] = func_read_qs("bill_add1");
	$fld_arr["bill_add2"] = func_read_qs("bill_add2");
	$fld_arr["bill_city"] = func_read_qs("bill_city");
	$fld_arr["bill_state"] = func_read_qs("bill_state");
	$fld_arr["bill_postcode"] = func_read_qs("bill_postcode");
	$fld_arr["bill_country"] = func_read_qs("bill_country");
	$fld_arr["bill_tel"] = func_read_qs("bill_tel");
	$fld_arr["bill_mob"] = func_read_qs("bill_mob");

	$fld_arr["ship_name"] = func_read_qs("ship_name");
	$fld_arr["ship_email"] = func_read_qs("ship_email");
	$fld_arr["ship_add1"] = func_read_qs("ship_add1");
	$fld_arr["ship_add2"] = func_read_qs("ship_add2");
	$fld_arr["ship_city"] = func_read_qs("ship_city");
	$fld_arr["ship_state"] = func_read_qs("ship_state");
	$fld_arr["ship_postcode"] = func_read_qs("ship_postcode");
	$fld_arr["ship_country"] = func_read_qs("ship_country");
	$fld_arr["ship_tel"] = func_read_qs("ship_tel");
	$fld_arr["ship_mob"] = func_read_qs("ship_mob");
	$fld_arr["ord_instruct"] = func_read_qs("ord_instruct");
	
	$_SESSION["ship_state"] = $fld_arr["ship_state"];
	$_SESSION["ship_city"] = $fld_arr["ship_city"];	
	$cart_id_upd = 0;
	if(get_rst("select cart_id from cart_details where memb_id=$memb_id",$row,$rst)){
	    $cart_id_upd = $row["cart_id"]; 
		$fld_arr["cart_id"] = $_SESSION["cart_id"];
		$sql=func_update_qry("cart_details",$fld_arr," where memb_id=$memb_id");
	}else{
	    $cart_id_upd = $_SESSION["cart_id"];
		$fld_arr["session_id"] = session_id();
		$fld_arr["cart_id"] = $_SESSION["cart_id"];
		$fld_arr["memb_id"] = $_SESSION["memb_id"];
		$sql=func_insert_qry("cart_details",$fld_arr);
	}
	//echo($sql);
	execute_qry($sql);
	
	$pay_method = func_read_qs("pay_method");
	if(get_rst("select pay_name from pay_method where pay_code='$pay_method'",$row)){
		$fld_arr_s = array();
		$fld_arr_s["pay_method"] = $pay_method;
		$fld_arr_s["pay_method_name"] = $row["pay_name"];
		$sql=func_update_qry("cart_summary",$fld_arr_s," where user_id=$memb_id");
		execute_qry($sql);
	}
	update_shipping_charges($fld_arr["ship_postcode"], $_SESSION["cart_id"]);
	update_cart_summary($memb_id);
	
	
	if($act=="prev"){
		js_redirect("basket.php");
	}else{
		js_redirect("ord_summary.php");
	}
}

if($act=="" AND $blank_msg==""){
	if(get_rst("select * from cart_details where memb_id=$memb_id",$row,$rst)){
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
		
		if(get_rst("select pay_method from cart_summary where user_id=$memb_id",$row)){
			$pay_method = $row["pay_method"];
		}
	}else{
		if($_SESSION["user_type"] <> "S"){
			if(get_rst("select * from member_mast where memb_id=0".$_SESSION["memb_id"],$row)){

				$bill_name = $row["memb_title"]." ".$row["memb_fname"]." ".$row["memb_sname"];
				$bill_email = $row["memb_email"];
				$bill_add1 = $row["memb_add1"];
				$bill_add2 = $row["memb_add2"];
				$bill_city = $row["memb_city"];
				$bill_state = $row["memb_state"];
				$bill_country = $row["memb_country"];
				$bill_postcode = $row["memb_postcode"];
				$bill_tel = $row["memb_tel"];
				$bill_act_id = $row["memb_act_id"];
			}
		}
	}
}
if(get_rst("select * from member_mast where memb_email='".$bill_email."'",$row_m)){
	$memb_company = $row_m["memb_company"];
}
?>
<div id="contentwrapper">
<div id="contentcolumn">
<div class="center-panel">
	<div class="you-are-here">
		<p align="left">
			YOU ARE HERE:<a href="index.php" title="">Home</a> | <span class="you-are-normal">Checkout</span>
		</p>
	</div>
	
	<br>
	<center>
	<table border="1" width="90%" class="table_checkout boxed-shadow">
		<tr>
			<th>1. Your Details</th>
			<td>2. Order Summary</td>
			<td>3. Payment</td>
			<td>4. Order Confirmation</td>			
		</tr>
	</table>
	</center>
	<br>
	<?php
		if ($_SESSION["error_msg"] <> "") { ?>
		<center>
			<div class="alert alert-info"><?=$_SESSION["error_msg"]?></div>
		</center>
		<?php 
			$_SESSION["error_msg"] = "";
		}
	?>
	<?php if($blank_msg<>""){ ?>
	<center>
		<div class="alert alert-info"><?=$blank_msg?></div>
	</center>
	<?php } else {
		 if(func_var($_SESSION["pg_failed"]) == "1"){ ?>
			<p> <span class="red">Returned from payment gateway</span></p>
			<?php
				$_SESSION["pg_failed"] ="";
		}
	?>
	
	<form name="frmCheckout" method="post" action="checkout.php">		
		<input type="hidden" name="act" value="next"> 
		<input type="hidden" name="hdnSubmit" value="y">	
		<table width="100%"  border="1" align="center" class="checkout" >
		<tr>
		<td align="right"></td>
		<td align="left" class="table-bg2"><p>Your Billing Details</p></td>
		<td align="left" class="table-bg2"><p>Your Shipping Details</p></td>
		</tr>
		<tr>
		<td align="left" class="table-bg2"></td>
		
		<td align="left" class="table-bg2">
		<?php
			$add_flds = "concat(ext_addr1,'|',IFNULL(ext_addr2,' '),'|',ext_addr_state,'|',ext_addr_city,'|',ext_addr_pin,'|',ext_addr_contact)";
		?>
		<select class="form-control textbox-mid" name="shipping_type" onChange="javascript:js_fill('B',this);">
			<option >Select Address</option>
			<?php
				create_cbo("select $add_flds, ext_addr_name from memb_ext_addr where memb_id=".$_SESSION["memb_id"],$add_type);
			?>
		</select>
		</td>
		
		<td align="left" valign="middle" class="table-bg2">
		<select class="form-control textbox-mid" name="billing_type" onChange="javascript:js_fill('S',this); alert('Shipping charges are subject to shipping location. They may change if the shipping address changes');">
			<option >Select Address</option>
			<?php
			create_cbo("select $add_flds, ext_addr_name from memb_ext_addr where memb_id=".$_SESSION["memb_id"],$add_type);
			?>
		</select>
		</td>
		</tr>
		

		<tr>
		<?php if($row_m["ind_buyer"]==1){?>
		<td align="right" class="table-bg2"><label for="Name"></label>
		<font color="<?=isRed($bill_name)?>"><p>Name :</p></font></td>
		<td align="left" class="table-bg2"><input name="bill_name" class="form-control textbox-mid" type="text" id="100" title="Billing Name" tabindex="1" size="20" maxlength="100"  value="<?=$memb_company?>" <?=$ro?>/>
		<span class="redtext">*</span></td>
		<td align="left" class="table-bg2"><input name="ship_name" class="form-control textbox-mid" type="text" id="100" title="Shipping Name" tabindex="11" size="20" maxlength="100"  value="<?=$memb_company?>"/>
		<font color="<?=isRed($ship_name)?>"><span class="redtext">*</span></font></td><?php }else{?>
		<td align="right" class="table-bg2"><label for="Name"></label>
		<font color="<?=isRed($bill_name)?>"><p>Name :</p></font></td>
		<td align="left" class="table-bg2"><input name="bill_name" class="form-control textbox-mid" type="text" id="100" title="Billing Name" tabindex="1" size="20" maxlength="100"  value="<?=$bill_name?>" <?=$ro?>/>
		<span class="redtext">*</span></td>
		<td align="left" class="table-bg2"><input name="ship_name" class="form-control textbox-mid" type="text" id="100" title="Shipping Name" tabindex="11" size="20" maxlength="100"  value="<?=$ship_name?>"/>
		<font color="<?=isRed($ship_name)?>"><span class="redtext">*</span></font></td><?php }?>
		</tr>
		
		<tr>
		<td align="right" class="table-bg2"><label for="Email"></label>
		<font color="<?=isRed($bill_email)?>"><p>Email :</p></font></td>
		<td align="left" class="table-bg2"><input name="bill_email" class="form-control textbox-mid" type="text" id="120" title="Billing Email" tabindex="2" size="20" maxlength="50"  value="<?=$bill_email?>" <?=$ro?>/>
		<span class="redtext">*</span></td>
		<td align="left" class="table-bg2"><input name="ship_email" class="form-control textbox-mid" type="text" id="120" title="Shipping Email" tabindex="12" size="20" maxlength="50"  value="<?=$ship_email?>"/>
		<font color="<?=isRed($ship_email)?>"><span class="redtext">*</span></font></td>
		</tr>
		<tr>
		<td align="right" class="table-bg2"><label for="Address1"></label>
		<font color="<?=isRed($bill_add1)?>"><p>Address 1 :</p></font></td>
		<td align="left" class="table-bg2"><input name="bill_add1" class="form-control textbox-mid" type="text" id="100" title="Billing Address1" tabindex="3" size="20" maxlength="50"  value="<?=$bill_add1?>" <?=$ro?>/>
		<span class="redtext">*</span></td>
		<td align="left" class="table-bg2"><input name="ship_add1" class="form-control textbox-mid" type="text" id="100" title="Shipping Address1" tabindex="13" size="20" maxlength="50"  value="<?=$ship_add1?>"/>
		<font color="<?=isRed($ship_add1)?>"><span class="redtext">*</span></font></td>
		</tr>

		<tr>
		<td align="right" class="table-bg2"><label for="Address2"></label>
		<p>Address 2 :</p></td>
		<td align="left" class="table-bg2"><input name="bill_add2" class="form-control textbox-mid" type="text" id="Address2" tabindex="4" size="20" maxlength="50" value="<?=$bill_add2?>" <?=$ro?>/></td>
		<td align="left" class="table-bg2"><input name="ship_add2" class="form-control textbox-mid" type="text" id="Address2" tabindex="14" size="20" maxlength="50" value="<?=$ship_add2?>"/></td>
		</tr>

		<tr>
		<td align="right" class="table-bg2"><label for="Country"></label>
		<p>Country :</p></td>
		<td align="left" class="table-bg2"><select id="Country" class="form-control textbox-mid" name="bill_country"  tabindex="5" <?=$ro?>>
		<option value="India">India</option>
		</select>
		</td>
		<td align="left" class="table-bg2"><select id="Country" class="form-control textbox-mid" name="ship_country"  tabindex="15">
		<option value="India">India</option>
		</select>
		</td>
		</tr>
		
		<tr>
		<td align="right" class="table-bg2"><label for="State"></label>
		<font color="<?=isRed($bill_state)?>"><p>State :</p></font></td>
		<td align="left" class="table-bg2">
			<select id="100" title="Billing State" class="form-control textbox-mid" name="bill_cbo_state" onchange="js_bill_state_sel(this);" tabindex="6">
			<option value="">Select</option>
			<?=create_cbo("select state_id,state_name from state_mast",func_var($bill_state))?>
			</select>
			<input name="bill_state" id="bill_state" type="hidden" value="<?=$bill_state?>">
		<span class="redtext">*</span></td>
		<td align="left" class="table-bg2">
			<select id="100" title="Shipping State" class="form-control textbox-mid" name="ship_cbo_state" onchange="js_ship_state_sel(this);" tabindex="16">
			<option value="">Select</option>
			<?=create_cbo("select state_id,state_name from state_mast",func_var($ship_state))?>
			</select>
			<input name="ship_state" id="ship_state" type="hidden" value="<?=$ship_state?>">
		<font color="<?=isRed($ship_state)?>"><span class="redtext">*</span></font></td>
		</tr>
		
		<tr>
		<td align="right" class="table-bg2"><label for="City"></label>
		<font color="<?=isRed($bill_city)?>"><p>City :</p></font></td>
		<td align="left" class="table-bg2">
			<div id='bill_city_auto' tabindex="7"></div>
				<input name="bill_city" class="form-control textbox-mid" type="hidden" id="100" title="Billing Town / City" tabindex="7" size="20" maxlength="50"  value="<?=$bill_city?>" />
		</td>
		
		<td align="left" class="table-bg2">
			<div id='ship_city_auto' tabindex="17"></div>
				<input name="ship_city" class="form-control textbox-mid" type="hidden" id="100" title="Shipping Town / City" tabindex="17" size="20" maxlength="50"  value="<?=$ship_city?>"/>			
			</td>
		</tr>
		
		<tr>
		<td align="right" class="table-bg2"><label for="Post Code"></label>
		<font color="<?=isRed($bill_postcode)?>"><p>Post code :</p></font></td>
		<td align="left" class="table-bg2"><input name="bill_postcode" class="form-control textbox-mid" type="text" id="100" title="Billing Post code" tabindex="8" size="20" maxlength="6" onkeypress="return isNumberKey(event)" value="<?=$bill_postcode?>" <?=$ro?>/>
		<span class="redtext">*</span></td>
		<td align="left" class="table-bg2"><input name="ship_postcode" class="form-control textbox-mid" type="text" id="100" title="Shipping Post code" tabindex="18" size="20" maxlength="6" onkeypress="return isNumberKey(event)" onchange = "alert('Shipping charges are subject to shipping location. They may change if the shipping address changes');" value="<?=$ship_postcode?>"/>
		<font color="<?=isRed($ship_postcode)?>"><span class="redtext">*</span></font></td>
		</tr>

		<tr>
		<td align="right" class="table-bg2"><label for="Telephone"></label>
		<font color="<?=isRed($ship_tel)?>"><p>Contact No. :</p></font></td>
		<td align="left"  class="table-bg2"><input name="bill_tel" class="form-control textbox-mid" type="text" id="100" title="Billing Contact No" tabindex="9" size="20" maxlength="10" onkeypress="return isNumberKey(event)" value="<?=$bill_tel?>" <?=$ro?>/>
		<span class="redtext">*</span></td>
		<td align="left"  class="table-bg2"><input name="ship_tel" class="form-control textbox-mid" type="text" id="100" title="Shipping Contact No" tabindex="19" size="20" maxlength="10" onkeypress="return isNumberKey(event)" value="<?=$ship_tel?>"/>
		<font color="<?=isRed($ship_tel)?>"><span class="redtext">*</span></font></td>
		</tr>

		</table>
		<table width="100%"  border="0" align="center" class="checkout" >
		<tr>
		<td width="175" align="left" class="table-bg2"><p>Additional Instructions</p></td>
		<td align="left"  class="table-bg2">
			<?php control_textarea("ord_instruct",$ord_instruct,1000,"","72","","class='txt_area'") ?>	
		</td>
		</tr>

		</table>

		<table width="100%"  border="0" align="center" class="checkout" >
		<tr>
		<td align="left" class="table-bg3"><p>Mode of Payment</p></td>
		</tr>
		
		<?php
		get_rst("select * from pay_method where pay_status<>0 order by pay_sort",$row,$rst);
		do{
			$rdo_disabled = "disabled";
			$row_class = "";
			$rdo_checked = "";
			if($row["pay_status"]=="1"){
				$rdo_disabled = "";
				$rdo_checked="";
				$row_class = "show_color";
			}elseif($row["pay_code"] == "OC"){
				if(get_rst("select memb_id from member_mast where memb_id=$memb_id and memb_credit_status=1")){
					$rdo_disabled = "";
					$row_class = "show_color";
					$rdo_checked="";
				}
			}
			if($row["pay_code"] == "CC"){
				$rdo_checked = "rdo_checked";
			}
			?>
			<tr class="<?=$row_class?>">
			<td align="left" class="table-bg2" ><p>
				<input name="pay_method" onchange="payBT_toggle(this);" type="radio" <?=$rdo_disabled?> <?=$rdo_checked?> id="pm_<?=$row["pay_code"]?>" value="<?=$row["pay_code"]?>" <?=sel_($pay_method,$row["pay_code"])?>/>
				<label class="normal" for="pm_<?=$row["pay_code"]?>"><?=$row["pay_name"]?></label>
			</p></td>
			</tr>
			<?php
		}while($row = mysqli_fetch_array($rst));
		?>
		
		
		</table>
		<div id="bt_payment" style="display:none;">
			<table width="100%"  border="0" align="center" class="checkout" >
				<tr>
				<td align="left" colspan="10" class="table-bg3"><p>Bank Transfer Details</p></td>
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
					<td class="color-amber">Note : </td>
					<td align="left" colspan="3" class="color-amber">After order confirmation, please provide the details of transfer to the above bank account. Please visit order details page for more information.</td>
				</tr>
				<tr>
				</tr>
				
			</table>
		</div>

		<div id="oncredit_payment" style="display:none;">
			<table width="100%"  border="0" align="center" class="checkout" >
				<tr>
					<td class="table-bg3"><p>On Credit Terms & Conditions</p></td>
				</tr>
				<tr>
					<td><p>By clicking "Next" button, You agree to the <a style="text-decoration: underline;" class="color-amber" href="extras/docs/BUYER_CREDIT_AGREEMENT.pdf" target="_blank">Company-Name Buyer Credit Agreement</a></p></td>
				</tr>
			</table>
		</div>
		<center>
		<table width="90%"  border="0" align="center" xclass="list">
		<tr>
		<td align="left"><input type="button" class="btn btn-warning" value=" Previous " onclick="javascript: js_prev();"/></a></td>
		<td align="right"><input type="button" class="btn btn-warning" value="  Next " onclick="javascript: js_next();"/></td>
		</tr>
		</table>
		
		</center>
	</form>


	   
	<script>	  

		function isNumberKey(evt)
		{
		  var charCode = (evt.which) ? evt.which : evt.keyCode;
		  if (charCode != 46 && charCode > 31 
			&& (charCode < 48 || charCode > 57))
			 return false;

		  return true;
		}

		state_obj = document.frmCheckout.bill_cbo_state
		v_state = document.getElementById("bill_state").value
		for(i=0;i<state_obj.options.length;i++){
			if(v_state == state_obj.options[i].text){
				state_obj.selectedIndex = i
				break;
			}
		}
		state_obj = document.frmCheckout.ship_cbo_state
		v_state = document.getElementById("ship_state").value
		for(i=0;i<state_obj.options.length;i++){
			if(v_state == state_obj.options[i].text){
				state_obj.selectedIndex = i
				break;
			}
		}

		var bill_auto = completely(document.getElementById('bill_city_auto'), {
			fontFamily : 'Roboto',
		});
		bill_auto.input.value = document.frmCheckout.bill_city.value	
		document.getElementById('bill_city_auto').className = "form-control textbox-mid"
		var ship_auto = completely(document.getElementById('ship_city_auto'), {
			fontFamily : 'Roboto',
		});
		ship_auto.input.value = document.frmCheckout.ship_city.value
		document.getElementById('ship_city_auto').className = "form-control textbox-mid"

		function js_auto(){
			//alert(bill_auto.input.value);
			document.frmCheckout.bill_city.value = bill_auto.input.value
			document.frmCheckout.ship_city.value = ship_auto.input.value
		}
		</script>
	<?php }?>	
	   <script>
		
		function js_bill_state_sel(state_obj){
			var state_name = state_obj.value;
			document.getElementById("bill_state").value = state_obj.options[state_obj.selectedIndex].text
			var c_arr = city_arr[state_name].split(",")
			bill_auto.options = [];
			for(i=0;i<c_arr.length;i++){
				bill_auto.options[i]=c_arr[i];
			}
			
			bill_auto.repaint(); 
			setTimeout(function() {
				bill_auto.input.focus();
		   },0);
		}

		function js_ship_state_sel(state_obj){
			var state_name = state_obj.value;
			document.getElementById("ship_state").value = state_obj.options[state_obj.selectedIndex].text
			var c_arr = city_arr[state_name].split(",")
			ship_auto.options = [];
			for(i=0;i<c_arr.length;i++){
				ship_auto.options[i]=c_arr[i];
			}
			ship_auto.repaint(); 
			setTimeout(function() {
				ship_auto.input.focus();
		   },0);
		}

	</script>	
	<br>
	
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
<script src="scripts/scripts.js" type="text/javascript"></script>
<script src="scripts/checkout.js" type="text/javascript"></script>
<script src="scripts/chat.js" type="text/javascript"></script>
</html>
	