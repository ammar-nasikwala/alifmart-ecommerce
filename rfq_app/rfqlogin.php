<?php

include("../lib/inc_connection.php");
include("../mobile_app/Company-Name_api/functions.php");

$email=addslashes($_GET['email']);
$pwd=addslashes($_GET['password']);

/*$rsMemb= mysqli_query($con,"select user_name from user_mast where user_email='".$email."' and user_pwd='".$pwd."'");
		if(mysqli_num_rows($rsMemb)>0){
			$row = mysqli_fetch_assoc($rsMemb);
			$signin["status"] = "success";
			$signin["user_name"] = $row["user_name"];
			
		}else{
			$signin["status"] = "Invalid email and password combination";
			
		}*/
	if(get_rst("select * from configuration where admin_user='$email' and admin_pwd='$pwd'",$row,$rst)){
		$signin["status"] = "success";
		$signin["user_name"] = $row["admin_user"];
	}else{
		if(get_rst("select * from user_mast where user_email='$email' and user_pwd='$pwd'",$row,$rst)){
			$signin["status"] = "success";
			$signin["user_name"] = $row["user_name"];
		}else{
			$signin["status"] = "Invalid email and password combination";
		}
	}
		echo json_encode($signin);
?>