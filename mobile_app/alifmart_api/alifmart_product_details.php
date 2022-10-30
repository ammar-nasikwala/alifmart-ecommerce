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


$prod_id=addslashes($_GET['product_id']);
$memb_id=func_read_qs('memb_id');
$sup_id=func_read_qs('sup_id');

//print_r($memb_id);die;
//print_r(addslashes($_GET['product_id']));die;
$sql="select p.*,b.brand_name from prod_mast p inner join brand_mast b on p.prod_brand_id=b.brand_id where prod_id=$prod_id";
//print_r($sql);die;
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 
$product_list['product_list']=array();

	if($rs_find){
		
		$row = mysqli_fetch_assoc($rs_find);
			
			$temp['product_id'] = $row["prod_id"];
			$temp['prod_stockno'] = $row["prod_stockno"];
			$temp['prod_name'] = $row["prod_name"];
			$temp['page_title'] = $row["page_title"];
			$temp['meta_key'] = $row["meta_key"];
			$temp['meta_desc'] = $row["meta_desc"];
			$temp['prod_detaildesc'] = $row["prod_detaildesc"];
			$temp['min_qty'] = $row["min_qty"];
			//$temp['prod_briefdesc'] = $row["prod_briefdesc"];
			 $filepath=$_SERVER['DOCUMENT_ROOT']."/prod_desc/".$temp['prod_stockno']."/Template.htm";
			if(file_exists($filepath)) { 
			
				//$myfile = fopen($filepath, "r") or die("Unable to open file!");
				
			//	$temp['prod_briefdesc']=fread($myfile,filesize($filepath));
			//	fclose($myfile);
			/* 	ob_start();
				include($filepath);
				$buffer = ob_get_clean(); */
				
				$buffer=file_get_contents($filepath);
				//print_r($buffer);die;
				//$tempvar=$buffer;
				//print_r($tempvar);die;
				$temp['prod_briefdesc']=htmlentities($buffer,ENT_QUOTES | ENT_IGNORE, "UTF-8");
				//print_r(html_entity_decode($temp['prod_briefdesc']));die;
			}
			else
			{
				$temp['prod_briefdesc'] = htmlentities($row["prod_briefdesc"],ENT_QUOTES | ENT_IGNORE, "UTF-8");
			} 
			$temp['brand_name'] = $row["brand_name"];
			$temp['prod_brand_id'] = $row["prod_brand_id"];
			$temp['prod_weight'] = $row["prod_weight"];
			$temp['level_parent'] = $row["level_parent"];
			$temp['prod_tax_id'] = $row["prod_tax_id"];
			$temp['prod_tax_name'] = $row["prod_tax_name"];
			$temp['prod_tax_percent'] = $row["prod_tax_percent"];
			$temp['parent_id'] = $row["parent_product"];
			if($temp['parent_id'] == 0){
				$temp['parent_name'] = "";
			}else{
				$parentid = $row["parent_product"];
			get_rst("select prod_name from prod_mast where prod_id =$parentid",$row_par);
			$temp['parent_name'] = $row_par["prod_name"];
			}
			if($row["prod_size"] == NULL){
				$temp['prod_size']= "";
			}else{
				$temp['prod_size']= $row["prod_size"];
			}
			
			
			
			$sql2="select level_name from levels where level_id=".$row['level_parent'];
			//print_r($sql);die;
			$rs_find2 = mysqli_query($con,$sql2);
			if($rs_find2){
				if($row2 = mysqli_fetch_assoc($rs_find2))
				{
					$temp['level_name']=$row2["level_name"];
				//	$temp['level_name']=$row2["level_name"];
					
				} 
			}
			
			$temp['prod_ourprice'] = $row["prod_ourprice"];
		
			$temp['prod_offerprice'] = $row["prod_offerprice"];
			if($row["prod_offerprice"]==null)
				$temp["tax_value"] = floatval($row["prod_ourprice"]) * floatval($row["prod_tax_percent"]."")/100;
			else
				$temp["tax_value"] = floatval($row["prod_offerprice"]) * floatval($row["prod_tax_percent"]."")/100;
	
		//	$temp["tax_value"] = floatval($row["prod_offerprice"]) * floatval($row["prod_tax_percent"]."")/100;
			$temp['prod_pricestatus'] = $row["prod_pricestatus"];
			$temp['sell_online'] = $row["sell_online"];
			$temp['cross_cell_item1'] = $row["cross_cell_item1"];
			$temp['cross_cell_item2'] = $row["cross_cell_item2"];
			$temp['cross_cell_item3'] = $row["cross_cell_item3"];
			$temp['is_vat'] = $row["is_vat"];
			$temp['prod_status'] = $row["prod_status"];
			$temp['prod_large1'] = base64_encode($row["prod_large1"]);
			$temp['prod_large2'] = base64_encode($row["prod_large2"]);
			$temp['prod_large3'] = base64_encode($row["prod_large3"]);
			$temp['prod_large4'] = base64_encode($row["prod_large4"]);
			$temp['prod_advrt'] = $row["prod_advrt"];
			//$temp['prod_outofstock'] = $row["prod_outofstock"];
			//$temp['prod_thumb1'] = base64_encode($row["prod_thumb1"]);
			$android= func_read_qs('android_seller');
			$prod_status="and prod_status=1";
			if($android == 1){
				$prod_status="";
			}
			$temp['child']=array();
			if($temp['parent_id'] == 0){
			if(get_rst("select prod_id,prod_size from prod_mast where parent_product = 0$prod_id $prod_status",$row_op,$rst_op)){
				do{
					$tempc['prod_size'] = $row_op["prod_size"];
					$tempc['prod_id'] = $row_op["prod_id"];
					array_push($temp['child'],$tempc);
				}while($row_op=mysqli_fetch_assoc($rst_op));
			}
			}else{
				get_rst("select parent_product from prod_mast where prod_id=0$prod_id $prod_status",$row_child);
				$child_product = $row_child["parent_product"];
				if(get_rst("select prod_id,prod_size from prod_mast where parent_product=$child_product and prod_id<>0$prod_id or prod_id=$child_product ",$row_op,$rst_op)){
					do{
						$tempc['prod_size'] = $row_op["prod_size"];
						$tempc['prod_id'] = $row_op["prod_id"];
						array_push($temp['child'],$tempc);
					}while($row_op=mysqli_fetch_assoc($rst_op));
				}
				
			}
			
			$temp['sup_list']=array();

			$sql="select sup_alias,out_of_stock,p.* from sup_mast s inner join prod_sup p on s.sup_id=p.sup_id where p.prod_id=0$prod_id and p.sup_status=1 ";
			
			if($sup_id<>"")
			$sql.=" and p.sup_id=$sup_id ";
		
			$sql.=" order by final_price";
			//print_r($sql);die;
			$rs_find1 = mysqli_query($con,$sql);
			
			if($rs_find1){
				while($row1 = mysqli_fetch_assoc($rs_find1))
				{
					$temp1['sup_alias']=$row1["sup_alias"];
					$temp1['offer_disc']=$row1["offer_disc"];
					$temp1['our_price']=$row1["price"];
					$temp1['final_price']=$row1["final_price"];
					$temp1['sup_id']=$row1["sup_id"];
					//$temp1['out_of_stock']=$row1["out_of_stock"];
					$temp1['out_of_stock']=1;
					if($memb_id<>"")
						{
							 //print_r($memb_id);die;
							$cart_query= mysqli_query($con,"select * from cart_items where memb_id=$memb_id and item_wish=0 and prod_id=$prod_id and sup_id=".$row1["sup_id"]);	
							if(mysqli_num_rows($cart_query)>0){
								$row = mysqli_fetch_assoc($cart_query);
								$temp1['cart']=1;
								
							}
							else
							{
								$temp1['cart']=0;
								
							}
							
							$wishlist_query= mysqli_query($con,"select * from cart_items where memb_id=$memb_id and item_wish=1 and prod_id=$prod_id and sup_id=".$row1["sup_id"]);	
							if(mysqli_num_rows($wishlist_query)>0){
								$row = mysqli_fetch_assoc($wishlist_query);
								$temp1['wish']=1;
								$temp1['cart_item_id']=$row['cart_item_id'];
							}
							else
							{
								$temp1['wish']=0;
								$temp1['cart_item_id']=0;		
							}
						}
						else
						{
							$temp1['wish']=0;
							$temp1['cart']=0;
							$temp1['cart_item_id']=0;		
						}
					array_push($temp['sup_list'],$temp1);
				} 
			}
			
			$temp['prop_list']=array();
			$sql="select prop_id,opt_value from prod_props where prod_id=0$prod_id";
			$rs_find1 = mysqli_query($con,$sql);
			if($rs_find1){
				while($row1 = mysqli_fetch_assoc($rs_find1))
				{
					$temp2['prop_id']=$row1["prop_id"];
					$temp2['opt_value']=$row1["opt_value"];
					array_push($temp['prop_list'],$temp2);
				} 
			}
			
			
			array_push($product_list['product_list'],$temp);
		
		
	}
		
	echo json_encode($product_list);
?>