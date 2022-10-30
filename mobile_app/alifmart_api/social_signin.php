<?php

date_default_timezone_set('Asia/Calcutta');

ini_set('display_errors', 1); 

include("db.php");
include("functions.php");
 

$sms_verify_code = mt_rand(1000, 9999);	
$email=$_GET["email"];
$oauth_uid=$_GET["oauth_uid"];
$fname=$_GET["fname"];
$lname=$_GET["lname"];
$sms_verify_status=0;


$prevQuery = mysqli_query($con,"SELECT * FROM member_mast WHERE oauth_uid = '".$oauth_uid."' OR memb_email='".$email."'");
		if(mysqli_num_rows($prevQuery) > 0){
			$update = mysqli_query($con,"UPDATE member_mast SET oauth_uid = '".$oauth_uid."', memb_fname = '".$fname."', memb_sname = '".$lname."', memb_email = '".$email."' WHERE oauth_uid = '".$oauth_uid."' OR memb_email='".$email."'");
			
			$row = mysqli_fetch_assoc($prevQuery);
	
			//return $row["max_id"];
			$response['memb_id']=$row["memb_id"];
			$response['user_type']='M';
			$response['memb_tel']=$row["memb_tel"];
			$response['memb_fname']=$row["memb_fname"];
			$response['memb_cstn']=$row["memb_cstn"]; 
			$response['memb_vat']=$row["memb_vat"]; 
			$response['ind_buyer']=$row["ind_buyer"]; 
			//print_r($row["memb_tel"]);die;
			if($row["memb_act_status"]==1)
			{
				$response["status"] = "update";
			}
			else
			{
				$response["status"] = "insert";
			}
			
			$memb_id=$row["memb_id"];
			
			$cart_query= mysqli_query($con,"select count(*) as countqty from cart_items where memb_id=$memb_id and item_wish=0");	
				if(mysqli_num_rows($cart_query)>0){
					$row = mysqli_fetch_assoc($cart_query);
					$response["cart_qty"]=$row['countqty'];
				}
				else
				{
					$response["cart_qty"]=0;
				}
				
				$wishlist_query= mysqli_query($con,"select count(*) as countqty from cart_items where memb_id=$memb_id and item_wish=1");	
				if(mysqli_num_rows($wishlist_query)>0){
					$row = mysqli_fetch_assoc($wishlist_query);
					$response["whishlist_qty"]=$row['countqty'];
				}
				else
				{
					$response["whishlist_qty"]=0;
				}
			
		}else{
			//header("location: http://".$_SERVER["SERVER_NAME"]."/test.php");
			$memb_id= get_max('member_mast','memb_id');
			$insert = mysqli_query($con,"INSERT INTO member_mast SET memb_id = '".$memb_id."', oauth_uid = '".$oauth_uid."', memb_fname = '".$fname."', memb_sname = '".$lname."', memb_email = '".$email."'");
			
			
			$response['memb_id']=$memb_id;
			$response['user_type']='M';
			$response["status"] = "insert";
			$response["cart_qty"]=0;
			$response["whishlist_qty"]=0;
		}
				  
			echo json_encode($response);
?>