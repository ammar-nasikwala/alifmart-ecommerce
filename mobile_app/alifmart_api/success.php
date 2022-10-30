<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<title>PayUMoney</title>
<body onload="PayUMoney.success(1,'<?php print htmlspecialchars($_POST['payuMoneyId']); ?>')">
<?php

include("db.php");
include("functions.php"); 
ini_set('display_errors', 1); 

//print_r($_POST);die;
$status=$_POST["status"];
$firstname=$_POST["firstName"];
$amount=$_POST["amount"];
$txnid=$_POST["txnid"];
$posted_hash=$_POST["hash"];
$key=$_POST["key"];
$productinfo=$_POST["productinfo"];
$email=$_POST["email"];
$salt="tAyajrnI";


If (isset($_POST["additionalCharges"])) {
       $additionalCharges=$_POST["additionalCharges"];
        $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
        
        }
	else {	  

        $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;

         }
		 
		 $hash = hash("sha512", $retHashSeq);
		/*  echo "<h3>Thank You. Your order status is ". $status .".</h3>";
          echo "<h4>Your Transaction ID for this transaction is ".$txnid.".</h4>";
          echo "<h4>We have received a payment of Rs. " . $amount . ". Your order will soon be shipped.</h4>";
		  echo "<button type='button' id='ok' onclick='performClick();'>ok</button>"; */
		  
        if ($hash != $posted_hash) {
			//print_r($hash."  ".$posted_hash);
	       /* echo "Invalid Transaction. Please try again";
		  echo "<div><button type='button' id='ok' style='font-weight: 700; margin-right: 20px;' onclick='validClick();'>Accept</button>
     <button type='button' id='no' onclick='refuseClick();'>Reject</button></div>"; */
	 echo "<h3>Thank You. Your order status is ". $status .".</h3>";
          echo "<h4>Your Transaction ID for this transaction is ".$txnid.".</h4>";
          echo "<h4>We have received a payment of Rs. " . $amount . ". Your order will soon be shipped.</h4>";
		   }
	   else {
           	   
          echo "<h3>Thank You. Your order status is ". $status .".</h3>";
          echo "<h4>Your Transaction ID for this transaction is ".$txnid.".</h4>";
          echo "<h4>We have received a payment of Rs. " . $amount . ". Your order will soon be shipped.</h4>";
		  //echo "<button type='button' id='ok' onclick='performClick();'>ok</button>";
           
		   }  


// save order

$memb_id = func_read_qs("udf1");
$session_cart_id = func_read_qs("udf2");
$session_user_type = func_read_qs("udf3");

$response['status']="fail";


	$file = fopen("test1.txt","w");
	fwrite($file,$memb_id." ".$session_cart_id." ".$session_user_type);
	fclose($file);
if(get_rst("select pay_method from cart_summary where user_id=$memb_id",$row)){
	
	$pay_method=$row["pay_method"];
	$file = fopen("test.txt","w");
	fwrite($file,$pay_method);
	fclose($file);
	switch($pay_method){
				/* case "CC":
					pay_by_cc();
					break;
					
				case "CCZ":
					pay_by_ccZ();
					break; */

				default:
				if($pay_method="CC"){
						$status=func_read_qs("status");
						$txnid=func_read_qs("txnid");
						$cart_id=func_read_qs("udf2");
						save_order($pay_method,$memb_id,$session_cart_id,$session_user_type,$session_cart_id);
						$sql = "update ord_summary set pg_status='$status', pg_txnid='$txnid',pay_status='Paid' where cart_id=$cart_id";
						execute_qry($sql);
						
						
						sendcustomernotification_order($cart_id,$memb_id,$pay_method);
						sendsellernotification_order($cart_id); 
						
					}
					else
					{
						save_order($pay_method,$memb_id,$session_cart_id,$session_user_type);
						$message['title']="Order Details";
						
						//print_r(json_encode($message));die;
						//sendcustomernotification_order($session_cart_id,$memb_id);
						sendcustomernotification_order($session_cart_id,$memb_id,$pay_method);
						sendsellernotification_order($session_cart_id); 
					}
				//save_order($pay_method,$memb_id,$session_cart_id,$session_user_type);
				//header('location: ord_confirmation.php');
				//js_redirect("ord_confirmation.php");
					$response['status']="success";
					break;

			}
}
			
	//echo json_encode($response);		   
?>	

</body>
<html>
