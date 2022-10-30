<?php


function convert_to_utf8(&$value,$key)
{
	$value = mb_convert_encoding($value, "UTF-8", true);
}

function get_local_ship_charges($memb_pincode,$sup_id,$weight){
	$ship_charges = 0;
	if(get_rst("select charges from local_logistics_charges where min_weight<=$weight and max_weight>=$weight", $s_row)){
		$ship_charges = $s_row["charges"];
 	}
	
	return $ship_charges;
}

function sendcustomernotification_order($cart_id,$memb_id,$pay_method)
{
	//$sql="select * from notification where user_id=0$memb_id and login_status=1 and device='android'";
	global $con;
	$sql="select * from notification where user_id=0$memb_id and login_status=1 and type='user'";
	$rs_find = mysqli_query($con,$sql);
	$flag=0;
	if($rs_find){
			$flag=1;
		while($row = mysqli_fetch_assoc($rs_find)){
		//	$flag=json_encode($row);

		/*  $rs_find = mysqli_query($con,$sql);
		if($row = mysqli_fetch_assoc($rs_find)){
			$flag=1;
		}  */
			$message['title']="Order Detail";
			
			if($row['device']=="android")
			{
				$activity='OrderListItemActivity';
			}
			else if($row['device']=="ios")
			{
				if($pay_method=='BT')
					$activity='BankDetailController1';
				else
					$activity='orderdetail1Controller';
			}
			
			$message['notification']=array(
				'title'=>"Order Detail",
				'text'=>"Order placed Successfully",
				//'text'=>$flag,
				'click_action'=>$activity
				);
				
			$message['data']=array(
					'activity'=>$activity,
					'id_offer'=>$cart_id,	
					//'icon'=>"https://api.sonymobile.com/files/xperia-c3-white-2000x2000-29720b59247327f2937f0e99493617ea.png",
			);

			$registration_ids=$row['device_token'];
			$message['registration_ids'][]=$registration_ids;
			//$message['registration_ids'][]="dJN3uoCcBlk:APA91bEaM7j6XFvkCIdK3S826p6pgjK90j5cNG5ooz8RqcdrxsyieTfUNPyd6Qc1NemVCb26GKRlfWdMsMMf5WpdS22h2-xVLRHz2vn-XGoIP2Hl_FPLi8Kt-LGMnWANGw2UUwZEDKlQ";
		
			//print_r(json_encode($message));die;
			sendnotification(json_encode($message));
		}
	}
}

function sendsellernotification_order($cart_id)
{
	global $con;
	$sql="select * from ord_summary where cart_id=0$cart_id";
	$rs_find = mysqli_query($con,$sql);
	$flag=0;
	if($rs_find){
		
		$flag=1;
		while($row = mysqli_fetch_assoc($rs_find)){
			//$flag=json_encode($row['sup_id']);

			
			//$seller_id=110;
			$seller_id=$row['sup_id'];
			
			
			$sql1="select * from notification where user_id=0$seller_id and login_status=1 and type='seller'";
			$rs_find1= mysqli_query($con,$sql1);
			if($rs_find1){
				while($row1 = mysqli_fetch_assoc($rs_find1)){
				//if(get_rst("select * from notification where user_id=0$seller_id and login_status=1 and type='seller'",$row)){
					$message['title']="Order Detail";
					
					if($row1['device']=="android")
					{
						$activity='Seller_ViewOrders';
					}
					else if($row1['device']=="ios")
					{		
						$activity='ViewOrderController';
					}
					
					$message['notification']=array(
						'title'=>"Order Detail",
						'text'=>"New Order arrived",
						//'text'=>$flag,
						'click_action'=>$activity
						);
						
					$message['data']=array(
							'activity'=>$activity,
						//	'id_offer'=>$cart_id,	
						//	'icon'=>"https://api.sonymobile.com/files/xperia-c3-white-2000x2000-29720b59247327f2937f0e99493617ea.png",
							
					);

					$registration_ids=$row1['device_token'];
					$message['registration_ids'][]=$registration_ids;
					//$message['registration_ids'][]="ebyTMUNkMwI:APA91bE7ndCkD4h_blpOQX3ZK5B_OtJ8BZc7MLxALcl8xYPlGABE8itVkOR3DwMCsJ2Mx-74std5af_dC2F7A1swrRSGqRSvhHttag8VoudtH3HAzLS_CC_OuKo_pAzEYKb_XdB75J_o";

					//print_r(json_encode($message));die;
					sendnotification(json_encode($message));
				}
			}
		  
		}
	}
} 

function sendnotification($message)
{
		/* $message="{\n\t\"title\":\"alidf\",\n\t\"notification\": {\n    \"title\": \"This is the Title\",\n    \"text\": \"Hello I'm a nikhil\",\n    \"icon\": \"ic_push\",\n    \"click_action\": \"ACTIVITY_XPTO\"\n  },\n    \"data\": {\n    \"id_offer\": \"41\"\n  },\n    \"registration_ids\": [\"eI0-dfx_d40:APA91bFvUZyhs8Iu902wbG8A2Z1BjqgKTe9b6udI_QQ1astT6JFVfzepYBYfIpcsYC6f4o2uwn2Y1XasPnEKBOvU7J_E5ThwgOkhZMDgCl019aNq0rHDdZRxUSTTCGF55YLK0_bV8mP6\"]\n}\n\n"; */
		
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $message,
		  CURLOPT_HTTPHEADER => array(
			"authorization: key=AIzaSyDUwPIUb32S2aT0cUKoDIpY1TfPiCCSBI8",
			"cache-control: no-cache",
			"content-type: application/json",
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		 // echo "cURL Error #:" . $err;
		} else {
		//  echo $response;
		}
}

function generate_sku($category, $sub_category, $brand){
	$sku_code = "A";
	//get category code from category table
	$qry = "select cat_code from cat_master where cat_name='".$category."'";
	$row = "";
	get_rst($qry, $row);
	if($row <> ""){
		$sku_code.= str_pad($row["cat_code"], 2, '0', STR_PAD_LEFT);
	}else{
		$sku_code.="00";
	}
	$cat_code = $row["cat_code"];
	
	//get sub category code from sub-category table
	$row="";
    $qry = "select sub_cat_code from sub_cat_master where sub_cat_name='".$sub_category."' AND cat_code='".$cat_code."'";
	get_rst($qry, $row);
	if($row <> ""){
		$sku_code.= str_pad($row["sub_cat_code"], 2, '0', STR_PAD_LEFT);
	}else{
		$sku_code.="00";
	}
	
	//get sub brand code from brand table
	$row="";
    $qry = "select brand_code from brand_master where brand_name='".$brand."'";
	get_rst($qry, $row);
	if($row <> ""){
		$sku_code.=$row["brand_code"];
	}else{
		$sku_code.="XX";
	}
	
	//get serial number for the above code combination
	$row="";
    $qry = "select count(0)+1 as serial_code from prod_mast where prod_stockno LIKE '$sku_code%' AND prod_stockno is NOT NULL";
	if(get_rst($qry, $row)){
		$sku_code.= str_pad($row["serial_code"], 5, '0', STR_PAD_LEFT);
	}else{
		$sku_code.="00001";
	}
	return $sku_code;
}

