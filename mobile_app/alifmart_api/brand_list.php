<?php

include("db.php");


//$sup_id=addslashes($_GET['sup_id']);
$sql="select * from brand_mast";
//print_r($sql);die;
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 
$brand_list['brand_list']=array();

	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			
			//$row["sup_company"];
			$temp['brand_id'] = $row["brand_id"];
			$temp['brand_name'] = $row["brand_name"];
			
			
			array_push($brand_list['brand_list'],$temp);
		}
		
	}
		
	echo json_encode($brand_list);
?>