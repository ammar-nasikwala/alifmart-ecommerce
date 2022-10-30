<?php include('zaakpay_checksum.php'); ?>
<?php include('lib/inc_library.php'); ?>
<?php
// Please insert your own secret key here
$secret = 'your_secret';

$recd_checksum = $_POST['checksum'];
$all = Checksum::getAllParams();
error_log("AllParams:".$all);
error_log("Secret Key : ".$secret);
$checksum_check = Checksum::verifyChecksum($recd_checksum, $all, $secret);

Checksum::outputResponse($checksum_check);

$cart_id = func_read_qs("orderId");
$responseCode = func_read_qs("responseCode");

if($responseCode=="100"){
	require_once("inc_init.php");

	//require_once("lib/inc_library.php");

	save_order("CCZ",$cart_id);
	$sql = "update ord_summary set pg_status='$responseCode', pg_txnid='$cart_id',pay_status='Paid' where cart_id=$cart_id";
	execute_qry($sql);

	header('location: ord_confirmation.php');
	js_redirect("ord_confirmation.php");	
}else{
	$sql = "update ord_summary set pg_status='$responseCode', pg_txnid='$cart_id' where cart_id=$cart_id";
	execute_qry($sql);

	echo(func_read_qs("responseDescription"));

	?>
	<br>
	Please click here to back to <a href="index.php">Company-Name.com</a>
	<?php
}
?>
