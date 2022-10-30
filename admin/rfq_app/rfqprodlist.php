<?php

include("../lib/inc_connection.php");
include("../mobile_app/Company-Name_api/functions.php");

$rfqid = func_read_qs("rfqid");

get_rst("select * from prod_enquiry where quotation_id=$rfqid",$row,$rst);
get_rst("select owner_name from request_for_quotation where quotation_id=$rfqid",$rowrfq);
$owner=$rowrfq["owner_name"];

 $listrfq['rfqlist']=array();
 
 //$lmdseller=mysqli_query($con,"select sup_lmd from sup_mast where sup_id=$sup_id");
 //$row_lmd = mysqli_fetch_assoc($lmdseller);
 
		//while($row = mysqli_fetch_assoc($rs_find)){
		do{	
			$temp['rfq_no'] = $row["id"];
			$temp['rfq_proddetails'] = $row["prod_name"];
			$temp['rfq_owner'] = $owner;
			$temp['rfq_date'] = $row["insertdate"];
			$temp['rfq_status'] = $row["rfq_status"];
			$temp['rfq_id'] = $row["quotation_id"];
			$temp['prodimg'] = $row["image_path1"];
			
			array_push($listrfq['rfqlist'],$temp);
		}while($row=mysqli_fetch_array($rst)); 
		
		
	echo json_encode($listrfq); 
?>