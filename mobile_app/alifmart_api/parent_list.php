<?php

include("db.php");
include("functions.php");

ini_set('display_errors', 1); 

$parent_list['prod_parents']=array();

$prod_level= $_GET["level_parent"];

get_rst("select prod_id, prod_name from prod_mast where parent_product = 0 and level_parent=$prod_level",$row_p,$rst_p);

		do
		{
			$temp=array();
			$temp['prod_id'] = $row_p["prod_id"];
			$temp['prod_name'] = $row_p['prod_name'];
			array_push($parent_list['prod_parents'],$temp);
		}while($row_p=mysqli_fetch_assoc($rst_p));
	
echo json_encode($parent_list);
?>