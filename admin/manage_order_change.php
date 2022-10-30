<?php
require_once("inc_admin_header.php");

$sql="select ord_id, sup_id";
$sql = $sql.",ord_no as 'Order No.'";
$sql = $sql.",ord_date as 'Date'";
$sql = $sql.",bill_name as 'Placed by'";
$sql = $sql.",ord_total as 'Order Value'";
$sql = $sql.",item_count as 'No. of Products'";
$sql = $sql.",pay_status as 'Payment Status'";
$sql = $sql." from ord_summary os join ord_details od on os.cart_id=od.cart_id and os.user_id=od.memb_id";


$sql_where = " where delivery_status='Pending' ";
$sql = $sql.$sql_where." order by ord_id desc"; ?>

<script>
function js_submit(){
	document.getElementById('page').value='';
	document.frm_list.submit();
}
</script>

<table border="0" width="100%">
	<tr>
		<td><h2>View Orders</h2></td>
	</tr>
    <tr>
        <td>
			<?php create_ord_list($sql,"edit_order_change.php",20,"tbl_pages",5);?>
        </td>
    </tr>
</table>

<?php require_once("inc_admin_footer.php"); ?>
