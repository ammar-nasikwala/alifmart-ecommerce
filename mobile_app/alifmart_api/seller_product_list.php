<?php

include("db.php");


$sup_id=addslashes($_GET['sup_id']);
$txt_key=addslashes($_GET['txt_key']);
$active_product=addslashes($_GET['active_product']);

$sql="select prod_id";
$sql = $sql.",prod_stockno"; 
$sql = $sql.",prod_name"; 
$sql = $sql.",prod_status"; 
$sql = $sql.",(select brand_name from brand_mast b where b.brand_id = p.prod_brand_id) as 'brand'"; 
$sql = $sql.",(select level_name from levels l where l.level_id = p.level_parent) as 'category'"; 
$sql = $sql."from prod_mast p ";
$sql_where = "";
if($txt_key<>""){
	$sql_where = $sql_where." where (prod_name like '%$txt_key%'";
	$sql_where = $sql_where." OR prod_stockno like '%$txt_key%'";
	$sql_where = $sql_where." OR prod_brand_id in (select brand_id from brand_mast where brand_name like '%$txt_key%')";
	$sql_where = $sql_where." OR level_parent in (select level_id from levels where level_name like '%$txt_key%'))";
	$sql_where = $sql_where." AND prod_id IN (select prod_id from prod_sup where sup_id=$sup_id)";
}else{
	$sql_where = $sql_where." where prod_id IN (select prod_id from prod_sup where sup_id=$sup_id)";
}

if($active_product<>"")
{
	$sql_where=$sql_where." and p.prod_status=1";
}

$sql = $sql.$sql_where." order by prod_stockno";

//echo $sql;die;
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 
$seller_product_list['seller_product_list']=array();

	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			
			//$row["sup_company"];
			$temp['prod_id'] = $row["prod_id"];
			$temp['prod_stockno'] = $row["prod_stockno"];
			$temp['prod_name'] = $row["prod_name"];
			$temp['brand'] = $row["brand"];
			$temp['category'] = $row["category"];
	
			array_push($seller_product_list['seller_product_list'],$temp);
		}
		
	}
		
	echo json_encode($seller_product_list);
?>