<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);
date_default_timezone_set('Asia/Calcutta');
//$db_servername = '107.180.1.224';
$db_servername = 'localhost';
$db_username = "Company-Namedba";
$db_password = "@|!fM@rt";
$db_name = "Company-Name";
$env_prod = true;
$enable_sms = true;

$con = mysqli_connect($db_servername,$db_username,$db_password);

if (!$con)
{
die('Could not connect to the server at the moment!  ');
}
mysqli_select_db($con, $db_name);
$company_title = "Company-Name";
$base_url=	"http://".$_SERVER["SERVER_NAME"]."/seller/activation.php?code=";
$fgt_base_url=	"http://".$_SERVER["SERVER_NAME"]."/seller/reset_pwd.php?code=";
$_SESSION["signin_msg"]="";

$url_root = "";
if($env_prod){
	$url_root = "https://www.Company-Name.com/";
	$sales = "sales@Company-Name.com";
	$info = "info@Company-Name.com";
	$orders = "orders@Company-Name.com";
}else{
	$url_root = "http://localhost:1080/";
	$sales = "azizmtinwala52@gmail.com";
	$info = "azizmtinwala52@gmail.com";
	$orders = "azizmtinwala52@gmail.com";
}
?>

