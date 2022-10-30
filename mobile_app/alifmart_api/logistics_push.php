<?php

ini_set('display_errors', 1); 

//include("functions.php");

//Logistics order cancellation
function lgs_cancel_order($id, &$msg){
	
}


//Logistics order generation
function lgs_create_order($id,&$msg,$sup_id){
	$sup_id = $sup_id;
	try{
		if(get_rst("select * from ord_summary where ord_id=0$id",$row_s,$rst_s)){
			$cart_id = $row_s["cart_id"];
			if($row_s["way_billl_no"].""<>""){
				write_log("Info:", "Order already sent to logistics");
				$msg = "1";
			}
			$way_bill_no = "";
			if(get_rst("select way_bill_no from ord_waybill where in_use=0 LIMIT 0,1",$row)){
				$way_bill_no = $row["way_bill_no"];
			}else{
				write_log("Fatal Error:", "Logistics Push failed. No free waybill number available at the moment");
				$msg = "0";
			}
			//echo("way_bill_no: $way_bill_no <br>");
			if($msg == ""){
				if(get_rst("select * from ord_details where cart_id='".$cart_id."'",$row,$rst,"1")){

					$date = date_create($row_s["ord_date"]);
					$ord_date = date_format($date,'Y-m-dTH:i:s+00:00');
					
					$token = "eb0a01d91defdbde966d72f828983dc61c6d93b3"; 
					//$token = "44c3a88c0d4788d0cb0a90b22c13197e80381a07";
					//$username = "VARKALASILKSAREES";
					//$password = "delhivery123";
					
					//Client ID: VARKALASILKSAREES - DFS
					//Pick up : VARKALASILKSAREES - DFS
					
					//$url = "https://test.delhivery.com/api/p/createOrder/json/?token=$token";
					$url = "https://track.delhivery.com/cmu/push/json/?token=$token";

					$params = array(); // this will contain request meta and the package feed
					$package_data = array(); // package data feed
					$shipments = array();
					$pickup_location = array();

					/////////////start: building the package feed/////////////////////
					$shipment = array();
					$shipment['waybill'] = $way_bill_no; // waybill number
					$shipment['name'] = $row["ship_name"]; // consignee name
					$shipment['order'] = $row_s["ord_no"]; // client order number
					$shipment['products_desc'] = 'Hardware Tools';
					$shipment['order_date'] = $ord_date; // ISO Format
					$shipment['payment_mode'] = "Pre-paid";		//$row_s["pay_method"];
					$shipment['total_amount'] = $row_s["ord_total"]; // in INR
					$shipment['cod_amount'] = $row_s["ord_total"]; // amount to be collected, required for COD
					$shipment['add'] = $row["ship_add1"].", ".$row["ship_add2"]; // consignee address
					$shipment['city'] = $row["ship_city"];
					$shipment['state'] = $row["ship_state"];
					$shipment['country'] = $row["ship_country"];
					$shipment['phone'] = $row["ship_tel"];
					$shipment['pin'] = $row["ship_postcode"];
					$shipment['quantity'] = $row_s["item_count"]; // quanitity of quantity
					
					if(get_rst("select s.sup_company,s.sup_logistics_name, sa.sup_ext_address, sa.sup_ext_pincode, sa.sup_ext_state, s.sup_cstn, s.sup_contact_no, s.sup_city from sup_mast s join sup_ext_addr sa on s.sup_id=sa.sup_id  where s.sup_id=$sup_id LIMIT 1",$row_ss)){
						$shipment['seller_name'] = $row_ss["sup_company"]; //name of seller
						$shipment['seller_add'] = $row_ss["sup_ext_address"]; // add of seller
						$shipment['seller_cst'] = $row_ss["sup_cstn"]; //cst number of seller
						$shipment['seller_tin'] = "";  //tin number of seller
						$shipment['seller_inv']= $row_s["ord_no"]; // invoice number of shipment
						$shipment['seller_inv_date']= $ord_date; // ISO Format
						
						// pickup location information //
						$pickup_location['add'] = $row_ss["sup_ext_address"];
						$pickup_location['city'] = $row_ss["sup_city"];
						$pickup_location['country'] = 'India';
						$pickup_location['name'] = $row_ss["sup_logistics_name"];  // Use client warehouse name
						$pickup_location['phone'] = $row_ss["sup_contact_no"];
						$pickup_location['pin'] = $row_ss["sup_ext_pincode"];
						$pickup_location['state'] = $row_ss["sup_ext_state"];
					}
					
					$shipments = array($shipment);
										
					$package_data['shipments'] = $shipments;
					$package_data['pickup_location'] = $pickup_location;
					$params['format'] = 'json';
					$params['data'] =json_encode($package_data);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($params));
					//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
					//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
					curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));

					//curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					//curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
					//curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
					//curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
					//curl_setopt($curl, CURLOPT_ENCODING, "");
					
					$result = curl_exec($ch);
					$output = json_decode($result, true);
					if($output["success"]){
						execute_qry("update ord_waybill set ord_id=0$id, in_use=1 where way_bill_no=0$way_bill_no");
						execute_qry("update ord_summary set way_billl_no=0$way_bill_no where ord_id=0$id");
						$msg = "1";
					}
					else{
						$msg="0";
					}
					curl_close($ch);
				}
			}
		}
	}
	catch(Exception $e){
		$msg = "0";
	}	
}

?>