function get_category_name($level_parent, $level_name)
{
	$category_name = "";
	$sub_category_name = "";
	$qry="select level_parent from levels where level_id=".$level_parent." AND level_name='".$level_name."'" ;
	//echo $qry;die;
	if(get_rst($qry,$row)){
		if($row["level_parent"] == 0){
			$category_name = $level_name;
		}else{
			$sub_category_name = $level_name;
			$level_parent = $row["level_parent"];
			$qry="";
			$row="";
			$qry="select level_name from levels where level_id=".$level_parent;
			if(get_rst($qry, $row)){
				$category_name=$row["level_name"];
			}
		}
	}	
	return array($category_name,$sub_category_name);
}

function get_brand_name($brand_id){
	$brand_name = "";
	$qry= "select brand_name from brand_mast where brand_id=".$brand_id;
	if(get_rst($qry,$row)){
		$brand_name=$row["brand_name"];
	}
	return $brand_name;
}


function show_img($img){
	if($img.""<>""){
		return "data:image/jpeg;base64,".base64_encode($img);
	}else{
		return "images/photonotavailable.gif";
	}
}

function send_ord_mail($session_ord_id){
	$fld_arr = array();
	$fld_sum_arr = array();
	$fld_item_arr = array();
	$cart_id="";
	$body_details = "";
	$body_sums = "";
	$body_items = "";
	$bill_email = "";
	$bill_fname = "";
	$bill_tel = "";
	$ord_no = "";
	$body_supplier = "";
	$admin_email=$orders;
	global $orders;
	$cc=" Company-Name <".$admin_email.">";
	
	if(get_rst("select * from ord_summary where ord_id=".$session_ord_id,$row_s,$rst_s)){
		$cart_id = $row_s["cart_id"];
		foreach($row_s as $key => $value) {
			$fld_arr[$key] = $value;
		}
		//$ord_no = $row_s["ord_no"];
	}	
	
	$ord_pay_total = 0;
	
	if($cart_id <> ""){
		if(get_rst("select * from ord_details where cart_id=".$cart_id,$row)){
			foreach($row as $key => $value) {
				$fld_arr[$key] = $value;
			}
			$bill_email=$row["bill_email"];
			$bill_fname=$row["bill_name"];
			$bill_tel=$row["bill_tel"];
		}
		
		do{
			$ord_no = $row_s["ord_no"];
			$body_supplier = "";
			foreach($row_s as $key => $value) {
				$fld_sum_arr[$key] = $value;
			}
			$sup_id = $row_s["sup_id"];
			$ord_pay_total = $ord_pay_total + $row_s["ord_total"];
			$body_items = "";
			$body_items_sup = "";
			if(get_rst("select * from ord_items where cart_id=".$cart_id." and sup_id=$sup_id",$row,$rst)){
				do{				
					foreach($row as $key => $value) {
						$fld_item_arr[$key] = $value;
					}				
					$fld_item_arr["prod_url"] = get_base_url()."/prod_details.php?prod_id=".$row["prod_id"];
					$fld_item_arr["item_thumb"] = show_img($row["item_thumb"]);
					if($row["tax_percent"].""<>""){
						$fld_item_arr["tax_percent"] = $row["tax_percent"]."%";
					}else{
						$fld_item_arr["tax_percent"] = "";
					}				
					get_rst("select hsn_code from prod_mast where prod_id=".$row["prod_id"], $hsn_rw);
					$fld_item_arr["hsn_code"] = $hsn_rw["hsn_code"];
					if($row["ship_amt"]==0){
						$fld_item_arr["shipping_charges"] = "Free";
					}else{
					$fld_item_arr["shipping_charges"] = $row["ship_amt"];
					}
					
					$fld_item_arr["total_amount"] = floatval($row["cart_price_tax"]) * intval($row["cart_qty"])+(floatval($row["ship_amt"]));

					$body_item = push_body("ord_items.txt",$fld_item_arr);
					$body_item_sup = push_body("ord_items_sup.txt",$fld_item_arr);
					
					$body_items = $body_items.$body_item;
					$body_items_sup = $body_items_sup.$body_item_sup;

				}while($row = mysqli_fetch_array($rst));
				
				if($row_s["shipping_charges"]==0){
					$fld_sum_arr["shipping_charges1"]="Free Delivery";
				}else{
					$fld_sum_arr["shipping_charges1"]=$row_s["shipping_charges"];
				}
				
				$fld_sum_arr["ord_no"]=$ord_no;
				$fld_sum_arr["ord_date"]=$fld_arr["ord_date"];
				$fld_sum_arr["ord_items_sup"] = $body_items_sup;
				$body_sum_sup = push_body("ord_summary_sup.txt",$fld_sum_arr);
				//$body_sum_sup = $body_sums_sup.$body_sum_sup;
				$body_sum_sup = $body_sum_sup;
				
				$fld_sum_arr["ord_items"] = $body_items;
				$body_sum = push_body("ord_summary.txt",$fld_sum_arr);
				$body_sums = $body_sums.$body_sum;
			}
			
			$fld_arr["ord_pay_total"] = $row_s["ord_total"];
			$fld_arr["ord_summary_sup"] = $body_sum_sup;
			$body_supplier = push_body("ord_details_sup.txt",$fld_arr);
			
			$fld_arr["ord_summary"] = $body_sum;
			if(get_rst("select sup_email,sup_contact_no, sup_company from sup_mast where sup_id=$sup_id",$row)){
				$sup_email = $row["sup_email"];
				$sup_tel = $row["sup_contact_no"];
				$sup_company = $row["sup_company"];
				send_mail($sup_email,"Company-Name - Order Confirmation (Seller): $ord_no",$body_supplier,"",$cc);
				$fld_sms_arr = array();
				$fld_sms_arr["memb_fname"] = $sup_company;
				$fld_sms_arr["ord_no"] = $ord_no;
				//send sms to seler
				$sms_msg = push_body("sms_ord_confirmation.txt",$fld_sms_arr);
				send_sms($sup_tel,$sms_msg);
				
				//send sms to buyer
				$fld_sms_arr["memb_fname"] = $bill_fname;
				$sms_msg = push_body("sms_ord_confirmation.txt",$fld_sms_arr);
				send_sms($bill_tel,$sms_msg);
			}
		}while($row_s = mysqli_fetch_assoc($rst_s));

		$fld_arr["ord_items"] = $body_items;
		$fld_arr["ord_summary"] = $body_sums;
		$fld_arr["ord_pay_total"] = $ord_pay_total;
		
		$body_details = push_body("ord_details.txt",$fld_arr);
		
		send_mail($bill_email,"Company-Name - Order Confirmation: $ord_no",$body_details,"orders@Company-Name.com");
		send_mail($orders,"Company-Name - Order Confirmation: $ord_no",$body_details,"info@Company-Name.com");
	}
}


