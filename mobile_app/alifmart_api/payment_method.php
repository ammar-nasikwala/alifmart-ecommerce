<?php

include("db.php");
include("functions.php");

$payment_method['payment_method']=array();

get_rst("select * from pay_method where pay_status<>0 order by pay_sort",$row,$rst);

	do{
			$temp['pay_status'] = $row["pay_status"];
			$temp['pay_code'] = $row["pay_code"];
			$temp['pay_name'] = $row["pay_name"];
			
			array_push($payment_method['payment_method'],$temp);
			
	}while($row = mysqli_fetch_array($rst));
			
	echo json_encode($payment_method);
?>