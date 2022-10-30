<?php

date_default_timezone_set('Asia/Calcutta');

ini_set('display_errors', 1); 


//$db_servername = '103.21.58.112';
include("db.php");
include("functions.php");
 
$sup_id=$_GET['sup_id'];

$update = mysqli_query($con,"UPDATE sup_mast SET sup_mou_accept=1 WHERE  sup_id='".$sup_id."'");

				  
 $response['status']="success";
				  
			echo json_encode($response);
?>