<?php

include("db.php");
include("functions.php");

ini_set('display_errors', 1); 
//$temp_file = tempnam(sys_get_temp_dir(), 'Tux');

//echo $temp_file;
//$action=addslashes($_GET['action']);
//print_r($_FILES);
//die;
//print_r($_GET);die;

$prod_id = func_read_qs("prod_id");
$type = func_read_qs("type");
	
$file_name=$_FILES['files']['name'];
 
$desired_dir="/home/azureuser/repo/Company-Name_v1.0/extras/user_data/".$prod_id;
//print_r($desired_dir);die;
if(is_dir($desired_dir)==false){
		mkdir("$desired_dir", 0777);		// Create directory if it does not exist
	}
	
//die;

$file_tmp =$_FILES['files']['tmp_name'];
//$file_path=$file_tmp;
$file_path=$_SERVER['DOCUMENT_ROOT']."/extras/user_data/".$prod_id."/".$file_name;
move_uploaded_file($file_tmp,$file_path);  



$imageFileType = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));
$upload_doc_arr=img_resize_db($file_path, 1200, 900, $imageFileType);
$upload_doc_arr_thumb=img_resize_db($file_path, 180, 200, $imageFileType);


if($type=='prod_large1')
{
 $fld_arr["prod_large1"] = $upload_doc_arr;
 $fld_arr["prod_thumb1"] = $upload_doc_arr_thumb;
}
else if($type=='prod_large2')
{
 $fld_arr["prod_large2"] = $upload_doc_arr;
 $fld_arr["prod_thumb2"] = $upload_doc_arr_thumb;
}
else if($type=='prod_large3')
{
 $fld_arr["prod_large3"] = $upload_doc_arr;
 $fld_arr["prod_thumb3"] = $upload_doc_arr_thumb;
}
else if($type=='prod_large4')
{
 $fld_arr["prod_large4"] = $upload_doc_arr;
 $fld_arr["prod_thumb4"] = $upload_doc_arr_thumb;
}			 
					 
$qry = func_update_qry("prod_mast",$fld_arr," where prod_id=".$prod_id);
 //print_r($qry);die; 
if (!mysqli_query($con,$qry)) {
				if (mysqli_errno($con) == 1062) {
					$response['status'] = "The updated email is already in use by another person. Please provide another one or keep the existing one.";
				}else{
					$response['status'] = mysqli_error($con);
				}
			}else{
				$response['status']="success";
				$response['file']=base64_encode($upload_doc_arr);
			}


echo json_encode($response);   


?>