<?php

include("db.php");
include("functions.php");


//$sup_id=addslashes($_GET['sup_id']);
$sql="select * from prop_mast";
//print_r($sql);die;
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 
$property_list['property_list']=array();

	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			$prop_id=$row["prop_id"];
			//$row["sup_company"];
			$temp['prop_id'] = $row["prop_id"];
			$temp['prop_name'] = $row["prop_name"];
			$temp['prop_status'] = $row["prop_status"];
			$temp['prop_option'] = array();
			if(get_rst("SELECT distinct opt_value FROM prod_props where prop_id=$prop_id",$row1,$rst)){
						do{
							$temp1=$row1['opt_value'];
							array_push($temp['prop_option'],$temp1);
						}while($row1 = mysqli_fetch_assoc($rst));
					}
			
			array_push($property_list['property_list'],$temp);
		}
		
	}
		
	echo json_encode($property_list);
?>