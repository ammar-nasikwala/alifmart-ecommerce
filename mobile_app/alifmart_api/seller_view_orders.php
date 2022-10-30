<?php

include("db.php");

ini_set('display_errors', 1); 
$sup_id=addslashes($_GET['sup_id']);
$dls=addslashes($_GET['dls']);

$sql="select ord_id";
$sql = $sql.",ord_no";
$sql = $sql.",ord_date";
$sql = $sql.",bill_name";
$sql = $sql.",ord_total";
$sql = $sql.",item_count";
$sql = $sql.",pay_status";
$sql = $sql.",delivery_status";

if($dls == 1){
	$sql = $sql." from ord_summary os join ord_details od on os.cart_id=od.cart_id where os.sup_id=$sup_id and (delivery_status='Pending' or delivery_status='Ready-to-ship') and pay_status='Paid'";
}else{
	$sql = $sql." from ord_summary os join ord_details od on os.cart_id=od.cart_id where os.sup_id=$sup_id";
}

//echo($sql);
//$sql = "select * from vw_ord_summary where cart_id in (select cart_id from ord_summary where sup_id=$sup_id)";
//$sql = $sql." where os.cart_id in (select oi.cart_id from ord_items oi where sup_id=".func_var($sup_id).") ";
if(!empty($_GET['delivery_status'])){
//$delivery_status="Pending";
$sql = $sql." AND delivery_status='Pending' ";
}
$sql = $sql." order by ord_id desc";


//print_r($sql);die;
$rs_find = mysqli_query($con,$sql);
//$cart_id = func_read_qs("id");

//print_r($rs_find);die; 
 $order_products['ordered_products']=array();
 
 $lmdseller=mysqli_query($con,"select sup_lmd from sup_mast where sup_id=$sup_id");
 $row_lmd = mysqli_fetch_assoc($lmdseller);

	$sup_lmd=$row_lmd["sup_lmd"];
	 if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			
			$temp['ord_id'] = $row["ord_id"];
			$temp['ord_no'] = $row["ord_no"];
			$temp['ord_date'] = $row["ord_date"];
			$temp['bill_name'] = $row["bill_name"];
			$temp['ord_total'] = $row["ord_total"];
			$temp['item_count'] = $row["item_count"];
			$temp['pay_status'] = $row["pay_status"];
			$temp['delivery_status'] = $row["delivery_status"];
			$temp['sup_lmd'] = $sup_lmd;
			
			array_push($order_products['ordered_products'],$temp);
		} 
		
	}
		
	echo json_encode($order_products); 
?>