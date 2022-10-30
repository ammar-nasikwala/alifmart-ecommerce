<?php

include("db.php");


$memb_id=addslashes($_GET['memb_id']);
$sql="select * from member_mast where memb_id=$memb_id";
//print_r($sql);die;
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 
$customer_profile['customer_profile']=array();

	if($rs_find){
		
		if($row = mysqli_fetch_assoc($rs_find)){
			
			//$row["sup_company"];
			$temp['memb_fname'] = $row["memb_fname"];
			$temp['memb_contact_per_name'] = $row["memb_contact_per_name"];
			$temp['memb_sname'] = $row["memb_sname"];
			$temp['memb_title'] = $row["memb_title"];
			$temp['memb_email'] = $row["memb_email"];
			$temp['memb_add1'] = $row["memb_add1"];
			$temp['memb_add2'] = $row["memb_add2"];
			$temp['memb_city'] = $row["memb_city"];
			$temp['memb_state'] = $row["memb_state"];
			$temp['memb_country'] = $row["memb_country"];
			$temp['memb_postcode'] = $row["memb_postcode"];
			$temp['memb_tel'] = $row["memb_tel"];
			$temp['memb_cst_doc'] = base64_encode($row["memb_cst_doc"]);
			$temp['memb_vat_doc'] = base64_encode($row["memb_vat_doc"]);
			$temp['memb_cstn'] = $row["memb_cstn"];
			$temp['memb_vat'] = $row["memb_vat"];
			$temp['memb_company'] = $row["memb_company"];
			$temp['ind_buyer'] = $row["ind_buyer"];
			$temp['memb_newsletter'] = $row["memb_newsletter"];
			
			array_push($customer_profile['customer_profile'],$temp);
		}
		
	}
		
	echo json_encode($customer_profile);
?>