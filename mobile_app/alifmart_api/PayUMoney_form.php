<?php
// Merchant key here as provided by Payu
$MERCHANT_KEY = "5180664";

// Merchant Salt as provided by Payu
$SALT = "tAyajrnI";

// End point - change to https://secure.payu.in for LIVE mode
//$PAYU_BASE_URL = "https://test.payu.in";
$PAYU_BASE_URL = "https://secure.payu.in";

$action = '';

$posted = array();

//print_r($_POST);die;
if(!empty($_POST)) {
    //print_r($_POST);
  foreach($_POST as $key => $value) {    
    $posted[$key] = $value; 
	
  }
}

$formError = 0;

if(empty($posted['txnid'])) {
  // Generate random transaction id
  $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
} else {
  $txnid = $posted['txnid'];
}
$hash = '';
// Hash Sequence
$hashSequence = "key|txnid|amount|productInfo|firstName|email|udf1|udf2|udf3|udf4|udf5";
if(empty($posted['hash']) && sizeof($posted) > 0) {
	$posted['service_provider']="payu_paisa";
   if(     empty($posted['key'])
          || empty($posted['txnid'])
          || empty($posted['amount'])
          || empty($posted['firstName'])
          || empty($posted['email'])
          || empty($posted['phone'])
          || empty($posted['productInfo'])
          || empty($posted['SURL'])
          || empty($posted['FURL'])
		  || empty($posted['service_provider'])
  ) {
//	print_r($posted);die;
    $formError = 1;
  } else { 
 // print_r("in else");
 // print_r($posted);die;
    //$posted['productinfo'] = json_encode(json_decode('[{"name":"tutionfee","description":"","value":"500","isRequired":"false"},{"name":"developmentfee","description":"monthly tution fee","value":"1500","isRequired":"false"}]'));
	$hashVarsSeq = explode('|', $hashSequence);
    $hash_string = '';	
	foreach($hashVarsSeq as $hash_var) {
      $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
      $hash_string .= '|';
    }

	//print_r($hash_string);die;
    $hash_string .= $SALT;


    $hash = strtolower(hash('sha512', $hash_string));
	
	//print_r($hash);die;
    $action = $PAYU_BASE_URL . '/_payment';
	$response['status']="1";
	$response['result']=$hash;
	echo json_encode($response);
  }
} elseif(!empty($posted['hash'])) {
  $hash = $posted['hash'];
  $action = $PAYU_BASE_URL . '/_payment';
}
?>
