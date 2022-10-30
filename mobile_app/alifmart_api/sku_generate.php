<?php

include("db.php");

include("functions.php");

ini_set('display_errors', 1); 


		$level_parent=func_read_qs("level_parent");
		$level_name = func_read_qs("level_name");
		
		$cat = array();
		$cat = get_category_name($level_parent, $level_name);
		$category = $cat[0];
		$sub_category = $cat[1];
		$prod_brand_id=func_read_qs("brand_id");
		//print_r($level_parent." ".$level_name." ".$prod_brand_id);die;
		$brand_name = get_brand_name($prod_brand_id);
		$sku_code = generate_sku($category, $sub_category,$brand_name);
		$response['sku_code']=$sku_code; 
		
			echo json_encode($response);
	
?>