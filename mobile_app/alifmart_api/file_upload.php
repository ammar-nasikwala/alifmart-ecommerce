<?php

include("db.php");
include("functions.php");

ini_set('display_errors', 1); 
//$temp_file = tempnam(sys_get_temp_dir(), 'Tux');

//echo $temp_file;
//$action=addslashes($_GET['action']);
//print_r($_FILES);die;
//print_r($_GET);die;
$memb_id = func_read_qs("memb_id");
$type = func_read_qs("type");
	
 $file_name=$_FILES['files']['name'];
 
 //$desired_dir="/home/azureuser/repo/Company-Name_v1.0/extras/user_data/".$memb_id;
 $desired_dir=$_SERVER['DOCUMENT_ROOT']."/extras/user_data/".$memb_id;
//print_r($desired_dir);die;
if(is_dir($desired_dir)==false){
		mkdir("$desired_dir", 0777);		// Create directory if it does not exist
	}
	
//die;

$file_tmp =$_FILES['files']['tmp_name'];
//$file_path=$file_tmp;
$file_path=$_SERVER['DOCUMENT_ROOT']."/extras/user_data/".$memb_id."/".$file_name;
move_uploaded_file($file_tmp,$file_path);  

//$temp_image=getimagesize($file_path);
	//	list($w_orig, $h_orig) = getimagesize($file_path);
//print_r($w_orig."height".$h_orig);die;
//print_r($temp_image);die;

$imageFileType = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));
$upload_doc_arr=img_resize_db($file_path, 900, 520, $imageFileType);

//print_r($upload_doc_arr);die;
//$type='cst';
if($type=='vat')
{
 $fld_arr["memb_vat_doc"] = $upload_doc_arr;

}
else if($type=='cst')
{
 $fld_arr["memb_cst_doc"] = $upload_doc_arr;
}
					 
$sqlIns = func_update_qry("member_mast",$fld_arr," where memb_id=".$memb_id); 

if (!mysqli_query($con,$sqlIns)) {
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