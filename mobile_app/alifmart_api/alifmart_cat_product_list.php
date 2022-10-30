<?php
//
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
//print_r("in  ");die;
ini_set('display_errors', 1); 

$sql="select prod_id,prod_stockno,prod_thumb1,prod_ourprice,prod_offerprice,prod_name,(select brand_name from brand_mast b where b.brand_id = p.prod_brand_id) ,(select level_name from levels l where l.level_id = p.level_parent) ,(select sup_company from sup_mast s where s.sup_id = p.prod_sup_id) ,(SELECT COUNT(0) - IFNULL(SUM(out_of_stock),0) FROM prod_sup s where s.prod_id = p.prod_id and sup_status=1) prod_outofstock from prod_mast p where prod_status=1 AND (level_parent in (".$_GET['lvl_id'].") or level_parent in (select level_id from levels where level_parent in (".$_GET['lvl_id'].")) or level_parent in (select level_id from levels where level_parent in (select level_id from levels where level_parent in (".$_GET['lvl_id']."))))";


$prod_sort = func_read_qs("prod_sort");
$page = func_read_qs("page");

if($prod_sort.""<>""){
		$sql = $sql." order by $prod_sort";
	}
	
if($page.""<>""){
		$sql = $sql." limit $page,6";
	}
//print_r($sql);die;
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 



$product_list['product_list']=array();

	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			
			$temp['product_id'] = $row["prod_id"];
			$temp['prod_stockno'] = $row["prod_stockno"];
			$temp['prod_name'] = $row["prod_name"];
			$temp['prod_ourprice'] = $row["prod_ourprice"];
			$temp['prod_offerprice'] = $row["prod_offerprice"];
			if($row["prod_offerprice"]!="null"&&$row["prod_ourprice"]!=0)
			{
				$row["prod_offerprice"]=0;
				$temp['prod_discount'] = (($row["prod_ourprice"]-$row["prod_offerprice"])/$row["prod_ourprice"])*100;
			}
			
			$temp['prod_outofstock'] = $row["prod_outofstock"];
			$temp['prod_thumb1'] = base64_encode($row["prod_thumb1"]);
			
			array_push($product_list['product_list'],$temp);
		}
		
	}
	
	
/* $sql="select prop_id, prop_name from prop_mast where prop_status=1 and prop_id in (select prop_id from prod_props where prod_id in (select prod_id from prod_mast where prod_status=1 AND (level_parent in (".$_GET['lvl_id'].") or level_parent in (select level_id from levels where level_parent in (".$_GET['lvl_id'].")) )))";

//echo $sql;die;	
$rs_find = mysql_query($sql);

$product_list['filter_by']=array();

	if($rs_find){
			
			while($row = mysql_fetch_assoc($rs_find)){
				
				$temp2['prop_id'] = $row["prop_id"];
				$temp2['prop_name'] = $row["prop_name"];
				$temp2['options']=array();
					
					$sql1="";
					$rs_find1 = mysql_query($sql1);
					while($row1 = mysql_fetch_assoc($rs_find1)){
						$temp1['prop_id']=$row1["prop_id"]
						array_push($temp2['options'],$temp1);
					}
					
				array_push($product_list['filter_by'],$temp);
			}
			
		} */
	
	echo json_encode($product_list);
?>