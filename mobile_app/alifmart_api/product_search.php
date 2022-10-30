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
$android = func_read_qs("android");

if($android == ""){
	
$txt_key = func_read_qs("searchtext");
$prod_sort = func_read_qs("prod_sort");
$page = func_read_qs("page");
$outofstock = func_read_qs("outofstock");
$memb_id = func_read_qs("memb_id");

$chk_level_ids = "";
if(!empty($_GET['chk_level_ids'])) {
    foreach($_GET['chk_level_ids'] as $check) {
		$chk_level_ids = $chk_level_ids.",".$check;
    }
	$chk_level_ids = substr($chk_level_ids,1);
}

if(func_read_qs("lvl_id")<>""){
	$chk_level_ids = func_read_qs("lvl_id");
}

$chk_brand_ids = "";
if(!empty($_GET['chk_brand_ids'])) {
    foreach($_GET['chk_brand_ids'] as $check) {
		$chk_brand_ids = $chk_brand_ids.",".$check;
    }
	$chk_brand_ids = substr($chk_brand_ids,1);
}
//print_r($chk_brand_ids);die;
if(func_read_qs("brand_id")<>""){
	$chk_brand_ids = func_read_qs("brand_id");
}


$sql_key="";
	if($txt_key<>""){
		$sql_key = $sql_key." AND (prod_name like '%$txt_key%'";
		$sql_key = $sql_key." OR prod_stockno like '%$txt_key%'";
		$sql_key = $sql_key." OR prod_brand_id in (select brand_id from brand_mast where brand_name like '%$txt_key%')";
		$sql_key = $sql_key." OR level_parent in (select level_id from levels where level_name like '%$txt_key%')";
		$sql_key = $sql_key." OR level_parent in (select level_id from levels where level_parent in (select level_id from levels where level_name like '%$txt_key%'))";
		//$sql_key = $sql_key." OR prod_sup_id in (select sup_id from sup_mast where sup_company like '%$txt_key%')";
		$sql_key = $sql_key.")";
	}	

	
$sql_brand="";
	if($chk_brand_ids<>""){
		$sql_brand = $sql_brand . " AND prod_brand_id in ($chk_brand_ids) ";
	}
	
$sql_level="";
	if($chk_level_ids<>""){
		$sql_level = $sql_level . " AND (level_parent in ($chk_level_ids) ";
		$sql_level = $sql_level . " or level_parent in (select level_id from levels where level_parent in ($chk_level_ids)) ";
		//$sql_level = $sql_level . " or level_parent in (select level_id from levels where level_parent in (select level_id from levels where level_parent in ($chk_level_ids)))";
		$sql_level = $sql_level . ")";
	}

$sql_filter = $sql_level;
$sql_filter = $sql_filter.$sql_brand;
$sql_filter = $sql_filter.$sql_key;

	$sql="select prod_id,prod_viewed";
	$sql = $sql.",prod_stockno,prod_thumb1,prod_ourprice,prod_offerprice"; 
	$sql = $sql.",prod_tax_id,prod_tax_name,prod_tax_percent"; 
	$sql = $sql.",prod_name,p.level_parent"; 
	$sql = $sql.",(select brand_name from brand_mast b where b.brand_id = p.prod_brand_id) "; 
	$sql = $sql.",(select level_name from levels l where l.level_id = p.level_parent) "; 
	$sql = $sql.",(select sup_company from sup_mast s where s.sup_id = p.prod_sup_id) "; 
	$sql = $sql.",(SELECT COUNT(0) - IFNULL(SUM(out_of_stock),0) FROM prod_sup s where s.prod_id = p.prod_id and sup_status=1) prod_outofstock"; 
	$sql = $sql." from prod_mast p where prod_status=1 and prod_advrt=0";
	/* if($featured == "1"){
		$sql = $sql."and prod_featured=1 ";
	} */
	$sql = $sql.$sql_filter;
	
	if($outofstock<>"")
	{
		$sql = $sql." having (prod_outofstock>0)";
	}
	
	if($prod_sort.""<>""){
		$sql = $sql." order by $prod_sort";
	}
	
	if($page.""<>""){
		$sql = $sql." limit $page,10";
	}
	/*elseif($bestseller == "1"){
		$sql = $sql." order by prod_purchased desc";
	}elseif($popular == "1"){
		$sql = $sql." order by prod_viewed desc";
	} */
//print_r($sql);die; 
$rs_find = mysqli_query($con,$sql); 

//print_r($rs_find);die; 
$product_list['product_list']=array();

	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			$temp=array();
			$temp['product_id'] = $row["prod_id"];
			$temp['prod_stockno'] = $row["prod_stockno"];
			$temp['prod_name'] = $row["prod_name"];
			$temp['prod_ourprice'] = $row["prod_ourprice"];
			$temp['prod_offerprice'] = $row["prod_offerprice"];
			
			$temp['prod_outofstock'] = $row["prod_outofstock"];
			$temp['prod_tax_id'] = $row["prod_tax_id"];
			$temp['prod_tax_name'] = $row["prod_tax_name"];
			$temp['prod_tax_percent'] = $row["prod_tax_percent"];
			if($row["prod_offerprice"]==null)
				$temp["tax_value"] = floatval($row["prod_ourprice"]) * floatval($row["prod_tax_percent"]."")/100;
			else
				$temp["tax_value"] = floatval($row["prod_offerprice"]) * floatval($row["prod_tax_percent"]."")/100;
	
	
			if($row["prod_offerprice"]!="null"&&$row["prod_ourprice"]!=0)
			{
				$row["prod_offerprice"]=0;
				$temp['prod_discount'] = (($row["prod_ourprice"]-$row["prod_offerprice"])/$row["prod_ourprice"])*100;
			}
			$temp['prod_thumb1'] = base64_encode($row["prod_thumb1"]);
			//$temp['prod_wish'] = 1;
			//$temp['sup_list']=array();

			$prod_id=$row["prod_id"];
			
			$sqll="select sup_alias,out_of_stock,p.* from sup_mast s inner join prod_sup p on s.sup_id=p.sup_id where p.prod_id=0$prod_id and p.sup_status=1 order by final_price";
			
		//	print_r($sqll."<br/><br/>");
			if(get_rst($sqll,$roww,$rstt)){
				//$roww["sup_id"]
				$temp["final_price"]=$roww["final_price"];
				$temp['sup_alias']=$roww["sup_alias"];
				$temp['offer_disc']=$roww["offer_disc"];
				$temp['our_price']=$roww["price"];
				$temp['final_price']=$roww["final_price"];
				$temp['sup_id']=$roww["sup_id"];
				$temp['out_of_stock']=$roww["out_of_stock"];
				
				if($memb_id<>"")
						{
							 //print_r($memb_id);die;
							/* $cart_query= mysqli_query($con,"select * from cart_items where memb_id=$memb_id and item_wish=0 and prod_id=$prod_id and sup_id=".$roww["sup_id"]);	
							if(mysqli_num_rows($cart_query)>0){
								$row = mysqli_fetch_assoc($cart_query);
								$temp['cart']=1;
								
							}
							else
							{
								$temp['cart']=0;
								
							} */
							
							$wishlist_query= mysqli_query($con,"select * from cart_items where memb_id=$memb_id and item_wish=1 and prod_id=$prod_id and sup_id=".$roww["sup_id"]);	
							
							//print_r("select * from cart_items where memb_id=$memb_id and item_wish=1 and prod_id=$prod_id and sup_id=".$roww["sup_id"]."<br/><br/><br/>");
							if(mysqli_num_rows($wishlist_query)>0){
								$row = mysqli_fetch_assoc($wishlist_query);
								$temp['wish']=1;
								$temp['cart_item_id']=$row['cart_item_id'];
							}
							else
							{
								$temp['wish']=0;
								$temp['cart_item_id']=0;		
							}
						}
						else
						{
							$temp['wish']=0;
							//$temp['cart']=0;
							$temp['cart_item_id']=0;		
						}
					//	array_push($temp['sup_list'],$temp1);
			}

			
			array_push($product_list['product_list'],$temp);
		}
		
	}
	
}else{
	
	$txt_key = func_read_qs("searchtext");
$prod_sort = func_read_qs("prod_sort");
$page = func_read_qs("page");
$outofstock = func_read_qs("outofstock");
$memb_id = func_read_qs("memb_id");

$advrt_enable=func_read_qs("advrt_enable");
	if($advrt_enable == ""){
		$prod_advrt= "and prod_advrt=0";
	}else{
		$prod_advrt= "";
	}

$chk_level_ids = "";
if(!empty($_GET['chk_level_ids'])) {
    foreach($_GET['chk_level_ids'] as $check) {
		$chk_level_ids = $chk_level_ids.",".$check;
    }
	$chk_level_ids = substr($chk_level_ids,1);
}

if(func_read_qs("lvl_id")<>""){
	$chk_level_ids = func_read_qs("lvl_id");
}

$chk_brand_ids = "";
if(!empty($_GET['chk_brand_ids'])) {
    foreach($_GET['chk_brand_ids'] as $check) {
		$chk_brand_ids = $chk_brand_ids.",".$check;
    }
	$chk_brand_ids = substr($chk_brand_ids,1);
}
//print_r($chk_brand_ids);die;
if(func_read_qs("brand_id")<>""){
	$chk_brand_ids = func_read_qs("brand_id");
}


$sql_key="";
	if($txt_key<>""){
		$sql_key = $sql_key." AND (prod_name like '%$txt_key%'";
		$sql_key = $sql_key." OR prod_stockno like '%$txt_key%'";
		$sql_key = $sql_key." OR prod_brand_id in (select brand_id from brand_mast where brand_name like '%$txt_key%')";
		$sql_key = $sql_key." OR level_parent in (select level_id from levels where level_name like '%$txt_key%')";
		$sql_key = $sql_key." OR level_parent in (select level_id from levels where level_parent in (select level_id from levels where level_name like '%$txt_key%'))";
		//$sql_key = $sql_key." OR prod_sup_id in (select sup_id from sup_mast where sup_company like '%$txt_key%')";
		$sql_key = $sql_key.")";
	}	

	
$sql_brand="";
	if($chk_brand_ids<>""){
		$sql_brand = $sql_brand . " AND prod_brand_id in ($chk_brand_ids) ";
	}
	
$sql_level="";
	if($chk_level_ids<>""){
		$sql_level = $sql_level . " AND (level_parent in ($chk_level_ids) ";
		$sql_level = $sql_level . " or level_parent in (select level_id from levels where level_parent in ($chk_level_ids)) ";
		//$sql_level = $sql_level . " or level_parent in (select level_id from levels where level_parent in (select level_id from levels where level_parent in ($chk_level_ids)))";
		$sql_level = $sql_level . ")";
	}

$sql_filter = $sql_level;
$sql_filter = $sql_filter.$sql_brand;
$sql_filter = $sql_filter.$sql_key;

	$sql="select prod_id,prod_viewed";
	$sql = $sql.",prod_stockno,prod_thumb1,prod_ourprice,prod_offerprice,prod_advrt"; 
	$sql = $sql.",prod_tax_id,prod_tax_name,prod_tax_percent"; 
	$sql = $sql.",prod_name,p.level_parent"; 
	$sql = $sql.",(select brand_name from brand_mast b where b.brand_id = p.prod_brand_id) "; 
	$sql = $sql.",(select level_name from levels l where l.level_id = p.level_parent) "; 
	$sql = $sql.",(select sup_company from sup_mast s where s.sup_id = p.prod_sup_id) "; 
	$sql = $sql.",(SELECT COUNT(0) - IFNULL(SUM(out_of_stock),0) FROM prod_sup s where s.prod_id = p.prod_id and sup_status=1) prod_outofstock"; 
	$sql = $sql." from prod_mast p where prod_id in (select prod_id from parent_products) $prod_advrt";
	/* if($featured == "1"){
		$sql = $sql."and prod_featured=1 ";
	} */
	$sql = $sql.$sql_filter;
	
	if($outofstock<>"")
	{
		$sql = $sql." having (prod_outofstock>0)";
	}
	
	if($prod_sort.""<>""){
		$sql = $sql." order by $prod_sort";
	}
	
	if($page.""<>""){
		$sql = $sql." limit $page,10";
	}
	/*elseif($bestseller == "1"){
		$sql = $sql." order by prod_purchased desc";
	}elseif($popular == "1"){
		$sql = $sql." order by prod_viewed desc";
	} */
//print_r($sql);die; 
$rs_find = mysqli_query($con,$sql); 

//print_r($rs_find);die; 
$product_list['product_list']=array();

	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			$temp=array();
			$temp['product_id'] = $row["prod_id"];
			$temp['prod_stockno'] = $row["prod_stockno"];
			$temp['prod_name'] = $row["prod_name"];
			$temp['prod_ourprice'] = $row["prod_ourprice"];
			$temp['prod_offerprice'] = $row["prod_offerprice"];
			
			$temp['prod_outofstock'] = $row["prod_outofstock"];
			$temp['prod_tax_id'] = $row["prod_tax_id"];
			$temp['prod_tax_name'] = $row["prod_tax_name"];
			$temp['prod_tax_percent'] = $row["prod_tax_percent"];
			if($row["prod_offerprice"]==null)
				$temp["tax_value"] = floatval($row["prod_ourprice"]) * floatval($row["prod_tax_percent"]."")/100;
			else
				$temp["tax_value"] = floatval($row["prod_offerprice"]) * floatval($row["prod_tax_percent"]."")/100;
	
	
			if($row["prod_offerprice"]!="null"&&$row["prod_ourprice"]!=0)
			{
				$row["prod_offerprice"]=0;
				$temp['prod_discount'] = (($row["prod_ourprice"]-$row["prod_offerprice"])/$row["prod_ourprice"])*100;
			}
			$temp['prod_thumb1'] = base64_encode($row["prod_thumb1"]);
			//$temp['prod_wish'] = 1;
			//$temp['sup_list']=array();
			$temp['prod_advrt'] = $row["prod_advrt"];

			$prod_id=$row["prod_id"];
			
			$sqll="select sup_alias,out_of_stock,p.* from sup_mast s inner join prod_sup p on s.sup_id=p.sup_id where p.prod_id=0$prod_id and p.sup_status=1 order by final_price";
			
		//	print_r($sqll."<br/><br/>");
			if(get_rst($sqll,$roww,$rstt)){
				//$roww["sup_id"]
				$temp["final_price"]=$roww["final_price"];
				$temp['sup_alias']=$roww["sup_alias"];
				$temp['offer_disc']=$roww["offer_disc"];
				$temp['our_price']=$roww["price"];
				$temp['final_price']=$roww["final_price"];
				$temp['sup_id']=$roww["sup_id"];
				$temp['out_of_stock']=$roww["out_of_stock"];
				
				if($memb_id<>"")
						{
							 //print_r($memb_id);die;
							/* $cart_query= mysqli_query($con,"select * from cart_items where memb_id=$memb_id and item_wish=0 and prod_id=$prod_id and sup_id=".$roww["sup_id"]);	
							if(mysqli_num_rows($cart_query)>0){
								$row = mysqli_fetch_assoc($cart_query);
								$temp['cart']=1;
								
							}
							else
							{
								$temp['cart']=0;
								
							} */
							
							$wishlist_query= mysqli_query($con,"select * from cart_items where memb_id=$memb_id and item_wish=1 and prod_id=$prod_id and sup_id=".$roww["sup_id"]);	
							
							//print_r("select * from cart_items where memb_id=$memb_id and item_wish=1 and prod_id=$prod_id and sup_id=".$roww["sup_id"]."<br/><br/><br/>");
							if(mysqli_num_rows($wishlist_query)>0){
								$row = mysqli_fetch_assoc($wishlist_query);
								$temp['wish']=1;
								$temp['cart_item_id']=$row['cart_item_id'];
							}
							else
							{
								$temp['wish']=0;
								$temp['cart_item_id']=0;		
							}
						}
						else
						{
							$temp['wish']=0;
							//$temp['cart']=0;
							$temp['cart_item_id']=0;		
						}
					//	array_push($temp['sup_list'],$temp1);
			}

			
			array_push($product_list['product_list'],$temp);
		}
		
	}
}	
		//print_r($product_list);echo "<br/><br/>";
	echo json_encode($product_list);
?>