function save_order($pay_method,$memb_id,$session_cart_id,$session_user_type,$cart_id=""){
	if($cart_id==""){
		$memb_id = $memb_id;
		$sql_where_summary = " where user_id=$memb_id";
		$sql_where = " where memb_id=$memb_id";
	}else{
		$sql_where_summary = " where cart_id=$cart_id";	
		$sql_where = " where cart_id=$cart_id";	
	}

	execute_qry("INSERT INTO ord_summary SELECT * FROM cart_summary $sql_where_summary and item_count is NOT NULL");
	execute_qry("INSERT INTO ord_items SELECT * FROM cart_items $sql_where and item_wish<>1");
	execute_qry("INSERT INTO ord_details SELECT * FROM cart_details $sql_where");
	execute_qry("delete FROM cart_summary $sql_where_summary");
	execute_qry("delete FROM cart_items $sql_where and item_wish<>1");
	execute_qry("delete FROM cart_details $sql_where");
    $count=1;
	if(get_rst("select count(sup_id) as scount from ord_summary where cart_id=".$session_cart_id. " group by cart_id", $row)){
		$count = $row["scount"];
	}
	$ord_id = get_max("ord_summary","ord_id");
    do{
		$fld_arr = array();
		$fld_arr["ord_id"] = $ord_id;
		$fld_arr["ord_no"] = "M".date('ymd').str_pad($ord_id, 7-strlen($ord_id), "0", STR_PAD_LEFT)."".$count;
		$fld_arr["ord_date"] = "now()";
		//$fld_arr["user_id"] = $_SESSION["memb_id"];
		$fld_arr["user_type"] = "M";
		$fld_arr["pay_status"] = "Pending";
		$fld_arr["delivery_status"] = "Pending";

		$sql = func_update_qry("ord_summary",$fld_arr," where cart_id=".$session_cart_id." and ord_no IS NULL LIMIT 1");
		execute_qry($sql);
		$count = $count - 1;
	}while($count > 0);	
	
	if(get_rst("select coupon_id from ord_coupon where cart_id=".$session_cart_id,$row_ocm )){	//change related to coupon feature...
		$fld_arr1 = array();
		$fld_arr1["memb_id"] = $memb_id;
		$fld_arr1["coupon_id"] = $row_ocm["coupon_id"];
		$fld_arr1["applied_status"] = 1;
		$qry1 = func_insert_qry("memb_coupon",$fld_arr1);
		execute_qry($qry1);
		
		if(get_rst("select max_discount_value from offer_coupon where credit_flag=1 and coupon_id=".$row_ocm["coupon_id"],$mdv)){
			execute_qry("update offer_coupon set credit_flag=NULL where coupon_id=".$row_ocm["coupon_id"]);
			get_rst("select credit_amt from credit_details where memb_id=$memb_id",$cr);
			$creditamt= $cr['credit_amt'] - $mdv["max_discount_value"];
			execute_qry("update credit_details set credit_amt=$creditamt where memb_id=$memb_id");
		}
	}
	
	execute_qry("update prod_mast set prod_purchased=IFNULL(prod_purchased,0)+1 where prod_id in (select prod_id from ord_items where cart_id=".$session_cart_id.")");
	
	//$_SESSION["cart_id"]="";
	$session_ord_id=$ord_id;
	
// Update wishlist item after order is placed
	$new_cart_id = get_next("seq_cart_id","cart_id");
	execute_qry("update cart_items set cart_id=0$new_cart_id $sql_where");
	if(get_rst("select DISTINCT sup_id from cart_items where cart_id=0$new_cart_id", $row, $rst)){
		do{
			$fld_s_arr = array();
			$fld_s_arr["session_id"] = "".session_id()."";
			$fld_s_arr["sup_id"] = $row["sup_id"];
			$fld_s_arr["cart_id"] = $new_cart_id;
			if($memb_id <> 0){
				$fld_s_arr["user_id"] = $memb_id;
			}
			$sql = func_insert_qry("cart_summary",$fld_s_arr);
			execute_qry($sql);
		}while($row = mysqli_fetch_array($rst));
	}
	send_ord_mail($session_ord_id);	
//return "true";
}

function formatNumber($var,$dec=0){
	return number_format($var,$dec,".",",");
}

function update_cart_summary($memb_id = 0, $ck){
	$qry = "";
	$qry2 = "";
	if($memb_id <> 0){
		$qry = "select cart_id,sup_id,cart_qty, sum(cart_qty * cart_price) as item_total,count(1) as item_count,sum(cart_qty * tax_value) as tax_total,sum(ship_amt) as shipping_charges from cart_items where item_wish<>1 and memb_id=$memb_id group by sup_id";		
	}else{
		$qry = "select cart_id,sup_id,sum(cart_qty * cart_price) as item_total,count(1) as item_count,sum(cart_qty * tax_value) as tax_total,sum(ship_amt) as shipping_charges from cart_items where session_id='".session_id()."' and item_wish<>1 and memb_id IS NULL group by sup_id";		
	}
	
	if(get_rst($qry,$row,$rst)){
		do{

			$fld_arr = array();
			$cart_id = $row["cart_id"];
			$sup_id = $row["sup_id"];
			$fld_arr["item_count"] = $row["item_count"];
			$fld_arr["item_total"] = $row["item_total"];
			//$fld_arr["vat_percent"] = $_SESSION["vat"];
			$fld_arr["vat_value"] = $row["tax_total"];
			$fld_arr["shipping_charges"] = 0;
			if(get_rst("select sup_company from sup_mast where sup_id=$sup_id and sup_lmd=1")){
				$fld_arr["shipping_charges"] = $row["shipping_charges"];
			}
			$fld_arr["ord_total"] = round(floatval($row["item_total"]."") + floatval($fld_arr["shipping_charges"])+ floatval($fld_arr["vat_value"]),2);
			if($memb_id <> 0){
				$sql = func_update_qry("cart_summary",$fld_arr," where user_id=$memb_id and sup_id=$sup_id");
			}else{
				$sql = func_update_qry("cart_summary",$fld_arr," where session_id='".session_id()."' and sup_id=$sup_id and user_id IS NULL");
			}
			execute_qry($sql);
		}while($row = mysqli_fetch_assoc($rst));
	}
	if($ck == 1){
	
	}else{
		if(get_rst("select coupon_id from ord_coupon where cart_id=$cart_id", $row_c)){			//if change in cart delete coupon...
		execute_qry("delete from ord_coupon where coupon_id='".$row_c["coupon_id"]."' and cart_id=$cart_id");
	}
	}
}

function update_shipping_charges($memb_pincode, $cart_id){
	if(get_rst("select cart_item_id, prod_id, cart_price, cart_qty, sup_id from cart_items where cart_id=0$cart_id ", $row, $rst)){
		do{
			$prod_id = $row["prod_id"];
			$sup_id = $row["sup_id"];
			$cart_qty = $row["cart_qty"];
			$cart_item_id= $row["cart_item_id"];
			get_rst("select prod_weight, prod_lmd  from prod_mast where prod_id=0$prod_id",$rw);
			$ship_amt = 0; 
			//if((get_rst("select sup_company from sup_mast where sup_id=$sup_id and sup_lmd=1")) && $rw["prod_lmd"]==1){
				get_rst("select sup_ext_pincode from sup_ext_addr where sup_id=$sup_id LIMIT 1", $s_row);
				//check for local logistics if applicable
				if((substr($s_row["sup_ext_pincode"],0,3)==411 || substr($s_row["sup_ext_pincode"],0,3)==410 || substr($s_row["sup_ext_pincode"],0,3)==412) && 
				(substr($memb_pincode,0,3) == 411 ||  substr($memb_pincode,0,3) == 410 || substr($memb_pincode,0,3) == 412)  || (floatval($rw["prod_weight"]) * $cart_qty)> 5){ // for local logistics
					$ship_amt = get_local_ship_charges($memb_pincode, $sup_id, floatval($rw["prod_weight"]) * $cart_qty);
					execute_qry("update cart_summary set local_logistics = 1 where sup_id=$sup_id AND cart_id=$cart_id");
				}else{
					execute_qry("update cart_summary set local_logistics = 0 where sup_id=$sup_id AND cart_id=$cart_id");
					$ship_amt = get_shipping_charges($s_row["sup_ext_pincode"], $memb_pincode, $rw["prod_weight"] * $cart_qty);			
				}
			//}else{
				//$ship_amt = 0;
			//}
			if($rw["prod_weight"] < 1 && $row["cart_price"] >= 2000){	//for free shipping....
				execute_qry("update cart_items set ship_amt=0,actual_ship_amt=$ship_amt where cart_item_id=$cart_item_id");
			}else{
				execute_qry("update cart_items set ship_amt=$ship_amt where cart_item_id=$cart_item_id");
			}
		}while($row = mysqli_fetch_assoc($rst));
	}
}

