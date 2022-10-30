<?php
 
include("db.php");
include("functions.php");

$message['title']="Order Detail";
$message['notification']=array(
	'title'=>"Order Detail",
	'text'=>"Normal",
	'click_action'=>'FcmNotificationActivity'
	);
	
$message['data']=array(
		'activity'=>'FcmNotificationActivity',
		//'id_offer'=>$cart_id,	
		'icon'=>"http://alif.cloudapp.net/images/logo.png",
		
);

$message['registration_ids'][]="fcVKFpgB5Yg:APA91bHnH0YHA4GpiTNlEFSqlHhpIuq7DdXv90di4VLXXRub8NXGRASoX1IxSm_clY9_xemNlVGUWgpr2o160osOq2vfH7WKi3mTfMd5-ub7J4FwMLsyfwtiXI-wDdMghrLZA4-0jzBo";

						//print_r(json_encode($message));die;
sendnotification(json_encode($message));
?>