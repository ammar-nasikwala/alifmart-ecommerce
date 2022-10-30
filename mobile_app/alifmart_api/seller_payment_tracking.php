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

//print_r($_GET);
$sup_id=addslashes($_GET['sup_id']);
//print_r($sup_id);die;
$sql="select invoice_id";
$sql = $sql.",ord_no as 'ord_no'"; 
$sql = $sql.",pay_total as 'pay_total'"; 
$sql = $sql.",pay_status as 'pay_status'"; 
$sql = $sql." from invoice_mast I where sup_id=".$sup_id." ";
$sql_where = "";

$sql = $sql.$sql_where." order by ord_no desc";
//echo $sql;die;
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 
$order_list['order_list']=array();

	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find))
		{
			
			$temp['invoice_id'] = $row["invoice_id"];
			$temp['ord_no'] = $row["ord_no"];
			$temp['pay_total'] = $row["pay_total"];
			$temp['pay_status'] = $row["pay_status"];
			
			array_push($order_list['order_list'],$temp);
		
		}
	}
		
	echo json_encode($order_list);
?>