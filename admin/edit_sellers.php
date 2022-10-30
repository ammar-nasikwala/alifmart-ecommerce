<?php
require_once("inc_admin_header.php");
?>

<script>
	function Submit_form(){	
		if(chkForm(document.frm)==false){
			return false;
		}else{
			document.frm.act.value="1";
			document.frm.submit();
		}
	}
	
	function js_approve(){
		if(chkForm(document.frm)==false){
			return false;
		}else{
			document.frm.act.value="A";
			document.frm.submit();
		}
	}
</script>

<?php

$_SESSION["sup_id"] = func_read_qs("id");
$admin_login = "1";

if(func_read_qs("act")=="B" && isset($_POST["btn_resend"]) == false){
	$sup_id=$_SESSION["sup_id"];
	$qry1="select cart_item_id from cart_items where sup_id=$sup_id";
	//$qry2="select session_id from ord_summary where sup_id=$sup_id and  buyer_action <> 'Cancelled'";
	$rst = mysqli_query($con,"select session_id from ord_summary where sup_id=$sup_id and (delivery_status = 'Pending' or delivery_status = 'Ready-to-Ship' ) and buyer_action IS NULL ");
	if(get_rst($qry1,$row) || mysqli_num_rows($rst)>0){
		js_alert("Cannot delete Seller as his products are selected by buyers.");
	}else{
		$qry="select sup_alias from sup_mast where sup_id=$sup_id";
		get_rst($qry,$row);
		$sup_alias=$row["sup_alias"];
		execute_qry("delete FROM prod_sup where sup_id=$sup_id");
		execute_qry("update sup_mast set sup_delete_account=1, sup_contact_no='' where sup_id=$sup_id");
		execute_qry("delete FROM sup_ext_addr where sup_id=$sup_id");
		if($sup_alias <> ""){
		execute_qry("update sup_alias_name set status = 0 where sup_alias='$sup_alias'");
		execute_qry("update sup_mast set sup_alias= NULL where sup_id=$sup_id");
		}
		//header("location: manage_sellers.php");
		echo "<script>window.top.location='manage_sellers.php'</script>";
	}
}

require_once("../seller/inc_update_form.php");
$_SESSION["sup_id"] = "";

require_once("inc_admin_footer.php");

?>
