<?php
session_start();
//unset($_SESSION["user"]);
//unset($_SESSION["sup_id"]);
//unset($_SESSION["seller_vfd"]);
session_destroy();
$_SESSION = array();
header("location: ../seller/index.php");

?>