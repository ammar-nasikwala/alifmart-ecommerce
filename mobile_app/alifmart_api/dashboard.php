<?php

include("db.php");
include("functions.php");

ini_set('display_errors', 1); 

$sup_id=$_GET['sup_id'];

	$response['top_selling_product']=array();
		if(get_rst("select prod_id,prod_name,prod_purchased from prod_mast where prod_purchased is not null and prod_id in (select prod_id from prod_sup where sup_id=$sup_id) order by prod_purchased desc LIMIT 0 , 5",$row,$rst)){
					do{
						
							$temp['prod_id']=$row["prod_id"];
							$temp['prod_name']=$row["prod_name"];
							$temp['prod_purchased']=$row["prod_purchased"];
							
						array_push($response['top_selling_product'],$temp)	;
						
					}while($row = mysqli_fetch_array($rst));
				}
	
	get_rst("select count(0) as tot from ord_summary where delivery_status='Pending' and cart_id in (select cart_id from ord_items where sup_id=$sup_id)",$row);
	$ord_pending = $row["tot"];
	get_rst("select count(0) as tot from ord_summary where cart_id in (select cart_id from ord_items where sup_id=$sup_id)",$row);
	$ord_total = $row["tot"];
	get_rst("select count(0) as tot from prod_mast where prod_status = 1 and prod_id in (select prod_id from prod_sup where sup_id=$sup_id)",$row);
	$prod_total = $row["tot"];
	
	
	$response['order_awaiting_dispatch']=$ord_pending;	
	$response['total_order']=$ord_total;	
	$response['total_active_product']=$prod_total;	

		
		echo json_encode($response);
					
					
?>