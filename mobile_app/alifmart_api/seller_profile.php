<?php

include("db.php");


$sup_id=addslashes($_GET['sup_id']);
$sql="select * from sup_mast where sup_id=$sup_id";
//print_r($sql);die;
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 
$seller_profile['seller_profile']=array();

	if($rs_find){
		
		if($row = mysqli_fetch_assoc($rs_find)){
			
			//$row["sup_company"];
			$temp['sup_company'] = $row["sup_company"];
			$temp['sup_business_type'] = $row["sup_business_type"];
			$temp['sup_contact_name'] = $row["sup_contact_name"];
			$temp['sup_contact_per_name'] = $row["sup_contact_per_name"];
			$temp['sup_email'] = $row["sup_email"];
			$temp['sup_pan'] = $row["sup_pan"];
			$temp['sup_vat'] = $row["sup_vat"];
			$temp['sup_lmd'] = $row["sup_lmd"];
			$temp['sup_cstn'] = $row["sup_cstn"];
			$temp['sup_bk_acc'] = $row["sup_bk_acc"];
			$temp['sup_bk_ifsc'] = $row["sup_bk_ifsc"];
			$temp['sup_bk_name'] = $row["sup_bk_name"];
			$temp['sup_bk_brname'] = $row["sup_bk_brname"];
			$temp['sup_contact_no'] = $row["sup_contact_no"];
			$temp['sup_alt_contact_no'] = $row["sup_alt_contact_no"];
			
			$sup_imp_exp_license=base64_encode($row["sup_imp_exp_license"]);
			$sup_dlr_doc=base64_encode($row["sup_dlr_doc"]);
			$sup_iso_doc=base64_encode($row["sup_iso_doc"]);
		
			if(strlen($sup_imp_exp_license)>0)
			{
				$temp['sup_imp_exp_license'] = 1;
			}
			else
			{
				$temp['sup_imp_exp_license'] = 0;
			}
			
			if(strlen($sup_dlr_doc)>0)
			{
				$temp['sup_dlr_doc'] = 1;
			}
			else
			{
				$temp['sup_dlr_doc'] = 0;
			}
			
			if(strlen($sup_iso_doc)>0)
			{
				$temp['sup_iso_doc'] = 1;
			}
			else
			{
				$temp['sup_iso_doc'] = 0;
			}
			
			
			$temp['sup_logo'] = base64_encode($row["sup_logo"]);
			$temp['sup_shop_act_license'] = base64_encode($row["sup_shop_act_license"]);
			$temp['sup_pan_doc'] = base64_encode($row["sup_pan_doc"]);
			$temp['sup_vat_doc'] = base64_encode($row["sup_vat_doc"]);
			$temp['sup_cst_doc'] = base64_encode($row["sup_cst_doc"]);
			$temp['sup_bk_can_chk'] = base64_encode($row["sup_bk_can_chk"]);
				
			//print_r($temp);die;
			$rst = mysqli_query($con,"select * from sup_ext_addr where sup_id=$sup_id LIMIT 1");
			$row2 = mysqli_fetch_assoc($rst);
		
			$temp['sup_ext_address'] = $row2["sup_ext_address"];
			$temp['sup_ext_state'] = $row2["sup_ext_state"];
			$temp['sup_ext_city'] = $row2["sup_ext_city"];
			$temp['sup_ext_pincode'] = $row2["sup_ext_pincode"];
			//print_r($row2);die;
			//print_r($temp);die;
			array_push($seller_profile['seller_profile'],$temp);
		}
		
	}
	array_walk_recursive($seller_profile,"convert_to_utf8");
		//print_r($seller_profile);die;
	echo json_encode($seller_profile);
?>