<?php 
echo "here;"
get_rst("select prod_id from prod_mast where prod_status=1", $row, $rst);
do{
	$prod_id = $row["prod_id"];
	$min_qty = 1;
	if(get_rst("select final_price from prod_sup where prod_id=$prod_id and out_of_stock is null order by final_price asc", $rw_qty){
		$min_qty = (int)(500/$rw_qty["final_price"]) + 1;
		echo "here2";
	}
	execute_qry("update prod_mast set min_qty=$min_qty where prod_id=$prod_id");
}while($row = mysqli_fetch_assoc($rst));
?>