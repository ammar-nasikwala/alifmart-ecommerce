<?php
session_start();
unset($_SESSION["saas_user"]);
//unset($_SESSION["tree"]);
//session_destroy();
header("location: ../saas/index.php");

?>