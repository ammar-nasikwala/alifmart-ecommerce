<?php
require("../lib/inc_connection.php");

class Users {
	public $tableName = 'member_mast';

	
function checkUser($memb_id,$oauth_uid,$fname,$lname,$email){
 global $con;
$_SESSION['google_data']['email']=$email;
		$prevQuery = mysqli_query($con,"SELECT memb_id FROM $this->tableName WHERE oauth_uid = '".$oauth_uid."' OR memb_email='".$email."'");
		if(mysqli_num_rows($prevQuery) > 0){
			$update = mysqli_query($con,"UPDATE $this->tableName SET oauth_uid = '".$oauth_uid."', memb_fname = '".$fname."', memb_sname = '".$lname."', memb_email = '".$email."' WHERE oauth_uid = '".$oauth_uid."' OR memb_email='".$email."'");
		}else{//header("location: http://".$_SERVER["SERVER_NAME"]."/test.php");
			$insert = mysqli_query($con,"INSERT INTO $this->tableName SET memb_id = '".$memb_id."', oauth_uid = '".$oauth_uid."', memb_fname = '".$fname."', memb_sname = '".$lname."', memb_email = '".$email."'");
       
		
    }
		
/*		$query = mysqli_query($con,"SELECT oauth_uid,memb_id,memb_fname,memb_sname,memb_email FROM $this->tableName WHERE oauth_uid = '".$oauth_uid."'");
		$result = mysqli_fetch_array($query);
		return $result;*/
	}
}
?>