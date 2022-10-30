<?php

include("../lib/inc_connection.php");
include("../mobile_app/Company-Name_api/functions.php");

get_rst("select * from request_for_quotation ORDER BY quotation_id DESC",$row,$rst);
 $listrfq['listrfq']=array();
 
 //$lmdseller=mysqli_query($con,"select sup_lmd from sup_mast where sup_id=$sup_id");
 //$row_lmd = mysqli_fetch_assoc($lmdseller);
 
		//while($row = mysqli_fetch_assoc($rs_find)){
		do{	
			$temp['rfq_id'] = $row["quotation_id"];
			$temp['description'] = $row["rfq_description"];
			$temp['owner'] = $row["owner_name"];
			$temp['date'] = $row["creation_date"];
			
			array_push($listrfq['listrfq'],$temp);
		}while($row=mysqli_fetch_array($rst)); 
		
		
	echo json_encode($listrfq); 
?>