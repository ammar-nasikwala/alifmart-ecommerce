<?php 
		include("db.php");
		include("functions.php");

		ini_set('display_errors', 1);
		
		$prod_id = func_read_qs("prod_id");
		$buyer_name = func_read_qs("buyer_name");		
		$buyer_mobile = func_read_qs("buyer_mobile");		
		$buyer_email = func_read_qs("buyer_email");
		
		get_rst("select prod_name,prod_stockno from prod_mast where prod_id =$prod_id",$row);
		
		$prod_name = $row["prod_name"];
		$prod_sku = $row["prod_stockno"];
		
		send_inquiry($buyer_name,$buyer_email,$buyer_mobile,$prod_name,$prod_sku);	
		
		$response['status'] = "success";
?>