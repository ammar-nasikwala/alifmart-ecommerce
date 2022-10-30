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

$sql = "Select * from cms_pages ";

if($page_name<>"")
{
	$sql.=" where page_name='$page_name'";
}	

//$sql.=" group by page_name";			
//print_r($sql);die;
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 
$cms_pages['cms_pages']=array();

	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			
			//$row["sup_company"];
			$temp['page_title'] = $row["page_title"];
			$temp['page_name'] = $row["page_name"];
			$temp['meta_key'] = $row["meta_key"];
			$temp['meta_desc'] = $row["meta_desc"];
			$temp['middle_panel'] = $row["middle_panel"];
			
			array_push($cms_pages['cms_pages'],$temp);
		}
		
	}
	
 echo json_encode($cms_pages);die;
 
?>