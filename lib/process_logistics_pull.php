<?php

require("inc_library.php");

write_log(INFO, "L_P: Started processing logistics Pull mechanism");

$xml_p=simplexml_load_string(logistics_pull_req());

foreach($xml_p->Shipment as $fld => $val){
	$way_billl_no = $val->AWB;
	$logistics_status=$val->Status[0]->Status;
	
	write_log(INFO, "L_P: way_billl_no=$way_billl_no, logistics_status=$logistics_status");	
	
	$delivery_status="";
	$sql_free = "";
	if($logistics_status=="DTO" or $logistics_status=="Delivered"){
		$delivery_status = "delivery_status='Delivered',";
		$sql_free = "update ord_waybill set in_use=0 where way_bill_no='$way_billl_no'";
		execute_qry($sql_free);
		get_rst("select sup_id,user_id,ord_date,ord_no,delivery_intimation_flag from ord_summary where way_billl_no='$way_billl_no'",$row_ids);//to get value related to order
		if($row_ids["delivery_intimation_flag"]==1)
		{
			$sql_update = "update ord_summary set delivery_intimation_flag=0 where way_bill_no='$way_billl_no'";//updating flag
			execute_qry($sql_update);
			$sup_id=$row_ids["sup_id"];
			$memb_id=$row_ids["user_id"];
			$ord_date=$row_ids["ord_date"];
			$ord_no=$row_ids["ord_no"];
			
			get_rst("select sup_email,sup_contact_no,sup_company from sup_mast where sup_id=$sup_id",$row_sup);	//to get seller and buyer details
			get_rst("select memb_email,memb_tel,memb_fname from member_mast where memb_id=$memb_id",$row_memb);
			$sup_email=$row_sup["sup_email"];
			$sup_contact_no=$row_sup["sup_contact_no"];
			$sup_company=$row_sup["sup_company"];
			$memb_email=$row_memb["memb_email"];
			$memb_tel=$row_memb["memb_tel"];
			$memb_fname=$row_memb["memb_fname"];
			
			$from="orders@Company-Name.com";			//sending mail to seller
			$body_s="Dear $sup_company,<br>";
			$body_s.="The order with Order ID $ord_no placed on $ord_date having WB $way_billl_no has been successfully delivered to the buyer. Kindly let us know your feedback about the experience by using our Android/iOS App or by logging to your Seller Dashboard. Looking forward for your continuous support. <br>";
			$body_s.="<br><br>Thanks & Regards<br>Company-Name.com ";			
			xsend_mail($sup_email,"Company-Name - Order Update",$body_s,$from );
			
			//sending SMS to seller
			$sms_seller = "Company-Name Order Update: The order ID $ord_no ordered on $ord_date having WB $way_billl_no has been successfully delivered to the Buyer. Please share your feedback on our App or by logging to your Seller Dashboard. Looking forward for your continuous support.";
			$output = send_sms($sup_contact_no,$sms_seller);
			
			$from="orders@Company-Name.com";			//sending mail to buyer
			$body="Dear $memb_fname,<br>";
			$body.="Your order with Order ID $ord_no placed with us on $ord_date has been successfully delivered to your delivery address selected while ordering. Kindly let us know your feedback on the experience by using our Android/iOS App or by logging to our site. <br>";
			$body.="<br>Company-Name.com - Industrial Shopping Redefined. Thanks for your patronage & keep shopping for all your Industrial needs. ";
			$body.="<br>Do not forget to check on latest Products being added to our site & our Bulk order offers & amazing discounts. ";
			$body.="<br><br>Thanks & Regards<br>Company-Name.com ";		
			xsend_mail($memb_email,"Company-Name - Order Update",$body,$from );
			
			//sending SMS to buyer...
			$sms_buyer = "Company-Name Order Update: Your order ID $ord_no ordered on $ord_date has been successfully delivered to your selected address. Please share your feedback on our App or by logging to our site Company-Name.com. Thanks for your patronage & keep shopping for all your Industrial needs.";
			$output = send_sms($memb_tel,$sms_buyer);
		}
	}
	$sql = "update ord_summary set $delivery_status logistics_status='$logistics_status' where way_billl_no='$way_billl_no'";
	//echo($sql."<br>");
	try{
		execute_qry($sql);
		write_log(INFO, "L_P: way_billl_no=$way_billl_no updated");
	}
	catch(Exception $e){
		write_log(ERR, $e->getMessage());
	}
}

?>