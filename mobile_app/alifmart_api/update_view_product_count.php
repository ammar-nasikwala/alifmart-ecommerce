<?php

include("db.php");

include("functions.php");

ini_set('display_errors', 1); 

$prod_id = func_read_qs("prod_id");
//$act = func_read_qs("act");
$memb_id = func_read_qs("memb_id");

if(get_rst("select * from prod_viewed where prod_id=$prod_id and user_id=$memb_id",$row_s,$rst_s)){
	
		$qry2 = "update prod_viewed set view_count = view_count+1 where user_id=$memb_id and prod_id=$prod_id";
		
		execute_qry($qry2);
//	print_r($row_s);die;
}
else
{
	//print_r("in insert");die;
			$fld_s_arr["session_id"] = "mobile_app";
			$fld_s_arr["prod_id"] = $prod_id;
			$fld_s_arr["view_count"] = 1;
			$fld_s_arr["user_id"] = $memb_id;
			
			$sql = func_insert_qry("prod_viewed",$fld_s_arr);
			execute_qry($sql);
	
}
	
		$response['status']="success";
			echo json_encode($response);
	
?>