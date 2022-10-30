<?php

//print_r($_SERVER);die;
//header("Authorization:1234567890,X-APP-TOKEN:7d5dda4a849d41da80961b79ab6fd875");

/* $realm = 'Restricted area';

$users = array('admin' => 'mypass', 'guest' => 'guest');

if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Basic 112233 realm="'.$realm.'"');

    die('Text to send if user hits Cancel button');
}  */



include("db.php");
include("functions.php");
ini_set('display_errors', 1); 

$memb_id = func_read_qs("memb_id");

if(isset($_GET["id"])){
		$id = $_GET["id"];
		if(isset($_GET["dl"])){
			$qry = "delete from memb_ext_addr where addr_id=$id";
			$result=mysqli_query($con, $qry);
			if($result){
				$msg = "Record deleted successfully";
			}else{
				$msg = "Record delete failed. Please try after sometime";	
			}
			
			$response['msg']=$msg;
			echo json_encode($response);die;
		}
}

if(isset($_GET["act"])){		
		$ext_addr_name = $_GET["ext_addr_name"];	
		$ext_addr1 = $_GET["ext_addr1"];
		$ext_addr2 = $_GET["ext_addr2"];
		$ext_addr_state = $_GET["ext_addr_state"];	
		$ext_addr_city = $_GET["ext_addr_city"];
		$ext_addr_pin = $_GET["ext_addr_pin"];
		$ext_addr_contact = $_GET["ext_addr_contact"];
		
		
		$fld_arr = array();
		$fld_arr["memb_id"] = $memb_id;
		$fld_arr["ext_addr_name"] = $ext_addr_name;
		$fld_arr["ext_addr1"] = $ext_addr1;
		$fld_arr["ext_addr2"] = $ext_addr2;
		$fld_arr["ext_addr_state"] = $ext_addr_state;
		$fld_arr["ext_addr_city"] = $ext_addr_city;
		$fld_arr["ext_addr_pin"] = $ext_addr_pin;
		$fld_arr["ext_addr_contact"] = $ext_addr_contact;
		
		$qry = "";
		if(isset($_GET["ext_addr_default"])){
			execute_qry("update memb_ext_addr set ext_addr_default=0 where memb_id=$memb_id");
			$fld_arr["ext_addr_default"] = 1;
		}else{
			$fld_arr["ext_addr_default"] = 0;
		}
		
		if(isset($_GET["id"]))
		{
			$qry = func_update_qry("memb_ext_addr",$fld_arr," where addr_id=".$id);
		}else{
			$qry = func_insert_qry("memb_ext_addr",$fld_arr);
		}
		$result=mysqli_query($con, $qry);
		if($result){
			$msg = "Details saved successfully";
		}else{
			$msg = "Update failed! Please try again after some time or contact out customer support.";
		}

		if(!get_rst("select * from memb_ext_addr where ext_addr_default=1 and memb_id=$memb_id", $rw)){
			execute_qry("update memb_ext_addr set ext_addr_default=1 where memb_id=$memb_id LIMIT 1");

			//$fld_customer_arr['memb_add1']=$rw['ext_addr_name'];
			$fld_customer_arr['memb_add1']=$rw['ext_addr1'];
			$fld_customer_arr['memb_add2']=$rw['ext_addr2'];
			$fld_customer_arr['memb_state']=$rw['ext_addr_state'];
			$fld_customer_arr['memb_city']=$rw['ext_addr_city'];
			$fld_customer_arr['memb_postcode']=$rw['ext_addr_pin'];
			$fld_customer_arr['memb_tel']=$rw['ext_addr_contact'];
			
			$sqlIns = func_update_qry("member_mast",$fld_customer_arr," where memb_id=".$memb_id);
			mysqli_query($con, $sqlIns);		

		}	

		$response['msg']=$msg;
		echo json_encode($response);die;
	}
	

$sql=	"select *"; 
$sql = $sql." from memb_ext_addr where memb_id=$memb_id";		
	
if(isset($_GET["id"]))	
{
	$sql.=" and addr_id=$id";
}

//print_r($sql);die;
//print_r($sql);die;
$rs_find = mysqli_query($con,$sql);

//print_r($rs_find);die; 
$address_list['address_list']=array();

	if($rs_find){
		
		while($row = mysqli_fetch_assoc($rs_find)){
			
			//print_r($row);die; 
			$temp['addr_id'] = $row["addr_id"];
			$temp['ext_addr_name'] = $row["ext_addr_name"];
			$temp['ext_addr1'] = $row["ext_addr1"];
			$temp['ext_addr2'] = $row["ext_addr2"];
			if($temp['ext_addr2'] == Null){
				$temp['ext_addr2'] = "";
			}
			$temp['ext_addr_state'] = $row["ext_addr_state"];
			$temp['ext_addr_city'] = $row["ext_addr_city"];
			$temp['ext_addr_city'] = $row["ext_addr_city"];
			$temp['ext_addr_pin'] = $row["ext_addr_pin"];
			$temp['ext_addr_contact'] = $row["ext_addr_contact"];
			$temp['ext_addr_default'] = $row["ext_addr_default"];
			
			array_push($address_list['address_list'],$temp);
		}
		
	}
	
 echo json_encode($address_list);
 
?>