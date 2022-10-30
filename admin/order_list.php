<?php
require_once("inc_admin_header.php");
if(func_read_qs("ds") <> ""){
	$_SESSION["ds"] = func_read_qs("ds");
}
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
$sql="select ord_id, sup_id";
$sql = $sql.",ord_no as 'Order No.'";
$sql = $sql.",ord_date as 'Date'";
$sql = $sql.",bill_name as 'Placed by'";
$sql = $sql.",ord_total as 'Order Value'";
$sql = $sql.",item_count as 'No. of Products'";
$sql = $sql.",pay_method as 'Payment Type'";
$sql = $sql.",pay_status as 'Payment Status'";
$sql = $sql.",delivery_status as 'Delivery Status'";
$sql = $sql." from ord_summary os join ord_details od on os.cart_id=od.cart_id and os.user_id=od.memb_id";

//$sql = "select * from vw_ord_summary";

$sql_where = " where 1=1 ";

if($_SESSION["ds"] == 0){
	$sql = $sql." and pay_status='Paid' and delivery_status <> 'Dispatched' and delivery_status <> 'Delivered' and buyer_action is null";
}

if($_SESSION["delivery_status"]<>"All"){
 $delivery_status = $_SESSION["delivery_status"];
 $sql_where = $sql_where." and delivery_status = '$delivery_status'";
 $page = "Dispatch Orders";
}else{
 $page = "View Orders";		
}
if($_SESSION["pay_status"]<>"All"){
	$pay_status = $_SESSION["pay_status"];
	$sql_where = $sql_where." and `pay_status`='$pay_status'";
}
$sql = $sql.$sql_where." order by ord_id desc";

?>

<script>
function js_submit(){
	document.getElementById('page').value='';
	document.frm_list.submit();
}

</script>

<table border="0" width="100%">
	<tr>
		<td><h2>Order List</h2></td>
	</tr>

	<form name="frm_list" method="post" action="order_list.php">
    <tr>
        <td>
		<input type="hidden" name="page" id="page" value="<?=$page?>">

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
			<?php create_ord_list($sql,"order_details.php",20,"tbl_pages",5);?>
        </td>
    </tr>
</table>

<?php
	require_once("inc_admin_footer.php");
?>
	
