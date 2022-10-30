<?php

//print_r("ksndjhs");die;
date_default_timezone_set('Asia/Calcutta');

include("db.php");
include("functions.php");

//print_r($_GET);die;
//$prod_id = func_read_qs("prod_id");
//$act = func_read_qs("act");
$memb_id = func_read_qs("memb_id");

$sql="select view_count,p.prod_id,prod_stockno,prod_name,prod_thumb1,prod_ourprice,prod_offerprice from prod_mast p inner join "; 

//print_r($sql);die;

$sql = $sql." prod_viewed v on p.prod_id=v.prod_id where prod_status=1 and user_id=$memb_id order by view_date desc LIMIT 4";

//print_r($sql);die;
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 
$recent_product_list['recent_product_list']=array();

	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			
			$temp['product_id'] = $row["prod_id"];
			$temp['prod_stockno'] = $row["prod_stockno"];
			$temp['prod_name'] = $row["prod_name"];
			$temp['prod_ourprice'] = $row["prod_ourprice"];
			$temp['prod_offerprice'] = $row["prod_offerprice"];
			$temp['prod_outofstock'] = $row["prod_outofstock"];
			$temp['prod_thumb1'] = base64_encode($row["prod_thumb1"]);
			
			array_push($recent_product_list['recent_product_list'],$temp);
		}
		
	}
		
	echo json_encode($recent_product_list);
?>