function func_var(&$var){
	if(isset($var)){
		return $var;
	}else{
		return "";
	}
}

function update_ord_summary($cart_id,$sup_id,$cart_item_id,$ord_no){
	$row_item_total=0;
	$row_item_count=0;
	$row_tax_total=0;
	if(get_rst("select sup_id,sum(cart_qty * cart_price) as item_total,count(1) as item_count,sum(cart_qty * tax_value) as tax_total,sum(ship_amt) as shipping_charges from ord_items where cart_id=0$cart_id and sup_id=0$sup_id and item_buyer_action is null group by sup_id",$row,$rst)){
		$row_item_total = $row["item_total"];
		$row_item_count = $row["item_count"];
		$row_tax_total = $row["tax_total"];
	}
	$shipping_charges = 0;
	if(intval($row_item_total)>0){
		if(get_rst("select sup_company from sup_mast where sup_id=$sup_id and sup_lmd=1")){
			$shipping_charges = $row["shipping_charges"];
		}
	}
	
	$fld_arr = array();
	
	$fld_arr["item_count"] = $row_item_count;
	$fld_arr["item_total"] = $row_item_total;
	//$fld_arr["vat_percent"] = $_SESSION["vat"];
	$fld_arr["vat_value"] = $row_tax_total;
	$fld_arr["shipping_charges"] = $shipping_charges;
	
	$fld_arr["ord_total"] = floatval($fld_arr["item_total"]."") + floatval($shipping_charges)+ floatval($fld_arr["vat_value"]);
	
	$sql = func_update_qry("ord_summary",$fld_arr," where cart_id=$cart_id and sup_id=$sup_id");
	execute_qry($sql);
	update_refund_amt($cart_id,$cart_item_id,$sup_id,$ord_no);
}

function update_refund_amt($cart_id,$cart_item_id,$sup_id,$ord_no){
 get_rst("select coupon_id,coupon_amt,coupon_deduct from ord_coupon where cart_id=$cart_id",$row_id); 
 $fld_arr = array();
 $fld_arr_ref = array();
 
 get_rst("select buyer_action,pay_status from ord_summary where cart_id=$cart_id and sup_id=$sup_id",$rc);
 get_rst("select cancel_amount,cancel_coupon from track_refund where cart_id=$cart_id and sup_id=$sup_id",$r);
 if($row_id["coupon_id"] <> ""){
		get_rst("select ord_id,user_id from ord_summary where cart_id=$cart_id",$row_canc,$rst_canc); 
	
		get_rst("select ((cart_price_tax *cart_qty) + ship_amt) as total, item_buyer_action from ord_items where cart_id=$cart_id and cart_item_id=$cart_item_id",$row_rtn);
		$actual_amt = $row_rtn["total"];
		
		get_rst("select sum((cart_price_tax * cart_qty) + ship_amt) as ord_total from ord_items where cart_id=$cart_id",$rw_tot);
		
			$total = $rw_tot["ord_total"];
			$act_amt_per = $actual_amt / $total;
			
			$coupon_ref = floatval((floatval($row_id["coupon_amt"]) * $act_amt_per));
			$coupon_ded = $coupon_ref + $row_id["coupon_deduct"];
			$refund_amt = $actual_amt - $coupon_ref;
		
		$fld_arr_ref["ord_id"] = $row_canc["ord_id"];
		$fld_arr_ref["user_id"] = $row_canc["user_id"];
		$fld_arr_ref["sup_id"] = $sup_id;
		$fld_arr_ref["ord_no"] = $ord_no;
		$fld_arr_ref["cart_id"] = $cart_id;
		$fld_arr_ref["cart_item_id"] = $cart_item_id;
		$fld_arr_ref["pay_status"] = $rc["pay_status"];
		
	if($rc["pay_status"]=='Paid'){
		$fld_arr_ref["refund_amt"] = $refund_amt;
		$fld_arr_ref["coupon_deduct"] = $coupon_ref; 
	}else{		
		$cancel_amount = $refund_amt + $ro["cancel_amount"];
		$cancel_coupon = $coupon_ref + $ro["cancel_coupon"];
		$fld_arr_ref["cancel_amount"] = $cancel_amount;
		$fld_arr_ref["cancel_coupon"] = $cancel_coupon;
		}
	
		
		$qry1 = func_insert_qry("track_refund",$fld_arr_ref);
		execute_qry($qry1);
	
	$fld_arr["coupon_deduct"] = $coupon_ded;
	
		$qry = func_update_qry("ord_coupon",$fld_arr,"where cart_id=$cart_id");
		execute_qry($qry);
	
   if($row_rtn["item_buyer_action"]=='Returned'){
		 
	   get_rst("select cart_price,actual_ship_amt from ord_items where cart_item_id=$cart_item_id",$row_rtn);
	   $amt_return = $row_rtn["cart_price"];
	   if($row_rtn["actual_ship_amt"] <> ""){
		   $amt_return = $amt_return - $row_rtn["actual_ship_amt"];
	   }
	   
	   $actual_return = $amt_return;
	   get_rst("select pay_total from invoice_mast where ord_no=(select ord_no from ord_summary where cart_id=$cart_id and sup_id=$sup_id)",$row_r);

	   $return_amt = $row_r["pay_total"] - $actual_return;
	   
	   $fld_arr_inv["pay_total"] = $return_amt;
	   
	   get_rst("select ord_no from ord_summary where cart_id=$cart_id and sup_id=$sup_id",$row_inv);
	   
	   $qry2 = func_update_qry("invoice_mast",$fld_arr_inv,"where ord_no='".$row_inv["ord_no"]."'");
	   execute_qry($qry2);
   }
 }
 else{
	 $fld_arr_ref = array();
	 $flrd_arr_inv = array();
		 get_rst("select ord_id,user_id from ord_summary where cart_id=$cart_id",$row_canc,$rst_canc);
		 
		 get_rst("select buyer_action,pay_status from ord_summary where cart_id=$cart_id and sup_id=$sup_id",$rc);
		 get_rst("select sum((cart_price_tax * cart_qty) + ship_amt) as total from ord_items where cart_id=$cart_id and sup_id=$sup_id",$rwc);
		
		get_rst("select ((cart_price_tax * cart_qty) + ship_amt) as total, item_buyer_action from ord_items where cart_id=$cart_id and cart_item_id=$cart_item_id",$row_rtn);
		$actual_amt = $row_rtn["total"];
		$refund_amt = $actual_amt;
		
		$fld_arr_ref["ord_id"] = $row_canc["ord_id"];
		$fld_arr_ref["user_id"] = $row_canc["user_id"];
		$fld_arr_ref["sup_id"] = $sup_id;
		$fld_arr_ref["cart_id"] = $cart_id;
		$fld_arr_ref["cart_item_id"] = $cart_item_id;
		$fld_arr_ref["ord_no"] = $ord_no;
		$fld_arr_ref["pay_status"] = $rc["pay_status"];
		
	if($rc["pay_status"]=='Paid'){
		$fld_arr_ref["refund_amt"] = $refund_amt;	
	}else{
		$cancel_amount = $refund_amt;
		$fld_arr_ref["cancel_amount"] = $cancel_amount;
	}	
		
		$qry = func_insert_qry("track_refund",$fld_arr_ref);
		execute_qry($qry);
	
	 if($row_rtn["item_buyer_action"]=='Returned'){
		 
	   get_rst("select cart_price,actual_ship_amt from ord_items where cart_item_id=$cart_item_id",$row_rtn);
	   $amt_return = $row_rtn["cart_price"];
	   if($row_rtn["actual_ship_amt"] <> ""){
		   $amt_return = $amt_return - $row_rtn["actual_ship_amt"];
	   }
	   $actual_return = $amt_return;
	   get_rst("select pay_total from invoice_mast where ord_no=(select ord_no from ord_summary where cart_id=$cart_id and sup_id=(select sup_id from ord_items where cart_item_id=$cart_item_id))",$row_r);
	   
	   $return_amt =  $row_r["pay_total"] - $actual_return;
	   
	   $fld_arr_inv["pay_total"] = $return_amt;
	   
	   get_rst("select ord_no from ord_summary where cart_id=$cart_id and sup_id=(select sup_id from ord_items where cart_item_id=$cart_item_id)",$row_inv);
	   
	   $qry2 = func_update_qry("invoice_mast",$fld_arr_inv,"where ord_no='".$row_inv["ord_no"]."'");
	   execute_qry($qry2);
   }
	}
	if($rc["pay_status"] == 'Paid'){
	send_refund_mail($cart_id,$cart_item_id,$coupon_ref,$sup_id,$actual_return);
	}else{
		get_rst("select bill_name,bill_tel from ord_details where cart_id=$cart_id",$row);
		if($row_id["coupon_id"] == ""){
			$sms_body = "Dear ".$row["bill_name"].", <br> your order $ord_no with amount Rs.$cancel_amount has been cancelled";
			send_sms($row["bill_tel"],$sms_body);
		}else{
			$sms_body = "Dear ".$row["bill_name"].", <br> your order $ord_no with amount Rs.$cancel_amount and coupon deduction Rs.$cancel_coupon has been cancelled";
			send_sms($row["bill_tel"],$sms_body);
		}
	}
}

