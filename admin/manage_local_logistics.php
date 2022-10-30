<?php
require_once("inc_admin_header.php");

	$sql="select ord_id, sup_id";
	$sql = $sql.",ord_no as 'Order No.'";
	$sql = $sql.",ord_date as 'Date'";
	$sql = $sql.",bill_name as 'Placed by'";
	$sql = $sql.",ord_total as 'Order Value'";
	$sql = $sql.",item_count as 'No. of Products'";
	$sql = $sql.",(select vendor_name from local_logistics_vendor where id=os.local_logistics_vendor_id) as 'Vendor'";
	$sql = $sql.",pay_status as 'Payment Status'";
	$sql = $sql.",delivery_status as 'Delivery Status'";
	$sql = $sql." from ord_summary os join ord_details od on os.cart_id=od.cart_id and os.user_id=od.memb_id";

//$sql = "select * from vw_ord_summary";

$sql_where = " where local_logistics=1 ";

$delivery_status = func_read_qs("delivery_status");
$pay_status = func_read_qs("pay_status");


if($delivery_status<>""){
 $sql_where = $sql_where." and delivery_status = '$delivery_status'";
 $page = "Dispatch Orders";
 }else{
 $page = "View Orders";		
}
if($pay_status<>"")	$sql_where = $sql_where." and `pay_status`='$pay_status'";

$sql = $sql.$sql_where." order by ord_id desc";
	
?>

<table border="0" width="100%">
	<tr>
		<td><h2>Manage Local Orders</h2></td>
	</tr>
	
    <tr>
        <td>
			<?php create_ord_list($sql,"edit_local_order.php",20,"tbl_pages",5);?>
        </td>
    </tr>
	
</table>
<?php
require_once("inc_admin_footer.php");

?>
