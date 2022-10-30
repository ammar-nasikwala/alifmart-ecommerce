<?php
require("../lib/inc_connection.php");
//require 'dbconfig.php';
function get_max($TableName,$fieldName){//getting max memb_id
	global $con;
	$sqlMax = "select IFNULL(max($fieldName),0)+1 as max_id from $TableName";
		
	$rstMax= mysqli_query($con, $sqlMax);
	$row = mysqli_fetch_assoc($rstMax);

	return $row["max_id"];
}

function checkuser($oauth_uid,$fname,$lname,$email){
global $con;

 $tableName = 'member_mast';
 $memb_id = get_max("member_mast","memb_id");
 
 $prevQuery = mysqli_query($con,"SELECT memb_id FROM $tableName WHERE oauth_uid = '".$oauth_uid."' OR memb_email='".$email."'");
		if(mysqli_num_rows($prevQuery) > 0){
			$update = mysqli_query($con,"UPDATE $tableName SET oauth_uid = '".$oauth_uid."', memb_fname = '".$fname."', memb_sname = '".$lname."' WHERE oauth_uid = '".$oauth_uid."' OR memb_email='".$email."'");
		}else{
			$insert = mysqli_query($con,"INSERT INTO $tableName SET memb_id = '".$memb_id."', oauth_uid = '".$oauth_uid."', memb_fname = '".$fname."', memb_sname = '".$lname."', memb_email = '".$email."'");
		}
}?>
