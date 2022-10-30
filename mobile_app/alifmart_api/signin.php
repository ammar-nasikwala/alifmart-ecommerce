<?php

date_default_timezone_set('Asia/Calcutta');

ini_set('display_errors', 1); 
/* $connection = @ssh2_connect('13.76.102.83', 22);  
ssh2_auth_password($connection, 'leometric', '|30M3tr!c'); 
$tunnel = ssh2_tunnel($connection, '13.76.102.83', 3307); 
 */
/* $db_servername = '127.0.0.1';
//$db_servername = 'localhost';
$db_username = "leometric.com";
$db_password = "@|!fM@rt";
$db_name = "Company-Name";
$env_prod = false; */

//$db_servername = '103.21.58.112';
include("db.php");

 
//print_r($pwd);die;
//$sql="select * from member_mast where memb_email='".$email."' and memb_pwd='".$pwd."'";
//print_r($sql);die;
if(strtoupper($_GET['user'])=="CUSTOMER")
{
	
$email=addslashes($_GET['email']);
$pwd=addslashes($_GET['password']);

$rsMemb= mysqli_query($con,"select * from member_mast where memb_email='".$email."' and memb_pwd='".$pwd."'");
		if(mysqli_num_rows($rsMemb)>0){
			//print_r("jdfj");die;
			$row = mysqli_fetch_assoc($rsMemb);
			$memb_id = $row["memb_id"];
		//	$sql="select * from member_mast where memb_id=$memb_id and memb_act_status=1";
			//print_r($sql);die;
			$actQry= mysqli_query($con,"select * from member_mast where memb_id=$memb_id and memb_act_status=1 and sms_verify_status=1");	
			if(mysqli_num_rows($actQry)>0){ 
				$signin["status"] = "success";
				$signin["memb_id"] = $row["memb_id"];
				$signin["user_type"] = "M";
				$cart_query= mysqli_query($con,"select count(*) as countqty from cart_items where memb_id=$memb_id and item_wish=0");	
				if(mysqli_num_rows($cart_query)>0){
					$row = mysqli_fetch_assoc($cart_query);
					$signin["cart_qty"]=$row['countqty'];
				}
				else
				{
					$signin["cart_qty"]=0;
				}
				
				$wishlist_query= mysqli_query($con,"select count(*) as countqty from cart_items where memb_id=$memb_id and item_wish=1");	
				if(mysqli_num_rows($wishlist_query)>0){
					$row = mysqli_fetch_assoc($wishlist_query);
					$signin["whishlist_qty"]=$row['countqty'];
				}
				else
				{
					$signin["whishlist_qty"]=0;
				}
				
			}else{	
				$signin["status"] = "Your account has not been activated. Please click on activation link sent to your email to activate the account.";	
			}
		}else{
			$signin["status"] = "Invalid email and password combination";
			
		}
}
else if(strtoupper($_GET['user'])=="SELLER")
{
	//print_r($_GET);die;
	if(isset($_GET["sup_email"])){
	
	if($_GET["sup_seller_token"] == ""){
		$sup_email = addslashes($_GET["sup_email"]) ;
		$sup_pwd = addslashes($_GET["sup_pwd"]);
		
		
		//$sup_id = "";
		//mysqli_select_db($con, $db_name);
		
		//$rsMemb= mysql_query("select * from member_mast where memb_email='".$sup_email."' and memb_pwd='".$sup_pwd."'");

		$rst = mysqli_query($con,"select sup_id,sup_delete_account,sup_seller_token from sup_mast where sup_email='$sup_email' and sup_pwd='$sup_pwd'");
		
		if (!$rst) {
			$signin['status']  = 'Invalid query: ' . mysqli_error() . '\n';
		}
		if(mysqli_num_rows($rst)>0){
		
			$act_check = mysqli_query($con,"select sup_id from sup_mast where sup_email='$sup_email' and sup_active_status=1");
			if(mysqli_num_rows($act_check)<=0){
				$signin['status'] = "Your account is not activated. Please activate your account by clicking the activation link send to your email.";	
			}
			else{
				$row = mysqli_fetch_assoc($rst);
				$sub_delete_account=$row["sup_delete_account"];
				if(!$sub_delete_account){
					
					if($row["sup_seller_token"] <> ""){
						$signin['status'] = "Token";
					}
					else{
						$signin["user"]="1";
						$signin["sup_id"] = $row["sup_id"];
						//die( $_SESSION["sup_id"]);
					}
					
				}else{
					$signin['status'] = "Account does not Exist.";
				}
				
				//die( $_SESSION["sup_id"]);
				//echo "<meta http-equiv='refresh' content='0;url=../seller_update.php'>";
				//exit();
			}
		}else{
			$signin['status'] = "Authentication failed! Please enter valid details.";
		}
	}else{
	
		$sup_email = addslashes($_GET["sup_email"]) ;
		$sup_pwd = addslashes($_GET["sup_pwd"]);
		$sup_seller_token = addslashes($_GET["sup_seller_token"]);
		//$sup_id = "";
		//mysqli_select_db($con, $db_name);
		

		$rst = mysqli_query($con,"select sup_id, sup_lmd,sup_mou_accept,sup_delete_account from sup_mast where sup_email='$sup_email' and sup_pwd='$sup_pwd' and sup_seller_token='$sup_seller_token'");
		
		if (!$rst) {
			$signin['status']  = 'Invalid query: ' . mysqli_error() . '\n';
			//die($msg);
		}
		if(mysqli_num_rows($rst)>0){
		//print_r($_GET);die;
			$act_check = mysqli_query($con,"select sup_id from sup_mast where sup_email='$sup_email' and sup_active_status=1");
			if(mysqli_num_rows($act_check)<=0){
				$signin['status'] = "Your account is not activated. Please activate your account by clicking the activation link send to your email.";	
			}
			else{
				$row = mysqli_fetch_assoc($rst);
				$sub_delete_account=$row["sup_delete_account"];
				if(!$sub_delete_account){
					$signin['status']="success";
					$signin["user"]="1";
					$signin["sup_id"] = $row["sup_id"];
					$signin["seller_vfd"] = "1";
					$signin["sup_lmd"] = $row["sup_lmd"];
					$signin["user_type"] = "S";
					$signin["mou_status"] =$row["sup_mou_accept"] ;
				//echo "<meta http-equiv='refresh' content='0;url=mainmenu.php'>";
				//exit();
				}else{
					$msg = "Account does not Exist. Please register as a seller or login with a different account.";
				}
				
			}
		}else{
			$signin['status'] = "Authentication failed! Please enter valid details.";
		}
	}
}
}
			echo json_encode($signin);
?>