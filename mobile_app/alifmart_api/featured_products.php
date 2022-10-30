<?php

//print_r($_SERVER);die;
//header("Authorization:1234567890,X-APP-TOKEN:7d5dda4a849d41da80961b79ab6fd875");

/* $realm = 'Restricted area';

$users = array('admin' => 'mypass', 'guest' => 'guest');

if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Basic 112233 realm="'.$realm.'"');

    die('Text to send if user hits Cancel button');
}  */



include("db.php");
include("functions.php");


ini_set('display_errors', 1); 

$android = func_read_qs("android");

if($android == ""){

$sql="select prod_id, prod_name,prod_thumb1,prod_briefdesc,prod_free_text,prod_offerprice,prod_ourprice from prod_mast where prod_status=1 and prod_featured=1 and prod_advrt=0 LIMIT 5";
//print_r($sql);die;
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 

$featured_product['featured_product']=array();

	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			
			//$row["sup_company"];
			$temp['prod_id'] = $row["prod_id"];
			$temp['prod_name'] = $row["prod_name"];
			$temp['prod_thumb1'] = base64_encode($row["prod_thumb1"]);
			$temp['prod_briefdesc'] = $row["prod_briefdesc"];
			$temp['prod_free_text'] = $row["prod_free_text"];
			$temp['prod_offerprice'] = $row["prod_offerprice"];
			$temp['prod_ourprice'] = $row["prod_ourprice"];
			
			array_push($featured_product['featured_product'],$temp);
		}
		
	}
	
$qry = "select prod_id, prod_name,prod_thumb1,prod_briefdesc,prod_free_text,prod_offerprice,prod_ourprice from prod_mast";
$qry = $qry." where prod_status=1 and prod_advrt=0 ORDER BY prod_purchased desc LIMIT 5";
//print_r($sql);die;
$rs_find = mysqli_query($con,$qry);

//print_r($rs_find);die; 
$featured_product['bestseller_product']=array();

	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			
			//$row["sup_company"];
			$temp['prod_id'] = $row["prod_id"];
			$temp['prod_name'] = $row["prod_name"];
			$temp['prod_thumb1'] = base64_encode($row["prod_thumb1"]);
			$temp['prod_briefdesc'] = $row["prod_briefdesc"];
			$temp['prod_free_text'] = $row["prod_free_text"];
			$temp['prod_offerprice'] = $row["prod_offerprice"];
			$temp['prod_ourprice'] = $row["prod_ourprice"];
			
			array_push($featured_product['bestseller_product'],$temp);
		}
		
	}	
	
$qry = "select prod_id, prod_name,prod_thumb1,prod_briefdesc,prod_free_text,prod_offerprice,prod_ourprice from prod_mast";
$qry = $qry." where prod_status=1 and prod_advrt=0 ORDER BY prod_purchased desc LIMIT 5";
//print_r($sql);die;
$rs_find = mysqli_query($con,$qry);

//print_r($rs_find);die; 
$featured_product['mostpopular_product']=array();

	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			
			//$row["sup_company"];
			$temp['prod_id'] = $row["prod_id"];
			$temp['prod_name'] = $row["prod_name"];
			$temp['prod_thumb1'] = base64_encode($row["prod_thumb1"]);
			$temp['prod_briefdesc'] = $row["prod_briefdesc"];
			$temp['prod_free_text'] = $row["prod_free_text"];
			$temp['prod_offerprice'] = $row["prod_offerprice"];
			$temp['prod_ourprice'] = $row["prod_ourprice"];
			
			array_push($featured_product['mostpopular_product'],$temp);
		}
		
	}	
	
	
$featured_product['recent_product_list']=array();

