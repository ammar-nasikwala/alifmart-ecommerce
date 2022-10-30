<?php

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

$list=func_read_qs("list");
$membid=func_read_qs("memb_id");
if($list == ""){

$sql="select * from levels where level_status=1 and level_parent=0 order by level_name";
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 
$category_list['category_list']=array();

	if($rs_find){
		while($row = mysqli_fetch_assoc($rs_find)){
			$parent_category=array();
			if(get_rst("select prod_id from prod_mast where prod_status=1 and (level_parent=0".$row["level_id"]." or level_parent in (select level_id from levels where level_parent=0".$row["level_id"]."))")){
			$parent_category['level_name'] = $row["level_name"];
			$parent_category['level_id'] = $row["level_id"];
			//$parent_category['level_image'] = base64_encode($row["level_image"]);
			$parent_category['level_image'] = "http://www.Company-Name.com/images/levels/".$row["level_id"]."/grey.png";
			$parent_category['sub_category'] = array();
			
			$sql="select * from levels where level_status=1 and level_parent=".$row['level_id'];
			$rs_find1 = mysqli_query($con,$sql);
			if($rs_find1){
					while($row1 = mysqli_fetch_assoc($rs_find1)){
					if(get_rst("select prod_id from prod_mast where prod_status=1 and level_parent=0".$row1['level_id'])){
						$temp['sub_level_name'] = $row1["level_name"];
						$temp['sub_level_id'] = $row1["level_id"];
						//$temp['sub_level_image'] = base64_encode($row1["level_image"]);
						
						array_push($parent_category['sub_category'],$temp);
						}
					}
					
			}
			$temp['sub_level_name'] = "View All";
			$temp['sub_level_id'] = $row["level_id"];
		//	$temp['sub_level_image'] = base64_encode($row["level_image"]);
			array_push($parent_category['sub_category'],$temp);
			
			array_push($category_list['category_list'],$parent_category);
			}
		}	
	}
	
$sql="select * from brand_mast";
//print_r($sql);die;
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 
$category_list['brand_list']=array();

	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			if(get_rst("select prod_id from prod_mast where prod_status=1 and prod_brand_id=0".$row["brand_id"])){
			//$row["sup_company"];
			$temp=array();
			$temp['brand_id'] = $row["brand_id"];
			$temp['brand_name'] = $row["brand_name"];
			
			
			array_push($category_list['brand_list'],$temp);
			}
		}
		
	}
	$category_list["app_version"]=26;
	
	if($membid <> "N/A"){
		$credit_amt=0;
		if(get_rst("select credit_amt from credit_details where memb_id=$membid",$cr)){
			$credit_amt=formatNumber($cr['credit_amt'],2);
		}
		
		if(get_rst("select coupon_code, max_discount_value, min_ord_value from offer_coupon where credit_flag=1 and memb_id=$membid", $row_cdetail)){
			$category_list["credit_amt"]=$credit_amt;
			$category_list["credit_coupon"]=$row_cdetail["coupon_code"];
			$category_list["min_amt"]=$row_cdetail["min_ord_value"];
			$category_list["max_dis"]=$row_cdetail["max_discount_value"];
		}else{
			$category_list["credit_amt"]=$credit_amt;
			$category_list["credit_coupon"]="NA";
			$category_list["min_amt"]="NA";
			$category_list["max_dis"]="NA";
		}
	}

	
}else{

$sql="select * from levels where level_status=1 and level_parent=0 order by level_name";
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 
$category_list['category_list']=array();

	if($rs_find){
		while($row = mysqli_fetch_assoc($rs_find)){
			$parent_category=array();
			$parent_category['level_name'] = $row["level_name"];
			$parent_category['level_id'] = $row["level_id"];
			//$parent_category['level_image'] = base64_encode($row["level_image"]);
			$parent_category['level_image'] = "http://www.Company-Name.com/images/levels/".$row["level_id"]."/grey.png";
			$parent_category['sub_category'] = array();
			
			$sql="select * from levels where level_status=1 and level_parent=".$row['level_id'];
			$rs_find1 = mysqli_query($con,$sql);
			if($rs_find1){
					while($row1 = mysqli_fetch_assoc($rs_find1)){
						$temp['sub_level_name'] = $row1["level_name"];
						$temp['sub_level_id'] = $row1["level_id"];
						//$temp['sub_level_image'] = base64_encode($row1["level_image"]);
						
						array_push($parent_category['sub_category'],$temp);
					}
					
			}
			$temp['sub_level_name'] = "View All";
			$temp['sub_level_id'] = $row["level_id"];
		//	$temp['sub_level_image'] = base64_encode($row["level_image"]);
			array_push($parent_category['sub_category'],$temp);
			
			array_push($category_list['category_list'],$parent_category);
		}	
	}
	
$sql="select * from brand_mast";
//print_r($sql);die;
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 
$category_list['brand_list']=array();

	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			//$row["sup_company"];
			$temp=array();
			$temp['brand_id'] = $row["brand_id"];
			$temp['brand_name'] = $row["brand_name"];
			
			
			array_push($category_list['brand_list'],$temp);
		}
		
	}
	
}
	
	echo json_encode($category_list);
?>