<?php

include("db.php");


$memb_id=addslashes($_GET['memb_id']);
$sql="select * from cart_items where memb_id=$memb_id and item_wish=1";
//print_r($sql);die;
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 
$wish_list['wish_list']=array();

	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			
			$temp['cart_item_id'] = $row["cart_item_id"];
			$temp['cart_price'] = $row["cart_price"];
			$temp['cart_qty'] = $row["cart_qty"];
			$temp['prod_id'] = $row["prod_id"];
			$temp['cart_price_tax'] = ($row["cart_price_tax"]*$row["cart_qty"]);
			$temp['tax_percent'] = $row["tax_percent"];
			$temp['item_name'] = $row["item_name"];
			$temp['item_thumb'] = base64_encode($row["item_thumb"]);
			
			$sql="select sup_status,out_of_stock from prod_sup where prod_id=".$row["prod_id"]." and sup_id=".$row["sup_id"];
			//print_r($sql);die;
			$rs_find1 = mysqli_query($con,$sql);
			
			if($rs_find1)
			{
				$rowcount=mysqli_num_rows($con,$rs_find1);
				//print_r($rowcount);die;
				if($rowcount>0)
				{
					$row_ps = mysqli_fetch_assoc($rs_find1);
					$temp['sup_status'] = $row_ps["sup_status"];
					$temp['out_of_stock'] = $row_ps["out_of_stock"];
				}
				else
				{
					$temp['sup_status'] =0;
					$temp['out_of_stock'] = 0;
				}
			}
			
			
			
			array_push($wish_list['wish_list'],$temp);
		}
		
	}
		
	echo json_encode($wish_list);
?>