<?php
session_start();
//unset($_SESSION["admin"]);
//unset($_SESSION["tree"]);
session_destroy();
header("location: ../admin/index.php");

?>