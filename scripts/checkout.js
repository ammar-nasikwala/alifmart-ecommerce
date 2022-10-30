function js_copy(){
	document.frmCheckout.ship_name.value=document.frmCheckout.bill_name.value;	
	document.frmCheckout.ship_email.value=document.frmCheckout.bill_email.value;
	document.frmCheckout.ship_add1.value=document.frmCheckout.bill_add1.value
	document.frmCheckout.ship_add2.value=document.frmCheckout.bill_add2.value
	document.frmCheckout.ship_tel.value=document.frmCheckout.bill_tel.value;
	document.frmCheckout.ship_country.value=document.frmCheckout.bill_country.value
	document.frmCheckout.ship_cbo_state.value=document.frmCheckout.bill_cbo_state.value
	document.frmCheckout.ship_state.value=document.frmCheckout.bill_state.value
	ship_auto.input.value = bill_auto.input.value
	document.frmCheckout.ship_city.value=document.frmCheckout.bill_city.value	
	document.frmCheckout.ship_postcode.value=document.frmCheckout.bill_postcode.value
}

function js_fill(add_type,obj){
	v_add = obj.value
	v_add_arr = v_add.split('|');
	
	if(add_type=="B"){
		if(v_add == 'Select Address'){
			document.frmCheckout.bill_add1.value = "";		
			document.frmCheckout.bill_add2.value = "";		
			document.frmCheckout.bill_state.value = "";
			document.frmCheckout.bill_cbo_state.value = "";
			bill_auto.input.value = "";
			document.frmCheckout.bill_city.value = "";
			document.frmCheckout.bill_postcode.value = "";
			document.frmCheckout.bill_tel.value = "";
		}else{
			//document.frmCheckout.bill_name.value = v_add_arr[0];
			document.frmCheckout.bill_add1.value = v_add_arr[0];		
			document.frmCheckout.bill_add2.value = v_add_arr[1];		
			document.frmCheckout.bill_state.value = v_add_arr[2];
			document.frmCheckout.bill_cbo_state.value = v_add_arr[2];
			bill_auto.input.value = v_add_arr[3];
			document.frmCheckout.bill_city.value = v_add_arr[3];
			document.frmCheckout.bill_postcode.value = v_add_arr[4];
			document.frmCheckout.bill_tel.value = v_add_arr[5];
			state_obj = document.frmCheckout.bill_cbo_state
			v_state = document.getElementById("bill_state").value
			for(i=0;i<state_obj.options.length;i++){
				if(v_state == state_obj.options[i].text){
					state_obj.selectedIndex = i
					break;
				}
			}
		}
	}

	if(add_type=="S"){
		if(v_add == 'Select Address'){
			document.frmCheckout.ship_add1.value = "";		
			document.frmCheckout.ship_add2.value = "";		
			document.frmCheckout.ship_state.value = "";
			document.frmCheckout.ship_cbo_state.value = "";
			ship_auto.input.value = "";
			document.frmCheckout.ship_city.value = "";
			document.frmCheckout.ship_postcode.value = "";
			document.frmCheckout.ship_tel.value = "";
		}else{
			document.frmCheckout.ship_name.value = document.frmCheckout.bill_name.value;
			document.frmCheckout.ship_add1.value = v_add_arr[0];		
			document.frmCheckout.ship_add2.value = v_add_arr[1];		
			document.frmCheckout.ship_state.value = v_add_arr[2];
			document.frmCheckout.ship_cbo_state.value = v_add_arr[2];
			ship_auto.input.value = v_add_arr[3];
			document.frmCheckout.ship_city.value = v_add_arr[3];
			document.frmCheckout.ship_postcode.value = v_add_arr[4];
			document.frmCheckout.ship_tel.value = v_add_arr[5];
			state_obj = document.frmCheckout.ship_cbo_state
			v_state = document.getElementById("ship_state").value
			for(i=0;i<state_obj.options.length;i++){
				if(v_state == state_obj.options[i].text){
					state_obj.selectedIndex = i
					break;
				}
			}
		}
	}
}

function get_delivery_status(pincode){
	v_valid="";
	if(pincode!=""){
		v_valid = call_ajax("ajax.php","process=check_delivery_pin&pincode=" + pincode) 
		if(v_valid=="Available"){
			return true;
		}else{
			return false
		}
	}
}

function js_prev(){
	document.frmCheckout.bill_city.value = bill_auto.input.value
	document.frmCheckout.ship_city.value = ship_auto.input.value
	document.frmCheckout.act.value="prev";
	document.frmCheckout.submit();
}

function js_next(){
	v_checked=0;
	for(i=0;i<document.frmCheckout.pay_method.length;i++){
		if(document.frmCheckout.pay_method[i].checked){
			v_checked++;
		}
	}
	 
	document.frmCheckout.bill_city.value = bill_auto.input.value
	document.frmCheckout.ship_city.value = ship_auto.input.value
    if(!get_delivery_status(document.frmCheckout.ship_postcode.value)){
		alert("Delivery is not available at your location, please change the shipping address.");
		return false;
	}
	if(chkForm(document.frmCheckout)==false){
		return false;
	}else{
		if(v_checked==0){
			alert("Please select a Payment Option")
			return false;
		}
		
		document.frmCheckout.act.value="next";
		document.frmCheckout.submit();
	}
}

function payBT_toggle(obj){
	if(obj.checked){
		if(obj.value == "BT"){
			document.getElementById("bt_payment").style.display = "block";
		}
		else{
			document.getElementById("bt_payment").style.display = "none";
		}

		if(obj.value == "OC"){
			document.getElementById("oncredit_payment").style.display = "block";
		}
		else{
			document.getElementById("oncredit_payment").style.display = "none";
		}
	}
}
