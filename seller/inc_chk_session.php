<?php
$session_user = "";
if(isset($_SESSION["user"]) || isset($_SESSION["sup_id"])){
	$session_user = 1;
}
if($session_user==""){	?>
	<script>
		alert("Your session timed out. Please login again.");
		window.location.href="index.php";
	</script>
	<?php
}
?>