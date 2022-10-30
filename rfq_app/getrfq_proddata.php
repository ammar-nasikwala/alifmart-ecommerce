<?php

include("../lib/inc_connection.php");
include("../mobile_app/Company-Name_api/functions.php");

$rfqid = func_read_qs("rfqid");
$pid = func_read_qs("pid");


if(get_rst("select * from request_for_quotation where quotation_id='".$rfqid."'",$row)){
		$respond['customername'] = $row["customer_name"];
		$respond['mobile']= $row["contact_no"];
		$respond['email']= $row["customer_email"];
		$respond['description'] = $row["rfq_description"];
		$respond['rfqid'] = $rfqid;
		
}
if(get_rst("select * from prod_enquiry where quotation_id='".$rfqid."' and id='".$pid."'",$row1)){
		$respond['proddetail'] = $row1["prod_name"];
		$respond['brand'] = $row1["brand_name"];
		$respond['qty'] = $row1["item_qty"];
		$respond['price'] = $row1["item_price"];
		$respond['status']= $row1["rfq_status"];
		$respond['vendorname'] = $row1["vendorname"];
		$respond['pid'] = $pid;
		$respond['insertdate'] = $row1["insertdate"];
		$respond['image'] = $row1["image_path1"];
		$respond['owner'] = $row["owner_name"];
	
}
	echo json_encode($respond);
?>