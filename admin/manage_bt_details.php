<?php
require_once("inc_admin_header.php");
if(func_read_qs("delivery_status") <> ""){
	$_SESSION["delivery_status"] = func_read_qs("delivery_status");
}
if(func_read_qs("pay_status") <> ""){
	$_SESSION["pay_status"] = func_read_qs("pay_status");
}
if(!isset($_SESSION["pay_status"]) || !isset($_SESSION["delivery_status"])){
	$_SESSION["pay_status"] = "All";
	$_SESSION["delivery_status"] = "All";
}

$sql="select os.cart_id";
$sql = $sql.",ord_no as 'Order No.'";
$sql = $sql.",ord_date as 'Date'";
$sql = $sql.",bill_name as 'Placed by'";
$sql = $sql.",sum(ord_total) as 'Order Value'";
$sql = $sql.",sum(item_count) as 'No. of Products'";
$sql = $sql.",pay_status as 'Payment Status'";
$sql = $sql.",delivery_status as 'Delivery Status'";
$sql = $sql." from ord_summary os join ord_details od on os.cart_id=od.cart_id and os.user_id=od.memb_id";

$sql_where = " where pay_method='BT' ";

if($_SESSION["delivery_status"]<>"All"){
 $delivery_status = $_SESSION["delivery_status"];
 $sql_where = $sql_where." and delivery_status = '$delivery_status'";
}

if($_SESSION["pay_status"]<>"All"){
	$pay_status = $_SESSION["pay_status"];
	$sql_where = $sql_where." and `pay_status`='$pay_status'";
}
$sql = $sql.$sql_where." group by ord_id order by ord_id desc";

?>

<script>
function js_submit(){
	document.getElementById('page').value='';
	document.frm_list.submit();
}

</script>

<table border="0" width="100%">
	<tr>
		<td><h2>Bank Transfer Order List</h2></td>
	</tr>

	<form name="frm_list" method="post" action="order_list.php">
    <tr>
        <td>

		Delivery:
		<select name="delivery_status" onchange="javascript: js_submit();">
			<?php func_option("All","All",$_SESSION["delivery_status"])?>
			<?php func_option("Pending","Pending",$_SESSION["delivery_status"])?>
			<?php func_option("Ready-to-Ship","Ready-to-Ship",$_SESSION["delivery_status"])?>
			<?php func_option("Dispatched","Dispatched",$_SESSION["delivery_status"])?>
			
			
		</select>
		 | 
		Payment:
		<select name="pay_status" onchange="javascript: js_submit();">
			<?php func_option("All","All",$_SESSION["pay_status"])?>
			<?php func_option("Pending","Pending",$_SESSION["pay_status"])?>
			<?php func_option("Paid","Paid",$_SESSION["pay_status"])?>
		</select>
		</td>
	</tr>
	</form>
	
    <tr>
        <td>
			<?php create_ord_list($sql,"edit_bt_details.php",20,"tbl_pages",5);?>
        </td>
    </tr>
</table>

<?php
	require_once("inc_admin_footer.php");
?>
	
