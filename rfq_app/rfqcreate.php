<?php

include("../lib/inc_connection.php");
include("../mobile_app/Company-Name_api/functions.php");


$rfqid = func_read_qs("rfqid");
$pid = func_read_qs("pid");
$subrfq = func_read_qs("subrfq");

$customer_name = func_read_qs("customername");
$customer_email = func_read_qs("email");
$contact_no = func_read_qs("mobile");
$owner_name = func_read_qs("owner");
$rfq_description = func_read_qs("rfqdesc");

if($rfqid == ""){
	mysqli_query($con,"insert into request_for_quotation SET customer_name='".$customer_name."',customer_email='".$customer_email."',contact_no='".$contact_no."',owner_name='".$owner_name."',rfq_description='".$rfq_description."',creation_date=NOW()");
}else{
	if($subrfq == ""){
		execute_qry("update request_for_quotation SET customer_name='".$customer_name."',customer_email='".$customer_email."',contact_no='".$contact_no."',rfq_description='".$rfq_description."' where quotation_id=$rfqid");
	}
}
//$sql = func_insert_qry("request_for_quotation",$fld_arr1);

//execute_qry($sql);

//get_rst("select quotation_id from request_for_quotation where rfq_description ='$rfq_description'",$row);

$prod_name = func_read_qs("proddetail");
$brand_name = func_read_qs("brand");
$item_qty = func_read_qs("qty");
$item_price = func_read_qs("price");
$vendorname = func_read_qs("vendorname");
$rfq_status = func_read_qs("status");


if($rfqid == ""){
	$quotation_id = mysqli_insert_id($con);
}else{
	$quotation_id=$rfqid;
}

		if($rfq_status =='Closed'){
			if(get_rst("select quotation_id from quotation_mast where rfq_no=$quotation_id")){
				
			}else{
				$quot_no = "AM/PNQ/".$timestamp."/";
				get_rst("select count(0)+1 as next_code from quotation_mast where quotation_no LIKE '$quot_no%' and quotation_no is NOT null",$row);
				$quotation_no = "AM/PNQ/".$timestamp."/".str_pad($row["next_code"], 4-strlen($row["next_code"]), "0", STR_PAD_LEFT);
				mysqli_query($con, "insert into quotation_mast set customer_name='".$customer_name."',customer_email='".$customer_email."',quotation_no='".$quotation_no."',rfq_no='".$quotation_id."'");
				$quot_id = mysqli_insert_id($con);
				mysqli_query($con,"INSERT INTO quotation(quotation_id, item_no, item_desc, brand_name, qty, price, discount) VALUES ('$quot_id', '1', NULL, NULL, NULL, NULL, NULL)");
			}
		}

$path1=NULL;

if($pid == ""){
$itemno=1;
if(get_rst("select item_no from prod_enquiry where quotation_id=$quotation_id ORDER BY id DESC",$row_item)){
	$itemno=$row_item["item_no"] + 1;
}
}else{
	get_rst("select item_no from prod_enquiry where id=$pid",$row_item);
		$itemno=$row_item["item_no"];
		
	get_rst("select image_path1 from prod_enquiry where id=$pid",$row_path);
		$path1=$row_path["image_path1"];
}
	
$file_name=$_FILES['files']['name'];
 
 if($file_name == ""){
	 $file_path=$path1;
 }else{
	 
	 $desired_dir1=$_SERVER['DOCUMENT_ROOT']."/extras/request_quotation";
	 if(is_dir($desired_dir1)==false){
		mkdir($desired_dir1, 0777);		// Create directory if it does not exist
	}
 
 $desired_dir=$_SERVER['DOCUMENT_ROOT']."/extras/request_quotation/id-".$quotation_id."-".$itemno;
//print_r($desired_dir);die;
if(is_dir($desired_dir)==false){
		mkdir($desired_dir, 0777);		// Create directory if it does not exist
	}
	
//die;



$file_tmp =$_FILES['files']['tmp_name'];
//$file_path=$file_tmp;
$file_path1=$_SERVER['DOCUMENT_ROOT']."/extras/request_quotation/id-".$quotation_id."-".$itemno."/".$file_name;
move_uploaded_file($file_tmp,$file_path1);  
$file_path="http://".$_SERVER['SERVER_NAME']."/extras/request_quotation/id-".$quotation_id."-".$itemno."/".$file_name;
 }
//$fld_arr["image_path1"] = $file_path;



if($pid == ""){					 
	execute_qry("insert into prod_enquiry SET quotation_id='".$quotation_id."',item_no=$itemno,vendorname='".$vendorname."',rfq_status='".$rfq_status."',item_price='".$item_price."',item_qty='".$item_qty."',brand_name='".$brand_name."',prod_name='".$prod_name."',insertdate=NOW(),image_path1='".$file_path."'");
}else{
	execute_qry("update prod_enquiry SET quotation_id='".$quotation_id."',vendorname='".$vendorname."',rfq_status='".$rfq_status."',item_price='".$item_price."',item_qty='".$item_qty."',brand_name='".$brand_name."',prod_name='".$prod_name."',insertdate=NOW(),image_path1='".$file_path."' where id=$pid");

}
	$response['status']="success";
	$response['rfqid']=$quotation_id;
		


echo json_encode($response);  


?>