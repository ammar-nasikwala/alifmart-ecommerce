<?php
require_once("../lib/inc_library.php");
	
if(func_read_qs("process")=="check_po_number"){
	$response = "Available";
	$po_no = func_read_qs("po_no");
	$po_no= str_replace(' ', '',$po_no);
	if(get_rst("select po_no from po_details where po_no='$po_no'",$row,$rst)){
		$response = "This Po number already used.";
	}else if(get_rst("select coupon_id from offer_coupon where coupon_code='$po_no'",$row,$rst)){
			$response = "This Po number already used.";
		}
	echo("<response>$response</response>");
	}
?>