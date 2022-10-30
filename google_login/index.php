<?php

include_once("includes/functions.php");
include_once("config.php");
  
function get_max($TableName,$fieldName){//getting max memb_id
	global $con;
	$sqlMax = "select IFNULL(max($fieldName),0)+1 as max_id from $TableName";
		
	$rstMax= mysqli_query($con, $sqlMax);
	$row = mysqli_fetch_assoc($rstMax);
	
	return $row["max_id"];
}



if(isset($_REQUEST['code'])){
	$gClient->authenticate();
	$_SESSION['token'] = $gClient->getAccessToken();
	header('Location: ' . filter_var($redirectUrl, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {

	$gClient->setAccessToken($_SESSION['token']);
 unset($_SESSION['token']);
}

if ($gClient->getAccessToken()) { //create user block
	$userProfile = $google_oauthV2->userinfo->get();
	//DB Insert
	$gUser = new Users();
    $memb_id = get_max("member_mast","memb_id");
   //todo: calculate memb_id over here and pass it to checkUser
	$gUser->checkUser($memb_id,$userProfile['id'],$userProfile['given_name'],$userProfile['family_name'],$userProfile['email']);
	$_SESSION['google_data'] = $userProfile; // Storing Google User Data in Session
	//todo: set session variables required after user login. check header.php
 //todo: call index.php of Company-Name root instead of account.ph
 	
    $email=$_SESSION['google_data']['email'];
 
	$sql =mysqli_query($con, "select memb_id,memb_email,memb_fname,memb_tel,sms_verify_status from member_mast where memb_email='".$email."'");
	$row=mysqli_fetch_assoc($sql);
	$status=$row["sms_verify_status"];
	$_SESSION["tel_social"] =$row["memb_tel"] ;
	$_SESSION["email_social"] =$row["memb_email"] ;
    $_SESSION["fname_social"] = $row["memb_fname"];
    $tel="";
    $tel=$tel.$row["memb_tel"];
    
      if($tel.""=="")	
      {
      header("location: http://".$_SERVER["SERVER_NAME"]."/social_login.php");
      die();
      }
	if($status==1){
		if($row <> ""){
	
			$_SESSION["memb_id"] = $row["memb_id"];
			$_SESSION["memb_email"] = $row["memb_email"];
			$_SESSION["user_type"] = "M";
			$name_arr = explode(" ",$row["memb_fname"]);
			$_SESSION["memb_fname"] = $name_arr[0];
			$memb_id = $_SESSION["memb_id"];
			mysqli_query($con,"update cart_items set memb_id=$memb_id where session_id='".session_id()."' AND memb_id IS NULL");
			mysqli_query($con,"update cart_summary set user_id=$memb_id where session_id='".session_id()."' AND user_id IS NULL");
			mysqli_query($con,"update cart_details set memb_id=$memb_id where session_id='".session_id()."' AND memb_id IS NULL");				
		}
	}else{ 
		header("location: http://".$_SERVER["SERVER_NAME"]."/social_login.php");
		die();
	}  
  
   	 header("location: http://".$_SERVER["SERVER_NAME"]."/index.php");
	$_SESSION['token'] = $gClient->getAccessToken();
} else { //login block
	$authUrl = $gClient->createAuthUrl();
}

if(isset($authUrl)) {
	//echo '<a href="'.$authUrl.'"><img src="images/glogin.png" alt=""/></a>';
 header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
} 

?>