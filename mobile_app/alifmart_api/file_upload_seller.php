<?php

include("db.php");
include("functions.php");

ini_set('display_errors', 1); 
//$temp_file = tempnam(sys_get_temp_dir(), 'Tux');

//echo $temp_file;
//$action=addslashes($_GET['action']);
//print_r($_FILES);die;
//print_r($_GET);die;
$sup_id = func_read_qs("sup_id");
$type = func_read_qs("type");
	
//print_r($_GET);die;	
	
 $file_name=$_FILES['files']['name'];
 
 $desired_dir="/home/azureuser/repo/Company-Name_v1.0/extras/user_data/id-".$sup_id;
//print_r($desired_dir);die;
if(is_dir($desired_dir)==false){
		mkdir("$desired_dir", 0777);		// Create directory if it does not exist
	}
	
//die;

$file_tmp =$_FILES['files']['tmp_name'];
//$file_path=$file_tmp;
$file_path=$_SERVER['DOCUMENT_ROOT']."/extras/user_data/id-".$sup_id."/".$file_name;
move_uploaded_file($file_tmp,$file_path);  



$imageFileType = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));
$upload_doc_arr=img_resize_db($file_path, 1200, 900, $imageFileType);


if($type=='logo')
{
 $fld_arr["sup_logo"] = $upload_doc_arr;
}
else if($type=='shop_act_license')
{
 $fld_arr["sup_shop_act_license"] = $upload_doc_arr;
}
else if($type=='pan_doc')
{
 $fld_arr["sup_pan_doc"] = $upload_doc_arr;
}
else if($type=='vat_doc')
{
 $fld_arr["sup_vat_doc"] = $upload_doc_arr;
}
else if($type=='cst_doc')
{
 $fld_arr["sup_cst_doc"] = $upload_doc_arr;
}
else if($type=='imp_exp_license')
{
 $fld_arr["sup_imp_exp_license"] = $upload_doc_arr;
}
else if($type=='dlr_doc')
{
 $fld_arr["sup_dlr_doc"] = $upload_doc_arr;
}
else if($type=='iso_doc')
{
 $fld_arr["sup_iso_doc"] = $upload_doc_arr;
}
else if($type=='bk_can_chk')
{
 $fld_arr["sup_bk_can_chk"] = $upload_doc_arr;
}
					 
					 
$qry = func_update_qry("sup_mast",$fld_arr," where sup_id=".$sup_id);
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