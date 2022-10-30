<?php

//print_r("sghsgsdhgf");die;
include("db.php");
include("functions.php");

ini_set('display_errors', 1); 



$memb_id = func_read_qs("memb_id");
$act = func_read_qs("act");
$user_type = func_read_qs("user_type");
get_rst("select cart_id from cart_items where memb_id=0$memb_id", $row_cr);
$cart_id_s = $row_cr["cart_id"];
if(get_rst("select memb_email from member_mast where memb_id='".$memb_id."'",$row_e)){
	$emailid=$row_e["memb_email"];
}
$qry="";
$incomp_msg="";

	if($memb_id <> 0){
		$qry = "select cart_item_id from cart_items where memb_id=$memb_id";
	}

	if(!get_rst($qry,$row,$rst)){
		$response['status'] = "Sorry! there are no items in the basket";
	}

if($act<>""){
	$fld_arr = array();
	
	$fld_arr["bill_name"] = func_read_qs("bill_name");
	$fld_arr["bill_email"] = func_read_qs("bill_email");
	$bill_email = func_read_qs("bill_email");
	if($bill_email == "N/A"){
	$fld_arr["bill_email"] = $emailid;
	$bill_email = $emailid;	
	}
	$fld_arr["bill_add1"] = func_read_qs("bill_add1");
	$fld_arr["bill_add2"] = func_read_qs("bill_add2");
	if($fld_arr["bill_add2"] == "null"){
		$fld_arr["bill_add2"] = "";
	}
	$fld_arr["bill_city"] = func_read_qs("bill_city");
	$fld_arr["bill_state"] = func_read_qs("bill_state");
	$fld_arr["bill_postcode"] = func_read_qs("bill_postcode");
	$fld_arr["bill_country"] = "India";
	$fld_arr["bill_tel"] = func_read_qs("bill_tel");
	$fld_arr["bill_mob"] = func_read_qs("bill_mob");

	$fld_arr["ship_name"] = func_read_qs("ship_name");
	$fld_arr["ship_email"] = func_read_qs("ship_email");
	if($fld_arr["ship_email"] == "N/A"){
			$fld_arr["ship_email"] = $emailid;
		}
	$fld_arr["ship_add1"] = func_read_qs("ship_add1");
	$fld_arr["ship_add2"] = func_read_qs("ship_add2");
	if($fld_arr["ship_add2"] == "null"){
		$fld_arr["ship_add2"] = "";
	}
	$fld_arr["ship_city"] = func_read_qs("ship_city");
	$fld_arr["ship_state"] = func_read_qs("ship_state");
	$fld_arr["ship_postcode"] = func_read_qs("ship_postcode");
	$fld_arr["ship_country"] = "India";
	$fld_arr["ship_tel"] = func_read_qs("ship_tel");
	$fld_arr["ship_mob"] = func_read_qs("ship_mob");
	$fld_arr["ord_instruct"] = func_read_qs("ord_instruct");
	
	//$_SESSION["ship_state"] = $fld_arr["ship_state"];
	//$_SESSION["ship_city"] = $fld_arr["ship_city"];	
	
	if(get_rst("select cart_id from cart_details where memb_id=$memb_id",$row,$rst)){
	
		$sql=func_update_qry("cart_details",$fld_arr," where memb_id=$memb_id");
	}
	else{
		
		$session_id=rand();
		$fld_arr["session_id"] = $session_id;
		$fld_arr["cart_id"] = $cart_id_s;
		$fld_arr["memb_id"] = $memb_id;
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
	
	$cart_id = $cart_id_s;		//change related to shipping calculation..
	$ship_pin=func_read_qs("ship_postcode");
	update_shipping_charges($ship_pin, $cart_id);
	$ck =1;
	update_cart_summary($memb_id,$ck);
	$response['status']="success";
}

if($act==""){
	$response['address']=array();
	if(get_rst("select * from cart_details where memb_id=$memb_id",$row,$rst)){
		$temp['bill_name'] = $row["bill_name"];
		$temp['bill_email'] = $row["bill_email"];
		$bill_email = $row["bill_email"];
		if($bill_email == "N/A"){
			$fld_arr["bill_email"] = $emailid;
			$bill_email = $emailid;	
		}
		$temp['bill_add1'] = $row["bill_add1"];
		$temp['bill_add2'] = $row["bill_add2"];
		$temp['bill_city'] = $row["bill_city"];
		$temp['bill_state'] = $row["bill_state"];
		$temp['bill_country'] = $row["bill_country"];
		$temp['bill_postcode'] = $row["bill_postcode"];
		$temp['bill_tel'] = $row["bill_tel"];

		$temp['ship_name'] = $row["ship_name"];
		$temp['ship_email'] = $row["ship_email"];
		if($temp['ship_email'] == "N/A"){
			$temp['ship_email'] = $emailid;
		}
		$temp['ship_add1'] = $row["ship_add1"];
		$temp['ship_add2'] = $row["ship_add2"];
		$temp['ship_city'] = $row["ship_city"];
		$temp['ship_state'] = $row["ship_state"];
		$temp['ship_country'] = $row["ship_country"];
		$temp['ship_postcode'] = $row["ship_postcode"];
		$temp['ship_tel'] = $row["ship_tel"];

		$temp['ord_instruct'] = $row["ord_instruct"];
		
		if(get_rst("select pay_method from cart_summary where user_id=$memb_id",$row)){
			$temp['pay_method'] = $row["pay_method"];
		}
		
		array_push($response['address'],$temp);
		
	}else{
		if($user_type <> "S"){
			if(get_rst("select * from member_mast where memb_id=0".$memb_id,$row)){

				$temp['bill_name'] = $row["memb_title"]." ".$row["memb_fname"]." ".$row["memb_sname"];
				$temp['bill_email'] = $row["memb_email"];
				$bill_email = $row["memb_email"];
				$temp['bill_add1'] = $row["memb_add1"];
				$temp['bill_add2'] = $row["memb_add2"];
				$temp['bill_city'] = $row["memb_city"];
				$temp['bill_state'] = $row["memb_state"];
				$temp['bill_country'] = $row["memb_country"];
				$temp['bill_postcode'] = $row["memb_postcode"];
				$temp['bill_tel'] = $row["memb_tel"];
				$temp['bill_act_id'] = $row["memb_act_id"];
				
				array_push($response['address'],$temp);
			}
		}
	}
}

 if(get_rst("select memb_company,ind_buyer from member_mast where memb_email='".$bill_email."'",$row_m)){
	$response['memb_company'] = $row_m["memb_company"];
	$response['ind_buyer'] = $row_m["ind_buyer"];
} 

echo json_encode($response);

?>