function send_refund_mail($cart_id,$cart_item_id,$coupon_ref,$sup_id,$actual_return,$ord_no){
	$fld_arr = array();
	$fld_sum_arr = array();
	$fld_item_arr = array();
	$body_details = "";
	$body_sums = "";
	$body_items = "";
	$bill_email = "";
	$bill_fname = "";
	$bill_tel = "";
	$ord_no = "";
	$body_supplier = "";
	get_rst("select Admin_email from configuration",$row_em);
		$super_admin_email = $row_em["Admin_email"];
	
	$cc=" Company-Name <".$super_admin_email.">";
	
	get_rst("select buyer_action from ord_summary where cart_id=$cart_id and sup_id=$sup_id",$rwc);
	
	get_rst("select item_buyer_action from ord_items where cart_item_id=$cart_item_id",$row_item);
	$item_buyer_action = $row_item["item_buyer_action"];
	
	if(get_rst("select * from ord_summary where cart_id=(select cart_id from ord_items where cart_item_id=$cart_item_id) and sup_id=$sup_id",$row_s,$rst_s)){
		$cart_id = $row_s["cart_id"];
		foreach($row_s as $key => $value) {
			$fld_arr[$key] = $value;
		}
	}	
	
	$ord_pay_total = 0;
	
	if($cart_id <> ""){
		if(get_rst("select * from ord_details where cart_id=".$cart_id,$row)){
			foreach($row as $key => $value) {
				$fld_arr[$key] = $value;
			}
			$bill_email=$row["bill_email"];
			$bill_fname=$row["bill_name"];
			$bill_tel=$row["bill_tel"];
		}
		
		do{
			$ord_no = $row_s["ord_no"];
			$body_supplier = "";
			foreach($row_s as $key => $value) {
				$fld_sum_arr[$key] = $value;
			}
			$sup_id = $row_s["sup_id"];
			$ord_pay_total = $ord_pay_total + $row_s["ord_total"];
			$body_items = "";
			$body_items_sup = "";

			if(get_rst("select * from ord_items where cart_id=$cart_id and cart_item_id=$cart_item_id and sup_id=$sup_id",$row,$rst)){
				if(get_rst("select sup_company from sup_mast where sup_id=$sup_id",$rw)){
					$fld_item_arr["sup_name"] = $rw["sup_company"];
				}
				do{				
					foreach($row as $key => $value) {
						$fld_item_arr[$key] = $value;
					}				
					$fld_item_arr["prod_url"] = get_base_url()."/prod_details.php?prod_id=".$row["prod_id"];
					$fld_item_arr["item_thumb"] = show_img($row["item_thumb"]);
					if($row["tax_percent"].""<>""){
						$fld_item_arr["tax_percent"] = $row["tax_percent"]."%";
					}else{
						$fld_item_arr["tax_percent"] = "";
					}
					
					if($row["ship_amt"]==0){
						$fld_item_arr["shipping_charges"] = "Free";
					}else{
					$fld_item_arr["shipping_charges"] = $row["ship_amt"];
					}
					$fld_item_arr["total_amount"] = (floatval($row["cart_price_tax"]) * intval($row["cart_qty"]))+(intval($row["cart_qty"]) * floatval($row["ship_amt"]));
					
					get_rst("select refund_amt from track_refund where cart_id=$cart_id and cart_item_id=$cart_item_id",$rww);
					$fld_item_arr["refund_amt"] = $rww["refund_amt"];
					
					$body_item = push_body("ord_refund_item.txt",$fld_item_arr);
					$body_items = $body_items.$body_item;
					if($item_buyer_action == 'Returned'){
						$body_item_sup = push_body("ord_return_items_sup.txt",$fld_item_arr);
						$body_items_sup = $body_items_sup.$body_item_sup;
					}
				}while($row = mysqli_fetch_array($rst));
				
				$fld_sum_arr["ord_no"]=$ord_no;
				$fld_sum_arr["ord_date"]=$fld_arr["ord_date"];
				
				if($item_buyer_action == 'Returned'){
					$fld_sum_arr["ord_return_items_sup"] = $body_items_sup;
					
					get_rst("select cart_price,actual_ship_amt from ord_items where cart_item_id=$cart_item_id",$row_price);
					$fld_sum_arr["cart_price"] = $row_price["cart_price"];
					
					get_rst("select pay_total from invoice_mast where sup_id=$sup_id and ord_no='$ord_no'",$rw_pay);
					$fld_sum_arr["return_amt"] = $rw_pay["pay_total"];
					
					if($row_price["actual_ship_amt"] <> ""){
						$fld_sum_arr["actual_ship_amt"] = $row_price["actual_ship_amt"];
					}else{
						$fld_sum_arr["actual_ship_amt"] = 0.00;
					}
					
					$body_sum_sup = push_body("ord_return_summary_sup.txt",$fld_sum_arr);
					$body_sum_sup = $body_sums_sup.$body_sum_sup;
					$fld_arr["ord_return_summary_sup"] = $body_sum_sup;
				}
				get_rst("select coupon_id from ord_coupon where cart_id=$cart_id",$row_id); 
				get_rst("select ((cart_price_tax * cart_qty) + ship_amt) as actual_amount from ord_items where cart_item_id=$cart_item_id",$row_ref);
				if($row_id["coupon_id"] <> ""){
					
					$fld_sum_arr["actual_amount"] = $row_ref["actual_amount"];
					$fld_sum_arr["coupon_deductables"] = formatNumber($coupon_ref,2);
					$fld_sum_arr["total_refund"] = $fld_item_arr["refund_amt"];
				}else{
					$fld_sum_arr["actual_amount"] = $row_ref["actual_amount"];
					$fld_sum_arr["coupon_deductables"] = 0.00;
					$fld_sum_arr["total_refund"] = $fld_item_arr["refund_amt"];
				}
				$fld_sum_arr["ord_no"]=$ord_no;
				$fld_sum_arr["ord_date"]=$fld_arr["ord_date"];
				
				$fld_sum_arr["ord_refund_item"] = $body_items;
				$body_sum = push_body("ord_refund_summary.txt",$fld_sum_arr);
			}
			$fld_arr["ord_pay_total"] = $row_s["ord_total"];

			if($item_buyer_action == 'Returned'){
				$fld_arr["ord_return_summary_sup"] = $body_sum_sup;
				$body_supplier = push_body("ord_return_details_sup.txt",$fld_arr);
				
				if(get_rst("select sup_email,sup_contact_no, sup_company from sup_mast where sup_id=$sup_id",$row)){
					$sup_email = $row["sup_email"];
					$sup_tel = $row["sup_contact_no"];
					$sup_company = $row["sup_company"];
					send_mail($sup_email,"Company-Name - Order Return(Seller): $ord_no",$body_supplier,"",$cc);
				}
			}
			//send sms to buyer
					$fld_sms_arr = array();
					$fld_sms_arr["ord_no"] = $ord_no;
					$fld_sms_arr["memb_fname"] = $bill_fname;
					$fld_sms_arr["refund_amt"] = $fld_item_arr["refund_amt"];
					$sms_msg = push_body("sms_ord_refund.txt",$fld_sms_arr);
					send_sms($bill_tel,$sms_msg);
					
			$fld_arr["ord_refund_summary"] = $body_sum;
		}while($row_s = mysqli_fetch_assoc($rst_s));

		$fld_arr["ord_refund_item"] = $body_items.$body_items;
		$fld_arr["ord_refund_summary"] = $body_sum;
		$fld_arr["ord_pay_total"] = $ord_pay_total;
		
		$body_details = push_body("ord_refund.txt",$fld_arr);
		send_mail($bill_email,"Company-Name - Order Amount Refund: $ord_no",$body_details,"",$cc);
    }
  
}

