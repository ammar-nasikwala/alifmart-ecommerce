<?php
session_start(); 
require("../lib/inc_connection.php");
?>

      
<?php 
$uid=$_SESSION['uid'];
$email=$_SESSION['EMAIL'];
//echo $fid;
global $con;
 $sql = mysqli_query($con, "select memb_id,oauth_uid,memb_email,memb_fname,memb_tel,sms_verify_status from member_mast where oauth_uid='".$uid."'");
		$row=mysqli_fetch_assoc($sql);
		$status=$row["sms_verify_status"];
    $_SESSION["tel_social"] =$row["memb_tel"] ;
    $_SESSION["email_social"] =$row["memb_email"] ;
    $_SESSION["fname_social"] = $row["memb_fname"];
    $_SESSION["social_fbid"]=$row["oauth_uid"];
    
     $mail="";
     $mail=$mail.$row["memb_email"];
     $tel="";
     $tel=$tel.$row["memb_tel"];
    
      if($tel.""=="" && $mail.""=="")	
      {
      header("location: http://".$_SERVER["SERVER_NAME"]."/login_facebook.php");
      die();
      }elseif($tel.""=="")
      {
      header("location: http://".$_SERVER["SERVER_NAME"]."/social_login.php");
      die();
      }
      if($status==1){
		if($row <> ""){
				$_SESSION["user_type"] = "M";
				$_SESSION["memb_id"] = $row["memb_id"];
				$_SESSION["memb_email"] = $row["memb_email"];
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
?>
    
 