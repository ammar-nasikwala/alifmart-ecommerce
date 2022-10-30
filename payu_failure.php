<?php
require_once("lib/inc_library.php");

require_once("payu_response.php");

$sql = "update cart_summary set pg_status='$status', pg_txnid='$txnid' where cart_id=0$cart_id";

$_SESSION["pg_failed"] = "1";
js_redirect("checkout.php");
?>