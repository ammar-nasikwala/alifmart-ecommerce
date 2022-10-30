<?php

require_once("inc_init.php");

require_once("lib/inc_library.php");

require_once("payu_response.php");


save_order("CC",$cart_id);

$sql = "update ord_summary set pg_status='$status', pg_txnid='$txnid',pay_status='Paid' where cart_id=$cart_id";
execute_qry($sql);

header('location: ord_confirmation.php');
js_redirect("ord_confirmation.php");

?>