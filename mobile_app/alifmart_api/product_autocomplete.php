<?php

//print_r("ksndjhs");die;
date_default_timezone_set('Asia/Calcutta');


/* $connection = @ssh2_connect('13.76.102.83', 22);  
ssh2_auth_password($connection, 'leometric', '|30M3tr!c'); 
$tunnel = ssh2_tunnel($connection, '13.76.102.83', 3307); 
 */
/* $db_servername = '127.0.0.1';
//$db_servername = 'localhost';
$db_username = "leometric.com";
$db_password = "@|!fM@rt";
$db_name = "Company-Name";
$env_prod = false; */

//$db_servername = '103.21.58.112';
include("db.php");
include("functions.php");

ini_set('display_errors', 1); 

$txt_key = func_read_qs("searchtext");
$android = func_read_qs("android");

if($android == ""){
 
 $sql="select * from ((select prod_name as search_term from prod_mast p where prod_status=1 and prod_name like '%$txt_key%' and prod_advrt=0) union (select brand_name as search_term from brand_mast where brand_name like '%$txt_key%') union (select level_name as search_term from levels where level_name like '%$txt_key%' and level_status=1)) as temp order by search_term";
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 
$search_list['search_list']=array();

	if($rs_find){		
		while($row = mysqli_fetch_assoc($rs_find)){
			$temp['search_term'] = $row["search_term"];
			array_push($search_list['search_list'],$temp);
		}		
	}
	echo json_encode($search_list);
}else{
	
	$advrt_enable=func_read_qs("advrt_enable");
	if($advrt_enable == ""){
		$prod_advrt= "and prod_advrt=0";
	}else{
		$prod_advrt= "";
	}
	
	$sql="select * from ((select prod_name as search_term from prod_mast p where prod_status=1 and prod_name like '%$txt_key%' and parent_product=0 $prod_advrt) union (select brand_name as search_term from brand_mast where brand_name like '%$txt_key%') union (select level_name as search_term from levels where level_name like '%$txt_key%' and level_status=1)) as temp order by search_term";
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 
$search_list['search_list']=array();

	if($rs_find){		
		while($row = mysqli_fetch_assoc($rs_find)){
			$temp['search_term'] = $row["search_term"];
			array_push($search_list['search_list'],$temp);
		}		
	}
	echo json_encode($search_list);
}	
	
?>