<?php
include 'inc_connection.php';
include 'inc_library.php';
$msg='';
if(!empty($_GET['code']) && isset($_GET['code']))
{
	$code=mysqli_real_escape_string($con,$_GET['code']);
	$c=mysqli_query($con,"SELECT sup_id FROM sup_mast WHERE sup_activation='$code'");

	if(mysqli_num_rows($c) > 0)
	{
		$count=mysqli_query($con,"SELECT sup_id FROM sup_mast WHERE sup_activation='$code' and sup_active_status='0'");

		if(mysqli_num_rows($count) == 1)
		{
			mysqli_query($con,"UPDATE sup_mast SET sup_active_status='1' WHERE sup_activation='$code'");
			$msg="Your account has been activated successfully. Click <a href='"http://www.Company-Name.com/seller/login.php"'>here</a> to login.";
		}
		else
		{
			$msg ="Your account is already active!";
		}

	}
	else
	{
		$msg ="Wrong activation code.";
	}

}
?>

<?php func_display_msg($msg)?>