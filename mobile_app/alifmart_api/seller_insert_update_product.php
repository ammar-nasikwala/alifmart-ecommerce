<?php

include("db.php");
include("functions.php");

ini_set('display_errors', 1); 

//print_r($_GET);die;

$sup_id=addslashes($_GET['sup_id']);
$id=addslashes($_GET['product_id']);
$act = func_read_qs("act");


if($act=="d"){
	//if(!mysql_query("delete from prod_mast where prod_id=".func_read_qs("id"))){
	if(execute_qry("delete from prod_sup where sup_id=$sup_id and prod_id=$id")){
		$response['message']="Problem updating database...";
	}else{
		$response['message']="Product deleted successfully.";	
//		die("");
	}	
	echo json_encode($response);die;
}

//print_r($option);die;
	/* foreach($img_arr as $fld => $val){
		$img_name="";
		$msg = img_upload_db($fld,$img_path,$img_name,$img_thumb);
		
		if($msg=="1"){
			$img_arr[$fld] = $img_name;
			$thumb_arr[replace($fld,"large","thumb")] = $img_thumb;
		}else{
			break;
		}
	} */
	$msg=1;
	if($msg=="1"){
		
		$fld_arr = array();

		$fld_arr["prod_name"] = func_read_qs("prod_name");
		$fld_arr["level_parent"] = intval(func_read_qs("level_parent"));
		//$fld_arr["prod_sup_id"] = $sup_id;
		$fld_arr["page_title"] = func_read_qs("page_title");
		$fld_arr["meta_key"] = func_read_qs("meta_key");
		$fld_arr["meta_desc"] = func_read_qs("meta_desc");
		$fld_arr["prod_briefdesc"] = func_read_qs("prod_briefdesc");
		$fld_arr["prod_ourprice"] = 1;//func_read_qs("prod_ourprice");
		//$fld_arr["prod_offerprice"] = func_read_qs("prod_offerprice");
		$fld_arr["prod_stockno"] = func_read_qs("prod_stockno");
		$fld_arr["prod_brand_id"] = func_read_qs("prod_brand_id");
		$fld_arr["prod_weight"] = func_read_qs("prod_weight");
		//create a folder in the image folder to store images/products/
		//mkdir("../prod_desc/".$fld_arr["prod_stockno"]);
		
		if(func_read_qs("prod_tax_info")<>""){
			$tax_arr = explode("|",func_read_qs("prod_tax_info"));
			$fld_arr["prod_tax_id"] = $tax_arr[0];
			$fld_arr["prod_tax_name"] = $tax_arr[1];
			$fld_arr["prod_tax_percent"] = $tax_arr[2];
		}

		if(func_read_qs("prod_tax_id")<>""){
			$tid=func_read_qs("prod_tax_id");
			get_rst("select tax_id,tax_name,tax_percent from tax_mast where tax_id=$tid",$rowtax);
			$fld_arr["prod_tax_id"] = $rowtax["tax_id"];
			$fld_arr["prod_tax_name"] = $rowtax["tax_name"];
			$fld_arr["prod_tax_percent"] = $rowtax["tax_percent"];
		}

		$pidc=func_read_qs("parent_id");
		if($pidc == ""){
			
		}else{
			$fld_arr["parent_product"] = func_read_qs("parent_id");
			$fld_arr["prod_size"] = func_read_qs("prod_size");
		}
		
		/* foreach($img_arr as $fld => $val){
			$remove_img = func_read_qs($fld."_remove_img");
			if($remove_img<>""){
				$fld_arr[$fld] = "";
				$fld_arr[replace($fld,"large","thumb")] = "";
			}
			if($val<>""){
				$fld_arr[$fld] = $val;
				$fld_arr[replace($fld,"large","thumb")] = $thumb_arr[replace($fld,"large","thumb")];
			}
		} */
		
		if($id==""){
			$fld_arr["prod_status"] = 0; // inactive by default for seller
			$qry = func_insert_qry("prod_mast",$fld_arr);			
		}else{
			$qry = func_update_qry("prod_mast",$fld_arr," where prod_id=$id");
		}
		
		if(!mysqli_query($con,$qry)){
		
				$msg="fail";
				//window.location.href="manage_prods.php";
		}else{
			if($id==""){
				$id=mysqli_insert_id($con);
				$response['product_id']=$id;
				//$msg['status']="success";
			}
			
			execute_qry("delete from prod_sup where prod_id=$id and sup_id=$sup_id");
			$prod_price=999999;
			$prod_offer_price=999999;
			$prod_final_price=999999;
			$prod_final_offer_price="";
			$price = func_read_qs("price");
			$offer_price = (func_read_qs("offer_price"));
			$offer_disc = func_read_qs("offer_disc");
			$final_price = "";
				
			if(intval($price)>0){
				$offer_disc_price = $price - (intval($price) * intval($offer_disc)/100);
				if(intval($offer_price)>0 and intval($offer_price)<intval($offer_disc_price)){
					$final_price=$offer_price;
					$prod_final_offer_price=$offer_price;
				}elseif(intval($offer_disc)>0){
					$final_price=$offer_disc_price;
					$prod_final_offer_price=$offer_disc_price;
				}else{
					$final_price = $price;
				}
				if(intval($prod_final_price)>intval($final_price)){
					
					$prod_final_price = $final_price;
					$prod_price = $price;
					$prod_offer_price = $prod_final_offer_price;
				}

					
				$fld_s_arr = array();
				get_rst("select sup_company from sup_mast where sup_id=$sup_id",$row_ps);
				$fld_s_arr["prod_id"] = $id;
				$fld_s_arr["sup_id"] = $sup_id;
				$fld_s_arr["price"] = $price;
				$fld_s_arr["offer_price"] = $offer_price;
				$fld_s_arr["offer_disc"] = $offer_disc;
				$fld_s_arr["final_price"] = $final_price;
				$fld_s_arr["sup_status"] = 1;
				$fld_s_arr["sup_company"] = $row_ps["sup_company"];
				$qry = func_insert_qry("prod_sup",$fld_s_arr);
				execute_qry($qry);
			}

			$prod_effective_price = $prod_price;
			if($prod_offer_price>0 and $prod_price<$prod_offer_price){
				$prod_effective_price = $prod_offer_price;
			}
			
			if($prod_offer_price >= 999999){	$prod_offer_price="";}
			if($prod_offer_price ==""){	$prod_offer_price="\N";}
			if($prod_price==999999){ $prod_price=0;};
			
			
			execute_qry("update prod_mast set prod_ourprice=$prod_price,prod_offerprice=$prod_offer_price,prod_effective_price=$prod_effective_price where prod_id=$id");

			execute_qry("delete from prod_props where prod_id=$id");
			
			if(!empty($_GET['prop_ids'])) {
				$prop_ids=json_decode($_GET['prop_ids']);
//print_r($prop_ids);die;
				foreach($prop_ids as $prop_id) {
				
					if(!empty($_GET['opt_'.$prop_id])) {
						//	print_r(json_decode($_GET['opt_'.$prop_id]));die;
						$option=json_decode($_GET['opt_'.$prop_id]);
						
						foreach($option as $opt_value) {
							$opt_value = trim($opt_value);
								if($opt_value."" <> ""){
								$fld_p_arr = array();
								$fld_p_arr["prod_id"] = $id;
								$fld_p_arr["prop_id"] = $prop_id;
								$fld_p_arr["opt_value"] = $opt_value;
								$qry = func_insert_qry("prod_props",$fld_p_arr);
								execute_qry($qry);
							}
						}
					}

					$opt_arr = explode("\n",func_read_qs("opt_new_".$prop_id));
					if(func_read_qs("opt_new_".$prop_id)."" <> ""){
						for($j=0;$j<count($opt_arr);$j++){
							$fld_p_arr = array();
							$fld_p_arr["prod_id"] = $id;
							$fld_p_arr["prop_id"] = $prop_id;
							$fld_p_arr["opt_value"] = trim($opt_arr[$j]);
							$qry = func_insert_qry("prod_props",$fld_p_arr);
							execute_qry($qry);
							if(!get_rst("select prop_id from prop_options where prop_id=".$prop_id." and opt_value='".$opt_arr[$j]."'")){
								$fld_pm_arr = array();
								$fld_pm_arr["prop_id"] = $prop_id;
								$fld_pm_arr["opt_value"] = trim($opt_arr[$j]);
								$qry = func_insert_qry("prop_options",$fld_pm_arr);
								execute_qry($qry);
							}					

						}
					}
				}
			}
			$msg="record saved successfully";
			$response['status']=$msg;
			echo json_encode($response);
		}
	}
?>