function img_resize_db($target, $w=0, $h=0, $ext="jpg") {
	if(file_exists($target)){

		list($w_orig, $h_orig) = getimagesize($target);
	//	print_r($w_orig."height".$h_orig);die;
		$scale_ratio = $w_orig / $h_orig;
		
		

		If(intval($w_orig) > intval($w) or intval($h_orig) > intval($h)){
			if($w==0){
				$w = $h * $scale_ratio;
			}elseif($h==0){
				$h = $w / $scale_ratio;
			}else{	
				if (($w / $h) > $scale_ratio) {
					   $w = $h * $scale_ratio;
				} else {
					   $h = $w / $scale_ratio;
				}
			}
		}else{
			$w= $w_orig;
			$h= $h_orig;
		}
		
		//print_r($target);die;
		$img = "";
		$ext = strtolower($ext);
		if ($ext == "gif"){ 
		  $img = imagecreatefromgif($target);
		} else if($ext =="png"){ 
		  $img = imagecreatefrompng($target);
		} else { 
		  $img = imagecreatefromjpeg($target);
		}
		$tci = imagecreatetruecolor($w, $h);
		// imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
		imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
		
		$temp_path = $_SERVER['DOCUMENT_ROOT']."/extras/user_data/thumb.".$ext;
		//	print_r($temp_path);die;
		//ob_start();
		imagejpeg($tci, $temp_path , 80);
		//$img_file=ob_get_clean();
		//die;
		return file_get_contents($temp_path);
		//return $img_file;
	}else{
		return "";
	}
}