if(isset($_GET['memb_id']))	
{
	$memb_id = func_read_qs("memb_id");
	$sql="select view_count,p.prod_id,prod_stockno,prod_name,prod_thumb1,prod_ourprice,prod_offerprice,p.prod_briefdesc,p.prod_free_text from prod_mast p inner join "; 
	$sql = $sql." prod_viewed v on p.prod_id=v.prod_id where prod_status=1 and user_id=$memb_id and prod_advrt=0 order by view_date desc LIMIT 4";

//print_r($sql);die;
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 


	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			
			$temp['prod_id'] = $row["prod_id"];
			$temp['prod_name'] = $row["prod_name"];
			$temp['prod_thumb1'] = base64_encode($row["prod_thumb1"]);
			$temp['prod_briefdesc'] = $row["prod_briefdesc"];
			$temp['prod_free_text'] = $row["prod_free_text"];
			$temp['prod_offerprice'] = $row["prod_offerprice"];
			$temp['prod_ourprice'] = $row["prod_ourprice"];
			
			array_push($featured_product['recent_product_list'],$temp);
		}
		
	}	
}
}else{
	$advrt_enable=func_read_qs("advrt_enable");
	if($advrt_enable == ""){
		$prod_advrt= "and prod_advrt=0";
	}else{
		$prod_advrt= "";
	}
	$sql="select prod_id, prod_name,prod_thumb1,prod_briefdesc,prod_free_text,prod_offerprice,prod_ourprice,prod_advrt from prod_mast where prod_status=1 and prod_featured=1 and parent_product=0 $prod_advrt LIMIT 5";
//print_r($sql);die;
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 

$featured_product['featured_product']=array();

	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			
			//$row["sup_company"];
			$temp['prod_id'] = $row["prod_id"];
			$temp['prod_name'] = $row["prod_name"];
			$temp['prod_thumb1'] = base64_encode($row["prod_thumb1"]);
			$temp['prod_briefdesc'] = $row["prod_briefdesc"];
			$temp['prod_free_text'] = $row["prod_free_text"];
			$temp['prod_offerprice'] = $row["prod_offerprice"];
			$temp['prod_ourprice'] = $row["prod_ourprice"];
			$temp['prod_advrt'] = $row["prod_advrt"];
			array_push($featured_product['featured_product'],$temp);
		}
		
	}
	
$qry = "select prod_id, prod_name,prod_thumb1,prod_briefdesc,prod_free_text,prod_offerprice,prod_ourprice,prod_advrt from prod_mast";
$qry = $qry." where prod_status=1 and parent_product=0 $prod_advrt ORDER BY prod_purchased desc LIMIT 5";
//print_r($sql);die;
$rs_find = mysqli_query($con,$qry);

//print_r($rs_find);die; 
$featured_product['bestseller_product']=array();

	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			
			//$row["sup_company"];
			$temp['prod_id'] = $row["prod_id"];
			$temp['prod_name'] = $row["prod_name"];
			$temp['prod_thumb1'] = base64_encode($row["prod_thumb1"]);
			$temp['prod_briefdesc'] = $row["prod_briefdesc"];
			$temp['prod_free_text'] = $row["prod_free_text"];
			$temp['prod_offerprice'] = $row["prod_offerprice"];
			$temp['prod_ourprice'] = $row["prod_ourprice"];
			$temp['prod_advrt'] = $row["prod_advrt"];
			
			array_push($featured_product['bestseller_product'],$temp);
		}
		
	}	
	
$qry = "select prod_id, prod_name,prod_thumb1,prod_briefdesc,prod_free_text,prod_offerprice,prod_ourprice,prod_advrt from prod_mast";
$qry = $qry." where prod_status=1 and parent_product=0 $prod_advrt ORDER BY prod_purchased desc LIMIT 5";
//print_r($sql);die;
$rs_find = mysqli_query($con,$qry);

//print_r($rs_find);die; 
$featured_product['mostpopular_product']=array();

	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			
			//$row["sup_company"];
			$temp['prod_id'] = $row["prod_id"];
			$temp['prod_name'] = $row["prod_name"];
			$temp['prod_thumb1'] = base64_encode($row["prod_thumb1"]);
			$temp['prod_briefdesc'] = $row["prod_briefdesc"];
			$temp['prod_free_text'] = $row["prod_free_text"];
			$temp['prod_offerprice'] = $row["prod_offerprice"];
			$temp['prod_ourprice'] = $row["prod_ourprice"];
			$temp['prod_advrt'] = $row["prod_advrt"];
			
			array_push($featured_product['mostpopular_product'],$temp);
		}
		
	}	
	
	
$featured_product['recent_product_list']=array();

if(isset($_GET['memb_id']))	
{
	$memb_id = func_read_qs("memb_id");
	$sql="select view_count,p.prod_id,prod_stockno,prod_name,prod_thumb1,prod_ourprice,prod_offerprice,prod_advrt,p.prod_briefdesc,p.prod_free_text from prod_mast p inner join "; 
	$sql = $sql." prod_viewed v on p.prod_id=v.prod_id where prod_status=1 and user_id=$memb_id order by view_date desc LIMIT 4";

//print_r($sql);die;
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 


	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			
			$temp['prod_id'] = $row["prod_id"];
			$temp['prod_name'] = $row["prod_name"];
			$temp['prod_thumb1'] = base64_encode($row["prod_thumb1"]);
			$temp['prod_briefdesc'] = $row["prod_briefdesc"];
			$temp['prod_free_text'] = $row["prod_free_text"];
			$temp['prod_offerprice'] = $row["prod_offerprice"];
			$temp['prod_ourprice'] = $row["prod_ourprice"];
			$temp['prod_advrt'] = $row["prod_advrt"];
			
			array_push($featured_product['recent_product_list'],$temp);
		}
		
	}	
}
}	
	echo json_encode($featured_product);
?>