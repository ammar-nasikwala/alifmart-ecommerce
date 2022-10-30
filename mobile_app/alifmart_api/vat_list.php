<?php

//print_r($_SERVER);die;
//header("Authorization:1234567890,X-APP-TOKEN:7d5dda4a849d41da80961b79ab6fd875");

/* $realm = 'Restricted area';

$users = array('admin' => 'mypass', 'guest' => 'guest');

if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Basic 112233 realm="'.$realm.'"');

    die('Text to send if user hits Cancel button');
}  */



include("db.php");
include("functions.php");

$page_name = func_read_qs("page_name");

ini_set('display_errors', 1); 

$sql = "select concat(tax_id,'|',tax_name,'|',tax_percent) as first,concat(tax_name,' - ',tax_percent,'%') as second from tax_mast where tax_status=1";
				
//print_r($sql);die;
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 
$vat_list['vat_list']=array();

	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			
			//$row["sup_company"];
			$temp['first_col'] = $row["first"];
			$temp['second_col'] = $row["second"];
			
			array_push($vat_list['vat_list'],$temp);
		}
		
	}
	
 echo json_encode($vat_list);die;
 
?>