function img_upload_db($obj_id,$target_dir,&$img_name,&$img_thumb,$w="1200",$h="500",$thumb="1"){
	
	$target_file = $target_dir . basename($_FILES[$obj_id]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	$msg="1";
	// Check if(image file is a actual image or fake image
	
	if($_FILES[$obj_id]["name"]<>"") {
		$check = getimagesize($_FILES[$obj_id]["tmp_name"]);
		if($check !== false) {
			$msg =  "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			$msg =  "File is not an image.";
			$uploadOk = 0;
		}
		
		if($_FILES[$obj_id]["size"] > 800 * 1024) {
			$msg =  "Sorry, your file is too large. Please upload files within 800kb only.";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
			$msg =  "Sorry, only JPG, JPEG, PNG & Gif(files are allowed.";
			$uploadOk = 0;
		}
		
		if($uploadOk == 0) {
			// if(everything is ok, try to upload file
		} else {
			$target_file = $_FILES[$obj_id]["tmp_name"];
			//if(move_uploaded_file($_FILES[$obj_id]["tmp_name"], $target_file)) {
			$msg =  "1"; //"The file ". basename( $_FILES[$obj_id]["name"]). " has been uploaded.";
			$img_name = $_FILES[$obj_id]["name"];
			
			$img_name = img_resize_db($target_file, $w, $h, $imageFileType);
			
			if($thumb=="1"){
				$img_thumb = img_resize_db($target_file, 180, 200, $imageFileType);
			}

		}
		
	}
	return $msg;
}

function func_update_qry($table,$fld_arr,$where){
	$qry = "update $table set |";

	$m = array('jan'=>1, 'feb'=>2, 'mar'=>3, 'apr'=>4, 'may'=>5, 'jun'=>6, 'jul'=>7, 'aug'=>8, 'sep'=>9, 'oct'=>10, 'nov'=>11, 'dec'=>12);

	foreach($fld_arr as $fld => $val){
		if(strpos($fld,"_date")){
			if($val=="now()"){
				$qry .= ", $fld=$val";
			}else{
				//echo($val."<hr>");
				$date_arr = explode("-", $val);
				$date_arr[1] = $m[strtolower($date_arr[1])];
				$val = "$date_arr[2]-$date_arr[1]-$date_arr[0]";
				if($val=="--"){
					$qry .= ", $fld=\N";
				}else{
					$qry .= ", $fld='$val'";
				}
			}
		}else{
			if($val."x"=="x"){
				$qry .= ", $fld=\N";
			}else{
				$val = addslashes($val);
				$qry .= ", $fld='$val'";
			}
		}
	}
	$qry = str_replace("|,","",$qry);
	$qry .= " $where";
	
	return $qry;
}

function func_update_qry1($table,$fld_arr,$where){
	$qry = "update $table set |";

	$m = array('jan'=>1, 'feb'=>2, 'mar'=>3, 'apr'=>4, 'may'=>5, 'jun'=>6, 'jul'=>7, 'aug'=>8, 'sep'=>9, 'oct'=>10, 'nov'=>11, 'dec'=>12);

	foreach($fld_arr as $fld => $val){
		/* if(strpos($fld,"_date")){
			if($val=="now()"){
				$qry .= ", $fld=$val";
			}else{
				//echo($val."<hr>");
				$date_arr = explode("-", $val);
				$date_arr[1] = $m[strtolower($date_arr[1])];
				$val = "$date_arr[2]-$date_arr[1]-$date_arr[0]";
				if($val=="--"){
					$qry .= ", $fld=\N";
				}else{
					$qry .= ", $fld='$val'";
				}
			}
		}else{ */
			if($val."x"=="x"){
				$qry .= ", $fld=\N";
			}else{
				$val = addslashes($val);
				$qry .= ", $fld='$val'";
			}
		//}
	}
	$qry = str_replace("|,","",$qry);
	$qry .= " $where";
	
	return $qry;
}

function get_base_url(){
	return "http://".$_SERVER["SERVER_NAME"];	//.":".$_SERVER['SERVER_PORT'];
}

function replace($txt ,$find ,$replace){
	return str_replace($find,$replace,$txt);
}

function push_body($file_name,$fld_arr,$html_format=""){
	$file = fopen(__DIR__."/../../emails/".$file_name, "r") or die("not found");
	$count = 0;
	$line = "";
	$mail_body = "";
	
	while(!feof($file) AND $count<1000) {
	  $line = fgets($file);
	  
	  foreach($fld_arr as $fld => $val){
		$line = replace($line,"[$fld]",$val);
	  }
	  
	  $mail_body = $mail_body.$line;
	  if($html_format=="1"){
		$mail_body = $mail_body."<br>";
	  }
	  $count++;
	}
	fclose($file);
	return $mail_body;
}

function get_max($TableName,$fieldName){
	global $con;
	$sqlMax = "select IFNULL(max($fieldName),0)+1 as max_id from $TableName";
//	echo $sqlMax;die;
	$rstMax= mysqli_query($con, $sqlMax);
	$row = mysqli_fetch_assoc($rstMax);
	
	return $row["max_id"];
}

function func_read_qs($key){
	//echo("|$key|");
	if(isset($_GET[$key])){
		return addslashes($_GET[$key])."";
	}elseif(isset($_POST[$key])){
		return addslashes($_POST[$key])."";
	}else{
        return "";
	} 
}

function get_act_id($id){
	return sha1(mt_rand(10000,99999).time().$id);
}

function send_sms($tele,$sms_msg){
	//authentication key
	//return "";
	global $enable_sms;
	if($enable_sms == false){ return 200;}
	$authKey = "97350AP8uE8No9Gv564866b9";

	//Multiple mobiles numbers separated by comma
	$mobileNumber = "91".$tele;

	//Sender ID,While using route4 sender id should be 6 characters long.
	$senderId = "ALFMRT";

	//Your message to send, Add URL encoding here.
	$message = urlencode($sms_msg);

	//Define route 
	$route = "4";
	//Prepare you post parameters
	$postData = array(
	'authkey' => $authKey,
	'mobiles' => $mobileNumber,
	'message' => $message,
	'sender' => $senderId,
	'route' => $route
	);

	//API URL
	$url="http://api.msg91.com/sendhttp.php";

	// init the resource
	$ch = curl_init();
	curl_setopt_array($ch, array(
	CURLOPT_URL => $url,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_POST => true,
	CURLOPT_POSTFIELDS => $postData
	//,CURLOPT_FOLLOWLOCATION => true
	));


	//Ignore SSL certificate verification
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


	//get response
	$output = curl_exec($ch);

	//Print error if any
	if(curl_errno($ch))
	{
	//echo 'error:' . curl_error($ch);
	}

	curl_close($ch);
	return $output;
}

function execute_qry($sql){
	global $con;
	if(!mysqli_query($con, $sql)){
		//die(mysqli_error($con));
	//	write_log(ERR, "Problem updating database... ".mysqli_error($con)."SQL query: ".$sql);
	?>
	<div class="alert alert-info">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <? echo "Oops! There is some problem updating your record. Please try again after some time."?>
	</div>	
	<?php
	}else{
		return mysqli_insert_id($con);
	}
}

function func_insert_qry($table,$fld_arr){
	$qry = "insert into $table (";
	
	foreach($fld_arr as $fld => $val){
		$qry .= ",$fld";   
	}
	$qry .= ") values (";

	$m = array('jan'=>1, 'feb'=>2, 'mar'=>3, 'apr'=>4, 'may'=>5, 'jun'=>6, 'jul'=>7, 'aug'=>8, 'sep'=>9, 'oct'=>10, 'nov'=>11, 'dec'=>12);

	foreach($fld_arr as $fld => $val){
		if(strpos($fld,"_date")){
			$date_arr = explode("-", $val);
			$date_arr[1] = $m[strtolower($date_arr[1])];
			$val = "$date_arr[2]-$date_arr[1]-$date_arr[0]";
		}
		if($val."x"=="x"){
			$qry .= ",\N";
		}else{
			$val = addslashes($val);
			$qry .= ",'$val'";
		}
	}
	$qry = str_replace("(,","(",$qry);
	$qry .= ")";

	return $qry;
}

function get_shipping_charges($sup_pincode, $memb_pincode, $weight){
	$service_tax = 0.04;
	$fuel_surcharge = 0.25;
	
	/* if($_SESSION["prod_weight"] <> ""){
		$weight = $_SESSION["prod_weight"];
	} */
	//print_r($sup_pincode."pin :".$memb_pincode."weight:".$weight);
	$shipping_addn = 0;
	$zone = "";
	if(substr($sup_pincode,0,3) == substr($memb_pincode,0,3)){
	//withing city ship charges
		$zone = "WITHINCITY";
	}elseif(getDistance($sup_pincode, $memb_pincode) < 500){
		//Regional-within 500km charges
		$zone = "WITHIN500";
	}else{
		//zonal charges METRO/ROI/NE
		$sql = "select zone from city_mast where pincode LIKE '".substr($memb_pincode,0,3)."%' LIMIT 1";
		if(get_rst($sql,$row_s)){		
			$zone = $row_s["zone"];
		}
	}
	$weight_factor = 0;
	if($weight > 0.5){
		$weight_factor = intval($weight / 0.5) - 1;
	}
	
	$sql = "select ship_min_charge, ship_addn_500 from ship_mast where ship_zone='$zone'";
	if(get_rst($sql,$row_s)){		
		$shipping_charges = $row_s["ship_min_charge"] + ($weight_factor * $row_s["ship_addn_500"]);
		$shipping_charges = $shipping_charges + ($shipping_charges * ($service_tax + $fuel_surcharge)); 
	}
	return $shipping_charges;
}		
	
function getDistance($zip1, $zip2){
	$first_lat = getLnt($zip1);
	$next_lat = getLnt($zip2);
	$lat1 = $first_lat['lat'];
	$lon1 = $first_lat['lng'];
	$lat2 = $next_lat['lat'];
	$lon2 = $next_lat['lng']; 
	$theta=$lon1-$lon2;
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
	cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
	cos(deg2rad($theta));
	$dist = acos($dist);
	$dist = rad2deg($dist);
	$miles = $dist * 60 * 1.1515;
	return round($miles * 1.609344, 0);
}

function getLnt($zip){
	$url = "http://maps.googleapis.com/maps/api/geocode/json?address=
	".urlencode($zip)."&sensor=false";
	$result_string = file_get_contents($url);
	$result = json_decode($result_string, true);
	$result1[]=$result['results'][0];
	$result2[]=$result1[0]['geometry'];
	$result3[]=$result2[0]['location'];
	return $result3[0];
}

function get_next($table,$fld){
	execute_qry("UPDATE $table SET $fld=LAST_INSERT_ID($fld+1)");
    get_rst("SELECT $fld from $table",$row);
	
	return $row[$fld];
}

function get_rst($sql,&$row="",&$rst="",$get_arr=""){

	global $con;
	//print_r($sql." <br/>");
	$rst = mysqli_query($con,$sql);
	if (mysqli_errno($con) <>0) {
		//write_log(ERR, "Problem selecting records... ".mysqli_error($con)."SQL Query: ".$sql);
		echo "Problem selecting records... ";
		?>
	<div class="alert alert-info">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<? echo "Oops! There is something went wrong. Please try again after some time. "; ?>
	</div>	
	<?php
		return false;
	}
	
	if($rst){
		if($get_arr=="1"){
			$row = mysqli_fetch_array($rst);
		}else{
			$row = mysqli_fetch_assoc($rst);
		}
	}else{
		return false;
	}
	
	if($row){
		return true;
	}else{
		return false;
	}
	
}

function send_mail($to,$subject,$body,$from="noreply@Company-Name.com",$cc="",$bcc=""){
	return xsend_mail($to, $subject, $body, $from);
}

function xsend_mail($to,$subject,$body, $from="noreply@Company-Name.com"){
//	print_r($_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/PHPMailerAutoload.php');die;
    require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/PHPMailerAutoload.php');
    global $env_prod;
    $mail       = new PHPMailer();
    $mail->IsSMTP(true);          
	$mail->IsHTML(true);
	$mail->SetFrom($from, 'Company-Name');
	$mail->Subject    = $subject;
	$mail->MsgHTML($body);
	$address = $to;
	$mail->AddAddress($address);
	//$mail->SMTPDebug = 4; 	//enable error log to see the cause of email failure.
	if($env_prod){
		//$mail->Host       = "localhost"; // SMTP host
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->Host       = "smtpout.asia.secureserver.net"; // SMTP host
		$mail->Port       =  80;                    // set the SMTP port
		$mail->Username   = "welcome@Company-Name.com";  // SMTP  username
		$mail->Password   = "alifwel@123";  // SMTP password
		//return $mail->Send();
	}else{	//mail server configuration for test environment
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->SMTPSecure = 'ssl';
		$mail->Host       = "smtp.gmail.com"; // SMTP host
		$mail->Port       =  465;                    // set the SMTP port
		$mail->SetFrom("Company-Name.test@gmail.com", 'Company-Name');
		$mail->Username   = "Company-Name.test@gmail.com";  // SMTP  username
		$mail->Password   = "aliftest@123";  // SMTP password
	}
	if($mail->Send()){
		return true;
	} else{
		//write_log(ERR, "Fail - " . $mail->ErrorInfo);
	//	echo "Fail";
		return false;
	}	
}

//Verify coupon code for buyer
function verify_offer_coupon($memb_id, $cart_id, $coupon_code, $ord_amt){
	$cur_date = Date("m/d/Y");
	$response = "";
	$qry = "select coupon_id, disc_per, valid_from, valid_till, min_ord_value, memb_id from offer_coupon where coupon_code='$coupon_code' and active=1";
	if(get_rst($qry, $row)){
		$coupon_id = $row["coupon_id"];
		$min_ord_value = $row["min_ord_value"];
		$disc_per = $row["disc_per"];
		$valid_from = $row["valid_from"];
		$valid_till = $row["valid_till"];
		if($memb_id <> $row["memb_id"] && $row["memb_id"]<>"")
		{
			$response = "Invalid coupon!";
			return $response;
		}
		if(strtotime($cur_date) < strtotime($from_date) || strtotime($cur_date) < strtotime($from_date)){
			$response = "Invalid coupon! Offer validity expired";
			return $response;
		}
		
		if($ord_amt < $min_ord_value){
			$response = "Invalid coupon! Offer is valid on a minimum order of Rs. $min_ord_value  (excluding tax and shipping charges)";
			return $response;
		}
		
		$qry = ""."select * from memb_coupon where coupon_id=$coupon_id and memb_id=$memb_id";
		if(get_rst($qry, $row)){
			$response = "Invalid coupon! It is a one time offer and it has already been applied.";
			return $response;
		}	
		
		$qry = ""."select * from ord_coupon where cart_id=$cart_id";
		if(get_rst($qry, $row)){
			$response = "Invalid coupon! Only one coupon can be applied per order.";
			return $response;
		}
		return $disc_per;
	}
	return $response;
}

function remove_item_from_cart_summary($sup_id, $cart_id, $prod_id){
	$qry = "select cart_qty, sum(cart_qty * cart_price) as item_total,count(1) as item_count,sum(cart_qty * tax_value) as tax_total,sum(ship_amt) as shipping_charges from cart_items where prod_id=$prod_id and cart_id=$cart_id and sup_id=$sup_id group by sup_id";		
	
	if(get_rst($qry,$row,$rst)){
		get_rst("select item_count, item_total, vat_value, shipping_charges, ord_total from cart_summary where cart_id=$cart_id and sup_id=$sup_id",$row_sum);
		$fld_arr = array();
		$fld_arr["item_count"] = $row_sum["item_count"] - $row["item_count"];
		$fld_arr["item_total"] = $row_sum["item_total"] - $row["item_total"];

		$fld_arr["vat_value"] = $row_sum["tax_total"] - $row["tax_total"];
		$fld_arr["shipping_charges"] = 0;
		$fld_arr["shipping_charges"] = $row_sum["shipping_charges"] - $row["shipping_charges"];

		$fld_arr["ord_total"] = $row_sum["ord_total"] - (floatval($row["item_total"]."") + floatval($fld_arr["shipping_charges"]) + floatval($fld_arr["vat_value"]));
		
		if($fld_arr["item_count"] == 0){
			$sql = "delete from cart_summary where cart_id=$cart_id and sup_id=$sup_id";
		}else{
			$sql = func_update_qry("cart_summary",$fld_arr," where cart_id=$cart_id and sup_id=$sup_id");
		}
		execute_qry($sql);
	}
	if(get_rst("select coupon_id from ord_coupon where cart_id='".$cart_id."'", $row_c)){   //if change in cart delete coupon...
		execute_qry("delete from ord_coupon where coupon_id='".$row_c["coupon_id"]."' and cart_id='".$cart_id."'");
	}
}

function send_inquiry($buyer_name,$buyer_email,$buyer_mobile,$prod_name,$prod_sku){
		global $sales;
		$msg = "Hi,<br><br>";
		$msg .= "An enquiry of a registered product has been recieved with the following details:<br><br>";
		$msg .= "Product SKU: $prod_sku <br>";
		$msg .= "Product Name: $prod_name <br>";
		$msg .= "Buyer Name: $buyer_name <br>";
		$msg .= "Buyer Email: $buyer_email <br>";
		$msg .= "Buyer Mobile No.: $buyer_mobile <br><br>";
		$msg .= "-- Company-Name Sales";
		xsend_mail($sales,"Company-Name - Registered product inquiry",$msg,"noreply@Company-Name.com" );
		$msg = "Dear $buyer_name,<br><br>";
		$msg .= "Thank You for placing an inquiry for the product with the following details to Company-Name. Our sales team has begun on it, and we will connect you with the best seller for the required product.<br><br>";
		$msg .= "Product SKU: $prod_sku <br>";
		$msg .= "Product Name: $prod_name <br><br>";
		$msg .= "Best Regards, <br>";
		$msg .= "Company-Name Sales Team";
		xsend_mail($buyer_email,"Company-Name - Registered product inquiry",$msg,"sales@Company-Name.com" );
}

?>